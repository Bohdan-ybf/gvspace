import type { Locale } from "@/i18n";
import { filterPublishedForLocale, type ContentLocalization } from "@/content-localization";

export type TeamCategory = { name: string; slug: string };
export type TeamMember = {
  id: number;
  name: string;
  role: string;
  tags: string[];
  years: string;
  projects: string;
  categorySlugs: string[];
  image?: string;
  imageAlt: string;
  order: number;
};
export type TeamDirectory = { categories: TeamCategory[]; members: TeamMember[] };

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;
const categoryOrder = ["core-team", "strategy", "marketing", "development", "content"];
const fallbackCategories: TeamCategory[] = categoryOrder.map((slug) => ({
  slug,
  name: slug.replace("-", " ").toUpperCase(),
}));
type TeamNode = {
  databaseId: number;
  title: string;
  menuOrder?: number;
  featuredImage?: { node?: { sourceUrl?: string; altText?: string } };
  teamMemberDetails?: {
    name?: string;
    role?: string;
    tags?: string[];
    years?: string;
    projects?: string;
  };
  teamMemberCategories?: { nodes?: TeamCategory[] };
  gvspaceLocalization?: ContentLocalization | null;
};

function sortCategories(categories: TeamCategory[]) {
  return [...categories].sort((a, b) => {
    const ai = categoryOrder.indexOf(a.slug);
    const bi = categoryOrder.indexOf(b.slug);
    if (ai === -1 && bi === -1) return a.name.localeCompare(b.name);
    if (ai === -1) return 1;
    if (bi === -1) return -1;
    return ai - bi;
  });
}

export async function getTeamDirectory(locale: Locale): Promise<TeamDirectory> {
  if (!endpoint) return { categories: fallbackCategories, members: [] };
  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query TeamDirectory {
        teamMemberCategories(first: 100, where: { hideEmpty: false }) { nodes { name slug } }
        teamMembers(first: 100) { nodes {
          databaseId title menuOrder gvspaceLocalization { locale translationGroup status }
          featuredImage { node { sourceUrl altText } }
          teamMemberDetails(locale: "${locale}") { name role tags years projects }
          teamMemberCategories { nodes { name slug } }
        } }
      }`,
      }),
      next: { revalidate: 60 },
    });
    if (!response.ok) throw new Error("Team request failed");
    const result = (await response.json()) as {
      data?: {
        teamMembers?: { nodes?: TeamNode[] };
        teamMemberCategories?: { nodes?: TeamCategory[] };
      };
      errors?: unknown[];
    };
    if (result.errors) throw new Error("Team query failed");
    const members = filterPublishedForLocale(result.data?.teamMembers?.nodes ?? [], locale)
      .map((node): TeamMember => ({
        id: node.databaseId,
        name: node.teamMemberDetails?.name || node.title,
        role: node.teamMemberDetails?.role ?? "",
        tags: node.teamMemberDetails?.tags ?? [],
        years: node.teamMemberDetails?.years ?? "",
        projects: node.teamMemberDetails?.projects ?? "",
        categorySlugs: node.teamMemberCategories?.nodes?.map(({ slug }) => slug) ?? [],
        image: node.featuredImage?.node?.sourceUrl,
        imageAlt: node.featuredImage?.node?.altText || node.teamMemberDetails?.name || node.title,
        order: node.menuOrder ?? 0,
      }))
      .sort((a, b) => a.order - b.order || a.id - b.id);
    return {
      categories: sortCategories(result.data?.teamMemberCategories?.nodes ?? fallbackCategories),
      members,
    };
  } catch {
    return { categories: fallbackCategories, members: [] };
  }
}
