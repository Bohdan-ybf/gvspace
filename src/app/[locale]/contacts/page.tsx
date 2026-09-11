import type { Metadata } from "next";
import { ContactsPage } from "@/components/contacts-page";
import { locales, type Locale } from "@/i18n";
import { buildSeoMetadata, normalizeSeoData } from "@/seo";
import { Breadcrumbs } from "@/components/breadcrumbs";
import { StructuredData } from "@/components/structured-data";

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: Locale }>;
}): Promise<Metadata> {
  const { locale } = await params;
  const content = {
    uk: ["Контакти GVSPACE", "Зв’яжіться з командою GVSPACE, щоб обговорити ваш проєкт."],
    en: ["Contact GVSPACE", "Contact the GVSPACE team to discuss your project."],
  } as const;
  return buildSeoMetadata({
    locale,
    pathname: "/contacts",
    seo: normalizeSeoData(undefined, {
      title: content[locale][0],
      description: content[locale][1],
    }),
    alternateLocales: locales,
  });
}

export default async function ContactsRoute({ params }: { params: Promise<{ locale: Locale }> }) {
  const { locale } = await params;
  return (
    <>
      <StructuredData
        data={{
          "@context": "https://schema.org",
          "@type": "ContactPage",
          name: locale === "uk" ? "Контакти GVSPACE" : "Contact GVSPACE",
          mainEntity: { "@type": "Organization", name: "GVSPACE" },
        }}
      />
      <Breadcrumbs locale={locale} items={[{ label: locale === "uk" ? "Контакти" : "Contacts" }]} />
      <ContactsPage locale={locale} />
    </>
  );
}
