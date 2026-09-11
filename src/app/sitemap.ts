import type { MetadataRoute } from "next";
import { getEnabledMarkets, getLocaleOrigin } from "@/markets";
export default function sitemap(): MetadataRoute.Sitemap {
  if (process.env.SITE_INDEXING_ENABLED !== "true") return [];

  const enabledMarkets = getEnabledMarkets();
  const languageAlternates = Object.fromEntries(
    enabledMarkets.map((market) => [market.contentLocale, market.origin]),
  );

  return enabledMarkets.map((market) => ({
    url: market.origin,
    changeFrequency: "weekly" as const,
    priority: 1,
    alternates: {
      languages: {
        ...languageAlternates,
        "x-default": getLocaleOrigin("en"),
      },
    },
  }));
}
