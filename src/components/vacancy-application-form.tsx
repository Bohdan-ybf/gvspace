"use client";

import type { FormEvent } from "react";
import type { Locale } from "@/i18n";
import Link from "next/link";

import { getTranslations } from "@/i18n/pages";
import { FormHoneypot, FormSubmitError, sendingLabel, useSiteForm } from "@/submit-site-form";
import { Field, PhoneField } from "./form-field";
import { FileUploadField } from "./file-upload-field";
import { FormSentModal } from "./form-sent-modal";
export function VacancyApplicationForm({
  locale,
  vacancyTitle,
  onSubmit,
}: {
  locale: Locale;
  vacancyTitle?: string;
  onSubmit?: () => void;
}) {
  const t = getTranslations("vacancies", locale).application;
  const { sending, success, error, formKey, submit, closeSuccess } = useSiteForm(locale);

  return (
    <>
      <form
        key={formKey}
        className="vacancy-application-form"
        noValidate
        onSubmit={async (event: FormEvent<HTMLFormElement>) => {
          const sent = await submit(
            event,
            "vacancy",
            vacancyTitle ? { vacancy: vacancyTitle } : undefined,
          );
          if (sent) onSubmit?.();
        }}
      >
        <FormHoneypot />
        <span className="mono">{t.eyebrow}</span>
        <h2>{t.title}</h2>
        <div>
          <Field locale={locale}>
            <input name="name" aria-label={t.nameLabel} placeholder={t.namePlaceholder} required />
          </Field>
          <PhoneField
            locale={locale}
            name="phone"
            aria-label={t.phoneLabel}
            placeholder={t.phonePlaceholder}
            required
          />
        </div>
        <div>
          <Field locale={locale}>
            <input
              name="email"
              aria-label={t.emailLabel}
              placeholder={t.emailPlaceholder}
              type="email"
              required
            />
          </Field>
          <input name="telegram" aria-label={t.telegramLabel} placeholder={t.telegramPlaceholder} />
        </div>
        <input
          name="portfolio"
          aria-label={t.portfolioLabel}
          placeholder={t.portfolioPlaceholder}
        />
        <Field locale={locale}>
          <textarea
            name="about"
            aria-label={t.aboutLabel}
            placeholder={t.aboutPlaceholder}
            required
          />
        </Field>
        <FileUploadField
          className="resume-upload mono"
          label={t.resumeLabel}
          locale={locale}
          accept=".pdf,application/pdf"
          maxFileSizeMb={5}
          name="resume"
        />
        <FormSubmitError message={error} />
        <div className="vacancy-application-actions">
          <button className="btn btn-primary" type="submit" disabled={sending}>
            {sending ? sendingLabel(locale) : t.submit}
          </button>
          <p className="mono">
            {t.consent} <Link href="/privacy-policy">{t.privacyLink}</Link>
          </p>
        </div>
      </form>
      {success && !onSubmit ? (
        <FormSentModal
          locale={locale}
          title={t.successTitle}
          lead={t.successDescription}
          onClose={closeSuccess}
        />
      ) : null}
    </>
  );
}
