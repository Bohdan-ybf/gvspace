import { notFound } from "next/navigation";
import { getDictionary, type Locale } from "@/i18n";
import { ContactSection } from "./contact-section";
import { CasesShowcaseSection } from "./cases-showcase-section";
import { getCaseStudy } from "./wordpress-cases";

import { componentCopy } from "@/i18n/component-copy";
export async function CaseDetailPage({ locale, slug }: { locale: Locale; slug: string }) {
  const data = await getCaseStudy(slug, locale);
  if (!data) notFound();
  const copy = componentCopy[locale]["case-detail-page"];
  const contact = {
    ...getDictionary(locale).contact,
    title: copy.copy1,
    titleSecond: copy.copy2,
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
            <h2>{copy.copy3}</h2>
            <p>{data.challenge}</p>
          </div>
          <div>
            <h3>{copy.copy4}</h3>
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
            <h2>{copy.copy5}</h2>
            <p>{data.discovery}</p>
          </div>
          <div>
            <h3>{copy.copy6}</h3>
            <p>{data.discoveryResult}</p>
          </div>
        </div>
        <div className="case-architecture">
          <h2>{copy.copy7}</h2>
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
          <h2>{copy.copy8}</h2>
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
        eyebrow={copy.copy9}
        title={copy.copy10}
      />
      <ContactSection text={contact} />
    </main>
  );
}
