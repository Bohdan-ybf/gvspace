"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import type { CSSProperties } from "react";
import { ArrowRight } from "@/components/icons/arrow-right";
import { defaultLocale, isLocale, type Locale } from "@/i18n";

const content = {
  uk: {
    eyebrow: "СТОРІНКУ НЕ ЗНАЙДЕНО",
    title: "Ця сторінка не існує або була переміщена",
    description: "Але ваш ріст — точно існує. Повертайтесь на головну або оберіть розділ нижче.",
    linksLabel: "ПЕРЕЙДІТЬ ДО",
    links: ["Кейси", "Технологічний стек", "Блог", "Контакти"],
  },
  en: {
    eyebrow: "PAGE NOT FOUND",
    title: "This page doesn’t exist or has been moved",
    description: "But your growth certainly does. Return home or choose a section below.",
    linksLabel: "CONTINUE TO",
    links: ["Cases", "Technology stack", "Blog", "Contacts"],
  },
} as const satisfies Record<Locale, unknown>;

const routes = ["cases", "technologies", "blog", "contacts"];

const arrowPath =
  "M31.3238 0H0V14.2387H27.8927C22.7041 24.3715 12.1504 31.3238 0 31.3238V45.5625C12.1312 45.5625 23.1571 40.8206 31.3238 33.0844V45.5625H45.5625V0H31.3238Z";

const digitRows = [
  "..#...##....#.",
  ".##..#..#..##.",
  "#.#..#..#.#.#.",
  "#.#..#..#.#.#.",
  "####.#..#.####",
  "..#...##....#.",
];

const digitCols = digitRows[0].length;
const digitRowsCount = digitRows.length;

const digitTiles = digitRows.flatMap((row, y) =>
  [...row].flatMap((cell, x) => (cell === "#" ? [[x, y] as const] : [])),
);

function NotFoundMark() {
  return (
    <div className="not-found-mark">
      <div
        className="not-found-digits"
        style={
          { "--not-found-cols": digitCols, "--not-found-rows": digitRowsCount } as CSSProperties
        }
      >
        {digitTiles.map(([x, y]) => (
          <div
            className="not-found-tile"
            key={`${x}-${y}`}
            style={{ left: `${(x / digitCols) * 100}%`, top: `${(y / digitRowsCount) * 100}%` }}
          >
            <svg viewBox="0 0 46 46" fill="none" aria-hidden="true">
              <path d={arrowPath} fill="#000080" />
            </svg>
          </div>
        ))}
      </div>
    </div>
  );
}

export default function NotFound() {
  const pathname = usePathname();
  const pathLocale = pathname.split("/")[1];
  const locale = isLocale(pathLocale) ? pathLocale : defaultLocale;
  const text = content[locale];

  return (
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
        <div className="not-found-code" aria-hidden="true">
          <NotFoundMark />
        </div>
      </div>
    </main>
  );
}
