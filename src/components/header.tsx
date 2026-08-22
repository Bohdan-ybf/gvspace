"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useEffect, useRef, useState } from "react";
import { getDictionary } from "@/i18n";
import { ChevronDown } from "./icons/chevron-down";
import { Logo } from "./logo";

const routes = ["services", "cases", "expertise", "about", "blog", "contacts"];

export function Header({ locale, forceSolid = false }: { locale: string; forceSolid?: boolean }) {
  const text = getDictionary(locale);
  const alternateLanguage = locale === "en" ? "uk" : "en";
  const pathname = usePathname();
  const languageHref = (language: "uk" | "en") => {
    const localizedPath = pathname.replace(/^\/(uk|en)(?=\/|$)/, `/${language}`);
    return localizedPath === pathname && !pathname.match(/^\/(uk|en)(?=\/|$)/)
      ? `/${language}${pathname === "/" ? "" : pathname}`
      : localizedPath;
  };
  const [isScrolled, setIsScrolled] = useState(false);
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isLanguageOpen, setIsLanguageOpen] = useState(false);
  const languageSwitcherRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const updateHeader = () => setIsScrolled(window.scrollY > 24);

    updateHeader();
    window.addEventListener("scroll", updateHeader, { passive: true });

    return () => window.removeEventListener("scroll", updateHeader);
  }, []);

  useEffect(() => {
    document.body.style.overflow = isMenuOpen ? "hidden" : "";
    const closeOnEscape = (event: KeyboardEvent) => {
      if (event.key === "Escape") {
        setIsMenuOpen(false);
        setIsLanguageOpen(false);
      }
    };

    window.addEventListener("keydown", closeOnEscape);
    return () => {
      document.body.style.overflow = "";
      window.removeEventListener("keydown", closeOnEscape);
    };
  }, [isMenuOpen]);

  useEffect(() => {
    const closeOnOutsideClick = (event: PointerEvent) => {
      if (!languageSwitcherRef.current?.contains(event.target as Node)) {
        setIsLanguageOpen(false);
      }
    };

    document.addEventListener("pointerdown", closeOnOutsideClick);
    return () => document.removeEventListener("pointerdown", closeOnOutsideClick);
  }, []);

  return (
    <header className={`header${forceSolid || isScrolled || isMenuOpen ? " is-scrolled" : ""}`}>
      <Link className="logo" href={`/${locale}`} aria-label="GVSPACE">
        <Logo variant="header" priority />
      </Link>
      <nav
        className="desktop-only"
        aria-label={locale === "en" ? "Main navigation" : "Головна навігація"}
      >
        {text.navigation.map((label, index) => (
          <Link key={routes[index]} href={`/${locale}/${routes[index]}`}>
            {label.toUpperCase()}
            {(index === 0 || index === 2) && <ChevronDown className="chevron" />}
          </Link>
        ))}
      </nav>
      <div className="actions">
        <a className="header-phone desktop-only" href="tel:+380123456789">
          +38 012 345 67 89
        </a>
        <Link className="btn btn-primary desktop-only" href={`/${locale}/contacts`}>
          {text.common.buildSystem}
        </Link>
        <div className="language-switcher" ref={languageSwitcherRef}>
          <button
            className="language-button"
            type="button"
            aria-expanded={isLanguageOpen}
            aria-haspopup="menu"
            aria-controls="language-menu"
            onClick={() => setIsLanguageOpen((isOpen) => !isOpen)}
          >
            {locale.toUpperCase()}
            <ChevronDown className="chevron" />
          </button>
          <div
            id="language-menu"
            className={`language-menu${isLanguageOpen ? " is-open" : ""}`}
            role="menu"
            aria-hidden={!isLanguageOpen}
          >
            <Link
              href={languageHref("uk")}
              hrefLang="uk"
              role="menuitem"
              aria-current={locale === "uk" ? "page" : undefined}
              tabIndex={isLanguageOpen ? 0 : -1}
              onClick={() => setIsLanguageOpen(false)}
            >
              <span>Українська</span>
              <span>UK</span>
            </Link>
            <Link
              href={languageHref("en")}
              hrefLang="en"
              role="menuitem"
              aria-current={locale === "en" ? "page" : undefined}
              tabIndex={isLanguageOpen ? 0 : -1}
              onClick={() => setIsLanguageOpen(false)}
            >
              <span>English</span>
              <span>EN</span>
            </Link>
          </div>
        </div>
        <button
          type="button"
          aria-label={text.common.openMenu}
          aria-expanded={isMenuOpen}
          aria-controls="mobile-navigation"
          className="menu"
          onClick={() => setIsMenuOpen((isOpen) => !isOpen)}
        >
          <span />
          <span />
          <span />
        </button>
      </div>
      <nav
        id="mobile-navigation"
        className={`mobile-navigation${isMenuOpen ? " is-open" : ""}`}
        aria-label={locale === "en" ? "Mobile navigation" : "Мобільна навігація"}
        aria-hidden={!isMenuOpen}
      >
        {text.navigation.map((label, index) => (
          <Link
            key={routes[index]}
            href={`/${locale}/${routes[index]}`}
            tabIndex={isMenuOpen ? 0 : -1}
            onClick={() => setIsMenuOpen(false)}
          >
            <span>{label}</span>
            <span aria-hidden="true">0{index + 1}</span>
          </Link>
        ))}
        <a
          href="tel:+380123456789"
          tabIndex={isMenuOpen ? 0 : -1}
          onClick={() => setIsMenuOpen(false)}
        >
          +38 012 345 67 89
        </a>
        <Link
          href={languageHref(alternateLanguage)}
          hrefLang={alternateLanguage}
          tabIndex={isMenuOpen ? 0 : -1}
          onClick={() => setIsMenuOpen(false)}
        >
          {alternateLanguage.toUpperCase()}
        </Link>
      </nav>
    </header>
  );
}
