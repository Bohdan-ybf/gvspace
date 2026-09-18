import type { Locale } from "@/i18n";
import {
  filterPublishedForLocale,
  getPublicContentSlug,
  type ContentLocalization,
} from "@/content-localization";

export type CaseMetric = { value: string; label: string };
export type CaseVector = { title: string; description: string };
export type CasePerson = { name: string; role: string; photo: string };

export type CaseStudy = {
  slug: string;
  title: string;
  catalogTitle: string;
  excerpt: string;
  publishedAt?: string;
  modifiedAt?: string;
  result: string;
  services: string[];
  metrics: CaseMetric[];
  challenge: string;
  problems: string[];
  discovery: string;
  discoveryResult: string;
  step1: string;
  step1Result: CaseVector;
  step2: string;
  architecture: CaseVector[];
  step3: string;
  step3Result: CaseVector;
  gallery: string[];
  tasks: string[];
  documents: string[];
  team: CasePerson[];
  testimonial: string;
  testimonialAuthor: string;
  testimonialCompany: string;
  projectType: string;
  industry: string;
  direction: string;
  badge: string;
  image?: string;
};

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

type CaseDetails = Partial<
  Omit<CaseStudy, "slug" | "title" | "image" | "publishedAt" | "modifiedAt">
> & {
  title?: string;
};

type CaseNode = {
  slug: string;
  title: string;
  date?: string;
  modified?: string;
  menuOrder?: number | null;
  featuredImage?: { node?: { sourceUrl?: string } };
  caseDetails?: CaseDetails | null;
  gvspaceLocalization?: ContentLocalization | null;
};

const fields = `
  slug title date modified menuOrder
  gvspaceLocalization { locale translationGroup status }
  featuredImage { node { sourceUrl } }
  caseDetails(locale: $locale) {
    title catalogTitle excerpt result services
    metrics { value label }
    challenge problems discovery discoveryResult
    step1 step1Result { title description }
    step2 architecture { title description }
    step3 step3Result { title description }
    gallery tasks documents
    team { name role photo }
    testimonial testimonialAuthor testimonialCompany
    projectType industry direction badge
  }
`;

function emptyVector(vector?: CaseVector | null): CaseVector {
  return { title: vector?.title ?? "", description: vector?.description ?? "" };
}

function mapCase(node: CaseNode): CaseStudy | undefined {
  const details = node.caseDetails;
  if (!details) return undefined;
  const title = details.title || node.title;
  const excerpt = details.excerpt || details.result || "";
  return {
    slug: getPublicContentSlug(node.slug, node.gvspaceLocalization),
    title,
    catalogTitle: details.catalogTitle || title,
    excerpt,
    publishedAt: node.date,
    modifiedAt: node.modified,
    result: excerpt,
    services: details.services ?? [],
    metrics: details.metrics ?? [],
    challenge: details.challenge ?? "",
    problems: details.problems ?? [],
    discovery: details.discovery || details.step1 || "",
    discoveryResult: details.discoveryResult || details.step1Result?.description || "",
    step1: details.step1 || details.discovery || "",
    step1Result: emptyVector(details.step1Result),
    step2: details.step2 ?? "",
    architecture: details.architecture ?? [],
    step3: details.step3 ?? "",
    step3Result: emptyVector(details.step3Result),
    gallery: details.gallery ?? [],
    tasks: details.tasks ?? [],
    documents: details.documents ?? [],
    team: details.team ?? [],
    testimonial: details.testimonial ?? "",
    testimonialAuthor: details.testimonialAuthor ?? "",
    testimonialCompany: details.testimonialCompany ?? "",
    projectType: details.projectType ?? "",
    industry: details.industry ?? "",
    direction: details.direction || details.badge || "",
    badge: details.badge || details.direction || "",
    image: node.featuredImage?.node?.sourceUrl,
  };
}

export async function getCaseStudies(locale: Locale): Promise<CaseStudy[]> {
  if (!endpoint) return [];
  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query CasesV2($locale: String!) { projectCases(first: 100) { nodes { ${fields} } } }`,
        variables: { locale },
      }),
      next: { revalidate: 60 },
    });
    if (!response.ok) return [];
    const result = (await response.json()) as {
      data?: { projectCases?: { nodes?: CaseNode[] } };
      errors?: unknown[];
    };
    if (result.errors) return [];
    return filterPublishedForLocale(result.data?.projectCases?.nodes ?? [], locale)
      .sort((left, right) => (left.menuOrder ?? 0) - (right.menuOrder ?? 0))
      .map(mapCase)
      .filter((item): item is CaseStudy => Boolean(item));
  } catch {
    return [];
  }
}

export async function getCaseStudy(slug: string, locale: Locale): Promise<CaseStudy | undefined> {
  const cases = await getCaseStudies(locale);
  return cases.find((item) => item.slug === slug);
}
