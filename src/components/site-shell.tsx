"use client";

import { usePathname } from "next/navigation";
import type { Locale } from "@/i18n";
import { Footer } from "./footer";
import { Header } from "./header";
import type { ServiceOffering } from "./wordpress-services";

export function SiteShell({
  children,
  locale,
  services,
}: {
  children: React.ReactNode;
  locale: Locale;
  services: ServiceOffering[];
}) {
  const pathname = usePathname();
  const localizedPathname = pathname.startsWith(`/${locale}`)
    ? pathname
    : `/${locale}${pathname === "/" ? "" : pathname}`;
  const pathSegments = localizedPathname.split("/").filter(Boolean);
  const isHomePage = localizedPathname === `/${locale}` || localizedPathname === `/${locale}/`;
  const isServiceDetail = pathSegments[1] === "services" && pathSegments.length >= 3;
  const isTechnologyCatalog = localizedPathname === `/${locale}/technologies`;
  const hasDarkHero =
    isHomePage ||
    localizedPathname === `/${locale}/services` ||
    isServiceDetail ||
    localizedPathname === `/${locale}/cases` ||
    localizedPathname === `/${locale}/reviews` ||
    localizedPathname.startsWith(`/${locale}/cases/`) ||
    localizedPathname === `/${locale}/about` ||
    localizedPathname === `/${locale}/team` ||
    isTechnologyCatalog ||
    localizedPathname === `/${locale}/blog` ||
    (localizedPathname.startsWith(`/${locale}/blog/`) &&
      !localizedPathname.startsWith(`/${locale}/blog/author/`)) ||
    localizedPathname === `/${locale}/careers` ||
    localizedPathname.startsWith(`/${locale}/careers/`);

  return (
    <>
      <Header locale={locale} forceSolid={!hasDarkHero} services={services} />
      {children}
      <Footer locale={locale} />
    </>
  );
}
