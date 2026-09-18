<?php

if (!defined('ABSPATH')) {
    exit;
}

function gvspace_technology_locale_field(int $post_id, string $field, string $locale): string
{
    $read = static fn (string $key): string => (string) get_post_meta($post_id, $key, true);
    $central = $field === 'title'
        ? $read('_gvspace_technology_title_' . $locale)
        : $read('_gvspace_technology_' . $field . '_' . $locale);
    if ($central !== '') return $central;

    if ($field === 'title') {
        if ($locale === 'en') {
            $en = $read('_gvspace_technology_title_en');
            if ($en !== '') return $en;
        }
        if ($locale !== 'uk') {
            $uk = $read('_gvspace_technology_title_uk');
            if ($uk !== '') return $uk;
        }
        return (string) get_the_title($post_id);
    }

    if ($locale !== 'uk') {
        $uk = $read('_gvspace_technology_' . $field . '_uk');
        if ($uk !== '') return $uk;
    }
    return '';
}

function gvspace_default_technology_tabs(): array
{
    return [
        'marketing' => ['uk' => 'Маркетинг', 'en' => 'Marketing'],
        'development' => ['uk' => 'Розробка', 'en' => 'Development'],
        'systems' => ['uk' => 'Системи', 'en' => 'Systems'],
        'content' => ['uk' => 'Контент', 'en' => 'Content'],
    ];
}

function gvspace_get_technology_tab_options(): array
{
    $terms = get_terms([
        'taxonomy' => 'gv_technology_category',
        'hide_empty' => false,
    ]);
    $options = [];
    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            $options[$term->slug] = $term->name;
        }
    }
    foreach (gvspace_default_technology_tabs() as $slug => $names) {
        if (!isset($options[$slug])) $options[$slug] = $names['uk'];
    }
    return $options;
}

function gvspace_technology_tab_name(WP_Term $term, string $locale): string
{
    $localized = (string) get_term_meta($term->term_id, '_gvspace_name_' . $locale, true);
    if ($localized !== '') return $localized;
    if ($locale !== 'uk') {
        $uk = (string) get_term_meta($term->term_id, '_gvspace_name_uk', true);
        if ($uk !== '') return $uk;
    }
    $defaults = gvspace_default_technology_tabs();
    if (isset($defaults[$term->slug][$locale])) return $defaults[$term->slug][$locale];
    return $term->name;
}

function gvspace_ensure_technology_tab(string $name, string $slug = ''): string
{
    $slug = $slug !== '' ? sanitize_title($slug) : sanitize_title($name);
    if ($slug === '') return '';
    $existing = get_term_by('slug', $slug, 'gv_technology_category');
    if ($existing instanceof WP_Term) return $existing->slug;
    $created = wp_insert_term($name, 'gv_technology_category', ['slug' => $slug]);
    if (is_wp_error($created)) return '';
    return $slug;
}

