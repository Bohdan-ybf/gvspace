import { notFound } from "next/navigation";
import type { Locale } from "@/i18n";
import { ContactSection } from "./contact-section";
import { CasesShowcaseSection } from "./cases-showcase-section";
import { getCaseStudy } from "./wordpress-cases";

import { getTranslations } from "@/i18n/pages";
export async function CaseDetailPage({ locale, slug }: { locale: Locale; slug: string }) {
  const data = await getCaseStudy(slug, locale);
  if (!data) notFound();
  const t = getTranslations("cases", locale).detail;
  const contact = {
    ...getTranslations("global", locale).contact,
    title: t.contactTitle,
    titleSecond: t.contactTitleSecond,
  };
  return (
    <main className="case-detail-page">
      <section className="case-detail-hero">
        <div className="container">
          <p className="mono">CASES / {data.title}</p>
          <div>
            <div>
              <span>
                {data.services.map((item) => (
                  <b key={item}>{item}</b>
                ))}
              </span>
              <h1>{data.title}</h1>
              <h2>{data.result}</h2>
            </div>
            <dl>
              {data.metrics.map((metric) => (
                <div key={metric.label}>
                  <dt>{metric.value}</dt>
                  <dd>{metric.label}</dd>
                </div>
              ))}
            </dl>
          </div>
        </div>
      </section>
      <section className="container case-story">
        <div className="case-two-columns">
          <div>
            <span className="mono">БРИФ</span>
            <h2>{t.challengeTitle}</h2>
            <p>{data.challenge}</p>
          </div>
          <div>
            <h3>{t.problemsTitle}</h3>
            <ul>
              {data.problems.map((problem) => (
                <li key={problem}>— {problem}</li>
              ))}
            </ul>
          </div>
        </div>
        <div className="case-two-columns case-discovery">
          <div>
            <span className="mono">ПРОЦЕС</span>
            <h2>{t.discoveryTitle}</h2>
            <p>{data.discovery}</p>
          </div>
          <div>
            <h3>{t.discoveryResultTitle}</h3>
            <p>{data.discoveryResult}</p>
          </div>
        </div>
        <div className="case-architecture">
          <h2>{t.architectureTitle}</h2>
          <div>
            {data.architecture.map((vector) => (
              <article key={vector.title}>
                <h3>{vector.title}:</h3>
                <p>{vector.description}</p>
              </article>
            ))}
          </div>
        </div>
      </section>
      {data.gallery.length > 0 && (
        <section className="case-gallery">
          <div
            style={{
              backgroundImage: `url(${data.gallery[0]})`,
              backgroundPosition: "center",
              backgroundSize: "cover",
            }}
          />
          <div>
            <div
              style={
                data.gallery[1]
                  ? {
                      backgroundImage: `url(${data.gallery[1]})`,
                      backgroundPosition: "center",
                      backgroundSize: "cover",
                    }
                  : undefined
              }
            />
            <div>
              <span
                style={
                  data.gallery[2]
                    ? {
                        backgroundImage: `url(${data.gallery[2]})`,
                        backgroundPosition: "center",
                        backgroundSize: "cover",
                      }
                    : undefined
                }
              />
              <span
                style={
                  data.gallery[3]
                    ? {
                        backgroundImage: `url(${data.gallery[3]})`,
                        backgroundPosition: "center",
                        backgroundSize: "cover",
                      }
                    : undefined
                }
              />
            </div>
          </div>
        </section>
      )}
      <section className="case-testimonial">
        <div className="container">
          <span className="mono">ВІДГУК</span>
          <h2>{t.testimonialTitle}</h2>
          <blockquote>
            {data.testimonial}
            <b>{data.testimonialAuthor}</b>
          </blockquote>
        </div>
      </section>
      <CasesShowcaseSection
        locale={locale}
        excludeSlug={data.slug}
        allowExcludedFallback
        eyebrow={t.relatedEyebrow}
        title={t.relatedTitle}
      />
      <ContactSection text={contact} />
    </main>
  );
}
