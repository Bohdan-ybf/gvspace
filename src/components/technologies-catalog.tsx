"use client";

import Image from "next/image";
import Link from "next/link";
import { useMemo, useState } from "react";
import type { Locale } from "@/i18n";
import type { TechnologyCategory, TechnologyItem } from "./wordpress-technologies";

import { getTranslations } from "@/i18n/pages";

export function TechnologiesCatalog({
  locale,
  categories,
  items,
}: {
  locale: Locale;
  categories: TechnologyCategory[];
  items: TechnologyItem[];
}) {
  const t = getTranslations("technologies", locale).catalog;
  const firstCategory = categories[0]?.slug ?? "";
  const [activeCategory, setActiveCategory] = useState(firstCategory);
  const selectedCategory = categories.some(({ slug }) => slug === activeCategory)
    ? activeCategory
    : firstCategory;
  const visibleItems = useMemo(
    () => items.filter(({ categorySlugs }) => categorySlugs.includes(selectedCategory)),
    [items, selectedCategory],
  );

  return (
    <section className="technologies-catalog section container">
      <nav aria-label={t.navigationLabel}>
        {categories.map((category, index) => {
          const count = items.filter((item) => item.categorySlugs.includes(category.slug)).length;
          return (
            <button
              className={category.slug === selectedCategory ? "is-active" : undefined}
              type="button"
              onClick={() => setActiveCategory(category.slug)}
              key={category.slug}
            >
              <span className="mono">{String(index + 1).padStart(2, "0")}</span>
              <b>{category.name}</b>
              <small>{count}</small>
            </button>
          );
        })}
      </nav>
      <div className="technology-tools-grid">
        {visibleItems.map((item) => (
          <article key={item.slug}>
            <Link href={`/${locale}/technologies/${item.slug}`}>
              <div className="technology-logo">
                {item.image ? (
                  <Image src={item.image} alt={item.imageAlt} fill sizes="72px" unoptimized />
                ) : (
                  <span aria-hidden="true">[ icon ]</span>
                )}
              </div>
              <div>
                <h3>{item.title}</h3>
                {item.description ? <p>{item.description}</p> : null}
                {item.tag ? <span className="mono">{item.tag}</span> : null}
              </div>
            </Link>
          </article>
        ))}
        {!visibleItems.length && <p className="technology-tools-empty">{t.emptyState}</p>}
      </div>
    </section>
  );
}
