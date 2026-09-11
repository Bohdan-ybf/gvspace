import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { CareersBanner } from "./careers-banner";
import { ContactSection } from "./contact-section";
import { ArrowRight } from "./icons/arrow-right";
import { TeamDirectorySection } from "./team-directory-section";
import { TeamFounderSection } from "./team-founder-section";

import { getTranslations } from "@/i18n/pages";
export function TeamPage({ locale }: { locale: Locale }) {
  const text = getTranslations("global", locale);
  const t = getTranslations("team", locale).page;

  return (
    <main className="team-page">
      <section className="team-hero">
        <Image src="/images/team/hero.webp" alt="" fill priority sizes="100vw" />
        <div className="container team-hero-content">
          <span className="mono">TEAM</span>
          <h1>{t.heroTitle}</h1>
          <p>{t.heroDescription}</p>
          <Link className="btn btn-primary" href={`/${locale}/contacts`}>
            {text.common.buildSystem}
            <ArrowRight />
          </Link>
        </div>
      </section>
      <TeamFounderSection locale={locale} />
      <TeamDirectorySection locale={locale} />
      <CareersBanner locale={locale} />
      <ContactSection text={text.contact} />
    </main>
  );
}
