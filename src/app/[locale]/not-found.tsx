import Link from "next/link";

export default function NotFound() {
  return (
    <main className="system-page">
      <span className="mono">[ 404 ]</span>
      <h1>Page not found</h1>
      <p className="muted">The requested page does not exist or has been moved.</p>
      <Link className="btn" href="/uk">
        На головну
      </Link>
    </main>
  );
}
