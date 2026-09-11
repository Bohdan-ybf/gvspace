import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { TechnologyTabs } from "./technology-tabs";
import { getTechnologyStack } from "./wordpress-technologies";

import { getTranslations } from "@/i18n/pages";
type TechnologySectionProps = {
  locale: Locale;
  title?: string;
};

export async function TechnologySection({ locale, title }: TechnologySectionProps) {
  const t = getTranslations("technologies", locale).summary;
  const stack = await getTechnologyStack(locale);

  return (
    <section className="technology-stack-section">
      <div className="container">
        <div className="technology-stack-heading">
          <h2>{title ?? t.title}</h2>
          <Link className="btn btn-primary" href={`/${locale}/technologies`}>
            {t.allTechnologies}
            <ArrowRight />
          </Link>
        </div>
        <TechnologyTabs
          categories={stack.categories}
          items={stack.items}
          emptyLabel={t.emptyState}
        />
      </div>
    </section>
  );
}
