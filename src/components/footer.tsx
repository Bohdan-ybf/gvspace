import Link from "next/link";
import type { ComponentType, SVGProps } from "react";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";
import { Logo } from "./logo";
import {
  ClutchIcon,
  FacebookIcon,
  InstagramIcon,
  LinkedinIcon,
  TelegramIcon,
  XIcon,
} from "./icons/social-icons";
import type { FooterContactDetails } from "./wordpress-contacts";

const socialIcons: Record<string, ComponentType<SVGProps<SVGSVGElement>>> = {
  facebook: FacebookIcon,
  instagram: InstagramIcon,
  linkedin: LinkedinIcon,
  clutch: ClutchIcon,
  x: XIcon,
  twitter: XIcon,
  telegram: TelegramIcon,
};

function SocialLinks({
  socials,
  label,
}: {
  socials: FooterContactDetails["socials"];
  label: string;
}) {
  if (!socials.length) return null;

  return (
    <nav className="social" aria-label={label}>
      {socials.map((social) => {
        const Icon = socialIcons[social.network];
        return (
          <a
            key={`${social.network}-${social.href}`}
            href={social.href}
            aria-label={social.name}
            target="_blank"
            rel="noreferrer"
          >
            {Icon ? <Icon /> : social.name}
          </a>
        );
      })}
    </nav>
  );
}

export function Footer({ locale, contacts }: { locale: Locale; contacts: FooterContactDetails }) {
  const t = getTranslations("common", locale).footer;
  const { footer } = getTranslations("global", locale);
  const columns = [footer.services, footer.company, footer.resources];

  return (
    <footer>
      <div className="footer-grid container">
        <div>
          <div className="footer-logo">
            <Logo variant="footer" />
          </div>
          <SocialLinks socials={contacts.socials} label={t.socialNavigationLabel} />
        </div>
        {columns.map((column) => (
          <div key={column.title}>
            <b>{column.title}</b>
            {column.links.map((item) => (
              <Link key={item.href} href={`/${locale}${item.href}`}>
                {item.label}
              </Link>
            ))}
          </div>
        ))}
        <div>
          <b>{footer.contactsTitle}</b>
          {contacts.phone ? <a href={contacts.phone.href}>{contacts.phone.value}</a> : null}
          {contacts.email ? <a href={contacts.email.href}>{contacts.email.value}</a> : null}
          {contacts.offices.map((office) => (
            <span className="footer-office" key={office.key}>
              {office.text}
            </span>
          ))}
        </div>
      </div>
      <div className="footer-mobile-wordmark">
        <Logo variant="footer" />
        <SocialLinks socials={contacts.socials} label={t.socialNavigationLabel} />
      </div>
      <div className="legal container">
        <span>
          © {new Date().getFullYear()} GVSPACE. {footer.copyright}
        </span>
        <nav aria-label={t.legalNavigationLabel}>
          <Link href={`/${locale}/sitemap`}>{footer.sitemap}</Link>
          <Link href={`/${locale}/privacy-policy`}>{footer.privacy}</Link>
          <Link href={`/${locale}/terms-of-use`}>{footer.terms}</Link>
        </nav>
      </div>
    </footer>
  );
}
