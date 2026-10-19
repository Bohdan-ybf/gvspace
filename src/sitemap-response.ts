import { getMarketById, type MarketId } from "@/markets";
import { getLocaleSitemapEntries } from "@/sitemap-data";

function escapeXml(value: string): string {
  return value
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&apos;");
}

export async function createLanguageSitemap(marketId: MarketId): Promise<Response> {
  const market = getMarketById(marketId);
  if (!market.enabled || !market.routeLocale) return new Response("Not found", { status: 404 });

  const entries = await getLocaleSitemapEntries(market.routeLocale);
  const urls = entries
    .map(({ pathname, lastModified }) => {
      const location = `${market.origin}${pathname === "/" ? "" : pathname}`;
      return [
        "  <url>",
        `    <loc>${escapeXml(location)}</loc>`,
        ...(lastModified ? [`    <lastmod>${escapeXml(lastModified)}</lastmod>`] : []),
        "  </url>",
      ].join("\n");
    })
    .join("\n");
  const xml = `<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n${urls}\n</urlset>\n`;

  return new Response(xml, {
    headers: {
      "Content-Type": "application/xml; charset=utf-8",
      "Cache-Control": "public, s-maxage=3600, stale-while-revalidate=86400",
    },
  });
}
