"use client";

export default function GlobalError({ reset }: { reset: () => void }) {
  return (
    <html lang="en">
      <body>
        <main className="system-page">
          <h1>Something went wrong</h1>
          <button className="btn" type="button" onClick={reset}>
            Try again
          </button>
        </main>
      </body>
    </html>
  );
}
