import type { Locale } from "@/i18n";

export type CaseStudy = {
  slug: string;
  title: string;
  publishedAt?: string;
  result: string;
  services: string[];
  metrics: Array<{ value: string; label: string }>;
  challenge: string;
  problems: string[];
  discovery: string;
  discoveryResult: string;
  architecture: Array<{ title: string; description: string }>;
  gallery: string[];
  testimonial: string;
  testimonialAuthor: string;
  projectType: string;
  industry: string;
  badge: string;
  image?: string;
};

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

export const fallback: CaseStudy = {
  slug: "growth-system",
  title: "[Назва бренду]",
  result: "[Головний результат одним реченням].",
  services: ["ТАНЦЮСТРІЯ", "ВИД ПОСЛУГИ"],
  metrics: [
    { value: "+140%", label: "ROAS" },
    { value: "-30%", label: "CPL" },
    { value: "x2", label: "Оборот" },
  ],
  challenge:
    "[Опис ситуації до нас. Відсутність аналітики, непрозорі звіти, відчуття «стелі» у зростанні, стрес власника.]",
  problems: [
    "Висока вартість ліда",
    "Застряглий сайт, що не конвертує",
    "Відсутність наскрізної аналітики",
    "Розрив між маркетингом і продажами",
  ],
  discovery: "Що показав аудит? Виявили «дірки» у воронці, знайшли неочевидні сегменти аудиторії.",
  discoveryResult: "Дорожня карта (Roadmap) трансформації бізнесу.",
  architecture: [
    { title: "Вектор IT", description: "Що змінили в коді/структурі сайту" },
    { title: "Вектор Marketing", description: "Які канали запустили та як зв’язали їх аналітикою" },
    { title: "Вектор Content", description: "Як пакували сенси, щоб викликати довіру" },
  ],
  gallery: [],
  testimonial:
    "Життя після впровадження системи змінилося: з’явився час на стратегію, спокій за результат.",
  testimonialAuthor: "Максим Бичок / Reason Agency",
  projectType: "strategy",
  industry: "services",
  badge: "+140% ROAS",
};

type CaseNode = {
  slug: string;
  title: string;
  date?: string;
  featuredImage?: { node?: { sourceUrl?: string } };
  caseDetails?: Omit<CaseStudy, "slug" | "title" | "image">;
};

const fields = `slug title date featuredImage { node { sourceUrl } } caseDetails { result services metrics { value label } challenge problems discovery discoveryResult architecture { title description } gallery testimonial testimonialAuthor projectType industry badge }`;

function mapCase(node: CaseNode): CaseStudy | undefined {
  if (!node.caseDetails) return undefined;
  return {
    slug: node.slug,
    title: node.title,
    publishedAt: node.date,
    image: node.featuredImage?.node?.sourceUrl,
    ...node.caseDetails,
  };
}

export async function getCaseStudies(): Promise<CaseStudy[]> {
  if (!endpoint) return [];
  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query Cases { projectCases(first: 100) { nodes { ${fields} } } }`,
      }),
      next: { revalidate: 60 },
    });
    if (!response.ok) return [];
    const result = (await response.json()) as { data?: { projectCases?: { nodes?: CaseNode[] } } };
    return (
      result.data?.projectCases?.nodes
        ?.map(mapCase)
        .filter((item): item is CaseStudy => Boolean(item)) ?? []
    );
  } catch {
    return [];
  }
}

export async function getCaseStudy(slug: string, locale: Locale): Promise<CaseStudy | undefined> {
  void locale;
  if (endpoint) {
    try {
      const response = await fetch(endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          query: `query CaseStudy($slug: ID!) { projectCase(id: $slug, idType: SLUG) { ${fields} } }`,
          variables: { slug },
        }),
        next: { revalidate: 60 },
      });
      const result = (await response.json()) as { data?: { projectCase?: CaseNode } };
      const node = result.data?.projectCase;
      if (node) return mapCase(node);
    } catch {}
  }
  return undefined;
}
