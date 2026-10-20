import type { Metadata } from "next";
import { cache } from "react";
import { notFound, permanentRedirect } from "next/navigation";
import { ServicesPage } from "@/components/services-page";
import { ServiceDetailPage } from "@/components/service-detail-page";
import { CasesPage } from "@/components/cases-page";
import { AboutPage } from "@/components/about-page";
import { TeamPage } from "@/components/team-page";
import { CareersPage } from "@/components/careers-page";
import { VacancyDetailPage } from "@/components/vacancy-detail-page";
import { getVacancyBySlug } from "@/components/wordpress-vacancies";
import { getBlogPost } from "@/components/wordpress-posts";
import { getCaseStudy } from "@/components/wordpress-cases";
import { getServiceOffering } from "@/components/wordpress-services";
import { getTechnologyBySlug } from "@/components/wordpress-technologies";
import { TechnologiesPage } from "@/components/technologies-page";
import { IndustriesPage } from "@/components/industries-page";
import { PartnersPage } from "@/components/partners-page";
import { TechnologyDetailPage } from "@/components/technology-detail-page";
import { BlogPageServer } from "@/components/blog-page-server";
import { BlogArticleDesignPage } from "@/components/blog-article-design-page";
import { BlogAuthorPage } from "@/components/blog-author-page";
import { CaseDetailPage } from "@/components/case-detail-page";
import { ReviewsPage } from "@/components/reviews-page";
import { isLocale } from "@/i18n";
import { locales, type Locale } from "@/i18n";
import { buildSeoMetadata, normalizeSeoData } from "@/seo";
import { getDynamicSeo, getStaticPageSeo, type DynamicSeoKind } from "@/wordpress-seo";
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
    industries: ["Індустрії", "Ніші, де GVSPACE знає специфіку звернення"],
    partners: ["Партнерство", "Партнерська програма GVSPACE Partners"],
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
    industries: ["Industries", "Niches where GVSPACE knows the specifics of the request"],
    partners: ["Partnership", "The GVSPACE Partners program"],
    blog: ["Blog", "Insights on marketing, strategy, and IT"],
  },
} as const;

function getDynamicRoute(slug: string[]): { kind: DynamicSeoKind; publicSlug: string } | undefined {
  if (slug[0] === "blog" && slug.length === 2) return { kind: "blog", publicSlug: slug[1] };
  if (slug[0] === "cases" && slug.length === 2) return { kind: "case", publicSlug: slug[1] };
  if (slug[0] === "careers" && slug.length === 2) return { kind: "vacancy", publicSlug: slug[1] };
  if (slug[0] === "technologies" && slug.length === 2)
    return { kind: "technology", publicSlug: slug[1] };
  if (slug[0] === "services" && slug.length >= 2)
    return { kind: "service", publicSlug: slug.at(-1) ?? "" };
  return undefined;
}

const staticRoutes = new Set([
  "services",
  "cases",
  "reviews",
  "about",
  "team",
  "careers",
  "technologies",
  "industries",
  "partners",
  "blog",
]);

export const revalidate = 300;

export function generateStaticParams() {
  return locales.flatMap((locale) => [...staticRoutes].map((route) => ({ locale, slug: [route] })));
}

