import type { Locale } from "@/i18n";
import { filterPublishedForLocale, type ContentLocalization } from "@/content-localization";
import { normalizeSeoData, seoGraphqlFields, type SeoData, type SeoGraphqlData } from "@/seo";

export type TermsSection = {
  number: string;
  title: string;
  body: string;
};

export type TermsOfUse = {
  kicker: string;
  title: string;
  dates: string;
  contents: string;
  sections: TermsSection[];
  seo?: SeoData;
};

type TermsNode = {
  title: string;
  gvspaceLocalization?: ContentLocalization | null;
  gvspaceSeo?: SeoGraphqlData;
  termsOfUseDetails?: {
    kicker?: string;
    title?: string;
    dates?: string;
    contents?: string;
    sections?: TermsSection[];
  };
};

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

export async function getTermsOfUse(locale: Locale): Promise<TermsOfUse | undefined> {
  if (!endpoint) return undefined;

  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query TermsOfUseDocument($locale: String!) {
          termsOfUses(first: 10) {
            nodes {
              title
              gvspaceLocalization { locale translationGroup status }
              gvspaceSeo(locale: $locale) { ${seoGraphqlFields} }
              termsOfUseDetails(locale: $locale) {
                kicker title dates contents
                sections { number title body }
              }
            }
          }
        }`,
        variables: { locale },
      }),
      next: { revalidate: 60 },
    });
    if (!response.ok) return undefined;

    const result = (await response.json()) as {
      data?: { termsOfUses?: { nodes?: TermsNode[] } };
      errors?: unknown[];
    };
    if (result.errors && !result.data?.termsOfUses?.nodes?.length) return undefined;

    const item = filterPublishedForLocale(result.data?.termsOfUses?.nodes ?? [], locale)[0];
    const details = item?.termsOfUseDetails;
    if (!item || !details?.title) return undefined;

    return {
      kicker: details.kicker || "LEGAL",
      title: details.title,
      dates: details.dates || "",
      contents: details.contents || "",
      sections: (details.sections ?? []).filter((section) => section.title),
      seo: normalizeSeoData(item.gvspaceSeo, {
        title: details.title || item.title,
        description: details.sections?.[0]?.body?.slice(0, 160) || details.title,
      }),
    };
  } catch {
    return undefined;
  }
}
