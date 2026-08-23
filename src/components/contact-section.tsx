import type { Messages } from "@/i18n/uk";

type ContactSectionProps = {
  text: Messages["contact"];
};

export function ContactSection({ text }: ContactSectionProps) {
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
          <input aria-label={text.phone} placeholder="+38 0..." />
        </div>
        <input type="email" aria-label="Email" placeholder="Email" required />
        <input aria-label={text.topic} placeholder={text.topic} required />
        <textarea aria-label={text.description} placeholder={text.description} required />
        <button className="btn btn-primary">{text.submit}</button>
        <p className="contact-consent mono">{text.consent}</p>
      </form>
    </section>
  );
}
