import type { Metadata } from "next";
import { ContactsPage } from "@/components/contacts-page";

export const metadata: Metadata = { title: "Контакти" };

export default async function ContactsRoute({ params }: { params: Promise<{ locale: string }> }) {
  const { locale } = await params;
  return <ContactsPage locale={locale} />;
}
