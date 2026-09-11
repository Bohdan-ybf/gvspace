import Link from "next/link";
import type { Locale } from "@/i18n";
import { Logo } from "./logo";
import { ClutchIcon, FacebookIcon, InstagramIcon, LinkedinIcon } from "./icons/social-icons";

import { getTranslations } from "@/i18n/pages";
const routes = ["services", "about", "blog"];

export function Footer({ locale }: { locale: Locale }) {
  const t = getTranslations("common", locale).footer;
  const { footer } = getTranslations("global", locale);

  return (
    <footer>
      <div className="footer-grid container">
        <div>
          <div className="footer-logo">
            <Logo variant="footer" />
          </div>
          <nav className="social" aria-label={t.socialNavigationLabel}>
            <a href="#" aria-label="Facebook">
              <FacebookIcon />
            </a>
            <a href="#" aria-label="Instagram">
              <InstagramIcon />
            </a>
            <a href="#" aria-label="LinkedIn">
              <LinkedinIcon />
            </a>
            <a href="#" aria-label="Clutch">
              <ClutchIcon />
            </a>
          </nav>
        </div>
        {footer.columns.map((column, columnIndex) => (
          <div key={column[0]}>
            <b>{column[0]}</b>
            {column.slice(1).map((item, index) => {
              const href =
                columnIndex === 1 && index === 1
                  ? `/${locale}/team`
                  : columnIndex === 1 && index === 2
                    ? `/${locale}/cases`
                    : columnIndex === 1 && index === 3
                      ? `/${locale}/reviews`
                      : columnIndex === 2 && index === 1
                        ? `/${locale}/technologies`
                        : columnIndex === 2 && index === 2
                          ? `/${locale}/careers`
                          : `/${locale}/${routes[columnIndex]}${index ? `/${index}` : ""}`;

              return (
                <Link key={item} href={href}>
                  {item}
                </Link>
              );
            })}
          </div>
        ))}
        <div>
          <b>{footer.contacts[0]}</b>
          <span>[email@gvspace.com]</span>
          <span>{footer.contacts[1]}</span>
          <span>{footer.contacts[2]}</span>
        </div>
      </div>
      <div className="legal container">
        © 2026 GVSPACE. {footer.copyright}
        <nav aria-label={t.legalNavigationLabel}>
          <Link href={`/${locale}/privacy-policy`}>{footer.privacy}</Link>
          <Link href={`/${locale}/terms-of-use`}>{footer.terms}</Link>
        </nav>
      </div>
      <div className="footer-mobile-wordmark" aria-hidden="true">
        <Logo variant="footer" />
      </div>
    </footer>
  );
}
