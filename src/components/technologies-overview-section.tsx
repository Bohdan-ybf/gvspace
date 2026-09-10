import type { Locale } from "@/i18n";

import { componentCopy } from "@/i18n/component-copy";
export function TechnologiesOverviewSection({ locale }: { locale: Locale }) {
  const copy = componentCopy[locale]["technologies-overview-section"];
  const stats = copy.copy1;

  return (
    <section className="technologies-overview section container">
      <div className="technologies-philosophy">
        <span className="mono">{copy.copy2}</span>
        <p>{copy.copy3}</p>
      </div>
      <div className="technologies-stats">
        {stats.map(([value, label]) => (
          <article key={label}>
            <strong>{value}</strong>
            <span className="mono">{label}</span>
          </article>
        ))}
      </div>
    </section>
  );
}
