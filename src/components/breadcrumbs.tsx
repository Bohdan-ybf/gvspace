import Link from "next/link";
import type { Locale } from "@/i18n";
import { getLocalizedUrl } from "@/markets";
import { StructuredData } from "./structured-data";

export type BreadcrumbItem = { label: string; pathname?: string };

export function Breadcrumbs({
  locale,
  items,
  visible = false,
}: {
  locale: Locale;
  items: BreadcrumbItem[];
  visible?: boolean;
}) {
  const homeLabel = locale === "uk" ? "Головна" : "Home";
  const allItems = [{ label: homeLabel, pathname: "/" }, ...items];
  const schema = {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    itemListElement: allItems.map((item, index) => ({
      "@type": "ListItem",
      position: index + 1,
      name: item.label,
      ...(item.pathname ? { item: getLocalizedUrl(locale, item.pathname) } : {}),
    })),
  };

  return (
    <>
      <StructuredData data={schema} />
      <nav
        className={`seo-breadcrumbs container mono${visible ? "" : " sr-only"}`}
        aria-label="Breadcrumb"
      >
        {allItems.map((item, index) => (
          <span key={`${item.label}-${index}`}>
            {index > 0 && <span aria-hidden="true">/</span>}
            {item.pathname && index < allItems.length - 1 ? (
              <Link href={`/${locale}${item.pathname === "/" ? "" : item.pathname}`}>
                {item.label}
              </Link>
            ) : (
              <span aria-current={index === allItems.length - 1 ? "page" : undefined}>
                {item.label}
              </span>
            )}
          </span>
        ))}
      </nav>
    </>
  );
}
