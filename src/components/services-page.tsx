import Image from "next/image";
import Link from "next/link";
import { getDictionary, type Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { CasesSection } from "./cases-section";
import { ContactSection } from "./contact-section";
import { ServicesNavigation } from "./services-navigation";
import { SystemTransitionSection } from "./system-transition-section";
import { TechnologyShowcaseSection } from "./technology-showcase-section";
import { getServiceOfferings } from "./wordpress-services";
import { ReviewsSection } from "./reviews-section";

const ukDirections = [
  {
    slug: "strategy",
    title: "Стратегія",
    description:
      "Ми проводимо глибоку діагностику вашої Unit-економіки та воронки, щоб не просто бачити, куди інвестувати кожен долар для масштабування.",
    services: [
      "Стратегічний аудит",
      "Комплексний Digital-аудит",
      "Аналіз ринку та конкурентне позиціонування",
      "Clarity Session",
      "Архітектура зростання (Roadmap)",
      "Аудит маркетингових процесів",
    ],
  },
  {
    slug: "marketing",
    title: "Маркетинг",
    description:
      "Ми будуємо Performance-стратегії, що базуються на цифрах, а не на гіпотезах. Впроваджуємо наскрізну аналітику та рекламні інструменти, які дозволяють зростати без хаосу.",
    services: [
      "Performance Marketing (Meta & Google Ads)",
      "Побудова системної аналітики & Dashboards",
      "SMM Стратегія та присутність",
      "SEO-просування",
      "Retention & CRM Маркетинг",
    ],
  },
  {
    slug: "development",
    title: "IT-розробка",
    description:
      "Ми створюємо відмовостійкі цифрові системи, до яких бізнес може підключати маркетинг без страху технічних обмежень.",
    services: [
      "Розробка корпоративних сайтів та лендингів",
      "Розробка складних систем (CRM, ERP, Dashboards)",
      "Технічна підтримка та інфраструктура",
      "E-commerce рішення (Інтернет-магазини)",
      "Product Discovery & Архітектура",
    ],
  },
  {
    slug: "content",
    title: "Контент & Продакшн",
    description:
      "Ми не просто створюємо візуал — ми будуємо систему комунікації, яка пояснює цінність продукту без зайвих слів.",
    services: [
      "Бренд-дизайн та Візуальна айдентика",
      "Фото-продакшн (Food, Product, Lifestyle)",
      "Креативні концепції та спецпроєкти",
      "Video Production (Рекламні та іміджеві ролики)",
      "Копірайтинг & Storytelling",
    ],
  },
];

const enDirections = [
  {
    slug: "strategy",
    title: "Strategy",
    description:
      "We diagnose your unit economics and funnel to reveal where every dollar should be invested for growth.",
    services: [
      "Strategic audit",
      "Comprehensive digital audit",
      "Market analysis and competitive positioning",
      "Clarity Session",
      "Growth architecture (Roadmap)",
      "Marketing process audit",
    ],
  },
  {
    slug: "marketing",
    title: "Marketing",
    description:
      "We build data-led performance strategies and marketing systems that scale without chaos.",
    services: [
      "Performance Marketing (Meta & Google Ads)",
      "Analytics systems & Dashboards",
      "SMM strategy and presence",
      "SEO promotion",
      "Retention & CRM Marketing",
    ],
  },
  {
    slug: "development",
    title: "IT Development",
    description:
      "We create resilient digital systems that let businesses connect marketing without technical limitations.",
    services: [
      "Corporate websites and landing pages",
      "Complex systems (CRM, ERP, Dashboards)",
      "Technical support and infrastructure",
      "E-commerce solutions",
      "Product Discovery & Architecture",
    ],
  },
  {
    slug: "content",
    title: "Content & Production",
    description:
      "We build a communication system that conveys product value without unnecessary words.",
    services: [
      "Brand design and visual identity",
      "Photo production",
      "Creative concepts and special projects",
      "Video Production",
      "Copywriting & Storytelling",
    ],
  },
];

export async function ServicesPage({ locale }: { locale: Locale }) {
  const uk = locale === "uk";
  const serviceItems = await getServiceOfferings(locale);
  const directionSlugs = ["strategy", "marketing", "development", "content"];
  const staticDirections = uk ? ukDirections : enDirections;
  const dynamicDirections = serviceItems.filter((item) => !item.parentSlug && directionSlugs.includes(item.slug)).sort((a, b) => directionSlugs.indexOf(a.slug) - directionSlugs.indexOf(b.slug)).map((item) => ({
    slug: item.slug,
    title: item.title,
    description: item.description || staticDirections.find((direction) => direction.slug === item.slug)?.description || "",
    image: item.image,
    services: serviceItems.filter((child) => child.parentSlug === item.slug),
  }));
  const hasDynamicChildren = dynamicDirections.some((direction) => direction.services.length);
  const directions = hasDynamicChildren ? dynamicDirections : staticDirections.map((direction) => ({ ...direction, image: undefined, services: direction.services.map((title, index) => ({ id: index, slug: "", title })) }));
  const text = getDictionary(locale);
  return (
    <main className="services-page">
      <section className="services-hero">
        <Image src="/images/services/hero.webp" alt="" fill priority sizes="100vw" />
        <div className="container services-hero-copy">
          <span className="mono">SERVICES & SOLUTIONS</span>
          <h1>
            {uk
              ? "Наші вектори для керованого масштабування"
              : "Our vectors for manageable scaling"}
          </h1>
          <p>
            {uk
              ? "Ми не просто закриваємо задачі. Ми інтегруємо маркетинг, IT та контент у єдину систему, де кожен елемент підсилює інший."
              : "We integrate marketing, IT, and content into one system where every element reinforces the others."}
          </p>
          <Link className="btn btn-primary" href={`/${locale}/contacts`}>
            {uk ? "Обговорити ваш проєкт" : "Discuss your project"}
            <ArrowRight />
          </Link>
        </div>
      </section>
      <ServicesNavigation items={directions} />
      <section className="service-directions">
        {directions.map((direction, index) => (
          <article className="service-direction container" id={direction.slug} key={direction.slug}>
            <div className="service-direction-copy">
              <div className="service-symbol" aria-hidden="true">
                <Image
                  src={direction.image || `/images/services/icons/${direction.slug}.webp`}
                  alt=""
                  fill
                  sizes="92px"
                />
              </div>
              <div>
                <span className="mono service-number">[0{index + 1}]</span>
                <h2>{direction.title}</h2>
                <Link className="btn" href={`/${locale}/services/${direction.slug}`}>
                  {uk ? "Дізнатись більше" : "Learn more"}
                  <ArrowRight />
                </Link>
              </div>
              <p>{direction.description}</p>
            </div>
            <ul>
              {direction.services.map((service) => (
                <li key={service.id || service.title}>
                  {service.slug ? <Link href={`/${locale}/services/${direction.slug}/${service.slug}`}><span>{service.title}</span><ArrowRight /></Link> : <><span>{service.title}</span><ArrowRight /></>}
                </li>
              ))}
            </ul>
          </article>
        ))}
      </section>
      <SystemTransitionSection locale={locale} />
      <TechnologyShowcaseSection locale={locale} />
      <CasesSection locale={locale} text={text.cases} />
      <ReviewsSection locale={locale} />
      <ContactSection text={text.contact} />
    </main>
  );
}
