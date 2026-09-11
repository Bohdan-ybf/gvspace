import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { ReviewCard } from "./review-card";
import { getClientReviews } from "./wordpress-reviews";

import { getTranslations } from "@/i18n/pages";
export async function ReviewsSection({ locale }: { locale: Locale }) {
  const t = getTranslations("reviews", locale).summary;
  const reviews = (await getClientReviews(locale)).slice(0, 3);
  if (!reviews.length) return null;
  return (
    <section className="section container home-reviews-section">
      <header>
        <h2>{t.title}</h2>
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
