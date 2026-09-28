import Link from "next/link";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";
import { Breadcrumbs } from "./breadcrumbs";

export function SitemapPage({ locale }: { locale: Locale }) {
  const { footer, navigation } = getTranslations("global", locale);
  const columns = [
    footer.services,
    footer.company,
    footer.resources,
    {
      title: footer.contactsTitle,
      links: [
        { label: navigation[5], href: "/contacts" },
        { label: footer.sitemap, href: "/sitemap" },
        { label: footer.privacy, href: "/privacy-policy" },
        { label: footer.terms, href: "/terms-of-use" },
      ],
    },
  ];

  return (
    <main className="sitemap-page">
      <div className="container">
        <h1>{footer.sitemap}</h1>
        <div className="sitemap-grid">
          {columns.map((column) => (
            <section key={column.title}>
              <h2>{column.title}</h2>
              {column.links.map((item) => (
                <Link key={item.href} href={`/${locale}${item.href}`}>
                  {item.label}
                </Link>
              ))}
            </section>
          ))}
        </div>
      </div>
      <Breadcrumbs locale={locale} visible items={[{ label: footer.sitemap }]} />
    </main>
  );
}
