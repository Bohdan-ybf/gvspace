import Image from "next/image";
import type { Locale } from "@/i18n";
import { OpenApplicationButton } from "./open-application-button";

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
      <OpenApplicationButton locale={locale}>{t.action}</OpenApplicationButton>
    </section>
  );
}
