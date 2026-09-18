import Link from "next/link";
import type { Locale } from "@/i18n";
import type { Messages } from "@/i18n/uk";
import { ArrowRight } from "./icons/arrow-right";
import { CaseCard } from "./case-card";
import { getCaseStudies } from "./wordpress-cases";

type CasesSectionProps = {
  locale: Locale;
  text: Messages["cases"];
};

export async function CasesSection({ locale, text }: CasesSectionProps) {
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
        {projects.map((project) => (
          <CaseCard key={project.slug} locale={locale} project={project} variant="list" />
        ))}
      </div>
    </section>
  );
}
