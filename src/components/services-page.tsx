import Image from "next/image";
import Link from "next/link";
import { getDictionary, type Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";
import { CasesSection } from "./cases-section";
import { ContactSection } from "./contact-section";
import { ServicesNavigation } from "./services-navigation";
import { SystemTransitionSection } from "./system-transition-section";
import { TechnologyShowcaseSection } from "./technology-showcase-section";
import { getServiceOfferings } from "./wordpress-services";
import { ReviewsSection } from "./reviews-section";

import { componentCopy } from "@/i18n/component-copy";

import { ukDirections, enDirections } from "@/i18n/page-copy";
export async function ServicesPage({ locale }: { locale: Locale }) {
  const copy = componentCopy[locale]["services-page"];
  const serviceItems = await getServiceOfferings(locale);
  const directionSlugs = ["strategy", "marketing", "development", "content"];
  const staticDirections = { uk: ukDirections, en: enDirections }[locale];
  const dynamicDirections = serviceItems
    .filter((item) => !item.parentSlug && directionSlugs.includes(item.slug))
    .sort((a, b) => directionSlugs.indexOf(a.slug) - directionSlugs.indexOf(b.slug))
    .map((item) => ({
      slug: item.slug,
      title: item.title,
      description:
        item.description ||
        staticDirections.find((direction) => direction.slug === item.slug)?.description ||
        "",
      image: item.image,
      services: serviceItems.filter((child) => child.parentSlug === item.slug),
    }));
  const hasDynamicChildren = dynamicDirections.some((direction) => direction.services.length);
  const directions = hasDynamicChildren
    ? dynamicDirections
    : staticDirections.map((direction) => ({
        ...direction,
        image: undefined,
        services: direction.services.map((title, index) => ({ id: index, slug: "", title })),
      }));
  const text = getDictionary(locale);
  return (
    <main className="services-page">
      <section className="services-hero">
        <Image src="/images/services/hero.webp" alt="" fill priority sizes="100vw" />
        <div className="container services-hero-copy">
          <span className="mono">SERVICES & SOLUTIONS</span>
          <h1>{copy.copy2}</h1>
          <p>{copy.copy3}</p>
          <Link className="btn btn-primary" href={`/${locale}/contacts`}>
            {copy.copy4}
            <ArrowRight />
          </Link>
        </div>
      </section>
      <ServicesNavigation items={directions} />
      <section className="service-directions">
        {directions.map((direction, index) => (
          <article className="service-direction container" id={direction.slug} key={direction.slug}>
            <div className="service-direction-copy">
              <div className="service-symbol" aria-hidden="true">
                <Image
                  src={direction.image || `/images/services/icons/${direction.slug}.webp`}
                  alt=""
                  fill
                  sizes="92px"
                />
              </div>
              <div>
                <span className="mono service-number">[0{index + 1}]</span>
                <h2>{direction.title}</h2>
                <Link className="btn" href={`/${locale}/services/${direction.slug}`}>
                  {copy.copy5}
                  <ArrowRight />
                </Link>
              </div>
              <p>{direction.description}</p>
            </div>
            <ul>
              {direction.services.map((service) => (
                <li key={service.id || service.title}>
                  {service.slug ? (
                    <Link href={`/${locale}/services/${direction.slug}/${service.slug}`}>
                      <span>{service.title}</span>
                      <ArrowRight />
                    </Link>
                  ) : (
                    <>
                      <span>{service.title}</span>
                      <ArrowRight />
                    </>
                  )}
                </li>
              ))}
            </ul>
          </article>
        ))}
      </section>
      <SystemTransitionSection locale={locale} />
      <TechnologyShowcaseSection locale={locale} />
      <CasesSection locale={locale} text={text.cases} />
      <ReviewsSection locale={locale} />
      <ContactSection text={text.contact} />
    </main>
  );
}
