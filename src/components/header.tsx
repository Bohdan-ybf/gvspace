"use client";

import Image from "next/image";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { useEffect, useRef, useState } from "react";
import { localeNames, locales, type Locale } from "@/i18n";
import { getLocalizedUrl } from "@/markets";
import { ChevronDown } from "./icons/chevron-down";
import { ClutchIcon, FacebookIcon, InstagramIcon, LinkedinIcon } from "./icons/social-icons";
import { LeadModalButton } from "./lead-modal";
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

function ServicesMenuArrow() {
  return (
    <svg
      className="services-menu-arrow"
      width="18"
      height="18"
      viewBox="0 0 18 18"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      aria-hidden="true"
    >
      <path
        d="M3.75 9H14.25M10.5 5.25L14.25 9L10.5 12.75"
        stroke="currentColor"
        strokeWidth="1.25"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
}

function PlusIcon() {
  return (
    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
      <path
        d="M6 0.75V11.25M0.75 6H11.25"
        stroke="currentColor"
        strokeWidth="1.2"
        strokeLinecap="round"
      />
    </svg>
  );
}

function MobileBackArrowIcon() {
  return (
    <svg width="12" height="11" viewBox="0 0 12 11" fill="none" aria-hidden="true">
      <path
        d="M0.500023 5.16683L11.1667 5.16683M5.16669 0.50016L0.500023 5.16683L5.16669 9.8335"
        stroke="currentColor"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
}

type MobilePanel =
  | { level: "root" }
  | { level: "services" }
  | { level: "direction"; slug: string }
  | { level: "expertise" }
  | { level: "company" };

function hasDarkHero(pathname: string, locale: Locale) {
  const localizedPathname = pathname.startsWith(`/${locale}`)
    ? pathname
    : `/${locale}${pathname === "/" ? "" : pathname}`;
  const pathSegments = localizedPathname.split("/").filter(Boolean);
  const isHomePage = localizedPathname === `/${locale}` || localizedPathname === `/${locale}/`;
  const isServiceDetail = pathSegments[1] === "services" && pathSegments.length >= 3;
  const isTechnologyCatalog = localizedPathname === `/${locale}/technologies`;

  return (
    isHomePage ||
    localizedPathname === `/${locale}/services` ||
    isServiceDetail ||
    localizedPathname === `/${locale}/cases` ||
    localizedPathname === `/${locale}/reviews` ||
    localizedPathname.startsWith(`/${locale}/cases/`) ||
    localizedPathname === `/${locale}/about` ||
    localizedPathname === `/${locale}/team` ||
    isTechnologyCatalog ||
    localizedPathname === `/${locale}/industries` ||
    localizedPathname === `/${locale}/partners` ||
    localizedPathname === `/${locale}/blog` ||
    (localizedPathname.startsWith(`/${locale}/blog/`) &&
      !localizedPathname.startsWith(`/${locale}/blog/author/`)) ||
    localizedPathname === `/${locale}/careers` ||
    localizedPathname.startsWith(`/${locale}/careers/`)
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
  const [isCompact, setIsCompact] = useState(false);
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isLanguageOpen, setIsLanguageOpen] = useState(false);
  const [areDesktopMenusDismissed, setAreDesktopMenusDismissed] = useState(false);
  const [hoveredServiceSlug, setHoveredServiceSlug] = useState<string | null>(null);
  const [mobilePanel, setMobilePanel] = useState<MobilePanel>({ level: "root" });
  const staticServiceDirections = getTranslations("services", locale).directions;
  const cmsServiceDirections = services
    .filter((service) => !service.parentSlug)
    .map((direction) => ({
      slug: direction.slug,
      title: direction.title,
      image: direction.image,
      services: services
        .filter((service) => service.parentSlug === direction.slug)
        .map((service) => ({ slug: service.slug, title: service.title })),
    }));
  const serviceMenuDirections = cmsServiceDirections.some((direction) => direction.services.length)
    ? cmsServiceDirections
    : staticServiceDirections.map((direction) => ({
        slug: direction.slug,
        title: direction.title,
        image: undefined,
        services: direction.services.map((title) => ({ slug: "", title })),
      }));
  const pathWithoutLocale = pathname.startsWith(`/${locale}`)
    ? pathname.slice(locale.length + 1)
    : pathname;
  const currentServiceSlug = pathWithoutLocale.startsWith("/services/")
    ? pathWithoutLocale.split("/")[2]
    : undefined;
  const activeServiceDirection =
    serviceMenuDirections.find((direction) => direction.slug === hoveredServiceSlug) ??
    serviceMenuDirections.find((direction) => direction.slug === currentServiceSlug) ??
    serviceMenuDirections[0];
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
  const companyMenuLabel = locale === "uk" ? "Компанія" : "Company";
  const backLabel = locale === "uk" ? "Назад" : "Back";
  const companyOrder = ["/about", "/team", "/careers", "/reviews", "/partners"];
  const orderedCompanyLinks = [
    ...companyOrder.flatMap((href) => companyLinks.filter((item) => item.href === href)),
    ...companyLinks.filter((item) => !companyOrder.includes(item.href)),
  ];
  const localeCode = (language: Locale) => (language === "uk" ? "UA" : language.toUpperCase());
  const closeMobileMenu = () => {
    setIsMenuOpen(false);
    setMobilePanel({ level: "root" });
  };
  const activeDirection =
    mobilePanel.level === "direction"
      ? serviceMenuDirections.find((direction) => direction.slug === mobilePanel.slug)
      : undefined;
  const languageSwitcherRef = useRef<HTMLDivElement>(null);
  const isMenuOpenRef = useRef(isMenuOpen);

  useEffect(() => {
    isMenuOpenRef.current = isMenuOpen;
  }, [isMenuOpen]);
  const isSectionActive = (index: number) => {
    const path = pathname.length > 1 && pathname.endsWith("/") ? pathname.slice(0, -1) : pathname;
    const prefix = `/${locale}`;
    const section = path.startsWith(prefix) ? path.slice(prefix.length) || "/" : path;
    const matches = (href: string) => section === href || section.startsWith(`${href}/`);

    if (index === 0) return matches("/services");
    if (index === 1) return matches("/cases");
    if (index === 2) return matches("/technologies") || matches("/industries");
    if (index === 3) {
      return ["/about", "/reviews", "/team", "/partners", "/careers"].some(matches);
    }
    if (index === 4) return matches("/blog");
    if (index === 5) return matches("/contacts");
    return false;
  };

  useEffect(() => {
    const updateHeader = () => setIsScrolled(window.scrollY > 24);

    updateHeader();
    window.addEventListener("scroll", updateHeader, { passive: true });

    return () => window.removeEventListener("scroll", updateHeader);
  }, []);

  useEffect(() => {
    let idleTimer = 0;

    const onScroll = () => {
      if (isMenuOpenRef.current) return;

      setIsCompact(true);
      window.clearTimeout(idleTimer);
      idleTimer = window.setTimeout(() => setIsCompact(false), 700);
    };

    window.addEventListener("scroll", onScroll, { passive: true });

    return () => {
      window.removeEventListener("scroll", onScroll);
      window.clearTimeout(idleTimer);
    };
  }, []);

  useEffect(() => {
    document.body.style.overflow = isMenuOpen ? "hidden" : "";
    const closeOnEscape = (event: KeyboardEvent) => {
      if (event.key === "Escape") {
        setIsMenuOpen(false);
        setIsLanguageOpen(false);
        setMobilePanel({ level: "root" });
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
      className={`header${forceSolid || !hasDarkHero(pathname, locale) || isScrolled || isMenuOpen ? " is-scrolled" : ""}${isMenuOpen ? " is-menu-open" : ""}${isCompact && !isMenuOpen ? " is-compact" : ""}${areDesktopMenusDismissed ? " desktop-menus-dismissed" : ""}`}
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
            <div
              className="services-menu"
              key="services"
              onPointerLeave={() => setHoveredServiceSlug(null)}
            >
              <Link
                className="nav-link"
                href={`/${locale}/services`}
                aria-current={isSectionActive(0) ? "page" : undefined}
              >
                {label.toUpperCase()}
                <ChevronDown className="chevron" />
              </Link>
              <div className="services-dropdown">
                <div className="services-dropdown-columns">
                  <ul className="services-dropdown-parents">
                    {serviceMenuDirections.map((direction) => (
                      <li key={direction.slug}>
                        <Link
                          href={`/${locale}/services/${direction.slug}`}
                          className={
                            direction.slug === activeServiceDirection?.slug
                              ? "is-active"
                              : undefined
                          }
                          aria-current={direction.slug === currentServiceSlug ? "page" : undefined}
                          onPointerEnter={() => setHoveredServiceSlug(direction.slug)}
                          onFocus={() => setHoveredServiceSlug(direction.slug)}
                        >
                          <span className="services-menu-label">
                            {direction.image ? (
                              <Image
                                className="services-menu-icon"
                                src={direction.image}
                                alt=""
                                width={25}
                                height={25}
                                unoptimized={direction.image.startsWith("http://")}
                              />
                            ) : null}
                            <span>{direction.title}</span>
                          </span>
                          <ServicesMenuArrow />
                        </Link>
                      </li>
                    ))}
                  </ul>
                  <ul className="services-dropdown-children">
                    {activeServiceDirection?.services.map((service) => (
                      <li key={service.slug || service.title}>
                        <Link
                          href={
                            service.slug
                              ? `/${locale}/services/${activeServiceDirection.slug}/${service.slug}`
                              : `/${locale}/services/${activeServiceDirection.slug}`
                          }
                        >
                          {service.title}
                        </Link>
                      </li>
                    ))}
                  </ul>
                </div>
              </div>
            </div>
          ) : index === 2 ? (
            <div className="expertise-menu" key="expertise">
              <Link
                className="nav-link"
                href={`/${locale}/technologies`}
                aria-current={isSectionActive(2) ? "page" : undefined}
              >
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
              <Link
                className="nav-link"
                href={`/${locale}/about`}
                aria-current={isSectionActive(3) ? "page" : undefined}
              >
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
            <Link
              className="nav-link"
              key={routes[index]}
              href={`/${locale}/${routes[index]}`}
              aria-current={isSectionActive(index) ? "page" : undefined}
            >
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
        <LeadModalButton className="btn btn-primary desktop-only">
          {text.common.buildSystem}
        </LeadModalButton>
        <LeadModalButton className="btn btn-primary mobile-header-cta">
          {t.mobileAction}
        </LeadModalButton>
        <div className="language-switcher" ref={languageSwitcherRef}>
          <button
            className="language-button"
            type="button"
            aria-label={localeNames[locale]}
            aria-expanded={isLanguageOpen}
            aria-haspopup="menu"
            aria-controls="language-menu"
            onClick={() => setIsLanguageOpen((isOpen) => !isOpen)}
          >
            {localeCode(locale)}
            <ChevronDown className="chevron" />
          </button>
          <div
            id="language-menu"
            className={`language-menu${isLanguageOpen ? " is-open" : ""}`}
            role="menu"
            aria-hidden={!isLanguageOpen}
          >
            {[...locales]
              .sort((a, b) => localeCode(a).localeCompare(localeCode(b)))
              .map((language) => (
                <Link
                  key={language}
                  href={languageHref(language)}
                  hrefLang={language}
                  role="menuitem"
                  aria-label={localeNames[language]}
                  aria-current={locale === language ? "page" : undefined}
                  tabIndex={isLanguageOpen ? 0 : -1}
                  onClick={() => setIsLanguageOpen(false)}
                >
                  {localeCode(language)}
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
          onClick={() => {
            setIsMenuOpen((isOpen) => !isOpen);
            setMobilePanel({ level: "root" });
            setIsCompact(false);
          }}
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
              onClick={closeMobileMenu}
            >
              {localeCode(language)}
            </Link>
          ))}
        </div>
        <div className="mobile-nav-links">
          {mobilePanel.level === "root" && (
            <>
              <button
                type="button"
                className="mobile-root-row"
                onClick={() => setMobilePanel({ level: "services" })}
              >
                <span>{text.navigation[0]}</span>
                <PlusIcon />
              </button>
              <Link
                className="mobile-root-row"
                href={`/${locale}/cases`}
                aria-current={isSectionActive(1) ? "page" : undefined}
                tabIndex={isMenuOpen ? 0 : -1}
                onClick={closeMobileMenu}
              >
                {text.navigation[1]}
              </Link>
              <button
                type="button"
                className="mobile-root-row"
                onClick={() => setMobilePanel({ level: "expertise" })}
              >
                <span>{text.navigation[2]}</span>
                <PlusIcon />
              </button>
              <button
                type="button"
                className="mobile-root-row"
                onClick={() => setMobilePanel({ level: "company" })}
              >
                <span>{companyMenuLabel}</span>
                <PlusIcon />
              </button>
              <Link
                className="mobile-root-row"
                href={`/${locale}/blog`}
                aria-current={isSectionActive(4) ? "page" : undefined}
                tabIndex={isMenuOpen ? 0 : -1}
                onClick={closeMobileMenu}
              >
                {text.navigation[4]}
              </Link>
              <Link
                className="mobile-root-row"
                href={`/${locale}/contacts`}
                aria-current={isSectionActive(5) ? "page" : undefined}
                tabIndex={isMenuOpen ? 0 : -1}
                onClick={closeMobileMenu}
              >
                {text.navigation[5]}
              </Link>
            </>
          )}
          {mobilePanel.level === "services" && (
            <>
              <div className="mobile-panel-heading">
                <button
                  type="button"
                  aria-label={backLabel}
                  onClick={() => setMobilePanel({ level: "root" })}
                >
                  <MobileBackArrowIcon />
                </button>
                <p>{text.navigation[0]}</p>
              </div>
              {serviceMenuDirections.map((direction) =>
                direction.services.length ? (
                  <button
                    type="button"
                    className="mobile-drill-row"
                    key={direction.slug}
                    onClick={() => setMobilePanel({ level: "direction", slug: direction.slug })}
                  >
                    <span>{direction.title}</span>
                    <MobileMenuArrowIcon />
                  </button>
                ) : (
                  <Link
                    className="mobile-sub-link"
                    href={`/${locale}/services/${direction.slug}`}
                    key={direction.slug}
                    tabIndex={isMenuOpen ? 0 : -1}
                    onClick={closeMobileMenu}
                  >
                    {direction.title}
                  </Link>
                ),
              )}
            </>
          )}
          {mobilePanel.level === "direction" && activeDirection && (
            <>
              <div className="mobile-panel-heading">
                <button
                  type="button"
                  aria-label={backLabel}
                  onClick={() => setMobilePanel({ level: "services" })}
                >
                  <MobileBackArrowIcon />
                </button>
                <p>{activeDirection.title}</p>
              </div>
              {activeDirection.services.map((service) => (
                <Link
                  className="mobile-sub-link"
                  href={
                    service.slug
                      ? `/${locale}/services/${activeDirection.slug}/${service.slug}`
                      : `/${locale}/services/${activeDirection.slug}`
                  }
                  key={service.slug || service.title}
                  tabIndex={isMenuOpen ? 0 : -1}
                  onClick={closeMobileMenu}
                >
                  {service.title}
                </Link>
              ))}
            </>
          )}
          {mobilePanel.level === "expertise" && (
            <>
              <div className="mobile-panel-heading">
                <button
                  type="button"
                  aria-label={backLabel}
                  onClick={() => setMobilePanel({ level: "root" })}
                >
                  <MobileBackArrowIcon />
                </button>
                <p>{text.navigation[2]}</p>
              </div>
              {expertiseLinks.map((item) => (
                <Link
                  className="mobile-sub-link"
                  href={`/${locale}${item.href}`}
                  key={item.href}
                  tabIndex={isMenuOpen ? 0 : -1}
                  onClick={closeMobileMenu}
                >
                  {item.title}
                </Link>
              ))}
            </>
          )}
          {mobilePanel.level === "company" && (
            <>
              <div className="mobile-panel-heading">
                <button
                  type="button"
                  aria-label={backLabel}
                  onClick={() => setMobilePanel({ level: "root" })}
                >
                  <MobileBackArrowIcon />
                </button>
                <p>{companyMenuLabel}</p>
              </div>
              {orderedCompanyLinks.map((item) => (
                <Link
                  className="mobile-sub-link"
                  href={`/${locale}${item.href}`}
                  key={item.href}
                  tabIndex={isMenuOpen ? 0 : -1}
                  onClick={closeMobileMenu}
                >
                  {item.title}
                </Link>
              ))}
            </>
          )}
        </div>
        <div className="mobile-menu-footer">
          <LeadModalButton
            className="btn btn-primary mobile-menu-cta"
            tabIndex={isMenuOpen ? 0 : -1}
            onClick={closeMobileMenu}
          >
            {text.common.buildSystem}
          </LeadModalButton>
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
