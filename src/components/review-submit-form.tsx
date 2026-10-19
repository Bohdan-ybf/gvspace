"use client";

import { useState } from "react";
import Link from "next/link";

type ReviewSubmitCopy = {
  title: string;
  description: string;
  detail: string;
  name: string;
  company: string;
  email: string;
  phone: string;
  text: string;
  submit: string;
  privacy: string;
  privacyLink: string;
  success: string;
};

export function ReviewSubmitForm({ copy }: { copy: ReviewSubmitCopy }) {
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
      <div className="review-submit-copy">
        <h2>{copy.title}</h2>
        <p>{copy.description}</p>
        <p>{copy.detail}</p>
      </div>
      <form
        onSubmit={(event) => {
          event.preventDefault();
          setSent(true);
        }}
      >
        <div className="review-submit-fields">
          <input name="name" aria-label={copy.name} placeholder={copy.name} required />
          <input name="company" aria-label={copy.company} placeholder={copy.company} />
          <input
            type="email"
            name="email"
            aria-label={copy.email}
            placeholder={copy.email}
            required
          />
          <input
            type="tel"
            name="phone"
            aria-label={copy.phone}
            placeholder={copy.phone}
            required
          />
        </div>
        <textarea name="text" aria-label={copy.text} placeholder={copy.text} required />
        <div className="review-submit-actions">
          <button className="btn btn-primary" type="submit">
            {copy.submit}
          </button>
          <p>
            {copy.privacy} <Link href="/privacy-policy">{copy.privacyLink}</Link>
          </p>
        </div>
      </form>
    </section>
  );
}
