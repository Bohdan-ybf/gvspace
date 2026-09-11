import type { Locale } from "@/i18n";
import {
  filterPublishedForLocale,
  getPublicContentSlug,
  type ContentLocalization,
} from "@/content-localization";

export type ServiceStep = { title: string; duration: string; description: string };
export type ServiceOffering = {
  id: number;
  slug: string;
  parentSlug?: string;
  title: string;
  headline: string;
  description: string;
  image?: string;
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
    headline?: string;
    description?: string;
    includes?: string[];
    steps?: ServiceStep[];
    faq?: ServiceOffering["faq"];
    titleEn?: string;
    headlineUk?: string;
    headlineEn?: string;
    descriptionUk?: string;
    descriptionEn?: string;
    includesUk?: string[];
    includesEn?: string[];
    stepsUk?: ServiceStep[];
    stepsEn?: ServiceStep[];
    metrics?: string[];
    faqUk?: ServiceOffering["faq"];
    faqEn?: ServiceOffering["faq"];
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
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query Services { serviceOfferings(first: 100) { nodes { databaseId slug title modified menuOrder gvspaceLocalization { locale translationGroup status } parent { node { slug ... on ServiceOffering { gvspaceLocalization { locale translationGroup status } } } } featuredImage { node { sourceUrl } } serviceDetails { headline description includes steps { title duration description } faq { question answer } titleEn headlineUk headlineEn descriptionUk descriptionEn includesUk includesEn stepsUk { title duration description } stepsEn { title duration description } metrics faqUk { question answer } faqEn { question answer } } } } }`,
      }),
      next: { revalidate: 10 },
    });
    if (!response.ok) return fallback;
    const json = (await response.json()) as {
      data?: { serviceOfferings?: { nodes?: Node[] } };
    };
    const nodes = json.data?.serviceOfferings?.nodes ?? [];
    if (!nodes.length) return fallback;
    return filterPublishedForLocale(nodes, locale)
      .sort((a, b) => (a.menuOrder ?? 0) - (b.menuOrder ?? 0))
      .map((node) => {
        const d = node.serviceDetails ?? {};
        const en = locale === "en";
        const localized = Boolean(
          node.gvspaceLocalization?.locale && node.gvspaceLocalization.locale !== "legacy",
        );
        return {
          id: node.databaseId,
          slug: getPublicContentSlug(node.slug, node.gvspaceLocalization),
          parentSlug: node.parent?.node?.slug
            ? getPublicContentSlug(node.parent.node.slug, node.parent.node.gvspaceLocalization)
            : undefined,
          title: localized ? node.title : en && d.titleEn ? d.titleEn : node.title,
          headline: localized
            ? (d.headline ?? node.title)
            : (en ? d.headlineEn : d.headlineUk) || (en && d.titleEn ? d.titleEn : node.title),
          description: localized
            ? (d.description ?? "")
            : ((en ? d.descriptionEn : d.descriptionUk) ?? ""),
          image: node.featuredImage?.node?.sourceUrl,
          includes: localized ? (d.includes ?? []) : ((en ? d.includesEn : d.includesUk) ?? []),
          steps: localized ? (d.steps ?? []) : ((en ? d.stepsEn : d.stepsUk) ?? []),
          metrics: d.metrics ?? [],
          faq: localized ? (d.faq ?? []) : ((en ? d.faqEn : d.faqUk) ?? []),
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
