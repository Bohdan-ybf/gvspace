"use client";

import Image from "next/image";
import Link from "next/link";
import { useEffect, useRef, useState } from "react";
import type { Locale } from "@/i18n";
import type { TechnologyCategory, TechnologyItem } from "./wordpress-technologies";

import { getTranslations } from "@/i18n/pages";

function TechnologyTools({
  locale,
  items,
  emptyState,
}: {
  locale: Locale;
  items: TechnologyItem[];
  emptyState: string;
}) {
  if (!items.length) return <p className="technology-tools-empty">{emptyState}</p>;

  return (
    <div className="technology-tools-grid">
      {items.map((item) => (
        <article key={item.slug}>
          <Link href={`/${locale}/technologies/${item.slug}`}>
            <div className="technology-logo">
              {item.image ? (
                <Image src={item.image} alt="" fill sizes="72px" unoptimized />
              ) : (
                <span aria-hidden="true">[ icon ]</span>
              )}
            </div>
            <div>
              <h2>{item.title}</h2>
              {item.description ? <p>{item.description}</p> : null}
              {item.tag ? <span className="mono">{item.tag}</span> : null}
            </div>
          </Link>
        </article>
      ))}
    </div>
  );
}

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
  const catalogRef = useRef<HTMLElement>(null);
  const [activeCategory, setActiveCategory] = useState(firstCategory);

  useEffect(() => {
    const media = window.matchMedia("(max-width: 767px)");
    const keepSelectionOnDesktop = () => {
      if (!media.matches) {
        setActiveCategory((current) => current || firstCategory);
      }
    };
    media.addEventListener("change", keepSelectionOnDesktop);
    return () => media.removeEventListener("change", keepSelectionOnDesktop);
  }, [firstCategory]);

  const pendingMobileScroll = useRef(false);

  const selectCategory = (slug: string, toggle: boolean) => {
    if (toggle) {
      setActiveCategory((current) => {
        if (current === slug) return "";
        pendingMobileScroll.current = true;
        return slug;
      });
      return;
    }
    setActiveCategory(slug);
    scrollToCatalog();
  };

  const catalogGap = () => {
    const nav = catalogRef.current?.querySelector<HTMLElement>(".technologies-catalog-nav");
    return nav
      ? Number.parseFloat(getComputedStyle(nav).getPropertyValue("--catalog-sticky-gap")) || 0
      : 0;
  };

  const scrollToCatalog = () => {
    const section = catalogRef.current;
    if (!section) return;
    const paddingTop = Number.parseFloat(getComputedStyle(section).paddingTop) || 0;
    const top =
      section.getBoundingClientRect().top + window.scrollY + paddingTop - 80 - catalogGap();
    window.scrollTo({ top: Math.max(0, top), behavior: "smooth" });
  };

  useEffect(() => {
    if (!pendingMobileScroll.current) return;
    pendingMobileScroll.current = false;
    const open = catalogRef.current?.querySelector<HTMLElement>(
      ".technologies-accordion > .is-open",
    );
    if (!open) return;
    const headerHeight = window.matchMedia("(max-width: 600px)").matches ? 78 : 80;
    const top = open.getBoundingClientRect().top + window.scrollY - headerHeight - catalogGap();
    window.scrollTo({ top: Math.max(0, top), behavior: "smooth" });
  }, [activeCategory]);

  return (
    <section className="technologies-catalog section container" ref={catalogRef}>
      <nav className="technologies-catalog-nav" aria-label={t.navigationLabel}>
        {categories.map((category, index) => {
          const count = items.filter((item) => item.categorySlugs.includes(category.slug)).length;
          return (
            <button
              className={category.slug === activeCategory ? "is-active" : undefined}
              type="button"
              onClick={() => selectCategory(category.slug, false)}
              key={category.slug}
            >
              <span className="mono">{String(index + 1).padStart(2, "0")}</span>
              <b>{category.name}</b>
              <small>{count}</small>
            </button>
          );
        })}
      </nav>
      <div className="technologies-accordion">
        {categories.map((category, index) => {
          const open = category.slug === activeCategory;
          const categoryItems = items.filter((item) => item.categorySlugs.includes(category.slug));
          return (
            <div className={open ? "is-open" : undefined} key={category.slug}>
              <button
                className="technologies-accordion-trigger"
                type="button"
                aria-expanded={open}
                onClick={() => selectCategory(category.slug, true)}
              >
                <span className="mono">{String(index + 1).padStart(2, "0")}</span>
                <b>{category.name}</b>
                <span className="technologies-accordion-icon" aria-hidden="true" />
              </button>
              {open ? (
                <TechnologyTools locale={locale} items={categoryItems} emptyState={t.emptyState} />
              ) : null}
            </div>
          );
        })}
      </div>
    </section>
  );
}
