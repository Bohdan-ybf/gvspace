import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { CasesShowcaseSection } from "./cases-showcase-section";
import { ContactSection } from "./contact-section";
import { ReviewsCatalog } from "./reviews-catalog";
import { getClientReviews } from "./wordpress-reviews";

import { getTranslations } from "@/i18n/pages";
import { getLocalizedUrl } from "@/markets";
import { ItemListStructuredData } from "./structured-data";
export async function ReviewsPage({ locale }: { locale: Locale }) {
  const reviews = await getClientReviews(locale);
  const text = getTranslations("global", locale);
  const t = getTranslations("reviews", locale).page;
  const companies = [...new Set(reviews.map((review) => review.company).filter(Boolean))].slice(
    0,
    6,
  );

  return (
    <>
      <ItemListStructuredData
        name={locale === "uk" ? "Відгуки клієнтів GVSPACE" : "GVSPACE client reviews"}
        items={reviews.map((review) => ({
          name: review.name,
          url: getLocalizedUrl(locale, `/reviews#${review.slug}`),
        }))}
      />
      <main className="reviews-page">
        <section className="reviews-hero">
          <Image
            className="reviews-hero-background"
            src="/images/reviews/reviews-bg.png"
            alt=""
            fill
            priority
            sizes="100vw"
          />
          <Image
            className="reviews-hero-object"
            src="/images/reviews/reviews-object.png"
            alt=""
            width={259}
            height={245}
            priority
            sizes="(max-width: 600px) 190px, (max-width: 900px) 220px, 259px"
          />
          <div className="container reviews-hero-content">
            <span className="mono">CLIENTS &amp; REVIEWS</span>
            <div className="reviews-hero-heading">
              <div>
                <h1>{t.heroTitle}</h1>
                <p>{t.heroDescription}</p>
              </div>
              <Link className="btn btn-primary" href={`/${locale}/contacts`}>
                {t.heroAction}
                <svg width="12" height="13" viewBox="0 0 12 13" fill="none" aria-hidden="true">
                  <path
                    d="M6 1V12M1.5 7.5L6 12L10.5 7.5"
                    stroke="currentColor"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  />
                </svg>
              </Link>
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
    </>
  );
}