function gvspace_technology_catalog(): array
{
    return [
        [
            'group' => 'google-analytics',
            'icon' => 'google-analytics',
            'order' => 0,
            'tabs' => ['marketing'],
            'uk' => [
                'title' => 'Google Analytics 4',
                'description' => 'Поведінковий аналіз аудиторії, воронки конверсій, event-трекінг.',
                'tag' => 'АНАЛІТИКА САЙТУ',
            ],
            'en' => [
                'title' => 'Google Analytics 4',
                'description' => 'Audience behavior analysis, conversion funnels, and event tracking.',
                'tag' => 'SITE ANALYTICS',
            ],
        ],
        [
            'group' => 'google-tag-manager',
            'icon' => 'google-tag-manager',
            'order' => 1,
            'tabs' => ['marketing'],
            'uk' => [
                'title' => 'Google Tag Manager',
                'description' => 'Управління всіма тегами та пікселями без правок у коді.',
                'tag' => 'ТЕГУВАННЯ',
            ],
            'en' => [
                'title' => 'Google Tag Manager',
                'description' => 'Manage every tag and pixel without touching the codebase.',
                'tag' => 'TAGGING',
            ],
        ],
        [
            'group' => 'meta-ads',
            'icon' => '',
            'order' => 2,
            'tabs' => ['marketing'],
            'uk' => [
                'title' => 'Meta Ads',
                'description' => 'Ретаргетинг, lookalike-аудиторії, оптимізація конверсій.',
                'tag' => 'ПЛАТНА РЕКЛАМА',
            ],
            'en' => [
                'title' => 'Meta Ads',
                'description' => 'Retargeting, lookalike audiences, and conversion optimization.',
                'tag' => 'PAID ADS',
            ],
        ],
        [
            'group' => 'google-ads',
            'icon' => 'google-ads',
            'order' => 3,
            'tabs' => ['marketing'],
            'uk' => [
                'title' => 'Google Ads',
                'description' => 'Search, Performance Max, Display кампанії з фокусом на ROI.',
                'tag' => 'ПЛАТНА РЕКЛАМА',
            ],
            'en' => [
                'title' => 'Google Ads',
                'description' => 'Search, Performance Max, and Display campaigns focused on ROI.',
                'tag' => 'PAID ADS',
            ],
        ],
        [
            'group' => 'looker-studio',
            'icon' => 'looker-studio',
            'order' => 4,
            'tabs' => ['marketing', 'systems'],
            'uk' => [
                'title' => 'Looker Studio',
                'description' => 'Кастомні дашборди, де власник бачить реальний стан без жаргону.',
                'tag' => 'ЗВІТНІСТЬ',
            ],
            'en' => [
                'title' => 'Looker Studio',
                'description' => 'Custom dashboards that show owners the real picture without jargon.',
                'tag' => 'REPORTING',
            ],
        ],
        [
            'group' => 'power-bi',
            'icon' => '',
            'order' => 5,
            'tabs' => ['marketing', 'systems'],
            'uk' => [
                'title' => 'Power BI',
                'description' => 'Глибока бізнес-аналітика для складних воронок і юніт-економіки.',
                'tag' => 'BI & ЗВІТНІСТЬ',
            ],
            'en' => [
                'title' => 'Power BI',
                'description' => 'Deep business analytics for complex funnels and unit economics.',
                'tag' => 'BI & REPORTING',
            ],
        ],
        [
            'group' => 'tiktok-ads',
            'icon' => 'tiktok-ads',
            'order' => 6,
            'tabs' => ['marketing'],
            'uk' => [
                'title' => 'TikTok Ads',
                'description' => 'Платна реклама для аудиторій, яких немає в інших каналах.',
                'tag' => 'ПЛАТНА РЕКЛАМА',
            ],
            'en' => [
                'title' => 'TikTok Ads',
                'description' => 'Paid acquisition for audiences that other channels do not reach.',
                'tag' => 'PAID ADS',
            ],
        ],
        [
            'group' => 'search-console',
            'icon' => 'search-console',
            'order' => 7,
            'tabs' => ['marketing'],
            'uk' => [
                'title' => 'Google Search Console',
                'description' => 'SEO-моніторинг, аналіз пошукових запитів, індексація.',
                'tag' => 'SEO',
            ],
            'en' => [
                'title' => 'Google Search Console',
                'description' => 'SEO monitoring, query analysis, and index coverage.',
                'tag' => 'SEO',
            ],
        ],
        [
            'group' => 'wordpress',
            'icon' => '',
            'order' => 8,
            'tabs' => ['development'],
            'uk' => [
                'title' => 'WordPress',
                'description' => 'Керований контент і адмінка для маркетингових команд без розробки на кожну правку.',
                'tag' => 'CMS',
            ],
            'en' => [
                'title' => 'WordPress',
                'description' => 'Managed content and an admin for marketing teams without a ticket for every change.',
                'tag' => 'CMS',
            ],
        ],
        [
            'group' => 'nextjs',
            'icon' => 'nextjs',
            'order' => 9,
            'tabs' => ['development'],
            'uk' => [
                'title' => 'Next.js',
                'description' => 'Швидкі публічні сайти з контролем SEO, маршрутів і продуктивності.',
                'tag' => 'FRONTEND',
            ],
            'en' => [
                'title' => 'Next.js',
                'description' => 'Fast public sites with control over SEO, routing, and performance.',
                'tag' => 'FRONTEND',
            ],
        ],
        [
            'group' => 'react',
            'icon' => '',
            'order' => 10,
            'tabs' => ['development'],
            'uk' => [
                'title' => 'React',
                'description' => 'Інтерактивні інтерфейси для складних кабінетів, форм і кабінетів клієнта.',
                'tag' => 'FRONTEND',
            ],
            'en' => [
                'title' => 'React',
                'description' => 'Interactive interfaces for complex dashboards, forms, and client portals.',
                'tag' => 'FRONTEND',
            ],
        ],
        [
            'group' => 'docker',
            'icon' => '',
            'order' => 11,
            'tabs' => ['systems'],
            'uk' => [
                'title' => 'Docker',
                'description' => 'Однакові середовища від розробки до продакшену без сюрпризів на сервері.',
                'tag' => 'INFRA',
            ],
            'en' => [
                'title' => 'Docker',
                'description' => 'The same environments from development to production, without server surprises.',
                'tag' => 'INFRA',
            ],
        ],
        [
            'group' => 'google-cloud',
            'icon' => '',
            'order' => 12,
            'tabs' => ['systems'],
            'uk' => [
                'title' => 'Google Cloud',
                'description' => 'Хмарна інфраструктура для аналітики, сховищ даних і стабільного хостингу.',
                'tag' => 'CLOUD',
            ],
            'en' => [
                'title' => 'Google Cloud',
                'description' => 'Cloud infrastructure for analytics, data storage, and reliable hosting.',
                'tag' => 'CLOUD',
            ],
        ],
        [
            'group' => 'figma',
            'icon' => '',
            'order' => 13,
            'tabs' => ['content'],
            'uk' => [
                'title' => 'Figma',
                'description' => 'Дизайн-система, макети лендингів і узгодження візуалу з клієнтом в одному місці.',
                'tag' => 'ДИЗАЙН',
            ],
            'en' => [
                'title' => 'Figma',
                'description' => 'Design systems, landing-page layouts, and visual alignment with the client in one place.',
                'tag' => 'DESIGN',
            ],
        ],
        [
            'group' => 'notion',
            'icon' => '',
            'order' => 14,
            'tabs' => ['content'],
            'uk' => [
                'title' => 'Notion',
                'description' => 'Контент-операції, брифи, редакційний календар і база знань команди.',
                'tag' => 'КОНТЕНТ',
            ],
            'en' => [
                'title' => 'Notion',
                'description' => 'Content operations, briefs, editorial calendar, and the team knowledge base.',
                'tag' => 'CONTENT',
            ],
        ],
    ];
}

