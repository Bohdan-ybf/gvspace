import type { Locale } from "@/i18n";

import { getTranslations } from "@/i18n/pages";
export function AboutStatsSection({ locale }: { locale: Locale }) {
  const t = getTranslations("about", locale).stats;
  const stats = t.items;

  return (
    <section className="about-stats section container">
      <h2>{t.title}</h2>
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
