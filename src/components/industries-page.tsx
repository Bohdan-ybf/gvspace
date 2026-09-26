import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";
import { ArrowRight } from "./icons/arrow-right";
import { CheckMark } from "./icons/check-mark";
import { Breadcrumbs } from "./breadcrumbs";
import { CasesShowcaseSection } from "./cases-showcase-section";
import { ContactSection } from "./contact-section";
import { TechnologyShowcaseSection } from "./technology-showcase-section";
import { getCaseStudies, type CaseStudy } from "./wordpress-cases";

const industryAliases: Record<string, string[]> = {
  ecommerce: ["ecommerce", "e-commerce"],
  saas: ["saas"],
  edtech: ["edtech"],
  "high-ticket": ["high-ticket", "high ticket"],
  health: ["health", "медицина"],
  "real-estate": ["real-estate", "нерухомість"],
};

export async function IndustriesPage({ locale }: { locale: Locale }) {
  const text = getTranslations("global", locale);
  const t = getTranslations("industries", locale).page;
  const cases = await getCaseStudies(locale);
  const contactText = {
    ...text.contact,
    eyebrow: t.contactEyebrow,
    title: t.contactTitle,
    titleSecond: t.contactTitleSecond,
    intro: t.contactIntro,
  };

  return (
    <main className="industries-page">
      <section className="industries-hero">
        <Image
          className="industries-hero-background"
          src="/images/technologies/technologies-bg.png"
          alt=""
          fill
          priority
          sizes="100vw"
        />
        <Image
          className="industries-hero-object"
          src="/images/industries/industries-object.png"
          alt=""
          width={447}
          height={424}
          priority
        />
        <div className="container industries-hero-content">
          <span className="mono">INDUSTRIES</span>
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

      <section className="industries-statement">
        <div className="container">
          <span className="mono">{t.statementEyebrow}</span>
          <p>
            {t.statementLead} <b>{t.statementEmphasis}</b>
          </p>
        </div>
      </section>

      <section
        className="section container industries-list"
        aria-labelledby="industries-list-title"
      >
        <header>
          <span className="mono">{t.listEyebrow}</span>
          <h2 id="industries-list-title">{t.listTitle}</h2>
        </header>
        {t.items.map((item) => {
          const projects = industryProjectCount(cases, item.slug);
          return (
            <article className="industry-row" id={item.slug} key={item.slug}>
              <Image
                className="industry-icon"
                src={`/images/industries/icons/${item.slug}.png`}
                alt=""
                width={280}
                height={280}
              />
              <div className="industry-copy">
                <div className="industry-copy-head">
                  <h3>{item.title}</h3>
                  <span className="mono">
                    [{projects}] {projectsWord(projects, locale)}
                  </span>
                </div>
                <p>{item.description}</p>
                <div className="industry-copy-foot">
                  <ul>
                    {item.tags.map((tag) => (
                      <li className="mono" key={tag}>
                        {tag}
                      </li>
                    ))}
                  </ul>
                  <Link className="btn btn-primary" href={`/${locale}/cases`}>
                    {t.casesAction}
                    <ArrowRight />
                  </Link>
                </div>
              </div>
            </article>
          );
        })}
      </section>

      <section className="industries-process">
        <div className="container">
          <span className="mono">{t.processEyebrow}</span>
          <h2>{t.processTitle}</h2>
          <div className="industries-process-grid">
            {t.steps.map((step) => (
              <article key={step.title}>
                <Image
                  src={`/images/industries/steps/${step.icon}.png`}
                  alt=""
                  width={72}
                  height={72}
                />
                <h3>{step.title}</h3>
                <ul>
                  {step.points.map((point) => (
                    <li key={point}>
                      <CheckMark />
                      {point}
                    </li>
                  ))}
                </ul>
              </article>
            ))}
          </div>
          <div className="industries-process-footer">
            <p className="industries-process-stat">
              <strong>{t.processStat}</strong>
              <span>{t.processStatNote}</span>
            </p>
            <Link className="btn btn-primary" href={`/${locale}/contacts`}>
              {t.heroAction}
            </Link>
          </div>
        </div>
      </section>

      <CasesShowcaseSection locale={locale} eyebrow={t.casesEyebrow} title={t.casesTitle} />
      <TechnologyShowcaseSection locale={locale} />
      <Breadcrumbs
        locale={locale}
        items={[{ label: locale === "uk" ? "Індустрії" : "Industries" }]}
        visible
      />
      <ContactSection text={contactText} />
    </main>
  );
}

function foldIndustry(value: string) {
  return value.toLocaleLowerCase("uk").replace(/[^a-z0-9а-яіїєґ]+/gi, "");
}

function industryProjectCount(cases: CaseStudy[], slug: string) {
  const needles = (industryAliases[slug] ?? [slug]).map(foldIndustry);
  return cases.filter((project) => {
    const haystack = foldIndustry(
      [project.industry, project.projectType].filter(Boolean).join(" "),
    );
    return needles.some((needle) => needle.length > 0 && haystack.includes(needle));
  }).length;
}

function projectsWord(count: number, locale: Locale) {
  if (locale === "en") return count === 1 ? "project" : "projects";
  const mod100 = Math.abs(count) % 100;
  const mod10 = mod100 % 10;
  if (mod100 > 10 && mod100 < 20) return "проєктів";
  if (mod10 > 1 && mod10 < 5) return "проєкти";
  if (mod10 === 1) return "проєкт";
  return "проєктів";
}
