"use client";

import { useState } from "react";

type ReviewSubmitCopy = {
  title: string;
  description: string;
  name: string;
  position: string;
  company: string;
  text: string;
  rating: string;
  consent: string;
  submit: string;
  success: string;
};

export function ReviewSubmitForm({ copy }: { copy: ReviewSubmitCopy }) {
  const [rating, setRating] = useState(5);
  const [sent, setSent] = useState(false);

  if (sent) {
    return (
      <section className="container review-submit" id="leave-review">
        <div>
          <h2>{copy.title}</h2>
          <p>{copy.success}</p>
        </div>
      </section>
    );
  }

  return (
    <section className="container review-submit" id="leave-review">
      <div>
        <h2>{copy.title}</h2>
        <p>{copy.description}</p>
      </div>
      <form
        onSubmit={(event) => {
          event.preventDefault();
          setSent(true);
        }}
      >
        <div className="review-submit-fields">
          <input name="name" aria-label={copy.name} placeholder={copy.name} required />
          <input name="position" aria-label={copy.position} placeholder={copy.position} />
          <input name="company" aria-label={copy.company} placeholder={copy.company} />
        </div>
        <textarea name="text" aria-label={copy.text} placeholder={copy.text} required />
        <div className="review-submit-meta">
          <div className="review-rating" role="radiogroup" aria-label={copy.rating}>
            <span className="mono">{copy.rating}</span>
            {[1, 2, 3, 4, 5].map((value) => (
              <button
                key={value}
                type="button"
                className={value <= rating ? "is-active" : ""}
                aria-label={`${value} / 5`}
                aria-checked={value === rating}
                role="radio"
                onClick={() => setRating(value)}
              >
                ★
              </button>
            ))}
          </div>
          <label className="review-submit-consent">
            <input type="checkbox" name="consent" required />
            <span>{copy.consent}</span>
          </label>
        </div>
        <button className="btn btn-primary" type="submit">
          {copy.submit}
        </button>
      </form>
    </section>
  );
}
