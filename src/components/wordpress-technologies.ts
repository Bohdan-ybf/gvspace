import type { Locale } from "@/i18n";
import {
  filterPublishedForLocale,
  getPublicContentSlug,
  isContentPublishedForLocale,
  type ContentLocalization,
} from "@/content-localization";
import type { CaseStudy } from "./wordpress-cases";
import { getFallbackTechnology, getFallbackTechnologyStack } from "./technology-data";

export type TechnologyCategory = { name: string; slug: string; order: number };

export type TechnologyStat = { value: string; label: string };
export type TechnologyBenefit = { title: string; description: string };
export type TechnologyFaq = { question: string; answer: string };
export type TechnologyTrigger = { title: string; description: string };
export type TechnologyUse = { title: string; description: string };
export type TechnologyMeet = {
  name: string;
  role: string;
  quote: string;
  years: string;
  projects: string;
  tags: string[];
  photo: string;
};

export type TechnologyItem = {
  id: number;
  slug: string;
  title: string;
  description: string;
  tag: string;
  headline: string;
  intro: string;
  why: string;
  triggers: TechnologyTrigger[];
  uses: TechnologyUse[];
  stats: TechnologyStat[];
  benefits: TechnologyBenefit[];
  faq: TechnologyFaq[];
  seoLead: string;
  seoText: string;
  meet: TechnologyMeet;
  relatedCase: string;
  visual: string;
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

type TechnologyDetails = {
  title?: string;
  description?: string;
  tag?: string;
  headline?: string;
  intro?: string;
  why?: string;
  triggers?: string;
  uses?: string;
  stats?: string;
  benefits?: string;
  faq?: string;
  seoLead?: string;
  seoText?: string;
  meetName?: string;
  meetRole?: string;
  meetQuote?: string;
  meetYears?: string;
  meetProjects?: string;
  meetTags?: string;
};

type TechnologyNode = {
  databaseId: number;
  slug: string;
  title: string;
  menuOrder?: number;
  modified?: string;
  relatedCase?: string | null;
  visual?: string | null;
  meetPhoto?: string | null;
  technologyDetails?: TechnologyDetails;
  featuredImage?: { node?: { sourceUrl?: string; altText?: string } };
  technologyCategories?: { nodes?: { name?: string; slug: string }[] };
  gvspaceLocalization?: ContentLocalization | null;
};

type CategoryNode = {
  name: string;
  slug: string;
  menuOrder?: number | null;
  localizedName?: string | null;
};

type TechnologyResponse = {
  technologies?: { nodes?: TechnologyNode[] };
  technologyCategories?: { nodes?: CategoryNode[] };
};

const technologyBaseFields = `
  databaseId
  slug
  title
  menuOrder
  modified
  gvspaceLocalization { locale translationGroup status }
  featuredImage { node { sourceUrl altText } }
  technologyCategories { nodes { name slug } }
`;

const technologyCardFields = `
  ${technologyBaseFields}
  technologyDetails(locale: $locale) { title description tag }
`;

const technologyPageFields = `
  ${technologyBaseFields}
  relatedCase
  visual
  meetPhoto
  technologyDetails(locale: $locale) {
    title description tag headline intro why triggers uses stats benefits faq
    seoLead seoText meetName meetRole meetQuote meetYears meetProjects meetTags
  }
`;

export function parseTechnologyPairs(value: string): [string, string][] {
  return value
    .split(/\r?\n/)
    .map((line) => line.trim())
    .filter(Boolean)
    .map((line) => {
      const separator = line.indexOf("|");
      if (separator === -1) return [line, ""] as [string, string];
      return [line.slice(0, separator).trim(), line.slice(separator + 1).trim()] as [
        string,
        string,
      ];
    })
    .filter(([left]) => left !== "");
}

async function queryWordPress<T>(
  query: string,
  variables?: Record<string, unknown>,
): Promise<T | null> {
  if (!endpoint) return null;

  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ query, variables }),
      next: { revalidate: 60 },
    });
    if (!response.ok) return null;
    const result = (await response.json()) as { data?: T; errors?: unknown[] };
    return result.errors ? null : (result.data ?? null);
  } catch {
    return null;
  }
}

function sortCategories(categories: TechnologyCategory[]) {
  return [...categories].sort((a, b) => {
    if (a.order !== b.order) return a.order - b.order;
    const aIndex = preferredCategoryOrder.indexOf(a.slug);
    const bIndex = preferredCategoryOrder.indexOf(b.slug);
    if (aIndex === -1 && bIndex === -1) return a.name.localeCompare(b.name);
    if (aIndex === -1) return 1;
    if (bIndex === -1) return -1;
    return aIndex - bIndex;
  });
}

