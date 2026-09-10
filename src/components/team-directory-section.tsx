import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";

import { componentCopy } from "@/i18n/component-copy";
const roles = ["HEAD OF MARKETING", "LEAD DEVELOPER", "BRAND STRATEGIST"];
const partners = ["IT-РОЗРОБКА", "ПРОДАКШН", "SEO & GEO"];
const expertise = ["META ADS", "GOOGLE ADS", "ANALYTICS"];

export function TeamDirectorySection({ locale }: { locale: Locale }) {
  const copy = componentCopy[locale]["team-directory-section"];

  return (
    <section className="team-directory section container">
      <span className="mono section-label">CORE TEAM</span>
      <div className="team-members">
        {roles.map((role) => (
          <article key={role}>
            <div className="team-member-photo" aria-hidden="true" />
            <div>
              <span className="mono">{role}</span>
              <h3>{copy.copy1}</h3>
              <p>{copy.copy2}</p>
              <div className="team-member-tags mono">
                {expertise.map((item) => (
                  <small key={item}>{item}</small>
                ))}
              </div>
            </div>
          </article>
        ))}
      </div>

      <div className="team-partners">
        <span className="mono section-label">{copy.copy3}</span>
        <div className="partner-grid">
          {partners.map((partner) => (
            <article key={partner}>
              <i aria-hidden="true" />
              <div>
                <span className="mono">{partner}</span>
                <h3>{copy.copy4}</h3>
                <p>{copy.copy5}</p>
              </div>
            </article>
          ))}
        </div>
        <Link className="btn" href={`/${locale}/contacts`}>
          {copy.copy6}
          <ArrowRight />
        </Link>
      </div>
    </section>
  );
}
