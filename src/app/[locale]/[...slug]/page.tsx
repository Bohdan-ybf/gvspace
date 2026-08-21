import Link from "next/link";
import type { Metadata } from "next";
import { getDictionary } from "@/i18n";

export const metadata: Metadata = {
  robots: { index: false, follow: false },
};
export default async function Placeholder({
  params,
}: {
  params: Promise<{ locale: string; slug: string[] }>;
}) {
  const { locale, slug } = await params;
  const text = getDictionary(locale);
  return (
    <main
      style={{
        minHeight: "100vh",
        display: "grid",
        placeItems: "center",
        textAlign: "center",
        padding: 24,
      }}
    >
      <div>
        <span className="mono">[ {text.placeholder.label} ]</span>
        <h1 style={{ fontSize: "clamp(40px,7vw,80px)", margin: "20px 0" }}>{slug.join(" / ")}</h1>
        <p className="muted">{text.placeholder.description}</p>
        <Link className="btn" style={{ marginTop: 28 }} href={`/${locale}`}>
          ← {text.common.backHome}
        </Link>
      </div>
    </main>
  );
}
