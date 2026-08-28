import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { TechnologyTabs } from "./technology-tabs";
import { getTechnologyStack } from "./wordpress-technologies";

type TechnologySectionProps = {
  locale: Locale;
  title?: string;
};

export async function TechnologySection({ locale, title }: TechnologySectionProps) {
  const uk = locale === "uk";
  const stack = await getTechnologyStack(locale);

  return (
    <section className="technology-stack-section">
      <div className="container">
        <div className="technology-stack-heading">
          <h2>{title ?? (uk ? "Стек технологій" : "Technology stack")}</h2>
          <Link className="btn btn-primary" href={`/${locale}/technologies`}>
            {uk ? "Усі технології" : "All technologies"}
            <ArrowRight />
          </Link>
        </div>
        <TechnologyTabs
          categories={stack.categories}
          items={stack.items}
          emptyLabel={uk ? "Додайте технології у WordPress" : "Add technologies in WordPress"}
        />
      </div>
    </section>
  );
}
