import type { Locale } from "@/i18n";

export type ContentLocalization = {
  locale?: string | null;
  translationGroup?: string | null;
  status?: string | null;
};

export function isContentPublishedForLocale(
  localization: ContentLocalization | null | undefined,
  locale: Locale,
): boolean {
  // Records created before localization was introduced are bilingual and remain visible.
  const contentLocale = localization?.locale || "legacy";
  const status = localization?.status || "published";
  return status === "published" && (contentLocale === "legacy" || contentLocale === locale);
}
