import type { Locale } from "@/i18n";

export type Partner = {
  id: number;
  name: string;
  direction: string;
  image?: string;
  imageAlt: string;
  order: number;
};

type PartnerNode = {
  databaseId: number;
  title: string;
  menuOrder?: number;
  featuredImage?: { node?: { sourceUrl?: string; altText?: string } };
  partnerDetails?: { directionUk?: string; directionEn?: string };
};

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

export async function getPartners(locale: Locale): Promise<Partner[]> {
  if (!endpoint) return [];

  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query Partners {
          partners(first: 100) {
            nodes {
              databaseId title menuOrder
              featuredImage { node { sourceUrl altText } }
              partnerDetails { directionUk directionEn }
            }
          }
        }`,
      }),
      next: { revalidate: 60 },
    });

    if (!response.ok) return [];
    const result = (await response.json()) as {
      data?: { partners?: { nodes?: PartnerNode[] } };
      errors?: unknown[];
    };
    if (result.errors) return [];

    return (result.data?.partners?.nodes ?? [])
      .map((node) => ({
        id: node.databaseId,
        name: node.title,
        direction:
          (locale === "en" ? node.partnerDetails?.directionEn : node.partnerDetails?.directionUk) ??
          "",
        image: node.featuredImage?.node?.sourceUrl,
        imageAlt: node.featuredImage?.node?.altText || node.title,
        order: node.menuOrder ?? 0,
      }))
      .sort((a, b) => a.order - b.order || a.id - b.id);
  } catch {
    return [];
  }
}
