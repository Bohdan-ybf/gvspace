import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { Breadcrumbs } from "./breadcrumbs";
import { ArrowRight } from "./icons/arrow-right";
import {
  EmailIcon,
  FacebookIcon,
  InstagramIcon,
  LinkedinIcon,
  PhoneIcon,
  TelegramIcon,
} from "./icons/social-icons";
import { getContactsPage, type ContactsChannel, type ContactsSocial } from "./wordpress-contacts";

const channelIcons = {
  telegram: TelegramIcon,
  email: EmailIcon,
  phone: PhoneIcon,
} as const;

const socialIcons = {
  linkedin: LinkedinIcon,
  instagram: InstagramIcon,
  facebook: FacebookIcon,
  telegram: TelegramIcon,
} as const;

function channelHref(channel: ContactsChannel): string {
  if (channel.url) return channel.url;
  if (channel.kind === "email") return `mailto:${channel.value.replace(/[[\]]/g, "")}`;
  if (channel.kind === "phone") return `tel:${channel.value.replace(/[^\d+]/g, "")}`;
  if (channel.kind === "telegram") {
    const handle = channel.value.replace(/^@/, "").replace(/[[\]]/g, "");
    return `https://t.me/${handle}`;
  }
  return "#";
}

function socialHref(social: ContactsSocial): string {
  return social.url || "#";
}

function SocialIcon({ network }: { network: string }) {
  const Icon = socialIcons[network as keyof typeof socialIcons] || LinkedinIcon;
  return <Icon />;
}

function ChannelIcon({ kind }: { kind: string }) {
  const Icon = channelIcons[kind as keyof typeof channelIcons] || EmailIcon;
  return <Icon />;
}

export async function ContactsPage({ locale }: { locale: Locale }) {
  const page = await getContactsPage(locale);

  return (
    <main className="contacts-page">
      <section className="contacts-hero container">
        <div>
          <span className="privacy-eyebrow mono">{page.eyebrow}</span>
          <h1>{page.title}</h1>
          <p>{page.intro}</p>
        </div>
        {page.response ? (
          <div className="response-badge mono">
            <i />
            {page.response}
          </div>
        ) : null}
      </section>

      <section className="contacts-main container">
        <div className="direct-contacts">
          <h2 className="contact-label mono">{page.directLabel}</h2>
          <div className="contact-cards">
            {page.channels.map((channel) => (
              <a href={channelHref(channel)} key={`${channel.kind}-${channel.value}`}>
                <ChannelIcon kind={channel.kind} />
                <span>
                  <small className="mono">{channel.label}</small>
                  <strong>{channel.value}</strong>
                  {channel.hint ? <em>{channel.hint}</em> : null}
                </span>
              </a>
            ))}
          </div>
        </div>

        <form className="contacts-form">
          <h2 className="contact-label mono">{page.formLabel}</h2>
          <div className="contacts-form-row">
            <input aria-label={page.namePlaceholder} placeholder={page.namePlaceholder} required />
            <input
              aria-label={page.phonePlaceholder}
              inputMode="tel"
              placeholder={page.phonePlaceholder}
            />
          </div>
          <input
            aria-label={page.emailPlaceholder}
            type="email"
            placeholder={page.emailPlaceholder}
            required
          />
          <input aria-label={page.topicPlaceholder} placeholder={page.topicPlaceholder} required />
          <textarea
            aria-label={page.messagePlaceholder}
            placeholder={page.messagePlaceholder}
            required
          />
          <button className="btn btn-primary" type="submit">
            {page.submit}
          </button>
          <p className="mono">{page.consent}</p>
        </form>
      </section>

      <section className="contacts-presence">
        <div className="contacts-presence-copy">
          <div className="contacts-presence-copy-bleed">
            <div className="container">
              <span className="mono">{page.presenceEyebrow}</span>
              <h2>{page.presenceTitle}</h2>
              <p>{page.presenceText}</p>
            </div>
          </div>
        </div>
        <div className={`contacts-presence-map${page.mapImage ? "" : " is-fallback"}`}>
          <Image
            src={page.mapImage || "/images/contacts/world-map.svg"}
            alt=""
            fill
            sizes="(max-width: 900px) 100vw, 60vw"
            unoptimized={Boolean(page.mapImage)}
          />
        </div>
      </section>

      {page.offices.length ? (
        <section className="contacts-offices container">
          {page.offices.map((office) => (
            <article key={office.title}>
              <h3>{office.title}</h3>
              <p>{office.address}</p>
              {office.phone ? (
                <a href={`tel:${office.phone.replace(/[^\d+]/g, "")}`}>{office.phone}</a>
              ) : null}
              {office.email ? <a href={`mailto:${office.email}`}>{office.email}</a> : null}
            </article>
          ))}
        </section>
      ) : null}

      {page.socials.length ? (
        <section className="contacts-social container">
          <h2 className="contact-label mono">{page.socialLabel}</h2>
          <div>
            {page.socials.map((social) => (
              <Link href={socialHref(social)} key={`${social.network}-${social.name}`}>
                <SocialIcon network={social.network} />
                <span>
                  <strong>{social.name}</strong>
                  <small>{social.handle}</small>
                </span>
                <ArrowRight />
              </Link>
            ))}
          </div>
        </section>
      ) : null}

      <Breadcrumbs
        locale={locale}
        visible
        items={[{ label: locale === "uk" ? "Контакти" : "Contacts" }]}
      />
    </main>
  );
}
