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

  const previousLabel = locale === "uk" ? "Попередній учасник" : "Previous teammate";
  const nextLabel = locale === "uk" ? "Наступний учасник" : "Next teammate";

  return (
    <section className="section container technology-meet-block">
      <h2>{title}</h2>
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
                sizes="(max-width: 900px) 100vw, 320px"
                unoptimized={!person.photo || person.photo.startsWith("http")}
              />
            </div>
            <article className="technology-meet-profile">
              {canCycle ? (
                <div className="technology-meet-nav">
                  <button type="button" aria-label={previousLabel} onClick={() => go(-1)}>
                    <MeetChevron direction="left" />
                  </button>
                  <button type="button" aria-label={nextLabel} onClick={() => go(1)}>
                    <MeetChevron direction="right" />
                  </button>
                </div>
              ) : null}
              {person.role ? <span>{person.role}</span> : null}
              <strong>{person.name}</strong>
              {person.quote ? <blockquote>{person.quote}</blockquote> : null}
              {person.years || person.projects ? (
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
              ) : null}
              {person.tags.length ? (
                <ul className="technology-meet-tags">
                  {person.tags.map((tag) => (
                    <li key={tag}>{tag}</li>
                  ))}
                </ul>
              ) : null}
            </article>
          </>
        ) : null}
      </div>
    </section>
  );
}

function MeetChevron({ direction }: { direction: "left" | "right" }) {
  return (
    <svg width="8" height="14" viewBox="0 0 8 14" fill="none" aria-hidden="true">
      <path
        d={direction === "left" ? "M7 1L1 7L7 13" : "M1 1L7 7L1 13"}
        stroke="currentColor"
        strokeWidth="1.6"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
}
