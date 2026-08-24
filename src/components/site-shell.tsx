"use client";

import { usePathname } from "next/navigation";
import type { Locale } from "@/i18n";
import { Footer } from "./footer";
import { Header } from "./header";

export function SiteShell({ children, locale }: { children: React.ReactNode; locale: Locale }) {
  const pathname = usePathname();
  const isHomePage = pathname === `/${locale}` || pathname === `/${locale}/`;
  const hasDarkHero =
    isHomePage ||
    pathname === `/${locale}/services` ||
    pathname === `/${locale}/cases` ||
    pathname === `/${locale}/about` ||
    pathname === `/${locale}/team` ||
    pathname === `/${locale}/technologies` ||
    pathname === `/${locale}/careers` ||
    pathname.startsWith(`/${locale}/careers/`);

  return (
    <>
      <Header locale={locale} forceSolid={!hasDarkHero} />
      {children}
      <Footer locale={locale} />
    </>
  );
}
