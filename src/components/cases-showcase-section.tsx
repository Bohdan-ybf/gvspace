import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { CaseCard } from "./case-card";
import { getCaseStudies } from "./wordpress-cases";
import { getTranslations } from "@/i18n/pages";

type CasesShowcaseSectionProps = {
  locale: Locale;
  eyebrow?: string;
  title?: string;
  subtitle?: string;
  actionLabel?: string;
  limit?: number;
  excludeSlug?: string;
  allowExcludedFallback?: boolean;
  cardVariant?: "catalog" | "related" | "list";
  layout?: "grid" | "list";
};

export async function CasesShowcaseSection({
  locale,
  eyebrow,
  title,
  subtitle,
  actionLabel,
  limit = 3,
  excludeSlug,
  allowExcludedFallback = false,
  cardVariant = "related",
  layout = "grid",
}: CasesShowcaseSectionProps) {
  const allProjects = await getCaseStudies(locale);
  const filteredProjects = allProjects.filter((project) => project.slug !== excludeSlug);
  const projects = (
    filteredProjects.length || !allowExcludedFallback ? filteredProjects : allProjects
  ).slice(0, limit);
  const t = getTranslations("cases", locale).showcase;

  if (!projects.length) return null;

  const heading = title ?? t.title;
  const cta = actionLabel ?? t.allCases;

  if (layout === "list") {
    return (
      <section className="section container cases">
        <aside>
          <h2>{heading}</h2>
          {subtitle ? <p>{subtitle}</p> : null}
          <Link className="btn btn-primary" href={`/${locale}/cases`}>
            <span>{cta}</span>
            <ArrowRight />
          </Link>
        </aside>
        <div className="home-cases-list">
          {projects.map((project) => (
            <CaseCard key={project.slug} locale={locale} project={project} variant="list" />
          ))}
        </div>
      </section>
    );
  }

  return (
    <section className="section container cases-showcase">
      <header>
        <div>
          <span className="mono">{eyebrow ?? t.eyebrow}</span>
          <h2>{heading}</h2>
        </div>
        <Link className="btn btn-primary" href={`/${locale}/cases`}>
          {cta}
          <ArrowRight />
        </Link>
      </header>
      <div className="cases-showcase-grid">
        {projects.map((project) => (
          <CaseCard key={project.slug} locale={locale} project={project} variant={cardVariant} />
        ))}
      </div>
    </section>
  );
}
