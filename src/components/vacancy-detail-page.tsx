import Image from "next/image";
import Link from "next/link";
import { notFound } from "next/navigation";
import type { Locale } from "@/i18n";
import { getVacancyBySlug } from "./wordpress-vacancies";
import { VacancyApplicationForm } from "./vacancy-application-form";

import { componentCopy } from "@/i18n/component-copy";
export async function VacancyDetailPage({ locale, slug }: { locale: Locale; slug: string }) {
  const vacancy = await getVacancyBySlug(slug, locale);
  if (!vacancy) notFound();
  const copy = componentCopy[locale]["vacancy-detail-page"];
  const sectionTitles = {
    role: copy.copy1,
    tasks: copy.copy2,
    requirements: copy.copy3,
    tools: copy.copy4,
    benefits: copy.copy5,
  };

  return (
    <main className="vacancy-detail-page">
      <section className="vacancy-detail-hero">
        <Image src={vacancy.heroImage} alt="" fill priority sizes="100vw" />
        <div className="container vacancy-detail-hero-content">
          <nav className="vacancy-breadcrumbs mono" aria-label={copy.copy6}>
            <Link href={`/${locale}/careers`}>{copy.copy7}</Link>
            <span aria-hidden="true">/</span>
            <span aria-current="page">{vacancy.title[locale]}</span>
          </nav>
          <div className="vacancy-title-row">
            <h1>{vacancy.title[locale]}</h1>
            {vacancy.hot && <span className="mono">{copy.copy8}</span>}
          </div>
          <div className="vacancy-detail-tags mono">
            {vacancy.tags.map((tag) => (
              <span key={tag}>{tag}</span>
            ))}
          </div>
          <b>{vacancy.salary}</b>
        </div>
      </section>

      <div className="vacancy-detail-layout container">
        <div className="vacancy-description">
          <VacancyTextSection
            title={sectionTitles.role}
            paragraphs={vacancy.role.map((item) => item[locale])}
          />
          <VacancyListSection
            title={sectionTitles.tasks}
            items={vacancy.tasks.map((item) => item[locale])}
            marker="—"
          />
          <VacancyListSection
            title={sectionTitles.requirements}
            items={vacancy.requirements.map((item) => item[locale])}
            marker="✓"
          />
          <section className="vacancy-content-section">
            <h2 className="mono">{sectionTitles.tools}</h2>
            <div className="vacancy-tools mono">
              {vacancy.tools.map((tool) => (
                <span key={tool}>{tool}</span>
              ))}
            </div>
          </section>
          <VacancyListSection
            title={sectionTitles.benefits}
            items={vacancy.benefits.map((item) => item[locale])}
            marker="+"
            accent
          />
        </div>
        <VacancyApplicationForm locale={locale} />
      </div>
    </main>
  );
}

function VacancyTextSection({ title, paragraphs }: { title: string; paragraphs: string[] }) {
  return (
    <section className="vacancy-content-section">
      <h2 className="mono">{title}</h2>
      {paragraphs.map((paragraph) => (
        <p key={paragraph}>{paragraph}</p>
      ))}
    </section>
  );
}

function VacancyListSection({
  title,
  items,
  marker,
  accent = false,
}: {
  title: string;
  items: string[];
  marker: string;
  accent?: boolean;
}) {
  return (
    <section className={`vacancy-content-section${accent ? " is-accent" : ""}`}>
      <h2 className="mono">{title}</h2>
      <ul>
        {items.map((item) => (
          <li key={item}>
            <span>{marker}</span>
            {item}
          </li>
        ))}
      </ul>
    </section>
  );
}
