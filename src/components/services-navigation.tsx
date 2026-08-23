"use client";

import { useEffect, useState } from "react";

type NavigationItem = {
  slug: string;
  title: string;
};

export function ServicesNavigation({ items }: { items: NavigationItem[] }) {
  const [activeSlug, setActiveSlug] = useState(items[0]?.slug);

  useEffect(() => {
    const sections = items
      .map(({ slug }) => document.getElementById(slug))
      .filter((section): section is HTMLElement => section !== null);

    const observer = new IntersectionObserver(
      (entries) => {
        const visibleSection = entries
          .filter((entry) => entry.isIntersecting)
          .sort((first, second) => second.intersectionRatio - first.intersectionRatio)[0];

        if (visibleSection) {
          setActiveSlug(visibleSection.target.id);
        }
      },
      {
        rootMargin: "-140px 0px -55% 0px",
        threshold: [0, 0.2, 0.5, 0.8],
      },
    );

    sections.forEach((section) => observer.observe(section));
    return () => observer.disconnect();
  }, [items]);

  return (
    <nav className="services-tabs" aria-label="Service directions">
      <div className="container">
        {items.map((item) => (
          <a
            className={activeSlug === item.slug ? "is-active" : undefined}
            href={`#${item.slug}`}
            aria-current={activeSlug === item.slug ? "location" : undefined}
            key={item.slug}
            onClick={() => setActiveSlug(item.slug)}
          >
            {item.title}
          </a>
        ))}
      </div>
    </nav>
  );
}
