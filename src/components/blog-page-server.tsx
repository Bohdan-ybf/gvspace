import type { Locale } from "@/i18n";
import { BlogPage } from "./blog-page";
import { getBlogPosts } from "./wordpress-posts";

export async function BlogPageServer({ locale }: { locale: Locale }) {
  const posts = await getBlogPosts(locale);
  return <BlogPage locale={locale} posts={posts} />;
}
