"use client";

import { useEffect, useId, useRef, useState } from "react";
import { createPortal } from "react-dom";
import type { ClientReview } from "./wordpress-reviews";

export function ReviewCard({
  review,
  compact = false,
  readMoreLabel,
  closeLabel = "Close",
}: {
  review: ClientReview;
  compact?: boolean;
  readMoreLabel?: string;
  closeLabel?: string;
}) {
  const [phase, setPhase] = useState<"closed" | "open" | "closing">("closed");
  const triggerRef = useRef<HTMLButtonElement>(null);
  const closeRef = useRef<HTMLButtonElement>(null);
  const titleId = useId();
  const tag = (review.tagLabel || review.category).trim();

  useEffect(() => {
    if (phase === "closed") return;
    const previousOverflow = document.body.style.overflow;
    document.body.style.overflow = "hidden";
    if (phase === "open") closeRef.current?.focus();
    const onKey = (event: KeyboardEvent) => {
      if (event.key === "Escape") setPhase("closing");
    };
    document.addEventListener("keydown", onKey);
    return () => {
      document.body.style.overflow = previousOverflow;
      document.removeEventListener("keydown", onKey);
    };
  }, [phase]);

  useEffect(() => {
    if (phase !== "closing") return;
    const timer = window.setTimeout(() => {
      setPhase("closed");
      triggerRef.current?.focus();
    }, 260);
    return () => window.clearTimeout(timer);
  }, [phase]);

  return (
    <article className={`client-review-card${compact ? " is-compact" : ""}`}>
      {!compact && (
        <div className="review-card-top mono">
          <span>{tag || "GVSPACE"}</span>
          <span className="review-stars" role="img" aria-label={`${review.rating} / 5`}>
            <span aria-hidden="true">{"★".repeat(Math.min(5, review.rating))}</span>
            <span className="is-muted" aria-hidden="true">
              {"★".repeat(Math.max(0, 5 - review.rating))}
            </span>
          </span>
        </div>
      )}
      {!compact && (
        <header>
          <span
            className="review-avatar"
            style={review.image ? { backgroundImage: `url(${review.image})` } : undefined}
          />
          <span>
            <strong>{review.name}</strong>
            <small>
              {review.position}
              {review.company && ` / ${review.company}`}
            </small>
          </span>
        </header>
      )}
      <p>{review.text}</p>
      {compact && readMoreLabel && (
        <button
          className="review-read-more"
          type="button"
          ref={triggerRef}
          onClick={() => setPhase("open")}
        >
          {readMoreLabel} →
        </button>
      )}
      {!compact && review.metrics.length > 0 && (
        <div className="review-metrics">
          {review.metrics.map((metric) => (
            <span key={metric}>{metric}</span>
          ))}
        </div>
      )}
      {compact && (
        <div className="review-author">
          <span
            className="review-avatar"
            style={review.image ? { backgroundImage: `url(${review.image})` } : undefined}
          />
          <span>
            <strong>{review.name}</strong>
            <small>
              {review.position}
              {review.company && ` / ${review.company}`}
            </small>
          </span>
        </div>
      )}
      {phase !== "closed"
        ? createPortal(
            <div
              className={`review-modal${phase === "closing" ? " is-closing" : ""}`}
              onClick={() => setPhase("closing")}
            >
              <div
                className="review-modal-dialog"
                role="dialog"
                aria-modal="true"
                aria-labelledby={titleId}
                onClick={(event) => event.stopPropagation()}
              >
                <div className="review-modal-main">
                  {tag ? <p className="review-modal-tag mono">{tag}</p> : null}
                  <button
                    className="review-modal-close"
                    type="button"
                    ref={closeRef}
                    aria-label={closeLabel}
                    onClick={() => setPhase("closing")}
                  >
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                      <path
                        d="M1 1L13 13M13 1L1 13"
                        stroke="currentColor"
                        strokeWidth="1.5"
                        strokeLinecap="round"
                      />
                    </svg>
                  </button>
                  <blockquote className="review-modal-quote">
                    <span className="review-modal-mark" aria-hidden="true">
                      “
                    </span>
                    <p>{review.text}</p>
                    <span className="review-modal-mark is-end" aria-hidden="true">
                      ”
                    </span>
                  </blockquote>
                </div>
                <div className="review-modal-author">
                  <span
                    className="review-avatar"
                    style={review.image ? { backgroundImage: `url(${review.image})` } : undefined}
                  />
                  <span>
                    <strong id={titleId}>{review.name}</strong>
                    <small>
                      {review.position}
                      {review.company && ` / ${review.company}`}
                    </small>
                  </span>
                </div>
              </div>
            </div>,
            document.body,
          )
        : null}
    </article>
  );
}
