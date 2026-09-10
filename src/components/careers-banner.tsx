import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";

import { componentCopy } from "@/i18n/component-copy";
export function CareersBanner({ locale }: { locale: Locale }) {
  const copy = componentCopy[locale]["careers-banner"];
  return (
    <section className="careers-banner container">
      <Image
        src="/images/team/careers.webp"
        alt=""
        fill
        sizes="(max-width: 1320px) 100vw, 1280px"
      />
      <div>
        <h2>{copy.copy1}</h2>
        <p>{copy.copy2}</p>
      </div>
      <Link className="btn btn-primary" href={`/${locale}/careers`}>
        {copy.copy3}
        <ArrowRight />
      </Link>
    </section>
  );
}
