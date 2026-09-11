import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";

import { getTranslations } from "@/i18n/pages";
const roles = ["HEAD OF MARKETING", "LEAD DEVELOPER", "BRAND STRATEGIST"];
const partners = ["IT-РОЗРОБКА", "ПРОДАКШН", "SEO & GEO"];
const expertise = ["META ADS", "GOOGLE ADS", "ANALYTICS"];

export function TeamDirectorySection({ locale }: { locale: Locale }) {
  const t = getTranslations("team", locale).directory;

  return (
    <section className="team-directory section container">
      <span className="mono section-label">CORE TEAM</span>
      <div className="team-members">
        {roles.map((role) => (
          <article key={role}>
            <div className="team-member-photo" aria-hidden="true" />
            <div>
              <span className="mono">{role}</span>
              <h3>{t.memberName}</h3>
              <p>{t.memberDescription}</p>
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
        <span className="mono section-label">{t.partnersEyebrow}</span>
        <div className="partner-grid">
          {partners.map((partner) => (
            <article key={partner}>
              <i aria-hidden="true" />
              <div>
                <span className="mono">{partner}</span>
                <h3>{t.partnerName}</h3>
                <p>{t.partnerDescription}</p>
              </div>
            </article>
          ))}
        </div>
        <Link className="btn" href={`/${locale}/contacts`}>
          {t.contactAction}
          <ArrowRight />
        </Link>
      </div>
    </section>
  );
}
