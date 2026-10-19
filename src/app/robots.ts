import type { MetadataRoute } from "next";
import { headers } from "next/headers";
import { getMarket, getMarketById } from "@/markets";

export const dynamic = "force-dynamic";

export default async function robots(): Promise<MetadataRoute.Robots> {
  const requestHeaders = await headers();
  const requestedMarket = getMarket(requestHeaders.get("host") ?? "");
  const market = requestedMarket?.enabled ? requestedMarket : getMarketById("international");
  const siteUrl = market.origin;
  const indexingEnabled = process.env.SITE_INDEXING_ENABLED === "true";
  const sitemapName = market.routeLocale === "uk" ? "sitemap-uk.xml" : "sitemap-en.xml";
  return {
    rules: indexingEnabled
      ? {
          userAgent: "*",
          allow: "/",
          disallow: ["/_next/", "/api/"],
        }
      : {
          userAgent: "*",
          disallow: "/",
        },
    ...(indexingEnabled ? { sitemap: `${siteUrl}/${sitemapName}` } : {}),
    host: siteUrl,
  };
}
