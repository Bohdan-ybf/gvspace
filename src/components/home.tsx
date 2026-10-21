import Image from "next/image";
import Link from "next/link";
import { Suspense } from "react";
import type { Locale } from "@/i18n";
import type { Messages } from "@/i18n/uk";
import { ArrowRight } from "./icons/arrow-right";
import { LeadModalButton } from "./lead-modal";
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
import { MissionText } from "./mission-text";
import { getHomeSeoText } from "./wordpress-home-seo-text";

import { getTranslations } from "@/i18n/pages";
import { ContentImageLabel, ContentPhotoImg } from "./content-image-label";
import { contentImageLabel } from "@/content-images";
const problemIcons = ["no-clarity", "no-system", "no-scale"] as const;
const approachIcons = ["clarity", "system", "scale"] as const;

export function Home({ locale }: { locale: Locale }) {
  const text = getTranslations("global", locale);
  const imageLabel = contentImageLabel(text.hero.title, text.hero.titleSecond);

  return (
    <ContentImageLabel label={imageLabel}>
      <>
        <section className="hero">
          <Image
            src="/images/hero/hero-main.webp"
            alt=""
            fill
            priority
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
          <LeadModalButton className="hero-cta btn btn-primary">
            <span>{text.common.buildSystem}</span>
            <ArrowRight />
          </LeadModalButton>
        </section>
        <main>
          <Problems text={text} />
          <Approach text={text} />
          <Suspense fallback={null}>
            <HomeServices text={text} locale={locale} />
          </Suspense>
          <MobileClarity text={text} />
          <Suspense fallback={null}>
            <TechnologySection locale={locale} title={text.technology.title} />
          </Suspense>
          <Suspense fallback={null}>
            <CasesSection text={text.cases} locale={locale} />
          </Suspense>
          <People text={text} />
          <Suspense fallback={null}>
            <HomePartners text={text} locale={locale} />
          </Suspense>
          <Suspense fallback={null}>
            <ReviewsSection locale={locale} />
          </Suspense>
          <Suspense fallback={null}>
            <HomeBlog text={text} locale={locale} />
          </Suspense>
          <Suspense fallback={null}>
            <HomeFaq text={text} locale={locale} />
          </Suspense>
          <Suspense fallback={null}>
            <HomeMission text={text} locale={locale} />
          </Suspense>
          <ContactSection locale={locale} text={text.contact} />
        </main>
      </>
    </ContentImageLabel>
  );
}

async function HomeServices({ text, locale }: { text: Messages; locale: Locale }) {
  const services = await getServiceOfferings(locale);
  return (
    <ServiceVectors
      text={text.vectors}
      clarity={text.clarity}
      locale={locale}
      services={services}
    />
  );
}

async function HomePartners({ text, locale }: { text: Messages; locale: Locale }) {
  const partners = await getPartners(locale);
  return (
    <PartnersSlider
      title={text.partners.title}
      description={text.partners.description}
      partners={partners}
    />
  );
}

async function HomeBlog({ text, locale }: { text: Messages; locale: Locale }) {
  const posts = (await getBlogPosts(locale)).slice(0, 4);
  return <Blog text={text} locale={locale} posts={posts} />;
}

async function HomeFaq({ text, locale }: { text: Messages; locale: Locale }) {
  const items = await getHomeFaqs(locale);
  return <Faq text={text} items={items} />;
}

async function HomeMission({ text, locale }: { text: Messages; locale: Locale }) {
  const homeSeoText = await getHomeSeoText(locale);
  return (
    <MissionText title={homeSeoText?.title ?? text.mission.statement}>
      <p>{homeSeoText?.content ?? text.mission.description}</p>
    </MissionText>
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
              <span className="mono">0{index + 1}</span>
            </div>
            <h3>{item[0]}</h3>
            <p className="muted">{item[1]}</p>
          </article>
        ))}
      </div>
    </section>
  );
}

function Approach({ text }: { text: Messages }) {
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
              <span className="mono">{item[0]}</span>
            </div>
            <h3>{item[1]}</h3>
            <p className="muted">{text.approach.description}</p>
          </article>
        ))}
      </div>
      <div className="center">
        <Link className="btn" href="/about">
          <span>{text.approach.more}</span>
          <ArrowRight />
        </Link>
      </div>
    </section>
  );
}

function MobileClarity({ text }: { text: Messages }) {
  return (
    <section className="mobile-clarity container">
      <h2>{text.clarity.title}</h2>
      <p>{text.clarity.description}</p>
      <LeadModalButton className="btn">
        <span>{text.clarity.action}</span>
        <ArrowRight />
      </LeadModalButton>
    </section>
  );
}

function People({ text }: { text: Messages }) {
  return (
    <section className="section container people">
      <div className="people-profile">
        <h2>{text.people.title}</h2>
        <div className="people-profile-grid">
          <div className="people-founder">
            <div className="people-founder-photo">
              <ContentPhotoImg src="/images/home/founder.webp" />
            </div>
            <Link className="btn btn-primary people-team-link" href="/team">
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
        <Link className="btn btn-primary home-blog-more" href="/blog">
          {t.allArticles}
          <ArrowRight />
        </Link>
      </header>
      <div className={`home-blog-grid${posts.length === 1 ? " is-single" : ""}`}>
        {posts.map((post, index) => {
          const image = post.image || "/images/blog/blog.webp";
          return (
            <article
              className={`home-blog-card${index === 0 ? " is-featured" : ""}`}
              key={post.slug}
            >
              <Link
                aria-label={post.title}
                className={`home-blog-image${post.image ? "" : " is-placeholder"}`}
                href={`/blog/${post.slug}`}
              >
                <ContentPhotoImg src={image} />
              </Link>
              <div className="home-blog-body">
                <div className="home-blog-meta mono">
                  <span className="home-blog-category">{post.category}</span>
                  <span>
                    {post.publishedAt}
                    {index === 0 ? ` · ${post.readingTime} ${t.minutesLabel}` : ""}
                  </span>
                </div>
                <h3>
                  <Link href={`/blog/${post.slug}`}>{post.title}</Link>
                </h3>
                <small className="home-blog-card-author mono">
                  {t.cardAuthorLabel}: {post.authorName || "GVSPACE"}
                </small>
              </div>
            </article>
          );
        })}
      </div>
    </section>
  );
}

function Faq({ text, items }: { text: Messages; items: FaqItem[] }) {
  return (
    <section className="section container faq">
      <div className="faq-intro">
        <h2>{text.faq.title}</h2>
        <p>{text.faq.subtitle}</p>
        <Link className="btn btn-primary" href="/contacts">
          {text.faq.action}
        </Link>
      </div>
      <div className="faq-list">
        {items.map((item, index) => (
          <details key={item.id} name="home-faq" open={index === 1}>
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
