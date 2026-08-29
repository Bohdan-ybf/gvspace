import { getDictionary, type Locale } from "@/i18n";
import { CasesShowcaseSection } from "./cases-showcase-section";
import { ContactSection } from "./contact-section";
import { ReviewsCatalog } from "./reviews-catalog";
import { getClientReviews } from "./wordpress-reviews";

export async function ReviewsPage({ locale }: { locale: Locale }) {
  const reviews = await getClientReviews(locale);
  const text = getDictionary(locale);
  const uk = locale === "uk";
  const companies = [...new Set(reviews.map((review) => review.company).filter(Boolean))].slice(0, 6);

  return <main className="reviews-page">
    <section className="reviews-hero">
      <div className="container reviews-hero-content">
        <span className="mono">CLIENTS &amp; REVIEWS</span>
        <div className="reviews-hero-heading">
          <h1>{uk ? <>Бізнеси, які обрали систему<br />замість лотереї</> : <>Businesses that chose a system<br />instead of a lottery</>}</h1>
          <p>{uk ? "Ми не збираємо логотипи. Ми будуємо системи — і просимо клієнтів говорити про результат." : "We do not collect logos. We build systems — and ask clients to speak about the results."}</p>
        </div>
        <dl>
          <div><dd>50+</dd><dt>{uk ? "ПРОЄКТІВ ЗАВЕРШЕНО" : "PROJECTS COMPLETED"}</dt></div>
          <div><dd>$10M+</dd><dt>{uk ? "БЮДЖЕТІВ ПІД УПРАВЛІННЯМ" : "BUDGETS MANAGED"}</dt></div>
          <div><dd>4</dd><dt>{uk ? "НАПРЯМКИ ПОСЛУГ" : "SERVICE DIRECTIONS"}</dt></div>
          <div><dd>87%</dd><dt>{uk ? "КЛІЄНТІВ ПОВЕРТАЮТЬСЯ" : "CLIENTS RETURN"}</dt></div>
        </dl>
      </div>
    </section>

    <section className="container review-clients">
      <span className="mono">{uk ? "КОМПАНІЇ, ЯКІ НАМ ДОВІРИЛИСЬ" : "COMPANIES THAT TRUSTED US"}</span>
      <div>{(companies.length ? companies : ["CLIENT 01", "CLIENT 02", "CLIENT 03", "CLIENT 04"]).map((company) => <span key={company}>{company}</span>)}</div>
    </section>

    <ReviewsCatalog locale={locale} reviews={reviews} />
    <CasesShowcaseSection locale={locale} />
    <ContactSection text={{ ...text.contact,
      title: uk ? "Ваш бізнес може бути" : "Your business could be",
      titleSecond: uk ? "наступним у цьому списку" : "next on this list",
    }} />
  </main>;
}
