import type { Locale } from "@/i18n";

import { getTranslations } from "@/i18n/pages";
export function AboutPrinciplesSection({ locale }: { locale: Locale }) {
  const t = getTranslations("about", locale).principles;

  return (
    <section className="about-principles section container">
      <div className="about-team">
        <div className="about-team-photo">{t.photoLabel}</div>
        <div>
          <h2>{t.teamTitle}</h2>
          <p>{t.teamDescription}</p>
        </div>
      </div>

      <div className="about-values">
        <h2>{t.valuesTitle}</h2>
        <span>{t.visionLabel}</span>
        <div className="about-values-grid">
          {t.values.map(([title, description]) => (
            <article key={title}>
              <h3>{title}</h3>
              <p>{description}</p>
            </article>
          ))}
        </div>
      </div>

      <div className="about-vision">
        <span>{t.valuesLabel}</span>
        <h2>{t.visionTitle}</h2>
        <div>
          <div>
            <p>{t.visionLead}</p>
            <p>{t.visionDescription}</p>
          </div>
          <p>{t.visionSummary}</p>
        </div>
      </div>
    </section>
  );
}
