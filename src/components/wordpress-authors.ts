import type { Locale } from "@/i18n";

export type BlogAuthor = {
  slug: string;
  name: string;
  role: string;
  headline: string;
  bio: string;
  experience: string;
  projects: string;
  photo?: string;
};

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

export async function getBlogAuthor(slug: string, locale: Locale): Promise<BlogAuthor | undefined> {
  if (endpoint) {
    try {
      const response = await fetch(endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          query: `query Author($slug: ID!) { user(id: $slug, idType: SLUG) { slug name description avatar { url } gvspaceAuthorProfile { role headline experience projects } } }`,
          variables: { slug },
        }),
        next: { revalidate: 60 },
      });
      const result = (await response.json()) as {
        data?: {
          user?: {
            slug: string;
            name: string;
            description?: string;
            avatar?: { url?: string };
            gvspaceAuthorProfile?: {
              role?: string;
              headline?: string;
              experience?: string;
              projects?: string;
            };
          };
        };
      };
      const author = result.data?.user;
      if (author) {
        return {
          slug: author.slug,
          name: author.name,
          role: author.gvspaceAuthorProfile?.role || "GVSPACE",
          headline:
            author.gvspaceAuthorProfile?.headline ||
            author.description ||
            (locale === "uk" ? "Автор GVSPACE" : "GVSPACE author"),
          bio: author.description || "",
          experience: author.gvspaceAuthorProfile?.experience || "—",
          projects: author.gvspaceAuthorProfile?.projects || "—",
          photo: author.avatar?.url,
        };
      }
    } catch {
      return undefined;
    }
  }
  return undefined;
}
