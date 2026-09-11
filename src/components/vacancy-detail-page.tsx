import Image from "next/image";
import { notFound } from "next/navigation";
import type { Locale } from "@/i18n";
import { getVacancyBySlug } from "./wordpress-vacancies";
import { VacancyApplicationForm } from "./vacancy-application-form";

import { getTranslations } from "@/i18n/pages";
import { getDynamicSeo } from "@/wordpress-seo";
import { Breadcrumbs } from "./breadcrumbs";
import { StructuredData } from "./structured-data";
export async function VacancyDetailPage({ locale, slug }: { locale: Locale; slug: string }) {
  const vacancy = await getVacancyBySlug(slug, locale);
  if (!vacancy) notFound();
  const seo = await getDynamicSeo("vacancy", slug, locale);
  const t = getTranslations("vacancies", locale).detail;
  const sectionTitles = {
    role: t.roleTitle,
    tasks: t.tasksTitle,
    requirements: t.requirementsTitle,
    tools: t.toolsTitle,
    benefits: t.benefitsTitle,
  };

  return (
    <>
      <StructuredData
        data={{
          "@context": "https://schema.org",
          "@type": "JobPosting",
          title: seo?.h1 || vacancy.title[locale],
          description: vacancy.role.map((item) => item[locale]).join("\n\n"),
          hiringOrganization: {
            "@type": "Organization",
            name: "GVSPACE",
            sameAs: "https://gvspace.com",
          },
          ...(seo?.datePublished ? { datePosted: seo.datePublished } : {}),
        }}
      />
      <Breadcrumbs
        locale={locale}
        items={[{ label: t.careersLabel, pathname: "/careers" }, { label: seo?.h1 || vacancy.title[locale] }]}
      />
      <main className="vacancy-detail-page">
      <section className="vacancy-detail-hero">
        <Image src={vacancy.heroImage} alt="" fill priority sizes="100vw" />
        <div className="container vacancy-detail-hero-content">
          <div className="vacancy-title-row">
            <h1>{seo?.h1 || vacancy.title[locale]}</h1>
            {vacancy.hot && <span className="mono">{t.hotLabel}</span>}
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
    </>
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
