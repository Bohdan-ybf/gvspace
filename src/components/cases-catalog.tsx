"use client";

import { useMemo, useState } from "react";
import type { Locale } from "@/i18n";
import { CaseCard } from "./case-card";
import { ChevronDown } from "./icons/chevron-down";
import Link from "next/link";
import type { CaseStudy } from "./wordpress-cases";

type CasesCatalogProps = {
  locale: Locale;
  projects: CaseStudy[];
};

export function CasesCatalog({ locale, projects }: CasesCatalogProps) {
  const uk = locale === "uk";
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
          <span className="sr-only">{uk ? "Тип проєкту" : "Project type"}</span>
          <select value={type} onChange={(event) => changeFilter(setType, event.target.value)}>
            <option value="all">{uk ? "Усі проєкти" : "All projects"}</option>
            <option value="ecommerce">E-commerce</option>
            <option value="strategy">{uk ? "Стратегія" : "Strategy"}</option>
            <option value="development">{uk ? "Розробка" : "Development"}</option>
            <option value="marketing">{uk ? "Маркетинг" : "Marketing"}</option>
          </select>
          <ChevronDown />
        </label>

        <label>
          <span className="sr-only">{uk ? "Індустрія" : "Industry"}</span>
          <select
            value={industry}
            onChange={(event) => changeFilter(setIndustry, event.target.value)}
          >
            <option value="all">{uk ? "Усі індустрії" : "All industries"}</option>
            <option value="retail">Retail</option>
            <option value="services">{uk ? "Послуги" : "Services"}</option>
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
          {uk ? "Більше" : "More"}
          <ChevronDown />
        </button>
      )}
    </section>
  );
}
