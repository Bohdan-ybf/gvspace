import type { Metadata } from "next";
import { LegalPage } from "@/components/legal-page";
import { locales, type Locale } from "@/i18n";
import { buildSeoMetadata, normalizeSeoData } from "@/seo";
import { Breadcrumbs } from "@/components/breadcrumbs";

export async function generateMetadata({ params }: { params: Promise<{ locale: Locale }> }) {
  const { locale } = await params;
  const title = locale === "uk" ? "Політика конфіденційності" : "Privacy Policy";
  return {
    ...buildSeoMetadata({
      locale,
      pathname: "/privacy-policy",
      seo: normalizeSeoData(undefined, { title, description: title }),
      alternateLocales: locales,
    }),
    robots: { index: false, follow: true },
  } satisfies Metadata;
}

export default async function PrivacyPage({ params }: { params: Promise<{ locale: Locale }> }) {
  const { locale } = await params;
  return (
    <>
      <Breadcrumbs
        locale={locale}
        items={[{ label: locale === "uk" ? "Політика конфіденційності" : "Privacy Policy" }]}
      />
      <LegalPage locale={locale} kind="privacy" />
    </>
  );
}
