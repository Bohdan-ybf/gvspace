import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { FacebookIcon, InstagramIcon, LinkedinIcon } from "./icons/social-icons";

const content = {
  uk: {
    title: "Все починається з розмови",
    intro:
      "Розкажіть про ваш бізнес і задачу. Ми відповімо і запропонуємо перший крок — без зобов’язань.",
    response: "Відповідаємо протягом 2 годин у робочий день",
    direct: "НАПИСАТИ НАПРЯМУ",
    telegram: ["TELEGRAM", "@[username]", "Найшвидший спосіб зв’язатись"],
    email: ["EMAIL", "[email@gvspace.com]", "Для детальних запитів і документів"],
    phone: ["ТЕЛЕФОН", "+38 0__ ___ __ __", "Пн–Пт, 10:00–19:00"],
    form: "АБО ЗАЛИШИТИ ЗАЯВКУ",
    name: "Ім'я",
    topic: "Що вас цікавить?",
    message: "Розкажіть про ваш проєкт або задачу",
    submit: "Відправити заявку",
    consent: "Натискаючи кнопку, ви погоджуєтесь з обробкою персональних даних",
    location: "ЛОКАЦІЯ",
    city: "Київ, Україна",
    details: [
      ["АДРЕСА", "[місто, вулиця, офіс]"],
      ["ГОДИНИ РОБОТИ", "Пн–Пт: 10:00–19:00"],
      ["ГЕОГРАФІЯ РОБОТИ", "Україна, Європа, глобальні digital-ринки"],
    ],
    social: "СОЦІАЛЬНІ МЕРЕЖІ",
  },
  en: {
    title: "Everything starts with a conversation",
    intro:
      "Tell us about your business and challenge. We’ll respond and suggest a first step — with no obligation.",
    response: "We respond within 2 hours on business days",
    direct: "CONTACT US DIRECTLY",
    telegram: ["TELEGRAM", "@[username]", "The fastest way to reach us"],
    email: ["EMAIL", "[email@gvspace.com]", "For detailed enquiries and documents"],
    phone: ["PHONE", "+38 0__ ___ __ __", "Mon–Fri, 10:00–19:00"],
    form: "OR LEAVE A REQUEST",
    name: "Name",
    topic: "What are you interested in?",
    message: "Tell us about your project or challenge",
    submit: "Send request",
    consent: "By clicking the button, you consent to the processing of personal data",
    location: "LOCATION",
    city: "Kyiv, Ukraine",
    details: [
      ["ADDRESS", "[city, street, office]"],
      ["WORKING HOURS", "Mon–Fri: 10:00–19:00"],
      ["WORK GEOGRAPHY", "Ukraine, Europe, global digital markets"],
    ],
    social: "SOCIAL MEDIA",
  },
} as const satisfies Record<Locale, unknown>;

function ContactIcon({ type }: { type: "telegram" | "email" | "phone" }) {
  const paths = {
    telegram:
      "M3 14.2 26.4 4.6c1.1-.4 2.1.3 1.7 2L24 26.1c-.3 1.4-1.6 1.8-2.7 1l-6.2-4.6-3.2 3.1c-.4.4-.8.7-1.4.7l.5-7.1 13-11.7-15.8 9.8-6.5-2.1c-1.4-.5-1.4-1.4.3-2Z",
    email:
      "M3 7h26v19H3V7Zm2.2 2 10.8 8L26.8 9H5.2Zm21.8 3.2-10.4 7.7a1 1 0 0 1-1.2 0L5 12.2V24h22V12.2Z",
    phone:
      "M8.5 3.5 13 8l-2.7 3.4a23 23 0 0 0 10.3 10.3L24 19l4.5 4.5-2.2 4.1c-.6 1.1-1.8 1.7-3 1.4C12.5 26.8 5.2 19.5 3 8.7c-.3-1.2.3-2.4 1.4-3L8.5 3.5Z",
  };
  return (
    <svg viewBox="0 0 32 32" aria-hidden="true">
      <path d={paths[type]} fill="currentColor" />
    </svg>
  );
}

const socials = [
  { name: "LinkedIn", handle: "linkedin.com/company/[gvspace]", Icon: LinkedinIcon },
  { name: "Instagram", handle: "@[gvspace]", Icon: InstagramIcon },
  { name: "Facebook", handle: "t.me/[gvspace]", Icon: FacebookIcon },
];

export function ContactsPage({ locale }: { locale: Locale }) {
  const text = content[locale];
  const cards = [text.telegram, text.email, text.phone];
  const types = ["telegram", "email", "phone"] as const;

  return (
    <main className="contacts-page">
      <section className="contacts-hero container">
        <div>
          <span className="privacy-eyebrow mono">LEGAL</span>
          <h1>{text.title}</h1>
          <p>{text.intro}</p>
        </div>
        <div className="response-badge mono">
          <i />
          {text.response}
        </div>
      </section>

      <section className="contacts-main container">
        <div className="direct-contacts">
          <h2 className="contact-label mono">{text.direct}</h2>
          <div className="contact-cards">
            {cards.map((card, index) => (
              <a
                href={
                  index === 0
                    ? "https://t.me/"
                    : index === 1
                      ? "mailto:email@gvspace.com"
                      : "tel:+380000000000"
                }
                key={card[0]}
              >
                <ContactIcon type={types[index]} />
                <span>
                  <small className="mono">{card[0]}</small>
                  <strong>{card[1]}</strong>
                  <em>{card[2]}</em>
                </span>
              </a>
            ))}
          </div>

          <div className="location-block">
            <h2 className="contact-label mono">{text.location}</h2>
            <div className="location-map">
              <span aria-hidden="true">📍</span>
              <b className="mono">{text.city}</b>
            </div>
            <dl>
              {text.details.map(([term, value]) => (
                <div key={term}>
                  <dt className="mono">{term}</dt>
                  <dd>{value}</dd>
                </div>
              ))}
            </dl>
          </div>
        </div>

        <form className="contacts-form">
          <h2 className="contact-label mono">{text.form}</h2>
          <div className="contacts-form-row">
            <input aria-label={text.name} placeholder={text.name} required />
            <input aria-label="Phone" inputMode="tel" placeholder="+38 0__" />
          </div>
          <input aria-label="Email" type="email" placeholder="Email" required />
          <input aria-label={text.topic} placeholder={text.topic} required />
          <textarea aria-label={text.message} placeholder={text.message} required />
          <button className="btn btn-primary" type="submit">
            {text.submit}
          </button>
          <p className="mono">{text.consent}</p>
        </form>
      </section>

      <section className="contacts-social container">
        <h2 className="contact-label mono">{text.social}</h2>
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
            <ContactIcon type="telegram" />
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
