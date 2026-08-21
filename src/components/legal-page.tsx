import Link from "next/link";
import { Footer } from "./footer";
import { Header } from "./header";

type LegalKind = "privacy" | "terms";

const content = {
  uk: {
    privacy: { title: "Політика конфіденційності", text: "Тут буде текст" },
    terms: { title: "Правила використання", text: "Тут буде текст" },
    back: "На головну",
  },
  en: {
    privacy: { title: "Privacy Policy", text: "Text will be here" },
    terms: { title: "Terms of Use", text: "Text will be here" },
    back: "Back home",
  },
} as const;

export function LegalPage({ locale, kind }: { locale: string; kind: LegalKind }) {
  const language = locale === "en" ? "en" : "uk";
  const text = content[language];
  const document = text[kind];

  return (
    <>
      <Header locale={language} forceSolid />
      <main className="legal-page container">
        <h1>{document.title}</h1>
        <p className="legal-intro">{document.text}</p>
        <Link className="btn" href={`/${language}`}>
          {text.back}
        </Link>
      </main>
      <Footer locale={language} />
    </>
  );
}
