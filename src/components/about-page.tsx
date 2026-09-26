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
import { getTeamDirectory } from "./wordpress-team";

import { getTranslations } from "@/i18n/pages";
export async function AboutPage({ locale }: { locale: Locale }) {
  const text = getTranslations("global", locale);
  const t = getTranslations("about", locale).page;
  const team = await getTeamDirectory(locale);

  return (
    <main className="about-page">
      <section className="about-hero">
        <Image
          className="about-hero-background"
          src="/images/about/about-bg.png"
          alt=""
          fill
          priority
          sizes="100vw"
        />
        <Image
          className="about-hero-pattern"
          src="/images/about/about-object.svg"
          alt=""
          fill
          priority
          sizes="100vw"
          unoptimized
        />
        <div className="container about-hero-content">
          <span className="mono">ABOUT US</span>
          <h1>
            <span className="about-hero-brand">GVSPACE</span>
            <span>{t.heroTitle}</span>
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
      <AboutPrinciplesSection locale={locale} members={team.members} />
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
