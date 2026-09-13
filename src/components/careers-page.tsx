import Image from "next/image";
import type { Locale } from "@/i18n";
import { CareersValuesSection } from "./careers-values-section";
import { Breadcrumbs } from "./breadcrumbs";
import { OpenApplicationBanner } from "./open-application-banner";
import { VacancyCard } from "./vacancy-card";
import { getVacancies } from "./wordpress-vacancies";

import { getTranslations } from "@/i18n/pages";
import { getLocalizedUrl } from "@/markets";
import { ItemListStructuredData } from "./structured-data";
export async function CareersPage({ locale }: { locale: Locale }) {
  const t = getTranslations("careers", locale).page;
  const vacancies = await getVacancies(locale);

  return (
    <>
      <ItemListStructuredData
        name={locale === "uk" ? "Кар’єра в GVSPACE" : "Careers at GVSPACE"}
        items={vacancies.map((vacancy) => ({
          name: vacancy.title,
          url: getLocalizedUrl(locale, `/careers/${vacancy.slug}`),
        }))}
      />
      <main className="careers-page">
        <section className="careers-hero">
          <Image
            className="careers-hero-background"
            src="/images/careers/careers-bg.png"
            alt=""
            fill
            priority
            sizes="100vw"
          />
          <Image
            className="careers-hero-object"
            src="/images/careers/careers-object.png"
            alt=""
            width={531}
            height={508}
            priority
            sizes="(max-width: 600px) 350px, (max-width: 900px) 420px, 531px"
          />
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
        <Breadcrumbs
          locale={locale}
          items={[{ label: locale === "uk" ? "Вакансії" : "Careers" }]}
          visible
        />
      </main>
    </>
  );
}
