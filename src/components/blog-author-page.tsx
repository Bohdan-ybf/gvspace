import { notFound, permanentRedirect } from "next/navigation";
import { ContactSection } from "./contact-section";
import type { Locale } from "@/i18n";
import { getBlogAuthor } from "./wordpress-authors";
import { getBlogPostsByAuthor } from "./wordpress-posts";

import { getTranslations } from "@/i18n/pages";
import { BlogFeaturedPost, BlogPostCard } from "./blog-post-cards";
import { toLatinSlug } from "./latin-slug";
import { Breadcrumbs } from "./breadcrumbs";
import { InstagramIcon, LinkedinIcon } from "./icons/social-icons";
import { LeadModalButton } from "./lead-modal";
import { StructuredData } from "./structured-data";
import { ContentImageLabel, ContentPhotoImg } from "./content-image-label";
import { contentImageLabel } from "@/content-images";
export async function BlogAuthorPage({ locale, slug }: { locale: Locale; slug: string }) {
  const latinSlug = toLatinSlug(slug);
  if (latinSlug && latinSlug !== slug) permanentRedirect(`/${locale}/blog/author/${latinSlug}`);
  const author = await getBlogAuthor(latinSlug || slug, locale);
  if (!author) notFound();
  const wordpressPosts = await getBlogPostsByAuthor(slug, locale);
  const copy = getTranslations("blog", locale);
  const t = copy.author;
  const featured = wordpressPosts[0];
  const bio = author.bio
    .split(/\n+/)
    .map((paragraph) => paragraph.trim())
    .filter(Boolean);
  const sameAs = [author.linkedinHref, author.instagramHref].filter((href): href is string =>
    Boolean(href),
  );

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
            ...(sameAs.length ? { sameAs } : {}),
          },
        }}
      />
      <ContentImageLabel label={contentImageLabel(author.name)}>
        <main className="author-page">
          <section className="container author-profile">
            <header>
              <div className="author-heading">
                <h1>{author.name}</h1>
                {author.role ? <p>{author.role}</p> : null}
              </div>
            </header>
            <div className="author-photo">
              {author.photo ? <ContentPhotoImg src={author.photo} /> : null}
            </div>
            {author.linkedinHref || author.instagramHref ? (
              <div className="author-socials">
                {author.linkedinHref ? (
                  <a href={author.linkedinHref} target="_blank" rel="noreferrer">
                    <span className="author-social-linkedin">
                      <LinkedinIcon />
                    </span>
                    {author.linkedinLabel}
                  </a>
                ) : null}
                {author.instagramHref ? (
                  <a href={author.instagramHref} target="_blank" rel="noreferrer">
                    <span className="author-social-instagram">
                      <InstagramIcon />
                    </span>
                    {author.instagramLabel}
                  </a>
                ) : null}
              </div>
            ) : null}
            <div className="author-bio">
              <span className="mono">● {t.aboutLabel}</span>
              {author.headline ? <h2>{author.headline}</h2> : null}
              {author.quote ? (
                <blockquote className="author-quote">
                  <svg width="22" height="18" viewBox="0 0 22 18" aria-hidden="true">
                    <path
                      d="M8.456 0L5.432 8.848H9.128V17.472H0V9.8L3.92 0H8.456ZM20.552 0L17.528 8.848H21.168V17.472H12.04V9.8L15.96 0H20.552Z"
                      fill="#E2DFF0"
                    />
                  </svg>
                  <p>{author.quote}</p>
                  <svg width="22" height="18" viewBox="0 0 22 18" aria-hidden="true">
                    <path
                      d="M12.712 17.416L15.736 8.568H12.096V0H21.224V7.672L17.304 17.416H12.712ZM0.616 17.416L3.64 8.568H0V0H9.128V7.672L5.208 17.416H0.616Z"
                      fill="#E2DFF0"
                    />
                  </svg>
                </blockquote>
              ) : null}
              {bio.length > 0 ? (
                <div className="author-bio-copy">
                  {bio.map((paragraph) => (
                    <p key={paragraph}>{paragraph}</p>
                  ))}
                </div>
              ) : null}
              {author.experience || author.projects ? (
                <dl>
                  {author.experience ? (
                    <div>
                      <dt>{author.experience}</dt>
                      <dd>{t.experienceLabel}</dd>
                    </div>
                  ) : null}
                  {author.projects ? (
                    <div>
                      <dt>{author.projects}</dt>
                      <dd>{t.projectsLabel}</dd>
                    </div>
                  ) : null}
                </dl>
              ) : null}
            </div>
            <aside>
              <h2>{t.questionTitle}</h2>
              <p>{t.questionDescription}</p>
              <LeadModalButton variant="question">
                {t.askAuthor} <span aria-hidden="true">→</span>
              </LeadModalButton>
            </aside>
          </section>
          <section className="container author-posts">
            <h2>{t.articlesTitle}</h2>
            {featured ? (
              <>
                <BlogFeaturedPost post={featured} locale={locale} />
                {wordpressPosts.length > 1 ? (
                  <div className="blog-grid">
                    {wordpressPosts.slice(1).map((post) => (
                      <BlogPostCard key={post.slug} post={post} locale={locale} />
                    ))}
                  </div>
                ) : null}
              </>
            ) : (
              <p className="blog-empty">{t.emptyState}</p>
            )}
          </section>
          <Breadcrumbs
            locale={locale}
            items={[{ label: copy.content.breadcrumb, pathname: "/blog" }, { label: author.name }]}
            visible
          />
          <ContactSection locale={locale} text={getTranslations("global", locale).contact} />
        </main>
      </ContentImageLabel>
    </>
  );
}
