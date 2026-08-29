"use client";

import Image from "next/image";
import { useMemo, useState } from "react";
import type { TechnologyCategory, TechnologyItem } from "./wordpress-technologies";

export function TechnologyShowcaseTabs({
  categories,
  items,
  emptyLabel,
}: {
  categories: TechnologyCategory[];
  items: TechnologyItem[];
  emptyLabel: string;
}) {
  const [active, setActive] = useState(categories[0]?.slug ?? "");
  const selected = categories.some((category) => category.slug === active)
    ? active
    : (categories[0]?.slug ?? "");
  const visible = useMemo(
    () => items.filter((item) => item.categorySlugs.includes(selected)).slice(0, 8),
    [items, selected],
  );

  return (
    <>
      <div className="technology-showcase-tabs" role="tablist">
        {categories.map((category) => (
          <button
            className={category.slug === selected ? "is-active" : undefined}
            type="button"
            role="tab"
            aria-selected={category.slug === selected}
            onClick={() => setActive(category.slug)}
            key={category.slug}
          >
            [ {category.name.toUpperCase()} ]
          </button>
        ))}
      </div>
      <div className="technology-showcase-grid" role="tabpanel">
        {visible.map((item) => (
          <article key={item.id}>
            <div>
              {item.image ? (
                <Image src={item.image} alt={item.imageAlt} fill sizes="70px" unoptimized />
              ) : (
                <span aria-hidden="true">[ icon ]</span>
              )}
            </div>
            <p className="mono">{item.title}</p>
          </article>
        ))}
        {!visible.length && <p className="technology-showcase-empty">{emptyLabel}</p>}
      </div>
    </>
  );
}
