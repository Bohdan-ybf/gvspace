import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { ReviewCard } from "./review-card";
import { getClientReviews } from "./wordpress-reviews";

import { getTranslations } from "@/i18n/pages";
export async function ReviewsSection({
  locale,
  eyebrow,
  title,
}: {
  locale: Locale;
  eyebrow?: string;
  title?: string;
}) {
  const t = getTranslations("reviews", locale).summary;
  const reviews = (await getClientReviews(locale)).slice(0, 3);
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
            readMoreHref={`/${locale}/reviews`}
            readMoreLabel={t.readMore}
          />
        ))}
      </div>
    </section>
  );
}
