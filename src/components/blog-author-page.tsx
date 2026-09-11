import Link from "next/link";
import { notFound } from "next/navigation";
import { ContactSection } from "./contact-section";
import type { Locale } from "@/i18n";
import { getBlogAuthor } from "./wordpress-authors";
import { getBlogPostsByAuthor } from "./wordpress-posts";

import { getTranslations } from "@/i18n/pages";
import { StructuredData } from "./structured-data";
export async function BlogAuthorPage({ locale, slug }: { locale: Locale; slug: string }) {
  const author = await getBlogAuthor(slug, locale);
  if (!author) notFound();
  const wordpressPosts = await getBlogPostsByAuthor(slug, locale);
  const t = getTranslations("blog", locale).author;
  const featured = wordpressPosts[0];

  return (
    <>
      <StructuredData
        data={{
          "@context": "https://schema.org",
          "@type": "ProfilePage",
          mainEntity: {
            "@type": "Person",
            name: author.name,
            jobTitle: author.role,
            description: author.bio,
            image: author.photo,
            worksFor: { "@type": "Organization", name: "GVSPACE" },
          },
        }}
      />
      <main className="author-page">
      <section className="container author-profile">
        <header>
          <h1>{author.name}</h1>
          <p className="mono">{author.role}</p>
        </header>
        <div
          className="author-photo"
          style={author.photo ? { backgroundImage: `url(${author.photo})` } : undefined}
          role="img"
          aria-label={author.name}
        />
        <div className="author-bio">
          <span className="mono">● {t.aboutLabel}</span>
          <h2>{author.headline}</h2>
          <p>{author.bio}</p>
          <dl>
            <div>
              <dt>{author.experience}</dt>
              <dd>{t.experienceLabel}</dd>
            </div>
            <div>
              <dt>{author.projects}</dt>
              <dd>{t.projectsLabel}</dd>
            </div>
          </dl>
        </div>
        <aside>
          <h2>{t.questionTitle}</h2>
          <p>{t.questionDescription}</p>
          <Link href={`/${locale}/contacts`}>{t.askAuthor} →</Link>
        </aside>
      </section>
      <section className="container author-posts">
        <h2>{t.articlesTitle}</h2>
        {featured ? (
          <article className="author-featured">
            <div
              className="author-post-image"
              style={
                featured.image
                  ? {
                      backgroundImage: `url(${featured.image})`,
                      backgroundSize: "cover",
                      backgroundPosition: "center",
                    }
                  : undefined
              }
            >
              <b>{featured.category}</b>
            </div>
            <div>
              <small className="mono">
                {featured.publishedAt} · {featured.readingTime} хв
              </small>
              <h3>{featured.title}</h3>
              <p>{featured.excerpt}</p>
              <Link href={`/${locale}/blog/${featured.slug}`}>{t.readArticle} →</Link>
            </div>
          </article>
        ) : (
          <p className="blog-empty">{t.emptyState}</p>
        )}
        {wordpressPosts.length > 1 && (
          <div className="author-post-grid">
            {wordpressPosts.slice(1).map((card) => (
              <article key={card.slug}>
                <div
                  className="author-post-image"
                  style={
                    card.image
                      ? {
                          backgroundImage: `url(${card.image})`,
                          backgroundSize: "cover",
                          backgroundPosition: "center",
                        }
                      : undefined
                  }
                >
                  <b>{card.category}</b>
                </div>
                <small className="mono">
                  {card.publishedAt} · {card.readingTime} хв
                </small>
                <h3>{card.title}</h3>
                <Link href={`/${locale}/blog/${card.slug}`}>{t.readMore} →</Link>
              </article>
            ))}
          </div>
        )}
      </section>
      <ContactSection text={getTranslations("global", locale).contact} />
      </main>
    </>
  );
}
