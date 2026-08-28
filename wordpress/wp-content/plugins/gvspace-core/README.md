# GVSPACE Core

## Технології

У WordPress з'являється розділ **Технології**.

1. У **Технології → Категорії** створіть таби зі slug: `marketing`, `development`, `systems`, `content`.
2. Додайте технологію та заповніть українську назву у заголовку.
3. За потреби вкажіть англійську назву в блоці **Налаштування технології**.
4. Завантажте SVG або PNG у **Головне зображення** — воно стане іконкою картки.
5. Оберіть одну або кілька категорій і задайте **Порядок** у Page Attributes.
6. Опублікуйте запис. Frontend оновлює дані раз на 60 секунд.

SVG дозволені лише адміністраторам. Завантажуйте тільки оптимізовані SVG із надійних джерел,
оскільки формат може містити активний вміст.

## Дублювання

У списках блогу, кейсів, вакансій і технологій під назвою запису доступна дія
**Дублювати**. Вона копіює запис разом із категоріями, позначками, метаполями та головним
зображенням і відкриває копію як чернетку.

Project-owned WordPress functionality for the headless GVSPACE website.

## Vacancy editing

- The standard title is the Ukrainian vacancy title.
- The vacancy hero banner is a shared static image managed by Next.js.
- Repeated values use one item per line.
- Publishing order can be controlled with the Order field in Page Attributes.

Activate **GVSPACE Core** together with **WPGraphQL** in WordPress Admin.

## Blog editing

- Create and publish articles in **Posts → Add New**.
- The post title, excerpt, body, publish date, author, category, tags and featured image are used by the Next.js blog automatically.
- The newest published post becomes the featured article in the catalog.
- Edit an author under **Users → Profile**. The **GVSPACE author profile** section controls the public role, headline and statistics.
- The standard WordPress biography and avatar are also displayed on the author page.
- After editing, allow up to 60 seconds for the frontend cache to refresh.

## Case studies

- Create a case under **Кейси → Додати кейс**.
- Use the standard title and featured image for the case name and catalog cover.
- Complete the **Дані кейсу** panel for metrics, services, challenge, process, architecture, gallery, testimonial and catalog filters.
- Multi-value fields use one item per line. Metrics and architecture use `value | label` pairs.
- Publish the case and allow up to 60 seconds for the frontend cache to refresh.
