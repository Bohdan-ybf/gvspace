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

| Variable                    | Description                                                                         |
| --------------------------- | ----------------------------------------------------------------------------------- |
| `NEXT_PUBLIC_SITE_URL`      | Canonical production origin without a trailing slash                                |
| `NEXT_PUBLIC_INTL_SITE_URL` | International (`en`) production origin without a trailing slash                     |
| `NEXT_PUBLIC_UK_SITE_URL`   | Ukrainian (`uk`) production origin without a trailing slash                         |
| `SITE_INDEXING_ENABLED`     | Set to `true` only when the production site should be indexed; defaults to disabled |

Copy `.env.example` to `.env.local`. Never commit `.env.local` or production secrets.

## Markets and domains

`src/markets.ts` is the single registry for country domains, content locales, frontend readiness and fallbacks. `gvspace.com` and `gvspace.com.ua` are currently enabled. Reserved country domains are configured but disabled: requests to them are redirected to `gvspace.com`, while `gvspace.ua` falls back to `gvspace.com.ua`.

Do not enable a market until all of the following are ready:

1. Static dictionaries exist for its `routeLocale`.
2. WordPress content for its `contentLocale` is reviewed and marked **Published**.
3. DNS, TLS and the deployment proxy accept the domain.
4. Canonical URLs, language alternates and the sitemap have been verified.

Global indexing remains controlled by `SITE_INDEXING_ENABLED` and must stay `false` during development.

### Adding a frontend locale

Use this order when a country version is prepared:

1. Add the locale to `src/i18n/index.ts`.
2. Add its file to every directory in `src/i18n/pages/` and register each file in `src/i18n/pages/index.ts`.
3. Set the market's `routeLocale` in `src/markets.ts`. Keep `enabled: false` while content is reviewed.
4. Run `npm run check` and `npm run build`. The typed translation registry fails when any page dictionary is missing, and market validation rejects unavailable fallbacks or an enabled market without dictionaries.
5. After DNS, TLS, WordPress content and SEO metadata are verified, change only that market's `enabled` value to `true`.

This final `enabled` switch is the release gate. Disabled markets remain outside the sitemap and redirect to their configured fallback.

## Project structure

```text
src/app/             Routes, metadata and global styles
src/components/      Reusable UI components
src/i18n/            Ukrainian and English dictionaries
public/images/       Optimized public image assets
```

## Deployment

Production runs on a VPS with Docker Compose, Caddy, WordPress and MySQL. Successful pushes to `main` are deployed automatically after CI. See [DEPLOYMENT.md](./DEPLOYMENT.md) for initial server setup, DNS, secrets, migration, backups and rollback.
