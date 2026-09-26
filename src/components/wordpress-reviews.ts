import type { Locale } from "@/i18n";
import {
  filterPublishedForLocale,
  getPublicContentSlug,
  type ContentLocalization,
} from "@/content-localization";

export type ClientReview = {
  slug: string;
  name: string;
  position: string;
  company: string;
  text: string;
  tags: string[];
  tagLabel: string;
  category: string;
  rating: number;
  metrics: string[];
  image?: string;
  logo?: string;
  order: number;
};

type ReviewNode = {
  slug: string;
  title: string;
  menuOrder?: number | null;
  featuredImage?: { node?: { sourceUrl?: string } };
  reviewDetails?: {
    name?: string;
    position?: string;
    text?: string;
    company?: string;
    tags?: string[];
    tagLabel?: string;
    category?: string;
    rating?: number;
    metrics?: string[];
    logo?: string;
  };
  gvspaceLocalization?: ContentLocalization | null;
};

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

const tagAliases: Record<string, string[]> = {
  systems: ["systems", "development"],
  development: ["development", "systems"],
};

export function reviewMatchesTags(review: ClientReview, tags: string[]): boolean {
  if (!tags.length) return true;
  const wanted = new Set(
    tags.flatMap((tag) => tagAliases[tag.toLowerCase()] ?? [tag]).map((tag) => tag.toLowerCase()),
  );
  return review.tags.some((tag) => wanted.has(tag.toLowerCase()));
}

export async function getClientReviews(locale: Locale): Promise<ClientReview[]> {
  if (!endpoint) return [];
  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query Reviews($locale: String!) { clientReviews(first: 100) { nodes { slug title menuOrder gvspaceLocalization { locale translationGroup status } featuredImage { node { sourceUrl } } reviewDetails(locale: $locale) { name position company text tags tagLabel category rating metrics logo } } } }`,
        variables: { locale },
      }),
      next: { revalidate: 60 },
    });
    if (!response.ok) return [];
    const result = (await response.json()) as {
      data?: { clientReviews?: { nodes?: ReviewNode[] } };
      errors?: unknown[];
    };
    if (result.errors || !result.data?.clientReviews) return [];
    return filterPublishedForLocale(result.data.clientReviews.nodes ?? [], locale)
      .flatMap((node) => {
        const details = node.reviewDetails;
        if (!details?.text) return [];
        const tags = (details.tags ?? []).filter(Boolean);
        const category = details.category || tags[0] || "";
        return [
          {
            slug: getPublicContentSlug(node.slug, node.gvspaceLocalization),
            name: details.name || node.title,
            position: details.position ?? "",
            company: details.company ?? "",
            text: details.text,
            tags: tags.length ? tags : category ? [category] : [],
            tagLabel: details.tagLabel || "",
            category,
            rating: details.rating ?? 5,
            metrics: details.metrics ?? [],
            image: node.featuredImage?.node?.sourceUrl,
            logo: details.logo || undefined,
            order: node.menuOrder ?? 0,
          },
        ];
      })
      .sort((left, right) => left.order - right.order || left.name.localeCompare(right.name));
  } catch {
    return [];
  }
}
