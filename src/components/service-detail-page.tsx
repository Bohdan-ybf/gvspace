import Image from "next/image";
import Link from "next/link";
import { Suspense } from "react";
import { notFound } from "next/navigation";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { CheckMark } from "./icons/check-mark";
import { getServiceOffering } from "./wordpress-services";
import { SystemTransitionSection } from "./system-transition-section";
import { TechnologyShowcaseSection } from "./technology-showcase-section";
import { CasesShowcaseSection } from "./cases-showcase-section";
import { ReviewsSection } from "./reviews-section";
import { ServiceInsightsSection } from "./service-insights-section";
import { ContactSection } from "./contact-section";
import { LeadModalButton } from "./lead-modal";

import { getTranslations } from "@/i18n/pages";
import { getDynamicSeo } from "@/wordpress-seo";
import { Breadcrumbs, type BreadcrumbItem } from "./breadcrumbs";
import { ContentImageLabel } from "./content-image-label";
import { contentImageLabel } from "@/content-images";
import { StructuredData } from "./structured-data";

function localizeStageLabel(label: string, locale: Locale): string {
  if (locale !== "uk") return label;
  const labels: Record<string, string> = {
    "early stage": "Ранній етап",
    "growth stage": "Етап зростання",
    "scale stage": "Етап масштабування",
  };
  return labels[label.trim().toLowerCase()] ?? label;
}

export async function ServiceDetailPage({ locale, slugs }: { locale: Locale; slugs: string[] }) {
  const [result, seo] = await Promise.all([
    getServiceOffering(locale, slugs),
    getDynamicSeo("service", slugs.at(-1) ?? "", locale),
  ]);
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
  const faqs = item.faq;
  const serviceBreadcrumbs: BreadcrumbItem[] = [
    { label: locale === "uk" ? "Послуги" : "Services", pathname: "/services" },
    ...(item.parentSlug
      ? [{ label: item.parentSlug, pathname: `/services/${item.parentSlug}` }]
      : []),
    { label: seo?.h1 || item.title },
  ];
  const serviceSchema = {
    "@context": "https://schema.org",
    "@type": "Service",
    name: seo?.h1 || item.title,
    description: seo?.description || item.description,
    provider: { "@type": "Organization", name: "GVSPACE" },
  };
  const faqSchema = faqs.length
    ? {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        mainEntity: faqs.map((faq) => ({
          "@type": "Question",
          name: faq.question,
          acceptedAnswer: { "@type": "Answer", text: faq.answer },
        })),
      }
    : undefined;

  return (
    <ContentImageLabel label={contentImageLabel(seo?.h1 || item.headline)}>
      <>
        <StructuredData data={faqSchema ? [serviceSchema, faqSchema] : serviceSchema} />
        <main
          className={`service-detail-page${isDirection ? " is-direction-page" : " is-service-page"}`}
        >
          <section
            className={`service-detail-hero${isDirection ? " is-direction" : " is-service"}`}
          >
            <Image
              className="service-detail-hero-background"
              src="/images/services/poslugi-l2.webp"
              alt=""
              fill
              priority
              sizes="100vw"
            />
            <div className="container service-detail-hero-grid">
              <div className="service-detail-icon">
                {item.image && (
                  <Image src={item.image} alt={item.title} fill preload sizes="520px" />
                )}
              </div>
              <div className="service-detail-copy">
                <span className="mono service-detail-eyebrow">{item.title}</span>
                <h1>{seo?.h1 || item.headline}</h1>
                <p>{item.description}</p>
                <LeadModalButton className="btn btn-primary">
                  {t.heroAction}
                  <ArrowRight />
                </LeadModalButton>
              </div>
            </div>
          </section>

          {!isDirection && item.fitCards.length > 0 && (
            <section className="section container service-fit">
              <span className="mono">{t.fitEyebrow}</span>
              <div>
                {item.fitCards.map((card) => (
                  <article key={`${card.label}-${card.title}`}>
                    <small className="mono">{localizeStageLabel(card.label, locale)}</small>
                    <h2>{card.title}</h2>
                    <p>{card.description}</p>
                  </article>
                ))}
              </div>
            </section>
          )}

          {(children.length > 0 || item.includes.length > 0) && (
            <section className="section container service-includes">
              <span className="mono">
                {isDirection ? t.includesEyebrow : t.serviceIncludesEyebrow}
              </span>
              <div>
                {(children.length
                  ? children.map((child) => ({
                      label: child.title,
                      href: `/services/${item.slug}/${child.slug}`,
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
                      <CheckMark />
                      <span>{entry.label}</span>
                    </div>
                  ),
                )}
              </div>
            </section>
          )}

          <section
            className={`section service-steps${isDirection ? " container" : ` is-process${steps.length === 3 ? " has-three-steps" : ""}`}`}
          >
            <div className={isDirection ? "" : "container"}>
              <span className="mono">
                {isDirection ? t.processEyebrow : t.serviceProcessEyebrow}
              </span>
              <h2>{isDirection ? t.directionProcessTitle : t.serviceProcessTitle}</h2>
              <div>
                {steps.map((step, index) => (
                  <article key={`${step.title}-${index}`}>
                    <small className="mono">
                      <span>0{index + 1}</span>
                      <span>{step.duration}</span>
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
          {isDirection ? (
            <>
              <Suspense fallback={null}>
                <TechnologyShowcaseSection locale={locale} />
              </Suspense>
              <Suspense fallback={null}>
                <CasesShowcaseSection
                  locale={locale}
                  eyebrow={t.casesEyebrow}
                  actionLabel={t.casesAction}
                />
              </Suspense>
            </>
          ) : (
            <>
              <Suspense fallback={null}>
                <CasesShowcaseSection
                  locale={locale}
                  eyebrow={t.casesEyebrow}
                  actionLabel={t.casesAction}
                />
              </Suspense>
              <Suspense fallback={null}>
                <TechnologyShowcaseSection locale={locale} />
              </Suspense>
            </>
          )}
          <Suspense fallback={null}>
            <ReviewsSection
              locale={locale}
              tags={[item.parentSlug, item.slug].filter((tag): tag is string => Boolean(tag))}
            />
          </Suspense>
          {!isDirection && (
            <Suspense fallback={null}>
              <ServiceInsightsSection locale={locale} />
            </Suspense>
          )}
          {faqs.length > 0 && (
            <section className="section container service-faq">
              <div>
                <h2>{t.faqTitle}</h2>
                <p>{t.faqDescription}</p>
                <LeadModalButton className="btn btn-primary">
                  {dictionary.faq.action}
                </LeadModalButton>
              </div>
              <div>
                {faqs.map((faq, index) => (
                  <details key={faq.question} name="service-faq" open={index === 1}>
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
          <Breadcrumbs locale={locale} items={serviceBreadcrumbs} visible />
          <ContactSection locale={locale} text={dictionary.contact} />
        </main>
      </>
    </ContentImageLabel>
  );
}
