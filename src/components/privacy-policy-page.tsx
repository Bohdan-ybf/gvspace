import { Breadcrumbs } from "./breadcrumbs";
import { LegalNavigation } from "./legal-navigation";
import type { Locale } from "@/i18n";

export type LegalDocument = {
  kicker: string;
  title: string;
  dates: string;
  contents: string;
  sections: { number: string; title: string; body: string }[];
};

export function PrivacyPolicyPage({
  locale,
  document,
}: {
  locale: Locale;
  document: LegalDocument;
}) {
  const navigationSections = document.sections.map((section, index) => ({
    id: `section-${section.number || String(index + 1).padStart(2, "0")}`,
    number: section.number || String(index + 1).padStart(2, "0"),
    title: section.title,
  }));
  const dateParts = document.dates
    .split(/\s*·\s*/)
    .map((part) => part.trim())
    .filter(Boolean);

  return (
    <main className="privacy-page">
      <header className="privacy-hero container">
        <p className="privacy-eyebrow mono">{document.kicker}</p>
        <h1>{document.title}</h1>
        <p className="privacy-updated mono">
          {dateParts.map((part, index) => (
            <span key={part}>
              {index > 0 ? <span className="privacy-updated-sep"> · </span> : null}
              {part}
            </span>
          ))}
        </p>
      </header>
      <div className="privacy-layout container">
        <aside className="privacy-aside">
          <LegalNavigation label={document.contents} sections={navigationSections} />
        </aside>
        <article className="privacy-document">
          {document.sections.map((section, index) => {
            const number = section.number || String(index + 1).padStart(2, "0");
            return (
              <section key={`${number}-${section.title}`} id={`section-${number}`}>
                <span className="privacy-section-number mono">{number}</span>
                <h2>{section.title}</h2>
                <p className="privacy-section-body">{section.body}</p>
              </section>
            );
          })}
        </article>
      </div>
      <Breadcrumbs locale={locale} visible homeLabel="HOME" items={[{ label: document.title }]} />
    </main>
  );
}
