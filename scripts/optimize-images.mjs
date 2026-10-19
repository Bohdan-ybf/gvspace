import { mkdir, readdir, readFile, stat, writeFile } from "node:fs/promises";
import path from "node:path";
import process from "node:process";
import sharp from "sharp";

const ROOT = process.cwd();
const PUBLIC_DIR = path.join(ROOT, "public");
const REPORT_DIR = path.join(ROOT, "reports", "image-optimization");
const SOURCE_DIRS = [path.join(ROOT, "src"), path.join(ROOT, "wordpress")];
const RASTER_EXTENSIONS = new Set([".jpg", ".jpeg", ".png", ".webp", ".avif"]);
const TEXT_EXTENSIONS = new Set([
  ".css",
  ".graphql",
  ".html",
  ".js",
  ".json",
  ".jsx",
  ".md",
  ".mjs",
  ".php",
  ".ts",
  ".tsx",
]);
const MIN_BYTES = Number(process.env.IMAGE_MIN_BYTES ?? 500 * 1024);
const MIN_SAVINGS = Number(process.env.IMAGE_MIN_SAVINGS ?? 10);

const args = new Set(process.argv.slice(2));
const mode = args.has("--apply") ? "apply" : args.has("--dry-run") ? "dry-run" : "audit";

async function walk(directory, allowedExtensions) {
  const results = [];
  let entries;
  try {
    entries = await readdir(directory, { withFileTypes: true });
  } catch (error) {
    if (error.code === "ENOENT") return results;
    throw error;
  }

  for (const entry of entries) {
    const absolutePath = path.join(directory, entry.name);
    if (entry.isDirectory()) results.push(...(await walk(absolutePath, allowedExtensions)));
    else if (allowedExtensions.has(path.extname(entry.name).toLowerCase()))
      results.push(absolutePath);
  }
  return results;
}

function publicUrl(filePath) {
  return `/${path.relative(PUBLIC_DIR, filePath).split(path.sep).join("/")}`;
}

function formatBytes(bytes) {
  if (bytes < 1024) return `${bytes} B`;
  if (bytes < 1024 ** 2) return `${(bytes / 1024).toFixed(1)} KB`;
  return `${(bytes / 1024 ** 2).toFixed(2)} MB`;
}

async function sourceReferences(url) {
  const files = (await Promise.all(SOURCE_DIRS.map((dir) => walk(dir, TEXT_EXTENSIONS)))).flat();
  const references = [];
  for (const file of files) {
    const content = await readFile(file, "utf8");
    if (content.includes(url)) references.push(path.relative(ROOT, file).split(path.sep).join("/"));
  }
  return references;
}

async function replaceSourceReferences(replacements) {
  const files = (await Promise.all(SOURCE_DIRS.map((dir) => walk(dir, TEXT_EXTENSIONS)))).flat();
  let changedFiles = 0;
  let changedReferences = 0;

  for (const file of files) {
    const original = await readFile(file, "utf8");
    let updated = original;
    for (const { from, to } of replacements) {
      const occurrences = updated.split(from).length - 1;
      if (occurrences > 0) {
        updated = updated.split(from).join(to);
        changedReferences += occurrences;
      }
    }
    if (updated !== original) {
      await writeFile(file, updated);
      changedFiles += 1;
    }
  }

  return { changedFiles, changedReferences };
}

function encoderFor(metadata) {
  if (metadata.format === "avif") return null;
  if (metadata.format === "webp")
    return { extension: ".webp", options: { quality: 82, effort: 5 } };
  if (metadata.hasAlpha)
    return { extension: ".webp", options: { quality: 90, alphaQuality: 100, effort: 5 } };
  return { extension: ".webp", options: { quality: 83, effort: 5 } };
}

