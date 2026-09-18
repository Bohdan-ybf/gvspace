import Image from "next/image";
import { notFound } from "next/navigation";
import type { Locale } from "@/i18n";
import { ContactSection } from "./contact-section";
import { CasesShowcaseSection } from "./cases-showcase-section";
import { getCaseStudy } from "./wordpress-cases";
import { getTranslations } from "@/i18n/pages";
import { getDynamicSeo } from "@/wordpress-seo";
import { Breadcrumbs } from "./breadcrumbs";
import { StructuredData } from "./structured-data";

function splitLine(line: string) {
  const [title, description] = line.split("|").map((part) => part.trim());
  return { title: title || "", description: description || "" };
}

function StageResult({ label, title, description }: { label: string; title: string; description: string }) {
  const body = [title, description].filter(Boolean).join(". ");
  if (!body) return null;
  return (
    <article className="case-result-card">
      <h3>{label}</h3>
      <p>{body}</p>
    </article>
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
  const brief = data.challenge || data.excerpt;
  const processItems = data.tasks.map(splitLine);
  const lead = data.team[0];
  const tags = [data.projectType, data.direction].filter(Boolean);
  const caseSchema = {
    "@context": "https://schema.org",
    "@type": "CreativeWork",
    name: seo?.h1 || data.title,
    description: seo?.description || data.excerpt,
    image: seo?.openGraphImage || data.image,
    datePublished: seo?.datePublished,
    dateModified: seo?.dateModified,
    author: { "@type": "Organization", name: "GVSPACE" },
  };

  return (
    <>
      <StructuredData data={caseSchema} />
      <main className="case-detail-page">
        <section className="case-detail-hero">
          {data.image ? (
            <Image alt="" className="case-detail-hero-image" fill priority sizes="100vw" src={data.image} unoptimized />
          ) : null}
          <div className="case-detail-hero-overlay" />
          <div className="container case-detail-hero-content">
            <div className="case-detail-hero-body">
              <div>
                {tags.length > 0 && (
                  <div className="case-detail-hero-tags">
                    {tags.map((tag) => (
                      <span className="mono" key={tag}>
                        [ {tag} ]
                      </span>
                    ))}
                  </div>
                )}
                <h1>{seo?.h1 || data.title}</h1>
                {data.excerpt ? <p>{data.excerpt}</p> : null}
              </div>
              {data.metrics.length > 0 && (
                <dl>
                  {data.metrics.map((metric) => (
                    <div key={`${metric.value}-${metric.label}`}>
                      <dt>{metric.value}</dt>
                      <dd>{metric.label}</dd>
                    </div>
                  ))}
                </dl>
              )}
            </div>
          </div>
        </section>

        {(brief || data.problems.length > 0) && (
          <section className="container case-brief">
            <div>
              <span className="mono">{t.briefEyebrow}</span>
              <h2>{t.challengeTitle}</h2>
              {brief ? <p>{brief}</p> : null}
            </div>
            {data.problems.length > 0 && (
              <div>
                <h3>{t.problemsTitle}</h3>
                <ul>
                  {data.problems.map((problem) => (
                    <li key={problem}>{problem}</li>
                  ))}
                </ul>
              </div>
            )}
          </section>
        )}

        <section className="container case-story">
          {(data.step1 || data.step1Result.title || data.step1Result.description) && (
            <div className="case-step is-split">
              <div>
                <span className="mono">{t.processEyebrow}</span>
                <h2>{t.step1Title}</h2>
                {data.step1 ? <p>{data.step1}</p> : null}
              </div>
              <StageResult
                description={data.step1Result.description}
                label={t.stageResult}
                title={data.step1Result.title}
              />
            </div>
          )}

          {data.architecture.length > 0 && (
            <div className="case-step is-architecture">
              <h2>{t.step2Title}</h2>
              <div className="case-architecture-cards">
                {data.architecture.map((vector) => (
                  <article key={vector.title}>
                    <h3 className="mono">
                      {t.vectorPrefix} {vector.title}
                    </h3>
                    <p>{vector.description}</p>
                  </article>
                ))}
              </div>
            </div>
          )}

          {(data.step3 || data.step3Result.title || data.step3Result.description || data.gallery.length > 0) && (
            <div className="case-step is-split is-final">
              <div>
                <h2>{t.step3Title}</h2>
                {data.step3 ? <p>{data.step3}</p> : null}
              </div>
              <StageResult
                description={data.step3Result.description}
                label={t.stageResult}
                title={data.step3Result.title}
              />
              {data.gallery.length > 0 && (
                <div className="case-gallery">
                  {data.gallery.slice(0, 2).map((image) => (
                    <div key={image} style={{ backgroundImage: `url(${image})` }} />
                  ))}
                </div>
              )}
            </div>
          )}
        </section>

        {(processItems.length > 0 || lead || data.testimonial) && (
          <section className="container case-process">
            <span className="mono">{t.workEyebrow}</span>
            <div className="case-process-grid">
              <div>
                <h2>{t.processTitle}</h2>
                {processItems.length > 0 && (
                  <ol>
                    {processItems.map((item, index) => (
                      <li key={`${item.title}-${index}`}>
                        <span>{String(index + 1).padStart(2, "0")}</span>
                        <div>
                          <h3>{item.title}</h3>
                          {item.description ? <p>{item.description}</p> : null}
                        </div>
                      </li>
                    ))}
                  </ol>
                )}
              </div>
              {(lead || data.testimonial) && (
                <aside className="case-process-quote">
                  {lead ? (
                    <>
                      <span className="mono">{lead.role}</span>
                      <b>{lead.name}</b>
                    </>
                  ) : null}
                  {data.testimonial ? (
                    <blockquote>
                      <p>{data.testimonial}</p>
                    </blockquote>
                  ) : null}
                </aside>
              )}
            </div>
          </section>
        )}

        {data.testimonial && (
          <section className="case-testimonial">
            <div className="container case-testimonial-grid">
              <div>
                <span className="mono">{t.testimonialEyebrow}</span>
                <h2>{t.testimonialTitle}</h2>
              </div>
              <blockquote>
                <p>{data.testimonial}</p>
                <cite>
                  {lead?.photo ? (
                    <Image alt="" height={48} src={lead.photo} unoptimized width={48} />
                  ) : (
                    <span className="case-testimonial-avatar" />
                  )}
                  <span>
                    <b>{data.testimonialAuthor}</b>
                    {data.testimonialCompany ? <small>{data.testimonialCompany}</small> : null}
                  </span>
                </cite>
              </blockquote>
            </div>
          </section>
        )}

        <CasesShowcaseSection
          locale={locale}
          excludeSlug={data.slug}
          allowExcludedFallback
          actionLabel={t.relatedAction}
          cardVariant="related"
          eyebrow={t.relatedEyebrow}
          title={t.relatedTitle}
        />
        <Breadcrumbs
          visible
          locale={locale}
          items={[
            { label: locale === "uk" ? "Cases" : "Cases", pathname: "/cases" },
            { label: `${seo?.h1 || data.title}${data.direction ? ` — ${data.direction}` : ""}` },
          ]}
        />
        <ContactSection text={contact} />
      </main>
    </>
  );
}
