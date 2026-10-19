import { createLanguageSitemap } from "@/sitemap-response";

export const dynamic = "force-dynamic";

export function GET() {
  return createLanguageSitemap("international");
}
