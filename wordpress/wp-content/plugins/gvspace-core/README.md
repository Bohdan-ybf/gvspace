# GVSPACE Core

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
