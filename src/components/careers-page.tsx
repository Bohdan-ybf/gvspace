import Image from "next/image";
import type { Locale } from "@/i18n";
import { CareersValuesSection } from "./careers-values-section";
import { OpenApplicationBanner } from "./open-application-banner";
import { VacancyCard } from "./vacancy-card";
import { getVacancies } from "./wordpress-vacancies";

import { getTranslations } from "@/i18n/pages";
export async function CareersPage({ locale }: { locale: Locale }) {
  const t = getTranslations("careers", locale).page;
  const vacancies = await getVacancies(locale);

  return (
    <main className="careers-page">
      <section className="careers-hero">
        <Image src="/images/careers/hero.webp" alt="" fill priority sizes="100vw" />
        <div className="container careers-hero-content">
          <span className="mono">CAREERS</span>
          <h1>{t.heroTitle}</h1>
          <p>{t.heroDescription}</p>
        </div>
      </section>

      <section className="careers-content container">
        <div className="careers-intro">
          <span className="mono">{t.workEyebrow}</span>
          <p>{t.workDescription}</p>
        </div>

        <section className="vacancies-section">
          <span className="mono">{t.vacanciesEyebrow}</span>
          <div className="vacancies-grid">
            {vacancies.map((vacancy) => (
              <VacancyCard key={vacancy.slug} locale={locale} vacancy={vacancy} />
            ))}
          </div>
        </section>
      </section>

      <CareersValuesSection locale={locale} />
      <OpenApplicationBanner locale={locale} />
    </main>
  );
}
