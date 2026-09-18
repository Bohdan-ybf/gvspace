import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";
import { CaseArrow } from "./icons/case-arrow";
import type { CaseStudy } from "./wordpress-cases";

function caseDateLabel(date: string | undefined, locale: string) {
  if (!date) return "";
  return new Intl.DateTimeFormat(locale, { month: "long", year: "numeric" })
    .format(new Date(date))
    .replace(/\s*р\.?$/i, "")
    .toUpperCase();
}

function caseCategoryLabel(project: CaseStudy) {
  const parts = [project.projectType, project.direction].filter(Boolean);
  return parts.length ? `[ ${parts.join(" / ")} ]` : "";
}

function metricUnit(label: string) {
  return label.length <= 8 ? label : "";
}

export function CaseCard({
  locale,
  project,
  variant = "catalog",
}: {
  locale: Locale;
  project: CaseStudy;
  variant?: "catalog" | "related" | "list";
}) {
  const catalog = getTranslations("cases", locale).catalog;
  const dateLabel = caseDateLabel(project.publishedAt, catalog.dateLocale);
  const categoryLabel = caseCategoryLabel(project);
  const href = `/${locale}/cases/${project.slug}`;
  const metrics = variant === "catalog" ? [] : project.metrics.slice(0, 2);

  if (variant === "list") {
    return (
      <article className="home-case-card">
        <div className="home-case-copy">
          <h3>
            <Link href={href}>{project.catalogTitle}</Link>
          </h3>
          {project.result ? <p className="home-case-result">[{project.result}]</p> : null}
          {metrics.length > 0 && (
            <dl className="home-case-metrics">
              {metrics.map((metric) => {
                const unit = metricUnit(metric.label);
                return (
                  <div key={`${metric.value}-${metric.label}`}>
                    <dt>{catalog.metricLabel}</dt>
                    <dd>
                      {metric.value}
                      {unit ? <small> {unit}</small> : null}
                    </dd>
                  </div>
                );
              })}
            </dl>
          )}
          <Link className="btn home-case-link" href={href}>
            {catalog.openCase}
          </Link>
        </div>
        <Link className="case-image" href={href}>
          {project.image ? (
            <Image
              alt=""
              fill
              sizes="(max-width: 900px) 100vw, 50vw"
              src={project.image}
              unoptimized
            />
          ) : null}
          <span className="home-case-labels mono">
            {dateLabel ? <span>{dateLabel}</span> : null}
            {categoryLabel ? <span>{categoryLabel}</span> : null}
          </span>
        </Link>
      </article>
    );
  }

  if (variant === "related") {
    return (
      <article className="showcase-case-card">
        <Link className="showcase-case-image" href={href}>
          {project.image ? (
            <Image
              alt=""
              fill
              sizes="(max-width: 900px) 100vw, 33vw"
              src={project.image}
              unoptimized
            />
          ) : null}
          <span className="showcase-case-labels mono">
            {dateLabel ? <span>{dateLabel}</span> : null}
            {categoryLabel ? <span>{categoryLabel}</span> : null}
          </span>
        </Link>
        <div className="showcase-case-copy">
          <h3>
            <Link href={href}>{project.catalogTitle}</Link>
          </h3>
          {project.result ? <p>[{project.result}]</p> : null}
          {metrics.length > 0 && (
            <dl>
              {metrics.map((metric) => {
                const unit = metricUnit(metric.label);
                return (
                  <div key={`${metric.value}-${metric.label}`}>
                    <dt>{catalog.metricLabel}</dt>
                    <dd>
                      {metric.value}
                      {unit ? <small> {unit}</small> : null}
                    </dd>
                  </div>
                );
              })}
            </dl>
          )}
        </div>
      </article>
    );
  }

  return (
    <article className="catalog-case-card">
      <Link className="catalog-case-image" href={href}>
        {project.image ? (
          <Image
            alt=""
            fill
            sizes="(max-width: 900px) 100vw, 50vw"
            src={project.image}
            unoptimized
          />
        ) : null}
        <span className="catalog-case-labels mono">
          {dateLabel ? <b>{dateLabel}</b> : null}
          {categoryLabel ? <b>{categoryLabel}</b> : null}
        </span>
      </Link>
      <div className="catalog-case-copy">
        <h3>
          <Link href={href}>{project.catalogTitle}</Link>
        </h3>
        {project.result ? <p>[{project.result}]</p> : null}
        <Link aria-hidden="true" className="catalog-case-arrow" href={href} tabIndex={-1}>
          <CaseArrow />
        </Link>
      </div>
    </article>
  );
}
