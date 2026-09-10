import { getDictionary, type Locale } from "@/i18n";
import { CasesShowcaseSection } from "./cases-showcase-section";
import { ContactSection } from "./contact-section";
import { ReviewsCatalog } from "./reviews-catalog";
import { getClientReviews } from "./wordpress-reviews";

import { componentCopy } from "@/i18n/component-copy";
export async function ReviewsPage({ locale }: { locale: Locale }) {
  const reviews = await getClientReviews(locale);
  const text = getDictionary(locale);
  const copy = componentCopy[locale]["reviews-page"];
  const companies = [...new Set(reviews.map((review) => review.company).filter(Boolean))].slice(
    0,
    6,
  );

  return (
    <main className="reviews-page">
      <section className="reviews-hero">
        <div className="container reviews-hero-content">
          <span className="mono">CLIENTS &amp; REVIEWS</span>
          <div className="reviews-hero-heading">
            <h1>{copy.copy1}</h1>
            <p>{copy.copy2}</p>
          </div>
          <dl>
            <div>
              <dd>50+</dd>
              <dt>{copy.copy3}</dt>
            </div>
            <div>
              <dd>$10M+</dd>
              <dt>{copy.copy4}</dt>
            </div>
            <div>
              <dd>4</dd>
              <dt>{copy.copy5}</dt>
            </div>
            <div>
              <dd>87%</dd>
              <dt>{copy.copy6}</dt>
            </div>
          </dl>
        </div>
      </section>

      <section className="container review-clients">
        <span className="mono">{copy.copy7}</span>
        <div>
          {(companies.length
            ? companies
            : ["CLIENT 01", "CLIENT 02", "CLIENT 03", "CLIENT 04"]
          ).map((company) => (
            <span key={company}>{company}</span>
          ))}
        </div>
      </section>

      <ReviewsCatalog locale={locale} reviews={reviews} />
      <CasesShowcaseSection locale={locale} />
      <ContactSection
        text={{
          ...text.contact,
          title: copy.copy8,
          titleSecond: copy.copy9,
        }}
      />
    </main>
  );
}
