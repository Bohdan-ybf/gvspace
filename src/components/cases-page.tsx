import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { Breadcrumbs } from "./breadcrumbs";
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
          <Image
            className="cases-hero-background"
            src="/images/cases/cases-bg.png"
            alt=""
            fill
            priority
            sizes="100vw"
          />
          <Image
            className="cases-hero-object"
            src="/images/cases/cases-object.png"
            alt=""
            width={497}
            height={474}
            priority
            sizes="(max-width: 600px) 340px, (max-width: 900px) 410px, 497px"
          />
          <div className="container cases-hero-content">
            <span className="mono">{t.heroEyebrow}</span>
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
        <Breadcrumbs
          locale={locale}
          items={[{ label: locale === "uk" ? "Кейси" : "Cases" }]}
          visible
        />
        <ContactSection text={contactText} />
      </main>
    </>
  );
}
