import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { CasesShowcaseSection } from "./cases-showcase-section";
import { ContactSection } from "./contact-section";
import { Breadcrumbs } from "./breadcrumbs";
import { ArrowRight } from "./icons/arrow-right";
import { ReviewsCatalog } from "./reviews-catalog";
import { ReviewSubmitForm } from "./review-submit-form";
import { getClientReviews, type ClientReview } from "./wordpress-reviews";

import { getTranslations } from "@/i18n/pages";
import { getLocalizedUrl } from "@/markets";
import { ItemListStructuredData } from "./structured-data";
import { ContentImageLabel } from "./content-image-label";
import { contentImageLabel } from "@/content-images";
export async function ReviewsPage({ locale }: { locale: Locale }) {
  const reviews = await getClientReviews(locale);
  const text = getTranslations("global", locale);
  const reviewsText = getTranslations("reviews", locale);
  const t = reviewsText.page;
  const clients = reviewClients(reviews);

  return (
    <ContentImageLabel label={contentImageLabel(t.heroTitle)}>
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
              src="/images/reviews/reviews.webp"
              alt=""
              fill
              priority
              sizes="100vw"
            />
            <Image
              className="reviews-hero-object"
              src="/images/reviews/reviews-object.webp"
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
                <Link className="btn btn-primary" href="#leave-review">
                  {t.heroAction}
                  <ArrowRight />
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

          {clients.length > 0 && (
            <section className="container review-clients">
              <span className="mono">{t.clientsEyebrow}</span>
              <div>
                {clients.map((client) =>
                  client.logo ? (
                    <span className="review-client is-logo" key={client.slug}>
                      <Image
                        src={client.logo}
                        alt={client.company || client.name}
                        width={140}
                        height={36}
                        unoptimized={client.logo.endsWith(".svg")}
                      />
                    </span>
                  ) : (
                    <span key={client.slug}>{client.company}</span>
                  ),
                )}
              </div>
            </section>
          )}

          <ReviewsCatalog locale={locale} reviews={reviews} />
          <CasesShowcaseSection locale={locale} />
          <ReviewSubmitForm copy={reviewsText.form} />
          <Breadcrumbs locale={locale} items={[{ label: reviewsText.summary.title }]} visible />
          <ContactSection
            locale={locale}
            text={{
              ...text.contact,
              title: t.contactTitle,
              titleSecond: t.contactTitleSecond,
            }}
          />
        </main>
      </>
    </ContentImageLabel>
  );
}

function reviewClients(reviews: ClientReview[]) {
  const seen = new Set<string>();
  return reviews
    .filter((review) => {
      const key = (review.company || review.logo || "").trim().toLowerCase();
      if (!key || seen.has(key)) return false;
      seen.add(key);
      return true;
    })
    .slice(0, 6);
}
