import type { Metadata } from "next";
import { notFound } from "next/navigation";
import { ServicesPage } from "@/components/services-page";
import { ServiceDetailPage } from "@/components/service-detail-page";
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
import { ReviewsPage } from "@/components/reviews-page";
import { isLocale } from "@/i18n";
import { locales, type Locale } from "@/i18n";
import { buildSeoMetadata, normalizeSeoData } from "@/seo";
import { getDynamicSeo, getPublishedSeoLocales, type DynamicSeoKind } from "@/wordpress-seo";
import { Breadcrumbs } from "@/components/breadcrumbs";

const routeSeo = {
  uk: {
    services: ["Послуги", "Системні рішення для розвитку бізнесу"],
    cases: ["Кейси", "Результати клієнтів та реалізовані проєкти GVSPACE"],
    reviews: ["Відгуки", "Відгуки клієнтів про співпрацю з GVSPACE"],
    about: ["Про компанію", "Команда й принципи роботи GVSPACE"],
    team: ["Команда", "Експерти GVSPACE"],
    careers: ["Вакансії", "Кар’єрні можливості у GVSPACE"],
    technologies: ["Технології", "Технологічний стек GVSPACE"],
    blog: ["Блог", "Статті про маркетинг, стратегію та IT"],
  },
  en: {
    services: ["Services", "System solutions for business growth"],
    cases: ["Cases", "GVSPACE client results and delivered projects"],
    reviews: ["Reviews", "What clients say about working with GVSPACE"],
    about: ["About", "The GVSPACE team and operating principles"],
    team: ["Team", "GVSPACE experts"],
    careers: ["Careers", "Career opportunities at GVSPACE"],
    technologies: ["Technologies", "The GVSPACE technology stack"],
    blog: ["Blog", "Insights on marketing, strategy, and IT"],
  },
} as const;

function getDynamicRoute(slug: string[]): { kind: DynamicSeoKind; publicSlug: string } | undefined {
  if (slug[0] === "blog" && slug.length === 2) return { kind: "blog", publicSlug: slug[1] };
  if (slug[0] === "cases" && slug.length === 2) return { kind: "case", publicSlug: slug[1] };
  if (slug[0] === "careers" && slug.length === 2) return { kind: "vacancy", publicSlug: slug[1] };
  if (slug[0] === "services" && slug.length >= 2)
    return { kind: "service", publicSlug: slug.at(-1) ?? "" };
  return undefined;
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: string; slug: string[] }>;
}): Promise<Metadata> {
  const { locale: localeParam, slug } = await params;
  if (!isLocale(localeParam)) return {};
  const locale: Locale = localeParam;
  const pathname = `/${slug.join("/")}`;
  const dynamicRoute = getDynamicRoute(slug);

  if (dynamicRoute) {
    const [seo, alternateLocales] = await Promise.all([
      getDynamicSeo(dynamicRoute.kind, dynamicRoute.publicSlug, locale),
      getPublishedSeoLocales(dynamicRoute.kind, dynamicRoute.publicSlug, locales),
    ]);
    if (seo) {
      return buildSeoMetadata({
        locale,
        pathname,
        seo,
        alternateLocales,
        type: dynamicRoute.kind === "blog" ? "article" : "website",
      });
    }
  }

  const section = slug[0] as keyof (typeof routeSeo)[Locale];
  const fallback = routeSeo[locale][section] ?? ["GVSPACE", "Space for managed growth"];
  const seo = normalizeSeoData(undefined, { title: fallback[0], description: fallback[1] });
  return buildSeoMetadata({ locale, pathname, seo, alternateLocales: locales });
}
export default async function RoutedPage({
  params,
}: {
  params: Promise<{ locale: string; slug: string[] }>;
}) {
  const { locale, slug } = await params;
  const section = (key: keyof (typeof routeSeo)[Locale], content: React.ReactNode) => (
    <>
      <Breadcrumbs
        locale={locale as Locale}
        items={[{ label: routeSeo[locale as Locale][key][0] }]}
      />
      {content}
    </>
  );
  if (isLocale(locale) && slug.length === 1 && slug[0] === "services") {
    return section("services", <ServicesPage locale={locale} />);
  }
  if (isLocale(locale) && slug.length >= 2 && slug.length <= 3 && slug[0] === "services") {
    return <ServiceDetailPage locale={locale} slugs={slug.slice(1)} />;
  }
  if (isLocale(locale) && slug.length === 1 && slug[0] === "cases") {
    return section("cases", <CasesPage locale={locale} />);
  }
  if (isLocale(locale) && slug.length === 1 && slug[0] === "reviews") {
    return section("reviews", <ReviewsPage locale={locale} />);
  }
  if (isLocale(locale) && slug.length === 2 && slug[0] === "cases") {
    return <CaseDetailPage locale={locale} slug={slug[1]} />;
  }
  if (isLocale(locale) && slug.length === 1 && slug[0] === "about") {
    return section("about", <AboutPage locale={locale} />);
  }
  if (isLocale(locale) && slug.length === 1 && slug[0] === "team") {
    return section("team", <TeamPage locale={locale} />);
  }
  if (isLocale(locale) && slug.length === 1 && slug[0] === "careers") {
    return section("careers", <CareersPage locale={locale} />);
  }
  if (isLocale(locale) && slug.length === 2 && slug[0] === "careers") {
    return <VacancyDetailPage locale={locale} slug={slug[1]} />;
  }
  if (isLocale(locale) && slug.length === 1 && slug[0] === "technologies") {
    return section("technologies", <TechnologiesPage locale={locale} />);
  }
  if (isLocale(locale) && slug.length === 1 && slug[0] === "blog") {
    return section("blog", <BlogPageServer locale={locale} />);
  }
  if (isLocale(locale) && slug.length === 2 && slug[0] === "blog") {
    return <BlogArticlePage locale={locale} slug={slug[1]} />;
  }
  if (isLocale(locale) && slug.length === 3 && slug[0] === "blog" && slug[1] === "author") {
    return (
      <>
        <Breadcrumbs
          locale={locale}
          items={[{ label: routeSeo[locale].blog[0], pathname: "/blog" }, { label: slug[2] }]}
        />
        <BlogAuthorPage locale={locale} slug={slug[2]} />
      </>
    );
  }
  return notFound();
}
