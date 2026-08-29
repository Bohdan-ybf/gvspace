import type { ClientReview } from "./wordpress-reviews";

export function ReviewCard({
  review,
  compact = false,
  readMoreHref,
  readMoreLabel,
}: {
  review: ClientReview;
  compact?: boolean;
  readMoreHref?: string;
  readMoreLabel?: string;
}) {
  return (
    <article className={`client-review-card${compact ? " is-compact" : ""}`}>
      {!compact && <div className="review-card-top mono">
        <span>{review.category || "GVSPACE"}</span>
        <span aria-label={`${review.rating} / 5`}>{"★".repeat(review.rating)}</span>
      </div>}
      {!compact && <header>
        <span className="review-avatar" style={review.image ? { backgroundImage: `url(${review.image})` } : undefined} />
        <span><strong>{review.name}</strong><small>{review.position} {review.company && ` / ${review.company}`}</small></span>
      </header>}
      <p>{review.text}</p>
      {compact && readMoreHref && (
        <a className="review-read-more" href={readMoreHref}>
          {readMoreLabel} →
        </a>
      )}
      {!compact && review.metrics.length > 0 && <div className="review-metrics">
        {review.metrics.map((metric) => <span key={metric}>{metric}</span>)}
      </div>}
      {compact && <div className="review-author">
        <span className="review-avatar" style={review.image ? { backgroundImage: `url(${review.image})` } : undefined} />
        <span><strong>{review.name}</strong><small>{review.position} {review.company && ` / ${review.company}`}</small></span>
      </div>}
    </article>
  );
}
