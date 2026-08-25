import Image from "next/image";
import Link from "next/link";
import { getDictionary, type Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { CasesCatalog } from "./cases-catalog";
import { ContactSection } from "./contact-section";
import { TechnologySection } from "./technology-section";
import { getCaseStudies } from "./wordpress-cases";

export async function CasesPage({ locale }: { locale: Locale }) {
  const text = getDictionary(locale);
  const projects = await getCaseStudies();
  const uk = locale === "uk";
  const contactText = {
    ...text.contact,
    title: uk ? "Ваш бізнес може бути" : "Your business could be",
    titleSecond: uk ? "наступним у цьому списку" : "the next one on this list",
  };

  return (
    <main className="cases-page">
      <section className="cases-hero">
        <Image src="/images/cases/cases.webp" alt="" fill priority sizes="100vw" />
        <div className="container cases-hero-content">
          <span className="mono">CASES</span>
          <h1>
            {uk ? (
              <>
                Від цифрового хаосу
                <br />
                до вимірюваних результатів
              </>
            ) : (
              <>
                From digital chaos
                <br />
                to measurable results
              </>
            )}
          </h1>
          <p>
            {uk
              ? "Кожен проєкт для нас — це не просто набір послуг, а історія перетворення бізнесу на стабільну систему."
              : "Every project is more than a set of services. It is the story of turning a business into a stable system."}
          </p>
          <Link className="btn btn-primary" href={`/${locale}/contacts`}>
            {uk ? "Обговорити ваш проєкт" : "Discuss your project"}
            <ArrowRight />
          </Link>
        </div>
      </section>

      <CasesCatalog locale={locale} projects={projects} />
      <TechnologySection locale={locale} />
      <ContactSection text={contactText} />
    </main>
  );
}
