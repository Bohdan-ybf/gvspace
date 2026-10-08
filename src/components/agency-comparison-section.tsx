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
      <div className="comparison-switch">
        <input defaultChecked id="comparison-agency" name="comparison-view" type="radio" />
        <input id="comparison-freelancer" name="comparison-view" type="radio" />
        <div className="comparison-tabs mono">
          <label htmlFor="comparison-agency">{t.agencyTab}</label>
          <label htmlFor="comparison-freelancer">{t.freelancerTab}</label>
        </div>
        <div className="comparison-table">
          <div className="comparison-col comparison-col-labels">
            <span className="comparison-col-head" />
            {rows.map((row) => (
              <strong key={row[0]}>{row[0]}</strong>
            ))}
          </div>
          <div className="comparison-col is-agency">
            <span className="comparison-col-head">{t.agencyLabel}</span>
            {rows.map((row) => (
              <span key={row[0]}>{row[1]}</span>
            ))}
          </div>
          <div className="comparison-col is-freelancer">
            <span className="comparison-col-head">{t.freelancerLabel}</span>
            {rows.map((row) => (
              <span key={row[0]}>{row[2]}</span>
            ))}
          </div>
          <div className="comparison-col comparison-col-gvspace">
            <strong className="comparison-col-head">GVSPACE</strong>
            {rows.map((row) => (
              <b key={row[0]}>{row[3]}</b>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}
