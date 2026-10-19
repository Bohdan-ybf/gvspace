import { Suspense } from "react";
import type { Locale } from "@/i18n";
import { Footer } from "./footer";
import { Header } from "./header";
import { LeadModalProvider } from "./lead-modal";
import { getContactsPage, toFooterContacts } from "./wordpress-contacts";
import { getServiceMenuItems } from "./wordpress-services";

async function FooterSlot({ locale }: { locale: Locale }) {
  const [contactsPage, services] = await Promise.all([
    getContactsPage(locale),
    getServiceMenuItems(locale),
  ]);
  return <Footer locale={locale} contacts={toFooterContacts(contactsPage)} services={services} />;
}

export async function SiteShell({
  children,
  locale,
}: {
  children: React.ReactNode;
  locale: Locale;
}) {
  const services = await getServiceMenuItems(locale);

  return (
    <LeadModalProvider locale={locale}>
      <Header locale={locale} services={services} />
      {children}
      <Suspense fallback={null}>
        <FooterSlot locale={locale} />
      </Suspense>
    </LeadModalProvider>
  );
}
