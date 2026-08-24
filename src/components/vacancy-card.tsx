import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";

type VacancyCardProps = {
  locale: Locale;
  hot?: boolean;
  salary: string;
};

export function VacancyCard({ locale, hot = false, salary }: VacancyCardProps) {
  const uk = locale === "uk";

  return (
    <article className="vacancy-card">
      <div className="vacancy-card-heading">
        <h3>Performance Marketing Manager</h3>
        {hot && <span className="mono">{uk ? "ГАРЯЧА" : "HOT"}</span>}
      </div>
      <p>
        {uk
          ? "Шукаємо фахівця з досвідом у Meta та Google Ads, який вміє будувати системи, а не просто запускати кампанії."
          : "We are looking for a Meta and Google Ads expert who builds systems rather than simply launching campaigns."}
      </p>
      <div className="vacancy-tags mono">
        <span>{uk ? "МАРКЕТИНГ" : "MARKETING"}</span>
        <span>REMOTE</span>
        <span>FULL-TIME</span>
      </div>
      <div className="vacancy-card-footer">
        <b>{salary}</b>
        <Link href={`/${locale}/careers/performance-marketing-manager`}>
          {uk ? "Детальніше" : "Details"}
          <ArrowRight />
        </Link>
      </div>
    </article>
  );
}
