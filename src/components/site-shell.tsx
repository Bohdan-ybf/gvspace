import { Suspense } from "react";
import type { Locale } from "@/i18n";
import { Footer } from "./footer";
import { Header } from "./header";
import { LeadModalProvider } from "./lead-modal";
import { getContactsPage, toFooterContacts } from "./wordpress-contacts";
import { getServiceOfferings } from "./wordpress-services";

async function HeaderSlot({ locale }: { locale: Locale }) {
  const services = await getServiceOfferings(locale);
  return <Header locale={locale} services={services} />;
}

async function FooterSlot({ locale }: { locale: Locale }) {
  const [contactsPage, services] = await Promise.all([
    getContactsPage(locale),
    getServiceOfferings(locale),
  ]);
  return <Footer locale={locale} contacts={toFooterContacts(contactsPage)} services={services} />;
}

export function SiteShell({ children, locale }: { children: React.ReactNode; locale: Locale }) {
  return (
    <LeadModalProvider locale={locale}>
      <Suspense fallback={<Header locale={locale} services={[]} />}>
        <HeaderSlot locale={locale} />
      </Suspense>
      {children}
      <Suspense fallback={null}>
        <FooterSlot locale={locale} />
      </Suspense>
    </LeadModalProvider>
  );
}
