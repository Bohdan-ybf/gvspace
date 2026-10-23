"use client";

import Image from "next/image";
import Link from "next/link";
import { useMemo, useState } from "react";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";
import { ContentPhoto } from "./content-image-label";
import { ArrowRight } from "./icons/arrow-right";
import type { TeamCategory, TeamMember } from "./wordpress-team";
import type { Partner } from "./wordpress-partners";

export function TeamDirectorySection({
  locale,
  categories,
  members,
  partners,
}: {
  locale: Locale;
  categories: TeamCategory[];
  members: TeamMember[];
  partners: Partner[];
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
            {category.name}
          </button>
        ))}
      </div>

      <div className="team-members" role="tabpanel">
        {visibleMembers.map((member) => (
          <article key={member.id}>
            <div className="team-member-photo">
              {member.image ? (
                <ContentPhoto
                  src={member.image}
                  fill
                  sizes="(max-width: 600px) 100vw, 320px"
                  unoptimized={
                    member.image.startsWith("http://localhost:") ||
                    member.image.startsWith("http://127.0.0.1:")
                  }
                />
              ) : (
                <Image src="/images/team/user-none.jpg" alt="" fill sizes="320px" />
              )}
            </div>
            <div>
              <span className="mono">{member.role}</span>
              <h2>{member.name}</h2>
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
          {partners.map((partner) => (
            <article key={partner.id}>
              <span className={partner.image ? "partner-card-logo has-image" : "partner-card-logo"}>
                {partner.image ? (
                  <Image
                    src={partner.image}
                    alt=""
                    fill
                    sizes="82px"
                    unoptimized={
                      partner.image.startsWith("http://localhost:") ||
                      partner.image.endsWith(".svg")
                    }
                  />
                ) : null}
              </span>
              <div>
                <span className="mono">{partner.direction}</span>
                <h2>{partner.name}</h2>
                {partner.description ? <p>{partner.description}</p> : null}
              </div>
            </article>
          ))}
        </div>
        <Link className="btn" href="/partners">
          {t.contactAction}
          <ArrowRight />
        </Link>
      </div>
    </section>
  );
}
