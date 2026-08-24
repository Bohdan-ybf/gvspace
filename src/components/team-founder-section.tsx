import Image from "next/image";
import type { Locale } from "@/i18n";
import { InstagramIcon, LinkedinIcon } from "./icons/social-icons";
import { ArrowRight } from "./icons/arrow-right";

export function TeamFounderSection({ locale }: { locale: Locale }) {
  const uk = locale === "uk";

  return (
    <section className="team-founder section container">
      <div className="team-philosophy">
        <span className="mono">{uk ? "НАША ФІЛОСОФІЯ" : "OUR PHILOSOPHY"}</span>
        <p>
          {uk ? (
            <>
              Ми не наймаємо людей — ми обираємо партнерів.{" "}
              <b>Кожен у команді є власником своєї зони відповідальності</b>, а не виконавцем задач.
            </>
          ) : (
            <>
              We do not hire people — we choose partners.{" "}
              <b>Everyone owns their area of responsibility</b>, rather than simply completing
              tasks.
            </>
          )}
        </p>
      </div>

      <div className="founder-profile">
        <div className="founder-photo">
          <Image
            src="/images/team/founder.webp"
            alt={uk ? "Василь Горайчук" : "Vasyl Horaichuk"}
            fill
            sizes="(max-width: 700px) 100vw, 500px"
          />
        </div>
        <div className="founder-copy">
          <span className="mono">CEO &amp; FOUNDER</span>
          <h2>{uk ? "Василь Горайчук" : "Vasyl Horaichuk"}</h2>
          <blockquote>
            {uk
              ? "Зростання можливе лише тоді, коли є простір, ясність і система."
              : "Growth is possible only when there is space, clarity, and a system."}
          </blockquote>
          <p>
            {uk
              ? "Я створив GVSPACE, щоб допомагати бізнесам переходити від хаотичних рішень до системного та керованого зростання. Для мене важливо не просто отримати результат сьогодні, а зрозуміти, за рахунок чого бізнес може стабільно рости завтра."
              : "I created GVSPACE to help businesses move from chaotic decisions to systematic, manageable growth — with a clear understanding of what will sustain that growth tomorrow."}
          </p>
          <p>
            {uk
              ? "Тому GVSPACE — це про ясність у рішеннях, сильну систему та масштабування того, що справді створює цінність для бізнесу."
              : "GVSPACE is about clarity in decisions, strong systems, and scaling what genuinely creates business value."}
          </p>
          <div className="founder-socials">
            <a href="https://linkedin.com" target="_blank" rel="noreferrer">
              <LinkedinIcon />
              <span>
                <b>LinkedIn</b>
                <small>linkedin.com/in/vasyl-horaichuk/</small>
              </span>
              <ArrowRight />
            </a>
            <a href="https://instagram.com" target="_blank" rel="noreferrer">
              <InstagramIcon />
              <span>
                <b>Instagram</b>
                <small>@vasyl.horaichuk</small>
              </span>
              <ArrowRight />
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}
