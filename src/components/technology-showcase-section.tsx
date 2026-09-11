import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { TechnologyShowcaseTabs } from "./technology-showcase-tabs";
import { getTechnologyStack } from "./wordpress-technologies";

import { getTranslations } from "@/i18n/pages";
export async function TechnologyShowcaseSection({
  locale,
  eyebrow,
  title,
}: {
  locale: Locale;
  eyebrow?: string;
  title?: string;
}) {
  const t = getTranslations("technologies", locale).showcase;
  const stack = await getTechnologyStack(locale);

  return (
    <section className="section container technology-showcase">
      <header>
        <div>
          <span className="mono">{eyebrow ?? t.eyebrow}</span>
          <h2>{title ?? t.title}</h2>
        </div>
      </header>
      <div className="technology-showcase-content">
        <TechnologyShowcaseTabs
          categories={stack.categories}
          items={stack.items}
          emptyLabel={t.emptyState}
        />
        <div className="technology-showcase-fade" aria-hidden="true" />
        <Link className="btn technology-showcase-more" href={`/${locale}/technologies`}>
          {t.allTechnologies}
          <ArrowRight />
        </Link>
      </div>
    </section>
  );
}