add_action('gv_technology_category_add_form_fields', function (): void {
    echo '<div class="form-field"><label for="gvspace_tab_name_en">Назва англійською</label>';
    echo '<input type="text" id="gvspace_tab_name_en" name="gvspace_tab_name_en">';
    echo '<p>Українська назва задається в полі вище. Якщо англійську не вказати, на сайті використається українська.</p></div>';
});
add_action('gv_technology_category_edit_form_fields', function (WP_Term $term): void {
    $en = (string) get_term_meta($term->term_id, '_gvspace_name_en', true);
    $order = (string) get_term_meta($term->term_id, '_gvspace_order', true);
    echo '<tr class="form-field"><th><label for="gvspace_tab_name_en">Назва англійською</label></th><td>';
    echo '<input type="text" id="gvspace_tab_name_en" name="gvspace_tab_name_en" value="' . esc_attr($en) . '">';
    echo '</td></tr>';
    echo '<tr class="form-field"><th><label for="gvspace_tab_order">Порядок</label></th><td>';
    echo '<input type="number" id="gvspace_tab_order" name="gvspace_tab_order" value="' . esc_attr($order) . '">';
    echo '<p class="description">Менше число показується раніше в табах.</p></td></tr>';
});
add_action('created_gv_technology_category', 'gvspace_save_technology_tab_meta');
add_action('edited_gv_technology_category', 'gvspace_save_technology_tab_meta');
function gvspace_save_technology_tab_meta(int $term_id): void
{
    $term = get_term($term_id, 'gv_technology_category');
    if ($term instanceof WP_Term) {
        update_term_meta($term_id, '_gvspace_name_uk', $term->name);
    }
    if (isset($_POST['gvspace_tab_name_en'])) {
        update_term_meta($term_id, '_gvspace_name_en', sanitize_text_field(wp_unslash((string) $_POST['gvspace_tab_name_en'])));
    }
    if (isset($_POST['gvspace_tab_order'])) {
        update_term_meta($term_id, '_gvspace_order', (string) absint($_POST['gvspace_tab_order']));
    }
}

function gvspace_seed_technology_tabs(): void
{
    $order = 0;
    foreach (gvspace_default_technology_tabs() as $slug => $names) {
        $term = get_term_by('slug', $slug, 'gv_technology_category');
        if (!$term instanceof WP_Term) {
            $created = wp_insert_term($names['uk'], 'gv_technology_category', ['slug' => $slug]);
            if (is_wp_error($created)) continue;
            $term = get_term((int) $created['term_id'], 'gv_technology_category');
        }
        if (!$term instanceof WP_Term) continue;
        wp_update_term($term->term_id, 'gv_technology_category', ['name' => $names['uk']]);
        update_term_meta($term->term_id, '_gvspace_name_uk', $names['uk']);
        update_term_meta($term->term_id, '_gvspace_name_en', $names['en']);
        update_term_meta($term->term_id, '_gvspace_order', (string) $order);
        $order++;
    }
}

function gvspace_import_technology_icon(string $slug): int
{
    if ($slug === '') return 0;
    $existing = get_posts([
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'numberposts' => 1,
        'fields' => 'ids',
        'meta_key' => '_gvspace_technology_icon',
        'meta_value' => $slug,
    ]);
    if ($existing) return (int) $existing[0];

    $source = __DIR__ . '/assets/technologies/' . $slug . '.svg';
    if (!is_readable($source)) return 0;

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $GLOBALS['gvspace_allow_svg_seed'] = true;
    $upload = wp_upload_bits($slug . '.svg', null, (string) file_get_contents($source));
    $GLOBALS['gvspace_allow_svg_seed'] = false;
    if (!empty($upload['error']) || empty($upload['file'])) return 0;

    $attachment_id = wp_insert_attachment([
        'post_mime_type' => 'image/svg+xml',
        'post_title' => $slug,
        'post_status' => 'inherit',
        'guid' => $upload['url'],
    ], $upload['file']);
    if (!$attachment_id || is_wp_error($attachment_id)) return 0;

    wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $upload['file']));
    update_post_meta($attachment_id, '_gvspace_technology_icon', $slug);
    return (int) $attachment_id;
}

function gvspace_find_technology_post_id(string $group): int
{
    $found = get_posts([
        'post_type' => 'gv_technology',
        'post_status' => 'any',
        'numberposts' => 1,
        'fields' => 'ids',
        'meta_key' => '_gvspace_translation_group',
        'meta_value' => $group,
    ]);
    if ($found) return (int) $found[0];

    $by_slug = get_page_by_path($group, OBJECT, 'gv_technology');
    return $by_slug ? (int) $by_slug->ID : 0;
}

function gvspace_migrate_legacy_technologies(): void
{
    $posts = get_posts([
        'post_type' => 'gv_technology',
        'post_status' => 'any',
        'numberposts' => -1,
    ]);
    foreach ($posts as $post) {
        if ((string) get_post_meta($post->ID, '_gvspace_technology_title_uk', true) === '') {
            update_post_meta($post->ID, '_gvspace_technology_title_uk', $post->post_title);
        }
        if ((string) get_post_meta($post->ID, '_gvspace_translation_group', true) === '') {
            update_post_meta($post->ID, '_gvspace_translation_group', sanitize_title($post->post_name ?: $post->post_title));
        }
        update_post_meta($post->ID, '_gvspace_content_locale', 'legacy');
        update_post_meta($post->ID, '_gvspace_translation_status', 'published');
    }
}

function gvspace_join_pipe_rows(array $rows): string
{
    $lines = [];
    foreach ($rows as $row) {
        $lines[] = trim((string) $row[0]) . ' | ' . trim((string) $row[1]);
    }
    return implode("\n", $lines);
}

