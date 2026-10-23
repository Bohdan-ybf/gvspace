"use client";

import Link from "next/link";
import type { Locale } from "@/i18n";
import { FormHoneypot, FormSubmitError, sendingLabel, useSiteForm } from "@/submit-site-form";
import { FormSentModal } from "./form-sent-modal";

type ArticleCommentFormProps = {
  locale: Locale;
  articleTitle: string;
  name: string;
  email: string;
  text: string;
  submitLabel: string;
  consent: string;
  privacyLink: string;
};

export function ArticleCommentForm({
  locale,
  articleTitle,
  name,
  email,
  text,
  submitLabel,
  consent,
  privacyLink,
}: ArticleCommentFormProps) {
  const { sending, success, error, formKey, submit, closeSuccess } = useSiteForm(locale);
  const thanks =
    locale === "uk" ? "Дякуємо, коментар надіслано." : "Thank you, your comment was sent.";

  return (
    <>
      <form key={formKey} onSubmit={(event) => submit(event, "comment", { article: articleTitle })}>
        <div>
          <FormHoneypot />
          <input name="name" aria-label={name} placeholder={name} required />
          <input name="email" type="email" aria-label={email} placeholder={email} required />
        </div>
        <textarea name="text" aria-label={text} placeholder={text} required />
        <FormSubmitError message={error} />
        <div>
          <button type="submit" disabled={sending}>
            {sending ? sendingLabel(locale) : submitLabel}
          </button>
          <small>
            {consent} <Link href="/privacy-policy">{privacyLink}</Link>
          </small>
        </div>
      </form>
      {success ? <FormSentModal locale={locale} lead={thanks} onClose={closeSuccess} /> : null}
    </>
  );
}
