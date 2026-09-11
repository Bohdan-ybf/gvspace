import type { Locale } from "@/i18n";

import { getTranslations } from "@/i18n/pages";
export function TeamFounderSection({ locale }: { locale: Locale }) {
  const t = getTranslations("team", locale).founder;

  return (
    <section className="team-founder section container">
      <div className="team-philosophy">
        <span className="mono">{t.eyebrow}</span>
        <p>{t.philosophy}</p>
      </div>
    </section>
  );
}
