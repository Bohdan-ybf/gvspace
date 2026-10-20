import type { Locale } from "@/i18n";
import { presentBlogCopy } from "./blog-open-graph";
import { toLatinSlug } from "./latin-slug";
import {
  getPublicContentSlug,
  isContentPublishedForLocale,
  type ContentLocalization,
} from "@/content-localization";

export type BlogPost = {
  slug: string;
  title: string;
  excerpt: string;
  content: string;
  category: string;
  publishedAt: string;
  readingTime: number;
  author: { slug: string; name: string; role: string; avatar?: string; cardBio: string };
  tags: string[];
  image?: string;
  openGraphTitle?: string;
  openGraphDescription?: string;
};

export type BlogPostSummary = {
  slug: string;
  title: string;
  excerpt: string;
  category: string;
  categorySlug: string;
  publishedAt: string;
  readingTime: number;
  authorName: string;
  authorSlug?: string;
  image?: string;
  modifiedAt?: string;
};

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

const postsQuery = `query Posts($locale: String!) { posts(first: 100, where: { status: PUBLISH }) { nodes { slug title excerpt content date modified gvspaceLocalization { locale translationGroup status } gvspaceBlog(locale: $locale) { title excerpt content isPublished } gvspaceBlogAuthor(locale: $locale) { slug name } author { node { name } } featuredImage { node { sourceUrl } } categories { nodes { name slug gvspaceNameEn } } } } }`;
const postsQueryLegacy = `query Posts($locale: String!) { posts(first: 100, where: { status: PUBLISH }) { nodes { slug title excerpt content date modified gvspaceLocalization { locale translationGroup status } gvspaceBlog(locale: $locale) { title excerpt content isPublished } author { node { name } } featuredImage { node { sourceUrl } } categories { nodes { name slug gvspaceNameEn } } } } }`;
const postQuery = `query PostsForRoute($locale: String!) { posts(first: 100, where: { status: PUBLISH }) { nodes { slug title excerpt content date gvspaceLocalization { locale translationGroup status } gvspaceBlog(locale: $locale) { title excerpt content isPublished } gvspaceBlogAuthor(locale: $locale) { slug name role cardBio photo } author { node { slug name avatar { url } gvspaceAuthorProfile { role } } } categories { nodes { name slug gvspaceNameEn } } tags { nodes { name } } featuredImage { node { sourceUrl } } } } }`;
const postQueryLegacy = `query PostsForRoute($locale: String!) { posts(first: 100, where: { status: PUBLISH }) { nodes { slug title excerpt content date gvspaceLocalization { locale translationGroup status } gvspaceBlog(locale: $locale) { title excerpt content isPublished } author { node { slug name avatar { url } gvspaceAuthorProfile { role } } } categories { nodes { name slug gvspaceNameEn } } tags { nodes { name } } featuredImage { node { sourceUrl } } } } }`;

async function fetchWordPress<T>(
  query: string,
  variables: Record<string, string>,
  fallbackQuery?: string,
) {
  const request = async (bodyQuery: string) => {
    const response = await fetch(endpoint as string, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ query: bodyQuery, variables }),
      next: { revalidate: 300 },
    });
    if (!response.ok) return undefined;
    return (await response.json()) as { data?: T; errors?: Array<{ message?: string }> };
  };
  const result = await request(query);
  const unknownAuthorField = result?.errors?.some((error) =>
    /gvspaceBlogAuthor|Cannot query field/i.test(error.message ?? ""),
  );
  if (unknownAuthorField && fallbackQuery) return request(fallbackQuery);
  return result;
}

type WordPressPost = {
  slug: string;
  title: string;
  excerpt: string;
  content: string;
  date: string;
  modified?: string;
  author?: {
    node?: {
      slug?: string;
      name?: string;
      avatar?: { url?: string };
      gvspaceAuthorProfile?: { role?: string };
    };
  };
  gvspaceBlogAuthor?: {
    slug?: string;
    name?: string;
    role?: string;
    cardBio?: string;
    photo?: string;
  } | null;
  categories?: { nodes?: Array<{ name: string; slug?: string; gvspaceNameEn?: string }> };
  tags?: { nodes?: Array<{ name: string }> };
  featuredImage?: { node?: { sourceUrl?: string } };
  gvspaceLocalization?: ContentLocalization;
  gvspaceBlog?: {
    title?: string;
    excerpt?: string;
    content?: string;
    isPublished?: boolean;
  };
};

function authorFromPost(post: WordPressPost) {
  const profile = post.gvspaceBlogAuthor;
  return {
    slug: profile?.slug ? toLatinSlug(profile.slug) : "",
    name: profile?.name || post.author?.node?.name || "GVSPACE",
    role: profile?.role || post.author?.node?.gvspaceAuthorProfile?.role || "GVSPACE",
    avatar: profile?.photo || post.author?.node?.avatar?.url,
    cardBio: profile?.cardBio ?? "",
  };
}

