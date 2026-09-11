import { notFound } from "next/navigation";
import { Home } from "@/components/home";
import type { Metadata } from "next";
import { defaultLocale, isLocale, locales, type Locale } from "@/i18n";
import { getLocaleOrigin } from "@/markets";
import { buildSeoMetadata, normalizeSeoData } from "@/seo";
import { StructuredData } from "@/components/structured-data";

const localizedMetadata = {
  uk: {
    title: "GVSPACE — простір вашого масштабування",
    description:
      "Проєктуємо керовані системи маркетингу, IT та стратегії для масштабування бізнесу.",
  },
  en: {
    title: "GVSPACE — Space for your growth",
    description:
      "We design manageable marketing, IT, and strategy systems that help businesses scale.",
  },
} satisfies Record<Locale, { title: string; description: string }>;

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: string }>;
}): Promise<Metadata> {
  const { locale: localeParam } = await params;
  const locale = isLocale(localeParam) ? localeParam : defaultLocale;
  const pageMetadata = localizedMetadata[locale];
  return buildSeoMetadata({
    locale,
    pathname: "/",
    seo: normalizeSeoData(undefined, pageMetadata),
    alternateLocales: locales,
  });
}
export function generateStaticParams() {
  return locales.map((locale) => ({ locale }));
}
export default async function Page({ params }: { params: Promise<{ locale: string }> }) {
  const { locale } = await params;
  if (!isLocale(locale)) notFound();
  const siteUrl = getLocaleOrigin(locale);
  const schemas = [
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      name: "GVSPACE",
      url: siteUrl,
      logo: `${siteUrl}/icon.svg`,
    },
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      name: "GVSPACE",
      url: siteUrl,
      inLanguage: locale,
    },
  ];

  return (
    <>
      <StructuredData data={schemas} />
      <Home locale={locale} />
    </>
  );
}
