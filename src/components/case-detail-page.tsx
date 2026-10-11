import Link from "next/link";
import { notFound } from "next/navigation";
import type { Locale } from "@/i18n";
import { ContactSection } from "./contact-section";
import { CasesShowcaseSection } from "./cases-showcase-section";
import { getCaseStudy, type CaseMedia, type CaseMetric, type CaseTagLink } from "./wordpress-cases";
import { getTranslations } from "@/i18n/pages";
import { getDynamicSeo } from "@/wordpress-seo";
import { Breadcrumbs } from "./breadcrumbs";
import { StructuredData } from "./structured-data";

function paragraphs(text: string) {
  return text
    .split(/\n{2,}/)
    .map((part) => part.trim())
    .filter(Boolean);
}

function hasMedia(media?: CaseMedia) {
  return Boolean(media?.url);
}

function Slot({ media, className = "" }: { media?: CaseMedia; className?: string }) {
  const url = media?.url ?? "";
  if (!url) return null;
  if (media?.kind === "video") {
    return (
      <div className={`case-slot ${className}`}>
        <video controls playsInline preload="metadata" src={url} />
      </div>
    );
  }
  return (
    <div className={`case-slot ${className}`}>
      <img alt="" src={url} />
    </div>
  );
}

function FactTags({ tags, locale }: { tags: CaseTagLink[]; locale: Locale }) {
  return (
    <dd className="case-sheet-tags">
      {tags.map((tag) =>
        tag.href ? (
          <Link href={`/${locale}${tag.href}`} key={`${tag.href}-${tag.label}`}>
            {tag.label}
          </Link>
        ) : (
          <span key={tag.label}>{tag.label}</span>
        ),
      )}
    </dd>
  );
}

function Metrics({ metrics, divided = false }: { metrics: CaseMetric[]; divided?: boolean }) {
  if (!metrics.length) return null;
  return (
    <dl className={divided ? "case-sheet-metrics is-divided" : "case-sheet-metrics"}>
      {metrics.map((metric) => (
        <div key={`${metric.value}-${metric.label}`}>
          <dt>{metric.value}</dt>
          {metric.label ? <dd>{metric.label}</dd> : null}
        </div>
      ))}
    </dl>
  );
}

