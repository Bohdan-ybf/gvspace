"use client";

import { useState } from "react";
import { ReviewCard } from "./review-card";
import type { ClientReview } from "./wordpress-reviews";

import { componentCopy } from "@/i18n/component-copy";
const categories = ["all", "strategy", "marketing", "development", "content"] as const;

export function ReviewsCatalog({
  reviews,
  locale,
}: {
  reviews: ClientReview[];
  locale: "uk" | "en";
}) {
  const copy = componentCopy[locale]["reviews-catalog"];
  const [active, setActive] = useState<(typeof categories)[number]>("all");
  const labels = copy.copy1;
  const visible =
    active === "all" ? reviews : reviews.filter((review) => review.category === active);

  return (
    <section className="section container reviews-catalog">
      <span className="reviews-eyebrow mono">{copy.copy2}</span>
      <nav aria-label={copy.copy3}>
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
