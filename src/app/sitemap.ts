import type { MetadataRoute } from "next";
import { getLocaleOrigin } from "@/markets";
export default function sitemap(): MetadataRoute.Sitemap {
  if (process.env.SITE_INDEXING_ENABLED !== "true") return [];

  return ["uk", "en"].map((locale) => ({
    url: `${getLocaleOrigin(locale as "uk" | "en")}/${locale}`,
    changeFrequency: "weekly" as const,
    priority: 1,
    alternates: {
      languages: {
        uk: `${getLocaleOrigin("uk")}/uk`,
        en: `${getLocaleOrigin("en")}/en`,
      },
    },
  }));
}
