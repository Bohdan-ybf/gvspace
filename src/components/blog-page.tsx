"use client";

import { useState } from "react";
import Link from "next/link";
import type { Locale } from "@/i18n";
import type { BlogPostSummary } from "./wordpress-posts";

type Category = "strategy" | "marketing" | "development" | "content" | "case" | "analytics";

const copy = {
  uk: {
    eyebrow: "INSIGHTS & CASES",
    title: "Простір для тих, хто думає про ріст",
    intro: "Статті, кейси та інсайти про системний маркетинг, IT і стратегію зростання.",
    all: "Усі",
    categories: {
      strategy: "Стратегія",
      marketing: "Маркетинг",
      development: "IT-розробка",
      content: "Контент & Продакшн",
      case: "Кейс",
      analytics: "Аналітика",
    },
    read: "Читати статтю",
    more: "Завантажити ще",
    stay: "ЗАЛИШАТИСЬ В КУРСІ",
    newsletter: "Інсайти про ріст — раз на тиждень",
    noSpam: "Без спаму. Тільки те, що допомагає ухвалювати рішення.",
    email: "your@email.com",
    subscribe: "Підписатись",
  },
  en: {
    eyebrow: "INSIGHTS & CASES",
    title: "A space for those who think about growth",
    intro: "Articles, cases and insights about systematic marketing, IT and growth strategy.",
    all: "All",
    categories: {
      strategy: "Strategy",
      marketing: "Marketing",
      development: "IT development",
      content: "Content & Production",
      case: "Case",
      analytics: "Analytics",
    },
    read: "Read article",
    more: "Load more",
    stay: "STAY UP TO DATE",
    newsletter: "Growth insights — once a week",
    noSpam: "No spam. Only ideas that help you make decisions.",
    email: "your@email.com",
    subscribe: "Subscribe",
  },
} as const;

export const fallbackArticles = [
  {
    category: "strategy",
    date: "08 трав 2026",
    time: 5,
    image: "featured.webp",
    title: "Чому ваш маркетинг виглядає як лотерея — і як це виправити за 30 днів",
    excerpt:
      "Розбираємо три системні помилки, які перетворюють рекламний бюджет на непередбачувані результати.",
    featured: true,
  },
  {
    category: "marketing",
    date: "08 трав 2026",
    time: 5,
    image: "cac-vs-ltv.webp",
    title: "CAC vs LTV: як власник бізнесу має читати цифри рекламних кабінетів",
  },
  {
    category: "development",
    date: "02 трав 2026",
    time: 7,
    image: "technical-debt.webp",
    title: "Технічний борг: що це таке і як він зупиняє масштабування бізнесу",
  },
  {
    category: "case",
    date: "25 кві 2026",
    time: 6,
    image: "cpl-case.webp",
    title: "Як ми знизили CPL на 30% без збільшення бюджету: розбір кейсу",
  },
  {
    category: "analytics",
    date: "18 кві 2026",
    time: 4,
    image: "ga4-metrics.webp",
    title: "GA4 для власника бізнесу: 5 метрик, які реально мають значення",
  },
  {
    category: "strategy",
    date: "14 трав 2026",
    time: 8,
    image: "north-star-metric.webp",
    title: "North Star Metric: як обрати одну метрику, яка об’єднає команду",
  },
  {
    category: "development",
    date: "03 кві 2026",
    time: 6,
    image: "nextjs-vs-react.webp",
    title: "Next.js або React: як обрати правильний фреймворк для продукту",
  },
] satisfies Array<{
  category: Category;
  date: string;
  time: number;
  image: string;
  title: string;
  excerpt?: string;
  featured?: boolean;
}>;

function categoryKey(value: string): Category {
  const key = value.toLowerCase();
  if (key.includes("маркет") || key.includes("market")) return "marketing";
  if (key.includes("it") || key.includes("розроб") || key.includes("develop")) return "development";
  if (key.includes("контент") || key.includes("content")) return "content";
  if (key.includes("кейс") || key.includes("case")) return "case";
  if (key.includes("анал") || key.includes("analy")) return "analytics";
  return "strategy";
}

