import type { Metadata } from "next";
import { notFound } from "next/navigation";
import { PrivacyPolicyPage } from "@/components/privacy-policy-page";
import { getPrivacyPolicy } from "@/components/wordpress-privacy-policy";
import { locales, type Locale } from "@/i18n";
import { buildSeoMetadata, normalizeSeoData } from "@/seo";

export const revalidate = 300;

export function generateStaticParams() {
  return locales.map((locale) => ({ locale }));
}

export async function generateMetadata({ params }: { params: Promise<{ locale: Locale }> }) {
  const { locale } = await params;
  const document = await getPrivacyPolicy(locale);
  const title =
    document?.title || (locale === "uk" ? "Політика конфіденційності" : "Privacy Policy");
  return buildSeoMetadata({
    locale,
    pathname: "/privacy-policy",
    seo: document?.seo ?? normalizeSeoData(undefined, { title, description: title }),
    alternateLocales: locales,
  }) satisfies Metadata;
}

export default async function PrivacyPage({ params }: { params: Promise<{ locale: Locale }> }) {
  const { locale } = await params;
  const document = await getPrivacyPolicy(locale);
  if (!document) notFound();
  return <PrivacyPolicyPage locale={locale} document={document} />;
}
