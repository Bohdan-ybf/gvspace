import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { AboutPrinciplesSection } from "./about-principles-section";
import { AboutStatsSection } from "./about-stats-section";
import { AgencyComparisonSection } from "./agency-comparison-section";
import { Breadcrumbs } from "./breadcrumbs";
import { ContactSection } from "./contact-section";
import { ArrowRight } from "./icons/arrow-right";
import { SystemTransitionSection } from "./system-transition-section";

import { getTranslations } from "@/i18n/pages";
export function AboutPage({ locale }: { locale: Locale }) {
  const text = getTranslations("global", locale);
  const t = getTranslations("about", locale).page;

  return (
    <main className="about-page">
      <section className="about-hero">
        <Image src="/images/about/hero.webp" alt="" fill priority sizes="100vw" />
        <div className="container about-hero-content">
          <span className="mono">ABOUT US</span>
          <h1>
            GVSPACE
            <br />
            {t.heroTitle}
          </h1>
          <p>{t.heroDescription}</p>
          <Link className="btn btn-primary" href={`/${locale}/contacts`}>
            {text.common.buildSystem}
            <ArrowRight />
          </Link>
        </div>
      </section>
      <AboutStatsSection locale={locale} />
      <SystemTransitionSection locale={locale} />
      <AboutPrinciplesSection locale={locale} />
      <AgencyComparisonSection locale={locale} />
      <Breadcrumbs
        locale={locale}
        items={[{ label: locale === "uk" ? "Про компанію" : "About us" }]}
        visible
      />
      <ContactSection text={text.contact} />
    </main>
  );
}