function gvspace_technology_example_meet(string $group): array
{
    if ($group === 'google-ads') {
        return [
            'uk' => [
                'meet_name' => 'Василь Горайчук',
                'meet_role' => 'CEO & FOUNDER',
                'meet_quote' => 'Рекламний бюджет має працювати як система: кожна гривня має відповідального, метрику і правило масштабування.',
                'meet_years' => '6',
                'meet_projects' => '150',
                'meet_tags' => "GOOGLE ADS\nMETA ADS\nANALYTICS",
            ],
            'en' => [
                'meet_name' => 'Vasyl Horaichuk',
                'meet_role' => 'CEO & FOUNDER',
                'meet_quote' => 'Ad spend should work as a system: every dollar has an owner, a metric, and a scaling rule.',
                'meet_years' => '6',
                'meet_projects' => '150',
                'meet_tags' => "GOOGLE ADS\nMETA ADS\nANALYTICS",
            ],
        ];
    }

    return [
        'uk' => [
            'meet_name' => 'Богдан Яронний',
            'meet_role' => 'LEAD DEVELOPER',
            'meet_quote' => 'Моє завдання — перетворити бізнес-цілі на технології, які не просто працюють сьогодні, а залишають простір для зростання завтра.',
            'meet_years' => '6',
            'meet_projects' => '150',
            'meet_tags' => "NEXT.JS\nNODE.JS\nAWS",
        ],
        'en' => [
            'meet_name' => 'Bohdan Yaronnyi',
            'meet_role' => 'LEAD DEVELOPER',
            'meet_quote' => 'My job is to turn business goals into technology that works today and still has room to grow tomorrow.',
            'meet_years' => '6',
            'meet_projects' => '150',
            'meet_tags' => "NEXT.JS\nNODE.JS\nAWS",
        ],
    ];
}

