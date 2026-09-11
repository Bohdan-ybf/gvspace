"use client";

import Link from "next/link";
import { useState } from "react";
import type { Locale } from "@/i18n";

import { getTranslations } from "@/i18n/pages";
import { ArrowRight } from "./icons/arrow-right";

export function AboutPrinciplesSection({ locale }: { locale: Locale }) {
  const t = getTranslations("about", locale).principles;
  const [activeTab, setActiveTab] = useState<"values" | "vision">("values");

  return (
    <section className="about-principles section container">
      <div className="about-team">
        <div className="about-team-photo">{t.photoLabel}</div>
        <div className="about-team-copy">
          <h2>{t.teamTitle}</h2>
          <p>{t.teamDescription}</p>
          <Link className="btn btn-primary" href={`/${locale}/team`}>
            {locale === "uk" ? "До команди" : "Meet the team"}
            <ArrowRight />
          </Link>
        </div>
      </div>

      <div className="about-values">
        <div className="about-principles-tabs" role="tablist" aria-label={t.valuesTitle}>
          <button
            className={activeTab === "values" ? "is-active" : ""}
            type="button"
            role="tab"
            aria-selected={activeTab === "values"}
            onClick={() => setActiveTab("values")}
          >
            {t.valuesTitle}
          </button>
          <button
            className={activeTab === "vision" ? "is-active" : ""}
            type="button"
            role="tab"
            aria-selected={activeTab === "vision"}
            onClick={() => setActiveTab("vision")}
          >
            {t.visionLabel}
          </button>
        </div>

        {activeTab === "values" ? (
          <div className="about-values-grid" role="tabpanel">
            {t.values.map(([title, description]) => (
              <article key={title}>
                <h3>{title}</h3>
                <p>{description}</p>
              </article>
            ))}
          </div>
        ) : (
          <div className="about-vision-panel" role="tabpanel">
            <div>
              <p>{t.visionLead}</p>
              <p>{t.visionDescription}</p>
            </div>
            <p>{t.visionSummary}</p>
          </div>
        )}
      </div>
    </section>
  );
}
