import type { Metadata } from "next";
import { SitemapPage } from "@/components/sitemap-page";
import { locales, type Locale } from "@/i18n";
import { buildSeoMetadata, normalizeSeoData } from "@/seo";

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: Locale }>;
}): Promise<Metadata> {
  const { locale } = await params;
  const title = locale === "uk" ? "Мапа сайту" : "Sitemap";
  const description =
    locale === "uk"
      ? "Усі розділи сайту GVSPACE: послуги, компанія, ресурси та контакти."
      : "All GVSPACE sections: services, company, resources, and contacts.";

  return buildSeoMetadata({
    locale,
    pathname: "/sitemap",
    seo: normalizeSeoData(undefined, { title, description }),
    alternateLocales: locales,
  });
}

export default async function Page({ params }: { params: Promise<{ locale: Locale }> }) {
  const { locale } = await params;
  return <SitemapPage locale={locale} />;
}
