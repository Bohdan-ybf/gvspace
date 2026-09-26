"use client";

import Image from "next/image";
import { useEffect, useState } from "react";
import type { TeamMember } from "./wordpress-team";

type AboutTeamSliderProps = {
  members: TeamMember[];
  yearsLabel: string;
  projectsLabel: string;
  previousLabel: string;
  nextLabel: string;
};

function TeamName({ name }: { name: string }) {
  const parts = name.trim().split(/\s+/);
  if (parts.length < 2) return <>{name}</>;
  const last = parts.pop();
  return (
    <>
      {parts.join(" ")}
      <br />
      {last}
    </>
  );
}

function isLocalUpload(src: string) {
  return src.startsWith("http://localhost:") || src.startsWith("http://127.0.0.1:");
}

export function AboutTeamSlider({
  members,
  yearsLabel,
  projectsLabel,
  previousLabel,
  nextLabel,
}: AboutTeamSliderProps) {
  const [index, setIndex] = useState(0);
  const [paused, setPaused] = useState(false);
  const [reducedMotion, setReducedMotion] = useState(false);
  const count = members.length;
  const activeIndex = count ? index % count : 0;
  const member = members[activeIndex];

  useEffect(() => {
    const media = window.matchMedia("(prefers-reduced-motion: reduce)");
    const update = () => setReducedMotion(media.matches);
    update();
    media.addEventListener("change", update);
    return () => media.removeEventListener("change", update);
  }, []);

  useEffect(() => {
    if (count < 2 || paused || reducedMotion) return;
    const timer = window.setTimeout(() => {
      setIndex((current) => (current + 1) % count);
    }, 5000);
    return () => window.clearTimeout(timer);
  }, [count, paused, reducedMotion, activeIndex]);

  if (!member) return null;

  const showPrevious = () => setIndex((current) => (current - 1 + count) % count);
  const showNext = () => setIndex((current) => (current + 1) % count);

  return (
    <div
      className="about-team-slider"
      onMouseEnter={() => setPaused(true)}
      onMouseLeave={() => setPaused(false)}
      onFocusCapture={() => setPaused(true)}
      onBlurCapture={(event) => {
        const next = event.relatedTarget;
        if (!(next instanceof Node) || !event.currentTarget.contains(next)) setPaused(false);
      }}
    >
      <article className="about-team-card" aria-live="polite" aria-atomic="true">
        <div className="about-team-card-photo" key={`photo-${member.id}`}>
          <Image
            src={member.image || "/images/team/user-none.jpg"}
            alt={member.imageAlt}
            fill
            sizes="(max-width: 600px) 100vw, 302px"
            unoptimized={!member.image || isLocalUpload(member.image)}
          />
        </div>
        <div className="about-team-card-copy" key={`copy-${member.id}`}>
          {member.role ? <span className="about-team-card-role">{member.role}</span> : null}
          <strong className="about-team-card-name">
            <TeamName name={member.name} />
          </strong>
          {member.years || member.projects ? (
            <dl className="about-team-card-stats">
              {member.years ? (
                <div>
                  <dt>{member.years}</dt>
                  <dd>{yearsLabel}</dd>
                </div>
              ) : null}
              {member.projects ? (
                <div>
                  <dt>{member.projects}</dt>
                  <dd>{projectsLabel}</dd>
                </div>
              ) : null}
            </dl>
          ) : null}
          {member.tags.length ? (
            <ul className="about-team-card-tags">
              {member.tags.map((tag) => (
                <li key={tag}>{tag}</li>
              ))}
            </ul>
          ) : null}
        </div>
        {count > 1 && (
          <div className="about-team-card-nav">
            <button type="button" onClick={showPrevious} aria-label={previousLabel}>
              <SliderChevron direction="left" />
            </button>
            <button type="button" onClick={showNext} aria-label={nextLabel}>
              <SliderChevron direction="right" />
            </button>
          </div>
        )}
      </article>
    </div>
  );
}

function SliderChevron({ direction }: { direction: "left" | "right" }) {
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
