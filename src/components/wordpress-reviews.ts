import type { Locale } from "@/i18n";

export type ClientReview = {
  slug: string;
  name: string;
  position: string;
  company: string;
  text: string;
  category: string;
  rating: number;
  metrics: string[];
  image?: string;
};

type ReviewNode = {
  slug: string;
  title: string;
  featuredImage?: { node?: { sourceUrl?: string } };
  reviewDetails?: {
    nameEn?: string;
    positionUk?: string;
    positionEn?: string;
    company?: string;
    textUk?: string;
    textEn?: string;
    category?: string;
    rating?: number;
    metrics?: string[];
  };
};

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

export async function getClientReviews(locale: Locale): Promise<ClientReview[]> {
  if (!endpoint) return [];
  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query Reviews { clientReviews(first: 100) { nodes { slug title featuredImage { node { sourceUrl } } reviewDetails { nameEn positionUk positionEn company textUk textEn category rating metrics } } } }`,
      }),
      next: { revalidate: 60 },
    });
    if (!response.ok) return [];
    const result = (await response.json()) as {
      data?: { clientReviews?: { nodes?: ReviewNode[] } };
    };
    return (result.data?.clientReviews?.nodes ?? []).flatMap((node) => {
      const details = node.reviewDetails;
      if (!details) return [];
      return [{
        slug: node.slug,
        name: locale === "en" && details.nameEn ? details.nameEn : node.title,
        position: locale === "en" ? details.positionEn ?? "" : details.positionUk ?? "",
        company: details.company ?? "",
        text: locale === "en" ? details.textEn ?? "" : details.textUk ?? "",
        category: details.category ?? "",
        rating: details.rating ?? 5,
        metrics: details.metrics ?? [],
        image: node.featuredImage?.node?.sourceUrl,
      }];
    });
  } catch {
    return [];
  }
}
