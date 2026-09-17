import type { Locale } from "@/i18n";
import { filterPublishedForLocale, type ContentLocalization } from "@/content-localization";

export type FaqItem = {
  id: number;
  question: string;
  answer: string;
  order: number;
};

type FaqNode = {
  databaseId: number;
  title: string;
  content?: string;
  menuOrder?: number;
  faqPlacement?: string;
  faqDetails?: { question?: string; answer?: string };
  gvspaceLocalization?: ContentLocalization | null;
};

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

function plainText(value: string) {
  return value
    .replace(/<[^>]+>/g, " ")
    .replace(/&nbsp;/g, " ")
    .replace(/&amp;/g, "&")
    .replace(/&#8217;|&rsquo;/g, "’")
    .replace(/\s+/g, " ")
    .trim();
}

export async function getHomeFaqs(locale: Locale): Promise<FaqItem[]> {
  if (!endpoint) return [];

  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query HomeFaqs {
          faqItems(first: 100) {
            nodes {
              databaseId title content menuOrder faqPlacement
              faqDetails(locale: "${locale}") { question answer }
              gvspaceLocalization { locale translationGroup status }
            }
          }
        }`,
      }),
      next: { revalidate: 60 },
    });
    if (!response.ok) return [];

    const result = (await response.json()) as {
      data?: { faqItems?: { nodes?: FaqNode[] } };
      errors?: unknown[];
    };
    if (result.errors) return [];

    return filterPublishedForLocale(result.data?.faqItems?.nodes ?? [], locale)
      .filter((item) => item.faqPlacement === "home")
      .map((item) => ({
        id: item.databaseId,
        question: plainText(item.faqDetails?.question || item.title),
        answer: plainText(item.faqDetails?.answer || item.content || ""),
        order: item.menuOrder ?? 0,
      }))
      .filter((item) => item.question !== "")
      .sort((a, b) => a.order - b.order || a.id - b.id);
  } catch {
    return [];
  }
}
