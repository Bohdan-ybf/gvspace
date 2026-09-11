import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { CasesCatalog } from "./cases-catalog";
import { ContactSection } from "./contact-section";
import { TechnologyShowcaseSection } from "./technology-showcase-section";
import { getCaseStudies } from "./wordpress-cases";

import { getTranslations } from "@/i18n/pages";
import { getLocalizedUrl } from "@/markets";
import { ItemListStructuredData } from "./structured-data";
export async function CasesPage({ locale }: { locale: Locale }) {
  const text = getTranslations("global", locale);
  const projects = await getCaseStudies(locale);
  const t = getTranslations("cases", locale).page;
  const contactText = {
    ...text.contact,
    title: t.contactTitle,
    titleSecond: t.contactTitleSecond,
  };

  return (
    <>
      <ItemListStructuredData
        name={locale === "uk" ? "Кейси GVSPACE" : "GVSPACE case studies"}
        items={projects.map((project) => ({
          name: project.title,
          url: getLocalizedUrl(locale, `/cases/${project.slug}`),
        }))}
      />
      <main className="cases-page">
      <section className="cases-hero">
        <Image src="/images/cases/cases.webp" alt="" fill priority sizes="100vw" />
        <div className="container cases-hero-content">
          <span className="mono">CASES</span>
          <h1>{t.heroTitle}</h1>
          <p>{t.heroDescription}</p>
          <Link className="btn btn-primary" href={`/${locale}/contacts`}>
            {t.heroAction}
            <ArrowRight />
          </Link>
        </div>
      </section>

      <CasesCatalog locale={locale} projects={projects} />
      <TechnologyShowcaseSection locale={locale} />
      <ContactSection text={contactText} />
      </main>
    </>
  );
}
