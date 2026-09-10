import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { ReviewCard } from "./review-card";
import { getClientReviews } from "./wordpress-reviews";

import { componentCopy } from "@/i18n/component-copy";
export async function ReviewsSection({ locale }: { locale: Locale }) {
  const copy = componentCopy[locale]["reviews-section"];
  const reviews = (await getClientReviews(locale)).slice(0, 3);
  if (!reviews.length) return null;
  return (
    <section className="section container home-reviews-section">
      <header>
        <h2>{copy.copy1}</h2>
        <Link className="btn btn-primary" href={`/${locale}/reviews`}>
          {copy.copy2}
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
            readMoreLabel={copy.copy3}
          />
        ))}
      </div>
    </section>
  );
}
