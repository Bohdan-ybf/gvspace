import Link from "next/link";
import { notFound } from "next/navigation";
import { ContactSection } from "./contact-section";
import { getDictionary, type Locale } from "@/i18n";
import { getBlogAuthor } from "./wordpress-authors";
import { getBlogPostsByAuthor } from "./wordpress-posts";

import { componentCopy } from "@/i18n/component-copy";
export async function BlogAuthorPage({ locale, slug }: { locale: Locale; slug: string }) {
  const author = await getBlogAuthor(slug, locale);
  if (!author) notFound();
  const wordpressPosts = await getBlogPostsByAuthor(slug, locale);
  const copy = componentCopy[locale]["blog-author-page"];
  const featured = wordpressPosts[0];

  return (
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
          <span className="mono">● {copy.copy1}</span>
          <h2>{author.headline}</h2>
          <p>{author.bio}</p>
          <dl>
            <div>
              <dt>{author.experience}</dt>
              <dd>{copy.copy2}</dd>
            </div>
            <div>
              <dt>{author.projects}</dt>
              <dd>{copy.copy3}</dd>
            </div>
          </dl>
        </div>
        <aside>
          <h2>{copy.copy4}</h2>
          <p>{copy.copy5}</p>
          <Link href={`/${locale}/contacts`}>{copy.copy6} →</Link>
        </aside>
      </section>
      <section className="container author-posts">
        <h2>{copy.copy7}</h2>
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
              <Link href={`/${locale}/blog/${featured.slug}`}>{copy.copy8} →</Link>
            </div>
          </article>
        ) : (
          <p className="blog-empty">{copy.copy9}</p>
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
                <Link href={`/${locale}/blog/${card.slug}`}>{copy.copy10} →</Link>
              </article>
            ))}
          </div>
        )}
      </section>
      <ContactSection text={getDictionary(locale).contact} />
    </main>
  );
}
