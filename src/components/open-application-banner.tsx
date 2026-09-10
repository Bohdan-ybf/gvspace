import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";

import { componentCopy } from "@/i18n/component-copy";
export function OpenApplicationBanner({ locale }: { locale: Locale }) {
  const copy = componentCopy[locale]["open-application-banner"];

  return (
    <section className="open-application container">
      <Image
        src="/images/careers/open-application.webp"
        alt=""
        fill
        sizes="(max-width: 1320px) 100vw, 1280px"
      />
      <div>
        <h2>{copy.copy1}</h2>
        <p>{copy.copy2}</p>
      </div>
      <Link className="btn btn-primary" href={`/${locale}/contacts`}>
        {copy.copy3}
      </Link>
    </section>
  );
}
