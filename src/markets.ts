import { isLocale, type Locale } from "@/i18n";

export type ContentLocale =
  "uk" | "en" | "pl" | "de-DE" | "de-AT" | "es" | "fr" | "it" | "nl" | "cs" | "sk" | "en-GB";

export type MarketId =
  | "international"
  | "ukraine"
  | "ukraine-short"
  | "poland"
  | "germany"
  | "austria"
  | "spain"
  | "france"
  | "italy"
  | "netherlands"
  | "czechia"
  | "slovakia"
  | "united-kingdom";

export type Market = {
  id: MarketId;
  domain: string;
  contentLocale: ContentLocale;
  /** Internal Next.js locale. Null means its frontend dictionaries are not ready. */
  routeLocale: Locale | null;
  enabled: boolean;
  fallbackMarket?: MarketId;
  origin: string;
};

const internationalOrigin = trimTrailingSlash(
  process.env.NEXT_PUBLIC_INTL_SITE_URL ??
    process.env.NEXT_PUBLIC_SITE_URL ??
    "https://gvspace.com",
);
const ukrainianOrigin = trimTrailingSlash(
  process.env.NEXT_PUBLIC_UK_SITE_URL ?? "https://gvspace.com.ua",
);

const configuredMarkets = [
  market("international", internationalOrigin, "en", "en", true),
  market("ukraine", ukrainianOrigin, "uk", "uk", true),
  market("ukraine-short", "https://gvspace.ua", "uk", null, false, "ukraine"),
  market("poland", "https://gvspace.pl", "pl", null, false, "international"),
  market("germany", "https://gvspace.de", "de-DE", null, false, "international"),
  market("austria", "https://gvspace.at", "de-AT", null, false, "international"),
  market("spain", "https://gvspace.es", "es", null, false, "international"),
  market("france", "https://gvspace.fr", "fr", null, false, "international"),
  market("italy", "https://gvspace.it", "it", null, false, "international"),
  market("netherlands", "https://gvspace.nl", "nl", null, false, "international"),
  market("czechia", "https://gvspace.cz", "cs", null, false, "international"),
  market("slovakia", "https://gvspace.sk", "sk", null, false, "international"),
  market("united-kingdom", "https://gvspace.uk", "en-GB", null, false, "international"),
] as const satisfies readonly Market[];

export const marketList: readonly Market[] = configuredMarkets;

export const markets = Object.fromEntries(
  marketList.map((entry) => [normalizeHostname(entry.domain), entry]),
) as Record<string, Market>;

const marketsById = Object.fromEntries(marketList.map((entry) => [entry.id, entry])) as Record<
  MarketId,
  Market
>;

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

export function getMarketById(id: MarketId): Market {
  return marketsById[id];
}

export function getFallbackMarket(entry: Market): Market {
  return entry.fallbackMarket ? getMarketById(entry.fallbackMarket) : entry;
}

export function getEnabledMarkets(): Array<Market & { routeLocale: Locale }> {
  return marketList.filter(
    (entry): entry is Market & { routeLocale: Locale } =>
      entry.enabled && entry.routeLocale !== null,
  );
}

export function validateMarketConfiguration(): void {
  const domains = new Set<string>();

  for (const entry of marketList) {
    const normalizedDomain = normalizeHostname(entry.domain);
    if (domains.has(normalizedDomain)) {
      throw new Error(`Duplicate market domain: ${normalizedDomain}`);
    }
    domains.add(normalizedDomain);

    if (entry.enabled && (!entry.routeLocale || !isLocale(entry.routeLocale))) {
      throw new Error(
        `Market ${entry.id} is enabled, but its static dictionaries are not registered`,
      );
    }

    if (entry.fallbackMarket) {
      const fallback = marketsById[entry.fallbackMarket];
      if (!fallback || !fallback.enabled || !fallback.routeLocale) {
        throw new Error(`Market ${entry.id} has an unavailable fallback: ${entry.fallbackMarket}`);
      }
      if (fallback.id === entry.id) {
        throw new Error(`Market ${entry.id} cannot fall back to itself`);
      }
    }
  }
}

export function getLocaleOrigin(locale: Locale): string {
  return localeOrigins[locale];
}

export function getLocalizedUrl(locale: Locale, pathname = ""): string {
  const localePattern = /^\/(uk|en)(?=\/|$)/;
  const localizedPath = pathname.replace(localePattern, "") || "/";

  return `${getLocaleOrigin(locale)}${localizedPath}`;
}

function trimTrailingSlash(value: string): string {
  return value.replace(/\/$/, "");
}

function market(
  id: MarketId,
  origin: string,
  contentLocale: ContentLocale,
  routeLocale: Locale | null,
  enabled: boolean,
  fallbackMarket?: MarketId,
): Market {
  const normalizedOrigin = trimTrailingSlash(origin);
  return {
    id,
    domain: new URL(normalizedOrigin).hostname,
    contentLocale,
    routeLocale,
    enabled,
    fallbackMarket,
    origin: normalizedOrigin,
  };
}

validateMarketConfiguration();
