import Image from "next/image";
import Link from "next/link";
import { notFound } from "next/navigation";
import { getDictionary, type Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { getServiceOffering } from "./wordpress-services";
import { SystemTransitionSection } from "./system-transition-section";
import { TechnologyShowcaseSection } from "./technology-showcase-section";
import { CasesShowcaseSection } from "./cases-showcase-section";
import { ReviewsSection } from "./reviews-section";
import { ContactSection } from "./contact-section";

export async function ServiceDetailPage({ locale, slugs }: { locale: Locale; slugs: string[] }) {
  const result = await getServiceOffering(locale, slugs);
  if (!result) notFound();
  const { item, children } = result;
  const uk = locale === "uk";
  const isDirection = slugs.length === 1;
  const steps = item.steps.length ? item.steps : [
    { title: "Clarity Session", duration: uk ? "безкоштовно · 30 хв" : "free · 30 min", description: uk ? "Розбираємо вашу поточну ситуацію та визначаємо точки росту." : "We assess the current situation and identify growth points." },
    { title: uk ? "Архітектура системи" : "System architecture", duration: "14 days", description: uk ? "Створюємо план, аналітику та потрібні інструменти." : "We create the plan, analytics and required tools." },
    { title: uk ? "Запуск та оптимізація" : "Launch and optimization", duration: uk ? "від 30 днів" : "from 30 days", description: uk ? "Запускаємо, вимірюємо й покращуємо результат." : "We launch, measure and improve the result." },
  ];
  const dictionary = getDictionary(locale);

  return <main className="service-detail-page">
    <section className={`service-detail-hero${isDirection ? " is-direction" : ""}`}>
      <div className="container service-breadcrumb mono">SERVICES / {slugs.map((slug) => slug.toUpperCase()).join(" / ")}</div>
      <div className="container service-detail-hero-grid">
        {isDirection && <div className="service-detail-icon">
          {item.image ? <Image src={item.image} alt="" fill sizes="220px" unoptimized /> : <Image src={`/images/services/icons/${item.slug}.webp`} alt="" fill sizes="220px" />}
        </div>}
        <div><h1>{item.headline}</h1><p>{item.description}</p>
          <Link className="btn btn-primary" href={`/${locale}/contacts`}>{uk ? "Обговорити ваш проєкт" : "Discuss your project"}<ArrowRight /></Link>
        </div>
      </div>
    </section>

    {(children.length > 0 || item.includes.length > 0) && <section className="section container service-includes">
      <span className="mono">{uk ? "ЩО ВХОДИТЬ У НАПРЯМОК" : "WHAT IS INCLUDED"}</span>
      <div>{(children.length ? children.map((child) => ({ label: child.title, href: `/${locale}/services/${item.slug}/${child.slug}` })) : item.includes.map((label) => ({ label, href: "" }))).map((entry) => entry.href ? <Link href={entry.href} key={entry.label}><span>{entry.label}</span><ArrowRight /></Link> : <div key={entry.label}><span>✓ {entry.label}</span></div>)}</div>
    </section>}

    <section className={`section service-steps${isDirection ? " container" : " is-process"}`}>
      <div className={isDirection ? "" : "container"}><span className="mono">{uk ? "НАШ ПІДХІД" : "OUR APPROACH"}</span><h2>{isDirection ? (uk ? "Три кроки до керованого зростання" : "Three steps to managed growth") : (uk ? "Від запиту до результату" : "From request to result")}</h2>
        <div>{steps.map((step, index) => <article key={`${step.title}-${index}`}><small className="mono">[ 0{index + 1} ]　 {step.duration}</small><h3>{step.title}</h3><p>{step.description}</p></article>)}</div>
      </div>
    </section>

    {isDirection && <SystemTransitionSection locale={locale} />}
    {!isDirection && item.metrics.length > 0 && <section className="section container service-results"><span className="mono">{uk ? "РЕЗУЛЬТАТ КЛІЄНТА" : "CLIENT RESULT"}</span><div>{item.metrics.map((metric) => <b key={metric}>{metric}</b>)}</div></section>}
    <TechnologyShowcaseSection locale={locale} />
    <CasesShowcaseSection locale={locale} eyebrow={uk ? "КЕЙСИ ПО НАПРЯМКУ" : "CASES IN THIS DIRECTION"} />
    <ReviewsSection locale={locale} />
    {item.faq.length > 0 && <section className="section container service-faq"><div><h2>{uk ? "Часті запитання" : "Frequently asked questions"}</h2><p>{uk ? "та відповіді до них" : "and answers"}</p></div><div>{item.faq.map((faq) => <details key={faq.question}><summary>{faq.question}<span>+</span></summary><p>{faq.answer}</p></details>)}</div></section>}
    <ContactSection text={dictionary.contact} />
  </main>;
}