function gvspace_technology_page_overrides(): array
{
    $next_meet = gvspace_technology_example_meet('nextjs');
    $ads_meet = gvspace_technology_example_meet('google-ads');
    return [
        'nextjs' => [
            'related_case' => '',
            'uk' => array_merge($next_meet['uk'], [
                'intro' => 'Реакт-фреймворк для серверного рендерингу та статичної генерації. Обираємо, коли потрібні SEO-видимість і швидкість завантаження без компромісів у функціональності.',
                'why' => 'Next.js вирішує головну проблему SPA: сторінки не індексуються пошуковиками. **SSR і SSG дають SEO-видимість із першого дня** — без окремої SEO-інфраструктури.',
                'triggers' => gvspace_join_pipe_rows([
                    ['SEO є пріоритетом росту', 'Проєкт залежить від органічного пошуку: лендінги, корпоративні сайти, контентні платформи.'],
                    ['Потрібна висока швидкість', 'Core Web Vitals критичні для конверсій. SSG генерує статичні сторінки миттєво.'],
                    ['Гібридна архітектура', 'Частина сторінок статична, частина — динамічна з API. Next.js поєднує обидва підходи.'],
                ]),
                'uses' => gvspace_join_pipe_rows([
                    ['Корпоративні та маркетингові сайти', 'SSG для статичних сторінок + API routes для форм і CRM-інтеграцій.'],
                    ['Лендінги для performance-кампаній', 'Швидке завантаження знижує bounce rate і підвищує Quality Score.'],
                    ['Клієнтські портали з авторизацією', 'App Router + middleware для захищених зон без окремого бекенду.'],
                    ['Інтеграція з headless CMS', 'Contentful, Sanity або Strapi як бекенд — редагування без розробника.'],
                ]),
                'faq' => gvspace_join_pipe_rows([
                    ['Скільки часу займає впровадження Next.js?', 'Типовий маркетинговий сайт запускаємо за кілька тижнів. Складніший продукт з кабінетами і інтеграціями плануємо окремо на Clarity Session.'],
                    ['Чи підходить Next.js для моєї ніші?', 'Так, якщо потрібна швидкість, SEO і контроль публічної частини: eCommerce, EdTech, IT/SaaS і сервісні бізнеси.'],
                    ['Які гарантії результату?', 'Гарантуємо архітектуру, SEO-базу, аналітику і супровід після запуску. Цифри росту залежать від оферу і каналів — це розбираємо на Clarity Session.'],
                    ['Чому не фриланс або інша агенція?', 'Стек підбираємо під задачу клієнта і вбудовуємо в систему: маркетинг, аналітика і розробка не живуть окремими файлами.'],
                ]),
                'seo_lead' => 'Ми віримо, що український бізнес заслуговує на простір для росту без хаосу.',
                'seo_text' => 'Next.js — наш інструмент для публічних сайтів, де SEO, швидкість і контроль маршрутів є частиною продукту, а не окремим етапом після запуску. Ми збираємо SSR і SSG під задачу клієнта: лендінг, корпоративний сайт, контентна платформа чи e-commerce. CMS, аналітика і рекламні пікселі підключаються в ту саму систему, щоб маркетинг міг змінювати сторінки без тікета в розробку на кожну правку.',
            ]),
            'en' => array_merge($next_meet['en'], [
                'intro' => 'A React framework for server rendering and static generation. We choose it when SEO visibility and load speed cannot come at the cost of functionality.',
                'why' => 'Next.js solves the core SPA problem: pages are not indexed. **SSR and SSG give SEO visibility from day one** — without a separate SEO stack.',
                'triggers' => gvspace_join_pipe_rows([
                    ['SEO is the growth priority', 'The project depends on organic search: landings, corporate sites, content platforms.'],
                    ['High speed is required', 'Core Web Vitals are critical for conversion. SSG serves static pages instantly.'],
                    ['Hybrid architecture', 'Some pages are static, some are dynamic against an API. Next.js holds both.'],
                ]),
                'uses' => gvspace_join_pipe_rows([
                    ['Corporate and marketing sites', 'SSG for static pages plus API routes for forms and CRM integrations.'],
                    ['Landings for performance campaigns', 'Fast load cuts bounce rate and lifts Quality Score.'],
                    ['Client portals with auth', 'App Router and middleware for protected zones without a separate backend.'],
                    ['Headless CMS integration', 'Contentful, Sanity, or Strapi as the backend — editors change pages without engineering.'],
                ]),
                'faq' => gvspace_join_pipe_rows([
                    ['How long does a Next.js build take?', 'A typical marketing site ships in a few weeks. Larger products with portals and integrations are scoped on a Clarity Session.'],
                    ['Is Next.js right for my niche?', 'Yes, when you need speed, SEO, and control of the public site: eCommerce, EdTech, IT/SaaS, and service businesses.'],
                    ['What results do you guarantee?', 'We guarantee the architecture, SEO baseline, analytics, and support after launch. Growth numbers depend on the offer and channels — that is scoped on a Clarity Session.'],
                    ['Why not a freelancer or another agency?', 'We pick the stack for the client’s task and wire it into one system: marketing, analytics, and engineering do not live in separate files.'],
                ]),
                'seo_lead' => 'We believe Ukrainian businesses deserve a space to grow without chaos.',
                'seo_text' => 'Next.js is our tool for public sites where SEO, speed, and routing control are part of the product, not a phase after launch. We assemble SSR and SSG around the client’s task: a landing page, a corporate site, a content platform, or e-commerce. CMS, analytics, and ad pixels join the same system so marketing can change pages without an engineering ticket for every edit.',
            ]),
        ],
        'google-ads' => [
            'related_case' => '',
            'uk' => array_merge($ads_meet['uk'], [
                'intro' => 'Пошукова і Performance Max реклама з фокусом на заявки, а не на кліки. Підключаємо, коли потрібен керований попит і прозора юніт-економіка.',
                'why' => 'Google Ads дає передбачуваний попит там, де органіка ще не тягне ріст. **Search і Performance Max зводять бюджет до заявок**, а не до порожніх кліків.',
                'triggers' => gvspace_join_pipe_rows([
                    ['Потрібен керований трафік', 'Є офер і аналітика, але органіка не закриває план заявок у найближчі тижні.'],
                    ['Важливий ROI з перших кампаній', 'Бюджет має окупатися: дивимось CPL, ROAS і якість ліда, а не лише покази.'],
                    ['Канали мають працювати як система', 'Кабінет Google Ads зв’язуємо з сайтом, CRM і звітністю, щоб масштабувати те, що працює.'],
                ]),
                'uses' => gvspace_join_pipe_rows([
                    ['Search-кампанії під комерційний попит', 'Збираємо семантику і посадкові так, щоб бюджет ішов у заявки, а не в інформаційні запити.'],
                    ['Performance Max для масштабу', 'Підключаємо, коли є аналітика, фіди й зрозуміла ціна ліда.'],
                    ['Ремаркетинг і lookalike', 'Повертаємо теплу аудиторію і розширюємо її без хаотичних охоплень.'],
                    ['Наскрізна аналітика кабінету', 'Зв’язуємо клік, сайт і CRM, щоб бачити CPL і якість заявки.'],
                ]),
                'faq' => gvspace_join_pipe_rows([
                    ['Скільки часу займає запуск Google Ads?', 'Базовий контур кампаній і аналітики збираємо за тижні. Повну систему попиту плануємо на Clarity Session.'],
                    ['Чи працюєте ви з моєю нішею?', 'Працюємо з digital-залежним бізнесом: eCommerce, EdTech, IT/SaaS і сервісами з середнім і високим чеком.'],
                    ['Що входить у ведення Google Ads?', 'Структура кампаній, трекінг, креативи/розширення, щотижневий контроль юніт-економіки і правила масштабування.'],
                    ['Чому не фрилансер у кабінеті?', 'Кабінет без системи зливає бюджет. Ми вбудовуємо рекламу в аналітику, сайт і продаж, а не крутимо ставки окремо.'],
                ]),
                'seo_lead' => 'Google Ads у GVSPACE — це не окремий кабінет, а контур керованого попиту.',
                'seo_text' => 'Ми запускаємо Search і Performance Max лише коли є офер, аналітика і зрозуміла ціна заявки. Кампанії зв’язуються з сайтом, CRM і звітністю, щоб власник бачив, що масштабувати, а що зупиняти. Реклама не живе окремим файлом: вона частина системи, де маркетинг, IT і стратегія дивляться в одні цифри.',
            ]),
            'en' => array_merge($ads_meet['en'], [
                'intro' => 'Search and Performance Max campaigns focused on leads, not clicks. We use them when you need managed demand and clear unit economics.',
                'why' => 'Google Ads creates predictable demand where organic cannot yet carry growth. **Search and Performance Max point budget at leads**, not empty clicks.',
                'triggers' => gvspace_join_pipe_rows([
                    ['Managed traffic is required', 'The offer and analytics are in place, but organic will not cover the lead plan in the next weeks.'],
                    ['ROI matters from the first campaigns', 'Spend has to pay back: we watch CPL, ROAS, and lead quality, not just impressions.'],
                    ['Channels should work as a system', 'The Google Ads account is wired to the site, CRM, and reporting so we scale what works.'],
                ]),
                'uses' => gvspace_join_pipe_rows([
                    ['Search campaigns for commercial demand', 'We shape queries and landings so spend goes to leads, not informational clicks.'],
                    ['Performance Max for scale', 'We turn it on when analytics, feeds, and a clear cost per lead are in place.'],
                    ['Remarketing and lookalike', 'We bring warm audiences back and expand them without chaotic reach.'],
                    ['End-to-end account analytics', 'Click, site, and CRM are wired so we see CPL and lead quality.'],
                ]),
                'faq' => gvspace_join_pipe_rows([
                    ['How long does a Google Ads launch take?', 'A baseline of campaigns and tracking is ready in weeks. A full demand system is scoped on a Clarity Session.'],
                    ['Do you work with my niche?', 'We work with digital-dependent businesses: eCommerce, EdTech, IT/SaaS, and service companies with a mid-to-high check.'],
                    ['What is included in Google Ads management?', 'Campaign structure, tracking, assets, weekly unit-economics control, and scaling rules.'],
                    ['Why not a freelancer in the account?', 'An account without a system burns budget. We wire ads into analytics, the site, and sales instead of tweaking bids in isolation.'],
                ]),
                'seo_lead' => 'Google Ads at GVSPACE is not a standalone account — it is a managed demand loop.',
                'seo_text' => 'We launch Search and Performance Max only when there is an offer, analytics, and a clear cost per lead. Campaigns connect to the site, CRM, and reporting so the owner sees what to scale and what to stop. Ads do not live in a side file: they are part of a system where marketing, IT, and strategy look at the same numbers.',
            ]),
        ],
    ];
}

