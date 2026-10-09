import { notFound } from "next/navigation";
import type { Locale } from "@/i18n";
import { ContactSection } from "./contact-section";
import { CasesShowcaseSection } from "./cases-showcase-section";
import { getCaseStudy, type CaseMedia, type CaseMetric } from "./wordpress-cases";
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

function Slot({
  media,
  label,
  hint,
  className = "",
}: {
  media?: CaseMedia;
  label?: string;
  hint?: string;
  className?: string;
}) {
  const url = media?.url ?? "";
  if (url && media?.kind === "video") {
    return (
      <div className={`case-slot ${className}`}>
        <video controls playsInline preload="metadata" src={url} />
      </div>
    );
  }
  if (url) {
    return (
      <div className={`case-slot ${className}`}>
        <img alt="" src={url} />
      </div>
    );
  }
  return (
    <div className={`case-slot is-empty ${className}`}>
      {label ? <strong>{label}</strong> : null}
      {hint ? <small>{hint}</small> : null}
    </div>
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
  const mediaLabel = data.mediaLabel || t.mediaLabel;
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
  const showResult = Boolean(
    data.resultTitle || data.resultLead || data.metrics.length || data.resultMedia.length,
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
          <div className="case-sheet-hero-top">
            <div>
              {data.projectType ? <p className="case-sheet-kicker">{data.projectType}</p> : null}
              <h1>{title}</h1>
              {data.subtitle ? <p>{data.subtitle}</p> : null}
            </div>
            <div className="case-sheet-logo">
              {data.logo ? <img alt="" src={data.logo} /> : <span>{t.logoPlaceholder}</span>}
            </div>
          </div>
          <Metrics metrics={data.metrics} />
        </header>

        <div className="container case-sheet-body">
          <Slot
            className="case-sheet-cover"
            label={data.cover ? "" : t.galleryPlaceholder}
            media={{ url: data.cover, kind: data.coverKind || "image" }}
          />

          {data.lead || showFacts ? (
            <section className="case-sheet-intro">
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
                      <dd className="case-sheet-tags">
                        {data.serviceTags.map((tag) => (
                          <span key={tag}>{tag}</span>
                        ))}
                      </dd>
                    </div>
                  ) : null}
                  {data.techTags.length > 0 ? (
                    <div>
                      <dt>{t.factTechnologies}</dt>
                      <dd className="case-sheet-tags">
                        {data.techTags.map((tag) => (
                          <span key={tag}>{tag}</span>
                        ))}
                      </dd>
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
              <div className="case-sheet-pair">
                <Slot hint={t.mediaHint} label={mediaLabel} media={data.briefMedia[0]} />
                <Slot hint={t.mediaHint} label={mediaLabel} media={data.briefMedia[1]} />
              </div>
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
                <article className="is-media-right">
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
                  <Slot hint={t.mediaHintWide} label={mediaLabel} media={data.stepMedia[0]} />
                </article>
              ) : null}
              {showStep2 ? (
                <article className="is-media-left">
                  <Slot hint={t.mediaHintWide} label={mediaLabel} media={data.stepMedia[1]} />
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
                <article className="is-media-right">
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
                  <Slot hint={t.mediaHintWide} label={mediaLabel} media={data.stepMedia[2]} />
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
              <div className={`case-sheet-gallery is-${data.resultLayout}`}>
                {Array.from({ length: data.resultLayout === "two-three" ? 5 : 3 }, (_, index) => (
                  <Slot key={index} label={t.galleryPlaceholder} media={data.resultMedia[index]} />
                ))}
              </div>
            </section>
          ) : null}
        </div>

        {data.testimonial ? (
          <section className="case-sheet-quote">
            <div className="container">
              <div>
                <span className="mono">{t.testimonialEyebrow}</span>
                <h2>{t.testimonialTitle}</h2>
              </div>
              <blockquote>
                <p>{data.testimonial}</p>
                <footer>
                  {data.authorPhoto ? <img alt="" src={data.authorPhoto} /> : <span />}
                  <cite>
                    <b>{data.testimonialAuthor}</b>
                    {data.testimonialCompany ? <small>{data.testimonialCompany}</small> : null}
                  </cite>
                </footer>
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
