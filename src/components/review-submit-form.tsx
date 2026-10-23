"use client";

import Link from "next/link";
import type { Locale } from "@/i18n";
import { FormHoneypot, FormSubmitError, sendingLabel, useSiteForm } from "@/submit-site-form";
import { Field, PhoneField } from "./form-field";
import { FormSentModal } from "./form-sent-modal";

type ReviewSubmitCopy = {
  title: string;
  description: string;
  detail: string;
  name: string;
  company: string;
  email: string;
  phone: string;
  text: string;
  submit: string;
  privacy: string;
  privacyLink: string;
  success: string;
};

export function ReviewSubmitForm({ copy, locale }: { copy: ReviewSubmitCopy; locale: Locale }) {
  const { sending, success, error, formKey, submit, closeSuccess } = useSiteForm(locale);

  return (
    <section className="container review-submit" id="leave-review">
      <div className="review-submit-copy">
        <h2>{copy.title}</h2>
        <p>{copy.description}</p>
        <p>{copy.detail}</p>
      </div>
      <form key={formKey} noValidate onSubmit={(event) => submit(event, "review")}>
        <FormHoneypot />
        <div className="review-submit-fields">
          <Field locale={locale}>
            <input name="name" aria-label={copy.name} placeholder={copy.name} required />
          </Field>
          <input name="company" aria-label={copy.company} placeholder={copy.company} />
          <Field locale={locale}>
            <input
              type="email"
              name="email"
              aria-label={copy.email}
              placeholder={copy.email}
              required
            />
          </Field>
          <PhoneField
            locale={locale}
            name="phone"
            aria-label={copy.phone}
            placeholder={copy.phone}
            required
          />
        </div>
        <Field locale={locale}>
          <textarea name="text" aria-label={copy.text} placeholder={copy.text} required />
        </Field>
        <FormSubmitError message={error} />
        <div className="review-submit-actions">
          <button className="btn btn-primary" type="submit" disabled={sending}>
            {sending ? sendingLabel(locale) : copy.submit}
          </button>
          <p>
            {copy.privacy} <Link href="/privacy-policy">{copy.privacyLink}</Link>
          </p>
        </div>
      </form>
      {success ? (
        <FormSentModal locale={locale} lead={copy.success} onClose={closeSuccess} />
      ) : null}
    </section>
  );
}
