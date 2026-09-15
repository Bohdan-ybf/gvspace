import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import type { Messages } from "@/i18n/uk";
import { ArrowRight } from "./icons/arrow-right";
import { ServiceVectors } from "./service-vectors";
import { CasesSection } from "./cases-section";
import { ContactSection } from "./contact-section";
import { TechnologySection } from "./technology-section";
import { getBlogPosts, type BlogPostSummary } from "./wordpress-posts";
import { ReviewsSection } from "./reviews-section";
import { getServiceOfferings } from "./wordpress-services";
import { PartnersSlider } from "./partners-slider";
import { getPartners } from "./wordpress-partners";
import { getHomeFaqs, type FaqItem } from "./wordpress-faqs";
import { getHomeSeoText } from "./wordpress-home-seo-text";

import { getTranslations } from "@/i18n/pages";
const problemIcons = ["no-clarity", "no-system", "no-scale"] as const;
const approachIcons = ["clarity", "system", "scale"] as const;

export async function Home({ locale }: { locale: Locale }) {
  const text = getTranslations("global", locale);
  const [blogPostsResult, services, partners, faqs, homeSeoText] = await Promise.all([
    getBlogPosts(locale),
    getServiceOfferings(locale),
    getPartners(locale),
    getHomeFaqs(locale),
    getHomeSeoText(locale),
  ]);
  const blogPosts = blogPostsResult.slice(0, 4);

  return (
    <>
      <section className="hero">
        <Image
          src="/images/hero/hero-main.jpg"
          alt=""
          fill
          priority
          quality={90}
          sizes="100vw"
          className="hero-img"
        />
        <div className="hero-copy">
          <span className="tag mono">{text.hero.eyebrow}</span>
          <h1>
            {text.hero.title}
            <br /> {text.hero.titleSecond}
          </h1>
        </div>
        <p className="hero-note">{text.hero.description}</p>
        <Link href={`/${locale}/contacts`} className="hero-cta btn btn-primary">
          <span>{text.common.buildSystem}</span>
          <ArrowRight />
        </Link>
      </section>
      <main>
        <Problems text={text} />
        <Approach text={text} locale={locale} />
        <ServiceVectors
          text={text.vectors}
          clarity={text.clarity}
          locale={locale}
          services={services}
        />
        <MobileClarity text={text} locale={locale} />
        <TechnologySection locale={locale} title={text.technology.title} />
        <CasesSection text={text.cases} locale={locale} />
        <People text={text} locale={locale} />
        <PartnersSlider
          title={text.partners.title}
          description={text.partners.description}
          partners={partners}
        />
        <ReviewsSection locale={locale} />
        <Blog text={text} locale={locale} posts={blogPosts} />
        <Faq text={text} locale={locale} items={faqs} />
        <section className="mission container">
          <b>{homeSeoText?.title ?? text.mission.statement}</b>
          <p>{homeSeoText?.content ?? text.mission.description}</p>
        </section>
        <ContactSection text={text.contact} />
      </main>
    </>
  );
}

function Problems({ text }: { text: Messages }) {
  return (
    <section className="section container intro">
      <div>
        <h2>{text.problems.title}</h2>
        <p className="muted">{text.problems.intro}</p>
      </div>
      <div className="cards">
        {text.problems.items.map((item, index) => (
          <article className="card" key={item[0]}>
            <div className="card-visual">
              <Image
                src={`/images/home/icons/${problemIcons[index]}.png`}
                alt=""
                width={96}
                height={96}
              />
              <span className="mono">[ 0{index + 1} ]</span>
            </div>
            <h3>{item[0]}</h3>
            <p className="muted">{item[1]}</p>
          </article>
        ))}
      </div>
    </section>
  );
}

function Approach({ text, locale }: { text: Messages; locale: Locale }) {
  return (
    <section className="section container approach-section">
      <h2>{text.approach.title}</h2>
      <p className="muted lead">{text.approach.intro}</p>
      <div className="values">
        {text.approach.items.map((item, index) => (
          <article key={item[0]}>
            <div className="card-visual">
              <Image
                src={`/images/home/icons/${approachIcons[index]}.png`}
                alt=""
                width={96}
                height={96}
              />
              <span className="mono">[ {item[0]} ]</span>
            </div>
            <h3>{item[1]}</h3>
            <p className="muted">{text.approach.description}</p>
          </article>
        ))}
      </div>
      <div className="center">
        <Link className="btn" href={`/${locale}/about`}>
          <span>{text.approach.more}</span>
          <ArrowRight />
        </Link>
      </div>
    </section>
  );
}

