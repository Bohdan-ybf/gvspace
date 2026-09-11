"use client";

import { useState } from "react";
import { ReviewCard } from "./review-card";
import type { ClientReview } from "./wordpress-reviews";

import { getTranslations } from "@/i18n/pages";
const categories = ["all", "strategy", "marketing", "development", "content"] as const;

export function ReviewsCatalog({
  reviews,
  locale,
}: {
  reviews: ClientReview[];
  locale: "uk" | "en";
}) {
  const t = getTranslations("reviews", locale).catalog;
  const [active, setActive] = useState<(typeof categories)[number]>("all");
  const labels = t.categoryLabels;
  const visible =
    active === "all" ? reviews : reviews.filter((review) => review.category === active);

  return (
    <section className="section container reviews-catalog">
      <span className="reviews-eyebrow mono">{t.eyebrow}</span>
      <nav aria-label={t.navigationLabel}>
        {categories.map((category, index) => (
          <button
            className={active === category ? "is-active" : ""}
            key={category}
            onClick={() => setActive(category)}
          >
            {labels[index]}
          </button>
        ))}
      </nav>
      <div className="reviews-masonry">
        {visible.map((review) => (
          <ReviewCard key={review.slug} review={review} />
        ))}
      </div>
    </section>
  );
}
