"use client";

import Image from "next/image";
import Link from "next/link";
import { useState } from "react";
import type { Locale } from "@/i18n";
import type { Messages } from "@/i18n/uk";
import { ArrowRight } from "./icons/arrow-right";
import type { ServiceOffering } from "./wordpress-services";

type ServiceVectorsProps = {
  locale: Locale;
  text: Messages["vectors"];
  clarity: Messages["clarity"];
  services: ServiceOffering[];
};

export function ServiceVectors({ locale, text, clarity, services }: ServiceVectorsProps) {
  const [activeIndex, setActiveIndex] = useState<number | null>(0);
  const parents = services.filter((service) => !service.parentSlug);
  const directions = parents.length
    ? parents.map((direction) => {
        const staticIndex = text.slugs.indexOf(direction.slug);
        const children = services.filter((service) => service.parentSlug === direction.slug);
        return {
          slug: direction.slug,
          title: direction.title || text.items[staticIndex] || direction.slug,
          description:
            direction.description ||
            (staticIndex >= 0 ? text.descriptions[staticIndex] : "") ||
            direction.headline,
          children: children.length
            ? children.map((service) => ({
                id: service.id,
                slug: service.slug,
                title: service.title,
              }))
            : staticIndex >= 0
              ? text.links[staticIndex].map((title, fallbackIndex) => ({
                  id: fallbackIndex,
                  slug: "",
                  title,
                }))
              : [],
        };
      })
    : text.slugs.map((slug, index) => ({
        slug,
        title: text.items[index],
        description: text.descriptions[index],
        children: text.links[index].map((title, fallbackIndex) => ({
          id: fallbackIndex,
          slug: "",
          title,
        })),
      }));

  return (
    <section className="section container vectors-section">
      <header className="vectors-header">
        <h2>{text.title}</h2>
        <Link className="btn btn-primary vectors-all" href={`/${locale}/services`}>
          <span>{locale === "uk" ? "Усі послуги" : "All services"}</span>
          <ArrowRight />
        </Link>
      </header>
      <div className="vectors">
        <aside className="vectors-visual">
          <Image
            src="/images/figma/service-vectors.webp"
            width={410}
            height={385}
            sizes="(max-width: 900px) 100vw, 410px"
            alt={text.imageAlt}
          />
          <div className="vectors-clarity-card">
            <h3>{clarity.title}</h3>
            <p>{clarity.description}</p>
            <Link href={`/${locale}/contacts`}>
              <span>{clarity.action}</span>
              <ArrowRight />
            </Link>
          </div>
        </aside>
        <div className="vectors-accordion">
          {directions.map((direction, index) => {
            const isOpen = activeIndex === index;
            const panelId = `service-vector-panel-${index}`;

            return (
              <article className={isOpen ? "is-open" : undefined} key={direction.slug}>
                <button
                  className="vector-trigger"
                  type="button"
                  aria-expanded={isOpen}
                  aria-controls={panelId}
                  onClick={() =>
                    setActiveIndex((currentIndex) => (currentIndex === index ? null : index))
                  }
                >
                  <span>{direction.title}</span>
                  <span className="vector-toggle" aria-hidden="true">
                    <span>[</span>
                    <span className="vector-toggle-symbol">{isOpen ? "−" : "+"}</span>
                    <span>]</span>
                  </span>
                </button>
                {direction.description ? (
                  <p className="vector-description">{direction.description}</p>
                ) : null}
                <div className="vector-panel" id={panelId} hidden={!isOpen}>
                  <ul>
                    {direction.children.map((service) => (
                      <li key={service.id}>
                        <Link
                          href={
                            service.slug
                              ? `/${locale}/services/${direction.slug}/${service.slug}`
                              : `/${locale}/services/${direction.slug}`
                          }
                        >
                          <span>{service.title}</span>
                          <ArrowRight />
                        </Link>
                      </li>
                    ))}
                  </ul>
                  <Link className="btn vector-more" href={`/${locale}/services/${direction.slug}`}>
                    <span>{text.more}</span>
                    <ArrowRight />
                  </Link>
                </div>
              </article>
            );
          })}
        </div>
      </div>
    </section>
  );
}
