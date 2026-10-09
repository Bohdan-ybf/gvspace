import type { Locale } from "@/i18n";
import {
  filterPublishedForLocale,
  getPublicContentSlug,
  type ContentLocalization,
} from "@/content-localization";

export type CaseMetric = { value: string; label: string };
export type CaseVector = { title: string; description: string };
export type CasePerson = { name: string; role: string; photo: string };
export type CaseMedia = { url: string; kind: string };
export type CaseBlock = {
  layout: string;
  side: string;
  tone: string;
  heading: string;
  body: string;
  label: string;
  media: CaseMedia[];
};

export type CaseStudy = {
  slug: string;
  title: string;
  catalogTitle: string;
  excerpt: string;
  publishedAt?: string;
  modifiedAt?: string;
  result: string;
  services: string[];
  metrics: CaseMetric[];
  challenge: string;
  problems: string[];
  discovery: string;
  discoveryResult: string;
  step1: string;
  step1Result: CaseVector;
  step2: string;
  architecture: CaseVector[];
  step3: string;
  step3Result: CaseVector;
  gallery: string[];
  tasks: string[];
  documents: string[];
  team: CasePerson[];
  testimonial: string;
  testimonialAuthor: string;
  testimonialCompany: string;
  projectType: string;
  industry: string;
  direction: string;
  badge: string;
  logo: string;
  cover: string;
  coverKind: string;
  subtitle: string;
  lead: string;
  contextTitle: string;
  contextBody: string;
  blocks: CaseBlock[];
  market: string;
  period: string;
  status: string;
  serviceTags: string[];
  techTags: string[];
  goalsTitle: string;
  goals: CaseVector[];
  processTitle: string;
  step1Title: string;
  step2Title: string;
  step3Title: string;
  resultTitle: string;
  resultLead: string;
  mediaLabel: string;
  briefMedia: CaseMedia[];
  stepMedia: CaseMedia[];
  resultLayout: string;
  resultMedia: CaseMedia[];
  authorPhoto: string;
  image?: string;
};

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

type CaseDetails = Partial<
  Omit<CaseStudy, "slug" | "title" | "image" | "publishedAt" | "modifiedAt">
> & {
  title?: string;
};

type CaseNode = {
  slug: string;
  title: string;
  date?: string;
  modified?: string;
  menuOrder?: number | null;
  featuredImage?: { node?: { sourceUrl?: string } };
  caseDetails?: CaseDetails | null;
  gvspaceLocalization?: ContentLocalization | null;
};

const fields = `
  slug title date modified menuOrder
  gvspaceLocalization { locale translationGroup status }
  featuredImage { node { sourceUrl } }
  caseDetails(locale: $locale) {
    title catalogTitle excerpt result services
    metrics { value label }
    challenge problems discovery discoveryResult
    step1 step1Result { title description }
    step2 architecture { title description }
    step3 step3Result { title description }
    gallery tasks documents
    team { name role photo }
    testimonial testimonialAuthor testimonialCompany
    projectType industry direction badge
    logo cover coverKind subtitle lead contextTitle contextBody
    market period status serviceTags techTags
    goalsTitle goals { title description }
    processTitle step1Title step2Title step3Title
    resultTitle resultLead resultLayout mediaLabel
    briefMedia { url kind }
    stepMedia { url kind }
    resultMedia { url kind }
    authorPhoto
  }
`;

function emptyVector(vector?: CaseVector | null): CaseVector {
  return { title: vector?.title ?? "", description: vector?.description ?? "" };
}

function mapMedia(media?: CaseMedia[] | null): CaseMedia[] {
  return (media ?? []).map((item) => ({
    url: item?.url ?? "",
    kind: item?.kind ?? "",
  }));
}

function mapBlocks(blocks?: CaseBlock[] | null): CaseBlock[] {
  return (blocks ?? []).map((block) => ({
    layout: block?.layout ?? "text",
    side: block?.side === "right" ? "right" : "left",
    tone: block?.tone === "light" ? "light" : "dark",
    heading: block?.heading ?? "",
    body: block?.body ?? "",
    label: block?.label ?? "",
    media: mapMedia(block?.media),
  }));
}

