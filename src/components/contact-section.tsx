import Link from "next/link";
import type { Locale } from "@/i18n";
import type { Messages } from "@/i18n/uk";
import { ContactMessageField } from "./contact-message-field";
import { FileUploadField } from "./file-upload-field";

type ContactSectionProps = {
  text: Messages["contact"];
  locale: Locale;
};

export function ContactSection({ text, locale }: ContactSectionProps) {
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

      <form>
        <div>
          <input aria-label={text.name} placeholder={text.name} required />
          <input aria-label={text.company} placeholder={text.company} required />
        </div>
        <div>
          <input type="email" aria-label={text.email} placeholder={text.email} required />
          <input aria-label={text.phone} placeholder={text.phonePlaceholder} />
        </div>
        <input aria-label={text.source} placeholder={text.source} />
        <ContactMessageField label={text.description} placeholder={text.description} />
        <FileUploadField className="contact-file" label={text.attachment} locale={locale} />
        <div className="contact-actions">
          <button className="btn btn-primary" type="submit">
            {text.submit}
          </button>
          <p className="contact-consent">
            {text.consent} <Link href="/privacy-policy">{text.consentMore}</Link>
          </p>
        </div>
      </form>
    </section>
  );
}
