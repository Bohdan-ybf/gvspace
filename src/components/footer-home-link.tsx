"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import type { ReactNode } from "react";
import type { Locale } from "@/i18n";

export function FooterHomeLink({ locale, children }: { locale: Locale; children: ReactNode }) {
  const pathname = usePathname();
  const home = `/${locale}`;
  const current = pathname.length > 1 && pathname.endsWith("/") ? pathname.slice(0, -1) : pathname;

  return (
    <Link
      className="footer-home-link"
      href={home}
      aria-label="GVSPACE"
      onClick={(event) => {
        if (current !== home) return;
        event.preventDefault();
        const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
        window.scrollTo({ top: 0, behavior: reduceMotion ? "auto" : "smooth" });
      }}
    >
      {children}
    </Link>
  );
}
