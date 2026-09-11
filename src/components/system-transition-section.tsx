import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";

import { getTranslations } from "@/i18n/pages";
export function SystemTransitionSection({ locale }: { locale: Locale }) {
  const t = getTranslations("common", locale).systemTransition;

  return (
    <section className="services-method">
      <Image src="/images/services/system-background.webp" alt="" fill sizes="100vw" />
      <div className="container">
        <span className="mono">{t.eyebrow}</span>
        <h2>{t.title}</h2>

        <div className="method-flow">
          <article>
            <small className="mono">{t.beforeLabel}</small>
            <h3>{t.beforeTitle}</h3>
            <p>{t.beforeDescription}</p>
          </article>

          <MethodArrow />

          <article>
            <small className="mono">{t.afterLabel}</small>
            <h3>{t.afterTitle}</h3>
            <p>{t.afterDescription}</p>
          </article>
        </div>
        <Link className="btn btn-primary method-cta" href={`/${locale}/contacts`}>
          {t.action}
          <ArrowRight />
        </Link>
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
