import { notFound } from "next/navigation";
import { Home } from "@/components/home";
import type { Metadata } from "next";
import { defaultLocale, isLocale, locales, type Locale } from "@/i18n";
import { getLocaleOrigin } from "@/markets";

const localizedMetadata = {
  uk: {
    title: "GVSPACE — простір вашого масштабування",
    description:
      "Проєктуємо керовані системи маркетингу, IT та стратегії для масштабування бізнесу.",
    openGraphLocale: "uk_UA",
  },
  en: {
    title: "GVSPACE — Space for your growth",
    description:
      "We design manageable marketing, IT, and strategy systems that help businesses scale.",
    openGraphLocale: "en_US",
  },
} satisfies Record<Locale, { title: string; description: string; openGraphLocale: string }>;

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: string }>;
}): Promise<Metadata> {
  const { locale: localeParam } = await params;
  const locale = isLocale(localeParam) ? localeParam : defaultLocale;
  const pageMetadata = localizedMetadata[locale];
  const canonicalUrl = getLocaleOrigin(locale);

  return {
    title: pageMetadata.title,
    description: pageMetadata.description,
    alternates: {
      canonical: canonicalUrl,
      languages: {
        ...Object.fromEntries(locales.map((language) => [language, getLocaleOrigin(language)])),
        "x-default": getLocaleOrigin("en"),
      },
    },
    openGraph: {
      locale: pageMetadata.openGraphLocale,
      alternateLocale: locales
        .filter((language) => language !== locale)
        .map((language) => localizedMetadata[language].openGraphLocale),
    },
  };
}
export function generateStaticParams() {
  return locales.map((locale) => ({ locale }));
}
export default async function Page({ params }: { params: Promise<{ locale: string }> }) {
  const { locale } = await params;
  if (!isLocale(locale)) notFound();
  const siteUrl = getLocaleOrigin(locale);
  const organizationSchema = {
    "@context": "https://schema.org",
    "@type": "Organization",
    name: "GVSPACE",
    url: siteUrl,
    logo: `${siteUrl}/icon.svg`,
  };

  return (
    <>
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{
          __html: JSON.stringify(organizationSchema).replace(/</g, "\\u003c"),
        }}
      />
      <Home locale={locale} />
    </>
  );
}
