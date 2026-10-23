"use client";

import Link from "next/link";
import type { Locale } from "@/i18n";
import type { Messages } from "@/i18n/uk";
import { FormHoneypot, FormSubmitError, sendingLabel, useSiteForm } from "@/submit-site-form";
import { ContactMessageField } from "./contact-message-field";
import { FileUploadField } from "./file-upload-field";
import { FormSentModal } from "./form-sent-modal";

type ContactSectionProps = {
  text: Messages["contact"];
  locale: Locale;
};

export function ContactSection({ text, locale }: ContactSectionProps) {
  const { sending, success, error, formKey, submit, closeSuccess } = useSiteForm(locale);

  return (
    <section className="contact">
      <div>
        <span className="mono">{text.eyebrow}</span>
        <h2>
          {text.title}
          <br />
          {text.titleSecond}
        </h2>
        <p>{text.intro}</p>
      </div>

      <form key={formKey} onSubmit={(event) => submit(event, "contact")}>
        <FormHoneypot />
        <div>
          <input name="name" aria-label={text.name} placeholder={text.name} required />
          <input name="company" aria-label={text.company} placeholder={text.company} required />
        </div>
        <div>
          <input
            name="email"
            type="email"
            aria-label={text.email}
            placeholder={text.email}
            required
          />
          <input name="phone" aria-label={text.phone} placeholder={text.phonePlaceholder} />
        </div>
        <input name="source" aria-label={text.source} placeholder={text.source} />
        <ContactMessageField label={text.description} placeholder={text.description} />
        <FileUploadField className="contact-file" label={text.attachment} locale={locale} />
        <FormSubmitError message={error} />
        <div className="contact-actions">
          <button className="btn btn-primary" type="submit" disabled={sending}>
            {sending ? sendingLabel(locale) : text.submit}
          </button>
          <p className="contact-consent">
            {text.consent} <Link href="/privacy-policy">{text.consentMore}</Link>
          </p>
        </div>
      </form>
      {success ? <FormSentModal locale={locale} onClose={closeSuccess} /> : null}
    </section>
  );
}
