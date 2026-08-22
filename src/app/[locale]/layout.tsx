import type { Metadata } from "next";
import { notFound } from "next/navigation";
import { isLocale, locales } from "@/i18n";
import { SiteShell } from "@/components/site-shell";
import "../globals.css";
import { geistMono, geistSans } from "../fonts";

const siteUrl = process.env.NEXT_PUBLIC_SITE_URL || "https://gvspace.com";

export const metadata: Metadata = {
  metadataBase: new URL(siteUrl),
  title: { default: "GVSPACE — простір вашого масштабування", template: "%s | GVSPACE" },
  description: "Проєктуємо керовані системи маркетингу, IT та стратегії для масштабування бізнесу.",
  applicationName: "GVSPACE",
  authors: [{ name: "GVSPACE", url: siteUrl }],
  creator: "GVSPACE",
  publisher: "GVSPACE",
  robots: { index: true, follow: true },
  openGraph: {
    title: "GVSPACE",
    description: "Простір вашого масштабування",
    siteName: "GVSPACE",
    url: siteUrl,
    locale: "uk_UA",
    type: "website",
  },
  twitter: {
    card: "summary_large_image",
    title: "GVSPACE",
    description: "Простір вашого масштабування",
  },
  icons: {
    icon: [{ url: "/icon.svg", type: "image/svg+xml" }],
    shortcut: "/icon.svg",
  },
};

export function generateStaticParams() {
  return locales.map((locale) => ({ locale }));
}

export default async function LocaleLayout({
  children,
  params,
}: Readonly<{
  children: React.ReactNode;
  params: Promise<{ locale: string }>;
}>) {
  const { locale } = await params;
  if (!isLocale(locale)) notFound();

  return (
    <html lang={locale}>
      <body suppressHydrationWarning className={`${geistSans.variable} ${geistMono.variable}`}>
        <SiteShell locale={locale}>{children}</SiteShell>
      </body>
    </html>
  );
}
