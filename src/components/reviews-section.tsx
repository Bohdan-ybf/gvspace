import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { ReviewCard } from "./review-card";
import { getClientReviews, reviewMatchesTags } from "./wordpress-reviews";

import { getTranslations } from "@/i18n/pages";
export async function ReviewsSection({
  locale,
  eyebrow,
  title,
  tags = [],
  slugs = [],
  limit = 3,
}: {
  locale: Locale;
  eyebrow?: string;
  title?: string;
  tags?: string[];
  slugs?: string[];
  limit?: number;
}) {
  const t = getTranslations("reviews", locale).summary;
  const allReviews = await getClientReviews(locale);
  const selected = new Map(slugs.map((slug, index) => [slug, index]));
  const reviews = selected.size
    ? allReviews
        .filter((review) => selected.has(review.slug))
        .sort((left, right) => (selected.get(left.slug) ?? 0) - (selected.get(right.slug) ?? 0))
    : allReviews.filter((review) => reviewMatchesTags(review, tags)).slice(0, limit);
  if (!reviews.length) return null;
  return (
    <section className="section container home-reviews-section">
      <header>
        <div>
          {eyebrow ? <span className="mono">{eyebrow}</span> : null}
          <h2>{title ?? t.title}</h2>
        </div>
        <Link className="btn btn-primary" href={`/${locale}/reviews`}>
          {t.allReviews}
          <ArrowRight />
        </Link>
      </header>
      <div>
        {reviews.map((review) => (
          <ReviewCard
            compact
            key={review.slug}
            review={review}
            closeLabel={t.close}
            readMoreLabel={t.readMore}
          />
        ))}
      </div>
    </section>
  );
}
