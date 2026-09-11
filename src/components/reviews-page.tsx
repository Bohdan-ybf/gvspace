import type { Locale } from "@/i18n";
import { CasesShowcaseSection } from "./cases-showcase-section";
import { ContactSection } from "./contact-section";
import { ReviewsCatalog } from "./reviews-catalog";
import { getClientReviews } from "./wordpress-reviews";

import { getTranslations } from "@/i18n/pages";
export async function ReviewsPage({ locale }: { locale: Locale }) {
  const reviews = await getClientReviews(locale);
  const text = getTranslations("global", locale);
  const t = getTranslations("reviews", locale).page;
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
            <h1>{t.heroTitle}</h1>
            <p>{t.heroDescription}</p>
          </div>
          <dl>
            <div>
              <dd>50+</dd>
              <dt>{t.projectsLabel}</dt>
            </div>
            <div>
              <dd>$10M+</dd>
              <dt>{t.capitalizationLabel}</dt>
            </div>
            <div>
              <dd>4</dd>
              <dt>{t.directionsLabel}</dt>
            </div>
            <div>
              <dd>87%</dd>
              <dt>{t.recommendationLabel}</dt>
            </div>
          </dl>
        </div>
      </section>

      <section className="container review-clients">
        <span className="mono">{t.clientsEyebrow}</span>
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
          title: t.contactTitle,
          titleSecond: t.contactTitleSecond,
        }}
      />
    </main>
  );
}
