"use client";

import Image from "next/image";
import Link from "next/link";
import { useState } from "react";
import type { Locale } from "@/i18n";
import type { Messages } from "@/i18n/uk";
import { ArrowRight } from "./icons/arrow-right";

type ServiceVectorsProps = { locale: Locale; text: Messages["vectors"] };

export function ServiceVectors({ locale, text }: ServiceVectorsProps) {
  const [activeIndex, setActiveIndex] = useState<number | null>(0);

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
          {text.items.map((item, index) => {
            const isOpen = activeIndex === index;
            const panelId = `service-vector-panel-${index}`;

            return (
              <article className={isOpen ? "is-open" : undefined} key={item}>
                <button
                  className="vector-trigger"
                  type="button"
                  aria-expanded={isOpen}
                  aria-controls={panelId}
                  onClick={() =>
                    setActiveIndex((currentIndex) => (currentIndex === index ? null : index))
                  }
                >
                  <span>{item}</span>
                  <span className="vector-toggle" aria-hidden="true">
                    <span>[</span>
                    <span className="vector-toggle-symbol">{isOpen ? "−" : "+"}</span>
                    <span>]</span>
                  </span>
                </button>
                <div className="vector-panel" id={panelId} hidden={!isOpen}>
                  <p className="muted">{text.descriptions[index]}</p>
                  <ul>
                    {text.links[index].map((link, linkIndex) => (
                      <li key={link}>
                        <Link href={`/${locale}/services/${text.slugs[index]}/${linkIndex + 1}`}>
                          <span>{link}</span>
                          <ArrowRight />
                        </Link>
                      </li>
                    ))}
                  </ul>
                  <Link
                    className="btn vector-more"
                    href={`/${locale}/services/${text.slugs[index]}`}
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
