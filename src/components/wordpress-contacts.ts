import type { Locale } from "@/i18n";
import { filterPublishedForLocale, type ContentLocalization } from "@/content-localization";
import { normalizeSeoData, seoGraphqlFields, type SeoData, type SeoGraphqlData } from "@/seo";

export type ContactsChannel = {
  kind: "telegram" | "email" | "phone" | string;
  label: string;
  value: string;
  hint: string;
  url: string;
};

export type ContactsOffice = {
  title: string;
  address: string;
  phone: string;
  email: string;
};

export type ContactsSocial = {
  name: string;
  handle: string;
  url: string;
  network: "linkedin" | "instagram" | "facebook" | "telegram" | string;
};

export type ContactsPageContent = {
  eyebrow: string;
  title: string;
  intro: string;
  response: string;
  directLabel: string;
  formLabel: string;
  namePlaceholder: string;
  phonePlaceholder: string;
  emailPlaceholder: string;
  topicPlaceholder: string;
  messagePlaceholder: string;
  submit: string;
  consent: string;
  presenceEyebrow: string;
  presenceTitle: string;
  presenceText: string;
  socialLabel: string;
  mapImage: string;
  channels: ContactsChannel[];
  offices: ContactsOffice[];
  socials: ContactsSocial[];
  seo?: SeoData;
};

type ContactsNode = {
  title: string;
  gvspaceLocalization?: ContentLocalization | null;
  gvspaceSeo?: SeoGraphqlData;
  featuredImage?: { node?: { sourceUrl?: string } };
  contactsPageDetails?: Omit<ContactsPageContent, "mapImage" | "seo">;
};

const fallbackByLocale: Record<"uk" | "en", Omit<ContactsPageContent, "seo">> = {
  uk: {
    eyebrow: "CONTACTS",
    title: "Все починається з розмови",
    intro:
      "Розкажіть про ваш бізнес і задачу. Ми відповімо і запропонуємо перший крок — без зобов’язань.",
    response: "Відповідаємо протягом 2 годин у робочий день",
    directLabel: "НАПИСАТИ НАПРЯМУ",
    formLabel: "АБО ЗАЛИШИТИ ЗАЯВКУ",
    namePlaceholder: "Ім'я",
    phonePlaceholder: "+38 0__",
    emailPlaceholder: "Email",
    topicPlaceholder: "Що вас цікавить?",
    messagePlaceholder: "Розкажіть про ваш проєкт або задачу",
    submit: "Відправити заявку",
    consent: "Натискаючи кнопку, ви погоджуєтесь з обробкою персональних даних",
    presenceEyebrow: "ЛОКАЦІЯ",
    presenceTitle: "Працюємо там, де зручно вам",
    presenceText: "Україна, ЄС та США — оформлюємо співпрацю у вашій юрисдикції.",
    socialLabel: "СОЦІАЛЬНІ МЕРЕЖІ",
    mapImage: "",
    channels: [
      {
        kind: "telegram",
        label: "TELEGRAM",
        value: "@[username]",
        hint: "Найшвидший спосіб зв’язатись",
        url: "https://t.me/",
      },
      {
        kind: "email",
        label: "EMAIL",
        value: "[email@gvspace.com]",
        hint: "Для детальних запитів і документів",
        url: "mailto:email@gvspace.com",
      },
      {
        kind: "phone",
        label: "ТЕЛЕФОН",
        value: "+38 0__ ___ __ __",
        hint: "Пн–Пт, 10:00–19:00",
        url: "tel:+380000000000",
      },
    ],
    offices: [
      {
        title: "Київ, Україна:",
        address: "Україна, м. Київ, вул. Нижній Вал, 17/8",
        phone: "+38 099 999 99 99",
        email: "gvspace.ua@gvspace.com",
      },
      {
        title: "Renton, USA:",
        address: "416 Monroe Ave NE, Apt 206\nRenton, WA 98056, USA",
        phone: "+1 999 999 99 99",
        email: "gvspace.usa@gvspace.com",
      },
      {
        title: "Bratislava, Slovakia:",
        address:
          "G1 - Space s. r. o.\nKarpatské námestie 7770/10A\n831 06 Bratislava - Rača SLOVAKIA",
        phone: "+421 999 99 99 99",
        email: "gvspace.eu@gvspace.com",
      },
    ],
    socials: [
      {
        name: "LinkedIn",
        handle: "linkedin.com/company/[gvspace]",
        url: "https://www.linkedin.com/company/gvspace",
        network: "linkedin",
      },
      {
        name: "Instagram",
        handle: "@[gvspace]",
        url: "https://www.instagram.com/gvspace",
        network: "instagram",
      },
      {
        name: "Facebook",
        handle: "facebook.com/[gvspace]",
        url: "https://www.facebook.com/gvspace",
        network: "facebook",
      },
      {
        name: "Telegram-канал",
        handle: "t.me/[gvspace]",
        url: "https://t.me/gvspace",
        network: "telegram",
      },
    ],
  },
  en: {
    eyebrow: "CONTACTS",
    title: "Everything starts with a conversation",
    intro:
      "Tell us about your business and challenge. We’ll respond and suggest a first step — with no obligation.",
    response: "We respond within 2 hours on business days",
    directLabel: "CONTACT US DIRECTLY",
    formLabel: "OR LEAVE A REQUEST",
    namePlaceholder: "Name",
    phonePlaceholder: "+38 0__",
    emailPlaceholder: "Email",
    topicPlaceholder: "What are you interested in?",
    messagePlaceholder: "Tell us about your project or challenge",
    submit: "Send request",
    consent: "By clicking the button, you consent to the processing of personal data",
    presenceEyebrow: "LOCATION",
    presenceTitle: "We work where it is convenient for you",
    presenceText: "Ukraine, the EU, and the USA — we set up cooperation in your jurisdiction.",
    socialLabel: "SOCIAL MEDIA",
    mapImage: "",
    channels: [
      {
        kind: "telegram",
        label: "TELEGRAM",
        value: "@[username]",
        hint: "The fastest way to reach us",
        url: "https://t.me/",
      },
      {
        kind: "email",
        label: "EMAIL",
        value: "[email@gvspace.com]",
        hint: "For detailed enquiries and documents",
        url: "mailto:email@gvspace.com",
      },
      {
        kind: "phone",
        label: "PHONE",
        value: "+38 0__ ___ __ __",
        hint: "Mon–Fri, 10:00–19:00",
        url: "tel:+380000000000",
      },
    ],
    offices: [
      {
        title: "Kyiv, Ukraine:",
        address: "Ukraine, Kyiv, Nyzhnii Val St, 17/8",
        phone: "+38 099 999 99 99",
        email: "gvspace.ua@gvspace.com",
      },
      {
        title: "Renton, USA:",
        address: "416 Monroe Ave NE, Apt 206\nRenton, WA 98056, USA",
        phone: "+1 999 999 99 99",
        email: "gvspace.usa@gvspace.com",
      },
      {
        title: "Bratislava, Slovakia:",
        address:
          "G1 - Space s. r. o.\nKarpatské námestie 7770/10A\n831 06 Bratislava - Rača SLOVAKIA",
        phone: "+421 999 99 99 99",
        email: "gvspace.eu@gvspace.com",
      },
    ],
    socials: [
      {
        name: "LinkedIn",
        handle: "linkedin.com/company/[gvspace]",
        url: "https://www.linkedin.com/company/gvspace",
        network: "linkedin",
      },
      {
        name: "Instagram",
        handle: "@[gvspace]",
        url: "https://www.instagram.com/gvspace",
        network: "instagram",
      },
      {
        name: "Facebook",
        handle: "facebook.com/[gvspace]",
        url: "https://www.facebook.com/gvspace",
        network: "facebook",
      },
      {
        name: "Telegram channel",
        handle: "t.me/[gvspace]",
        url: "https://t.me/gvspace",
        network: "telegram",
      },
    ],
  },
};