const getResolvedDynamicSeo = cache(async function getResolvedDynamicSeo(
  kind: DynamicSeoKind,
  publicSlug: string,
  locale: Locale,
  routeSlugs: string[],
) {
  const configuredSeo = await getDynamicSeo(kind, publicSlug, locale);

  if (kind === "service") {
    const result = await getServiceOffering(locale, routeSlugs.slice(1));
    if (!result) return undefined;
    const genericTitle = /^(?:services|послуги)(?:\s*\|\s*gvspace)?$/i.test(
      configuredSeo?.title ?? "",
    );
    const genericDescription = new Set([
      "System solutions for business growth",
      "Системні рішення для розвитку бізнесу",
    ]).has(configuredSeo?.description ?? "");
    const sourceDescription = result.item.description || result.item.headline;
    const generatedDescription =
      locale === "uk"
        ? `${result.item.title}: ${sourceDescription}. Системне рішення GVSPACE для вимірюваного розвитку бізнесу.`
        : `${result.item.title}: ${sourceDescription}. A GVSPACE system solution designed for measurable business growth.`;
    const description =
      configuredSeo?.description &&
      !genericDescription &&
      configuredSeo.description.trim().length >= 70
        ? configuredSeo.description
        : generatedDescription.length > 160
          ? `${generatedDescription.slice(0, 157).replace(/\s+\S*$/, "")}…`
          : generatedDescription;
    return normalizeSeoData(
      {
        ...configuredSeo,
        title: !configuredSeo?.title || genericTitle ? result.item.title : configuredSeo.title,
        description,
      },
      { title: result.item.title, description },
    );
  }
  if (configuredSeo) return configuredSeo;
  if (kind === "blog") {
    const post = await getBlogPost(publicSlug, locale);
    return post
      ? normalizeSeoData(undefined, { title: post.title, description: post.excerpt })
      : undefined;
  }
  if (kind === "case") {
    const item = await getCaseStudy(publicSlug, locale);
    return item
      ? normalizeSeoData(undefined, { title: item.title, description: item.excerpt })
      : undefined;
  }
  if (kind === "vacancy") {
    const vacancy = await getVacancyBySlug(publicSlug, locale);
    return vacancy
      ? normalizeSeoData(undefined, { title: vacancy.title, description: vacancy.excerpt })
      : undefined;
  }
  const technology = await getTechnologyBySlug(publicSlug, locale);
  return technology
    ? normalizeSeoData(undefined, {
        title: technology.title,
        description: technology.intro || technology.description,
      })
    : undefined;
});

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
    const [seo, localizedSeo] = await Promise.all([
      getResolvedDynamicSeo(dynamicRoute.kind, dynamicRoute.publicSlug, locale, slug),
      Promise.all(
        locales.map(async (candidate) => ({
          locale: candidate,
          seo: await getResolvedDynamicSeo(
            dynamicRoute.kind,
            dynamicRoute.publicSlug,
            candidate,
            slug,
          ),
        })),
      ),
    ]);
    if (!seo) notFound();
    const alternateLocales = localizedSeo
      .filter((entry) => entry.seo?.robotsIndex !== false)
      .map((entry) => entry.locale);
    return buildSeoMetadata({
      locale,
      pathname,
      seo,
      alternateLocales,
      type: dynamicRoute.kind === "blog" ? "article" : "website",
    });
  }

  const isAuthorRoute = slug.length === 3 && slug[0] === "blog" && slug[1] === "author";
  if (!(slug.length === 1 && staticRoutes.has(slug[0])) && !isAuthorRoute) notFound();

  const section = slug[0] as keyof (typeof routeSeo)[Locale];
  const fallback = routeSeo[locale][section] ?? ["GVSPACE", "Space for managed growth"];
  const adminSeo =
    section in routeSeo[locale] ? await getStaticPageSeo(section, locale) : undefined;
  const seo = normalizeSeoData(adminSeo, { title: fallback[0], description: fallback[1] });
  return buildSeoMetadata({ locale, pathname, seo, alternateLocales: locales });
}
export default async function RoutedPage({
  params,
}: {
  params: Promise<{ locale: string; slug: string[] }>;
}) {
  const { locale, slug } = await params;
  if (!isLocale(locale)) notFound();

  if (
    slug.length === 3 &&
    slug[0] === "blog" &&
    slug[1] === "author" &&
    slug[2] === "gvspace_admin"
  ) {
    permanentRedirect("/blog");
  }

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
    return <ServicesPage locale={locale} />;
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
  if (isLocale(locale) && slug.length === 1 && slug[0] === "industries") {
    return <IndustriesPage locale={locale} />;
  }
  if (isLocale(locale) && slug.length === 1 && slug[0] === "partners") {
    return <PartnersPage locale={locale} />;
  }
  if (isLocale(locale) && slug.length === 1 && slug[0] === "technologies") {
    return section("technologies", <TechnologiesPage locale={locale} />);
  }
  if (isLocale(locale) && slug.length === 2 && slug[0] === "technologies") {
    return <TechnologyDetailPage locale={locale} slug={slug[1]} />;
  }
  if (isLocale(locale) && slug.length === 1 && slug[0] === "blog") {
    return <BlogPageServer locale={locale} />;
  }
  if (isLocale(locale) && slug.length === 2 && slug[0] === "blog") {
    return <BlogArticleDesignPage locale={locale} slug={slug[1]} />;
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
