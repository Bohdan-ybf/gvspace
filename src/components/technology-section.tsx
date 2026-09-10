import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { TechnologyTabs } from "./technology-tabs";
import { getTechnologyStack } from "./wordpress-technologies";

import { componentCopy } from "@/i18n/component-copy";
type TechnologySectionProps = {
  locale: Locale;
  title?: string;
};

export async function TechnologySection({ locale, title }: TechnologySectionProps) {
  const copy = componentCopy[locale]["technology-section"];
  const stack = await getTechnologyStack(locale);

  return (
    <section className="technology-stack-section">
      <div className="container">
        <div className="technology-stack-heading">
          <h2>{title ?? copy.copy1}</h2>
          <Link className="btn btn-primary" href={`/${locale}/technologies`}>
            {copy.copy2}
            <ArrowRight />
          </Link>
        </div>
        <TechnologyTabs categories={stack.categories} items={stack.items} emptyLabel={copy.copy3} />
      </div>
    </section>
  );
}