export async function CaseDetailPage({ locale, slug }: { locale: Locale; slug: string }) {
  const data = await getCaseStudy(slug, locale);
  if (!data) notFound();
  const seo = await getDynamicSeo("case", slug, locale);
  const t = getTranslations("cases", locale).detail;
  const contact = {
    ...getTranslations("global", locale).contact,
    title: t.contactTitle,
    titleSecond: t.contactTitleSecond,
  };
  const title = seo?.h1 || data.title;
  const facts = [
    [t.factIndustry, data.industry],
    [t.factMarket, data.market],
    [t.factPeriod, data.period],
    [t.factStatus, data.status],
  ].filter((row) => row[1]);
  const showFacts = facts.length > 0 || data.serviceTags.length > 0 || data.techTags.length > 0;
  const step1Title = data.step1Title || t.step1Title;
  const step2Title = data.step2Title || t.step2Title;
  const step3Title = data.step3Title || t.step3Title;
  const showStep1 = Boolean(data.step1 || data.step1Result.title || data.step1Result.description);
  const showStep2 = data.architecture.length > 0 || Boolean(data.step2);
  const showStep3 = Boolean(data.step3 || data.step3Result.title || data.step3Result.description);
  const showProcess = showStep1 || showStep2 || showStep3;
  const briefMedia = data.briefMedia.filter(hasMedia);
  const resultMedia = data.resultMedia.filter(hasMedia);
  const testimonial = data.testimonial.trim();
  const showQuoteAuthor = Boolean(
    data.authorPhoto || data.testimonialAuthor || data.testimonialCompany,
  );
  const showResult = Boolean(
    data.resultTitle || data.resultLead || data.metrics.length || resultMedia.length,
  );

  return (
    <>
      <StructuredData
        data={{
          "@context": "https://schema.org",
          "@type": "CreativeWork",
          name: title,
          description: seo?.description || data.excerpt,
          image: seo?.openGraphImage || data.cover || data.image,
          datePublished: seo?.datePublished,
          dateModified: seo?.dateModified,
          author: { "@type": "Organization", name: "GVSPACE" },
        }}
      />
      <main className="case-detail-page case-sheet">
        <header className="container case-sheet-hero">
          <div className={`case-sheet-hero-top${data.logo ? "" : " is-text-only"}`}>
            <div>
              {data.projectType ? <p className="case-sheet-kicker">{data.projectType}</p> : null}
              <h1>{title}</h1>
              {data.subtitle ? <p>{data.subtitle}</p> : null}
            </div>
            {data.logo ? (
              <div className="case-sheet-logo">
                <img alt="" src={data.logo} />
              </div>
            ) : null}
          </div>
          <Metrics metrics={data.metrics} />
        </header>

        <div className="container case-sheet-body">
          {data.cover ? (
            <Slot
              className="case-sheet-cover"
              media={{ url: data.cover, kind: data.coverKind || "image" }}
            />
          ) : null}

          {data.lead || showFacts ? (
            <section className={`case-sheet-intro${showFacts ? "" : " is-text-only"}`}>
              <div>
                {paragraphs(data.lead).map((part) => (
                  <p key={part}>{part}</p>
                ))}
              </div>
              {showFacts ? (
                <dl>
                  {facts.map(([label, value]) => (
                    <div key={label}>
                      <dt>{label}</dt>
                      <dd>{value}</dd>
                    </div>
                  ))}
                  {data.serviceTags.length > 0 ? (
                    <div>
                      <dt>{t.factServices}</dt>
                      <FactTags locale={locale} tags={data.serviceTags} />
                    </div>
                  ) : null}
                  {data.techTags.length > 0 ? (
                    <div>
                      <dt>{t.factTechnologies}</dt>
                      <FactTags locale={locale} tags={data.techTags} />
                    </div>
                  ) : null}
                </dl>
              ) : null}
            </section>
          ) : null}
        </div>

        {data.challenge || data.problems.length > 0 ? (
          <section className="case-sheet-brief">
            <div className="container">
              <div>
                <span className="mono">{t.briefEyebrow}</span>
                <h2>{t.challengeTitle}</h2>
                {paragraphs(data.challenge).map((part) => (
                  <p key={part}>{part}</p>
                ))}
              </div>
              {data.problems.length > 0 ? (
                <div className="case-sheet-brief-aside">
                  <h3>{t.problemsTitle}</h3>
                  <ul>
                    {data.problems.map((problem) => (
                      <li key={problem}>{problem}</li>
                    ))}
                  </ul>
                </div>
              ) : null}
              {briefMedia.length > 0 ? (
                <div className={`case-sheet-pair${briefMedia.length === 1 ? " is-single" : ""}`}>
                  {briefMedia.map((media, index) => (
                    <Slot key={media.url || index} media={media} />
                  ))}
                </div>
              ) : null}
            </div>
          </section>
        ) : null}

        <div className="container case-sheet-body">
          {data.goalsTitle || data.goals.length > 0 ? (
            <section className="case-sheet-goals">
              <span className="mono">{t.goalsEyebrow}</span>
              {data.goalsTitle ? <h2>{data.goalsTitle}</h2> : null}
              <ol>
                {data.goals.map((goal, index) => (
                  <li key={`${goal.title}-${index}`}>
                    <span>{String(index + 1).padStart(2, "0")}</span>
                    <h3>{goal.title}</h3>
                    {goal.description ? <p>{goal.description}</p> : null}
                  </li>
                ))}
              </ol>
            </section>
          ) : null}
        </div>

        {showProcess ? (
          <section className="case-sheet-process">
            <div className="container">
              <div>
                <span className="mono">{t.processEyebrow}</span>
                <h2>{data.processTitle || t.processHeading}</h2>
              </div>
              {showStep1 ? (
                <article
                  className={hasMedia(data.stepMedia[0]) ? "is-media-right" : "is-text-only"}
                >
                  <div>
                    <h3>{step1Title}</h3>
                    {paragraphs(data.step1).map((part) => (
                      <p key={part}>{part}</p>
                    ))}
                    {data.step1Result.title || data.step1Result.description ? (
                      <div className="case-sheet-result-card">
                        <b>{t.stageResult}</b>
                        <p>
                          {[data.step1Result.title, data.step1Result.description]
                            .filter(Boolean)
                            .join(" ")}
                        </p>
                      </div>
                    ) : null}
                  </div>
                  <Slot media={data.stepMedia[0]} />
                </article>
              ) : null}
              {showStep2 ? (
                <article className={hasMedia(data.stepMedia[1]) ? "is-media-left" : "is-text-only"}>
                  <Slot media={data.stepMedia[1]} />
                  <div>
                    <h3>{step2Title}</h3>
                    {paragraphs(data.step2).map((part) => (
                      <p key={part}>{part}</p>
                    ))}
                    <div className="case-sheet-vectors">
                      {data.architecture.map((vector) => (
                        <div key={vector.title}>
                          <b>
                            {t.vectorPrefix} {vector.title}
                          </b>
                          <p>{vector.description}</p>
                        </div>
                      ))}
                    </div>
                  </div>
                </article>
              ) : null}
              {showStep3 ? (
                <article
                  className={hasMedia(data.stepMedia[2]) ? "is-media-right" : "is-text-only"}
                >
                  <div>
                    <h3>{step3Title}</h3>
                    {paragraphs(data.step3).map((part) => (
                      <p key={part}>{part}</p>
                    ))}
                    {data.step3Result.title || data.step3Result.description ? (
                      <div className="case-sheet-result-card">
                        <b>{t.stageResult}</b>
                        <p className="is-emphasis">
                          {[data.step3Result.title, data.step3Result.description]
                            .filter(Boolean)
                            .join(" ")}
                        </p>
                      </div>
                    ) : null}
                  </div>
                  <Slot media={data.stepMedia[2]} />
                </article>
              ) : null}
            </div>
          </section>
        ) : null}

        <div className="container case-sheet-body">
          {showResult ? (
            <section className="case-sheet-outcome">
              <span className="mono">{t.resultEyebrow}</span>
              {data.resultTitle ? <h2>{data.resultTitle}</h2> : null}
              {paragraphs(data.resultLead).map((part) => (
                <p key={part}>{part}</p>
              ))}
              <Metrics divided metrics={data.metrics} />
              {resultMedia.length > 0 ? (
                <div className={`case-sheet-gallery is-${data.resultLayout}`}>
                  {resultMedia.map((media, index) => (
                    <Slot key={media.url || index} media={media} />
                  ))}
                </div>
              ) : null}
            </section>
          ) : null}
        </div>

        {testimonial ? (
          <section className="case-sheet-quote">
            <div className="container">
              <div>
                <span className="mono">{t.testimonialEyebrow}</span>
                <h2>{t.testimonialTitle}</h2>
              </div>
              <blockquote>
                <p>{testimonial}</p>
                {showQuoteAuthor ? (
                  <footer>
                    {data.authorPhoto ? <img alt="" src={data.authorPhoto} /> : null}
                    <cite>
                      {data.testimonialAuthor ? <b>{data.testimonialAuthor}</b> : null}
                      {data.testimonialCompany ? <small>{data.testimonialCompany}</small> : null}
                    </cite>
                  </footer>
                ) : null}
              </blockquote>
            </div>
          </section>
        ) : null}

        <CasesShowcaseSection
          actionLabel={t.relatedAction}
          allowExcludedFallback
          cardVariant="related"
          excludeSlug={data.slug}
          eyebrow={t.relatedEyebrow}
          locale={locale}
          title={t.relatedTitle}
        />
        <Breadcrumbs
          items={[
            { label: "Cases", pathname: "/cases" },
            { label: `${title}${data.direction ? ` — ${data.direction}` : ""}` },
          ]}
          locale={locale}
          visible
        />
        <ContactSection locale={locale} text={contact} />
      </main>
    </>
  );
}
