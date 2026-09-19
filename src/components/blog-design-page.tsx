"use client";

import { useMemo, useState } from "react";
import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";
import type { BlogPostSummary } from "./wordpress-posts";
import { Breadcrumbs } from "./breadcrumbs";

const POSTS_PER_PAGE = 6;

const imageUrl = (image?: string) =>
  image ? (image.startsWith("http") ? image : `/images/blog/${image}`) : "/images/blog/blog-bg.png";

export function BlogDesignPage({
  locale,
  posts = [],
}: {
  locale: Locale;
  posts?: BlogPostSummary[];
}) {
  const t = getTranslations("blog", locale).page;
  const text = getTranslations("blog", locale).content;
  const [activeCategory, setActiveCategory] = useState("all");
  const [page, setPage] = useState(1);
  const categories = useMemo(() => {
    const unique = new Map<string, string>();
    posts.forEach((post) => unique.set(post.categorySlug, post.category));
    return [...unique].map(([slug, name]) => ({ slug, name }));
  }, [posts]);
  const filtered =
    activeCategory === "all" ? posts : posts.filter((post) => post.categorySlug === activeCategory);
  const featured = filtered[0];
  const remaining = filtered.slice(1);
  const totalPages = Math.max(1, Math.ceil(remaining.length / POSTS_PER_PAGE));
  const currentPage = Math.min(page, totalPages);
  const visible = remaining.slice((currentPage - 1) * POSTS_PER_PAGE, currentPage * POSTS_PER_PAGE);

  const selectCategory = (slug: string) => {
    setActiveCategory(slug);
    setPage(1);
  };
  const selectPage = (next: number) => {
    setPage(Math.min(totalPages, Math.max(1, next)));
    document.querySelector(".blog-catalog")?.scrollIntoView({ behavior: "smooth", block: "start" });
  };

  return (
    <main className="blog-page">
      <section className="blog-hero">
        <Image
          className="blog-hero-background"
          src="/images/blog/blog-bg.png"
          alt=""
          fill
          priority
          sizes="100vw"
        />
        <Image
          className="blog-hero-object"
          src="/images/blog/blog-object.png"
          alt=""
          width={450}
          height={429}
          priority
        />
        <div className="container blog-hero-content">
          <div className="blog-hero-copy">
            <span className="mono">BLOG</span>
            <h1>{text.title}</h1>
            <p>{text.intro}</p>
            <div className="blog-topic-label mono">{text.chooseTopic}</div>
            <nav className="blog-filters" aria-label={t.filtersLabel}>
              <button
                type="button"
                className={activeCategory === "all" ? "is-active" : ""}
                onClick={() => selectCategory("all")}
              >
                {text.all}
              </button>
              {categories.map(({ slug, name }) => (
                <button
                  type="button"
                  key={slug}
                  className={activeCategory === slug ? "is-active" : ""}
                  onClick={() => selectCategory(slug)}
                >
                  {name}
                </button>
              ))}
            </nav>
          </div>
        </div>
      </section>

      <section className="container blog-catalog" aria-live="polite">
        {featured ? (
          <>
            <article className="blog-featured">
              <Link
                className={`blog-card-image${featured.image ? "" : " is-placeholder"}`}
                href={`/${locale}/blog/${featured.slug}`}
                style={{ backgroundImage: `url(${imageUrl(featured.image)})` }}
                aria-label={featured.title}
              >
                <span>{featured.category}</span>
              </Link>
              <div className="blog-featured-copy">
                <div className="blog-meta mono">
                  <small>
                    {featured.publishedAt} · {featured.readingTime} {text.minutesShort}
                  </small>
                  <small>
                    {text.author}: {featured.authorName}
                  </small>
                </div>
                <h2>
                  <Link href={`/${locale}/blog/${featured.slug}`}>{featured.title}</Link>
                </h2>
                {featured.excerpt && <p>{featured.excerpt}</p>}
              </div>
            </article>
            <div className="blog-grid">
              {visible.map((article) => (
                <article className="blog-card" key={article.slug}>
                  <Link
                    className={`blog-card-image${article.image ? "" : " is-placeholder"}`}
                    href={`/${locale}/blog/${article.slug}`}
                    style={{ backgroundImage: `url(${imageUrl(article.image)})` }}
                    aria-label={article.title}
                  />
                  <div className="blog-card-meta mono">
                    <span>{article.category}</span>
                    <small>
                      {article.publishedAt} · {article.readingTime} {text.minutesShort}
                    </small>
                  </div>
                  <h3>
                    <Link href={`/${locale}/blog/${article.slug}`}>{article.title}</Link>
                  </h3>
                  <small className="blog-card-author">
                    {text.author}: {article.authorName}
                  </small>
                </article>
              ))}
            </div>
            {currentPage < totalPages && (
              <button
                className="btn blog-load-more"
                type="button"
                onClick={() => selectPage(currentPage + 1)}
              >
                {text.more} ↓
              </button>
            )}
            {totalPages > 1 && (
              <nav className="blog-pagination" aria-label={text.paginationLabel}>
                <button
                  type="button"
                  onClick={() => selectPage(currentPage - 1)}
                  disabled={currentPage === 1}
                  aria-label={text.previousPage}
                >
                  ‹
                </button>
                {Array.from({ length: totalPages }, (_, i) => i + 1).map((number) => (
                  <button
                    type="button"
                    key={number}
                    className={number === currentPage ? "is-active" : ""}
                    onClick={() => selectPage(number)}
                    aria-current={number === currentPage ? "page" : undefined}
                  >
                    {number}
                  </button>
                ))}
                <button
                  type="button"
                  onClick={() => selectPage(currentPage + 1)}
                  disabled={currentPage === totalPages}
                  aria-label={text.nextPage}
                >
                  ›
                </button>
              </nav>
            )}
          </>
        ) : (
          <p className="blog-empty">{t.emptyState}</p>
        )}
      </section>

      <Breadcrumbs locale={locale} items={[{ label: text.breadcrumb }]} visible />
      <section className="blog-newsletter">
        <div className="container">
          <div>
            <span className="mono">{text.stay}</span>
            <h2>{text.newsletter}</h2>
            <p>{text.noSpam}</p>
          </div>
          <form onSubmit={(event) => event.preventDefault()}>
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
