import type { MetadataRoute } from "next";
import { headers } from "next/headers";
import { getMarket, getMarketById } from "@/markets";
import { getLocaleSitemapEntries } from "@/sitemap-data";

export const dynamic = "force-dynamic";

export default async function sitemap(): Promise<MetadataRoute.Sitemap> {
  const requestHeaders = await headers();
  const requestedMarket = getMarket(requestHeaders.get("host") ?? "");
  const currentMarket =
    requestedMarket?.enabled && requestedMarket.routeLocale
      ? requestedMarket
      : getMarketById("international");
  if (!currentMarket.routeLocale) return [];

  const entries = await getLocaleSitemapEntries(currentMarket.routeLocale);
  return entries.map(({ pathname, lastModified }) => ({
    url: `${currentMarket.origin}${pathname === "/" ? "" : pathname}`,
    ...(lastModified ? { lastModified } : {}),
  }));
}