export function getFallbackContacts(locale: Locale): ContactsPageContent {
  return fallbackByLocale[locale === "en" ? "en" : "uk"];
}

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

export async function getContactsPage(locale: Locale): Promise<ContactsPageContent> {
  const fallback = getFallbackContacts(locale);
  if (!endpoint) return fallback;

  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query ContactsPage($locale: String!) {
          contactsPages(first: 10) {
            nodes {
              title
              gvspaceLocalization { locale translationGroup status }
              gvspaceSeo(locale: $locale) { ${seoGraphqlFields} }
              featuredImage { node { sourceUrl } }
              contactsPageDetails(locale: $locale) {
                eyebrow title intro response directLabel formLabel
                namePlaceholder phonePlaceholder emailPlaceholder topicPlaceholder messagePlaceholder
                submit consent presenceEyebrow presenceTitle presenceText socialLabel
                channels { kind label value hint url }
                offices { title address phone email }
                socials { name handle url network }
              }
            }
          }
        }`,
        variables: { locale },
      }),
      next: { revalidate: 10 },
    });
    if (!response.ok) return fallback;
    const payload = (await response.text()).trim();
    if (!payload) return fallback;
    const result = JSON.parse(payload) as {
      data?: { contactsPages?: { nodes?: ContactsNode[] } };
      errors?: unknown[];
    };
    if (result.errors) return fallback;

    const item = filterPublishedForLocale(result.data?.contactsPages?.nodes ?? [], locale)[0];
    const details = item?.contactsPageDetails;
    if (!item || !details?.title) return fallback;

    return {
      eyebrow: details.eyebrow || fallback.eyebrow,
      title: details.title || fallback.title,
      intro: details.intro || fallback.intro,
      response: details.response || fallback.response,
      directLabel: details.directLabel || fallback.directLabel,
      formLabel: details.formLabel || fallback.formLabel,
      namePlaceholder: details.namePlaceholder || fallback.namePlaceholder,
      phonePlaceholder: details.phonePlaceholder || fallback.phonePlaceholder,
      emailPlaceholder: details.emailPlaceholder || fallback.emailPlaceholder,
      topicPlaceholder: details.topicPlaceholder || fallback.topicPlaceholder,
      messagePlaceholder: details.messagePlaceholder || fallback.messagePlaceholder,
      submit: details.submit || fallback.submit,
      consent: details.consent || fallback.consent,
      presenceEyebrow: details.presenceEyebrow || fallback.presenceEyebrow,
      presenceTitle: details.presenceTitle || fallback.presenceTitle,
      presenceText: details.presenceText || fallback.presenceText,
      socialLabel: details.socialLabel || fallback.socialLabel,
      mapImage: item.featuredImage?.node?.sourceUrl || "",
      channels: details.channels?.length ? details.channels : fallback.channels,
      offices: details.offices?.length ? details.offices : fallback.offices,
      socials: details.socials?.length ? details.socials : fallback.socials,
      seo: normalizeSeoData(item.gvspaceSeo, {
        title: details.title || item.title,
        description: details.intro || details.title,
      }),
    };
  } catch {
    return fallback;
  }
}
