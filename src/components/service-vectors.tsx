"use client";

import Image from "next/image";
import Link from "next/link";
import { useState } from "react";
import type { Locale } from "@/i18n";
import type { Messages } from "@/i18n/uk";
import { ArrowRight } from "./icons/arrow-right";
import type { ServiceOffering } from "./wordpress-services";

type ServiceVectorsProps = { locale: Locale; text: Messages["vectors"]; services: ServiceOffering[] };

export function ServiceVectors({ locale, text, services }: ServiceVectorsProps) {
  const [activeIndex, setActiveIndex] = useState<number | null>(0);
  const directions = text.slugs.map((slug, index) => {
    const direction = services.find((service) => service.slug === slug && !service.parentSlug);
    const children = services.filter((service) => service.parentSlug === slug);
    return {
      slug,
      title: direction?.title || text.items[index],
      description: direction?.description || text.descriptions[index],
      children: children.length
        ? children
        : text.links[index].map((title, fallbackIndex) => ({
            id: fallbackIndex,
            slug: "",
            title,
          })),
    };
  });

  return (
    <section className="section container vectors-section">
      <h2>{text.title}</h2>
      <div className="vectors">
        <Image
          src="/images/figma/service-vectors.webp"
          width={410}
          height={385}
          sizes="(max-width: 900px) 100vw, 410px"
          alt={text.imageAlt}
        />
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
                <div className="vector-panel" id={panelId} hidden={!isOpen}>
                  <p className="muted">{direction.description}</p>
                  <ul>
                    {direction.children.map((service) => (
                      <li key={service.id}>
                        <Link href={service.slug ? `/${locale}/services/${direction.slug}/${service.slug}` : `/${locale}/services/${direction.slug}`}>
                          <span>{service.title}</span>
                          <ArrowRight />
                        </Link>
                      </li>
                    ))}
                  </ul>
                  <Link
                    className="btn vector-more"
                    href={`/${locale}/services/${direction.slug}`}
                  >
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
