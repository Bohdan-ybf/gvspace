import type { Locale } from "@/i18n";
import {
  filterPublishedForLocale,
  getPublicContentSlug,
  isContentPublishedForLocale,
  type ContentLocalization,
} from "@/content-localization";
import { getVacancyBySlug as getFallbackVacancy, type Vacancy } from "./vacancy-data";

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

type VacancyDetails = {
  excerpt?: string;
  role?: string[];
  tasks?: string[];
  requirements?: string[];
  benefits?: string[];
  titleEn: string;
  excerptUk: string;
  excerptEn: string;
  salary: string;
  hot: boolean;
  tags: string[];
  roleUk: string[];
  roleEn: string[];
  tasksUk: string[];
  tasksEn: string[];
  requirementsUk: string[];
  requirementsEn: string[];
  tools: string[];
  benefitsUk: string[];
  benefitsEn: string[];
};

type VacancyNode = {
  slug: string;
  title: string;
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
};

const vacancyFields = `
  slug
  title
  gvspaceLocalization { locale translationGroup status }
  vacancyDetails {
    excerpt role tasks requirements benefits
    titleEn excerptUk excerptEn salary hot tags
    roleUk roleEn tasksUk tasksEn requirementsUk requirementsEn
    tools benefitsUk benefitsEn
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

function pairLists(uk: string[] = [], en: string[] = []) {
  return Array.from({ length: Math.max(uk.length, en.length) }, (_, index) => ({
    uk: uk[index] || en[index] || "",
    en: en[index] || uk[index] || "",
  }));
}

function toVacancy(node: VacancyNode): Vacancy {
  const details = node.vacancyDetails;
  const localized = Boolean(
    node.gvspaceLocalization?.locale && node.gvspaceLocalization.locale !== "legacy",
  );
  const localizedPairs = (items: string[] = []) => items.map((item) => ({ uk: item, en: item }));
  return {
    slug: getPublicContentSlug(node.slug, node.gvspaceLocalization),
    title: localized
      ? { uk: node.title, en: node.title }
      : { uk: node.title, en: details.titleEn || node.title },
    salary: details.salary,
    hot: details.hot,
    tags: details.tags,
    heroImage: "/images/careers/vacancy-hero.webp",
    role: localized ? localizedPairs(details.role) : pairLists(details.roleUk, details.roleEn),
    tasks: localized ? localizedPairs(details.tasks) : pairLists(details.tasksUk, details.tasksEn),
    requirements: localized
      ? localizedPairs(details.requirements)
      : pairLists(details.requirementsUk, details.requirementsEn),
    tools: details.tools,
    benefits: localized
      ? localizedPairs(details.benefits)
      : pairLists(details.benefitsUk, details.benefitsEn),
  };
}

export async function getVacancies(locale: Locale): Promise<VacancySummary[]> {
  const data = await queryWordPress<{ vacancies: { nodes: VacancyNode[] } }>(`
    query Vacancies {
      vacancies(first: 100) { nodes { ${vacancyFields} } }
    }
  `);

  if (data?.vacancies.nodes.length) {
    return filterPublishedForLocale(data.vacancies.nodes, locale).map((node) => ({
      slug: getPublicContentSlug(node.slug, node.gvspaceLocalization),
      title:
        node.gvspaceLocalization?.locale && node.gvspaceLocalization.locale !== "legacy"
          ? node.title
          : locale === "en" && node.vacancyDetails.titleEn
            ? node.vacancyDetails.titleEn
            : node.title,
      excerpt:
        node.gvspaceLocalization?.locale && node.gvspaceLocalization.locale !== "legacy"
          ? (node.vacancyDetails.excerpt ?? "")
          : locale === "uk"
            ? node.vacancyDetails.excerptUk
            : node.vacancyDetails.excerptEn,
      salary: node.vacancyDetails.salary,
      hot: node.vacancyDetails.hot,
      tags: node.vacancyDetails.tags,
    }));
  }

  const fallback = getFallbackVacancy("performance-marketing-manager");
  if (!fallback) return [];

  return [
    {
      slug: fallback.slug,
      title: fallback.title[locale],
      excerpt:
        locale === "uk"
          ? "Шукаємо фахівця з досвідом у Meta та Google Ads, який вміє будувати системи."
          : "We are looking for a Meta and Google Ads expert who knows how to build systems.",
      salary: fallback.salary,
      hot: fallback.hot,
      tags: fallback.tags,
    },
  ];
}

export async function getVacancyBySlug(slug: string, locale: Locale): Promise<Vacancy | undefined> {
  const data = await queryWordPress<{ vacancies: { nodes: VacancyNode[] } }>(`
    query VacanciesForRoute {
      vacancies(first: 100) { nodes { ${vacancyFields} } }
    }
  `);
  const node = data?.vacancies.nodes.find(
    (candidate) =>
      isContentPublishedForLocale(candidate.gvspaceLocalization, locale) &&
      getPublicContentSlug(candidate.slug, candidate.gvspaceLocalization) === slug,
  );
  return node ? toVacancy(node) : getFallbackVacancy(slug);
}