function toItem(node: TechnologyNode): TechnologyItem {
  const slug = getPublicContentSlug(node.slug, node.gvspaceLocalization);
  const title = node.technologyDetails?.title || node.title;
  const stats = parseTechnologyPairs(node.technologyDetails?.stats ?? "").map(([value, label]) => ({
    value,
    label,
  }));
  const benefits = parseTechnologyPairs(node.technologyDetails?.benefits ?? "").map(
    ([titleText, description]) => ({ title: titleText, description }),
  );
  const triggerSource = node.technologyDetails?.triggers || node.technologyDetails?.benefits || "";
  const triggers = parseTechnologyPairs(triggerSource).map(([triggerTitle, description]) => ({
    title: triggerTitle,
    description,
  }));
  const uses = parseTechnologyPairs(node.technologyDetails?.uses ?? "").map(
    ([useTitle, description]) => ({
      title: useTitle,
      description,
    }),
  );
  const faq = parseTechnologyPairs(node.technologyDetails?.faq ?? "").map(([question, answer]) => ({
    question,
    answer,
  }));
  const why = node.technologyDetails?.why || node.technologyDetails?.headline || "";
  return {
    id: node.databaseId,
    slug,
    title,
    description: node.technologyDetails?.description ?? "",
    tag: node.technologyDetails?.tag ?? "",
    headline: node.technologyDetails?.headline || title,
    intro: node.technologyDetails?.intro ?? "",
    why,
    triggers,
    uses,
    stats,
    benefits,
    faq,
    seoLead: node.technologyDetails?.seoLead ?? "",
    seoText: node.technologyDetails?.seoText ?? "",
    meet: {
      name: node.technologyDetails?.meetName ?? "",
      role: node.technologyDetails?.meetRole ?? "",
      quote: node.technologyDetails?.meetQuote ?? "",
      years: node.technologyDetails?.meetYears ?? "",
      projects: node.technologyDetails?.meetProjects ?? "",
      tags: (node.technologyDetails?.meetTags ?? "")
        .split(/\r?\n/)
        .map((item) => item.trim())
        .filter(Boolean),
      photo: node.meetPhoto ?? "",
    },
    relatedCase: node.relatedCase ?? "",
    visual: node.visual ?? "",
    image: node.featuredImage?.node?.sourceUrl,
    imageAlt: node.featuredImage?.node?.altText || title,
    categorySlugs:
      node.technologyCategories?.nodes?.map(({ slug: categorySlug }) => categorySlug) ?? [],
    order: node.menuOrder ?? 0,
  };
}

function mergeTechnologyFallback(item: TechnologyItem, locale: Locale): TechnologyItem {
  const fallback = getFallbackTechnology(item.slug, locale);
  if (!fallback) return item;
  return {
    ...item,
    headline: item.headline || fallback.headline,
    intro: item.intro || fallback.intro,
    why: item.why || fallback.why,
    triggers: item.triggers.length ? item.triggers : fallback.triggers,
    uses: item.uses.length ? item.uses : fallback.uses,
    stats: item.stats.length ? item.stats : fallback.stats,
    benefits: item.benefits.length ? item.benefits : fallback.benefits,
    faq: item.faq.length ? item.faq : fallback.faq,
    seoLead: item.seoLead || fallback.seoLead,
    seoText: item.seoText || fallback.seoText,
    meet: {
      name: item.meet.name || fallback.meet.name,
      role: item.meet.role || fallback.meet.role,
      quote: item.meet.quote || fallback.meet.quote,
      years: item.meet.years || fallback.meet.years,
      projects: item.meet.projects || fallback.meet.projects,
      tags: item.meet.tags.length ? item.meet.tags : fallback.meet.tags,
      photo: item.meet.photo || fallback.meet.photo,
    },
    relatedCase: item.relatedCase || fallback.relatedCase,
    image: item.image || fallback.image,
  };
}

export async function getTechnologyStack(locale: Locale): Promise<TechnologyStack> {
  const data = await queryWordPress<TechnologyResponse>(
    `query TechnologyStack($locale: String!) {
      technologyCategories(first: 100, where: { hideEmpty: false }) {
        nodes { name slug menuOrder localizedName(locale: $locale) }
      }
      technologies(first: 100) {
        nodes { ${technologyCardFields} }
      }
    }`,
    { locale },
  );

  if (!data) return getFallbackTechnologyStack(locale);

  const items = filterPublishedForLocale(data.technologies?.nodes ?? [], locale)
    .map((node) => toItem(node))
    .sort((a, b) => a.order - b.order || a.id - b.id);

  const categories = sortCategories(
    (data.technologyCategories?.nodes ?? []).map((category, index) => ({
      slug: category.slug,
      name: category.localizedName || category.name,
      order: category.menuOrder ?? index,
    })),
  );

  return {
    categories: categories.length ? categories : getFallbackTechnologyStack(locale).categories,
    items,
  };
}

