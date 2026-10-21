"use client";

import { useMemo, useRef, useState, type ComponentType, type SVGProps } from "react";
import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";
import {
  ClutchIcon,
  FacebookIcon,
  InstagramIcon,
  LinkedinIcon,
  TelegramIcon,
  XIcon,
} from "./icons/social-icons";
import type { BlogPostSummary } from "./wordpress-posts";
import { Breadcrumbs } from "./breadcrumbs";
import { BlogFeaturedPost, BlogPostCard } from "./blog-post-cards";
import { Pagination, scrollToBlockTop } from "./pagination";
import { ContentImageLabel } from "./content-image-label";
import { contentImageLabel } from "@/content-images";

const POSTS_PER_PAGE = 6;

const socialIcons: Record<string, ComponentType<SVGProps<SVGSVGElement>>> = {
  facebook: FacebookIcon,
  instagram: InstagramIcon,
  linkedin: LinkedinIcon,
  clutch: ClutchIcon,
  x: XIcon,
  twitter: XIcon,
  telegram: TelegramIcon,
};

export function BlogDesignPage({
  locale,
  posts = [],
  socials = [],
}: {
  locale: Locale;
  posts?: BlogPostSummary[];
  socials?: { name: string; href: string; network: string }[];
}) {
  const t = getTranslations("blog", locale).page;
  const text = getTranslations("blog", locale).content;
  const [activeCategory, setActiveCategory] = useState("all");
  const [page, setPage] = useState(1);
  const catalogRef = useRef<HTMLElement>(null);
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
    scrollToBlockTop(catalogRef.current);
  };

  return (
    <ContentImageLabel label={contentImageLabel(text.title)}>
      <main className="blog-page">
        <div className="blog-catalog-region">
          <section className="blog-hero">
            <Image
              className="blog-hero-background"
              src="/images/blog/blog.webp"
              alt=""
              fill
              priority
              sizes="100vw"
            />
            <Image
              className="blog-hero-object"
              src="/images/blog/blog-object.webp"
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
                    {text.all.toLocaleUpperCase("uk-UA")}
                  </button>
                  {categories.map(({ slug, name }) => (
                    <button
                      type="button"
                      key={slug}
                      className={activeCategory === slug ? "is-active" : ""}
                      onClick={() => selectCategory(slug)}
                    >
                      {name.toLocaleUpperCase("uk-UA")}
                    </button>
                  ))}
                </nav>
                <div className="blog-filter-bar">
                  <select
                    className="blog-filter-select"
                    aria-label={text.chooseTopic}
                    value={activeCategory}
                    onChange={(event) => selectCategory(event.target.value)}
                  >
                    <option value="all">{text.chooseTopic}</option>
                    {categories.map(({ slug, name }) => (
                      <option key={slug} value={slug}>
                        {name}
                      </option>
                    ))}
                  </select>
                </div>
              </div>
            </div>
          </section>

          <section className="container blog-catalog" aria-live="polite" ref={catalogRef}>
            {featured ? (
              <>
                <BlogFeaturedPost post={featured} locale={locale} />
                <div className="blog-grid">
                  {visible.map((article) => (
                    <BlogPostCard key={article.slug} post={article} locale={locale} />
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
                <Pagination
                  className="blog-pagination"
                  page={currentPage}
                  pageCount={totalPages}
                  label={text.paginationLabel}
                  previousLabel={text.previousPage}
                  nextLabel={text.nextPage}
                  onPageChange={selectPage}
                />
              </>
            ) : (
              <p className="blog-empty">{t.emptyState}</p>
            )}
          </section>
        </div>

        <Breadcrumbs locale={locale} items={[{ label: text.breadcrumb }]} visible />
        <section className="blog-newsletter">
          <div className="container">
            <div className="blog-newsletter-copy">
              <span className="mono">{text.stay}</span>
              <h2>{text.newsletter}</h2>
              <p>{text.noSpam}</p>
            </div>
            <div className="blog-newsletter-aside">
              <form onSubmit={(event) => event.preventDefault()}>
                <label className="sr-only" htmlFor="blog-email">
                  Email
                </label>
                <input id="blog-email" type="email" placeholder={text.email} />
                <button type="submit">{text.subscribe}</button>
              </form>
              <p className="blog-newsletter-consent">
                {text.consent} <Link href="/privacy-policy">{text.privacyLink}</Link>
              </p>
              {socials.length ? (
                <>
                  <p className="blog-newsletter-follow">{text.follow}</p>
                  <nav className="social" aria-label={text.follow}>
                    {socials.map((social) => {
                      const Icon = socialIcons[social.network];
                      return (
                        <a
                          key={`${social.network}-${social.href}`}
                          href={social.href}
                          aria-label={social.name}
                          target="_blank"
                          rel="noreferrer"
                        >
                          {Icon ? <Icon /> : social.name}
                        </a>
                      );
                    })}
                  </nav>
                </>
              ) : null}
            </div>
          </div>
        </section>
      </main>
    </ContentImageLabel>
  );
}
