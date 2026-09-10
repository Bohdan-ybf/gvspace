import Link from "next/link";
import type { Locale } from "@/i18n";
import type { Messages } from "@/i18n/uk";
import { ArrowRight } from "./icons/arrow-right";
import { getCaseStudies } from "./wordpress-cases";

import { componentCopy } from "@/i18n/component-copy";
type CasesSectionProps = {
  locale: Locale;
  text: Messages["cases"];
};

export async function CasesSection({ locale, text }: CasesSectionProps) {
  const copy = componentCopy[locale]["cases-section"];
  const projects = (await getCaseStudies(locale)).slice(0, 3);

  if (!projects.length) return null;

  return (
    <section className="section container cases">
      <aside>
        <h2>{text.title}</h2>
        <p>{text.subtitle}</p>
        <Link className="btn btn-primary" href={`/${locale}/cases`}>
          <span>{text.all}</span>
          <ArrowRight />
        </Link>
      </aside>

      <div className="home-cases-list">
        {projects.map((project) => {
          const dateLabel = project.publishedAt
            ? new Intl.DateTimeFormat(copy.copy1, {
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
            <article className="home-case-card" key={project.slug}>
              <div className="home-case-copy">
                <h3>
                  <Link href={`/${locale}/cases/${project.slug}`}>{project.title}</Link>
                </h3>
                <p className="home-case-result">[{project.result}]</p>
                <dl className="home-case-metrics">
                  {project.metrics.slice(0, 2).map((metric) => (
                    <div key={`${metric.value}-${metric.label}`}>
                      <dt>{copy.copy2}</dt>
                      <dd>
                        {metric.value} <small>{metric.label}</small>
                      </dd>
                    </div>
                  ))}
                </dl>
                <Link className="btn home-case-link" href={`/${locale}/cases/${project.slug}`}>
                  <span>{copy.copy3}</span>
                  <ArrowRight />
                </Link>
              </div>
              <Link
                className="case-image"
                href={`/${locale}/cases/${project.slug}`}
                style={project.image ? { backgroundImage: `url(${project.image})` } : undefined}
              >
                <span className="home-case-labels mono">
                  {dateLabel && <span>{dateLabel}</span>}
                  {categoryLabel && <span>[ {categoryLabel} ]</span>}
                </span>
              </Link>
            </article>
          );
        })}
      </div>
    </section>
  );
}
