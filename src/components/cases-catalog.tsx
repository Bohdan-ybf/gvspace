"use client";

import { useMemo, useRef, useState } from "react";
import type { Locale } from "@/i18n";
import { CaseCard } from "./case-card";
import { FilterSelect } from "./filter-select";
import { Pagination, scrollToBlockTop } from "./pagination";
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

export function CasesCatalog({ locale, projects }: { locale: Locale; projects: CaseStudy[] }) {
  const t = getTranslations("cases", locale).catalog;
  const [direction, setDirection] = useState("all");
  const [projectType, setProjectType] = useState("all");
  const [page, setPage] = useState(0);
  const catalogRef = useRef<HTMLElement>(null);
  const filtersActive = direction !== "all" || projectType !== "all";

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
    <section className="cases-catalog section container" ref={catalogRef}>
      <div className="cases-filters">
        <FilterSelect
          label={t.directionLabel}
          value={direction}
          options={directionOptions.map((option) => ({ value: option, label: option }))}
          onChange={(value) => changeFilter(setDirection, value)}
        />
        <FilterSelect
          label={t.typeLabel}
          value={projectType}
          options={typeOptions.map((option) => ({ value: option, label: option }))}
          onChange={(value) => changeFilter(setProjectType, value)}
        />
        {filtersActive ? (
          <button
            type="button"
            className="filters-reset"
            onClick={() => {
              setDirection("all");
              setProjectType("all");
              setPage(0);
            }}
          >
            {t.resetFilters}
          </button>
        ) : null}
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
        <button
          className="btn cases-more"
          type="button"
          onClick={() => {
            setPage((value) => value + 1);
            scrollToBlockTop(catalogRef.current);
          }}
        >
          {t.more} <span aria-hidden="true">↓</span>
        </button>
      )}

      <Pagination
        className="cases-pagination"
        page={currentPage + 1}
        pageCount={pageCount}
        label={t.paginationLabel}
        previousLabel={t.prevPage}
        nextLabel={t.nextPage}
        onPageChange={(nextPage) => {
          setPage(nextPage - 1);
          scrollToBlockTop(catalogRef.current);
        }}
      />
    </section>
  );
}
