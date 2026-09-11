import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { ContactSection } from "./contact-section";
import { TechnologiesCatalog } from "./technologies-catalog";
import { TechnologiesOverviewSection } from "./technologies-overview-section";

import { getTranslations } from "@/i18n/pages";
export function TechnologiesPage({ locale }: { locale: Locale }) {
  const text = getTranslations("global", locale);
  const t = getTranslations("technologies", locale).page;
  const contactText = {
    ...text.contact,
    eyebrow: t.contactEyebrow,
    title: t.contactTitle,
    titleSecond: "на Clarity Session",
  };

  return (
    <main className="technologies-page">
      <section className="technologies-hero">
        <Image src="/images/technologies/hero.webp" alt="" fill priority sizes="100vw" />
        <div className="container technologies-hero-content">
          <span className="mono">TECHNOLOGIES</span>
          <h1>{t.heroTitle}</h1>
          <p>{t.heroDescription}</p>
          <Link className="btn btn-primary" href={`/${locale}/contacts`}>
            {t.heroAction}
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
