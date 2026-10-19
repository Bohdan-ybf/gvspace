import { readFile } from "node:fs/promises";
import { join } from "node:path";
import { type NextRequest, NextResponse } from "next/server";
import { getMarket } from "@/markets";

export const dynamic = "force-dynamic";

export async function GET(request: NextRequest) {
  const market = getMarket(request.headers.get("host") ?? "");
  if (!market?.enabled) return new NextResponse("Not found", { status: 404 });

  const filePath = join(process.cwd(), "public", "llms", `${market.contentLocale}.txt`);
  try {
    const body = await readFile(filePath, "utf8");
    return new NextResponse(body, {
      headers: {
        "Content-Type": "text/plain; charset=utf-8",
        "Cache-Control": "public, max-age=300, s-maxage=3600",
      },
    });
  } catch {
    // Do not publish a placeholder: a missing approved file should remain unavailable.
    return new NextResponse("Not found", { status: 404 });
  }
}
