import Link from "next/link";
import type { Locale } from "@/i18n";
import type { Messages } from "@/i18n/uk";
import { ContactMessageField } from "./contact-message-field";

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
        <label className="contact-file mono">
          <input type="file" />
          <AttachFileIcon />
          {text.attachment}
        </label>
        <div className="contact-actions">
          <button className="btn btn-primary" type="submit">
            {text.submit}
          </button>
          <p className="contact-consent">
            {text.consent} <Link href={`/${locale}/privacy-policy`}>{text.consentMore}</Link>
          </p>
        </div>
      </form>
    </section>
  );
}

function AttachFileIcon() {
  return (
    <svg
      width="18"
      height="22"
      viewBox="0 0 18 22"
      fill="none"
      aria-hidden="true"
      focusable="false"
    >
      <path
        d="M0.75 10.75V13.295C0.75 16.54 0.75 18.162 1.636 19.261C1.81494 19.4829 2.01709 19.6851 2.239 19.864C3.34 20.75 4.961 20.75 8.206 20.75C8.911 20.75 9.264 20.75 9.587 20.637C9.65367 20.6123 9.71933 20.585 9.784 20.555C10.094 20.407 10.343 20.157 10.842 19.659L15.578 14.922C16.157 14.344 16.445 14.055 16.598 13.687C16.75 13.32 16.75 12.911 16.75 12.094V8.75C16.75 4.978 16.75 3.093 15.578 1.921C14.519 0.861 12.877 0.761 9.785 0.751M9.75 20.25V19.75C9.75 16.921 9.75 15.507 10.629 14.628C11.507 13.75 12.922 13.75 15.75 13.75H16.25M8.75 4.75H0.75M4.75 0.75V8.75"
        stroke="white"
        strokeWidth="1.5"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
}
