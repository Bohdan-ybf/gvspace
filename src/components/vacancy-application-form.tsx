import type { Locale } from "@/i18n";

import { getTranslations } from "@/i18n/pages";
export function VacancyApplicationForm({ locale }: { locale: Locale }) {
  const t = getTranslations("vacancies", locale).application;

  return (
    <form className="vacancy-application-form">
      <span className="mono">{t.eyebrow}</span>
      <h2>{t.title}</h2>
      <div>
        <input aria-label={t.nameLabel} placeholder={t.namePlaceholder} required />
        <input aria-label={t.phoneLabel} placeholder="+38 0__" inputMode="tel" />
      </div>
      <input aria-label="Email" placeholder="Email" type="email" required />
      <input aria-label="Telegram" placeholder="Telegram" />
      <input aria-label="LinkedIn or portfolio" placeholder="LinkedIn або портфоліо" />
      <textarea aria-label={t.aboutLabel} placeholder={t.aboutPlaceholder} required />
      <label className="resume-upload mono">
        <input type="file" accept=".pdf" />
        {t.resumeLabel}
      </label>
      <button className="btn btn-primary" type="submit">
        {t.submit}
      </button>
      <p className="mono">{t.consent}</p>
    </form>
  );
}
