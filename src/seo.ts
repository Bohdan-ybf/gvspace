export type SeoData = {
  title: string;
  description: string;
  h1: string;
  openGraphTitle: string;
  openGraphDescription: string;
  openGraphImage?: string;
  datePublished?: string;
  dateModified?: string;
};

export type SeoGraphqlData = Partial<SeoData> | null | undefined;

export const seoGraphqlFields = `
  title
  description
  h1
  openGraphTitle
  openGraphDescription
  openGraphImage
  datePublished
  dateModified
`;

export function normalizeSeoData(
  seo: SeoGraphqlData,
  fallback: Pick<SeoData, "title" | "description">,
): SeoData {
  const title = seo?.title?.trim() || fallback.title;
  const description = seo?.description?.trim() || fallback.description;

  return {
    title,
    description,
    h1: seo?.h1?.trim() || title,
    openGraphTitle: seo?.openGraphTitle?.trim() || title,
    openGraphDescription: seo?.openGraphDescription?.trim() || description,
    openGraphImage: seo?.openGraphImage?.trim() || undefined,
    datePublished: seo?.datePublished || undefined,
    dateModified: seo?.dateModified || undefined,
  };
}
