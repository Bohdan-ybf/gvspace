import Image from "next/image";
import Link from "next/link";
import { notFound } from "next/navigation";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";
import { getDynamicSeo } from "@/wordpress-seo";
import { ArrowRight } from "./icons/arrow-right";
import { Breadcrumbs } from "./breadcrumbs";
import { CaseCard } from "./case-card";
import { ReviewsSection } from "./reviews-section";
import { StructuredData } from "./structured-data";
import { TechnologyMeetSection } from "./technology-meet-section";
import { TechnologyShowcaseSection } from "./technology-showcase-section";
import { getCaseStudies, getCaseStudy } from "./wordpress-cases";
import { getHomeFaqs } from "./wordpress-faqs";
import { getServiceOfferings } from "./wordpress-services";
import { getTeamDirectory } from "./wordpress-team";
import {
  caseMatchesTechnology,
  getTechnologyBySlug,
  technologyServiceDirection,
} from "./wordpress-technologies";

function EmphasisText({ text }: { text: string }) {
  const parts = text.split(/(\*\*[^*]+\*\*)/g);
  return (
    <>
      {parts.map((part, index) =>
        part.startsWith("**") && part.endsWith("**") ? (
          <strong key={index}>{part.slice(2, -2)}</strong>
        ) : (
          <span key={index}>{part}</span>
        ),
      )}
    </>
  );
}

function ServicesChevron() {
  return (
    <svg
      className="technology-detail-services-visual"
      viewBox="0 0 360 280"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      aria-hidden="true"
    >
      <defs>
        <linearGradient id="tech-chevron-front" x1="40" y1="40" x2="220" y2="240">
          <stop stopColor="#9BE7FF" />
          <stop offset="0.45" stopColor="#C9B6FF" />
          <stop offset="1" stopColor="#7A5CFF" />
        </linearGradient>
        <linearGradient id="tech-chevron-back" x1="140" y1="20" x2="320" y2="220">
          <stop stopColor="#B9F3FF" />
          <stop offset="1" stopColor="#8F7CFF" />
        </linearGradient>
      </defs>
      <path
        d="M168 36L312 142L168 248V196L248 142L168 88V36Z"
        fill="url(#tech-chevron-back)"
        opacity="0.72"
      />
      <path d="M48 36L192 142L48 248V196L128 142L48 88V36Z" fill="url(#tech-chevron-front)" />
    </svg>
  );
}

