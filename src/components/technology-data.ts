import type { Locale } from "@/i18n";
import type { TechnologyItem, TechnologyStack } from "./wordpress-technologies";

function parsePairs(value: string): [string, string][] {
  return value
    .split(/\r?\n/)
    .map((line) => line.trim())
    .filter(Boolean)
    .map((line) => {
      const separator = line.indexOf("|");
      if (separator === -1) return [line, ""] as [string, string];
      return [line.slice(0, separator).trim(), line.slice(separator + 1).trim()] as [
        string,
        string,
      ];
    })
    .filter(([left]) => left !== "");
}

type FallbackTechnology = {
  slug: string;
  image?: string;
  order: number;
  categorySlugs: string[];
  relatedCase?: string;
  title: Record<"uk" | "en", string>;
  description: Record<"uk" | "en", string>;
  tag: Record<"uk" | "en", string>;
  body: Record<"uk" | "en", string>;
  headline?: Record<"uk" | "en", string>;
  intro?: Record<"uk" | "en", string>;
  why?: Record<"uk" | "en", string>;
  triggers?: Record<"uk" | "en", string>;
  uses?: Record<"uk" | "en", string>;
  stats?: Record<"uk" | "en", string>;
  benefits?: Record<"uk" | "en", string>;
  faq?: Record<"uk" | "en", string>;
  seoLead?: Record<"uk" | "en", string>;
  seoText?: Record<"uk" | "en", string>;
  meetName?: Record<"uk" | "en", string>;
  meetRole?: Record<"uk" | "en", string>;
  meetQuote?: Record<"uk" | "en", string>;
  meetYears?: Record<"uk" | "en", string>;
  meetProjects?: Record<"uk" | "en", string>;
  meetTags?: Record<"uk" | "en", string>;
};

const fallbackCategories = [
  { slug: "marketing", name: { uk: "Маркетинг", en: "Marketing" } },
  { slug: "development", name: { uk: "Розробка", en: "Development" } },
  { slug: "systems", name: { uk: "Системи", en: "Systems" } },
  { slug: "content", name: { uk: "Контент", en: "Content" } },
] as const;

