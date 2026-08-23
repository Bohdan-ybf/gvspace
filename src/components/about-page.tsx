import Image from "next/image";
import Link from "next/link";
import { getDictionary, type Locale } from "@/i18n";
import { AboutPrinciplesSection } from "./about-principles-section";
import { AboutStatsSection } from "./about-stats-section";
import { AgencyComparisonSection } from "./agency-comparison-section";
import { ContactSection } from "./contact-section";
import { ArrowRight } from "./icons/arrow-right";
import { SystemTransitionSection } from "./system-transition-section";

export function AboutPage({ locale }: { locale: Locale }) {
  const text = getDictionary(locale);
  const uk = locale === "uk";

  return (
    <main className="about-page">
      <section className="about-hero">
        <Image src="/images/about/hero.webp" alt="" fill priority sizes="100vw" />
        <div className="container about-hero-content">
          <span className="mono">ABOUT US</span>
          <h1>
            GVSPACE
            <br />
            {uk ? "Будуємо ваші цифрові екосистеми" : "We build your digital ecosystems"}
          </h1>
          <p>
            {uk
              ? "Ми — ваш стратегічний партнер, який перетворює хаос маркетингу на прогнозовану систему зростання."
              : "We are your strategic partner, turning marketing chaos into a predictable growth system."}
          </p>
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
      <ContactSection text={text.contact} />
    </main>
  );
}
