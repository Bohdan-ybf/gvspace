import { en } from "./en";
import { uk, type Messages } from "./uk";

export const locales = ["uk", "en"] as const;
export type Locale = (typeof locales)[number];
export const defaultLocale: Locale = "uk";

export const localeNames = {
  uk: "Українська",
  en: "English",
} satisfies Record<Locale, string>;

const dictionaries: Record<Locale, Messages> = { uk, en };

export function isLocale(value: string): value is Locale {
  return locales.includes(value as Locale);
}

export function getDictionary(locale: Locale): Messages {
  return dictionaries[locale];
}
