import Link from "next/link";
import { notFound } from "next/navigation";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";
import { getDynamicSeo } from "@/wordpress-seo";
import { Breadcrumbs } from "./breadcrumbs";
import { ContactSection } from "./contact-section";
import { StructuredData } from "./structured-data";
import { getBlogPost, getBlogPosts } from "./wordpress-posts";

function prepareArticleContent(content: string) {
  const headings: Array<{ id: string; label: string }> = [];
  const withHeadings = content.replace(
    /<h2([^>]*)>([\s\S]*?)<\/h2>/gi,
    (heading, attributes: string, inner: string) => {
      const label = inner
        .replace(/<[^>]*>/g, " ")
        .replace(/\s+/g, " ")
        .trim();
      const existingId = attributes.match(/\sid=["']([^"']+)["']/i)?.[1];
      const id = existingId || `article-section-${headings.length + 1}`;
      headings.push({ id, label });
      return existingId ? heading : `<h2${attributes} id="${id}">${inner}</h2>`;
    },
  );
  const leadMatch = withHeadings.match(/^\s*(<p(?:\s[^>]*)?>[\s\S]*?<\/p>)/i);
  const lead = leadMatch?.[1] ?? "";
  const body = leadMatch ? withHeadings.slice(leadMatch[0].length) : withHeadings;
  return { lead, body, headings };
}

const cardImage = (image?: string) => image || "/images/blog/blog-bg.png";

export async function BlogArticleDesignPage({ locale, slug }: { locale: Locale; slug: string }) {
  const t = getTranslations("blog", locale).article;
  const post = await getBlogPost(slug, locale);
  if (!post) notFound();
  const [seo, allPosts] = await Promise.all([
    getDynamicSeo("blog", slug, locale),
    getBlogPosts(locale),
  ]);
  const { lead, body, headings } = prepareArticleContent(post.content);
  const related = allPosts.filter((item) => item.slug !== post.slug).slice(0, 3);
  const text = t.labels;
  const articleSchema = {
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    headline: seo?.h1 || post.title,
    description: seo?.description || post.excerpt,
    datePublished: seo?.datePublished,
    dateModified: seo?.dateModified,
    image: seo?.openGraphImage || post.image,
    author: { "@type": "Person", name: post.author.name },
    publisher: { "@type": "Organization", name: "GVSPACE" },
  };

  return (
    <main className="article-page article-design-page">
      <StructuredData data={articleSchema} />
      <header className="article-hero">
        <div className="container">
          <span className="mono">{post.category}</span>
          <small className="mono">
            {post.publishedAt} · {post.readingTime} {text.read}
          </small>
          <h1>{seo?.h1 || post.title}</h1>
          <p>{post.excerpt}</p>
          <div className="article-author">
            <i
              style={
                post.author.avatar ? { backgroundImage: `url(${post.author.avatar})` } : undefined
              }
            />
            <div>
              <b>{post.author.name}</b>
              <small>{post.author.role}</small>
            </div>
          </div>
        </div>
      </header>

      <div className="container article-design-layout">
        <aside className="article-sidebar">
          {headings.length > 0 && (
            <nav className="article-toc" aria-label={text.contents}>
              <span className="mono">{text.contents}</span>
              {headings.map((heading) => (
                <a key={heading.id} href={`#${heading.id}`}>
                  {heading.label}
                </a>
              ))}
            </nav>
          )}
          <section className="article-project-card">
            <h2>{t.projectTitle}</h2>
            <p>{t.projectDescription}</p>
            <Link href={`/${locale}/contacts`}>{t.projectCta}</Link>
          </section>
        </aside>

        <div className="article-main">
          {lead && <div className="article-lead" dangerouslySetInnerHTML={{ __html: lead }} />}
          <div
            className={`article-cover${post.image ? "" : " is-placeholder"}`}
            style={{ backgroundImage: `url(${cardImage(post.image)})` }}
            role="img"
            aria-label={post.title}
          />
          <article className="article-content" dangerouslySetInnerHTML={{ __html: body }} />

          <div className="article-meta">
            <div>
              {(post.tags.length ? post.tags : [post.category]).map((tag) => (
                <span key={tag}>{tag}</span>
              ))}
            </div>
            <button type="button">↗ {text.share}</button>
          </div>

          <section className="article-author-card">
            <div className="article-author">
              <i
                style={
                  post.author.avatar ? { backgroundImage: `url(${post.author.avatar})` } : undefined
                }
              />
              <div>
                <b>{post.author.name}</b>
                <small>{post.author.role}</small>
                <p>{t.authorBio}</p>
              </div>
            </div>
            <Link href={`/${locale}/blog/author/${post.author.slug}`}>{text.author} →</Link>
          </section>

          <section className="article-rating" aria-label={t.ratingLabel}>
            <span>{t.ratingLabel}</span>
            <div aria-label="5 out of 5">★★★★★</div>
            <span>
              {t.ratingScore}: <strong>5.0</strong>
            </span>
          </section>

          <section className="article-comments">
            <h2>{t.commentsTitle}</h2>
            <p>{t.commentsEmpty}</p>
            <form>
              <div>
                <input aria-label={t.commentName} placeholder={t.commentName} />
                <input type="email" aria-label={t.commentEmail} placeholder={t.commentEmail} />
              </div>
              <textarea aria-label={t.commentText} placeholder={t.commentText} />
              <div>
                <button type="button">{t.commentSubmit}</button>
                <small>{t.commentConsent}</small>
              </div>
            </form>
          </section>
        </div>
      </div>

      {related.length > 0 && (
        <section className="container article-related">
          <span className="mono">{text.related}</span>
          <div>
            {related.map((item) => (
              <article key={item.slug}>
                <Link
                  className="article-related-image"
                  href={`/${locale}/blog/${item.slug}`}
                  style={{ backgroundImage: `url(${cardImage(item.image)})` }}
                  aria-label={item.title}
                >
                  <b>{item.category}</b>
                </Link>
                <small className="mono">
                  {item.publishedAt} · {item.readingTime} {text.read}
                </small>
                <h3>
                  <Link href={`/${locale}/blog/${item.slug}`}>{item.title}</Link>
                </h3>
                <small>
                  {t.authorLabel}: {item.authorName}
                </small>
              </article>
            ))}
          </div>
        </section>
      )}

      <Breadcrumbs
        locale={locale}
        items={[{ label: t.blogBreadcrumb, pathname: "/blog" }, { label: seo?.h1 || post.title }]}
        visible
      />
      <ContactSection text={getTranslations("global", locale).contact} />
    </main>
  );
}
