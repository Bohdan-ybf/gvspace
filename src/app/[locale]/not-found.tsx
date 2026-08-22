"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { Footer } from "@/components/footer";
import { Header } from "@/components/header";
import { ArrowRight } from "@/components/icons/arrow-right";

const content = {
  uk: {
    eyebrow: "СТОРІНКУ НЕ ЗНАЙДЕНО",
    title: "Ця сторінка не існує або була переміщена",
    description: "Але ваш ріст — точно існує. Повертайтесь на головну або оберіть розділ нижче.",
    linksLabel: "АБО ПЕРЕЙДІТЬ ДО",
    links: ["Кейси", "Технологічний стек", "Блог", "Контакти"],
  },
  en: {
    eyebrow: "PAGE NOT FOUND",
    title: "This page doesn’t exist or has been moved",
    description: "But your growth certainly does. Return home or choose a section below.",
    linksLabel: "OR CONTINUE TO",
    links: ["Cases", "Technology stack", "Blog", "Contacts"],
  },
} as const;

const routes = ["cases", "technology", "blog", "contacts"];

export default function NotFound() {
  const pathname = usePathname();
  const locale = pathname.split("/")[1] === "en" ? "en" : "uk";
  const text = content[locale];

  return (
    <>
      <Header locale={locale} forceSolid />
      <main className="not-found-page">
        <div className="not-found-content container">
          <div className="not-found-copy">
            <p className="not-found-eyebrow mono">{text.eyebrow}</p>
            <h1>{text.title}</h1>
            <p className="not-found-description">{text.description}</p>
            <nav className="not-found-links" aria-label={text.linksLabel}>
              <span className="mono">{text.linksLabel}</span>
              {text.links.map((label, index) => (
                <Link key={label} href={`/${locale}/${routes[index]}`}>
                  {label}
                  <ArrowRight />
                </Link>
              ))}
            </nav>
          </div>
          <div className="not-found-code mono" aria-hidden="true">
            404
          </div>
        </div>
      </main>
      <Footer locale={locale} />
    </>
  );
}
