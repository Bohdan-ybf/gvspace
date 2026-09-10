import type { Locale } from "@/i18n";

import { componentCopy } from "@/i18n/component-copy";
export function AgencyComparisonSection({ locale }: { locale: Locale }) {
  const copy = componentCopy[locale]["agency-comparison-section"];
  const rows = copy.copy1;

  return (
    <section className="agency-comparison section container">
      <span className="mono">{copy.copy2}</span>
      <h2>{copy.copy3}</h2>
      <p>{copy.copy4}</p>
      <div className="comparison-table">
        <div className="comparison-head mono">
          <span />
          <span>{copy.copy5}</span>
          <span>{copy.copy6}</span>
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
