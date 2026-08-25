import Link from "next/link";
import { notFound } from "next/navigation";
import type { Locale } from "@/i18n";
import { getDictionary } from "@/i18n";
import { ContactSection } from "./contact-section";
import { getBlogPost, getBlogPosts } from "./wordpress-posts";

function prepareArticleContent(content: string) {
  const headings: Array<{ id: string; label: string }> = [];
  const html = content.replace(
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
  return { html, headings };
}

export async function BlogArticlePage({ locale, slug }: { locale: Locale; slug: string }) {
  const post = await getBlogPost(slug, locale);
  if (!post) notFound();
  const { html, headings } = prepareArticleContent(post.content);
  const related = (await getBlogPosts(locale))
    .filter((item) => item.slug !== post.slug)
    .slice(0, 3);
  const text =
    locale === "uk"
      ? {
          contents: "ЗМІСТ",
          read: "хв читати",
          share: "Поділитись",
          author: "На сторінку автора",
          related: "ЧИТАЙТЕ ТАКОЖ",
        }
      : {
          contents: "CONTENTS",
          read: "min read",
          share: "Share",
          author: "Author page",
          related: "READ ALSO",
        };

  return (
    <main className="article-page">
      <header className="article-hero">
        <div className="container">
          <span className="mono">{post.category}</span>
          <small className="mono">
            {post.publishedAt} · {post.readingTime} {text.read}
          </small>
          <h1>{post.title}</h1>
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
      <div className={`container article-layout${headings.length ? "" : " without-toc"}`}>
        {headings.length > 0 && (
          <aside className="article-toc">
            <span className="mono">{text.contents}</span>
            {headings.map((heading) => (
              <a key={heading.id} href={`#${heading.id}`}>
                {heading.label}
              </a>
            ))}
          </aside>
        )}
        <article className="article-content" dangerouslySetInnerHTML={{ __html: html }} />
        <div className="article-meta">
          <div>
            {post.tags.map((tag) => (
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
              <p>
                Засновник GVSPACE. 8+ років у digital-маркетингу та побудові систем зростання для
                бізнесів.
              </p>
            </div>
          </div>
          <Link href={`/${locale}/blog/author/${post.author.slug}`}>{text.author} →</Link>
        </section>
      </div>
      {related.length > 0 && (
        <section className="container article-related">
          <span className="mono">{text.related}</span>
          <div>
            {related.map((item) => (
              <article key={item.slug}>
                <div
                  style={
                    item.image
                      ? {
                          backgroundImage: `url(${item.image})`,
                          backgroundSize: "cover",
                          backgroundPosition: "center",
                        }
                      : undefined
                  }
                >
                  <b>{item.category}</b>
                </div>
                <small className="mono">
                  {item.publishedAt} · {item.readingTime} хв
                </small>
                <h3>{item.title}</h3>
                <Link href={`/${locale}/blog/${item.slug}`}>
                  {locale === "uk" ? "Читати" : "Read"} →
                </Link>
              </article>
            ))}
          </div>
        </section>
      )}
      <ContactSection text={getDictionary(locale).contact} />
    </main>
  );
}
