import type { Metadata } from "next";
import type { Locale } from "@/i18n";
import { getLocalizedUrl, marketList } from "@/markets";

export type SeoData = {
  title: string;
  description: string;
  h1: string;
  openGraphTitle: string;
  openGraphDescription: string;
  openGraphImage?: string;
  canonical?: string;
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
  canonical
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
    canonical: seo?.canonical?.trim() || undefined,
    datePublished: seo?.datePublished || undefined,
    dateModified: seo?.dateModified || undefined,
  };
}

const openGraphLocales: Record<Locale, string> = { uk: "uk_UA", en: "en_US" };

const allowedCanonicalHosts = new Set(marketList.map((market) => market.domain));

export function resolveCanonicalUrl(locale: Locale, pathname: string, override?: string): string {
  const native = getLocalizedUrl(locale, pathname);
  const value = override?.trim();
  if (!value) return native;
  if (value.startsWith("/")) return getLocalizedUrl(locale, value.split(/[?#]/)[0] || "/");

  try {
    const url = new URL(value);
    const host = url.hostname.toLowerCase().replace(/^www\./, "");
    if (
      (url.protocol !== "http:" && url.protocol !== "https:") ||
      !allowedCanonicalHosts.has(host)
    ) {
      return native;
    }
    url.hash = "";
    return url.toString();
  } catch {
    return native;
  }
}

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
  const nativeCanonical = getLocalizedUrl(locale, pathname);
  const canonical = resolveCanonicalUrl(locale, pathname, seo.canonical);
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
      ...(canonical === nativeCanonical
        ? {
            languages: {
              ...languages,
              "x-default": getLocalizedUrl("en", alternateLocales.includes("en") ? pathname : "/"),
            },
          }
        : {}),
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
