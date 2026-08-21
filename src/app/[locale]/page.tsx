import { notFound } from "next/navigation";
import { Home } from "@/components/home";
import type { Metadata } from "next";
import { isLocale } from "@/i18n";

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: string }>;
}): Promise<Metadata> {
  const { locale } = await params;
  const isEnglish = locale === "en";

  return {
    title: isEnglish ? "GVSPACE — Space for your growth" : "GVSPACE — простір вашого масштабування",
    description: isEnglish
      ? "We design manageable marketing, IT, and strategy systems that help businesses scale."
      : "Проєктуємо керовані системи маркетингу, IT та стратегії для масштабування бізнесу.",
    alternates: {
      canonical: `/${locale}`,
      languages: { uk: "/uk", en: "/en", "x-default": "/uk" },
    },
    openGraph: {
      locale: isEnglish ? "en_US" : "uk_UA",
      alternateLocale: isEnglish ? ["uk_UA"] : ["en_US"],
    },
  };
}
export function generateStaticParams() {
  return [{ locale: "uk" }, { locale: "en" }];
}
export default async function Page({ params }: { params: Promise<{ locale: string }> }) {
  const { locale } = await params;
  if (!isLocale(locale)) notFound();
  const siteUrl = process.env.NEXT_PUBLIC_SITE_URL || "https://gvspace.com";
  const organizationSchema = {
    "@context": "https://schema.org",
    "@type": "Organization",
    name: "GVSPACE",
    url: `${siteUrl}/${locale}`,
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
