import type { Locale } from "@/i18n";
import { filterPublishedForLocale, type ContentLocalization } from "@/content-localization";

export type HomeSeoText = {
  title: string;
  content: string;
};

type HomeSeoTextNode = {
  title: string;
  content?: string;
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

export async function getHomeSeoText(locale: Locale): Promise<HomeSeoText | undefined> {
  if (!endpoint) return undefined;

  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query HomeSeoText {
          homeSeoTexts(first: 20) {
            nodes {
              title content
              gvspaceLocalization { locale translationGroup status }
            }
          }
        }`,
      }),
      next: { revalidate: 60 },
    });
    if (!response.ok) return undefined;

    const result = (await response.json()) as {
      data?: { homeSeoTexts?: { nodes?: HomeSeoTextNode[] } };
      errors?: unknown[];
    };
    if (result.errors) return undefined;

    const item = filterPublishedForLocale(result.data?.homeSeoTexts?.nodes ?? [], locale)[0];
    if (!item) return undefined;
    return { title: plainText(item.title), content: plainText(item.content ?? "") };
  } catch {
    return undefined;
  }
}