const fallbackTechnologies: FallbackTechnology[] = [
  {
    slug: "google-analytics",
    image: "/images/technologies/logos/google-analytics.svg",
    order: 0,
    categorySlugs: ["marketing"],
    title: { uk: "Google Analytics 4", en: "Google Analytics 4" },
    description: {
      uk: "Поведінковий аналіз аудиторії, воронки конверсій, event-трекінг.",
      en: "Audience behavior analysis, conversion funnels, and event tracking.",
    },
    tag: { uk: "АНАЛІТИКА САЙТУ", en: "SITE ANALYTICS" },
    body: { uk: "", en: "" },
  },
  {
    slug: "google-tag-manager",
    image: "/images/technologies/logos/google-tag-manager.svg",
    order: 1,
    categorySlugs: ["marketing"],
    title: { uk: "Google Tag Manager", en: "Google Tag Manager" },
    description: {
      uk: "Управління всіма тегами та пікселями без правок у коді.",
      en: "Manage every tag and pixel without touching the codebase.",
    },
    tag: { uk: "ТЕГУВАННЯ", en: "TAGGING" },
    body: { uk: "", en: "" },
  },
  {
    slug: "meta-ads",
    order: 2,
    categorySlugs: ["marketing"],
    title: { uk: "Meta Ads", en: "Meta Ads" },
    description: {
      uk: "Ретаргетинг, lookalike-аудиторії, оптимізація конверсій.",
      en: "Retargeting, lookalike audiences, and conversion optimization.",
    },
    tag: { uk: "ПЛАТНА РЕКЛАМА", en: "PAID ADS" },
    body: { uk: "", en: "" },
  },
  {
    slug: "google-ads",
    image: "/images/technologies/logos/google-ads.svg",
    order: 3,
    categorySlugs: ["marketing"],
    title: { uk: "Google Ads", en: "Google Ads" },
    description: {
      uk: "Search, Performance Max, Display кампанії з фокусом на ROI.",
      en: "Search, Performance Max, and Display campaigns focused on ROI.",
    },
    tag: { uk: "ПЛАТНА РЕКЛАМА", en: "PAID ADS" },
    body: { uk: "", en: "" },
    intro: {
      uk: "Пошукова і Performance Max реклама з фокусом на заявки, а не на кліки. Підключаємо, коли потрібен керований попит і прозора юніт-економіка.",
      en: "Search and Performance Max campaigns focused on leads, not clicks. We use them when you need managed demand and clear unit economics.",
    },
    why: {
      uk: "Google Ads дає передбачуваний попит там, де органіка ще не тягне ріст. **Search і Performance Max зводять бюджет до заявок**, а не до порожніх кліків.",
      en: "Google Ads creates predictable demand where organic cannot yet carry growth. **Search and Performance Max point budget at leads**, not empty clicks.",
    },
    triggers: {
      uk: "Потрібен керований трафік | Є офер і аналітика, але органіка не закриває план заявок у найближчі тижні.\nВажливий ROI з перших кампаній | Бюджет має окупатися: дивимось CPL, ROAS і якість ліда, а не лише покази.\nКанали мають працювати як система | Кабінет Google Ads зв’язуємо з сайтом, CRM і звітністю, щоб масштабувати те, що працює.",
      en: "Managed traffic is required | The offer and analytics are in place, but organic will not cover the lead plan in the next weeks.\nROI matters from the first campaigns | Spend has to pay back: we watch CPL, ROAS, and lead quality, not just impressions.\nChannels should work as a system | The Google Ads account is wired to the site, CRM, and reporting so we scale what works.",
    },
    uses: {
      uk: "Search-кампанії під комерційний попит | Збираємо семантику і посадкові так, щоб бюджет ішов у заявки, а не в інформаційні запити.\nPerformance Max для масштабу | Підключаємо, коли є аналітика, фіди й зрозуміла ціна ліда.\nРемаркетинг і lookalike | Повертаємо теплу аудиторію і розширюємо її без хаотичних охоплень.\nНаскрізна аналітика кабінету | Зв’язуємо клік, сайт і CRM, щоб бачити CPL і якість заявки.",
      en: "Search campaigns for commercial demand | We shape queries and landings so spend goes to leads, not informational clicks.\nPerformance Max for scale | We turn it on when analytics, feeds, and a clear cost per lead are in place.\nRemarketing and lookalike | We bring warm audiences back and expand them without chaotic reach.\nEnd-to-end account analytics | Click, site, and CRM are wired so we see CPL and lead quality.",
    },
    faq: {
      uk: "Скільки часу займає запуск Google Ads? | Базовий контур кампаній і аналітики збираємо за тижні. Повну систему попиту плануємо на Clarity Session.\nЧи працюєте ви з моєю нішею? | Працюємо з digital-залежним бізнесом: eCommerce, EdTech, IT/SaaS і сервісами з середнім і високим чеком.\nЩо входить у ведення Google Ads? | Структура кампаній, трекінг, креативи/розширення, щотижневий контроль юніт-економіки і правила масштабування.",
      en: "How long does a Google Ads launch take? | A baseline of campaigns and tracking is ready in weeks. A full demand system is scoped on a Clarity Session.\nDo you work with my niche? | We work with digital-dependent businesses: eCommerce, EdTech, IT/SaaS, and service companies with a mid-to-high check.\nWhat is included in Google Ads management? | Campaign structure, tracking, assets, weekly unit-economics control, and scaling rules.",
    },
    seoLead: {
      uk: "Google Ads у GVSPACE — це не окремий кабінет, а контур керованого попиту.",
      en: "Google Ads at GVSPACE is not a standalone account — it is a managed demand loop.",
    },
    seoText: {
      uk: "Ми запускаємо Search і Performance Max лише коли є офер, аналітика і зрозуміла ціна заявки. Кампанії зв’язуються з сайтом, CRM і звітністю, щоб власник бачив, що масштабувати, а що зупиняти.",
      en: "We launch Search and Performance Max only when there is an offer, analytics, and a clear cost per lead. Campaigns connect to the site, CRM, and reporting so the owner sees what to scale and what to stop.",
    },
    meetName: { uk: "Василь Горайчук", en: "Vasyl Horaichuk" },
    meetRole: { uk: "CEO & FOUNDER", en: "CEO & FOUNDER" },
    meetQuote: {
      uk: "Рекламний бюджет має працювати як система: кожна гривня має відповідального, метрику і правило масштабування.",
      en: "Ad spend should work as a system: every dollar has an owner, a metric, and a scaling rule.",
    },
    meetYears: { uk: "6", en: "6" },
    meetProjects: { uk: "150", en: "150" },
    meetTags: { uk: "GOOGLE ADS\nMETA ADS\nANALYTICS", en: "GOOGLE ADS\nMETA ADS\nANALYTICS" },
  },
  {
    slug: "looker-studio",
    image: "/images/technologies/logos/looker-studio.svg",
    order: 4,
    categorySlugs: ["marketing", "systems"],
    title: { uk: "Looker Studio", en: "Looker Studio" },
    description: {
      uk: "Кастомні дашборди, де власник бачить реальний стан без жаргону.",
      en: "Custom dashboards that show owners the real picture without jargon.",
    },
    tag: { uk: "ЗВІТНІСТЬ", en: "REPORTING" },
    body: { uk: "", en: "" },
  },
  {
    slug: "power-bi",
    order: 5,
    categorySlugs: ["marketing", "systems"],
    title: { uk: "Power BI", en: "Power BI" },
    description: {
      uk: "Глибока бізнес-аналітика для складних воронок і юніт-економіки.",
      en: "Deep business analytics for complex funnels and unit economics.",
    },
    tag: { uk: "BI & ЗВІТНІСТЬ", en: "BI & REPORTING" },
    body: { uk: "", en: "" },
  },
  {
    slug: "tiktok-ads",
    image: "/images/technologies/logos/tiktok-ads.svg",
    order: 6,
    categorySlugs: ["marketing"],
    title: { uk: "TikTok Ads", en: "TikTok Ads" },
    description: {
      uk: "Платна реклама для аудиторій, яких немає в інших каналах.",
      en: "Paid acquisition for audiences that other channels do not reach.",
    },
    tag: { uk: "ПЛАТНА РЕКЛАМА", en: "PAID ADS" },
    body: { uk: "", en: "" },
  },
  {
    slug: "search-console",
    image: "/images/technologies/logos/search-console.svg",
    order: 7,
    categorySlugs: ["marketing"],
    title: { uk: "Google Search Console", en: "Google Search Console" },
    description: {
      uk: "SEO-моніторинг, аналіз пошукових запитів, індексація.",
      en: "SEO monitoring, query analysis, and index coverage.",
    },
    tag: { uk: "SEO", en: "SEO" },
    body: { uk: "", en: "" },
  },
  {
    slug: "wordpress",
    order: 8,
    categorySlugs: ["development"],
    title: { uk: "WordPress", en: "WordPress" },
    description: {
      uk: "Керований контент і адмінка для маркетингових команд без розробки на кожну правку.",
      en: "Managed content and an admin for marketing teams without a ticket for every change.",
    },
    tag: { uk: "CMS", en: "CMS" },
    body: { uk: "", en: "" },
  },
  {
    slug: "nextjs",
    image: "/images/technologies/logos/nextjs.svg",
    order: 9,
    categorySlugs: ["development"],
    title: { uk: "Next.js", en: "Next.js" },
    description: {
      uk: "Швидкі публічні сайти з контролем SEO, маршрутів і продуктивності.",
      en: "Fast public sites with control over SEO, routing, and performance.",
    },
    tag: { uk: "FRONTEND", en: "FRONTEND" },
    body: { uk: "", en: "" },
    intro: {
      uk: "Реакт-фреймворк для серверного рендерингу та статичної генерації. Обираємо, коли потрібні SEO-видимість і швидкість завантаження без компромісів у функціональності.",
      en: "A React framework for server rendering and static generation. We choose it when SEO visibility and load speed cannot come at the cost of functionality.",
    },
    why: {
      uk: "Next.js вирішує головну проблему SPA: сторінки не індексуються пошуковиками. **SSR і SSG дають SEO-видимість із першого дня** — без окремої SEO-інфраструктури.",
      en: "Next.js solves the core SPA problem: pages are not indexed. **SSR and SSG give SEO visibility from day one** — without a separate SEO stack.",
    },
    triggers: {
      uk: "SEO є пріоритетом росту | Проєкт залежить від органічного пошуку: лендінги, корпоративні сайти, контентні платформи.\nПотрібна висока швидкість | Core Web Vitals критичні для конверсій. SSG генерує статичні сторінки миттєво.\nГібридна архітектура | Частина сторінок статична, частина — динамічна з API. Next.js поєднує обидва підходи.",
      en: "SEO is the growth priority | The project depends on organic search: landings, corporate sites, content platforms.\nHigh speed is required | Core Web Vitals are critical for conversion. SSG serves static pages instantly.\nHybrid architecture | Some pages are static, some are dynamic against an API. Next.js holds both.",
    },
    uses: {
      uk: "Корпоративні та маркетингові сайти | SSG для статичних сторінок + API routes для форм і CRM-інтеграцій.\nЛендінги для performance-кампаній | Швидке завантаження знижує bounce rate і підвищує Quality Score.\nКлієнтські портали з авторизацією | App Router + middleware для захищених зон без окремого бекенду.\nІнтеграція з headless CMS | Contentful, Sanity або Strapi як бекенд — редагування без розробника.",
      en: "Corporate and marketing sites | SSG for static pages plus API routes for forms and CRM integrations.\nLandings for performance campaigns | Fast load cuts bounce rate and lifts Quality Score.\nClient portals with auth | App Router and middleware for protected zones without a separate backend.\nHeadless CMS integration | Contentful, Sanity, or Strapi as the backend — editors change pages without engineering.",
    },
    faq: {
      uk: "Скільки часу займає впровадження Next.js? | Типовий маркетинговий сайт запускаємо за кілька тижнів. Складніший продукт з кабінетами і інтеграціями плануємо окремо на Clarity Session.\nЧи підходить Next.js для моєї ніші? | Так, якщо потрібна швидкість, SEO і контроль публічної частини: eCommerce, EdTech, IT/SaaS і сервісні бізнеси.\nЯкі гарантії результату? | Гарантуємо архітектуру, SEO-базу, аналітику і супровід після запуску. Цифри росту залежать від оферу і каналів — це розбираємо на Clarity Session.\nЧому не фриланс або інша агенція? | Стек підбираємо під задачу клієнта і вбудовуємо в систему: маркетинг, аналітика і розробка не живуть окремими файлами.",
      en: "How long does a Next.js build take? | A typical marketing site ships in a few weeks. Larger products with portals and integrations are scoped on a Clarity Session.\nIs Next.js right for my niche? | Yes, when you need speed, SEO, and control of the public site: eCommerce, EdTech, IT/SaaS, and service businesses.\nWhat results do you guarantee? | We guarantee the architecture, SEO baseline, analytics, and support after launch.\nWhy not a freelancer or another agency? | We pick the stack for the client’s task and wire it into one system.",
    },
    seoLead: {
      uk: "Ми віримо, що український бізнес заслуговує на простір для росту без хаосу.",
      en: "We believe Ukrainian businesses deserve a space to grow without chaos.",
    },
    seoText: {
      uk: "Next.js — наш інструмент для публічних сайтів, де SEO, швидкість і контроль маршрутів є частиною продукту, а не окремим етапом після запуску. Ми збираємо SSR і SSG під задачу клієнта: лендінг, корпоративний сайт, контентна платформа чи e-commerce.",
      en: "Next.js is our tool for public sites where SEO, speed, and routing control are part of the product, not a phase after launch. We assemble SSR and SSG around the client’s task: a landing page, a corporate site, a content platform, or e-commerce.",
    },
    meetName: { uk: "Богдан Яронний", en: "Bohdan Yaronnyi" },
    meetRole: { uk: "LEAD DEVELOPER", en: "LEAD DEVELOPER" },
    meetQuote: {
      uk: "Моє завдання — перетворити бізнес-цілі на технології, які не просто працюють сьогодні, а залишають простір для зростання завтра.",
      en: "My job is to turn business goals into technology that works today and still has room to grow tomorrow.",
    },
    meetYears: { uk: "6", en: "6" },
    meetProjects: { uk: "150", en: "150" },
    meetTags: { uk: "NEXT.JS\nNODE.JS\nAWS", en: "NEXT.JS\nNODE.JS\nAWS" },
  },
  {
    slug: "react",
    order: 10,
    categorySlugs: ["development"],
    title: { uk: "React", en: "React" },
    description: {
      uk: "Інтерактивні інтерфейси для складних кабінетів, форм і кабінетів клієнта.",
      en: "Interactive interfaces for complex dashboards, forms, and client portals.",
    },
    tag: { uk: "FRONTEND", en: "FRONTEND" },
    body: { uk: "", en: "" },
  },
  {
    slug: "docker",
    order: 11,
    categorySlugs: ["systems"],
    title: { uk: "Docker", en: "Docker" },
    description: {
      uk: "Однакові середовища від розробки до продакшену без сюрпризів на сервері.",
      en: "The same environments from development to production, without server surprises.",
    },
    tag: { uk: "INFRA", en: "INFRA" },
    body: { uk: "", en: "" },
  },
  {
    slug: "google-cloud",
    order: 12,
    categorySlugs: ["systems"],
    title: { uk: "Google Cloud", en: "Google Cloud" },
    description: {
      uk: "Хмарна інфраструктура для аналітики, сховищ даних і стабільного хостингу.",
      en: "Cloud infrastructure for analytics, data storage, and reliable hosting.",
    },
    tag: { uk: "CLOUD", en: "CLOUD" },
    body: { uk: "", en: "" },
  },
  {
    slug: "figma",
    order: 13,
    categorySlugs: ["content"],
    title: { uk: "Figma", en: "Figma" },
    description: {
      uk: "Дизайн-система, макети лендингів і узгодження візуалу з клієнтом в одному місці.",
      en: "Design systems, landing-page layouts, and visual alignment with the client in one place.",
    },
    tag: { uk: "ДИЗАЙН", en: "DESIGN" },
    body: { uk: "", en: "" },
  },
  {
    slug: "notion",
    order: 14,
    categorySlugs: ["content"],
    title: { uk: "Notion", en: "Notion" },
    description: {
      uk: "Контент-операції, брифи, редакційний календар і база знань команди.",
      en: "Content operations, briefs, editorial calendar, and the team knowledge base.",
    },
    tag: { uk: "КОНТЕНТ", en: "CONTENT" },
    body: { uk: "", en: "" },
  },
];

