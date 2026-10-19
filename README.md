# GVSPACE

Корпоративний мультимовний сайт на Next.js 16, React, TypeScript і Tailwind CSS. Контент надходить із WordPress через GraphQL. Активні домени: `gvspace.com` (EN) та `gvspace.ua` (UK).

## Запуск

Потрібен Node.js `20.9+`.

```bash
npm ci
copy .env.example .env.local
npm run dev
```

Локальна адреса: `http://localhost:3000`.

## Основні команди

```bash
npm run dev                  # локальна розробка
npm run format               # автоматичне форматування коду
npm run check                # форматування, ESLint і TypeScript
npm run build                # production-збірка
npm run start                # запуск production-збірки
npm run images:audit         # аудит локальних зображень
npm run images:optimize:dry  # попередній розрахунок оптимізації
npm run images:optimize      # створення WebP без видалення оригіналів
```

## Налаштування

Основні змінні в `.env.local`:

```env
NEXT_PUBLIC_SITE_URL=https://gvspace.com
SITE_INDEXING_ENABLED=false
WORDPRESS_URL=http://localhost:8080
WORDPRESS_GRAPHQL_URL=http://localhost:8080/graphql
```

Не додавайте `.env.local` і секрети до Git. Деталі production-розгортання: [DEPLOYMENT.md](./DEPLOYMENT.md).
