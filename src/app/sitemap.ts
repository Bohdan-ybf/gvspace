import type { MetadataRoute } from "next";
import { headers } from "next/headers";
import { getBlogPosts } from "@/components/wordpress-posts";
import { getCaseStudies } from "@/components/wordpress-cases";
import { getServiceOfferings } from "@/components/wordpress-services";
import { getVacancies } from "@/components/wordpress-vacancies";
import type { Locale } from "@/i18n";
import { getEnabledMarkets, getMarket, getMarketById } from "@/markets";

export const dynamic = "force-dynamic";

const staticPaths = [
  "/",
  "/services",
  "/cases",
  "/reviews",
  "/about",
  "/team",
  "/careers",
  "/technologies",
  "/blog",
  "/contacts",
] as const;

type SitemapEntry = { pathname: string; lastModified?: string };

async function getLocalePaths(locale: Locale): Promise<SitemapEntry[]> {
  const [posts, cases, vacancies, services] = await Promise.all([
    getBlogPosts(locale),
    getCaseStudies(locale),
    getVacancies(locale),
    getServiceOfferings(locale),
  ]);
  return [
    ...staticPaths.map((pathname) => ({ pathname })),
    ...posts.map((post) => ({
      pathname: `/blog/${post.slug}`,
      lastModified: post.modifiedAt,
    })),
    ...cases.map((item) => ({
      pathname: `/cases/${item.slug}`,
      lastModified: item.modifiedAt,
    })),
    ...vacancies.map((vacancy) => ({
      pathname: `/careers/${vacancy.slug}`,
      lastModified: vacancy.modifiedAt,
    })),
    ...services.map((service) => ({
      pathname: service.parentSlug
        ? `/services/${service.parentSlug}/${service.slug}`
        : `/services/${service.slug}`,
      lastModified: service.modifiedAt,
    })),
  ];
}

export default async function sitemap(): Promise<MetadataRoute.Sitemap> {
  if (process.env.SITE_INDEXING_ENABLED !== "true") return [];

  const enabledMarkets = getEnabledMarkets();
  const requestHeaders = await headers();
  const requestedMarket = getMarket(requestHeaders.get("host") ?? "");
  const currentMarket =
    requestedMarket?.enabled && requestedMarket.routeLocale
      ? requestedMarket
      : getMarketById("international");
  if (!currentMarket.routeLocale) return [];

  const pathsByLocale = new Map(
    await Promise.all(
      enabledMarkets.map(
        async (market) =>
          [
            market.routeLocale,
            new Map(
              (await getLocalePaths(market.routeLocale)).map((entry) => [entry.pathname, entry]),
            ),
          ] as const,
      ),
    ),
  );

  const currentPaths =
    pathsByLocale.get(currentMarket.routeLocale) ?? new Map<string, SitemapEntry>();
  return [...currentPaths.values()].map(({ pathname, lastModified }) => ({
    url: `${currentMarket.origin}${pathname === "/" ? "" : pathname}`,
    ...(lastModified ? { lastModified } : {}),
    changeFrequency: "weekly" as const,
    priority: pathname === "/" ? 1 : 0.7,
    alternates: {
      languages: Object.fromEntries([
        ...enabledMarkets
          .filter((market) => pathsByLocale.get(market.routeLocale)?.has(pathname))
          .map((market) => [
            market.contentLocale,
            `${market.origin}${pathname === "/" ? "" : pathname}`,
          ]),
        [
          "x-default",
          `${getMarketById("international").origin}${pathname === "/" ? "" : pathname}`,
        ],
      ]),
    },
  }));
}
