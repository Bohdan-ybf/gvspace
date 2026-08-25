import type { Locale } from "@/i18n";
import { getVacancyBySlug as getFallbackVacancy, type Vacancy } from "./vacancy-data";

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

type VacancyDetails = {
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
  vacancyDetails {
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
  return {
    slug: node.slug,
    title: { uk: node.title, en: details.titleEn || node.title },
    salary: details.salary,
    hot: details.hot,
    tags: details.tags,
    heroImage: "/images/careers/vacancy-hero.webp",
    role: pairLists(details.roleUk, details.roleEn),
    tasks: pairLists(details.tasksUk, details.tasksEn),
    requirements: pairLists(details.requirementsUk, details.requirementsEn),
    tools: details.tools,
    benefits: pairLists(details.benefitsUk, details.benefitsEn),
  };
}

export async function getVacancies(locale: Locale): Promise<VacancySummary[]> {
  const data = await queryWordPress<{ vacancies: { nodes: VacancyNode[] } }>(`
    query Vacancies {
      vacancies(first: 100) { nodes { ${vacancyFields} } }
    }
  `);

  if (data?.vacancies.nodes.length) {
    return data.vacancies.nodes.map((node) => ({
      slug: node.slug,
      title:
        locale === "en" && node.vacancyDetails.titleEn ? node.vacancyDetails.titleEn : node.title,
      excerpt: locale === "uk" ? node.vacancyDetails.excerptUk : node.vacancyDetails.excerptEn,
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

export async function getVacancyBySlug(slug: string): Promise<Vacancy | undefined> {
  const data = await queryWordPress<{ vacancy: VacancyNode | null }>(
    `query Vacancy($slug: ID!) { vacancy(id: $slug, idType: SLUG) { ${vacancyFields} } }`,
    { slug },
  );
  return data?.vacancy ? toVacancy(data.vacancy) : getFallbackVacancy(slug);
}
