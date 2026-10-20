import type { Locale } from "@/i18n";
import { toLatinSlug } from "./latin-slug";

export type BlogAuthor = {
  slug: string;
  name: string;
  role: string;
  headline: string;
  quote: string;
  bio: string;
  cardBio: string;
  experience: string;
  projects: string;
  photo?: string;
  linkedinHref?: string;
  linkedinLabel?: string;
  instagramHref?: string;
  instagramLabel?: string;
};

type AuthorNode = {
  slug?: string;
  name?: string;
  role?: string;
  headline?: string;
  quote?: string;
  bio?: string;
  cardBio?: string;
  experience?: string;
  projects?: string;
  photo?: string;
  linkedin?: string;
  instagram?: string;
};

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

function socialLink(kind: "linkedin" | "instagram", value?: string) {
  const raw = value?.trim() ?? "";
  if (!raw) return {};
  if (/^https?:\/\//i.test(raw)) {
    try {
      const url = new URL(raw);
      const handle = url.pathname.split("/").filter(Boolean).pop() ?? "";
      if (kind === "instagram") {
        return { href: raw, label: handle ? `@${handle.replace(/^@/, "")}` : raw };
      }
      return { href: raw, label: handle ? `/${handle}` : raw };
    } catch {
      return { href: raw, label: raw };
    }
  }
  if (kind === "instagram") {
    const handle = raw.replace(/^@/, "");
    return {
      href: `https://instagram.com/${handle}`,
      label: raw.startsWith("@") ? raw : `@${handle}`,
    };
  }
  const handle = raw.replace(/^\//, "");
  return {
    href: `https://www.linkedin.com/in/${handle}`,
    label: raw.startsWith("/") ? raw : `/${handle}`,
  };
}

function mapAuthor(author: AuthorNode): BlogAuthor | undefined {
  if (!author.slug || !author.name) return undefined;
  const linkedin = socialLink("linkedin", author.linkedin);
  const instagram = socialLink("instagram", author.instagram);
  return {
    slug: toLatinSlug(author.slug),
    name: author.name,
    role: author.role ?? "",
    headline: author.headline ?? "",
    quote: author.quote ?? "",
    bio: author.bio ?? "",
    cardBio: author.cardBio ?? "",
    experience: author.experience ?? "",
    projects: author.projects ?? "",
    photo: author.photo || undefined,
    linkedinHref: linkedin.href,
    linkedinLabel: linkedin.label,
    instagramHref: instagram.href,
    instagramLabel: instagram.label,
  };
}

const authorFields =
  "slug name role headline quote bio cardBio experience projects photo linkedin instagram";

async function authorFromAssignedPosts(slug: string, locale: Locale) {
  const response = await fetch(endpoint as string, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      query: `query AuthorFromPosts($locale: String!) {
        posts(first: 100, where: { status: PUBLISH }) {
          nodes { gvspaceBlogAuthor(locale: $locale) { ${authorFields} } }
        }
      }`,
      variables: { locale },
    }),
    next: { revalidate: 300 },
  });
  if (!response.ok) return undefined;
  const result = (await response.json()) as {
    data?: { posts?: { nodes?: Array<{ gvspaceBlogAuthor?: AuthorNode | null }> } };
    errors?: unknown[];
  };
  if (result.errors) return undefined;
  const wanted = toLatinSlug(slug);
  const match = result.data?.posts?.nodes?.find((post) => {
    const authorSlug = post.gvspaceBlogAuthor?.slug;
    return authorSlug ? toLatinSlug(authorSlug) === wanted : false;
  });
  return match?.gvspaceBlogAuthor ? mapAuthor(match.gvspaceBlogAuthor) : undefined;
}

export async function getBlogAuthor(slug: string, locale: Locale): Promise<BlogAuthor | undefined> {
  if (!endpoint) return undefined;
  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query BlogAuthor($slug: String!, $locale: String!) {
          gvspaceBlogAuthor(slug: $slug, locale: $locale) { ${authorFields} }
        }`,
        variables: { slug, locale },
      }),
      next: { revalidate: 300 },
    });
    if (!response.ok) return authorFromAssignedPosts(slug, locale);
    const result = (await response.json()) as {
      data?: { gvspaceBlogAuthor?: AuthorNode | null };
      errors?: unknown[];
    };
    if (result.errors || !result.data?.gvspaceBlogAuthor)
      return authorFromAssignedPosts(slug, locale);
    return mapAuthor(result.data.gvspaceBlogAuthor);
  } catch {
    return undefined;
  }
}
