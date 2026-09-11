import type { Metadata } from "next";
import type { Locale } from "@/i18n";
import { getLocalizedUrl } from "@/markets";

export type SeoData = {
  title: string;
  description: string;
  h1: string;
  openGraphTitle: string;
  openGraphDescription: string;
  openGraphImage?: string;
  datePublished?: string;
  dateModified?: string;
};

export type SeoGraphqlData = Partial<SeoData> | null | undefined;

export const seoGraphqlFields = `
  title
  description
  h1
  openGraphTitle
  openGraphDescription
  openGraphImage
  datePublished
  dateModified
`;

export function normalizeSeoData(
  seo: SeoGraphqlData,
  fallback: Pick<SeoData, "title" | "description">,
): SeoData {
  const title = seo?.title?.trim() || fallback.title;
  const description = seo?.description?.trim() || fallback.description;

  return {
    title,
    description,
    h1: seo?.h1?.trim() || title,
    openGraphTitle: seo?.openGraphTitle?.trim() || title,
    openGraphDescription: seo?.openGraphDescription?.trim() || description,
    openGraphImage: seo?.openGraphImage?.trim() || undefined,
    datePublished: seo?.datePublished || undefined,
    dateModified: seo?.dateModified || undefined,
  };
}

const openGraphLocales: Record<Locale, string> = { uk: "uk_UA", en: "en_US" };

export function buildSeoMetadata({
  locale,
  pathname,
  seo,
  alternateLocales,
  type = "website",
}: {
  locale: Locale;
  pathname: string;
  seo: SeoData;
  alternateLocales: readonly Locale[];
  type?: "website" | "article";
}): Metadata {
  const canonical = getLocalizedUrl(locale, pathname);
  const languages = Object.fromEntries(
    alternateLocales.map((alternateLocale) => [
      alternateLocale,
      getLocalizedUrl(alternateLocale, pathname),
    ]),
  );

  return {
    title: seo.title,
    description: seo.description,
    alternates: {
      canonical,
      languages: {
        ...languages,
        "x-default": getLocalizedUrl(
          "en",
          alternateLocales.includes("en") ? pathname : "/",
        ),
      },
    },
    openGraph: {
      type,
      title: seo.openGraphTitle,
      description: seo.openGraphDescription,
      url: canonical,
      siteName: "GVSPACE",
      locale: openGraphLocales[locale],
      alternateLocale: alternateLocales
        .filter((alternateLocale) => alternateLocale !== locale)
        .map((alternateLocale) => openGraphLocales[alternateLocale]),
      images: seo.openGraphImage
        ? [{ url: seo.openGraphImage, alt: seo.openGraphTitle }]
        : undefined,
      ...(type === "article"
        ? { publishedTime: seo.datePublished, modifiedTime: seo.dateModified }
        : {}),
    },
    twitter: {
      card: "summary_large_image",
      title: seo.openGraphTitle,
      description: seo.openGraphDescription,
      images: seo.openGraphImage ? [seo.openGraphImage] : undefined,
    },
  };
}