function mapCase(node: CaseNode): CaseStudy | undefined {
  const details = node.caseDetails;
  if (!details) return undefined;
  const title = details.title || node.title;
  const excerpt = details.excerpt || details.result || "";
  return {
    slug: getPublicContentSlug(node.slug, node.gvspaceLocalization),
    title,
    catalogTitle: details.catalogTitle || title,
    excerpt,
    publishedAt: node.date,
    modifiedAt: node.modified,
    result: excerpt,
    services: details.services ?? [],
    metrics: details.metrics ?? [],
    challenge: details.challenge ?? "",
    problems: details.problems ?? [],
    discovery: details.discovery || details.step1 || "",
    discoveryResult: details.discoveryResult || details.step1Result?.description || "",
    step1: details.step1 || details.discovery || "",
    step1Result: emptyVector(details.step1Result),
    step2: details.step2 ?? "",
    architecture: details.architecture ?? [],
    step3: details.step3 ?? "",
    step3Result: emptyVector(details.step3Result),
    gallery: details.gallery ?? [],
    tasks: details.tasks ?? [],
    documents: details.documents ?? [],
    team: details.team ?? [],
    testimonial: details.testimonial ?? "",
    testimonialAuthor: details.testimonialAuthor ?? "",
    testimonialCompany: details.testimonialCompany ?? "",
    projectType: details.projectType ?? "",
    industry: details.industry ?? "",
    direction: details.direction || details.badge || "",
    badge: details.badge || details.direction || "",
    logo: details.logo ?? "",
    cover: details.cover ?? "",
    coverKind: details.coverKind ?? "",
    subtitle: details.subtitle || excerpt,
    lead: details.lead ?? "",
    contextTitle: details.contextTitle ?? "",
    contextBody: details.contextBody ?? "",
    blocks: mapBlocks(details.blocks),
    market: details.market ?? "",
    period: details.period ?? "",
    status: details.status ?? "",
    serviceTags: details.serviceTags ?? [],
    techTags: details.techTags ?? [],
    goalsTitle: details.goalsTitle ?? "",
    goals: (details.goals ?? []).map((goal) => emptyVector(goal)),
    processTitle: details.processTitle ?? "",
    step1Title: details.step1Title ?? "",
    step2Title: details.step2Title ?? "",
    step3Title: details.step3Title ?? "",
    resultTitle: details.resultTitle ?? "",
    resultLead: details.resultLead ?? "",
    resultLayout:
      details.resultLayout === "stack-tall" || details.resultLayout === "two-three"
        ? details.resultLayout
        : "wide-two",
    mediaLabel: details.mediaLabel ?? "",
    briefMedia: mapMedia(details.briefMedia),
    stepMedia: mapMedia(details.stepMedia),
    resultMedia: mapMedia(details.resultMedia),
    authorPhoto: details.authorPhoto ?? "",
    image: node.featuredImage?.node?.sourceUrl,
  };
}

export async function getCaseStudies(locale: Locale): Promise<CaseStudy[]> {
  if (!endpoint) return [];
  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query CasesV2($locale: String!) { projectCases(first: 100) { nodes { ${fields} } } }`,
        variables: { locale },
      }),
      next: { revalidate: 60 },
    });
    if (!response.ok) return [];
    const result = (await response.json()) as {
      data?: { projectCases?: { nodes?: CaseNode[] } };
      errors?: unknown[];
    };
    if (result.errors) return [];
    return filterPublishedForLocale(result.data?.projectCases?.nodes ?? [], locale)
      .sort((left, right) => (left.menuOrder ?? 0) - (right.menuOrder ?? 0))
      .map(mapCase)
      .filter((item): item is CaseStudy => Boolean(item));
  } catch {
    return [];
  }
}

export async function getCaseStudy(slug: string, locale: Locale): Promise<CaseStudy | undefined> {
  const cases = await getCaseStudies(locale);
  return cases.find((item) => item.slug === slug);
}