function stripHtml(value: string) {
  return value
    .replace(/<[^>]*>/g, " ")
    .replace(/\s+/g, " ")
    .trim();
}

export async function getBlogPosts(locale: Locale): Promise<BlogPostSummary[]> {
  if (!endpoint) return [];
  try {
    const result = await fetchWordPress<{ posts?: { nodes?: WordPressPost[] } }>(
      postsQuery,
      { locale },
      postsQueryLegacy,
    );
    if (!result?.data) return [];
    return (
      result.data?.posts?.nodes
        ?.filter(
          (post) =>
            isContentPublishedForLocale(post.gvspaceLocalization, locale) &&
            post.gvspaceBlog?.isPublished !== false,
        )
        .map((post) => {
          const author = authorFromPost(post);
          const copy = presentBlogCopy({
            title: stripHtml(post.gvspaceBlog?.title || post.title),
            excerpt: stripHtml(post.gvspaceBlog?.excerpt || post.excerpt),
            content: post.gvspaceBlog?.content || post.content || "",
          });
          return {
            slug: getPublicContentSlug(post.slug, post.gvspaceLocalization),
            title: copy.title,
            excerpt: copy.excerpt,
            category:
              locale === "en"
                ? post.categories?.nodes?.[0]?.gvspaceNameEn ||
                  post.categories?.nodes?.[0]?.name ||
                  "Blog"
                : (post.categories?.nodes?.[0]?.name ?? "Блог"),
            categorySlug: post.categories?.nodes?.[0]?.slug ?? "blog",
            publishedAt: new Intl.DateTimeFormat(locale === "uk" ? "uk-UA" : "en-GB", {
              day: "2-digit",
              month: "long",
              year: "numeric",
            }).format(new Date(post.date)),
            readingTime: Math.max(
              1,
              Math.ceil(stripHtml(copy.content || copy.excerpt).split(" ").length / 200),
            ),
            authorName: author.name,
            authorSlug: author.slug || undefined,
            image: post.featuredImage?.node?.sourceUrl,
            modifiedAt: post.modified,
          };
        }) ?? []
    );
  } catch {
    return [];
  }
}

export async function getBlogPostsByAuthor(
  slug: string,
  locale: Locale,
): Promise<BlogPostSummary[]> {
  const posts = await getBlogPosts(locale);
  const wanted = toLatinSlug(slug);
  return posts.filter(
    (post) => post.authorSlug !== undefined && toLatinSlug(post.authorSlug) === wanted,
  );
}

export async function getBlogPost(slug: string, locale: Locale): Promise<BlogPost | undefined> {
  if (endpoint) {
    try {
      const result = await fetchWordPress<{ posts?: { nodes?: WordPressPost[] } }>(
        postQuery,
        { locale },
        postQueryLegacy,
      );
      const post = result?.data?.posts?.nodes?.find(
        (candidate) =>
          isContentPublishedForLocale(candidate.gvspaceLocalization, locale) &&
          candidate.gvspaceBlog?.isPublished !== false &&
          getPublicContentSlug(candidate.slug, candidate.gvspaceLocalization) === slug,
      );
      if (post && isContentPublishedForLocale(post.gvspaceLocalization, locale)) {
        const copy = presentBlogCopy({
          title: stripHtml(post.gvspaceBlog?.title || post.title),
          excerpt: stripHtml(post.gvspaceBlog?.excerpt || post.excerpt),
          content: post.gvspaceBlog?.content || post.content || "",
        });
        const words = stripHtml(copy.content).split(" ").length;
        return {
          slug: getPublicContentSlug(post.slug, post.gvspaceLocalization),
          title: copy.title,
          excerpt: copy.excerpt,
          content: copy.content,
          category:
            locale === "en"
              ? post.categories?.nodes?.[0]?.gvspaceNameEn ||
                post.categories?.nodes?.[0]?.name ||
                "Blog"
              : (post.categories?.nodes?.[0]?.name ?? "Блог"),
          publishedAt: new Intl.DateTimeFormat(locale === "uk" ? "uk-UA" : "en-GB", {
            dateStyle: "long",
          }).format(new Date(post.date)),
          readingTime: Math.max(1, Math.ceil(words / 200)),
          author: authorFromPost(post),
          tags: post.tags?.nodes?.map(({ name }) => name) ?? [],
          image: post.featuredImage?.node?.sourceUrl,
          openGraphTitle: copy.ogTitle || undefined,
          openGraphDescription: copy.ogDescription || undefined,
        };
      }
    } catch {
      return undefined;
    }
  }
  return undefined;
}
