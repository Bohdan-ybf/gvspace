"use client";

import type { FormEvent } from "react";
import type { Locale } from "@/i18n";
import Link from "next/link";

import { getTranslations } from "@/i18n/pages";
import { FileUploadField } from "./file-upload-field";
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
        <input name="name" aria-label={t.nameLabel} placeholder={t.namePlaceholder} required />
        <input
          name="phone"
          type="tel"
          aria-label={t.phoneLabel}
          placeholder={t.phonePlaceholder}
          inputMode="tel"
          required
        />
      </div>
      <div>
        <input
          name="email"
          aria-label={t.emailLabel}
          placeholder={t.emailPlaceholder}
          type="email"
          required
        />
        <input name="telegram" aria-label={t.telegramLabel} placeholder={t.telegramPlaceholder} />
      </div>
      <input name="portfolio" aria-label={t.portfolioLabel} placeholder={t.portfolioPlaceholder} />
      <textarea name="about" aria-label={t.aboutLabel} placeholder={t.aboutPlaceholder} required />
      <FileUploadField
        className="resume-upload mono"
        label={t.resumeLabel}
        locale={locale}
        accept=".pdf,application/pdf"
        maxFileSizeMb={5}
        name="resume"
      />
      <div className="vacancy-application-actions">
        <button className="btn btn-primary" type="submit">
          {t.submit}
        </button>
        <p className="mono">
          {t.consent} <Link href="/privacy-policy">{t.privacyLink}</Link>
        </p>
      </div>
    </form>
  );
}
