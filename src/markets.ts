import type { Locale } from "@/i18n";

export type Market = {
  domain: string;
  locale: Locale;
  origin: string;
  enabled: boolean;
  fallbackDomain?: string;
};

const internationalOrigin = trimTrailingSlash(
  process.env.NEXT_PUBLIC_INTL_SITE_URL ??
    process.env.NEXT_PUBLIC_SITE_URL ??
    "https://gvspace.com",
);
const ukrainianOrigin = trimTrailingSlash(
  process.env.NEXT_PUBLIC_UK_SITE_URL ?? "https://gvspace.com.ua",
);

const marketList: Market[] = [
  {
    domain: new URL(internationalOrigin).hostname,
    locale: "en",
    origin: internationalOrigin,
    enabled: true,
  },
  {
    domain: new URL(ukrainianOrigin).hostname,
    locale: "uk",
    origin: ukrainianOrigin,
    enabled: true,
  },
];

export const markets = Object.fromEntries(
  marketList.map((market) => [normalizeHostname(market.domain), market]),
) as Record<string, Market>;

export const localeOrigins = {
  uk: ukrainianOrigin,
  en: internationalOrigin,
} satisfies Record<Locale, string>;

export function normalizeHostname(host: string): string {
  return host
    .toLowerCase()
    .split(":")[0]
    .replace(/^www\./, "");
}

export function getMarket(host: string): Market | undefined {
  return markets[normalizeHostname(host)];
}

export function getLocaleOrigin(locale: Locale): string {
  return localeOrigins[locale];
}

export function getLocalizedUrl(locale: Locale, pathname = ""): string {
  const localePattern = /^\/(uk|en)(?=\/|$)/;
  const localizedPath = localePattern.test(pathname)
    ? pathname.replace(localePattern, `/${locale}`)
    : `/${locale}${pathname === "/" ? "" : pathname}`;

  return `${getLocaleOrigin(locale)}${localizedPath}`;
}

function trimTrailingSlash(value: string): string {
  return value.replace(/\/$/, "");
}
