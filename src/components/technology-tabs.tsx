"use client";

import Image from "next/image";
import { useMemo, useState } from "react";
import type { TechnologyCategory, TechnologyItem } from "./wordpress-technologies";

type TechnologyTabsProps = {
  categories: TechnologyCategory[];
  items: TechnologyItem[];
  emptyLabel: string;
};

export function TechnologyTabs({ categories, items, emptyLabel }: TechnologyTabsProps) {
  const firstCategory = categories[0]?.slug ?? "";
  const [activeCategory, setActiveCategory] = useState(firstCategory);
  const selectedCategory = categories.some(({ slug }) => slug === activeCategory)
    ? activeCategory
    : firstCategory;
  const visibleItems = useMemo(
    () => items.filter(({ categorySlugs }) => categorySlugs.includes(selectedCategory)),
    [items, selectedCategory],
  );
  const handleTabKeyDown = (event: React.KeyboardEvent<HTMLButtonElement>, index: number) => {
    if (!["ArrowLeft", "ArrowRight", "Home", "End"].includes(event.key)) return;
    event.preventDefault();

    const nextIndex =
      event.key === "Home"
        ? 0
        : event.key === "End"
          ? categories.length - 1
          : (index + (event.key === "ArrowRight" ? 1 : -1) + categories.length) %
            categories.length;
    const nextCategory = categories[nextIndex];
    if (!nextCategory) return;

    setActiveCategory(nextCategory.slug);
    requestAnimationFrame(() => document.getElementById(`technology-tab-${nextCategory.slug}`)?.focus());
  };

  return (
    <>
      <div className="technology-stack-tabs" role="tablist" aria-label="Technology categories">
        {categories.map((category, index) => {
          const isActive = category.slug === selectedCategory;
          return (
            <button
              id={`technology-tab-${category.slug}`}
              className={isActive ? "is-active" : undefined}
              type="button"
              role="tab"
              aria-selected={isActive}
              aria-controls={`technology-panel-${category.slug}`}
              tabIndex={isActive ? 0 : -1}
              onClick={() => setActiveCategory(category.slug)}
              onKeyDown={(event) => handleTabKeyDown(event, index)}
              key={category.slug}
            >
              [ {category.name.toUpperCase()} ]
            </button>
          );
        })}
      </div>

      <div
        id={`technology-panel-${selectedCategory}`}
        className="technology-stack-grid"
        role="tabpanel"
        aria-labelledby={`technology-tab-${selectedCategory}`}
      >
        {visibleItems.map((item) => (
          <article className="technology-stack-card" key={item.id}>
            <div className="technology-stack-icon">
              {item.image ? (
                <Image src={item.image} alt={item.imageAlt} fill sizes="84px" unoptimized />
              ) : (
                <span aria-hidden="true">[ icon ]</span>
              )}
            </div>
            <p className="mono">{item.title}</p>
          </article>
        ))}
        {!visibleItems.length && <p className="technology-stack-empty">{emptyLabel}</p>}
      </div>
    </>
  );
}
