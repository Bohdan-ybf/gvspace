import Image from "next/image";
import Link from "next/link";
import { getDictionary, type Locale } from "@/i18n";
import { CareersBanner } from "./careers-banner";
import { ContactSection } from "./contact-section";
import { ArrowRight } from "./icons/arrow-right";
import { TeamDirectorySection } from "./team-directory-section";
import { TeamFounderSection } from "./team-founder-section";

export function TeamPage({ locale }: { locale: Locale }) {
  const text = getDictionary(locale);
  const uk = locale === "uk";

  return (
    <main className="team-page">
      <section className="team-hero">
        <Image src="/images/team/hero.webp" alt="" fill priority sizes="100vw" />
        <div className="container team-hero-content">
          <span className="mono">TEAM</span>
          <h1>
            {uk ? "Люди, які будують системи зростання" : "The people who build growth systems"}
          </h1>
          <p>
            {uk
              ? "Ми — команда стратегів, інженерів і маркетологів, яка об’єднує дисципліни навколо одного результату: керованого росту вашого бізнесу."
              : "We are strategists, engineers, and marketers united around one result: manageable business growth."}
          </p>
          <Link className="btn btn-primary" href={`/${locale}/contacts`}>
            {text.common.buildSystem}
            <ArrowRight />
          </Link>
        </div>
      </section>
      <TeamFounderSection locale={locale} />
      <TeamDirectorySection locale={locale} />
      <CareersBanner locale={locale} />
      <ContactSection text={text.contact} />
    </main>
  );
}
