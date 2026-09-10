import Link from "next/link";
import type { Locale } from "@/i18n";
import type { VacancySummary } from "./wordpress-vacancies";
import { ArrowRight } from "./icons/arrow-right";

import { componentCopy } from "@/i18n/component-copy";
export function VacancyCard({ locale, vacancy }: { locale: Locale; vacancy: VacancySummary }) {
  const copy = componentCopy[locale]["vacancy-card"];

  return (
    <article className="vacancy-card">
      <div className="vacancy-card-heading">
        <h3>{vacancy.title}</h3>
        {vacancy.hot && <span className="mono">{copy.copy1}</span>}
      </div>
      <p>{vacancy.excerpt}</p>
      <div className="vacancy-tags mono">
        {vacancy.tags.map((tag) => (
          <span key={tag}>{tag}</span>
        ))}
      </div>
      <div className="vacancy-card-footer">
        <b>{vacancy.salary}</b>
        <Link href={`/${locale}/careers/${vacancy.slug}`}>
          {copy.copy2}
          <ArrowRight />
        </Link>
      </div>
    </article>
  );
}
