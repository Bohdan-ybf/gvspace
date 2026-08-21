import Image from "next/image";
import Link from "next/link";
import { getDictionary } from "@/i18n";
import type { Messages } from "@/i18n/uk";
import { Footer } from "./footer";
import { Header } from "./header";
import { ArrowRight } from "./icons/arrow-right";
import { ServiceVectors } from "./service-vectors";

export function Home({ locale }: { locale: string }) {
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
        <Header locale={locale} />
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
        <Technology text={text} />
        <Cases text={text} locale={locale} />
        <People text={text} />
        <Reviews text={text} />
        <Blog text={text} />
        <Faq text={text} />
        <section className="mission container">
          <b>{text.mission.statement}</b>
          <p>{text.mission.description}</p>
        </section>
        <Contact text={text} />
      </main>
      <Footer locale={locale} />
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

function Approach({ text, locale }: { text: Messages; locale: string }) {
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

function Technology({ text }: { text: Messages }) {
  return (
    <section className="section container technology-section">
      <h2>{text.technology.title}</h2>
      <p className="mono filters">[ MARKETING ]　[ DEVELOPMENT ]　[ SYSTEMS ]　[ CONTENT ]</p>
      <div className="logos">
        {Array.from({ length: 32 }, (_, index) => (
          <div key={index}>
            {text.technology.logo}
            <br />
            <b>{text.technology.name}</b>
          </div>
        ))}
      </div>
    </section>
  );
}

function MobileClarity({ text, locale }: { text: Messages; locale: string }) {
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

function Cases({ text, locale }: { text: Messages; locale: string }) {
  return (
    <section className="section container cases">
      <aside>
        <h2>{text.cases.title}</h2>
        <p>{text.cases.subtitle}</p>
        <Link className="btn" href={`/${locale}/cases`}>
          <span>{text.cases.all}</span>
          <ArrowRight />
        </Link>
      </aside>
      <div>
        {Array.from({ length: 3 }, (_, index) => (
          <article key={index}>
            <div>
              <span className="mono">0{index + 1}</span>
              <h3>{text.cases.caseTitle}</h3>
              <p>[{text.cases.result}]</p>
              <b>
                +140% ROAS　　−30% CPL
                <br />
                {text.cases.revenue}
              </b>
            </div>
            <div className="case-image">{text.cases.badge}</div>
          </article>
        ))}
      </div>
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

function Contact({ text }: { text: Messages }) {
  return (
    <section className="contact">
      <div>
        <span className="mono">{text.contact.eyebrow}</span>
        <h2>
          {text.contact.title}
          <br />
          {text.contact.titleSecond}
        </h2>
        <p>{text.contact.intro}</p>
      </div>
      <form>
        <div>
          <input aria-label={text.contact.name} placeholder={text.contact.name} required />
          <input aria-label={text.contact.phone} placeholder="+38 0..." />
        </div>
        <input type="email" aria-label="Email" placeholder="Email" required />
        <input aria-label={text.contact.topic} placeholder={text.contact.topic} required />
        <textarea
          aria-label={text.contact.description}
          placeholder={text.contact.description}
          required
        />
        <button className="btn btn-primary">{text.contact.submit}</button>
        <p className="contact-consent mono">{text.contact.consent}</p>
      </form>
    </section>
  );
}