function gvspace_technology_default_page_copy(array $item): array
{
    $uk_title = (string) $item['uk']['title'];
    $en_title = (string) $item['en']['title'];
    $uk_description = (string) $item['uk']['description'];
    $en_description = (string) $item['en']['description'];
    $tab = (string) ($item['tabs'][0] ?? 'development');
    $stats = [
        'marketing' => [
            'uk' => [['10+', 'років у performance'], ['360°', 'аналітика кампаній'], ['1 система', 'замість розрізнених кабінетів']],
            'en' => [['10+', 'years in performance'], ['360°', 'campaign analytics'], ['1 system', 'instead of scattered dashboards']],
        ],
        'development' => [
            'uk' => [['SSR', 'і статична генерація'], ['SEO', 'контроль індексації'], ['1 стек', 'від лендінгу до продукту']],
            'en' => [['SSR', 'and static generation'], ['SEO', 'indexing control'], ['1 stack', 'from landing to product']],
        ],
        'systems' => [
            'uk' => [['99.9%', 'доступність інфраструктури'], ['24/7', 'моніторинг'], ['Cloud', 'масштабування під навантаження']],
            'en' => [['99.9%', 'infrastructure uptime'], ['24/7', 'monitoring'], ['Cloud', 'scale under load']],
        ],
        'content' => [
            'uk' => [['CMS', 'без тікетів на кожну правку'], ['SEO', 'структура контенту'], ['1 процес', 'для редакційної команди']],
            'en' => [['CMS', 'without a ticket for every change'], ['SEO', 'content structure'], ['1 process', 'for the editorial team']],
        ],
    ];
    $benefits = [
        'marketing' => [
            'uk' => [
                ['Вимірюваність', 'Бачимо, що приносить ліди і продажі, а не лише кліки.'],
                ['Швидкість змін', 'Кампанії, теги й аналітику крутимо без очікування розробки.'],
                ['Єдина картина', 'Кабінети, пікселі та звіти зводяться в одну систему.'],
                ['Ріст без хаосу', 'Інструмент вбудовується в стек, а не живе окремим файлом.'],
            ],
            'en' => [
                ['Measurable', 'We see what brings leads and sales, not just clicks.'],
                ['Speed of change', 'Campaigns, tags, and analytics move without waiting on engineering.'],
                ['One picture', 'Ad accounts, pixels, and reports sit in one system.'],
                ['Growth without chaos', 'The tool joins the stack instead of living in a side file.'],
            ],
        ],
        'development' => [
            'uk' => [
                ['Швидкість', 'Публічні сторінки відкриваються швидко і тримають Core Web Vitals.'],
                ['SEO', 'Контроль індексації, мета-даних і структури маршрутів.'],
                ['Архітектура', 'Стек витримує кабінети, інтеграції та ріст команди.'],
                ['Масштабування', 'Від лендінгу до продукту — без зміни платформи на кожному етапі.'],
            ],
            'en' => [
                ['Speed', 'Public pages stay fast and protect Core Web Vitals.'],
                ['SEO', 'Control over indexing, metadata, and routing structure.'],
                ['Architecture', 'The stack holds portals, integrations, and a growing team.'],
                ['Scale', 'From a landing page to a product — without changing platforms at every stage.'],
            ],
        ],
        'systems' => [
            'uk' => [
                ['Надійність', 'Інфраструктура тримає піки трафіку і не розсипається під час росту.'],
                ['Безпека', 'Доступи, секрети і середовища розділені за ролями.'],
                ['Спостережуваність', 'Логи, алерти й метрики показують збій раніше за клієнта.'],
                ['Вартість контролю', 'Платимо за фактичне навантаження, а не за запас «на всяк випадок».'],
            ],
            'en' => [
                ['Reliability', 'Infrastructure holds traffic spikes and does not fall apart as you grow.'],
                ['Security', 'Access, secrets, and environments are split by role.'],
                ['Observability', 'Logs, alerts, and metrics surface a failure before the client does.'],
                ['Cost control', 'You pay for real load, not a permanent spare capacity tax.'],
            ],
        ],
        'content' => [
            'uk' => [
                ['Швидкість публікацій', 'Маркетинг оновлює сторінки без черги в розробку.'],
                ['SEO-структура', 'Контент сідає в правильні шаблони, URL і мета-дані.'],
                ['Контроль якості', 'Ролі, прев’ю і історія змін зменшують ризик зламати сайт.'],
                ['Єдине джерело', 'Тексти, медіа і лендінги живуть в одній системі.'],
            ],
            'en' => [
                ['Publishing speed', 'Marketing updates pages without an engineering queue.'],
                ['SEO structure', 'Content lands in the right templates, URLs, and metadata.'],
                ['Quality control', 'Roles, previews, and history reduce the risk of breaking the site.'],
                ['Single source', 'Copy, media, and landings live in one system.'],
            ],
        ],
    ];
    $tab_triggers = $benefits[$tab] ?? $benefits['development'];

    return [
        'related_case' => '',
        'uk' => [
            'intro' => $uk_description,
            'why' => 'Працюємо з ' . $uk_title . ', коли це найкращий інструмент під задачу клієнта — і вбудовуємо його в систему GVSPACE.',
            'triggers' => gvspace_join_pipe_rows($tab_triggers['uk']),
            'uses' => '',
            'faq' => gvspace_join_pipe_rows([
                ['Скільки часу займає впровадження?', 'Точкове підключення займає тижні, повна система планується на Clarity Session.'],
                ['Чи працюєте ви з моєю нішею?', 'Працюємо з digital-залежним бізнесом: eCommerce, EdTech, IT/SaaS і сервісами з середнім і високим чеком.'],
                ['Що входить у роботу з ' . $uk_title . '?', 'Налаштування, інтеграція в аналітику, контроль якості даних і супровід команди.'],
            ]),
            'seo_lead' => '',
            'seo_text' => '',
            'meet_name' => '',
            'meet_role' => '',
            'meet_quote' => '',
            'meet_years' => '',
            'meet_projects' => '',
            'meet_tags' => '',
        ],
        'en' => [
            'intro' => $en_description,
            'why' => 'We use ' . $en_title . ' when it is the best tool for the client’s task — and we wire it into the GVSPACE system.',
            'triggers' => gvspace_join_pipe_rows($tab_triggers['en']),
            'uses' => '',
            'faq' => gvspace_join_pipe_rows([
                ['How long does implementation take?', 'A focused setup takes weeks; a full system is scoped on a Clarity Session.'],
                ['Do you work with my niche?', 'We work with digital-dependent businesses: eCommerce, EdTech, IT/SaaS, and service companies with a mid-to-high check.'],
                ['What does work with ' . $en_title . ' include?', 'Setup, analytics integration, data quality control, and team support.'],
            ]),
            'seo_lead' => '',
            'seo_text' => '',
            'meet_name' => '',
            'meet_role' => '',
            'meet_quote' => '',
            'meet_years' => '',
            'meet_projects' => '',
            'meet_tags' => '',
        ],
    ];
}

