"use client";

import { useState } from "react";
import { ReviewCard } from "./review-card";
import type { ClientReview } from "./wordpress-reviews";

const categories = ["all", "strategy", "marketing", "development", "content"] as const;

export function ReviewsCatalog({ reviews, locale }: { reviews: ClientReview[]; locale: "uk" | "en" }) {
  const [active, setActive] = useState<(typeof categories)[number]>("all");
  const labels = locale === "uk"
    ? ["Усі", "Стратегія", "Маркетинг", "IT-розробка", "Контент і продакшн"]
    : ["All", "Strategy", "Marketing", "IT development", "Content & production"];
  const visible = active === "all" ? reviews : reviews.filter((review) => review.category === active);

  return <section className="section container reviews-catalog">
    <span className="reviews-eyebrow mono">{locale === "uk" ? "ВІДГУКИ КЛІЄНТІВ" : "CLIENT REVIEWS"}</span>
    <nav aria-label={locale === "uk" ? "Категорії відгуків" : "Review categories"}>
      {categories.map((category, index) => <button className={active === category ? "is-active" : ""}
        key={category} onClick={() => setActive(category)}>{labels[index]}</button>)}
    </nav>
    <div className="reviews-masonry">{visible.map((review) => <ReviewCard key={review.slug} review={review} />)}</div>
  </section>;
}