export async function TechnologyDetailPage({ locale, slug }: { locale: Locale; slug: string }) {
  const technology = await getTechnologyBySlug(slug, locale);
  if (!technology) notFound();

  const t = getTranslations("technologies", locale);
  const text = getTranslations("global", locale);
  const seo = await getDynamicSeo("technology", slug, locale);
  const directionSlug = technologyServiceDirection(technology.categorySlugs);
  const [relatedCaseOverride, cases, services, team, homeFaqs] = await Promise.all([
    technology.relatedCase
      ? getCaseStudy(technology.relatedCase, locale)
      : Promise.resolve(undefined),
    getCaseStudies(locale),
    getServiceOfferings(locale),
    getTeamDirectory(locale),
    technology.faq.length ? Promise.resolve([]) : getHomeFaqs(locale),
  ]);
  const relatedCase =
    relatedCaseOverride ||
    cases.find((project) => caseMatchesTechnology(project, technology.categorySlugs));
  const relatedServices = services.filter((service) => service.parentSlug === directionSlug);
  const directionIcon = services.find(
    (service) => !service.parentSlug && service.slug === directionSlug,
  )?.image;
  const faqs = technology.faq.length
    ? technology.faq.map((item, index) => ({ ...item, id: index, order: index }))
    : homeFaqs;
  const heading = technology.title;
  const lead = technology.intro || technology.description;
  const logo = technology.image || technology.visual;
  const pageTitle = seo?.title || technology.title;
  const pageDescription = seo?.description || lead;
  const categoryLabels: Record<string, string> = {
    development: locale === "uk" ? "IT" : "IT",
    marketing: locale === "uk" ? "Маркетинг" : "Marketing",
    systems: locale === "uk" ? "Системи" : "Systems",
    content: locale === "uk" ? "Контент" : "Content",
  };
  const categoryName = categoryLabels[technology.categorySlugs[0] ?? ""] || t.detail.catalogLabel;
  const teamCategory =
    directionSlug === "development"
      ? "development"
      : technology.categorySlugs.includes("marketing")
        ? "marketing"
        : technology.categorySlugs[0];
  const teamPeople = team.members
    .filter((member) => (teamCategory ? member.categorySlugs.includes(teamCategory) : true))
    .map((member) => ({
      name: member.name,
      role: member.role,
      quote: "",
      years: "",
      projects: "",
      tags: member.tags,
      photo: member.image || "/images/team/user-none.jpg",
    }));
  const featuredPerson = technology.meet.name
    ? {
        name: technology.meet.name,
        role: technology.meet.role,
        quote: technology.meet.quote,
        years: technology.meet.years,
        projects: technology.meet.projects,
        tags: technology.meet.tags,
        photo: technology.meet.photo || "/images/team/user-none.jpg",
      }
    : undefined;
  const meetPeople = featuredPerson
    ? [featuredPerson, ...teamPeople.filter((member) => member.name !== featuredPerson.name)]
    : teamPeople;
  const caseHighlight = relatedCase?.metrics[0];
  const technologySchema = {
    "@context": "https://schema.org",
    "@type": "WebPage",
    name: pageTitle,
    headline: heading,
    description: pageDescription,
    inLanguage: locale,
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
    <>
      <StructuredData data={faqSchema ? [technologySchema, faqSchema] : technologySchema} />
      <main className="technology-detail-page">
        <section className="technology-detail-hero">
          <div className="container technology-detail-hero-grid">
            <div className="technology-detail-hero-copy">
              <h1>{heading}</h1>
              {lead ? <p>{lead}</p> : null}
            </div>
            {logo ? (
              <div className="technology-detail-logo">
                <Image
                  src={logo}
                  alt={technology.imageAlt || technology.title}
                  width={220}
                  height={220}
                  priority
                  unoptimized={logo.startsWith("http") || logo.endsWith(".svg")}
                />
              </div>
            ) : null}
          </div>
        </section>

        {technology.why ? (
          <section className="technology-detail-why">
            <div className="container">
              <span className="mono">{t.detail.whyEyebrow}</span>
              <p>
                <EmphasisText text={technology.why} />
              </p>
            </div>
          </section>
        ) : null}

        {technology.triggers.length ? (
          <section className="section container technology-detail-triggers">
            <span className="mono">{t.detail.triggersEyebrow}</span>
            <div>
              {technology.triggers.map((trigger, index) => (
                <article key={`${trigger.title}-${index}`}>
                  <small className="mono">
                    {t.detail.triggerLabel} {index + 1}
                  </small>
                  <h2>{trigger.title}</h2>
                  <p>{trigger.description}</p>
                </article>
              ))}
            </div>
          </section>
        ) : null}

        {technology.uses.length ? (
          <section className="section container technology-detail-uses">
            <span className="mono">{t.detail.usesEyebrow}</span>
            <div>
              {technology.uses.map((use, index) => (
                <article key={`${use.title}-${index}`}>
                  <span className="mono">{String(index + 1).padStart(2, "0")}</span>
                  <h2>{use.title}</h2>
                  <p>{use.description}</p>
                </article>
              ))}
            </div>
          </section>
        ) : null}

        {relatedServices.length ? (
          <section className="section container technology-detail-services">
            {directionIcon ? (
              <span className="technology-detail-services-visual">
                <Image
                  src={directionIcon}
                  alt=""
                  width={1000}
                  height={800}
                  sizes="(max-width: 900px) 80vw, 360px"
                  unoptimized
                />
              </span>
            ) : (
              <ServicesChevron />
            )}
            <div>
              <span className="mono">{t.detail.servicesEyebrow}</span>
              <div>
                {relatedServices.map((service) => (
                  <Link
                    href={`/${locale}/services/${directionSlug}/${service.slug}`}
                    key={service.id}
                  >
                    <span>{service.title}</span>
                    <ArrowRight />
                  </Link>
                ))}
              </div>
            </div>
          </section>
        ) : null}

        {relatedCase ? (
          <section className="section container technology-detail-case">
            <div className="technology-detail-case-head">
              <span className="mono">{t.detail.caseEyebrow}</span>
              <Link className="btn btn-primary" href={`/${locale}/cases`}>
                {t.detail.allCases}
                <ArrowRight />
              </Link>
            </div>
            <div className="technology-detail-case-grid">
              {caseHighlight ? (
                <article className="technology-detail-case-highlight">
                  <strong>{caseHighlight.value}</strong>
                  <span>{caseHighlight.label}</span>
                </article>
              ) : relatedCase.result ? (
                <article className="technology-detail-case-highlight">
                  <p>{relatedCase.result}</p>
                </article>
              ) : null}
              <CaseCard locale={locale} project={relatedCase} variant="list" />
            </div>
          </section>
        ) : null}

        <TechnologyMeetSection
          locale={locale}
          title={t.detail.meetTitle}
          people={meetPeople}
          clarityTitle={text.clarity.title}
          clarityDescription={text.clarity.description}
          clarityAction={text.clarity.action}
          yearsLabel={t.detail.meetYearsLabel}
          projectsLabel={t.detail.meetProjectsLabel}
        />

        <ReviewsSection
          locale={locale}
          eyebrow={t.page.reviewsEyebrow}
          title={t.page.reviewsTitle}
        />

        <TechnologyShowcaseSection
          locale={locale}
          title={t.detail.otherTitle}
          excludeSlug={technology.slug}
          initialCategory={technology.categorySlugs[0]}
        />

        {faqs.length ? (
          <section className="section container faq">
            <div className="faq-intro">
              <h2>{text.faq.title}</h2>
              <p>{text.faq.subtitle}</p>
              <Link className="btn btn-primary" href={`/${locale}/contacts`}>
                {text.faq.action}
              </Link>
            </div>
            <div className="faq-list">
              {faqs.map((item, index) => (
                <details key={item.id} open={index === 1}>
                  <summary>
                    {item.question}
                    <span>+</span>
                  </summary>
                  <p>{item.answer}</p>
                </details>
              ))}
            </div>
          </section>
        ) : null}

        {technology.seoLead || technology.seoText ? (
          <section className="mission container">
            {technology.seoLead ? <b>{technology.seoLead}</b> : null}
            {technology.seoText ? <p>{technology.seoText}</p> : null}
          </section>
        ) : null}

        <Breadcrumbs
          locale={locale}
          items={[
            { label: t.detail.catalogLabel, pathname: "/technologies" },
            { label: categoryName },
            { label: technology.title },
          ]}
          visible
        />
      </main>
    </>
  );
}
