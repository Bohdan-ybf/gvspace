import Link from "next/link";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";
import type { BlogPostSummary } from "./wordpress-posts";

const imageUrl = (image?: string) =>
  image ? (image.startsWith("http") ? image : `/images/blog/${image}`) : "/images/blog/blog.webp";

export function BlogFeaturedPost({ post, locale }: { post: BlogPostSummary; locale: Locale }) {
  const text = getTranslations("blog", locale).content;
  return (
    <article className="blog-featured">
      <Link
        className={`blog-card-image${post.image ? "" : " is-placeholder"}`}
        href={`/blog/${post.slug}`}
        style={{ backgroundImage: `url(${imageUrl(post.image)})` }}
        aria-label={post.title}
      >
        <span>{post.category}</span>
      </Link>
      <div className="blog-featured-copy">
        <div className="blog-meta mono">
          <small>
            {post.publishedAt} · {post.readingTime} {text.minutesShort}
          </small>
          <small>
            {text.author}: {post.authorName}
          </small>
        </div>
        <h2>
          <Link href={`/blog/${post.slug}`}>{post.title}</Link>
        </h2>
        {post.excerpt ? <p>{post.excerpt}</p> : null}
      </div>
    </article>
  );
}

export function BlogPostCard({ post, locale }: { post: BlogPostSummary; locale: Locale }) {
  const text = getTranslations("blog", locale).content;
  return (
    <article className="blog-card">
      <Link
        className={`blog-card-image${post.image ? "" : " is-placeholder"}`}
        href={`/blog/${post.slug}`}
        style={{ backgroundImage: `url(${imageUrl(post.image)})` }}
        aria-label={post.title}
      />
      <div className="blog-card-meta mono">
        <span>{post.category}</span>
        <small>
          {post.publishedAt} · {post.readingTime} {text.minutesShort}
        </small>
      </div>
      <h3>
        <Link href={`/blog/${post.slug}`}>{post.title}</Link>
      </h3>
      <small className="blog-card-author">
        {text.author}: {post.authorName}
      </small>
    </article>
  );
}
