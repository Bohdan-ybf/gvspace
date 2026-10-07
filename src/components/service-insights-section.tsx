import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { getBlogPosts } from "./wordpress-posts";

export async function ServiceInsightsSection({ locale }: { locale: Locale }) {
  const posts = (await getBlogPosts(locale)).slice(0, 3);
  if (!posts.length) return null;

  const copy =
    locale === "uk"
      ? {
          eyebrow: "ЧИТАЙТЕ ТАКОЖ",
          title: "Інсайти",
          all: "Усі статті",
          author: "Автор",
          minutes: "хв",
        }
      : {
          eyebrow: "READ ALSO",
          title: "Insights",
          all: "All articles",
          author: "Author",
          minutes: "min",
        };

  return (
    <section className="section container service-insights">
      <header>
        <div>
          <span className="mono">{copy.eyebrow}</span>
          <h2>{copy.title}</h2>
        </div>
        <Link className="btn btn-primary" href={`/${locale}/blog`}>
          {copy.all}
          <ArrowRight />
        </Link>
      </header>
      <div>
        {posts.map((post) => (
          <article key={post.slug}>
            <Link
              aria-label={post.title}
              className="service-insights-image"
              href={`/${locale}/blog/${post.slug}`}
              style={post.image ? { backgroundImage: `url(${post.image})` } : undefined}
            />
            <div className="service-insights-copy">
              <div className="service-insights-meta">
                <span className="service-insights-category">{post.category}</span>
                <small>
                  {post.publishedAt} · {post.readingTime} {copy.minutes}
                </small>
              </div>
              <h3>
                <Link href={`/${locale}/blog/${post.slug}`}>{post.title}</Link>
              </h3>
              <p>
                {copy.author}: {post.authorName}
              </p>
            </div>
          </article>
        ))}
      </div>
    </section>
  );
}
