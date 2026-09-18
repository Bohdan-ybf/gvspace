"use client";

import Image from "next/image";
import Link from "next/link";
import { useState } from "react";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";

export type TechnologyMeetPerson = {
  name: string;
  role: string;
  quote: string;
  years: string;
  projects: string;
  tags: string[];
  photo: string;
};

export function TechnologyMeetSection({
  locale,
  title,
  people,
  clarityTitle,
  clarityDescription,
  clarityAction,
  yearsLabel,
  projectsLabel,
}: {
  locale: Locale;
  title: string;
  people: TechnologyMeetPerson[];
  clarityTitle: string;
  clarityDescription: string;
  clarityAction: string;
  yearsLabel: string;
  projectsLabel: string;
}) {
  const [index, setIndex] = useState(0);
  const person = people[index];
  const canCycle = people.length > 1;
  const go = (direction: -1 | 1) => {
    setIndex((current) => (current + direction + people.length) % people.length);
  };

  return (
    <section className="section container technology-meet-block">
      <header>
        <h2>{title}</h2>
        {canCycle ? (
          <div className="technology-meet-nav">
            <button type="button" aria-label="Previous" onClick={() => go(-1)}>
              ‹
            </button>
            <button type="button" aria-label="Next" onClick={() => go(1)}>
              ›
            </button>
          </div>
        ) : null}
      </header>
      <div className="technology-meet-grid">
        <article className="technology-meet-clarity">
          <h3>{clarityTitle}</h3>
          <p>{clarityDescription}</p>
          <Link className="btn btn-primary" href={`/${locale}/contacts`}>
            {clarityAction}
          </Link>
        </article>
        {person ? (
          <>
            <div className="technology-meet-photo">
              <Image
                src={person.photo || "/images/team/user-none.jpg"}
                alt={person.name}
                fill
                sizes="(max-width: 900px) 100vw, 360px"
                unoptimized={person.photo.startsWith("http")}
              />
            </div>
            <article className="technology-meet-profile">
              {person.role ? <span className="mono">{person.role}</span> : null}
              <strong>{person.name}</strong>
              {person.quote ? <blockquote>{person.quote}</blockquote> : null}
              <dl>
                {person.years ? (
                  <div>
                    <dt>{yearsLabel}</dt>
                    <dd>{person.years}</dd>
                  </div>
                ) : null}
                {person.projects ? (
                  <div>
                    <dt>{projectsLabel}</dt>
                    <dd>{person.projects}</dd>
                  </div>
                ) : null}
              </dl>
              {person.tags.length ? (
                <div className="technology-meet-tags mono">
                  {person.tags.map((tag) => (
                    <small key={tag}>{tag}</small>
                  ))}
                </div>
              ) : null}
            </article>
          </>
        ) : null}
      </div>
    </section>
  );
}
