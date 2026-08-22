import type { Metadata } from "next";
import { LegalPage } from "@/components/legal-page";
import type { Locale } from "@/i18n";

export const metadata: Metadata = { title: "Terms of Use", robots: { index: false, follow: true } };

export default async function TermsPage({ params }: { params: Promise<{ locale: Locale }> }) {
  const { locale } = await params;
  return <LegalPage locale={locale} kind="terms" />;
}
