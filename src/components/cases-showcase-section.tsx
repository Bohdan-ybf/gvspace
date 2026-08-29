import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { getCaseStudies } from "./wordpress-cases";

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
  const allProjects = await getCaseStudies();
  const filteredProjects = allProjects.filter((project) => project.slug !== excludeSlug);
  const projects = (filteredProjects.length || !allowExcludedFallback ? filteredProjects : allProjects).slice(
    0,
    limit,
  );
  const uk = locale === "uk";

  if (!projects.length) return null;

  return (
    <section className="section container cases-showcase">
      <header>
        <div>
          <span className="mono">{eyebrow ?? (uk ? "КЕЙСИ ЦИХ КЛІЄНТІВ" : "THESE CLIENTS’ CASES")}</span>
          <h2>{title ?? (uk ? "Від хаосу до результату" : "From chaos to results")}</h2>
        </div>
        <Link className="btn btn-primary" href={`/${locale}/cases`}>
          {uk ? "Усі кейси" : "All cases"}
          <ArrowRight />
        </Link>
      </header>

      <div className="cases-showcase-grid">
        {projects.map((project) => {
          const dateLabel = project.publishedAt
            ? new Intl.DateTimeFormat(uk ? "uk-UA" : "en-US", {
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
                      <dt>{uk ? "Головна цифра" : "Key figure"}</dt>
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
