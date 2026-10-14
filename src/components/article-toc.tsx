"use client";

import { useEffect, useState } from "react";

export type ArticleTocHeading = {
  id: string;
  label: string;
  level: 2 | 3;
};

export function ArticleToc({ label, headings }: { label: string; headings: ArticleTocHeading[] }) {
  const [activeId, setActiveId] = useState(headings[0]?.id ?? "");

  useEffect(() => {
    const elements = headings
      .map(({ id }) => document.getElementById(id))
      .filter((element): element is HTMLElement => Boolean(element));
    if (!elements.length) return;

    const updateActiveHeading = () => {
      const threshold = 113;
      const passed = elements.filter((element) => element.getBoundingClientRect().top <= threshold);
      setActiveId((passed.at(-1) ?? elements[0]).id);
    };

    updateActiveHeading();
    window.addEventListener("scroll", updateActiveHeading, { passive: true });
    window.addEventListener("resize", updateActiveHeading);
    return () => {
      window.removeEventListener("scroll", updateActiveHeading);
      window.removeEventListener("resize", updateActiveHeading);
    };
  }, [headings]);

  return (
    <nav className="article-toc" aria-label={label}>
      <span className="mono">{label}</span>
      {headings.map((heading) => (
        <a
          key={heading.id}
          href={`#${heading.id}`}
          className={`${heading.level === 3 ? "is-nested " : ""}${
            activeId === heading.id ? "is-active" : ""
          }`.trim()}
          aria-current={activeId === heading.id ? "location" : undefined}
          onClick={() => setActiveId(heading.id)}
        >
          {heading.label}
        </a>
      ))}
    </nav>
  );
}
