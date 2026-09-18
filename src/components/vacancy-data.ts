import type { Locale } from "@/i18n";

type LocalizedText = Record<Locale, string>;

export type VacancyRecord = {
  slug: string;
  title: LocalizedText;
  excerpt: LocalizedText;
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

export type Vacancy = {
  slug: string;
  title: string;
  excerpt: string;
  salary: string;
  hot: boolean;
  tags: string[];
  heroImage: string;
  role: string[];
  tasks: string[];
  requirements: string[];
  tools: string[];
  benefits: string[];
};

const heroImage = "/images/careers/vacancy-hero.webp";

export const vacancies: VacancyRecord[] = [
  {
    slug: "performance-marketing-manager",
    title: {
      uk: "Performance Marketing Manager",
      en: "Performance Marketing Manager",
    },
    excerpt: {
      uk: "Шукаємо фахівця з досвідом у Meta та Google Ads, який вміє будувати системи, а не запускати кампанії наосліп.",
      en: "We are looking for a Meta and Google Ads specialist who can build systems rather than launch campaigns blindly.",
    },
    salary: "$[XXX–XXX] / міс",
    hot: true,
    tags: ["МАРКЕТИНГ", "REMOTE", "FULL-TIME"],
    heroImage,
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
  {
    slug: "full-stack-developer",
    title: {
      uk: "Full-stack Developer",
      en: "Full-stack Developer",
    },
    excerpt: {
      uk: "Шукаємо розробника, який збирає продукти від архітектури до релізу і тримає якість коду без хаосу.",
      en: "We are looking for a developer who can take products from architecture to release and keep code quality without chaos.",
    },
    salary: "$[XXX–XXX] / міс",
    hot: false,
    tags: ["РОЗРОБКА", "REMOTE", "FULL-TIME"],
    heroImage,
    role: [
      {
        uk: "Ви відповідаєте за розробку та підтримку продуктів GVSPACE і клієнтських систем: від архітектури до релізу.",
        en: "You own the development and support of GVSPACE products and client systems: from architecture to release.",
      },
      {
        uk: "Працюєте в парі зі стратегією і маркетингом, щоб технічні рішення підсилювали бізнес-результат, а не існували окремо.",
        en: "You work alongside strategy and marketing so technical decisions reinforce business results rather than living in isolation.",
      },
    ],
    tasks: [
      {
        uk: "Проєктування та розробка вебпродуктів на Next.js, WordPress і пов’язаному стеку.",
        en: "Design and build web products on Next.js, WordPress, and the related stack.",
      },
      {
        uk: "Інтеграції з CRM, аналітикою, платежами та внутрішніми сервісами.",
        en: "Integrate CRM, analytics, payments, and internal services.",
      },
      {
        uk: "Підтримка якості: рев’ю, тести, документація, стабільні релізи.",
        en: "Protect quality through reviews, tests, documentation, and stable releases.",
      },
      {
        uk: "Оцінка складності й ризиків до старту розробки.",
        en: "Estimate complexity and risks before development starts.",
      },
      {
        uk: "Участь у Clarity Session, коли рішення залежить від технічної архітектури.",
        en: "Join Clarity Sessions when the decision depends on technical architecture.",
      },
    ],
    requirements: [
      {
        uk: "3+ роки комерційної розробки, є живі продукти в портфоліо.",
        en: "3+ years of commercial development with live products in the portfolio.",
      },
      {
        uk: "Впевнений TypeScript, React/Next.js і досвід з WordPress або headless CMS.",
        en: "Confident TypeScript and React/Next.js, plus WordPress or headless CMS experience.",
      },
      {
        uk: "Розуміння API, баз даних і базової інфраструктури релізу.",
        en: "A working understanding of APIs, databases, and basic release infrastructure.",
      },
      {
        uk: "Вмієш пояснювати технічні обмеження бізнесу без жаргону.",
        en: "Ability to explain technical constraints to the business without jargon.",
      },
      {
        uk: "Системне мислення: шукаєш першопричину, а не латаєш симптоми.",
        en: "Systematic thinking: finding root causes rather than patching symptoms.",
      },
    ],
    tools: ["Next.js", "TypeScript", "WordPress", "GraphQL", "MySQL", "Docker", "Git"],
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
  {
    slug: "project-manager",
    title: {
      uk: "Project Manager",
      en: "Project Manager",
    },
    excerpt: {
      uk: "Шукаємо людину, яка тримає проєкти в системі: строки, комунікацію і результат без хаосу.",
      en: "We are looking for someone who keeps projects in a system: timelines, communication, and results without chaos.",
    },
    salary: "$[XXX–XXX] / міс",
    hot: false,
    tags: ["ПРОЄКТИ", "REMOTE", "FULL-TIME"],
    heroImage,
    role: [
      {
        uk: "Ви власник поставки: тримаєте строки, якість комунікації і прозорість статусу для команди та клієнта.",
        en: "You own delivery: timelines, communication quality, and status transparency for the team and the client.",
      },
      {
        uk: "Роль потрібна, щоб проєкти не розпадалися на задачі. Ви збираєте процес у систему і не даєте втратити фокус на результаті.",
        en: "The role exists so projects do not fragment into tasks. You turn the process into a system and keep the result in focus.",
      },
    ],
    tasks: [
      {
        uk: "Ведення клієнтських проєктів від Clarity Session до релізу.",
        en: "Run client projects from the Clarity Session through to release.",
      },
      {
        uk: "Планування спринтів, дедлайнів і залежностей між маркетингом, контентом і розробкою.",
        en: "Plan sprints, deadlines, and dependencies across marketing, content, and development.",
      },
      {
        uk: "Регулярний статус клієнту без шуму — що зроблено, що блокує, який наступний крок.",
        en: "Give the client a calm status: what is done, what is blocked, and what happens next.",
      },
      {
        uk: "Збір вимог і фіксація рішень, щоб команда не працювала з усних домовленостей.",
        en: "Capture requirements and decisions so the team is not working from verbal agreements.",
      },
      {
        uk: "Контроль якості здачі: чеклісти, рев’ю, передача в підтримку.",
        en: "Control handover quality with checklists, reviews, and a clean pass into support.",
      },
    ],
    requirements: [
      {
        uk: "2+ роки в проєктному менеджменті digital / IT, є кейси з живими клієнтами.",
        en: "2+ years in digital/IT project management with live client cases.",
      },
      {
        uk: "Вмієш вести кілька проєктів паралельно без втрати деталей.",
        en: "Able to run several projects in parallel without losing the details.",
      },
      {
        uk: "Сильна письмова комунікація: протоколи, статуси, брифи без води.",
        en: "Strong written communication: notes, status updates, and briefs without filler.",
      },
      {
        uk: "Розумієш, як пов’язані маркетинг, контент і розробка в одному проєкті.",
        en: "You understand how marketing, content, and development connect in one project.",
      },
      {
        uk: "Системне мислення: шукаєш першопричину, а не латаєш симптоми.",
        en: "Systematic thinking: finding root causes rather than patching symptoms.",
      },
    ],
    tools: ["Notion", "ClickUp", "Google Meet", "Slack", "Google Sheets", "Figma"],
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
  {
    slug: "content-manager",
    title: {
      uk: "Content Manager",
      en: "Content Manager",
    },
    excerpt: {
      uk: "Шукаємо спеціаліста, який будує контент як систему: сенс, SEO і регулярність без хаотичних постів.",
      en: "We are looking for a specialist who builds content as a system: meaning, SEO, and cadence instead of chaotic posts.",
    },
    salary: "$[XXX–XXX] / міс",
    hot: false,
    tags: ["КОНТЕНТ", "REMOTE", "FULL-TIME"],
    heroImage,
    role: [
      {
        uk: "Ви відповідаєте за контент-систему клієнта: від сенсу і структури до публікації та вимірюваного ефекту.",
        en: "You own the client content system: from meaning and structure through to publishing and measurable effect.",
      },
      {
        uk: "Роль закриває розрив між стратегією і щоденним контентом. Ви робите так, щоб тексти працювали на ріст, а не заповнювали стрічку.",
        en: "The role closes the gap between strategy and daily content. You make texts work for growth rather than filling a feed.",
      },
    ],
    tasks: [
      {
        uk: "Побудова контент-системи: рубрики, меседжі, календар, критерії якості.",
        en: "Build the content system: pillars, messages, calendar, and quality criteria.",
      },
      {
        uk: "Написання та редактура матеріалів для сайту, блогу, розсилок і соцмереж.",
        en: "Write and edit materials for the website, blog, email, and social channels.",
      },
      {
        uk: "SEO-каркас сторінок: структура, Title/H1, внутрішні зв’язки.",
        en: "Build the SEO skeleton of pages: structure, Title/H1, and internal links.",
      },
      {
        uk: "Брифи для дизайну і продакшену, щоб візуал підтримував сенс, а не навпаки.",
        en: "Brief design and production so visuals support meaning rather than the other way around.",
      },
      {
        uk: "Звіт по контенту: що вийшло, що спрацювало, що змінюємо далі.",
        en: "Report on content: what shipped, what worked, and what we change next.",
      },
    ],
    requirements: [
      {
        uk: "2+ роки в контенті / SEO / редактурі, є кейси з вимірюваним результатом.",
        en: "2+ years in content, SEO, or editing with cases that have measurable results.",
      },
      {
        uk: "Сильна українська та англійська письмова мова.",
        en: "Strong written Ukrainian and English.",
      },
      {
        uk: "Розуміння SEO не як списку ключів, а як структури сторінки і сенсу для людини.",
        en: "SEO understood as page structure and meaning for people, not a keyword list.",
      },
      {
        uk: "Вмієш збирати бриф, ставити питання і не писати «в порожнечу».",
        en: "Able to gather a brief, ask questions, and avoid writing into a vacuum.",
      },
      {
        uk: "Системне мислення: шукаєш першопричину, а не латаєш симптоми.",
        en: "Systematic thinking: finding root causes rather than patching symptoms.",
      },
    ],
    tools: ["Notion", "Google Docs", "Ahrefs", "Surfer", "Meta", "Figma"],
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

export function resolveVacancy(record: VacancyRecord, locale: Locale): Vacancy {
  return {
    slug: record.slug,
    title: record.title[locale],
    excerpt: record.excerpt[locale],
    salary: record.salary,
    hot: record.hot,
    tags: record.tags,
    heroImage: record.heroImage,
    role: record.role.map((item) => item[locale]),
    tasks: record.tasks.map((item) => item[locale]),
    requirements: record.requirements.map((item) => item[locale]),
    tools: record.tools,
    benefits: record.benefits.map((item) => item[locale]),
  };
}

export function getVacancyBySlug(slug: string, locale: Locale) {
  const record = vacancies.find((vacancy) => vacancy.slug === slug);
  return record ? resolveVacancy(record, locale) : undefined;
}

export function getFallbackVacancies() {
  return vacancies;
}
