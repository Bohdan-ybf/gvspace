import type { Metadata } from "next";
import { notFound } from "next/navigation";
import { PrivacyPolicyPage } from "@/components/privacy-policy-page";
import { getTermsOfUse } from "@/components/wordpress-terms-of-use";
import { locales, type Locale } from "@/i18n";
import { buildSeoMetadata, normalizeSeoData } from "@/seo";

export async function generateMetadata({ params }: { params: Promise<{ locale: Locale }> }) {
  const { locale } = await params;
  const document = await getTermsOfUse(locale);
  const title =
    document?.title || (locale === "uk" ? "Правила використання сайту" : "Website Terms of Use");
  return buildSeoMetadata({
    locale,
    pathname: "/terms-of-use",
    seo: document?.seo ?? normalizeSeoData(undefined, { title, description: title }),
    alternateLocales: locales,
  }) satisfies Metadata;
}

export default async function TermsPage({ params }: { params: Promise<{ locale: Locale }> }) {
  const { locale } = await params;
  const document = await getTermsOfUse(locale);
  if (!document) notFound();
  return <PrivacyPolicyPage locale={locale} document={document} />;
}
