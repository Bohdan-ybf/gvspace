import type { Locale } from "@/i18n";

export type TechnologyCategory = { name: string; slug: string };

export type TechnologyItem = {
  id: number;
  title: string;
  image?: string;
  imageAlt: string;
  categorySlugs: string[];
  order: number;
};

export type TechnologyStack = {
  categories: TechnologyCategory[];
  items: TechnologyItem[];
};

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;
const preferredCategoryOrder = ["marketing", "development", "systems", "content"];
const fallbackCategories: TechnologyCategory[] = preferredCategoryOrder.map((slug) => ({
  slug,
  name: slug.toUpperCase(),
}));

type TechnologyNode = {
  databaseId: number;
  title: string;
  menuOrder?: number;
  technologyTitleEn?: string;
  featuredImage?: { node?: { sourceUrl?: string; altText?: string } };
  technologyCategories?: { nodes?: TechnologyCategory[] };
};

type TechnologyResponse = {
  data?: {
    technologies?: { nodes?: TechnologyNode[] };
    technologyCategories?: { nodes?: TechnologyCategory[] };
  };
  errors?: unknown[];
};

function sortCategories(categories: TechnologyCategory[]) {
  return [...categories].sort((a, b) => {
    const aIndex = preferredCategoryOrder.indexOf(a.slug);
    const bIndex = preferredCategoryOrder.indexOf(b.slug);
    if (aIndex === -1 && bIndex === -1) return a.name.localeCompare(b.name);
    if (aIndex === -1) return 1;
    if (bIndex === -1) return -1;
    return aIndex - bIndex;
  });
}

export async function getTechnologyStack(locale: Locale): Promise<TechnologyStack> {
  if (!endpoint) return { categories: fallbackCategories, items: [] };

  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query TechnologyStack {
          technologyCategories(first: 100, where: { hideEmpty: false }) {
            nodes { name slug }
          }
          technologies(first: 100) {
            nodes {
              databaseId title menuOrder technologyTitleEn
              featuredImage { node { sourceUrl altText } }
              technologyCategories { nodes { name slug } }
            }
          }
        }`,
      }),
      next: { revalidate: 60 },
    });

    if (!response.ok) return { categories: fallbackCategories, items: [] };
    const result = (await response.json()) as TechnologyResponse;
    if (result.errors) return { categories: fallbackCategories, items: [] };

    const categories = result.data?.technologyCategories?.nodes ?? [];
    const items = (result.data?.technologies?.nodes ?? [])
      .map((node): TechnologyItem => ({
        id: node.databaseId,
        title: locale === "en" && node.technologyTitleEn ? node.technologyTitleEn : node.title,
        image: node.featuredImage?.node?.sourceUrl,
        imageAlt: node.featuredImage?.node?.altText || node.title,
        categorySlugs: node.technologyCategories?.nodes?.map(({ slug }) => slug) ?? [],
        order: node.menuOrder ?? 0,
      }))
      .sort((a, b) => a.order - b.order || a.id - b.id);

    return {
      categories: sortCategories(categories.length ? categories : fallbackCategories),
      items,
    };
  } catch {
    return { categories: fallbackCategories, items: [] };
  }
}
