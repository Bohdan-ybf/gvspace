import Image from "next/image";
import Link from "next/link";
import { getDictionary, type Locale } from "@/i18n";
import type { Messages } from "@/i18n/uk";
import { ArrowRight } from "./icons/arrow-right";
import { ServiceVectors } from "./service-vectors";
import { CasesSection } from "./cases-section";
import { ContactSection } from "./contact-section";
import { TechnologySection } from "./technology-section";

export function Home({ locale }: { locale: Locale }) {
  const text = getDictionary(locale);

  return (
    <>
      <section className="hero">
        <Image
          src="/images/hero/hero-main.webp"
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
            <br />
            {text.hero.titleSecond}
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
        <ServiceVectors text={text.vectors} locale={locale} />
        <MobileClarity text={text} locale={locale} />
        <TechnologySection locale={locale} />
        <CasesSection text={text.cases} locale={locale} />
        <People text={text} />
        <Reviews text={text} />
        <Blog text={text} />
        <Faq text={text} />
        <section className="mission container">
          <b>{text.mission.statement}</b>
          <p>{text.mission.description}</p>
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
            <span className="mono">[ 0{index + 1} ]</span>
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
        {text.approach.items.map((item) => (
          <article key={item[0]}>
            <span className="mono">[ {item[0]} ]</span>
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

function People({ text }: { text: Messages }) {
  return (
    <section className="section container people">
      <div>
        <h2>{text.people.title}</h2>
        <p className="muted">{text.people.intro}</p>
      </div>
      <div className="stats">
        <strong>$10M+</strong>
        <b>{text.people.capitalization}</b>
        <div>
          <strong>50+</strong>
          <b>{text.people.ecosystems}</b>
        </div>
        <div>
          <strong>{text.people.days}</strong>
          <b>{text.people.results}</b>
        </div>
      </div>
    </section>
  );
}

function Reviews({ text }: { text: Messages }) {
  return (
    <section className="section container reviews-section">
      <h2 className="center-title">{text.reviews.title}</h2>
      <div className="reviews">
        {Array.from({ length: 4 }, (_, index) => (
          <article key={index}>
            <div />
            <b>{text.reviews.author}</b>
            <p>{text.reviews.text}</p>
          </article>
        ))}
      </div>
    </section>
  );
}

function Blog({ text }: { text: Messages }) {
  return (
    <section className="section container blog-section">
      <h2 className="center-title">{text.blog.title}</h2>
      <div className="blog">
        <article className="featured">
          <div />
          <h3>{text.blog.headline}</h3>
          <p>{text.blog.intro}</p>
        </article>
        {Array.from({ length: 4 }, (_, index) => (
          <article key={index}>
            <div />
            <h3>{text.blog.articleTitle}</h3>
            <p>{text.blog.articleIntro}</p>
          </article>
        ))}
      </div>
    </section>
  );
}

function Faq({ text }: { text: Messages }) {
  return (
    <section className="section container faq">
      <h2>{text.faq.title}</h2>
      {text.faq.questions.map((question) => (
        <details key={question}>
          <summary>
            {question}
            <span>+</span>
          </summary>
          <p className="muted">{text.faq.answer}</p>
        </details>
      ))}
    </section>
  );
}
