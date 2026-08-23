"use client";

import { useMemo, useState } from "react";
import type { Locale } from "@/i18n";
import type { Messages } from "@/i18n/uk";
import { CaseCard } from "./case-card";
import { ChevronDown } from "./icons/chevron-down";

type CasesCatalogProps = {
  locale: Locale;
  text: Messages["cases"];
};

const projects = [
  { type: "ecommerce", industry: "retail" },
  { type: "strategy", industry: "services" },
  { type: "development", industry: "retail" },
  { type: "marketing", industry: "services" },
  { type: "ecommerce", industry: "technology" },
  { type: "strategy", industry: "technology" },
];

export function CasesCatalog({ locale, text }: CasesCatalogProps) {
  const uk = locale === "uk";
  const [type, setType] = useState("all");
  const [industry, setIndustry] = useState("all");
  const [visibleCount, setVisibleCount] = useState(4);

  const filteredProjects = useMemo(
    () =>
      projects.filter(
        (project) =>
          (type === "all" || project.type === type) &&
          (industry === "all" || project.industry === industry),
      ),
    [industry, type],
  );

  const changeFilter = (setter: (value: string) => void, value: string) => {
    setter(value);
    setVisibleCount(4);
  };

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
        {filteredProjects.slice(0, visibleCount).map((_, index) => (
          <CaseCard
            index={index + 1}
            title={text.caseTitle}
            result={text.result}
            badge={text.badge}
            key={index}
          />
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
