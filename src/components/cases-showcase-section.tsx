import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { getCaseStudies } from "./wordpress-cases";

import { componentCopy } from "@/i18n/component-copy";
type CasesShowcaseSectionProps = {
  locale: Locale;
  eyebrow?: string;
  title?: string;
  limit?: number;
  excludeSlug?: string;
  allowExcludedFallback?: boolean;
};

export async function CasesShowcaseSection({
  locale,
  eyebrow,
  title,
  limit = 3,
  excludeSlug,
  allowExcludedFallback = false,
}: CasesShowcaseSectionProps) {
  const allProjects = await getCaseStudies(locale);
  const filteredProjects = allProjects.filter((project) => project.slug !== excludeSlug);
  const projects = (
    filteredProjects.length || !allowExcludedFallback ? filteredProjects : allProjects
  ).slice(0, limit);
  const copy = componentCopy[locale]["cases-showcase-section"];

  if (!projects.length) return null;

  return (
    <section className="section container cases-showcase">
      <header>
        <div>
          <span className="mono">{eyebrow ?? copy.copy1}</span>
          <h2>{title ?? copy.copy2}</h2>
        </div>
        <Link className="btn btn-primary" href={`/${locale}/cases`}>
          {copy.copy3}
          <ArrowRight />
        </Link>
      </header>

      <div className="cases-showcase-grid">
        {projects.map((project) => {
          const dateLabel = project.publishedAt
            ? new Intl.DateTimeFormat(copy.copy4, {
                month: "long",
                year: "numeric",
              })
                .format(new Date(project.publishedAt))
                .toUpperCase()
            : null;
          const categoryLabel = [project.projectType, ...project.services]
            .filter(Boolean)
            .slice(0, 3)
            .join(" / ");

          return (
            <article className="showcase-case-card" key={project.slug}>
              <Link
                aria-label={project.title}
                className="showcase-case-image"
                href={`/${locale}/cases/${project.slug}`}
                style={project.image ? { backgroundImage: `url(${project.image})` } : undefined}
              >
                <span className="showcase-case-labels mono">
                  {dateLabel && <span>{dateLabel}</span>}
                  {categoryLabel && <span>[ {categoryLabel} ]</span>}
                </span>
              </Link>

              <div className="showcase-case-copy">
                <h3>
                  <Link href={`/${locale}/cases/${project.slug}`}>{project.title}</Link>
                </h3>
                <p>[{project.result}]</p>
                <dl>
                  {project.metrics.slice(0, 2).map((metric) => (
                    <div key={`${metric.value}-${metric.label}`}>
                      <dt>{copy.copy5}</dt>
                      <dd>
                        {metric.value} <small>{metric.label}</small>
                      </dd>
                    </div>
                  ))}
                </dl>
              </div>
            </article>
          );
        })}
      </div>
    </section>
  );
}