export function BlogPage({ locale, posts = [] }: { locale: Locale; posts?: BlogPostSummary[] }) {
  const text = copy[locale];
  const articles = posts.map((post, index) => ({
    category: categoryKey(post.category),
    date: post.publishedAt,
    time: post.readingTime,
    image: post.image,
    title: post.title,
    excerpt: post.excerpt,
    featured: index === 0,
    slug: post.slug,
  }));
  const availableCategories = (
    ["strategy", "marketing", "development", "content", "case", "analytics"] as const
  ).filter((category) => articles.some((article) => article.category === category));
  const [active, setActive] = useState<Category | "all">("all");
  const filtered =
    active === "all" ? articles : articles.filter((article) => article.category === active);
  const featured = filtered.find((article) => article.featured) ?? filtered[0];
  const rest = filtered.filter((article) => article !== featured);

  return (
    <main className="blog-page">
      <section className="blog-hero">
        <div className="blog-hero-art" aria-hidden="true" />
        <div className="container blog-hero-content">
          <div>
            <span className="mono">{text.eyebrow}</span>
            <h1>{text.title}</h1>
          </div>
          <p>{text.intro}</p>
        </div>
      </section>

      <nav
        className="blog-filters"
        aria-label={locale === "uk" ? "Категорії блогу" : "Blog categories"}
      >
        {(["all", ...availableCategories] as const).map((category) => (
          <button
            key={category}
            type="button"
            className={active === category ? "is-active" : ""}
            onClick={() => setActive(category)}
          >
            {category === "all" ? text.all : text.categories[category]}
          </button>
        ))}
      </nav>

      <section className="container blog-catalog">
        {featured && (
          <article className="blog-featured">
            <div
              className="blog-card-image"
              style={
                featured.image
                  ? {
                      backgroundImage: `url(${featured.image.startsWith("http") ? featured.image : `/images/blog/${featured.image}`})`,
                    }
                  : undefined
              }
            >
              <span>{text.categories[featured.category]}</span>
            </div>
            <div className="blog-featured-copy">
              <small className="mono">
                {featured.date} · {featured.time} хв
              </small>
              <h2>{featured.title}</h2>
              {featured.excerpt && <p>{featured.excerpt}</p>}
              <Link href={`/${locale}/blog/${featured.slug}`}>{text.read} →</Link>
            </div>
          </article>
        )}
        <div className="blog-grid">
          {rest.map((article) => (
            <article className="blog-card" key={article.title}>
              <div
                className="blog-card-image"
                style={
                  article.image
                    ? {
                        backgroundImage: `url(${article.image.startsWith("http") ? article.image : `/images/blog/${article.image}`})`,
                      }
                    : undefined
                }
              >
                <span>{text.categories[article.category]}</span>
              </div>
              <small className="mono">
                {article.date} · {article.time} хв
              </small>
              <h3>{article.title}</h3>
              <Link href={article.slug ? `/${locale}/blog/${article.slug}` : `/${locale}/blog`}>
                {text.read.replace(" статтю", "")} →
              </Link>
            </article>
          ))}
        </div>
        {!featured && (
          <p className="blog-empty">
            {locale === "uk"
              ? "Статей ще немає. Опублікуйте перший запис у WordPress."
              : "There are no articles yet. Publish the first post in WordPress."}
          </p>
        )}
        {featured && (
          <button className="btn blog-load-more" type="button">
            {text.more} ↓
          </button>
        )}
      </section>

      <section className="blog-newsletter">
        <div className="container">
          <div>
            <span className="mono">{text.stay}</span>
            <h2>{text.newsletter}</h2>
            <p>{text.noSpam}</p>
          </div>
          <form>
            <label className="sr-only" htmlFor="blog-email">
              Email
            </label>
            <input id="blog-email" type="email" placeholder={text.email} />
            <button type="submit">{text.subscribe}</button>
          </form>
        </div>
      </section>
    </main>
  );
}
