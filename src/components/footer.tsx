import Link from "next/link";
import { getDictionary, type Locale } from "@/i18n";
import { Logo } from "./logo";
import { ClutchIcon, FacebookIcon, InstagramIcon, LinkedinIcon } from "./icons/social-icons";

const routes = ["services", "about", "blog"];

export function Footer({ locale }: { locale: Locale }) {
  const { footer } = getDictionary(locale);

  return (
    <footer>
      <div className="footer-grid container">
        <div>
          <div className="footer-logo">
            <Logo variant="footer" />
          </div>
          <nav
            className="social"
            aria-label={locale === "en" ? "Social media" : "Соціальні мережі"}
          >
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
            {column.slice(1).map((item, index) => (
              <Link
                key={item}
                href={`/${locale}/${routes[columnIndex]}${index ? `/${index}` : ""}`}
              >
                {item}
              </Link>
            ))}
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
        <nav aria-label={locale === "en" ? "Legal information" : "Юридична інформація"}>
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
