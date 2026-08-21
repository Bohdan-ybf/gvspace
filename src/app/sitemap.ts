import type { MetadataRoute } from "next";
export default function sitemap(): MetadataRoute.Sitemap {
  const siteUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "https://gvspace.com";
  return ["uk", "en"].map((locale) => ({
    url: `${siteUrl}/${locale}`,
    changeFrequency: "weekly" as const,
    priority: 1,
    alternates: {
      languages: {
        uk: `${siteUrl}/uk`,
        en: `${siteUrl}/en`,
      },
    },
  }));
}
