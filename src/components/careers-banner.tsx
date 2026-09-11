import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";

import { getTranslations } from "@/i18n/pages";
export function CareersBanner({ locale }: { locale: Locale }) {
  const t = getTranslations("careers", locale).banner;
  return (
    <section className="careers-banner container">
      <Image
        src="/images/team/careers.webp"
        alt=""
        fill
        sizes="(max-width: 1320px) 100vw, 1280px"
      />
      <div>
        <h2>{t.title}</h2>
        <p>{t.description}</p>
      </div>
      <Link className="btn btn-primary" href={`/${locale}/careers`}>
        {t.action}
        <ArrowRight />
      </Link>
    </section>
  );
}
