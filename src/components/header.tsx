"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useEffect, useRef, useState } from "react";
import { localeNames, locales, type Locale } from "@/i18n";
import { getLocalizedUrl } from "@/markets";
import { ChevronDown } from "./icons/chevron-down";
import { ClutchIcon, FacebookIcon, InstagramIcon, LinkedinIcon } from "./icons/social-icons";
import { Logo } from "./logo";
import type { ServiceOffering } from "./wordpress-services";

import { getTranslations } from "@/i18n/pages";
const routes = ["services", "cases", "expertise", "about", "blog", "contacts"];

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

function MobileMenuArrowIcon() {
  return (
    <svg
      width="12"
      height="11"
      viewBox="0 0 12 11"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      aria-hidden="true"
    >
      <path
        d="M11.1667 5.16634L0.5 5.16634M6.5 0.499671L11.1667 5.16634L6.5 9.83301"
        stroke="currentColor"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
}

export function Header({
  locale,
  forceSolid = false,
  services,
}: {
  locale: Locale;
  forceSolid?: boolean;
  services: ServiceOffering[];
}) {
  const t = getTranslations("common", locale).header;
  const text = getTranslations("global", locale);
  const pathname = usePathname();
  const languageHref = (language: Locale) => {
    return getLocalizedUrl(language, pathname);
  };
  const [isScrolled, setIsScrolled] = useState(false);
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isLanguageOpen, setIsLanguageOpen] = useState(false);
  const [areDesktopMenusDismissed, setAreDesktopMenusDismissed] = useState(false);
  const [openMobileSection, setOpenMobileSection] = useState<number | null>(null);
  const staticServiceDirections = getTranslations("services", locale).directions;
  const cmsServiceDirections = services
    .filter((service) => !service.parentSlug)
    .map((direction) => ({
      slug: direction.slug,
      title: direction.title,
      services: services
        .filter((service) => service.parentSlug === direction.slug)
        .map((service) => ({ slug: service.slug, title: service.title })),
    }));
  const serviceMenuDirections = cmsServiceDirections.some((direction) => direction.services.length)
    ? cmsServiceDirections
    : staticServiceDirections.map((direction) => ({
        slug: direction.slug,
        title: direction.title,
        services: direction.services.map((title) => ({ slug: "", title })),
      }));
  const serviceDirections = serviceMenuDirections.map(({ slug, title }) => ({ slug, title }));
  const companyLinks = t.companyLinks;
  const expertiseLinks =
    locale === "uk"
      ? [
          {
            href: "/technologies",
            title: "Технології",
            description:
              "Ми не використовуємо один стек для всього. Кожен інструмент у нашому арсеналі вирішує конкретну задачу — і тільки її.",
          },
          {
            href: "/industries",
            title: "Індустрії",
            description:
              "Кожна індустрія має власну економіку, цикл рішення і больові точки. Ми не переносимо шаблон з однієї ніші в іншу.",
          },
        ]
      : [
          {
            href: "/technologies",
            title: "Technologies",
            description:
              "We do not use one stack for everything. Every tool in our arsenal solves a specific task — and only that task.",
          },
          {
            href: "/industries",
            title: "Industries",
            description:
              "Every industry has its own economics, decision cycle, and pain points. We do not transfer one niche's template to another.",
          },
        ];
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
    <header
      className={`header${forceSolid || isScrolled || isMenuOpen ? " is-scrolled" : ""}${isMenuOpen ? " is-menu-open" : ""}${areDesktopMenusDismissed ? " desktop-menus-dismissed" : ""}`}
    >
      <Link className="logo" href={`/${locale}`} aria-label="GVSPACE">
        <Logo variant="header" priority />
      </Link>
      <nav
        className="desktop-only"
        aria-label={t.mainNavigationLabel}
        onClickCapture={() => setAreDesktopMenusDismissed(true)}
        onPointerLeave={() => setAreDesktopMenusDismissed(false)}
      >
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
                        {direction.services.map((service) => (
                          <li key={service.slug || service.title}>
                            <Link
                              href={
                                service.slug
                                  ? `/${locale}/services/${direction.slug}/${service.slug}`
                                  : `/${locale}/services/${direction.slug}`
                              }
                            >
                              {service.title}
                            </Link>
                          </li>
                        ))}
                      </ul>
                    </section>
                  ))}
                </div>
              </div>
            </div>
          ) : index === 2 ? (
            <div className="expertise-menu" key="expertise">
              <Link href={`/${locale}/technologies`}>
                {label.toUpperCase()}
                <ChevronDown className="chevron" />
              </Link>
              <div className="expertise-dropdown">
                <div className="expertise-dropdown-grid">
                  {expertiseLinks.map((item) => (
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
            {locale === "uk" ? "UA" : locale.toUpperCase()}
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
        <div className="mobile-language-options" aria-label="Language">
          {locales.map((language) => (
            <Link
              key={language}
              href={languageHref(language)}
              hrefLang={language}
              aria-current={locale === language ? "page" : undefined}
              tabIndex={isMenuOpen ? 0 : -1}
              onClick={() => setIsMenuOpen(false)}
            >
              {language === "uk" ? "UA" : language.toUpperCase()}
            </Link>
          ))}
        </div>
        <div className="mobile-nav-links">
          {text.navigation.map((label, index) => {
            const submenu =
              index === 0
                ? serviceDirections.map((item) => ({
                    href: `/services/${item.slug}`,
                    label: item.title,
                  }))
                : index === 2
                  ? expertiseLinks.map((item) => ({ href: item.href, label: item.title }))
                  : index === 3
                    ? companyLinks.map((item) => ({ href: item.href, label: item.title }))
                    : [];
            const hasSubmenu = submenu.length > 0;
            const isSubmenuOpen = openMobileSection === index;

            return (
              <div className="mobile-nav-item" key={routes[index]}>
                <div className="mobile-nav-row">
                  <Link
                    href={`/${locale}/${routes[index]}`}
                    tabIndex={isMenuOpen ? 0 : -1}
                    onClick={() => setIsMenuOpen(false)}
                  >
                    {label}
                  </Link>
                  {hasSubmenu && (
                    <button
                      type="button"
                      aria-label={`${label}: ${isSubmenuOpen ? "close" : "open"}`}
                      aria-expanded={isSubmenuOpen}
                      onClick={() => setOpenMobileSection(isSubmenuOpen ? null : index)}
                    >
                      <MobileMenuArrowIcon />
                    </button>
                  )}
                </div>
                {hasSubmenu && (
                  <div className={`mobile-submenu${isSubmenuOpen ? " is-open" : ""}`}>
                    {submenu.map((item) => (
                      <Link
                        href={`/${locale}${item.href}`}
                        tabIndex={isMenuOpen && isSubmenuOpen ? 0 : -1}
                        onClick={() => setIsMenuOpen(false)}
                        key={item.href}
                      >
                        {item.label}
                      </Link>
                    ))}
                  </div>
                )}
              </div>
            );
          })}
        </div>
        <div className="mobile-menu-footer">
          <Link
            className="btn btn-primary mobile-menu-cta"
            href={`/${locale}/contacts`}
            tabIndex={isMenuOpen ? 0 : -1}
            onClick={() => setIsMenuOpen(false)}
          >
            {text.common.buildSystem}
          </Link>
          <a href="tel:+380123456789" tabIndex={isMenuOpen ? 0 : -1}>
            +38 012 345 67 89
          </a>
          <a href="mailto:e-mail@gvspace.com" tabIndex={isMenuOpen ? 0 : -1}>
            e-mail@gvspace.com
          </a>
          <div
            className="mobile-menu-socials"
            aria-label={locale === "uk" ? "Соціальні мережі" : "Social media"}
          >
            <a href="#" aria-label="Facebook" tabIndex={isMenuOpen ? 0 : -1}>
              <FacebookIcon />
            </a>
            <a href="#" aria-label="Instagram" tabIndex={isMenuOpen ? 0 : -1}>
              <InstagramIcon />
            </a>
            <a href="#" aria-label="LinkedIn" tabIndex={isMenuOpen ? 0 : -1}>
              <LinkedinIcon />
            </a>
            <a href="#" aria-label="Clutch" tabIndex={isMenuOpen ? 0 : -1}>
              <ClutchIcon />
            </a>
          </div>
        </div>
      </nav>
    </header>
  );
}
