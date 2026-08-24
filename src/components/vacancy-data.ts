import type { Locale } from "@/i18n";

type LocalizedText = Record<Locale, string>;

export type Vacancy = {
  slug: string;
  title: LocalizedText;
  salary: string;
  hot: boolean;
  tags: string[];
  heroImage: string;
  role: LocalizedText[];
  tasks: LocalizedText[];
  requirements: LocalizedText[];
  tools: string[];
  benefits: LocalizedText[];
};

const vacancies: Vacancy[] = [
  {
    slug: "performance-marketing-manager",
    title: {
      uk: "Performance Marketing Manager",
      en: "Performance Marketing Manager",
    },
    salary: "$[XXX–XXX] / міс",
    hot: true,
    tags: ["МАРКЕТИНГ", "REMOTE", "FULL-TIME"],
    heroImage: "/images/careers/vacancy-hero.webp",
    role: [
      {
        uk: "[Опис ролі: що саме робить ця людина в команді, яка її основна функція, як виглядає типовий день або тиждень. 2–3 речення.]",
        en: "[Role description: what this person does, their main function, and what a typical day or week looks like. 2–3 sentences.]",
      },
      {
        uk: "[Контекст: на якому етапі знаходиться компанія, чому зараз потрібна ця роль, який вплив матиме ця людина на результат.]",
        en: "[Context: where the company is now, why this role is needed, and how this person will influence the result.]",
      },
    ],
    tasks: [
      {
        uk: "Налаштування та оптимізація рекламних кампаній у Meta та Google Ads.",
        en: "Set up and optimize advertising campaigns in Meta and Google Ads.",
      },
      {
        uk: "Побудова наскрізної аналітики: GA4, GTM, Pixel, CRM-інтеграція.",
        en: "Build end-to-end analytics: GA4, GTM, Pixel, and CRM integration.",
      },
      {
        uk: "Написання медіапланів і прогнозів для клієнтів.",
        en: "Prepare media plans and forecasts for clients.",
      },
      {
        uk: "Щотижневі звіти без жаргону — тільки бізнесові метрики.",
        en: "Produce weekly reports without jargon — only business metrics.",
      },
      {
        uk: "Участь у Clarity Session з клієнтами на старті кожного проєкту.",
        en: "Participate in client Clarity Sessions at the start of each project.",
      },
    ],
    requirements: [
      {
        uk: "2+ роки у performance-маркетингу, є підтверджені результати по ROAS/CPL.",
        en: "2+ years in performance marketing with proven ROAS/CPL results.",
      },
      {
        uk: "Впевнена робота з Meta Ads Manager і Google Ads на рівні вище середнього.",
        en: "Confident, above-average use of Meta Ads Manager and Google Ads.",
      },
      {
        uk: "Розуміння юніт-економіки: CAC, LTV, ROAS, CPL — не просто терміни.",
        en: "A practical understanding of unit economics: CAC, LTV, ROAS, and CPL.",
      },
      {
        uk: "Вмієш пояснювати результати клієнту без жаргону.",
        en: "Ability to explain results to clients without jargon.",
      },
      {
        uk: "Системне мислення: шукаєш першопричину, а не латаєш симптоми.",
        en: "Systematic thinking: finding root causes rather than patching symptoms.",
      },
    ],
    tools: ["Meta Ads", "Google Ads", "GA4", "GTM", "Looker Studio", "TikTok Ads", "Notion"],
    benefits: [
      {
        uk: "Ставка $[XXX–XXX]/міс залежно від досвіду та результатів.",
        en: "Compensation of $[XXX–XXX]/month depending on experience and results.",
      },
      {
        uk: "Remote, гнучкий графік без зайвих нарад.",
        en: "Remote work and a flexible schedule without unnecessary meetings.",
      },
      {
        uk: "Власна зона відповідальності без мікроменеджменту.",
        en: "Your own area of responsibility without micromanagement.",
      },
      {
        uk: "Реальні кейси для портфоліо з вимірюваними результатами.",
        en: "Real portfolio cases with measurable results.",
      },
      { uk: "Можливість рости разом з агенцією.", en: "The opportunity to grow with the agency." },
    ],
  },
];

export function getVacancyBySlug(slug: string) {
  return vacancies.find((vacancy) => vacancy.slug === slug);
}
