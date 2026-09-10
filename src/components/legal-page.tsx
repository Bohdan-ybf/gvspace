import { LegalNavigation } from "./legal-navigation";
import type { Locale } from "@/i18n";

import { privacyContent, termsContent } from "@/i18n/page-copy";
type LegalKind = "privacy" | "terms";
type LegalSection = {
  title: string;
  paragraphs: string[];
  items?: string[];
  afterItems?: string[];
};

function LegalDocument({ locale, kind }: { locale: Locale; kind: LegalKind }) {
  const document = kind === "privacy" ? privacyContent[locale] : termsContent[locale];
  const sections = document.sections as readonly LegalSection[];
  const navigationSections = sections.map((section) => ({
    id: `section-${section.title.split(".")[0]}`,
    title: section.title,
  }));

  return (
    <main className="privacy-page">
      <header className="privacy-hero container">
        <p className="privacy-eyebrow mono">{document.eyebrow}</p>
        <h1>{document.title}</h1>
        <p className="privacy-updated mono">{document.updated}</p>
      </header>
      <div className="privacy-layout container">
        <aside className="privacy-aside">
          <LegalNavigation label={document.contents} sections={navigationSections} />
        </aside>
        <article className="privacy-document">
          {sections.map((section) => (
            <section key={section.title} id={`section-${section.title.split(".")[0]}`}>
              <span className="privacy-section-number mono">
                {section.title.split(".")[0].padStart(2, "0")}
              </span>
              <h2>{section.title.replace(/^\d+\.\s*/, "")}</h2>
              {section.paragraphs.map((paragraph) => (
                <p key={paragraph}>{paragraph}</p>
              ))}
              {section.items && (
                <ul>
                  {section.items.map((item) => (
                    <li key={item}>{item}</li>
                  ))}
                </ul>
              )}
              {section.afterItems?.map((paragraph) => (
                <p className="privacy-after-items" key={paragraph}>
                  {paragraph}
                </p>
              ))}
            </section>
          ))}
        </article>
      </div>
    </main>
  );
}

export function LegalPage({ locale, kind }: { locale: Locale; kind: LegalKind }) {
  return <LegalDocument locale={locale} kind={kind} />;
}
