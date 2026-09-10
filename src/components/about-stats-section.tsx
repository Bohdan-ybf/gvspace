import type { Locale } from "@/i18n";

import { componentCopy } from "@/i18n/component-copy";
export function AboutStatsSection({ locale }: { locale: Locale }) {
  const copy = componentCopy[locale]["about-stats-section"];
  const stats = copy.copy1;

  return (
    <section className="about-stats section container">
      <h2>{copy.copy2}</h2>
      <div className="about-stats-grid">
        {stats.map(([value, title, description]) => (
          <article key={value}>
            <strong>{value}</strong>
            <h3>{title}</h3>
            <p>{description}</p>
          </article>
        ))}
      </div>
    </section>
  );
}
