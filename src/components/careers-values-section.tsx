import type { Locale } from "@/i18n";

import { componentCopy } from "@/i18n/component-copy";
export function CareersValuesSection({ locale }: { locale: Locale }) {
  const copy = componentCopy[locale]["careers-values-section"];
  const values = copy.copy1;

  return (
    <section className="careers-values section container">
      <span className="mono">{copy.copy2}</span>
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
