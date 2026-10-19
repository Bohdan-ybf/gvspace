import { getBlogPosts } from "@/components/wordpress-posts";
import { getCaseStudies } from "@/components/wordpress-cases";
import { getServiceOfferings } from "@/components/wordpress-services";
import { getVacancies } from "@/components/wordpress-vacancies";
import { getTechnologyStack } from "@/components/wordpress-technologies";
import type { Locale } from "@/i18n";

const staticPaths = [
  "/",
  "/services",
  "/cases",
  "/reviews",
  "/about",
  "/team",
  "/careers",
  "/technologies",
  "/industries",
  "/partners",
  "/blog",
  "/contacts",
  "/sitemap",
  "/privacy-policy",
  "/terms-of-use",
] as const;

export type SitemapEntry = { pathname: string; lastModified?: string };

function isPublicSlug(slug: string): boolean {
  return Boolean(slug) && !/^\d+$/.test(slug) && !/^(?:article-)?slug$/i.test(slug);
}

function toW3cDate(value?: string): string | undefined {
  if (!value) return undefined;
  const date = new Date(value);
  return Number.isNaN(date.valueOf()) ? undefined : date.toISOString();
}

export async function getLocaleSitemapEntries(locale: Locale): Promise<SitemapEntry[]> {
  const [posts, cases, vacancies, services, technologies] = await Promise.all([
    getBlogPosts(locale),
    getCaseStudies(locale),
    getVacancies(locale),
    getServiceOfferings(locale),
    getTechnologyStack(locale),
  ]);

  return [
    ...staticPaths.map((pathname) => ({ pathname })),
    ...posts
      .filter((post) => isPublicSlug(post.slug))
      .map((post) => ({
        pathname: `/blog/${post.slug}`,
        lastModified: toW3cDate(post.modifiedAt),
      })),
    ...cases
      .filter((item) => item.robotsIndex && isPublicSlug(item.slug))
      .map((item) => ({
        pathname: `/cases/${item.slug}`,
        lastModified: toW3cDate(item.modifiedAt),
      })),
    ...vacancies
      .filter((vacancy) => isPublicSlug(vacancy.slug))
      .map((vacancy) => ({
        pathname: `/careers/${vacancy.slug}`,
        lastModified: toW3cDate(vacancy.modifiedAt),
      })),
    ...services
      .filter((service) => isPublicSlug(service.slug))
      .map((service) => ({
        pathname: service.parentSlug
          ? `/services/${service.parentSlug}/${service.slug}`
          : `/services/${service.slug}`,
        lastModified: toW3cDate(service.modifiedAt),
      })),
    ...technologies.items
      .filter((item) => isPublicSlug(item.slug))
      .map((item) => ({ pathname: `/technologies/${item.slug}` })),
  ];
}
