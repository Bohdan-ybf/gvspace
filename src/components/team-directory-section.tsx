import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";

const roles = ["HEAD OF MARKETING", "LEAD DEVELOPER", "BRAND STRATEGIST"];
const partners = ["IT-РОЗРОБКА", "ПРОДАКШН", "SEO & GEO"];
const expertise = ["META ADS", "GOOGLE ADS", "ANALYTICS"];

export function TeamDirectorySection({ locale }: { locale: Locale }) {
  const uk = locale === "uk";

  return (
    <section className="team-directory section container">
      <span className="mono section-label">CORE TEAM</span>
      <div className="team-members">
        {roles.map((role) => (
          <article key={role}>
            <div className="team-member-photo" aria-hidden="true" />
            <div>
              <span className="mono">{role}</span>
              <h3>{uk ? "[Ім’я Прізвище]" : "[First Last Name]"}</h3>
              <p>
                {uk
                  ? "[Коротко: зона відповідальності та ключова експертиза. 1–2 речення]"
                  : "[Area of responsibility and key expertise. 1–2 sentences.]"}
              </p>
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
        <span className="mono section-label">
          {uk ? "ПАРТНЕРИ ТА ПІДРЯДНИКИ" : "PARTNERS AND CONTRACTORS"}
        </span>
        <div className="partner-grid">
          {partners.map((partner) => (
            <article key={partner}>
              <i aria-hidden="true" />
              <div>
                <span className="mono">{partner}</span>
                <h3>{uk ? "[Назва компанії]" : "[Company name]"}</h3>
                <p>
                  {uk
                    ? "[Коротко: що саме закривають ці партнери і чому їм довіряємо.]"
                    : "[What these partners handle and why we trust them.]"}
                </p>
              </div>
            </article>
          ))}
        </div>
        <Link className="btn" href={`/${locale}/contacts`}>
          {uk ? "Про партнерство з нами" : "Partner with us"}
          <ArrowRight />
        </Link>
      </div>
    </section>
  );
}
