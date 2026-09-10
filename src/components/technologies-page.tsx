import Image from "next/image";
import Link from "next/link";
import { getDictionary, type Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { ContactSection } from "./contact-section";
import { TechnologiesCatalog } from "./technologies-catalog";
import { TechnologiesOverviewSection } from "./technologies-overview-section";

import { componentCopy } from "@/i18n/component-copy";
export function TechnologiesPage({ locale }: { locale: Locale }) {
  const text = getDictionary(locale);
  const copy = componentCopy[locale]["technologies-page"];
  const contactText = {
    ...text.contact,
    eyebrow: copy.copy1,
    title: copy.copy2,
    titleSecond: "на Clarity Session",
  };

  return (
    <main className="technologies-page">
      <section className="technologies-hero">
        <Image src="/images/technologies/hero.webp" alt="" fill priority sizes="100vw" />
        <div className="container technologies-hero-content">
          <span className="mono">TECHNOLOGIES</span>
          <h1>{copy.copy3}</h1>
          <p>{copy.copy4}</p>
          <Link className="btn btn-primary" href={`/${locale}/contacts`}>
            {copy.copy5}
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