function MobileClarity({ text, locale }: { text: Messages; locale: Locale }) {
  return (
    <section className="mobile-clarity container">
      <h2>{text.clarity.title}</h2>
      <p>{text.clarity.description}</p>
      <Link className="btn" href={`/${locale}/contacts`}>
        <span>{text.clarity.action}</span>
        <ArrowRight />
      </Link>
    </section>
  );
}

function People({ text, locale }: { text: Messages; locale: Locale }) {
  return (
    <section className="section container people">
      <div className="people-profile">
        <h2>{text.people.title}</h2>
        <div className="people-profile-grid">
          <div className="people-founder">
            <div className="people-founder-photo" role="img" aria-label={text.people.name} />
            <Link className="btn btn-primary people-team-link" href={`/${locale}/team`}>
              <span>{text.people.teamAction}</span>
              <ArrowRight />
            </Link>
          </div>
          <div className="people-founder-copy">
            <blockquote>{text.people.quote}</blockquote>
            <p className="mono">{text.people.position}</p>
            <strong>{text.people.name}</strong>
          </div>
        </div>
      </div>
      <div className="stats">
        <div className="stats-main">
          <strong>$10M+</strong>
          <b>{text.people.capitalization}</b>
        </div>
        <div>
          <strong>50+</strong>
          <b>{text.people.ecosystems}</b>
        </div>
        <div>
          <strong>{text.people.days}</strong>
          <b>{text.people.results}</b>
        </div>
        <div className="stats-experts">
          <strong>32</strong>
          <b>{text.people.experts}</b>
        </div>
      </div>
    </section>
  );
}

function Blog({
  text,
  locale,
  posts,
}: {
  text: Messages;
  locale: Locale;
  posts: BlogPostSummary[];
}) {
  const t = getTranslations("home", locale).blog;
  if (!posts.length) return null;

  return (
    <section className="section container home-blog-section">
      <header className="home-blog-header">
        <h2>{text.blog.title}</h2>
        <Link className="btn btn-primary home-blog-more" href={`/${locale}/blog`}>
          {t.allArticles}
          <ArrowRight />
        </Link>
      </header>
      <div className={`home-blog-grid${posts.length === 1 ? " is-single" : ""}`}>
        {posts.map((post, index) => (
          <article className={`home-blog-card${index === 0 ? " is-featured" : ""}`} key={post.slug}>
            <Link
              aria-label={post.title}
              className="home-blog-image"
              href={`/${locale}/blog/${post.slug}`}
              style={
                post.image
                  ? {
                      backgroundImage: `url(${post.image})`,
                      backgroundPosition: "center",
                      backgroundSize: "cover",
                    }
                  : undefined
              }
            />
            <div className="home-blog-body">
              <div className="home-blog-meta mono">
                <span className="home-blog-category">{post.category}</span>
                <span>{post.publishedAt}</span>
                {index === 0 && (
                  <>
                    <span aria-hidden="true">•</span>
                    <span>
                      {post.readingTime} {t.minutesLabel}
                    </span>
                  </>
                )}
              </div>
              <h3>
                <Link href={`/${locale}/blog/${post.slug}`}>{post.title}</Link>
              </h3>
              <small className="home-blog-card-author mono">
                {t.cardAuthorLabel}: {post.authorName}
              </small>
            </div>
          </article>
        ))}
      </div>
    </section>
  );
}

function Faq({ text, locale, items }: { text: Messages; locale: Locale; items: FaqItem[] }) {
  return (
    <section className="section container faq">
      <div className="faq-intro">
        <h2>{text.faq.title}</h2>
        <p>{text.faq.subtitle}</p>
        <Link className="btn btn-primary" href={`/${locale}/contacts`}>
          {text.faq.action}
        </Link>
      </div>
      <div className="faq-list">
        {items.map((item, index) => (
          <details key={item.id} open={index === 1}>
            <summary>
              {item.question}
              <span>+</span>
            </summary>
            <p>{item.answer}</p>
          </details>
        ))}
      </div>
    </section>
  );
}
