import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { CareersBanner } from "./careers-banner";
import { ContactSection } from "./contact-section";
import { ArrowRight } from "./icons/arrow-right";
import { TeamDirectorySection } from "./team-directory-section";
import { TeamFounderSection } from "./team-founder-section";
import { Breadcrumbs } from "./breadcrumbs";
import { getTeamDirectory } from "./wordpress-team";

import { getTranslations } from "@/i18n/pages";
export async function TeamPage({ locale }: { locale: Locale }) {
  const text = getTranslations("global", locale);
  const t = getTranslations("team", locale).page;
  const directory = await getTeamDirectory(locale);

  return (
    <main className="team-page">
      <section className="team-hero">
        <Image
          className="team-hero-background"
          src="/images/team/team-bg.png"
          alt=""
          fill
          priority
          sizes="100vw"
        />
        <Image
          className="team-hero-object"
          src="/images/team/team-object.png"
          alt=""
          width={563}
          height={535}
          priority
          sizes="(max-width: 600px) 360px, (max-width: 900px) 440px, 563px"
        />
        <div className="container team-hero-content">
          <span className="mono">TEAM</span>
          <h1>{t.heroTitle}</h1>
          <p>{t.heroDescription}</p>
          <Link className="btn btn-primary" href={`/${locale}/contacts`}>
            {text.common.buildSystem}
            <ArrowRight />
          </Link>
        </div>
      </section>
      <TeamFounderSection locale={locale} />
      <TeamDirectorySection locale={locale} {...directory} />
      <CareersBanner locale={locale} />
      <Breadcrumbs
        locale={locale}
        items={[{ label: locale === "uk" ? "Команда" : "Team" }]}
        visible
      />
      <ContactSection text={text.contact} />
    </main>
  );
}
