import { en } from "./en";
import { uk, type Messages } from "./uk";

export const locales = ["uk", "en"] as const;
export type Locale = (typeof locales)[number];

const dictionaries: Record<Locale, Messages> = { uk, en };

export function isLocale(value: string): value is Locale {
  return locales.includes(value as Locale);
}

export function getDictionary(locale: string): Messages {
  return dictionaries[isLocale(locale) ? locale : "uk"];
}
