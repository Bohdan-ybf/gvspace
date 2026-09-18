import type { Locale } from "@/i18n";
import {
  filterPublishedForLocale,
  getPublicContentSlug,
  isContentPublishedForLocale,
  type ContentLocalization,
} from "@/content-localization";
import {
  getFallbackVacancies,
  getVacancyBySlug as getFallbackVacancy,
  type Vacancy,
} from "./vacancy-data";

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

type VacancyDetails = {
  title?: string;
  excerpt?: string;
  salary?: string;
  hot?: boolean;
  tags?: string[];
  direction?: string;
  employmentTags?: string[];
  role?: string[];
  tasks?: string[];
  requirements?: string[];
  tools?: string[];
  benefits?: string[];
};

type VacancyNode = {
  slug: string;
  title: string;
  menuOrder?: number | null;
  modified?: string;
  vacancyDetails: VacancyDetails;
  gvspaceLocalization?: ContentLocalization | null;
};

export type VacancySummary = {
  slug: string;
  title: string;
  excerpt: string;
  salary: string;
  hot: boolean;
  tags: string[];
  direction: string;
  employmentTags: string[];
  modifiedAt?: string;
};

const vacancyFields = `
  slug
  title
  menuOrder
  modified
  gvspaceLocalization { locale translationGroup status }
  vacancyDetails(locale: $locale) {
    title excerpt salary hot tags direction employmentTags role tasks requirements tools benefits
  }
`;

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

function toVacancy(node: VacancyNode, locale: Locale): Vacancy {
  const details = node.vacancyDetails;
  const fallback = getFallbackVacancy(
    getPublicContentSlug(node.slug, node.gvspaceLocalization),
    locale,
  );
  return {
    slug: getPublicContentSlug(node.slug, node.gvspaceLocalization),
    title: details.title || node.title,
    excerpt: details.excerpt || "",
    salary: details.salary || fallback?.salary || "",
    hot: Boolean(details.hot),
    tags: details.tags ?? [],
    heroImage: "/images/careers/vacancy-hero.webp",
    role: details.role ?? [],
    tasks: details.tasks ?? [],
    requirements: details.requirements ?? [],
    tools: details.tools ?? [],
    benefits: details.benefits ?? [],
  };
}

function sortVacancyNodes(nodes: VacancyNode[]) {
  return [...nodes].sort((left, right) => (left.menuOrder ?? 0) - (right.menuOrder ?? 0));
}

function normalizeVacancyTag(tag: string) {
  return tag.trim().toUpperCase().replace(/\s+/g, "-");
}

const employmentTagNames = new Set([
  "REMOTE",
  "FULL-TIME",
  "FULLTIME",
  "PART-TIME",
  "PARTTIME",
  "HYBRID",
  "OFFICE",
  "ONSITE",
  "ON-SITE",
]);

function isEmploymentTag(tag: string) {
  return employmentTagNames.has(normalizeVacancyTag(tag));
}

function directionFromTags(tags: string[]) {
  return tags.find((tag) => !isEmploymentTag(tag)) ?? "";
}

function employmentFromTags(tags: string[]) {
  return tags.filter(isEmploymentTag);
}

function toSummary(node: VacancyNode): VacancySummary {
  const tags = node.vacancyDetails.tags ?? [];
  const employmentTags = node.vacancyDetails.employmentTags?.length
    ? node.vacancyDetails.employmentTags
    : employmentFromTags(tags);
  return {
    slug: getPublicContentSlug(node.slug, node.gvspaceLocalization),
    title: node.vacancyDetails.title || node.title,
    excerpt: node.vacancyDetails.excerpt ?? "",
    salary: node.vacancyDetails.salary ?? "",
    hot: Boolean(node.vacancyDetails.hot),
    tags,
    direction: node.vacancyDetails.direction || directionFromTags(tags),
    employmentTags,
    modifiedAt: node.modified,
  };
}

function fallbackSummaries(locale: Locale): VacancySummary[] {
  return getFallbackVacancies().map((vacancy) => {
    const resolved = getFallbackVacancy(vacancy.slug, locale);
    const tags = resolved?.tags ?? vacancy.tags;
    return {
      slug: vacancy.slug,
      title: resolved?.title ?? vacancy.title[locale],
      excerpt: resolved?.excerpt ?? vacancy.excerpt[locale],
      salary: resolved?.salary ?? vacancy.salary,
      hot: vacancy.hot,
      tags,
      direction: directionFromTags(tags),
      employmentTags: employmentFromTags(tags),
    };
  });
}

export async function getVacancies(locale: Locale): Promise<VacancySummary[]> {
  const data = await queryWordPress<{ vacancies: { nodes: VacancyNode[] } }>(
    `
    query Vacancies($locale: String!) {
      vacancies(first: 100) { nodes { ${vacancyFields} } }
    }
  `,
    { locale },
  );

  if (data?.vacancies.nodes.length) {
    return sortVacancyNodes(filterPublishedForLocale(data.vacancies.nodes, locale)).map(toSummary);
  }

  return fallbackSummaries(locale);
}

export async function getVacancyBySlug(slug: string, locale: Locale): Promise<Vacancy | undefined> {
  const data = await queryWordPress<{ vacancies: { nodes: VacancyNode[] } }>(
    `
    query VacanciesForRoute($locale: String!) {
      vacancies(first: 100) { nodes { ${vacancyFields} } }
    }
  `,
    { locale },
  );
  if (data?.vacancies.nodes.length) {
    const node = data.vacancies.nodes.find(
      (candidate) =>
        isContentPublishedForLocale(candidate.gvspaceLocalization, locale) &&
        getPublicContentSlug(candidate.slug, candidate.gvspaceLocalization) === slug,
    );
    return node ? toVacancy(node, locale) : undefined;
  }

  return getFallbackVacancy(slug, locale);
}
