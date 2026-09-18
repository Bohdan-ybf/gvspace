"use client";

import { useMemo, useState } from "react";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";
import { ChevronDown } from "./icons/chevron-down";
import { VacancyCard } from "./vacancy-card";
import type { VacancySummary } from "./wordpress-vacancies";

function normalizeTag(tag: string) {
  return tag.trim().toUpperCase().replace(/\s+/g, "-");
}

function uniqueTags(values: string[]) {
  const seen = new Set<string>();
  return values.filter((value) => {
    const key = normalizeTag(value);
    if (!key || seen.has(key)) return false;
    seen.add(key);
    return true;
  });
}

export function VacanciesCatalog({
  locale,
  vacancies,
}: {
  locale: Locale;
  vacancies: VacancySummary[];
}) {
  const t = getTranslations("careers", locale).page;
  const [direction, setDirection] = useState("all");
  const [employment, setEmployment] = useState("all");

  const directionOptions = useMemo(
    () => uniqueTags(vacancies.map((vacancy) => vacancy.direction).filter(Boolean)),
    [vacancies],
  );
  const employmentOptions = useMemo(
    () => uniqueTags(vacancies.flatMap((vacancy) => vacancy.employmentTags)),
    [vacancies],
  );

  const visibleVacancies = useMemo(
    () =>
      vacancies.filter((vacancy) => {
        const matchesDirection =
          direction === "all" || normalizeTag(vacancy.direction) === normalizeTag(direction);
        const matchesEmployment =
          employment === "all" ||
          vacancy.employmentTags.some((tag) => normalizeTag(tag) === normalizeTag(employment));
        return matchesDirection && matchesEmployment;
      }),
    [direction, employment, vacancies],
  );

  return (
    <section className="vacancies-section">
      <span className="mono">{t.vacanciesEyebrow}</span>
      <div className="vacancies-filters">
        <label>
          <span className="sr-only">{t.directionLabel}</span>
          <select value={direction} onChange={(event) => setDirection(event.target.value)}>
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
          <span className="sr-only">{t.employmentLabel}</span>
          <select value={employment} onChange={(event) => setEmployment(event.target.value)}>
            <option value="all">{t.employmentLabel}</option>
            {employmentOptions.map((option) => (
              <option key={option} value={option}>
                {option}
              </option>
            ))}
          </select>
          <ChevronDown />
        </label>
      </div>
      {visibleVacancies.length ? (
        <div className="vacancies-grid">
          {visibleVacancies.map((vacancy) => (
            <VacancyCard key={vacancy.slug} locale={locale} vacancy={vacancy} />
          ))}
        </div>
      ) : (
        <p className="vacancies-empty">{t.emptyVacancies}</p>
      )}
    </section>
  );
}