async function inspect(filePath, calculateCandidate) {
  const fileStat = await stat(filePath);
  const metadata = await sharp(filePath).metadata();
  const url = publicUrl(filePath);
  const item = {
    file: path.relative(ROOT, filePath).split(path.sep).join("/"),
    url,
    bytes: fileStat.size,
    size: formatBytes(fileStat.size),
    width: metadata.width ?? null,
    height: metadata.height ?? null,
    format: metadata.format ?? path.extname(filePath).slice(1),
    hasAlpha: metadata.hasAlpha ?? false,
    references: await sourceReferences(url),
    candidate: null,
  };

  if (!calculateCandidate || fileStat.size < MIN_BYTES) return item;
  const encoder = encoderFor(metadata);
  if (!encoder) return item;

  const outputPath = path.join(
    path.dirname(filePath),
    `${path.parse(filePath).name}${encoder.extension}`,
  );
  if (outputPath === filePath) return item;

  const buffer = await sharp(filePath).rotate().webp(encoder.options).toBuffer();
  const savings = ((fileStat.size - buffer.length) / fileStat.size) * 100;
  item.candidate = {
    file: path.relative(ROOT, outputPath).split(path.sep).join("/"),
    url: publicUrl(outputPath),
    bytes: buffer.length,
    size: formatBytes(buffer.length),
    savingsPercent: Number(savings.toFixed(1)),
    recommended: savings >= MIN_SAVINGS,
  };

  if (mode === "apply" && item.candidate.recommended) {
    try {
      await stat(outputPath);
      item.candidate.status = "skipped-existing-target";
    } catch (error) {
      if (error.code !== "ENOENT") throw error;
      await writeFile(outputPath, buffer, { flag: "wx" });
      item.candidate.status = "created-original-kept";
    }
  }
  return item;
}

function markdownReport(report) {
  const lines = [
    "# Image optimization report",
    "",
    `Mode: ${report.mode}`,
    `Generated: ${report.generatedAt}`,
    `Threshold: ${formatBytes(report.settings.minBytes)}`,
    `Minimum saving: ${report.settings.minSavingsPercent}%`,
    "",
    `Scanned raster files: ${report.summary.scanned}`,
    `Files above threshold: ${report.summary.heavy}`,
    `Recommended conversions: ${report.summary.recommended}`,
    `Potential saving: ${formatBytes(report.summary.potentialSavingsBytes)}`,
    "",
    "| Source | Current | Candidate | Saving | References |",
    "| --- | ---: | ---: | ---: | ---: |",
  ];
  for (const item of report.images.filter((image) => image.bytes >= report.settings.minBytes)) {
    lines.push(
      `| ${item.file} | ${item.size} | ${item.candidate?.size ?? "—"} | ${item.candidate ? `${item.candidate.savingsPercent}%` : "—"} | ${item.references.length} |`,
    );
  }
  return `${lines.join("\n")}\n`;
}

async function main() {
  if (mode === "apply" && !args.has("--apply")) throw new Error("Apply mode requires --apply.");
  const files = await walk(PUBLIC_DIR, RASTER_EXTENSIONS);
  const calculateCandidate = mode !== "audit";
  const images = [];
  for (const file of files) images.push(await inspect(file, calculateCandidate));
  images.sort((a, b) => b.bytes - a.bytes);

  const recommended = images.filter((item) => item.candidate?.recommended);
  const report = {
    generatedAt: new Date().toISOString(),
    mode,
    settings: { minBytes: MIN_BYTES, minSavingsPercent: MIN_SAVINGS },
    summary: {
      scanned: images.length,
      heavy: images.filter((item) => item.bytes >= MIN_BYTES).length,
      recommended: recommended.length,
      potentialSavingsBytes: recommended.reduce(
        (total, item) => total + item.bytes - item.candidate.bytes,
        0,
      ),
    },
    images,
  };

  if (mode === "apply") {
    const replacements = recommended
      .filter((item) => item.candidate.status === "created-original-kept")
      .map((item) => ({ from: item.url, to: item.candidate.url }));
    report.sourceUpdates = await replaceSourceReferences(replacements);
  }

  await mkdir(REPORT_DIR, { recursive: true });
  await writeFile(path.join(REPORT_DIR, `${mode}.json`), `${JSON.stringify(report, null, 2)}\n`);
  await writeFile(path.join(REPORT_DIR, `${mode}.md`), markdownReport(report));

  console.log(markdownReport(report));
  console.log(`Reports: ${path.relative(ROOT, REPORT_DIR)}`);
  if (mode === "apply") {
    console.log(
      `Updated ${report.sourceUpdates.changedReferences} references in ${report.sourceUpdates.changedFiles} source files.`,
    );
    console.log("Original images were kept for rollback.");
  }
}

main().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
