import Image from "next/image";
import type { Locale } from "@/i18n";

export function SystemTransitionSection({ locale }: { locale: Locale }) {
  const uk = locale === "uk";

  return (
    <section className="services-method">
      <Image src="/images/services/system-background.webp" alt="" fill sizes="100vw" />
      <div className="container">
        <span className="mono">{uk ? "НАШ ПІДХІД" : "OUR APPROACH"}</span>
        <h2>{uk ? "Від Хаосу до Системи" : "From Chaos to System"}</h2>

        <div className="method-flow">
          <article>
            <small className="mono">{uk ? "ХАОС (ТОЧКА А)" : "CHAOS (POINT A)"}</small>
            <h3>{uk ? "Дії як лотерея" : "Actions as a lottery"}</h3>
            <p>
              {uk
                ? "Втрачені бюджети, неузгоджені звіти, рішення на основі інтуїції."
                : "Lost budgets, disconnected reports, decisions based on intuition."}
            </p>
          </article>

          <MethodArrow />

          <article>
            <small className="mono">{uk ? "GVSPACE (ТОЧКА Б)" : "GVSPACE (POINT B)"}</small>
            <h3>{uk ? "Простір для рішень" : "Space for decisions"}</h3>
            <p>
              {uk
                ? "Прозорі дашборди, масштабування як свідомий крок, а не випадковість."
                : "Transparent dashboards and deliberate scaling."}
            </p>
          </article>
        </div>
      </div>
    </section>
  );
}

function MethodArrow() {
  return (
    <svg
      className="method-arrow"
      width="25"
      height="36"
      viewBox="0 0 25 36"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      aria-hidden="true"
    >
      <path
        d="M24.749 17.6777L24.6699 17.7568L24.749 17.8359L7.07129 35.5137L0 28.4424L10.6855 17.7568L0 7.07129L7.07129 0L24.749 17.6777Z"
        fill="white"
      />
    </svg>
  );
}
