import type { Metadata } from "next";
import { notFound } from "next/navigation";
import { ServicesPage } from "@/components/services-page";
import { CasesPage } from "@/components/cases-page";
import { AboutPage } from "@/components/about-page";
import { TeamPage } from "@/components/team-page";
import { CareersPage } from "@/components/careers-page";
import { VacancyDetailPage } from "@/components/vacancy-detail-page";
import { TechnologiesPage } from "@/components/technologies-page";
import { BlogPageServer } from "@/components/blog-page-server";
import { BlogArticlePage } from "@/components/blog-article-page";
import { BlogAuthorPage } from "@/components/blog-author-page";
import { CaseDetailPage } from "@/components/case-detail-page";
import { isLocale } from "@/i18n";

export const metadata: Metadata = {
  robots: { index: false, follow: false },
};
export default async function RoutedPage({
  params,
}: {
  params: Promise<{ locale: string; slug: string[] }>;
}) {
  const { locale, slug } = await params;
  if (isLocale(locale) && slug.length === 1 && slug[0] === "services") {
    return <ServicesPage locale={locale} />;
  }
  if (isLocale(locale) && slug.length === 1 && slug[0] === "cases") {
    return <CasesPage locale={locale} />;
  }
  if (isLocale(locale) && slug.length === 2 && slug[0] === "cases") {
    return <CaseDetailPage locale={locale} slug={slug[1]} />;
  }
  if (isLocale(locale) && slug.length === 1 && slug[0] === "about") {
    return <AboutPage locale={locale} />;
  }
  if (isLocale(locale) && slug.length === 1 && slug[0] === "team") {
    return <TeamPage locale={locale} />;
  }
  if (isLocale(locale) && slug.length === 1 && slug[0] === "careers") {
    return <CareersPage locale={locale} />;
  }
  if (isLocale(locale) && slug.length === 2 && slug[0] === "careers") {
    return <VacancyDetailPage locale={locale} slug={slug[1]} />;
  }
  if (isLocale(locale) && slug.length === 1 && slug[0] === "technologies") {
    return <TechnologiesPage locale={locale} />;
  }
  if (isLocale(locale) && slug.length === 1 && slug[0] === "blog") {
    return <BlogPageServer locale={locale} />;
  }
  if (isLocale(locale) && slug.length === 2 && slug[0] === "blog") {
    return <BlogArticlePage locale={locale} slug={slug[1]} />;
  }
  if (isLocale(locale) && slug.length === 3 && slug[0] === "blog" && slug[1] === "author") {
    return <BlogAuthorPage locale={locale} slug={slug[2]} />;
  }
  return notFound();
}
