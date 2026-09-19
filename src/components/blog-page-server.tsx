import type { Locale } from "@/i18n";
import { BlogDesignPage } from "./blog-design-page";
import { getBlogPosts } from "./wordpress-posts";
import { getLocalizedUrl } from "@/markets";
import { ItemListStructuredData } from "./structured-data";

export async function BlogPageServer({ locale }: { locale: Locale }) {
  const posts = await getBlogPosts(locale);
  return (
    <>
      <ItemListStructuredData
        name={locale === "uk" ? "Блог GVSPACE" : "GVSPACE Blog"}
        items={posts.map((post) => ({
          name: post.title,
          url: getLocalizedUrl(locale, `/blog/${post.slug}`),
        }))}
      />
      <BlogDesignPage locale={locale} posts={posts} />
    </>
  );
}
