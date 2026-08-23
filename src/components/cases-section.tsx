import Link from "next/link";
import type { Locale } from "@/i18n";
import type { Messages } from "@/i18n/uk";
import { ArrowRight } from "./icons/arrow-right";

type CasesSectionProps = {
  locale: Locale;
  text: Messages["cases"];
};

export function CasesSection({ locale, text }: CasesSectionProps) {
  return (
    <section className="section container cases">
      <aside>
        <h2>{text.title}</h2>
        <p>{text.subtitle}</p>
        <Link className="btn" href={`/${locale}/cases`}>
          <span>{text.all}</span>
          <ArrowRight />
        </Link>
      </aside>

      <div>
        {Array.from({ length: 3 }, (_, index) => (
          <article key={index}>
            <div>
              <span className="mono">0{index + 1}</span>
              <h3>{text.caseTitle}</h3>
              <p>[{text.result}]</p>
              <b>
                +140% ROAS　　−30% CPL
                <br />
                {text.revenue}
              </b>
            </div>
            <div className="case-image">
              <span>{text.badge}</span>
            </div>
          </article>
        ))}
      </div>
    </section>
  );
}
