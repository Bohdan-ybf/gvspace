import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";

import { getTranslations } from "@/i18n/pages";
export function OpenApplicationBanner({ locale }: { locale: Locale }) {
  const t = getTranslations("careers", locale).openApplication;

  return (
    <section className="open-application container">
      <Image
        src="/images/careers/open-application.webp"
        alt=""
        fill
        sizes="(max-width: 1320px) 100vw, 1280px"
      />
      <div>
        <h2>{t.title}</h2>
        <p>{t.description}</p>
      </div>
      <Link className="btn btn-primary" href={`/${locale}/contacts`}>
        {t.action}
      </Link>
    </section>
  );
}
