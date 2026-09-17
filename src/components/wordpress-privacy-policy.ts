import type { Locale } from "@/i18n";
import { filterPublishedForLocale, type ContentLocalization } from "@/content-localization";
import { normalizeSeoData, seoGraphqlFields, type SeoData, type SeoGraphqlData } from "@/seo";

export type PrivacySection = {
  number: string;
  title: string;
  body: string;
};

export type PrivacyPolicy = {
  kicker: string;
  title: string;
  dates: string;
  contents: string;
  sections: PrivacySection[];
  seo?: SeoData;
};

type PrivacyNode = {
  title: string;
  gvspaceLocalization?: ContentLocalization | null;
  gvspaceSeo?: SeoGraphqlData;
  privacyPolicyDetails?: {
    kicker?: string;
    title?: string;
    dates?: string;
    contents?: string;
    sections?: PrivacySection[];
  };
};

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

export async function getPrivacyPolicy(locale: Locale): Promise<PrivacyPolicy | undefined> {
  if (!endpoint) return undefined;

  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query PrivacyPolicy($locale: String!) {
          privacyPolicies(first: 10) {
            nodes {
              title
              gvspaceLocalization { locale translationGroup status }
              gvspaceSeo(locale: $locale) { ${seoGraphqlFields} }
              privacyPolicyDetails(locale: $locale) {
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
      data?: { privacyPolicies?: { nodes?: PrivacyNode[] } };
      errors?: unknown[];
    };
    if (result.errors) return undefined;

    const item = filterPublishedForLocale(result.data?.privacyPolicies?.nodes ?? [], locale)[0];
    const details = item?.privacyPolicyDetails;
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
