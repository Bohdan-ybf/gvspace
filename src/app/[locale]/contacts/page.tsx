import type { Metadata } from "next";
import { ContactsPage } from "@/components/contacts-page";
import { getContactsPage } from "@/components/wordpress-contacts";
import { locales, type Locale } from "@/i18n";
import { buildSeoMetadata, normalizeSeoData } from "@/seo";
import { StructuredData } from "@/components/structured-data";

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: Locale }>;
}): Promise<Metadata> {
  const { locale } = await params;
  const page = await getContactsPage(locale);
  return buildSeoMetadata({
    locale,
    pathname: "/contacts",
    seo:
      page.seo ??
      normalizeSeoData(undefined, {
        title: page.title,
        description: page.intro,
      }),
    alternateLocales: locales,
  });
}

export default async function ContactsRoute({ params }: { params: Promise<{ locale: Locale }> }) {
  const { locale } = await params;
  const page = await getContactsPage(locale);
  return (
    <>
      <StructuredData
        data={{
          "@context": "https://schema.org",
          "@type": "ContactPage",
          name: page.title,
          description: page.intro,
          mainEntity: {
            "@type": "Organization",
            name: "GVSPACE",
            email: page.channels.find((channel) => channel.kind === "email")?.value,
            telephone: page.channels.find((channel) => channel.kind === "phone")?.value,
          },
        }}
      />
      <ContactsPage locale={locale} />
    </>
  );
}
