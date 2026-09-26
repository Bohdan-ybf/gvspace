import type { Locale } from "@/i18n";
import {
  filterPublishedForLocale,
  getPublicContentSlug,
  type ContentLocalization,
} from "@/content-localization";

export type ServiceStep = { title: string; duration: string; description: string };
export type ServiceFitCard = { label: string; title: string; description: string };
export type ServiceOffering = {
  id: number;
  slug: string;
  parentSlug?: string;
  title: string;
  headline: string;
  description: string;
  image?: string;
  fitCards: ServiceFitCard[];
  includes: string[];
  steps: ServiceStep[];
  metrics: string[];
  faq: Array<{ question: string; answer: string }>;
  modifiedAt?: string;
};

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;
const fallback: ServiceOffering[] = [
  [
    "strategy",
    "Стратегія",
    "Strategy",
    "Системна стратегія керованого зростання",
    "System strategy for managed growth",
  ],
  [
    "marketing",
    "Маркетинг",
    "Marketing",
    "Маркетинг, який перетворює трафік на капітал",
    "Marketing that turns traffic into capital",
  ],
  [
    "development",
    "IT-розробка",
    "IT Development",
    "Цифрові системи для масштабування",
    "Digital systems built to scale",
  ],
  [
    "content",
    "Контент & Продакшн",
    "Content & Production",
    "Контент, який формує довіру",
    "Content that builds trust",
  ],
].map(
  (row, index) =>
    ({
      id: index + 1,
      slug: row[0],
      title: row[1],
      headline: row[3],
      description: "",
      fitCards: [],
      includes: [],
      steps: [],
      metrics: [],
      faq: [],
      _enTitle: row[2],
      _enHeadline: row[4],
    }) as ServiceOffering & { _enTitle: string; _enHeadline: string },
);

type Node = {
  databaseId: number;
  slug: string;
  title: string;
  modified?: string;
  menuOrder?: number;
  parent?: {
    node?: { slug?: string; gvspaceLocalization?: ContentLocalization | null };
  };
  featuredImage?: { node?: { sourceUrl?: string } };
  serviceDetails?: {
    title?: string;
    order?: number;
    headline?: string;
    description?: string;
    fitCards?: ServiceFitCard[];
    includes?: string[];
    steps?: ServiceStep[];
    faq?: ServiceOffering["faq"];
    metrics?: string[];
  };
  gvspaceLocalization?: ContentLocalization | null;
};

export async function getServiceOfferings(locale: Locale): Promise<ServiceOffering[]> {
  if (!endpoint)
    return fallback.map((item) =>
      locale === "en"
        ? {
            ...item,
            title: (item as ServiceOffering & { _enTitle: string })._enTitle,
            headline: (item as ServiceOffering & { _enHeadline: string })._enHeadline,
          }
        : item,
    );
  try {
    const nodes: Node[] = [];
    let after: string | null = null;

    for (let page = 0; page < 20; page += 1) {
      const response = await fetch(endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          query: `query Services($locale: String!, $after: String) { serviceOfferings(first: 100, after: $after) { pageInfo { hasNextPage endCursor } nodes { databaseId slug title modified menuOrder gvspaceLocalization { locale translationGroup status } parent { node { slug ... on ServiceOffering { gvspaceLocalization { locale translationGroup status } } } } featuredImage { node { sourceUrl } } serviceDetails(locale: $locale) { title headline description order fitCards { label title description } includes steps { title duration description } faq { question answer } metrics } } } }`,
          variables: { locale, after },
        }),
        next: { revalidate: 10 },
      });
      if (!response.ok) break;
      const json = (await response.json()) as {
        data?: {
          serviceOfferings?: {
            pageInfo?: { hasNextPage?: boolean; endCursor?: string | null };
            nodes?: Node[];
          };
        };
      };
      const connection = json.data?.serviceOfferings;
      const pageNodes = connection?.nodes ?? [];
      nodes.push(...pageNodes);
      if (
        !connection?.pageInfo?.hasNextPage ||
        !connection.pageInfo.endCursor ||
        !pageNodes.length
      ) {
        break;
      }
      after = connection.pageInfo.endCursor;
    }

    if (!nodes.length) return fallback;
    return filterPublishedForLocale(nodes, locale)
      .sort(
        (a, b) =>
          (a.serviceDetails?.order ?? a.menuOrder ?? 0) -
          (b.serviceDetails?.order ?? b.menuOrder ?? 0),
      )
      .map((node) => {
        const d = node.serviceDetails ?? {};
        return {
          id: node.databaseId,
          slug: getPublicContentSlug(node.slug, node.gvspaceLocalization),
          parentSlug: node.parent?.node?.slug
            ? getPublicContentSlug(node.parent.node.slug, node.parent.node.gvspaceLocalization)
            : undefined,
          title: d.title || node.title,
          headline: d.headline || d.title || node.title,
          description: d.description || "",
          image: node.featuredImage?.node?.sourceUrl,
          fitCards: d.fitCards ?? [],
          includes: d.includes ?? [],
          steps: d.steps ?? [],
          metrics: d.metrics ?? [],
          faq: d.faq ?? [],
          modifiedAt: node.modified,
        };
      });
  } catch {
    return fallback;
  }
}

export async function getServiceOffering(locale: Locale, slugs: string[]) {
  const items = await getServiceOfferings(locale);
  const item = items.find((entry) => entry.slug === slugs.at(-1));
  if (!item) return undefined;
  if (slugs.length === 2 && item.parentSlug !== slugs[0]) return undefined;
  return { item, children: items.filter((entry) => entry.parentSlug === item.slug) };
}
