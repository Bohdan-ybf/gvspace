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

const socials = [
  { name: "LinkedIn", handle: "linkedin.com/company/[gvspace]", Icon: LinkedinIcon },
  { name: "Instagram", handle: "@[gvspace]", Icon: InstagramIcon },
  { name: "Facebook", handle: "facebook.com/[gvspace]", Icon: FacebookIcon },
];

export function ContactsPage({ locale }: { locale: Locale }) {
  const text = content[locale];
  const cards = [text.telegram, text.email, text.phone];
  const contactIcons = [TelegramIcon, EmailIcon, PhoneIcon];

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
