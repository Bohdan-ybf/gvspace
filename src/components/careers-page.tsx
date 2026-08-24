import Image from "next/image";
import type { Locale } from "@/i18n";
import { CareersValuesSection } from "./careers-values-section";
import { OpenApplicationBanner } from "./open-application-banner";
import { VacancyCard } from "./vacancy-card";
import { getVacancies } from "./wordpress-vacancies";

export async function CareersPage({ locale }: { locale: Locale }) {
  const uk = locale === "uk";
  const vacancies = await getVacancies(locale);

  return (
    <main className="careers-page">
      <section className="careers-hero">
        <Image src="/images/careers/hero.webp" alt="" fill priority sizes="100vw" />
        <div className="container careers-hero-content">
          <span className="mono">CAREERS</span>
          <h1>
            {uk ? (
              <>
                Будуємо команду,
                <br />
                яка мислить системно
              </>
            ) : (
              <>
                Building a team
                <br />
                that thinks systematically
              </>
            )}
          </h1>
          <p>
            {uk
              ? "Шукаємо людей, для яких результат важливіший за процес. Якщо ви фахівець у своїй зоні — нам є про що поговорити."
              : "We are looking for people who value results over process. If you are an expert in your field, we should talk."}
          </p>
        </div>
      </section>

      <section className="careers-content container">
        <div className="careers-intro">
          <span className="mono">{uk ? "ЯК МИ ПРАЦЮЄМО" : "HOW WE WORK"}</span>
          <p>
            {uk ? (
              <>
                Тут немає мікроменеджменту і зайвих нарад.{" "}
                <b>Є зона відповідальності, чіткі цілі і команда</b>, яка підтримує, а не контролює.
              </>
            ) : (
              <>
                There is no micromanagement or unnecessary meetings.{" "}
                <b>There is ownership, clear goals, and a team</b> that supports rather than
                controls.
              </>
            )}
          </p>
        </div>

        <section className="vacancies-section">
          <span className="mono">{uk ? "НАШІ ВАКАНСІЇ" : "OPEN POSITIONS"}</span>
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