function gvspace_technology_page_copy(array $item): array
{
    $overrides = gvspace_technology_page_overrides();
    $group = (string) $item['group'];
    return $overrides[$group] ?? gvspace_technology_default_page_copy($item);
}

function gvspace_apply_technology_page_fields(int $post_id, array $item): void
{
    $page = gvspace_technology_page_copy($item);
    $page_keys = [
        'intro', 'why', 'triggers', 'uses', 'faq', 'seo_lead', 'seo_text',
        'meet_name', 'meet_role', 'meet_quote', 'meet_years', 'meet_projects', 'meet_tags',
    ];
    foreach (['uk', 'en'] as $locale) {
        $copy = $page[$locale];
        foreach ($page_keys as $key) {
            if (!array_key_exists($key, $copy)) continue;
            update_post_meta($post_id, '_gvspace_technology_' . $key . '_' . $locale, (string) $copy[$key]);
        }
        $seo_title = $item[$locale]['title'];
        $seo_description = $copy['intro'] !== '' ? $copy['intro'] : (string) $item[$locale]['description'];
        update_post_meta($post_id, '_gvspace_seo_title_' . $locale, $seo_title);
        update_post_meta($post_id, '_gvspace_seo_h1_' . $locale, $seo_title);
        update_post_meta($post_id, '_gvspace_seo_description_' . $locale, $seo_description);
        update_post_meta($post_id, '_gvspace_seo_og_title_' . $locale, $seo_title);
        update_post_meta($post_id, '_gvspace_seo_og_description_' . $locale, $seo_description);
    }
    update_post_meta($post_id, '_gvspace_technology_related_case', (string) ($page['related_case'] ?? ''));
}

