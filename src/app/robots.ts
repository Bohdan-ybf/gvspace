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

  if (!indexingEnabled) {
    return {
      rules: {
        userAgent: "*",
        disallow: "/",
      },
    };
  }

  return {
    rules: {
      userAgent: "*",
      allow: "/",
      disallow: ["/_next/", "/api/"],
    },
    sitemap: `${siteUrl}/sitemap.xml`,
    host: siteUrl,
  };
}
