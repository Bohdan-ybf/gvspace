"use client";

import { useMemo, useState } from "react";
import type { Locale } from "@/i18n";
import { CaseCard } from "./case-card";
import { ChevronDown } from "./icons/chevron-down";
import type { CaseStudy } from "./wordpress-cases";
import { getTranslations } from "@/i18n/pages";

const PAGE_SIZE = 10;

function uniqueValues(values: string[]) {
  const seen = new Set<string>();
  return values.filter((value) => {
    const key = value.trim();
    if (!key || seen.has(key)) return false;
    seen.add(key);
    return true;
  });
}

function paginationItems(current: number, total: number) {
  if (total <= 7) return Array.from({ length: total }, (_, index) => index);
  const items: Array<number | "ellipsis"> = [];
  const pushRange = (from: number, to: number) => {
    for (let index = from; index <= to; index += 1) items.push(index);
  };
  items.push(0);
  if (current > 2) items.push("ellipsis");
  const start = Math.max(1, current - 1);
  const end = Math.min(total - 2, current + 1);
  pushRange(start, end);
  if (current < total - 3) items.push("ellipsis");
  items.push(total - 1);
  return items.filter((item, index, list) => item !== list[index - 1]);
}

export function CasesCatalog({ locale, projects }: { locale: Locale; projects: CaseStudy[] }) {
  const t = getTranslations("cases", locale).catalog;
  const [direction, setDirection] = useState("all");
  const [projectType, setProjectType] = useState("all");
  const [page, setPage] = useState(0);

  const directionOptions = useMemo(
    () => uniqueValues(projects.map((project) => project.direction)),
    [projects],
  );
  const typeOptions = useMemo(
    () => uniqueValues(projects.map((project) => project.projectType)),
    [projects],
  );

  const filteredProjects = useMemo(
    () =>
      projects.filter(
        (project) =>
          (direction === "all" || project.direction === direction) &&
          (projectType === "all" || project.projectType === projectType),
      ),
    [direction, projectType, projects],
  );

  const pageCount = Math.max(1, Math.ceil(filteredProjects.length / PAGE_SIZE));
  const currentPage = Math.min(page, pageCount - 1);
  const visibleProjects = filteredProjects.slice(
    currentPage * PAGE_SIZE,
    currentPage * PAGE_SIZE + PAGE_SIZE,
  );

  const changeFilter = (setter: (value: string) => void, value: string) => {
    setter(value);
    setPage(0);
  };

  return (
    <section className="cases-catalog section container">
      <div className="cases-filters">
        <label>
          <span className="sr-only">{t.directionLabel}</span>
          <select
            value={direction}
            onChange={(event) => changeFilter(setDirection, event.target.value)}
          >
            <option value="all">{t.directionLabel}</option>
            {directionOptions.map((option) => (
              <option key={option} value={option}>
                {option}
              </option>
            ))}
          </select>
          <ChevronDown />
        </label>
        <label>
          <span className="sr-only">{t.typeLabel}</span>
          <select
            value={projectType}
            onChange={(event) => changeFilter(setProjectType, event.target.value)}
          >
            <option value="all">{t.typeLabel}</option>
            {typeOptions.map((option) => (
              <option key={option} value={option}>
                {option}
              </option>
            ))}
          </select>
          <ChevronDown />
        </label>
      </div>

      {visibleProjects.length ? (
        <div className="cases-catalog-grid">
          {visibleProjects.map((project) => (
            <CaseCard key={project.slug} locale={locale} project={project} />
          ))}
        </div>
      ) : (
        <p className="cases-empty">{t.empty}</p>
      )}

      {currentPage < pageCount - 1 && (
        <button className="btn cases-more" type="button" onClick={() => setPage((value) => value + 1)}>
          {t.more} <span aria-hidden="true">+</span>
        </button>
      )}

      {pageCount > 1 && (
        <nav aria-label={t.paginationLabel} className="cases-pagination">
          <button
            aria-label={t.prevPage}
            disabled={currentPage === 0}
            type="button"
            onClick={() => setPage((value) => Math.max(0, value - 1))}
          >
            ‹
          </button>
          {paginationItems(currentPage, pageCount).map((item, index) =>
            item === "ellipsis" ? (
              <span key={`ellipsis-${index}`}>…</span>
            ) : (
              <button
                aria-current={item === currentPage ? "page" : undefined}
                className={item === currentPage ? "is-active" : undefined}
                key={item}
                type="button"
                onClick={() => setPage(item)}
              >
                {item + 1}
              </button>
            ),
          )}
          <button
            aria-label={t.nextPage}
            disabled={currentPage >= pageCount - 1}
            type="button"
            onClick={() => setPage((value) => Math.min(pageCount - 1, value + 1))}
          >
            ›
          </button>
        </nav>
      )}
    </section>
  );
}