function gvspace_apply_technology_catalog_item(array $item): void
{
    $group = (string) $item['group'];
    $post_id = gvspace_find_technology_post_id($group);
    $uk = $item['uk'];
    $payload = [
        'post_type' => 'gv_technology',
        'post_status' => 'publish',
        'post_title' => $uk['title'],
        'post_name' => $group,
        'menu_order' => (int) $item['order'],
    ];
    if ($post_id) {
        $payload['ID'] = $post_id;
        wp_update_post($payload);
    } else {
        $post_id = (int) wp_insert_post($payload);
    }
    if (!$post_id || is_wp_error($post_id)) return;

    update_post_meta($post_id, '_gvspace_technology_title_uk', $uk['title']);
    update_post_meta($post_id, '_gvspace_technology_description_uk', $uk['description']);
    update_post_meta($post_id, '_gvspace_technology_tag_uk', $uk['tag']);
    update_post_meta($post_id, '_gvspace_technology_title_en', $item['en']['title']);
    update_post_meta($post_id, '_gvspace_technology_description_en', $item['en']['description']);
    update_post_meta($post_id, '_gvspace_technology_tag_en', $item['en']['tag']);
    update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($post_id, '_gvspace_translation_group', $group);
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    update_post_meta($post_id, '_gvspace_technology_seeded', '1');
    wp_set_object_terms($post_id, $item['tabs'], 'gv_technology_category', false);
    gvspace_apply_technology_page_fields($post_id, $item);

    if (!has_post_thumbnail($post_id) && $item['icon'] !== '') {
        $icon_id = gvspace_import_technology_icon((string) $item['icon']);
        if ($icon_id) set_post_thumbnail($post_id, $icon_id);
    }
}

function gvspace_seed_technologies(): void
{
    if (get_option('gvspace_technologies_catalog_v1') === '1') return;
    if (!add_option('gvspace_technologies_catalog_v1_lock', time(), '', false)) return;

    gvspace_seed_technology_tabs();
    gvspace_migrate_legacy_technologies();
    foreach (gvspace_technology_catalog() as $item) {
        gvspace_apply_technology_catalog_item($item);
    }

    update_option('gvspace_technologies_catalog_v1', '1', false);
    delete_option('gvspace_technologies_catalog_v1_lock');
}
add_action('init', 'gvspace_seed_technology_tabs', 19);
add_action('init', 'gvspace_seed_technologies', 21);

function gvspace_seed_technology_pages(): void
{
    if (get_option('gvspace_technologies_pages_v1') === '1') return;
    if (!add_option('gvspace_technologies_pages_v1_lock', time(), '', false)) return;

    foreach (gvspace_technology_catalog() as $item) {
        gvspace_apply_technology_catalog_item($item);
    }

    update_option('gvspace_technologies_pages_v1', '1', false);
    delete_option('gvspace_technologies_pages_v1_lock');
}
add_action('init', 'gvspace_seed_technology_pages', 22);

function gvspace_seed_technology_pages_v2(): void
{
    if (get_option('gvspace_technologies_pages_v2') === '1') return;
    if (!add_option('gvspace_technologies_pages_v2_lock', time(), '', false)) return;

    foreach (gvspace_technology_catalog() as $item) {
        $post_id = gvspace_find_technology_post_id((string) $item['group']);
        if (!$post_id) {
            gvspace_apply_technology_catalog_item($item);
            continue;
        }
        gvspace_apply_technology_page_fields($post_id, $item);
        if (!has_post_thumbnail($post_id) && $item['icon'] !== '') {
            $icon_id = gvspace_import_technology_icon((string) $item['icon']);
            if ($icon_id) set_post_thumbnail($post_id, $icon_id);
        }
    }

    update_option('gvspace_technologies_pages_v2', '1', false);
    delete_option('gvspace_technologies_pages_v2_lock');
}
add_action('init', 'gvspace_seed_technology_pages_v2', 23);

function gvspace_seed_technology_pages_v3(): void
{
    if (get_option('gvspace_technologies_pages_v3') === '1') return;
    if (!add_option('gvspace_technologies_pages_v3_lock', time(), '', false)) return;

    $examples = ['nextjs', 'google-ads'];
    foreach (gvspace_technology_catalog() as $item) {
        $group = (string) $item['group'];
        if (!in_array($group, $examples, true)) continue;
        $post_id = gvspace_find_technology_post_id($group);
        if (!$post_id) {
            gvspace_apply_technology_catalog_item($item);
            continue;
        }
        gvspace_apply_technology_page_fields($post_id, $item);
        if (!has_post_thumbnail($post_id) && $item['icon'] !== '') {
            $icon_id = gvspace_import_technology_icon((string) $item['icon']);
            if ($icon_id) set_post_thumbnail($post_id, $icon_id);
        }
    }

    update_option('gvspace_technologies_pages_v3', '1', false);
    delete_option('gvspace_technologies_pages_v3_lock');
}
add_action('init', 'gvspace_seed_technology_pages_v3', 24);

function gvspace_seed_technology_pages_v4(): void
{
    if (get_option('gvspace_technologies_pages_v4') === '1') return;
    if (!add_option('gvspace_technologies_pages_v4_lock', time(), '', false)) return;

    $examples = ['nextjs', 'google-ads'];
    foreach (gvspace_technology_catalog() as $item) {
        $group = (string) $item['group'];
        if (!in_array($group, $examples, true)) continue;
        $post_id = gvspace_find_technology_post_id($group);
        if (!$post_id) {
            gvspace_apply_technology_catalog_item($item);
            continue;
        }
        gvspace_apply_technology_page_fields($post_id, $item);
        if (!has_post_thumbnail($post_id) && $item['icon'] !== '') {
            $icon_id = gvspace_import_technology_icon((string) $item['icon']);
            if ($icon_id) set_post_thumbnail($post_id, $icon_id);
        }
    }

    update_option('gvspace_technologies_pages_v4', '1', false);
    delete_option('gvspace_technologies_pages_v4_lock');
}
add_action('init', 'gvspace_seed_technology_pages_v4', 25);
