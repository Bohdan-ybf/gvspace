import type { Locale } from "@/i18n";

export type ContentLocalization = {
  locale?: string | null;
  translationGroup?: string | null;
  status?: string | null;
};

export type LocalizedContent = {
  gvspaceLocalization?: ContentLocalization | null;
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

export function filterPublishedForLocale<T extends LocalizedContent>(
  items: T[],
  locale: Locale,
): T[] {
  return items.filter((item) => isContentPublishedForLocale(item.gvspaceLocalization, locale));
}

/**
 * Single-language translations can have different WordPress slugs because
 * WordPress requires them to be unique. Their shared translation group is the
 * stable public slug used on every market domain.
 */
export function getPublicContentSlug(
  wordpressSlug: string,
  localization: ContentLocalization | null | undefined,
): string {
  const locale = localization?.locale || "legacy";
  const translationGroup = localization?.translationGroup?.trim();
  return locale !== "legacy" && translationGroup ? translationGroup : wordpressSlug;
}
