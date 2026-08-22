import type { Metadata } from "next";
import { LegalPage } from "@/components/legal-page";
import type { Locale } from "@/i18n";

export const metadata: Metadata = {
  title: "Privacy Policy",
  robots: { index: false, follow: true },
};

export default async function PrivacyPage({ params }: { params: Promise<{ locale: Locale }> }) {
  const { locale } = await params;
  return <LegalPage locale={locale} kind="privacy" />;
}
