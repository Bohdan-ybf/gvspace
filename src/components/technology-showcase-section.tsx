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
  excludeSlug,
  initialCategory,
}: {
  locale: Locale;
  eyebrow?: string;
  title?: string;
  excludeSlug?: string;
  initialCategory?: string;
}) {
  const t = getTranslations("technologies", locale).showcase;
  const stack = await getTechnologyStack(locale);
  const items = excludeSlug ? stack.items.filter((item) => item.slug !== excludeSlug) : stack.items;
  const categories = stack.categories.filter((category) =>
    items.some((item) => item.categorySlugs.includes(category.slug)),
  );
  if (!items.length) return null;

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
          locale={locale}
          categories={categories}
          items={items}
          emptyLabel={t.emptyState}
          initialCategory={initialCategory}
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
