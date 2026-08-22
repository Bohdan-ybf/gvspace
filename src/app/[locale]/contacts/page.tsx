import type { Metadata } from "next";
import { ContactsPage } from "@/components/contacts-page";
import type { Locale } from "@/i18n";

export const metadata: Metadata = { title: "Контакти" };

export default async function ContactsRoute({ params }: { params: Promise<{ locale: Locale }> }) {
  const { locale } = await params;
  return <ContactsPage locale={locale} />;
}
