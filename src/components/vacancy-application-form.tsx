"use client";

import type { FormEvent } from "react";
import type { Locale } from "@/i18n";

import { getTranslations } from "@/i18n/pages";
export function VacancyApplicationForm({
  locale,
  onSubmit,
}: {
  locale: Locale;
  onSubmit?: () => void;
}) {
  const t = getTranslations("vacancies", locale).application;

  return (
    <form
      className="vacancy-application-form"
      onSubmit={(event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        onSubmit?.();
      }}
    >
      <span className="mono">{t.eyebrow}</span>
      <h2>{t.title}</h2>
      <div>
        <input aria-label={t.nameLabel} placeholder={t.namePlaceholder} required />
        <input aria-label={t.phoneLabel} placeholder={t.phonePlaceholder} inputMode="tel" />
      </div>
      <input aria-label={t.emailLabel} placeholder={t.emailPlaceholder} type="email" required />
      <input aria-label={t.telegramLabel} placeholder={t.telegramPlaceholder} />
      <input aria-label={t.portfolioLabel} placeholder={t.portfolioPlaceholder} />
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
