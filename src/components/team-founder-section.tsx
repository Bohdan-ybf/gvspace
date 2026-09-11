import Image from "next/image";
import type { Locale } from "@/i18n";
import { InstagramIcon, LinkedinIcon } from "./icons/social-icons";
import { ArrowRight } from "./icons/arrow-right";

import { getTranslations } from "@/i18n/pages";
export function TeamFounderSection({ locale }: { locale: Locale }) {
  const t = getTranslations("team", locale).founder;

  return (
    <section className="team-founder section container">
      <div className="team-philosophy">
        <span className="mono">{t.eyebrow}</span>
        <p>{t.philosophy}</p>
      </div>

      <div className="founder-profile">
        <div className="founder-photo">
          <Image
            src="/images/team/founder.webp"
            alt={t.imageAlt}
            fill
            sizes="(max-width: 700px) 100vw, 500px"
          />
        </div>
        <div className="founder-copy">
          <span className="mono">CEO &amp; FOUNDER</span>
          <h2>{t.name}</h2>
          <blockquote>{t.quote}</blockquote>
          <p>{t.description}</p>
          <p>{t.summary}</p>
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
