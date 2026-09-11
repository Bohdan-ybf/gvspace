import type { Locale } from "@/i18n";
import {
  getPublicContentSlug,
  isContentPublishedForLocale,
  type ContentLocalization,
} from "@/content-localization";
import { normalizeSeoData, seoGraphqlFields, type SeoData, type SeoGraphqlData } from "@/seo";

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

export type DynamicSeoKind = "blog" | "case" | "vacancy" | "service";

type SeoNode = {
  slug: string;
  title: string;
  excerpt?: string;
  gvspaceLocalization?: ContentLocalization | null;
  gvspaceSeo?: SeoGraphqlData;
};

const roots: Record<DynamicSeoKind, string> = {
  blog: "posts(first: 100, where: { status: PUBLISH })",
  case: "projectCases(first: 100)",
  vacancy: "vacancies(first: 100)",
  service: "serviceOfferings(first: 100)",
};

export async function getDynamicSeo(
  kind: DynamicSeoKind,
  publicSlug: string,
  locale: Locale,
): Promise<SeoData | undefined> {
  if (!endpoint) return undefined;

  try {
    const excerptField = kind === "blog" ? "excerpt" : "";
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query RouteSeo($locale: String!) {
          items: ${roots[kind]} {
            nodes {
              slug
              title
              ${excerptField}
              gvspaceLocalization { locale translationGroup status }
              gvspaceSeo(locale: $locale) { ${seoGraphqlFields} }
            }
          }
        }`,
        variables: { locale },
      }),
      next: { revalidate: 60 },
    });
    if (!response.ok) return undefined;
    const result = (await response.json()) as {
      data?: { items?: { nodes?: SeoNode[] } };
      errors?: unknown[];
    };
    if (result.errors) return undefined;

    const node = result.data?.items?.nodes?.find(
      (candidate) =>
        isContentPublishedForLocale(candidate.gvspaceLocalization, locale) &&
        getPublicContentSlug(candidate.slug, candidate.gvspaceLocalization) === publicSlug,
    );
    if (!node) return undefined;

    return normalizeSeoData(node.gvspaceSeo, {
      title: node.title,
      description:
        node.excerpt
          ?.replace(/<[^>]*>/g, " ")
          .replace(/\s+/g, " ")
          .trim() || "",
    });
  } catch {
    return undefined;
  }
}

export async function getPublishedSeoLocales(
  kind: DynamicSeoKind,
  publicSlug: string,
  locales: readonly Locale[],
): Promise<Locale[]> {
  const results = await Promise.all(
    locales.map(async (locale) => ({ locale, seo: await getDynamicSeo(kind, publicSlug, locale) })),
  );
  return results.filter(({ seo }) => Boolean(seo)).map(({ locale }) => locale);
}
