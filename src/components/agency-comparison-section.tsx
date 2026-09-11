import type { Locale } from "@/i18n";

import { getTranslations } from "@/i18n/pages";
export function AgencyComparisonSection({ locale }: { locale: Locale }) {
  const t = getTranslations("about", locale).comparison;
  const rows = t.rows;

  return (
    <section className="agency-comparison section container">
      <span className="mono">{t.eyebrow}</span>
      <h2>{t.title}</h2>
      <p>{t.description}</p>
      <div className="comparison-table">
        <div className="comparison-head mono">
          <span />
          <span>{t.agencyLabel}</span>
          <span>{t.freelancerLabel}</span>
          <strong>GVSPACE</strong>
        </div>
        {rows.map((row) => (
          <div className="comparison-row" key={row[0]}>
            <strong>{row[0]}</strong>
            <span>{row[1]}</span>
            <span>{row[2]}</span>
            <b>{row[3]}</b>
          </div>
        ))}
      </div>
    </section>
  );
}
