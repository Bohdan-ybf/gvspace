import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { ContactSection } from "./contact-section";
import { CasesShowcaseSection } from "./cases-showcase-section";
import { ReviewsSection } from "./reviews-section";
import { Breadcrumbs } from "./breadcrumbs";
import { TechnologiesCatalog } from "./technologies-catalog";
import { TechnologiesOverviewSection } from "./technologies-overview-section";
import { getTechnologyStack } from "./wordpress-technologies";

import { getTranslations } from "@/i18n/pages";
export async function TechnologiesPage({ locale }: { locale: Locale }) {
  const text = getTranslations("global", locale);
  const t = getTranslations("technologies", locale).page;
  const stack = await getTechnologyStack(locale);
  const contactText = {
    ...text.contact,
    eyebrow: t.contactEyebrow,
    title: t.contactTitle,
    titleSecond: t.contactTitleSecond,
  };

  return (
    <main className="technologies-page">
      <section className="technologies-hero">
        <Image
          className="technologies-hero-background"
          src="/images/technologies/technologies-bg.png"
          alt=""
          fill
          priority
          sizes="100vw"
        />
        <Image
          className="technologies-hero-object"
          src="/images/technologies/technologies-object.png"
          alt=""
          width={488}
          height={459}
          priority
          sizes="(max-width: 600px) 340px, (max-width: 900px) 400px, 488px"
        />
        <div className="container technologies-hero-content">
          <span className="mono">TECHNOLOGIES</span>
          <h1>{t.heroTitle}</h1>
          <p>{t.heroDescription}</p>
          <Link className="btn btn-primary" href={`/${locale}/contacts`}>
            {t.heroAction}
            <ArrowRight />
          </Link>
        </div>
      </section>
      <TechnologiesOverviewSection locale={locale} />
      <TechnologiesCatalog locale={locale} categories={stack.categories} items={stack.items} />
      <CasesShowcaseSection locale={locale} eyebrow={t.casesEyebrow} cardVariant="related" />
      <ReviewsSection locale={locale} eyebrow={t.reviewsEyebrow} title={t.reviewsTitle} />
      <Breadcrumbs
        locale={locale}
        items={[{ label: locale === "uk" ? "Технології" : "Technologies" }]}
        visible
      />
      <ContactSection text={contactText} />
    </main>
  );
}
