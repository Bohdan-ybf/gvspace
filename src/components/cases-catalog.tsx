"use client";

import { useMemo, useState } from "react";
import type { Locale } from "@/i18n";
import { CaseCard } from "./case-card";
import { ChevronDown } from "./icons/chevron-down";
import Link from "next/link";
import type { CaseStudy } from "./wordpress-cases";

import { componentCopy } from "@/i18n/component-copy";
type CasesCatalogProps = {
  locale: Locale;
  projects: CaseStudy[];
};

export function CasesCatalog({ locale, projects }: CasesCatalogProps) {
  const copy = componentCopy[locale]["cases-catalog"];
  const [type, setType] = useState("all");
  const [industry, setIndustry] = useState("all");
  const [visibleCount, setVisibleCount] = useState(4);

  const filteredProjects = useMemo(
    () =>
      projects.filter(
        (project) =>
          (type === "all" || project.projectType === type) &&
          (industry === "all" || project.industry === industry),
      ),
    [industry, projects, type],
  );

  const changeFilter = (setter: (value: string) => void, value: string) => {
    setter(value);
    setVisibleCount(4);
  };

  if (!projects.length) return null;

  return (
    <section className="cases-catalog section container">
      <div className="cases-filters">
        <label>
          <span className="sr-only">{copy.copy1}</span>
          <select value={type} onChange={(event) => changeFilter(setType, event.target.value)}>
            <option value="all">{copy.copy2}</option>
            <option value="ecommerce">E-commerce</option>
            <option value="strategy">{copy.copy3}</option>
            <option value="development">{copy.copy4}</option>
            <option value="marketing">{copy.copy5}</option>
          </select>
          <ChevronDown />
        </label>

        <label>
          <span className="sr-only">{copy.copy6}</span>
          <select
            value={industry}
            onChange={(event) => changeFilter(setIndustry, event.target.value)}
          >
            <option value="all">{copy.copy7}</option>
            <option value="retail">Retail</option>
            <option value="services">{copy.copy8}</option>
            <option value="technology">Technology</option>
          </select>
          <ChevronDown />
        </label>
      </div>

      <div className="cases-catalog-grid">
        {filteredProjects.slice(0, visibleCount).map((project, index) => (
          <Link href={`/${locale}/cases/${project.slug}`} key={project.slug}>
            <CaseCard
              index={index + 1}
              title={project.title}
              result={project.result}
              badge={project.badge}
              image={project.image}
            />
          </Link>
        ))}
      </div>

      {visibleCount < filteredProjects.length && (
        <button
          className="btn cases-more"
          type="button"
          onClick={() => setVisibleCount((count) => count + 2)}
        >
          {copy.copy9}
          <ChevronDown />
        </button>
      )}
    </section>
  );
}
