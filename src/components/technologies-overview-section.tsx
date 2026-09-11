import type { Locale } from "@/i18n";

import { getTranslations } from "@/i18n/pages";
export function TechnologiesOverviewSection({ locale }: { locale: Locale }) {
  const t = getTranslations("technologies", locale).overview;
  const stats = t.stats;

  return (
    <section className="technologies-overview section container">
      <div className="technologies-philosophy">
        <span className="mono">{t.eyebrow}</span>
        <p>{t.description}</p>
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
