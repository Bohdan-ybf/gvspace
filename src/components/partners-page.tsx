import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";
import { Breadcrumbs } from "./breadcrumbs";
import { ArrowRight } from "./icons/arrow-right";

export function PartnersPage({ locale }: { locale: Locale }) {
  const t = getTranslations("partners", locale).page;

  return (
    <main className="partners-page">
      <section className="partners-hero">
        <Image
          className="partners-hero-background"
          src="/images/technologies/technologies-bg.png"
          alt=""
          fill
          priority
          sizes="100vw"
        />
        <Image
          className="partners-hero-object"
          src="/images/partners/rings.png"
          alt=""
          width={510}
          height={487}
          priority
        />
        <div className="container partners-hero-content">
          <span className="mono">{t.heroEyebrow}</span>
          <h1>
            {t.heroTitle}
            <br />
            {t.heroTitleSecond}
          </h1>
          <p>{t.heroDescription}</p>
          <Link className="btn btn-primary" href={`/${locale}/contacts`}>
            {t.heroAction}
            <ArrowRight />
          </Link>
        </div>
      </section>

      <section
        className="section container partners-audience"
        aria-labelledby="partners-audience-title"
      >
        <header>
          <span className="mono">{t.audienceEyebrow}</span>
          <h2 id="partners-audience-title">{t.audienceTitle}</h2>
        </header>
        <div className="partners-audience-grid">
          {t.audience.map((item) => (
            <article key={item.number}>
              <span className="mono">{item.number}</span>
              <h3>{item.title}</h3>
              <p>{item.description}</p>
            </article>
          ))}
        </div>
      </section>

      <section
        className="section container partners-programs"
        aria-labelledby="partners-programs-title"
      >
        <header>
          <span className="mono">{t.programsEyebrow}</span>
          <h2 id="partners-programs-title">{t.programsTitle}</h2>
        </header>
        <div className="partners-programs-grid">
          {t.programs.map((item) => (
            <article key={item.title}>
              <h3>{item.title}</h3>
              <p>{item.description}</p>
            </article>
          ))}
        </div>
      </section>

      <section className="section container partners-steps" aria-labelledby="partners-steps-title">
        <header>
          <span className="mono">{t.stepsEyebrow}</span>
          <h2 id="partners-steps-title">{t.stepsTitle}</h2>
        </header>
        <div className="partners-steps-grid">
          {t.steps.map((step) => (
            <article key={step.number}>
              <span>{step.number}</span>
              <h3>{step.title}</h3>
              <p>{step.description}</p>
            </article>
          ))}
        </div>
      </section>

      <section className="container partners-proof" aria-labelledby="partners-proof-title">
        <div className="partners-proof-card">
          <div>
            <span className="mono">{t.proofEyebrow}</span>
            <blockquote id="partners-proof-title">{t.quote}</blockquote>
            <div className="partners-proof-author">
              <span aria-hidden="true" />
              <div>
                <strong>{t.quoteName}</strong>
                <small>{t.quoteRole}</small>
              </div>
            </div>
          </div>
          <dl>
            {t.stats.map((stat) => (
              <div key={stat.label}>
                <dt>{stat.value}</dt>
                <dd>{stat.label}</dd>
              </div>
            ))}
          </dl>
        </div>
      </section>

      <section className="section container partners-logos" aria-label={t.logosEyebrow}>
        <span className="mono">{t.logosEyebrow}</span>
        <div className="partners-logos-grid">
          {t.logos.map((logo) => (
            <article key={logo.label}>
              <i aria-hidden="true" />
              <div>
                <span className="mono">{logo.label}</span>
                <h3>{logo.name}</h3>
                <p>{logo.description}</p>
              </div>
            </article>
          ))}
        </div>
      </section>

      <section
        className="section container partners-reasons"
        aria-labelledby="partners-reasons-title"
      >
        <header>
          <span className="mono">{t.reasonsEyebrow}</span>
          <h2 id="partners-reasons-title">{t.reasonsTitle}</h2>
        </header>
        <div className="partners-reasons-grid">
          {t.reasons.map((reason) => (
            <article key={reason.number}>
              <span className="mono">{reason.number}</span>
              <h3>{reason.title}</h3>
              <p>{reason.description}</p>
            </article>
          ))}
        </div>
      </section>

      <section className="section container partners-benefits" aria-label={t.benefitsEyebrow}>
        <span className="mono">{t.benefitsEyebrow}</span>
        <div className="partners-benefits-grid">
          {t.benefits.map((benefit) => (
            <article key={benefit.title}>
              <h3>{benefit.title}</h3>
              <p>{benefit.description}</p>
            </article>
          ))}
        </div>
      </section>

      <section className="partners-cta" aria-labelledby="partners-cta-title">
        <div className="partners-cta-copy">
          <div>
            <span className="mono">{t.ctaEyebrow}</span>
            <h2 id="partners-cta-title">{t.ctaTitle}</h2>
          </div>
          <div>
            <p>
              {t.ctaLead}
              <br />
              {t.ctaDescription}
            </p>
            <Link className="btn" href={`/${locale}/contacts`}>
              {t.ctaAction}
              <ArrowRight />
            </Link>
          </div>
        </div>
        <div className="partners-cta-cards">
          {t.ctaCards.map((card) => (
            <article key={card.title}>
              <h3>{card.title}</h3>
              <p>{card.description}</p>
            </article>
          ))}
        </div>
      </section>

      <Breadcrumbs
        locale={locale}
        homeLabel="HOME"
        items={[{ label: "PARTNERSHIP PROGRAM" }]}
        visible
      />
    </main>
  );
}