function localize(locale: Locale): "uk" | "en" {
  return locale === "en" ? "en" : "uk";
}

function toItem(item: FallbackTechnology, locale: Locale): TechnologyItem {
  const language = localize(locale);
  const title = item.title[language];
  const triggerSource = item.triggers?.[language] ?? item.benefits?.[language] ?? "";
  return {
    id: item.order + 1,
    slug: item.slug,
    title,
    description: item.description[language],
    tag: item.tag[language],
    headline: item.headline?.[language] || title,
    intro: item.intro?.[language] || item.body[language],
    why: item.why?.[language] || item.headline?.[language] || "",
    triggers: parsePairs(triggerSource).map(([triggerTitle, description]) => ({
      title: triggerTitle,
      description,
    })),
    uses: parsePairs(item.uses?.[language] ?? "").map(([useTitle, description]) => ({
      title: useTitle,
      description,
    })),
    stats: parsePairs(item.stats?.[language] ?? "").map(([value, label]) => ({
      value,
      label,
    })),
    benefits: parsePairs(item.benefits?.[language] ?? "").map(([benefitTitle, description]) => ({
      title: benefitTitle,
      description,
    })),
    faq: parsePairs(item.faq?.[language] ?? "").map(([question, answer]) => ({
      question,
      answer,
    })),
    seoLead: item.seoLead?.[language] ?? "",
    seoText: item.seoText?.[language] ?? "",
    meet: {
      name: item.meetName?.[language] ?? "",
      role: item.meetRole?.[language] ?? "",
      quote: item.meetQuote?.[language] ?? "",
      years: item.meetYears?.[language] ?? "",
      projects: item.meetProjects?.[language] ?? "",
      tags: (item.meetTags?.[language] ?? "")
        .split(/\r?\n/)
        .map((tag) => tag.trim())
        .filter(Boolean),
      photo: "",
    },
    relatedCase: item.relatedCase ?? "",
    visual: "",
    image: item.image,
    imageAlt: title,
    categorySlugs: item.categorySlugs,
    order: item.order,
  };
}

export function getFallbackTechnologyStack(locale: Locale): TechnologyStack {
  const language = localize(locale);
  return {
    categories: fallbackCategories.map((category, index) => ({
      slug: category.slug,
      name: category.name[language],
      order: index,
    })),
    items: fallbackTechnologies.map((item) => toItem(item, locale)),
  };
}

export function getFallbackTechnology(slug: string, locale: Locale): TechnologyItem | undefined {
  const item = fallbackTechnologies.find((candidate) => candidate.slug === slug);
  return item ? toItem(item, locale) : undefined;
}
