import type { Metadata } from "next";
import "../globals.css";
import { geistMono, geistSans } from "../fonts";

export const metadata: Metadata = {
  metadataBase: new URL(process.env.NEXT_PUBLIC_SITE_URL || "https://gvspace.com"),
  robots: { index: false, follow: true },
};

export default function RedirectLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return (
    <html lang="uk">
      <body suppressHydrationWarning className={`${geistSans.variable} ${geistMono.variable}`}>
        {children}
      </body>
    </html>
  );
}
