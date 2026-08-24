import Image from "next/image";
import Link from "next/link";
import { getDictionary, type Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { ContactSection } from "./contact-section";
import { TechnologiesCatalog } from "./technologies-catalog";
import { TechnologiesOverviewSection } from "./technologies-overview-section";

export function TechnologiesPage({ locale }: { locale: Locale }) {
  const text = getDictionary(locale);
  const uk = locale === "uk";
  const contactText = {
    ...text.contact,
    eyebrow: uk
      ? "ХОЧЕТЕ ЗРОЗУМІТИ, ЯКІ ІНСТРУМЕНТИ ПОТРІБНІ ВАШОМУ БІЗНЕСУ?"
      : "WANT TO UNDERSTAND WHICH TOOLS YOUR BUSINESS NEEDS?",
    title: uk ? "Розберемо це" : "Let’s figure it out",
    titleSecond: "на Clarity Session",
  };

  return (
    <main className="technologies-page">
      <section className="technologies-hero">
        <Image src="/images/technologies/hero.webp" alt="" fill priority sizes="100vw" />
        <div className="container technologies-hero-content">
          <span className="mono">TECHNOLOGIES</span>
          <h1>
            {uk ? "Правильний інструмент для кожної задачі" : "The right tool for every task"}
          </h1>
          <p>
            {uk
              ? "Ми не використовуємо один стек для всього. Кожен інструмент у нашому арсеналі вирішує конкретну задачу — і тільки її."
              : "We do not use one stack for everything. Every tool in our arsenal solves a specific task — and only that task."}
          </p>
          <Link className="btn btn-primary" href={`/${locale}/contacts`}>
            {uk ? "Обговорити ваш проєкт" : "Discuss your project"}
            <ArrowRight />
          </Link>
        </div>
      </section>
      <TechnologiesOverviewSection locale={locale} />
      <TechnologiesCatalog locale={locale} />
      <ContactSection text={contactText} />
    </main>
  );
}
