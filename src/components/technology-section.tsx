import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";

const technologies = [
  "Meta",
  "Google",
  "Figma",
  "HubSpot",
  "Next.js",
  "Shopify",
  "Webflow",
  "GA4",
  "CRM",
  "Analytics",
  "Cloud",
];

type TechnologySectionProps = {
  locale: Locale;
  title?: string;
};

export function TechnologySection({ locale, title }: TechnologySectionProps) {
  const uk = locale === "uk";

  return (
    <section className="services-tech">
      <div className="container">
        <span className="mono">{uk ? "ТЕХНОЛОГІЧНИЙ ФУНДАМЕНТ" : "TECHNOLOGY FOUNDATION"}</span>
        <div className="services-tech-heading">
          <h2>
            {title ??
              (uk ? "Правильний інструмент для кожної задачі" : "The right tool for every task")}
          </h2>
          <Link className="btn" href={`/${locale}/technologies`}>
            {uk ? "Всі технології" : "All technologies"}
            <ArrowRight />
          </Link>
        </div>
        <div className="services-logos">
          {technologies.map((name) => (
            <div key={name}>
              <span className="mono">logo</span>
              <b>{name}</b>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
