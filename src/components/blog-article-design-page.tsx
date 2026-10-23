import Link from "next/link";
import { notFound } from "next/navigation";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";
import { getDynamicSeo } from "@/wordpress-seo";
import { Breadcrumbs } from "./breadcrumbs";
import { LeadModalButton } from "./lead-modal";
import { ContactSection } from "./contact-section";
import { StructuredData } from "./structured-data";
import { stripOpenGraphLabel } from "./blog-open-graph";
import { getBlogPost, getBlogPosts } from "./wordpress-posts";
import { ArticleCommentForm } from "./article-comment-form";
import { ArticleToc } from "./article-toc";
import { BlogPostCard } from "./blog-post-cards";
import { ArticleShareButton } from "./article-share-button";
import { ContentImageLabel, ContentPhotoImg } from "./content-image-label";
import { contentImageLabel, stampContentImages } from "@/content-images";

function prepareArticleContent(content: string, imageLabel: string) {
  const headings: Array<{ id: string; label: string; level: 2 | 3 }> = [];
  const withHeadings = stampContentImages(content, imageLabel).replace(
    /<h([23])([^>]*)>([\s\S]*?)<\/h\1>/gi,
    (heading, levelValue: string, attributes: string, inner: string) => {
      if (levelValue === "3") return heading;

      const label = inner
        .replace(/<[^>]*>/g, " ")
        .replace(/\s+/g, " ")
        .trim();
      const existingId = attributes.match(/\sid=["']([^"']+)["']/i)?.[1];
      const id = existingId || `article-section-${headings.length + 1}`;
      headings.push({ id, label, level: 2 });
      return existingId ? heading : `<h2${attributes} id="${id}">${inner}</h2>`;
    },
  );
  const leadMatch = withHeadings.match(/^\s*(<p(?:\s[^>]*)?>[\s\S]*?<\/p>)/i);
  const lead = leadMatch?.[1] ?? "";
  const body = leadMatch ? withHeadings.slice(leadMatch[0].length) : withHeadings;
  return { lead, body, headings };
}

const cardImage = (image?: string) => image || "/images/blog/blog.webp";

export async function BlogArticleDesignPage({ locale, slug }: { locale: Locale; slug: string }) {
  const t = getTranslations("blog", locale).article;
  const content = getTranslations("blog", locale).content;
  const post = await getBlogPost(slug, locale);
  if (!post) notFound();
  const [seo, allPosts] = await Promise.all([
    getDynamicSeo("blog", slug, locale),
    getBlogPosts(locale),
  ]);
  const heading = contentImageLabel(stripOpenGraphLabel(seo?.h1 || post.title));
  const { lead, body, headings } = prepareArticleContent(post.content, heading);
  const related = allPosts.filter((item) => item.slug !== post.slug).slice(0, 3);
  const text = t.labels;
  const articleSchema = {
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    headline: stripOpenGraphLabel(seo?.h1 || post.title),
    description: stripOpenGraphLabel(seo?.description || post.excerpt),
    datePublished: seo?.datePublished,
    dateModified: seo?.dateModified,
    image: seo?.openGraphImage || post.image,
    ...(post.author ? { author: { "@type": "Person", name: post.author.name } } : {}),
    publisher: { "@type": "Organization", name: "GVSPACE" },
  };

  return (
    <ContentImageLabel label={heading}>
      <main className="article-page article-design-page">
        <StructuredData data={articleSchema} />
        <header className="article-hero">
          <div className="container">
            <span className="mono">{post.category}</span>
            <small className="mono">
              {post.publishedAt} · {post.readingTime} {text.read}
            </small>
            <h1>{heading}</h1>
            <p>{stripOpenGraphLabel(post.excerpt)}</p>
            {post.author ? (
              <div className="article-author">
                <i>{post.author.avatar ? <ContentPhotoImg src={post.author.avatar} /> : null}</i>
                <div>
                  <b>{post.author.name}</b>
                  {post.author.role ? <small>{post.author.role}</small> : null}
                </div>
              </div>
            ) : null}
          </div>
        </header>

        <div className="container article-design-layout">
          <aside className="article-sidebar">
            {headings.length > 0 && <ArticleToc label={text.contents} headings={headings} />}
            <section className="article-project-card">
              <h2>{t.projectTitle}</h2>
              <p>{t.projectDescription}</p>
              <LeadModalButton>{t.projectCta}</LeadModalButton>
            </section>
          </aside>

          <div className="article-main">
            {lead && <div className="article-lead" dangerouslySetInnerHTML={{ __html: lead }} />}
            {post.image ? (
              <div className="article-cover">
                <ContentPhotoImg src={cardImage(post.image)} />
              </div>
            ) : null}
            <article className="article-content" dangerouslySetInnerHTML={{ __html: body }} />

            <div className="article-meta">
              <div>
                {(post.tags.length ? post.tags : [post.category]).map((tag) => (
                  <span key={tag}>{tag}</span>
                ))}
              </div>
              <ArticleShareButton
                label={text.share}
                copyLabel={text.copyLink}
                copiedLabel={text.copied}
                title={heading}
              />
            </div>

            {post.author ? (
              <section className="article-author-card">
                <div className="article-author">
                  <i>{post.author.avatar ? <ContentPhotoImg src={post.author.avatar} /> : null}</i>
                  <div>
                    <b>{post.author.name}</b>
                    {post.author.role ? <small>{post.author.role}</small> : null}
                    <p>{post.author.cardBio || t.authorBio}</p>
                  </div>
                </div>
                {post.author.slug ? (
                  <Link href={`/blog/author/${post.author.slug}`}>{text.author} →</Link>
                ) : null}
              </section>
            ) : null}

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
              <ArticleCommentForm
                locale={locale}
                articleTitle={heading}
                name={t.commentName}
                email={t.commentEmail}
                text={t.commentText}
                submitLabel={t.commentSubmit}
                consent={t.commentConsent}
                privacyLink={content.privacyLink}
              />
            </section>
          </div>
        </div>

        {related.length > 0 && (
          <section className="container article-related">
            <span className="mono">{text.related}</span>
            <div className="blog-grid">
              {related.map((item) => (
                <BlogPostCard key={item.slug} post={item} locale={locale} />
              ))}
            </div>
          </section>
        )}

        <Breadcrumbs
          locale={locale}
          items={[
            { label: t.blogBreadcrumb, pathname: "/blog" },
            { label: stripOpenGraphLabel(seo?.h1 || post.title) },
          ]}
          visible
        />
        <ContactSection locale={locale} text={getTranslations("global", locale).contact} />
      </main>
    </ContentImageLabel>
  );
}
