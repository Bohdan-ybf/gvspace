"use client";

export default function ErrorPage({ reset }: { reset: () => void }) {
  return (
    <main className="system-page">
      <span className="mono">[ ERROR ]</span>
      <h1>Something went wrong</h1>
      <p className="muted">Please try loading this page again.</p>
      <button className="btn" type="button" onClick={reset}>
        Try again
      </button>
    </main>
  );
}
