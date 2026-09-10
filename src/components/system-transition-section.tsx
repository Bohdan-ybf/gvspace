import Image from "next/image";
import type { Locale } from "@/i18n";

import { componentCopy } from "@/i18n/component-copy";
export function SystemTransitionSection({ locale }: { locale: Locale }) {
  const copy = componentCopy[locale]["system-transition-section"];

  return (
    <section className="services-method">
      <Image src="/images/services/system-background.webp" alt="" fill sizes="100vw" />
      <div className="container">
        <span className="mono">{copy.copy1}</span>
        <h2>{copy.copy2}</h2>

        <div className="method-flow">
          <article>
            <small className="mono">{copy.copy3}</small>
            <h3>{copy.copy4}</h3>
            <p>{copy.copy5}</p>
          </article>

          <MethodArrow />

          <article>
            <small className="mono">{copy.copy6}</small>
            <h3>{copy.copy7}</h3>
            <p>{copy.copy8}</p>
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
