import type { Metadata } from "next";
import { LegalPage } from "@/components/legal-page";
import { locales, type Locale } from "@/i18n";
import { buildSeoMetadata, normalizeSeoData } from "@/seo";
import { Breadcrumbs } from "@/components/breadcrumbs";

export async function generateMetadata({ params }: { params: Promise<{ locale: Locale }> }) {
  const { locale } = await params;
  const title = locale === "uk" ? "Умови використання" : "Terms of Use";
  return {
    ...buildSeoMetadata({
      locale,
      pathname: "/terms-of-use",
      seo: normalizeSeoData(undefined, { title, description: title }),
      alternateLocales: locales,
    }),
    robots: { index: false, follow: true },
  } satisfies Metadata;
}

export default async function TermsPage({ params }: { params: Promise<{ locale: Locale }> }) {
  const { locale } = await params;
  return (
    <>
      <Breadcrumbs
        locale={locale}
        items={[{ label: locale === "uk" ? "Умови використання" : "Terms of Use" }]}
      />
      <LegalPage locale={locale} kind="terms" />
    </>
  );
}
