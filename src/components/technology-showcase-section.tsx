import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { TechnologyShowcaseTabs } from "./technology-showcase-tabs";
import { getTechnologyStack } from "./wordpress-technologies";

import { componentCopy } from "@/i18n/component-copy";
export async function TechnologyShowcaseSection({
  locale,
  eyebrow,
  title,
}: {
  locale: Locale;
  eyebrow?: string;
  title?: string;
}) {
  const copy = componentCopy[locale]["technology-showcase-section"];
  const stack = await getTechnologyStack(locale);

  return (
    <section className="section container technology-showcase">
      <header>
        <div>
          <span className="mono">{eyebrow ?? copy.copy1}</span>
          <h2>{title ?? copy.copy2}</h2>
        </div>
      </header>
      <div className="technology-showcase-content">
        <TechnologyShowcaseTabs
          categories={stack.categories}
          items={stack.items}
          emptyLabel={copy.copy3}
        />
        <div className="technology-showcase-fade" aria-hidden="true" />
        <Link className="btn technology-showcase-more" href={`/${locale}/technologies`}>
          {copy.copy4}
          <ArrowRight />
        </Link>
      </div>
    </section>
  );
}
