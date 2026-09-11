import type { Locale } from "@/i18n";

import { getTranslations } from "@/i18n/pages";
export function CareersValuesSection({ locale }: { locale: Locale }) {
  const t = getTranslations("careers", locale).values;
  const values = t.items;

  return (
    <section className="careers-values section container">
      <span className="mono">{t.eyebrow}</span>
      <div className="careers-values-grid">
        {values.map(([title, description], index) => (
          <article key={title}>
            <span className="mono">[ 0{index + 1} ]</span>
            <h3>{title}</h3>
            <p>{description}</p>
          </article>
        ))}
      </div>
    </section>
  );
}
