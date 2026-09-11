"use client";

import Image from "next/image";
import Link from "next/link";
import { useMemo, useState } from "react";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";
import { ArrowRight } from "./icons/arrow-right";
import type { TeamCategory, TeamMember } from "./wordpress-team";

export function TeamDirectorySection({
  locale,
  categories,
  members,
}: {
  locale: Locale;
  categories: TeamCategory[];
  members: TeamMember[];
}) {
  const t = getTranslations("team", locale).directory;
  const [activeTab, setActiveTab] = useState(categories[0]?.slug ?? "core-team");
  const visibleMembers = useMemo(
    () => members.filter((member) => member.categorySlugs.includes(activeTab)),
    [activeTab, members],
  );

  return (
    <section className="team-directory section container">
      <span className="mono section-label">{locale === "uk" ? "КОМАНДА" : "TEAM"}</span>
      <div
        className="team-tabs"
        role="tablist"
        aria-label={locale === "uk" ? "Напрямки команди" : "Team departments"}
      >
        {categories.map((category) => (
          <button
            key={category.slug}
            className={activeTab === category.slug ? "is-active" : ""}
            type="button"
            role="tab"
            aria-selected={activeTab === category.slug}
            onClick={() => setActiveTab(category.slug)}
          >
            [ {category.name} ]
          </button>
        ))}
      </div>

      <div className="team-members" role="tabpanel">
        {visibleMembers.map((member) => (
          <article key={member.id}>
            <div className="team-member-photo">
              {member.image ? (
                <Image
                  src={member.image}
                  alt={member.imageAlt}
                  fill
                  sizes="(max-width: 600px) 100vw, 320px"
                  unoptimized={
                    member.image.startsWith("http://localhost:") ||
                    member.image.startsWith("http://127.0.0.1:")
                  }
                />
              ) : (
                <span aria-hidden="true" />
              )}
            </div>
            <div>
              <span className="mono">{member.role}</span>
              <h3>{member.name}</h3>
              <div className="team-member-tags mono">
                {member.tags.map((item) => (
                  <small key={item}>{item}</small>
                ))}
              </div>
            </div>
          </article>
        ))}
      </div>

      <div className="team-partners">
        <span className="mono section-label">{t.partnersEyebrow}</span>
        <div className="partner-grid">
          {["IT-РОЗРОБКА", "ПРОДАКШН", "SEO & GEO"].map((partner) => (
            <article key={partner}>
              <i aria-hidden="true" />
              <div>
                <span className="mono">{partner}</span>
                <h3>{t.partnerName}</h3>
                <p>{t.partnerDescription}</p>
              </div>
            </article>
          ))}
        </div>
        <Link className="btn" href={`/${locale}/contacts`}>
          {t.contactAction}
          <ArrowRight />
        </Link>
      </div>
    </section>
  );
}
