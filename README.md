# GVSPACE Website

Multilingual corporate website built with Next.js App Router, TypeScript, Tailwind CSS and Geist.

## Live website

[View the deployed website](https://gvspace-eta.vercel.app/uk)

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

| Variable                | Description                                                                         |
| ----------------------- | ----------------------------------------------------------------------------------- |
| `NEXT_PUBLIC_SITE_URL`  | Canonical production origin without a trailing slash                                |
| `SITE_INDEXING_ENABLED` | Set to `true` only when the production site should be indexed; defaults to disabled |

Copy `.env.example` to `.env.local`. Never commit `.env.local` or production secrets.

## Project structure

```text
src/app/             Routes, metadata and global styles
src/components/      Reusable UI components
src/i18n/            Ukrainian and English dictionaries
public/images/       Optimized public image assets
```

## Deployment

The website is deployed on Vercel and connected to the GitHub repository. Pushes to the production branch trigger a new deployment automatically.

Production URL: [https://gvspace-eta.vercel.app/uk](https://gvspace-eta.vercel.app/uk)

Configure the following environment variable in Vercel without a trailing slash or locale path:

```env
NEXT_PUBLIC_SITE_URL=https://gvspace-eta.vercel.app
```

Run `npm run build` before deployment to verify the production build locally.
