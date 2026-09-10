"use client";

import { usePathname } from "next/navigation";
import type { Locale } from "@/i18n";
import { Footer } from "./footer";
import { Header } from "./header";

export function SiteShell({ children, locale }: { children: React.ReactNode; locale: Locale }) {
  const pathname = usePathname();
  const localizedPathname = pathname.startsWith(`/${locale}`)
    ? pathname
    : `/${locale}${pathname === "/" ? "" : pathname}`;
  const isHomePage = localizedPathname === `/${locale}` || localizedPathname === `/${locale}/`;
  const hasDarkHero =
    isHomePage ||
    localizedPathname === `/${locale}/services` ||
    localizedPathname === `/${locale}/cases` ||
    localizedPathname === `/${locale}/reviews` ||
    localizedPathname.startsWith(`/${locale}/cases/`) ||
    localizedPathname === `/${locale}/about` ||
    localizedPathname === `/${locale}/team` ||
    localizedPathname === `/${locale}/technologies` ||
    localizedPathname === `/${locale}/blog` ||
    (localizedPathname.startsWith(`/${locale}/blog/`) &&
      !localizedPathname.startsWith(`/${locale}/blog/author/`)) ||
    localizedPathname === `/${locale}/careers` ||
    localizedPathname.startsWith(`/${locale}/careers/`);

  return (
    <>
      <Header locale={locale} forceSolid={!hasDarkHero} />
      {children}
      <Footer locale={locale} />
    </>
  );
}
