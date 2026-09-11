import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import {
  EmailIcon,
  FacebookIcon,
  InstagramIcon,
  LinkedinIcon,
  PhoneIcon,
  TelegramIcon,
} from "./icons/social-icons";

import { getTranslations } from "@/i18n/pages";
const socials = [
  { name: "LinkedIn", handle: "linkedin.com/company/[gvspace]", Icon: LinkedinIcon },
  { name: "Instagram", handle: "@[gvspace]", Icon: InstagramIcon },
  { name: "Facebook", handle: "facebook.com/[gvspace]", Icon: FacebookIcon },
];

export function ContactsPage({ locale }: { locale: Locale }) {
  const t = getTranslations("contacts", locale).page;
  const cards = [t.telegram, t.email, t.phone];
  const contactIcons = [TelegramIcon, EmailIcon, PhoneIcon];

  return (
    <main className="contacts-page">
      <section className="contacts-hero container">
        <div>
          <span className="privacy-eyebrow mono">LEGAL</span>
          <h1>{t.title}</h1>
          <p>{t.intro}</p>
        </div>
        <div className="response-badge mono">
          <i />
          {t.response}
        </div>
      </section>

      <section className="contacts-main container">
        <div className="direct-contacts">
          <h2 className="contact-label mono">{t.direct}</h2>
          <div className="contact-cards">
            {cards.map((card, index) => {
              const Icon = contactIcons[index];
              const href =
                index === 0
                  ? "https://t.me/"
                  : index === 1
                    ? "mailto:email@gvspace.com"
                    : "tel:+380000000000";

              return (
                <a href={href} key={card[0]}>
                  <Icon />
                  <span>
                    <small className="mono">{card[0]}</small>
                    <strong>{card[1]}</strong>
                    <em>{card[2]}</em>
                  </span>
                </a>
              );
            })}
          </div>

          <div className="location-block">
            <h2 className="contact-label mono">{t.location}</h2>
            <div className="location-map">
              <span aria-hidden="true">📍</span>
              <b className="mono">{t.city}</b>
            </div>
            <dl>
              {t.details.map(([term, value]) => (
                <div key={term}>
                  <dt className="mono">{term}</dt>
                  <dd>{value}</dd>
                </div>
              ))}
            </dl>
          </div>
        </div>

        <form className="contacts-form">
          <h2 className="contact-label mono">{t.form}</h2>
          <div className="contacts-form-row">
            <input aria-label={t.name} placeholder={t.name} required />
            <input aria-label="Phone" inputMode="tel" placeholder="+38 0__" />
          </div>
          <input aria-label="Email" type="email" placeholder="Email" required />
          <input aria-label={t.topic} placeholder={t.topic} required />
          <textarea aria-label={t.message} placeholder={t.message} required />
          <button className="btn btn-primary" type="submit">
            {t.submit}
          </button>
          <p className="mono">{t.consent}</p>
        </form>
      </section>

      <section className="contacts-social container">
        <h2 className="contact-label mono">{t.social}</h2>
        <div>
          {socials.map(({ name, handle, Icon }) => (
            <Link href="#" key={name}>
              <Icon />
              <span>
                <strong>{name}</strong>
                <small>{handle}</small>
              </span>
              <ArrowRight />
            </Link>
          ))}
          <Link href="#">
            <TelegramIcon />
            <span>
              <strong>Telegram-канал</strong>
              <small>t.me/[gvspace]</small>
            </span>
            <ArrowRight />
          </Link>
        </div>
      </section>
    </main>
  );
}
