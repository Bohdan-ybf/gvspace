import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { ReviewCard } from "./review-card";
import { getClientReviews } from "./wordpress-reviews";

export async function ReviewsSection({ locale }: { locale: Locale }) {
  const reviews = (await getClientReviews(locale)).slice(0, 3);
  if (!reviews.length) return null;
  return <section className="section container home-reviews-section">
    <header><h2>{locale === "uk" ? "Відгуки" : "Reviews"}</h2>
      <Link className="btn btn-primary" href={`/${locale}/reviews`}>
        {locale === "uk" ? "Усі відгуки" : "All reviews"}<ArrowRight />
      </Link>
    </header>
    <div>{reviews.map((review) => <ReviewCard compact key={review.slug} review={review}
      readMoreHref={`/${locale}/reviews`}
      readMoreLabel={locale === "uk" ? "Читати повністю" : "Read in full"} />)}</div>
  </section>;
}
