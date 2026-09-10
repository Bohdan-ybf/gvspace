import Image from "next/image";
import Link from "next/link";
import { getDictionary, type Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { CasesCatalog } from "./cases-catalog";
import { ContactSection } from "./contact-section";
import { TechnologyShowcaseSection } from "./technology-showcase-section";
import { getCaseStudies } from "./wordpress-cases";

import { componentCopy } from "@/i18n/component-copy";
export async function CasesPage({ locale }: { locale: Locale }) {
  const text = getDictionary(locale);
  const projects = await getCaseStudies(locale);
  const copy = componentCopy[locale]["cases-page"];
  const contactText = {
    ...text.contact,
    title: copy.copy1,
    titleSecond: copy.copy2,
  };

  return (
    <main className="cases-page">
      <section className="cases-hero">
        <Image src="/images/cases/cases.webp" alt="" fill priority sizes="100vw" />
        <div className="container cases-hero-content">
          <span className="mono">CASES</span>
          <h1>{copy.copy3}</h1>
          <p>{copy.copy4}</p>
          <Link className="btn btn-primary" href={`/${locale}/contacts`}>
            {copy.copy5}
            <ArrowRight />
          </Link>
        </div>
      </section>

      <CasesCatalog locale={locale} projects={projects} />
      <TechnologyShowcaseSection locale={locale} />
      <ContactSection text={contactText} />
    </main>
  );
}
