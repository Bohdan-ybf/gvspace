"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useEffect, useRef, useState } from "react";
import { localeNames, locales, type Locale } from "@/i18n";
import { getLocalizedUrl } from "@/markets";
import { ChevronDown } from "./icons/chevron-down";
import { Logo } from "./logo";

import { getTranslations } from "@/i18n/pages";
const routes = ["services", "cases", "expertise", "about", "blog", "contacts"];
const serviceSlugs = {
  strategy: [
    "strategic-audit",
    "digital-audit",
    "market-analysis",
    "clarity-session",
    "growth-roadmap",
    "marketing-process-audit",
  ],
  marketing: [
    "performance-marketing",
    "analytics-dashboards",
    "smm-strategy",
    "seo",
    "retention-crm",
  ],
  development: [
    "corporate-websites",
    "business-systems",
    "technical-support",
    "ecommerce",
    "product-discovery",
  ],
  content: [
    "brand-design",
    "photo-production",
    "creative-concepts",
    "video-production",
    "copywriting",
  ],
} as const;

function MenuArrowIcon() {
  return (
    <svg
      className="menu-arrow-icon"
      width="20"
      height="20"
      viewBox="0 0 20 20"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      aria-hidden="true"
    >
      <path
        d="M13.7498 0H0V6.25018H12.2437C9.96615 10.6981 5.33352 13.7498 0 13.7498V20C5.32506 20 10.165 17.9185 13.7498 14.5226V20H20V0H13.7498Z"
        fill="currentColor"
        fillOpacity="0.7"
      />
    </svg>
  );
}

export function Header({ locale, forceSolid = false }: { locale: Locale; forceSolid?: boolean }) {
  const t = getTranslations("common", locale).header;
  const text = getTranslations("global", locale);
  const alternateLanguages = locales.filter((language) => language !== locale);
  const pathname = usePathname();
  const languageHref = (language: Locale) => {
    return getLocalizedUrl(language, pathname);
  };
  const [isScrolled, setIsScrolled] = useState(false);
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isLanguageOpen, setIsLanguageOpen] = useState(false);
  const serviceDirections = t.serviceDirections;
  const serviceMenuDirections = getTranslations("services", locale).directions;
  const companyLinks = t.companyLinks;
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
      <nav className="desktop-only" aria-label={t.mainNavigationLabel}>
        {text.navigation.map((label, index) =>
          index === 0 ? (
            <div className="services-menu" key="services">
              <Link href={`/${locale}/services`}>
                {label.toUpperCase()}
                <ChevronDown className="chevron" />
              </Link>
              <div className="services-dropdown">
                <div className="services-dropdown-grid">
                  {serviceMenuDirections.map((direction) => (
                    <section className="services-dropdown-group" key={direction.slug}>
                      <Link
                        className="services-dropdown-title"
                        href={`/${locale}/services/${direction.slug}`}
                      >
                        <MenuArrowIcon />
                        {direction.title}
                      </Link>
                      <ul>
                        {direction.services.map((service, serviceIndex) => (
                          <li key={service}>
                            <Link
                              href={`/${locale}/services/${direction.slug}/${serviceSlugs[direction.slug][serviceIndex]}`}
                            >
                              {service}
                            </Link>
                          </li>
                        ))}
                      </ul>
                    </section>
                  ))}
                </div>
              </div>
            </div>
          ) : index === 3 ? (
            <div className="company-menu" key="about">
              <Link href={`/${locale}/about`}>
                {label.toUpperCase()}
                <ChevronDown className="chevron" />
              </Link>
              <div className="company-dropdown">
                <div className="company-dropdown-grid">
                  {companyLinks.map((item) => (
                    <Link href={`/${locale}${item.href}`} key={item.title}>
                      <strong>
                        <MenuArrowIcon />
                        {item.title}
                      </strong>
                      <span>{item.description}</span>
                    </Link>
                  ))}
                </div>
              </div>
            </div>
          ) : (
            <Link key={routes[index]} href={`/${locale}/${routes[index]}`}>
              {label.toUpperCase()}
              {index === 2 && <ChevronDown className="chevron" />}
            </Link>
          ),
        )}
      </nav>
      <div className="actions">
        <a className="header-phone desktop-only" href="tel:+380123456789">
          +38 012 345 67 89
        </a>
        <Link className="btn btn-primary desktop-only" href={`/${locale}/contacts`}>
          {text.common.buildSystem}
        </Link>
        <Link className="btn btn-primary mobile-header-cta" href={`/${locale}/contacts`}>
          {t.mobileAction}
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
            {locales.map((language) => (
              <Link
                key={language}
                href={languageHref(language)}
                hrefLang={language}
                role="menuitem"
                aria-current={locale === language ? "page" : undefined}
                tabIndex={isLanguageOpen ? 0 : -1}
                onClick={() => setIsLanguageOpen(false)}
              >
                <span>{localeNames[language]}</span>
                <span>{language.toUpperCase()}</span>
              </Link>
            ))}
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
        aria-label={t.mobileNavigationLabel}
        aria-hidden={!isMenuOpen}
      >
        {text.navigation.map((label, index) => (
          <div className="mobile-nav-group" key={routes[index]}>
            <Link
              href={`/${locale}/${routes[index]}`}
              tabIndex={isMenuOpen ? 0 : -1}
              onClick={() => setIsMenuOpen(false)}
            >
              <span>{label}</span>
              <span aria-hidden="true">0{index + 1}</span>
            </Link>
            {index === 0 &&
              serviceDirections.map((direction) => (
                <Link
                  className="mobile-service-link"
                  href={`/${locale}/services/${direction.slug}`}
                  tabIndex={isMenuOpen ? 0 : -1}
                  onClick={() => setIsMenuOpen(false)}
                  key={direction.slug}
                >
                  {direction.title}
                </Link>
              ))}
          </div>
        ))}
        <a
          href="tel:+380123456789"
          tabIndex={isMenuOpen ? 0 : -1}
          onClick={() => setIsMenuOpen(false)}
        >
          +38 012 345 67 89
        </a>
        {alternateLanguages.map((language) => (
          <Link
            key={language}
            href={languageHref(language)}
            hrefLang={language}
            tabIndex={isMenuOpen ? 0 : -1}
            onClick={() => setIsMenuOpen(false)}
          >
            {language.toUpperCase()}
          </Link>
        ))}
      </nav>
    </header>
  );
}
