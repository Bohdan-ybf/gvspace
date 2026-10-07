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
          <div className="comparison-head mono">
            <span />
            <span className="is-agency">{t.agencyLabel}</span>
            <span className="is-freelancer">{t.freelancerLabel}</span>
            <strong>GVSPACE</strong>
          </div>
          {rows.map((row) => (
            <div className="comparison-row" key={row[0]}>
              <strong>{row[0]}</strong>
              <span className="is-agency">{row[1]}</span>
              <span className="is-freelancer">{row[2]}</span>
              <b>{row[3]}</b>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
