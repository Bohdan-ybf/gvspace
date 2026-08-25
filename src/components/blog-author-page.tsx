import Link from "next/link";
import { notFound } from "next/navigation";
import { ContactSection } from "./contact-section";
import { getDictionary, type Locale } from "@/i18n";
import { getBlogAuthor } from "./wordpress-authors";
import { getBlogPostsByAuthor } from "./wordpress-posts";

export async function BlogAuthorPage({ locale, slug }: { locale: Locale; slug: string }) {
  const author = await getBlogAuthor(slug, locale);
  if (!author) notFound();
  const wordpressPosts = await getBlogPostsByAuthor(slug, locale);
  const uk = locale === "uk";
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
          <span className="mono">● {uk ? "ПРО АВТОРА" : "ABOUT THE AUTHOR"}</span>
          <h2>{author.headline}</h2>
          <p>{author.bio}</p>
          <dl>
            <div>
              <dt>{author.experience}</dt>
              <dd>{uk ? "років досвіду" : "years of experience"}</dd>
            </div>
            <div>
              <dt>{author.projects}</dt>
              <dd>{uk ? "успішних проєктів" : "successful projects"}</dd>
            </div>
          </dl>
        </div>
        <aside>
          <h2>{uk ? "Маєте питання?" : "Have a question?"}</h2>
          <p>
            {uk
              ? "На безкоштовній особистій консультації розберемо вашу ситуацію та підберемо ефективне рішення."
              : "We will review your situation during a free personal consultation and find an effective solution."}
          </p>
          <Link href={`/${locale}/contacts`}>{uk ? "Запитати автора" : "Ask the author"} →</Link>
        </aside>
      </section>
      <section className="container author-posts">
        <h2>{uk ? "Всі статті автора" : "All articles by the author"}</h2>
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
              <Link href={`/${locale}/blog/${featured.slug}`}>
                {uk ? "Читати статтю" : "Read article"} →
              </Link>
            </div>
          </article>
        ) : (
          <p className="blog-empty">
            {uk
              ? "Цей автор ще не опублікував статей."
              : "This author has not published any articles yet."}
          </p>
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
                <Link href={`/${locale}/blog/${card.slug}`}>{uk ? "Читати" : "Read"} →</Link>
              </article>
            ))}
          </div>
        )}
      </section>
      <ContactSection text={getDictionary(locale).contact} />
    </main>
  );
}
