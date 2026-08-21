# GVSPACE Website

Multilingual corporate website built with Next.js App Router, TypeScript, Tailwind CSS and Geist.

## Requirements

- Node.js 22 LTS
- npm 10+

## Local development

```bash
npm ci
copy .env.example .env.local
npm run dev
```

The site is available at `http://localhost:3000`. Ukrainian is the default locale (`/uk`), with English available at `/en`.

## Quality checks

```bash
npm run check
npm run build
```

`npm run check` runs formatting verification, ESLint and TypeScript validation.

## Environment variables

| Variable               | Description                                          |
| ---------------------- | ---------------------------------------------------- |
| `NEXT_PUBLIC_SITE_URL` | Canonical production origin without a trailing slash |

Copy `.env.example` to `.env.local`. Never commit `.env.local` or production secrets.

## Project structure

```text
src/app/             Routes, metadata and global styles
src/components/      Reusable UI components
src/i18n/            Ukrainian and English dictionaries
public/images/       Optimized public image assets
```

## Deployment

Run `npm run build` before deployment. The application requires a Next.js-compatible Node.js runtime. Configure `NEXT_PUBLIC_SITE_URL` with the final production domain.
