import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { TechnologyShowcaseTabs } from "./technology-showcase-tabs";
import { getTechnologyStack } from "./wordpress-technologies";

export async function TechnologyShowcaseSection({
  locale,
  eyebrow,
  title,
}: {
  locale: Locale;
  eyebrow?: string;
  title?: string;
}) {
  const uk = locale === "uk";
  const stack = await getTechnologyStack(locale);

  return (
    <section className="section container technology-showcase">
      <header>
        <div>
          <span className="mono">
            {eyebrow ?? (uk ? "ТЕХНОЛОГІЧНИЙ ФУНДАМЕНТ" : "TECHNOLOGY FOUNDATION")}
          </span>
          <h2>{title ?? (uk ? "Правильний інструмент для кожної задачі" : "The right tool for every task")}</h2>
        </div>
      </header>
      <div className="technology-showcase-content">
        <TechnologyShowcaseTabs
          categories={stack.categories}
          items={stack.items}
          emptyLabel={uk ? "Додайте технології у WordPress" : "Add technologies in WordPress"}
        />
        <div className="technology-showcase-fade" aria-hidden="true" />
        <Link className="btn technology-showcase-more" href={`/${locale}/technologies`}>
          {uk ? "Усі технології" : "All technologies"}
          <ArrowRight />
        </Link>
      </div>
    </section>
  );
}
