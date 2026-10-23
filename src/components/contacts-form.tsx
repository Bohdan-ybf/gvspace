"use client";

import type { Locale } from "@/i18n";
import { FormHoneypot, FormSubmitError, sendingLabel, useSiteForm } from "@/submit-site-form";
import { Field, PhoneField } from "./form-field";
import { FormSentModal } from "./form-sent-modal";

type ContactsFormProps = {
  locale: Locale;
  formLabel: string;
  namePlaceholder: string;
  phonePlaceholder: string;
  emailPlaceholder: string;
  topicPlaceholder: string;
  messagePlaceholder: string;
  submitLabel: string;
  consent: string;
};

export function ContactsForm({
  locale,
  formLabel,
  namePlaceholder,
  phonePlaceholder,
  emailPlaceholder,
  topicPlaceholder,
  messagePlaceholder,
  submitLabel,
  consent,
}: ContactsFormProps) {
  const { sending, success, error, formKey, submit, closeSuccess } = useSiteForm(locale);

  return (
    <form
      key={formKey}
      className="contacts-form"
      noValidate
      onSubmit={(event) => submit(event, "contacts")}
    >
      <h2 className="contact-label mono">{formLabel}</h2>
      <FormHoneypot />
      <div className="contacts-form-row">
        <Field locale={locale}>
          <input name="name" aria-label={namePlaceholder} placeholder={namePlaceholder} required />
        </Field>
        <PhoneField
          locale={locale}
          name="phone"
          aria-label={phonePlaceholder}
          placeholder={phonePlaceholder}
        />
      </div>
      <Field locale={locale}>
        <input
          name="email"
          aria-label={emailPlaceholder}
          type="email"
          placeholder={emailPlaceholder}
          required
        />
      </Field>
      <Field locale={locale}>
        <input name="topic" aria-label={topicPlaceholder} placeholder={topicPlaceholder} required />
      </Field>
      <Field locale={locale}>
        <textarea
          name="message"
          aria-label={messagePlaceholder}
          placeholder={messagePlaceholder}
          required
        />
      </Field>
      <FormSubmitError message={error} />
      <button className="btn btn-primary" type="submit" disabled={sending}>
        {sending ? sendingLabel(locale) : submitLabel}
      </button>
      <p className="mono">{consent}</p>
      {success ? <FormSentModal locale={locale} onClose={closeSuccess} /> : null}
    </form>
  );
}
