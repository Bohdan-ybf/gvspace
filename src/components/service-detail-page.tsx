import Image from "next/image";
import Link from "next/link";
import { notFound } from "next/navigation";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { getServiceOffering } from "./wordpress-services";
import { SystemTransitionSection } from "./system-transition-section";
import { TechnologyShowcaseSection } from "./technology-showcase-section";
import { CasesShowcaseSection } from "./cases-showcase-section";
import { ReviewsSection } from "./reviews-section";
import { ContactSection } from "./contact-section";

import { getTranslations } from "@/i18n/pages";
export async function ServiceDetailPage({ locale, slugs }: { locale: Locale; slugs: string[] }) {
  const result = await getServiceOffering(locale, slugs);
  if (!result) notFound();
  const { item, children } = result;
  const t = getTranslations("services", locale).detail;
  const isDirection = slugs.length === 1;
  const steps = item.steps.length
    ? item.steps
    : [
        {
          title: "Clarity Session",
          duration: t.clarityDuration,
          description: t.clarityDescription,
        },
        {
          title: t.auditTitle,
          duration: "14 days",
          description: t.auditDescription,
        },
        {
          title: t.roadmapTitle,
          duration: t.roadmapDuration,
          description: t.roadmapDescription,
        },
      ];
  const dictionary = getTranslations("global", locale);

  return (
    <main className="service-detail-page">
      <section className={`service-detail-hero${isDirection ? " is-direction" : ""}`}>
        <div className="container service-breadcrumb mono">
          SERVICES / {slugs.map((slug) => slug.toUpperCase()).join(" / ")}
        </div>
        <div className="container service-detail-hero-grid">
          {isDirection && (
            <div className="service-detail-icon">
              {item.image ? (
                <Image src={item.image} alt="" fill sizes="220px" unoptimized />
              ) : (
                <Image src={`/images/services/icons/${item.slug}.webp`} alt="" fill sizes="220px" />
              )}
            </div>
          )}
          <div>
            <h1>{item.headline}</h1>
            <p>{item.description}</p>
            <Link className="btn btn-primary" href={`/${locale}/contacts`}>
              {t.heroAction}
              <ArrowRight />
            </Link>
          </div>
        </div>
      </section>

      {(children.length > 0 || item.includes.length > 0) && (
        <section className="section container service-includes">
          <span className="mono">{t.includesEyebrow}</span>
          <div>
            {(children.length
              ? children.map((child) => ({
                  label: child.title,
                  href: `/${locale}/services/${item.slug}/${child.slug}`,
                }))
              : item.includes.map((label) => ({ label, href: "" }))
            ).map((entry) =>
              entry.href ? (
                <Link href={entry.href} key={entry.label}>
                  <span>{entry.label}</span>
                  <ArrowRight />
                </Link>
              ) : (
                <div key={entry.label}>
                  <span>✓ {entry.label}</span>
                </div>
              ),
            )}
          </div>
        </section>
      )}

      <section className={`section service-steps${isDirection ? " container" : " is-process"}`}>
        <div className={isDirection ? "" : "container"}>
          <span className="mono">{t.processEyebrow}</span>
          <h2>{isDirection ? t.directionProcessTitle : t.serviceProcessTitle}</h2>
          <div>
            {steps.map((step, index) => (
              <article key={`${step.title}-${index}`}>
                <small className="mono">
                  [ 0{index + 1} ]　 {step.duration}
                </small>
                <h3>{step.title}</h3>
                <p>{step.description}</p>
              </article>
            ))}
          </div>
        </div>
      </section>

      {isDirection && <SystemTransitionSection locale={locale} />}
      {!isDirection && item.metrics.length > 0 && (
        <section className="section container service-results">
          <span className="mono">{t.resultsEyebrow}</span>
          <div>
            {item.metrics.map((metric) => (
              <b key={metric}>{metric}</b>
            ))}
          </div>
        </section>
      )}
      <TechnologyShowcaseSection locale={locale} />
      <CasesShowcaseSection locale={locale} eyebrow={t.casesEyebrow} />
      <ReviewsSection locale={locale} />
      {item.faq.length > 0 && (
        <section className="section container service-faq">
          <div>
            <h2>{t.faqTitle}</h2>
            <p>{t.faqDescription}</p>
          </div>
          <div>
            {item.faq.map((faq) => (
              <details key={faq.question}>
                <summary>
                  {faq.question}
                  <span>+</span>
                </summary>
                <p>{faq.answer}</p>
              </details>
            ))}
          </div>
        </section>
      )}
      <ContactSection text={dictionary.contact} />
    </main>
  );
}