export function technologyServiceDirection(categorySlugs: string[]): string {
  const primary = categorySlugs[0] ?? "development";
  if (primary === "systems") return "development";
  return primary;
}

export function caseMatchesTechnology(
  project: { direction: string; projectType: string; badge: string; services: string[] },
  categorySlugs: string[],
): boolean {
  const haystack = [project.direction, project.projectType, project.badge, ...project.services]
    .join(" ")
    .toLowerCase();
  const tagsByCategory: Record<string, string[]> = {
    development: ["development", "розроб", "it", "айті", "web"],
    systems: ["development", "розроб", "it", "system", "систем"],
    marketing: ["marketing", "маркет", "performance", "seo", "ads"],
    content: ["content", "контент", "продак", "brand"],
  };
  const tags = categorySlugs.flatMap((slug) => tagsByCategory[slug] ?? [slug]);
  return tags.some((tag) => haystack.includes(tag.toLowerCase()));
}

function compactKey(value: string) {
  return value.toLowerCase().replace(/[^a-z0-9а-яіїєґ]+/gi, "");
}

function technologyKeys(technology: Pick<TechnologyItem, "title" | "slug">) {
  return [
    ...new Set(
      [technology.title, technology.slug.replace(/-/g, ""), technology.slug.replace(/-/g, " ")]
        .map(compactKey)
        .filter((key) => key.length >= 3),
    ),
  ];
}

function latestCase(cases: CaseStudy[]) {
  return cases.reduce<CaseStudy | undefined>((latest, project) => {
    if (!latest) return project;
    const latestTime = latest.publishedAt ? Date.parse(latest.publishedAt) : 0;
    const projectTime = project.publishedAt ? Date.parse(project.publishedAt) : 0;
    return projectTime >= latestTime ? project : latest;
  }, undefined);
}

export function pickTechnologyCase(
  cases: CaseStudy[],
  technology: Pick<TechnologyItem, "title" | "slug" | "categorySlugs" | "relatedCase">,
) {
  const keys = technologyKeys(technology);
  const mentioned = cases.filter((project) => {
    const haystack = compactKey(
      [
        project.title,
        project.catalogTitle,
        project.excerpt,
        ...project.services,
        project.badge,
        project.direction,
        project.projectType,
      ].join(" "),
    );
    return keys.some((key) => haystack.includes(key));
  });
  const pool = mentioned.length
    ? mentioned
    : cases.filter((project) => caseMatchesTechnology(project, technology.categorySlugs));
  return (
    latestCase(pool) ||
    (technology.relatedCase
      ? cases.find((project) => project.slug === technology.relatedCase)
      : undefined)
  );
}

export function memberMatchesTechnology(
  member: { tags: string[] },
  technology: Pick<TechnologyItem, "title" | "slug" | "tag">,
) {
  const keys = new Set(
    [...technologyKeys(technology), compactKey(technology.tag)].filter((key) => key.length >= 3),
  );
  return member.tags.some((tag) => keys.has(compactKey(tag)));
}

export async function getTechnologyBySlug(
  slug: string,
  locale: Locale,
): Promise<TechnologyItem | undefined> {
  const data = await queryWordPress<TechnologyResponse>(
    `query TechnologyBySlug($locale: String!) {
      technologies(first: 100) {
        nodes { ${technologyPageFields} }
      }
    }`,
    { locale },
  );

  const legacyPageFields = `
    ${technologyBaseFields}
    relatedCase
    visual
    technologyDetails(locale: $locale) { title description tag headline intro stats benefits faq }
  `;

  const nodes =
    data?.technologies?.nodes ??
    (
      await queryWordPress<TechnologyResponse>(
        `query TechnologyBySlug($locale: String!) {
          technologies(first: 100) {
            nodes { ${legacyPageFields} }
          }
        }`,
        { locale },
      )
    )?.technologies?.nodes ??
    (
      await queryWordPress<TechnologyResponse>(
        `query TechnologyBySlug($locale: String!) {
          technologies(first: 100) {
            nodes { ${technologyCardFields} }
          }
        }`,
        { locale },
      )
    )?.technologies?.nodes;

  if (nodes?.length) {
    const node = nodes.find(
      (candidate) =>
        isContentPublishedForLocale(candidate.gvspaceLocalization, locale) &&
        getPublicContentSlug(candidate.slug, candidate.gvspaceLocalization) === slug,
    );
    return node ? mergeTechnologyFallback(toItem(node), locale) : undefined;
  }

  return getFallbackTechnology(slug, locale);
}
