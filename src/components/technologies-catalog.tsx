"use client";

import Image from "next/image";
import { useState } from "react";
import type { Locale } from "@/i18n";

const categories = [
  { uk: "Маркетинг & Аналітика", en: "Marketing & Analytics" },
  { uk: "Frontend & Web", en: "Frontend & Web" },
  { uk: "Backend & API", en: "Backend & API" },
  { uk: "Мобільні додатки", en: "Mobile Applications" },
  { uk: "Бази даних", en: "Databases" },
  { uk: "Cloud & DevOps", en: "Cloud & DevOps" },
];

const tools = [
  [
    "google-analytics",
    "Google Analytics 4",
    "Поведінковий аналіз аудиторії, воронки конверсій, event-трекінг.",
    "АНАЛІТИКА САЙТУ",
  ],
  [
    "google-tag-manager",
    "Google Tag Manager",
    "Управління всіма тегами та пікселями без правок у коді.",
    "ТЕГУВАННЯ",
  ],
  [
    "meta-pixel",
    "Meta Pixel & Ads",
    "Ретаргетинг, lookalike-аудиторії, оптимізація конверсій.",
    "ПЛАТНА РЕКЛАМА",
  ],
  [
    "google-ads",
    "Google Ads",
    "Search, Performance Max, Display кампанії з фокусом на ROI.",
    "ПЛАТНА РЕКЛАМА",
  ],
  [
    "looker-studio",
    "Looker Studio",
    "Кастомні дашборди, де власник бачить реальний стан без жаргону.",
    "ЗВІТНІСТЬ",
  ],
  [
    "power-bi",
    "Power BI",
    "Глибока бізнес-аналітика для складних воронок і юніт-економіки.",
    "BI & ЗВІТНІСТЬ",
  ],
  [
    "tiktok-ads",
    "TikTok Ads",
    "Платна реклама для аудиторій, яких немає в інших каналах.",
    "ПЛАТНА РЕКЛАМА",
  ],
  [
    "search-console",
    "Google Search Console",
    "SEO-моніторинг, аналіз пошукових запитів, індексація.",
    "SEO",
  ],
];

export function TechnologiesCatalog({ locale }: { locale: Locale }) {
  const [activeCategory, setActiveCategory] = useState(0);

  return (
    <section className="technologies-catalog section container">
      <nav aria-label={locale === "uk" ? "Категорії технологій" : "Technology categories"}>
        {categories.map((category, index) => (
          <button
            className={activeCategory === index ? "is-active" : undefined}
            type="button"
            onClick={() => setActiveCategory(index)}
            key={category.en}
          >
            <span className="mono">0{index + 1}</span>
            <b>{category[locale]}</b>
            <small>8</small>
          </button>
        ))}
      </nav>
      <div className="technology-tools-grid">
        {tools.map(([slug, name, description, tag]) => (
          <article key={slug}>
            <div className="technology-logo">
              <Image src={`/images/technologies/logos/${slug}.svg`} alt="" fill sizes="72px" />
            </div>
            <div>
              <h3>{name}</h3>
              <p>{description}</p>
              <span className="mono">{tag}</span>
            </div>
          </article>
        ))}
      </div>
    </section>
  );
}
