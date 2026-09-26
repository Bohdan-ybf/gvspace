import type { Locale } from "@/i18n";
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
  author: { slug: string; name: string; role: string; avatar?: string };
  tags: string[];
  image?: string;
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
  image?: string;
  modifiedAt?: string;
};

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

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

function stripHtml(value: string) {
  return value
    .replace(/<[^>]*>/g, " ")
    .replace(/\s+/g, " ")
    .trim();
}

export async function getBlogPosts(locale: Locale): Promise<BlogPostSummary[]> {
  if (!endpoint) return [];
  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query Posts($locale: String!) { posts(first: 100, where: { status: PUBLISH }) { nodes { slug title excerpt content date modified gvspaceLocalization { locale translationGroup status } gvspaceBlog(locale: $locale) { title excerpt content isPublished } author { node { name } } featuredImage { node { sourceUrl } } categories { nodes { name slug gvspaceNameEn } } } } }`,
        variables: { locale },
      }),
      cache: "no-store",
    });
    if (!response.ok) return [];
    const result = (await response.json()) as { data?: { posts?: { nodes?: WordPressPost[] } } };
    return (
      result.data?.posts?.nodes
        ?.filter(
          (post) =>
            isContentPublishedForLocale(post.gvspaceLocalization, locale) &&
            post.gvspaceBlog?.isPublished !== false,
        )
        .map((post) => ({
          slug: getPublicContentSlug(post.slug, post.gvspaceLocalization),
          title: stripHtml(post.gvspaceBlog?.title || post.title),
          excerpt: stripHtml(post.gvspaceBlog?.excerpt || post.excerpt),
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
            Math.ceil(
              stripHtml(
                post.gvspaceBlog?.content ||
                  post.content ||
                  post.gvspaceBlog?.excerpt ||
                  post.excerpt,
              ).split(" ").length / 200,
            ),
          ),
          authorName: post.author?.node?.name ?? "GVSPACE",
          image: post.featuredImage?.node?.sourceUrl,
          modifiedAt: post.modified,
        })) ?? []
    );
  } catch {
    return [];
  }
}

export async function getBlogPostsByAuthor(
  slug: string,
  locale: Locale,
): Promise<BlogPostSummary[]> {
  if (!endpoint) return [];
  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query AuthorPosts($slug: ID!, $locale: String!) { user(id: $slug, idType: SLUG) { posts(first: 100) { nodes { slug title excerpt content date gvspaceLocalization { locale translationGroup status } gvspaceBlog(locale: $locale) { title excerpt content isPublished } author { node { name } } featuredImage { node { sourceUrl } } categories { nodes { name slug gvspaceNameEn } } } } } }`,
        variables: { slug, locale },
      }),
      cache: "no-store",
    });
    if (!response.ok) return [];
    const result = (await response.json()) as {
      data?: { user?: { posts?: { nodes?: WordPressPost[] } } };
    };
    return (
      result.data?.user?.posts?.nodes
        ?.filter(
          (post) =>
            isContentPublishedForLocale(post.gvspaceLocalization, locale) &&
            post.gvspaceBlog?.isPublished !== false,
        )
        .map((post) => ({
          slug: getPublicContentSlug(post.slug, post.gvspaceLocalization),
          title: stripHtml(post.gvspaceBlog?.title || post.title),
          excerpt: stripHtml(post.gvspaceBlog?.excerpt || post.excerpt),
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
            Math.ceil(
              stripHtml(
                post.gvspaceBlog?.content ||
                  post.content ||
                  post.gvspaceBlog?.excerpt ||
                  post.excerpt,
              ).split(" ").length / 200,
            ),
          ),
          authorName: post.author?.node?.name ?? "GVSPACE",
          image: post.featuredImage?.node?.sourceUrl,
        })) ?? []
    );
  } catch {
    return [];
  }
}

export async function getBlogPost(slug: string, locale: Locale): Promise<BlogPost | undefined> {
  if (endpoint) {
    try {
      const response = await fetch(endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          query: `query PostsForRoute($locale: String!) { posts(first: 100, where: { status: PUBLISH }) { nodes { slug title excerpt content date gvspaceLocalization { locale translationGroup status } gvspaceBlog(locale: $locale) { title excerpt content isPublished } author { node { slug name avatar { url } gvspaceAuthorProfile { role } } } categories { nodes { name slug gvspaceNameEn } } tags { nodes { name } } featuredImage { node { sourceUrl } } } } }`,
          variables: { locale },
        }),
        cache: "no-store",
      });
      const result = (await response.json()) as {
        data?: { posts?: { nodes?: WordPressPost[] } };
      };
      const post = result.data?.posts?.nodes?.find(
        (candidate) =>
          isContentPublishedForLocale(candidate.gvspaceLocalization, locale) &&
          candidate.gvspaceBlog?.isPublished !== false &&
          getPublicContentSlug(candidate.slug, candidate.gvspaceLocalization) === slug,
      );
      if (post && isContentPublishedForLocale(post.gvspaceLocalization, locale)) {
        const localizedTitle = post.gvspaceBlog?.title || post.title;
        const localizedExcerpt = post.gvspaceBlog?.excerpt || post.excerpt;
        const localizedContent = post.gvspaceBlog?.content || post.content;
        const words = stripHtml(localizedContent).split(" ").length;
        return {
          slug: getPublicContentSlug(post.slug, post.gvspaceLocalization),
          title: stripHtml(localizedTitle),
          excerpt: stripHtml(localizedExcerpt),
          content: localizedContent,
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
          author: {
            slug: post.author?.node?.slug ?? "vasyl-hordiichuk",
            name: post.author?.node?.name ?? "GVSPACE",
            role: post.author?.node?.gvspaceAuthorProfile?.role || "GVSPACE",
            avatar: post.author?.node?.avatar?.url,
          },
          tags: post.tags?.nodes?.map(({ name }) => name) ?? [],
          image: post.featuredImage?.node?.sourceUrl,
        };
      }
    } catch {
      return undefined;
    }
  }
  return undefined;
}
