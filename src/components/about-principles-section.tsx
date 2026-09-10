import type { Locale } from "@/i18n";

import { componentCopy } from "@/i18n/component-copy";
export function AboutPrinciplesSection({ locale }: { locale: Locale }) {
  const copy = componentCopy[locale]["about-principles-section"];

  return (
    <section className="about-principles section container">
      <div className="about-team">
        <div className="about-team-photo">{copy.copy1}</div>
        <div>
          <h2>{copy.copy2}</h2>
          <p>{copy.copy3}</p>
        </div>
      </div>

      <div className="about-values">
        <h2>{copy.copy4}</h2>
        <span>{copy.copy5}</span>
        <div className="about-values-grid">
          {copy.copy6.map(([title, description]) => (
            <article key={title}>
              <h3>{title}</h3>
              <p>{description}</p>
            </article>
          ))}
        </div>
      </div>

      <div className="about-vision">
        <span>{copy.copy7}</span>
        <h2>{copy.copy8}</h2>
        <div>
          <div>
            <p>{copy.copy9}</p>
            <p>{copy.copy10}</p>
          </div>
          <p>{copy.copy11}</p>
        </div>
      </div>
    </section>
  );
}
