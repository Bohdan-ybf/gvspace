import Image from "next/image";
import type { Locale } from "@/i18n";
import { InstagramIcon, LinkedinIcon } from "./icons/social-icons";
import { ArrowRight } from "./icons/arrow-right";

import { componentCopy } from "@/i18n/component-copy";
export function TeamFounderSection({ locale }: { locale: Locale }) {
  const copy = componentCopy[locale]["team-founder-section"];

  return (
    <section className="team-founder section container">
      <div className="team-philosophy">
        <span className="mono">{copy.copy1}</span>
        <p>{copy.copy2}</p>
      </div>

      <div className="founder-profile">
        <div className="founder-photo">
          <Image
            src="/images/team/founder.webp"
            alt={copy.copy3}
            fill
            sizes="(max-width: 700px) 100vw, 500px"
          />
        </div>
        <div className="founder-copy">
          <span className="mono">CEO &amp; FOUNDER</span>
          <h2>{copy.copy4}</h2>
          <blockquote>{copy.copy5}</blockquote>
          <p>{copy.copy6}</p>
          <p>{copy.copy7}</p>
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
