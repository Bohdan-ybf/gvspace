import Image from "next/image";
import type { Locale } from "@/i18n";
import { CareersValuesSection } from "./careers-values-section";
import { OpenApplicationBanner } from "./open-application-banner";
import { VacancyCard } from "./vacancy-card";
import { getVacancies } from "./wordpress-vacancies";

import { componentCopy } from "@/i18n/component-copy";
export async function CareersPage({ locale }: { locale: Locale }) {
  const copy = componentCopy[locale]["careers-page"];
  const vacancies = await getVacancies(locale);

  return (
    <main className="careers-page">
      <section className="careers-hero">
        <Image src="/images/careers/hero.webp" alt="" fill priority sizes="100vw" />
        <div className="container careers-hero-content">
          <span className="mono">CAREERS</span>
          <h1>{copy.copy1}</h1>
          <p>{copy.copy2}</p>
        </div>
      </section>

      <section className="careers-content container">
        <div className="careers-intro">
          <span className="mono">{copy.copy3}</span>
          <p>{copy.copy4}</p>
        </div>

        <section className="vacancies-section">
          <span className="mono">{copy.copy5}</span>
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
