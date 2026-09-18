<?php

if (!defined('ABSPATH')) {
    exit;
}

function gvspace_case_lines(array $items): string
{
    return implode("\n", $items);
}

function gvspace_case_locale_field(int $post_id, string $field, string $locale): string
{
    $read = static fn (string $key): string => (string) get_post_meta($post_id, $key, true);
    $central = $field === 'title'
        ? $read('_gvspace_case_title_' . $locale)
        : $read('_gvspace_case_' . $field . '_' . $locale);
    if ($central !== '') return $central;

    if ($field === 'title') {
        if ($locale !== 'uk') {
            $uk = $read('_gvspace_case_title_uk');
            if ($uk !== '') return $uk;
        }
        return (string) get_the_title($post_id);
    }

    $legacy = $read('_gvspace_case_' . $field);
    if ($legacy !== '') return $legacy;
    if ($locale !== 'uk') {
        $uk = $read('_gvspace_case_' . $field . '_uk');
        if ($uk !== '') return $uk;
    }
    return '';
}

function gvspace_case_direction_defaults(): array
{
    return [
        'marketing' => 'МАРКЕТИНГ',
        'development' => 'РОЗРОБКА',
        'projects' => 'ПРОЄКТИ',
        'content' => 'КОНТЕНТ',
    ];
}

function gvspace_case_project_type_defaults(): array
{
    return [
        'ecommerce' => 'E-COMMERCE',
        'performance' => 'PERFORMANCE',
        'brand' => 'BRAND',
        'seo' => 'SEO',
    ];
}

function gvspace_get_case_taxonomy_options(string $taxonomy, array $preferred): array
{
    $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
    if (is_wp_error($terms) || !$terms) return $preferred;
    $preferred_slugs = array_keys($preferred);
    usort($terms, static function ($a, $b) use ($preferred_slugs): int {
        $ai = array_search($a->slug, $preferred_slugs, true);
        $bi = array_search($b->slug, $preferred_slugs, true);
        if ($ai === false && $bi === false) return strcasecmp($a->name, $b->name);
        if ($ai === false) return 1;
        if ($bi === false) return -1;
        return $ai - $bi;
    });
    $options = [];
    foreach ($terms as $term) $options[$term->slug] = $term->name;
    return $options;
}

function gvspace_get_case_direction_options(): array
{
    return gvspace_get_case_taxonomy_options('gv_case_direction', gvspace_case_direction_defaults());
}

function gvspace_get_case_project_type_options(): array
{
    return gvspace_get_case_taxonomy_options('gv_case_project_type', gvspace_case_project_type_defaults());
}

function gvspace_ensure_case_term(string $name, string $taxonomy): string
{
    $collapsed = preg_replace('/\s+/u', ' ', trim($name));
    $name = is_string($collapsed) ? $collapsed : trim($name);
    if ($name === '') return '';
    $needle = function_exists('mb_strtoupper') ? mb_strtoupper($name, 'UTF-8') : strtoupper($name);
    $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            $label = function_exists('mb_strtoupper') ? mb_strtoupper($term->name, 'UTF-8') : strtoupper($term->name);
            if ($label === $needle) return $term->slug;
        }
    }
    $slug = sanitize_title($name);
    if ($slug === '') $slug = 'term-' . substr(md5($name), 0, 8);
    $existing = get_term_by('slug', $slug, $taxonomy);
    if ($existing && !is_wp_error($existing)) return $existing->slug;
    $result = wp_insert_term($name, $taxonomy, ['slug' => $slug]);
    if (is_wp_error($result)) {
        $term_id = (int) $result->get_error_data('term_exists');
        if ($term_id) {
            $term = get_term($term_id, $taxonomy);
            return $term && !is_wp_error($term) ? $term->slug : '';
        }
        return '';
    }
    $term = get_term((int) $result['term_id'], $taxonomy);
    return $term && !is_wp_error($term) ? $term->slug : $slug;
}

function gvspace_case_term_name(int $post_id, string $taxonomy): string
{
    $terms = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'names']);
    return is_array($terms) && $terms ? (string) $terms[0] : '';
}

function gvspace_seed_case_filter_terms(): void
{
    foreach (gvspace_case_direction_defaults() as $slug => $name) {
        if (!term_exists($slug, 'gv_case_direction')) wp_insert_term($name, 'gv_case_direction', ['slug' => $slug]);
    }
    foreach (gvspace_case_project_type_defaults() as $slug => $name) {
        if (!term_exists($slug, 'gv_case_project_type')) wp_insert_term($name, 'gv_case_project_type', ['slug' => $slug]);
    }
}
add_action('init', 'gvspace_seed_case_filter_terms', 19);
add_action('admin_init', 'gvspace_seed_case_filter_terms');

function gvspace_existing_case_asset(string $filename): int
{
    $attachments = get_posts([
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'numberposts' => 1,
        'title' => pathinfo($filename, PATHINFO_FILENAME),
    ]);
    return $attachments ? (int) $attachments[0]->ID : 0;
}

function gvspace_sideload_case_asset(string $filename, int $post_id): int
{
    $existing = gvspace_existing_case_asset($filename);
    if ($existing) return $existing;
    $path = __DIR__ . '/assets/cases/' . $filename;
    if (!is_readable($path)) return 0;
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $tmp = wp_tempnam($filename);
    if (!$tmp || !copy($path, $tmp)) return 0;
    $attachment_id = media_handle_sideload(['name' => $filename, 'tmp_name' => $tmp], $post_id);
    if (is_wp_error($attachment_id)) {
        @unlink($tmp);
        return 0;
    }
    return (int) $attachment_id;
}

function gvspace_case_seed_catalog(): array
{
    $shared_metrics = gvspace_case_lines(['01% | ROAS', '02 | CPL']);
    $detox_excerpt_uk = 'Новорічна рекламна кампанія, яка зібрала попит у системі, а не в хаотичних акціях.';
    $detox_excerpt_en = 'A New Year campaign that collected demand inside a system rather than chaotic promotions.';
    $detox_problems_uk = gvspace_case_lines([
        'Немає єдиної картини по рекламі, сайту і продажах.',
        'Бюджет зливається в акції без прогнозу.',
        'Контент і офери змінюються щотижня без гіпотез.',
        'Команда не розуміє, яка зв’язка дає прибуток.',
    ]);
    $detox_problems_en = gvspace_case_lines([
        'No single view of ads, the site, and sales.',
        'Budget is spent on promotions without a forecast.',
        'Content and offers change weekly without hypotheses.',
        'The team cannot see which combination makes profit.',
    ]);

    $detail_uk = [
        'challenge' => 'Інтернет-магазин фарфорового посуду на німецькому ринку. Сайт не був оптимізований під пошук — органічний трафік практично відсутній, продажі трималися на інших каналах.',
        'problems' => gvspace_case_lines([
            'Продажі залежать від акцій, а не від системи попиту.',
            'Немає прозорої аналітики від кліку до покупки.',
            'Сайт і реклама живуть окремо від складу та CRM.',
            'Власник не бачить, куди масштабувати бюджет.',
        ]),
        'step1' => 'Спочатку зібрали факти: канали, юніт-економіку, асортимент і те, де губиться клієнт після кліку. Аудит показав, що зростання впирається не в креатив, а в розрив між рекламою, сайтом і продажами.',
        'step1_result' => "Мапа можливостей | Пріоритетні сегменти, офери та точки втрат, з яких складається дорожня карта запуску.",
        'step2' => 'Далі зібрали архітектуру зростання: SEO і GEO для попиту, performance для керованого трафіку, контент для довіри. Кожен блок має метрику і власника, щоб робота не розпадалася на задачі.',
        'architecture' => gvspace_case_lines([
            'IT | Реєстрація в Search Console, карта сайту для швидкої індексації кількома мовами і виправлення технічних помилок.',
            'MARKETING | Performance-зв’язки Meta та Google з наскрізною аналітикою, прогнозом ROAS і правилами масштабування.',
            'CONTENT | Контент для довіри: статті, колекції, офери і посадкові, які тримають попит після кліку.',
        ]),
        'step3' => 'Після стабільного юніт-економікс масштабуємо працюючі зв’язки і тримаємо контроль: дашборди, ритм звітів і правила, коли зупиняти або підсилювати канал.',
        'step3_result' => "Контроль масштабу | Щотижневі звіти без жаргону, ліміти бюджету і критерії масштабування.",
        'tasks' => gvspace_case_lines([
            'Аудит і побудова семантичного ядра | Провчили структуру сайту, технічний стан і конкурентів, зібрали семантику під DE та EN.',
            'Технічні доопрацювання та зовнішня оптимізація | Закрили технічну базу, мікророзмітку, швидкість і редіректи, паралельно нарощували посилальну масу.',
        ]),
        'documents' => gvspace_case_lines([
            'Бриф і карта воронки після аудиту.',
            'Медіаплан із прогнозом і юніт-економікою.',
            'Регламент звітів і критерії масштабування.',
        ]),
        'testimonial' => 'Життя після впровадження системи змінилось: з’явився час на стратегію і спокій за результат. Ми більше не гасимо акції — керуємо зростанням.',
        'testimonial_author' => 'Максим Бичок',
        'testimonial_company' => 'Reason Agency',
    ];
    $detail_en = [
        'challenge' => 'A porcelain e-commerce store on the German market. The site was not built for search — organic traffic was almost absent, and sales stayed on other channels.',
        'problems' => gvspace_case_lines([
            'Sales depend on promotions, not on a demand system.',
            'There is no transparent path from click to purchase.',
            'The site and ads live apart from stock and CRM.',
            'The owner cannot see where to scale budget.',
        ]),
        'step1' => 'We started with facts: channels, unit economics, assortment, and where the customer is lost after the click. The audit showed growth was blocked not by creative, but by the gap between ads, the site, and sales.',
        'step1_result' => "Opportunity map | Priority segments, offers, and drop-off points that form the launch roadmap.",
        'step2' => 'Then we assembled the growth architecture: SEO and GEO for demand, performance for managed traffic, content for trust. Every block has a metric and an owner so the work does not fragment into tasks.',
        'architecture' => gvspace_case_lines([
            'IT | Search Console, a multilingual sitemap, and technical fixes that unblock indexing.',
            'MARKETING | Meta and Google performance links with end-to-end analytics and a ROAS forecast.',
            'CONTENT | Trust content: articles, collections, offers, and landings that hold demand after the click.',
        ]),
        'step3' => 'Once unit economics are stable we scale working combinations and keep control: dashboards, a reporting cadence, and rules for when to pause or amplify a channel.',
        'step3_result' => "Scale control | Weekly reports without jargon, budget limits, and scaling criteria.",
        'tasks' => gvspace_case_lines([
            'Audit and semantic core | We mapped the site, technical state, and competitors, then built DE and EN semantics.',
            'Technical work and off-site optimization | We closed the technical base, speed, and redirects while growing the link profile.',
        ]),
        'documents' => gvspace_case_lines([
            'Brief and funnel map after the audit.',
            'Media plan with forecast and unit economics.',
            'Reporting rules and scaling criteria.',
        ]),
        'testimonial' => 'Life after the system changed: there is time for strategy and calm about the result. We no longer fight promotions — we manage growth.',
        'testimonial_author' => 'Maksym Bychok',
        'testimonial_company' => 'Reason Agency',
    ];

    $detox_cases = [
        ['detox-new-year', 'Новорічна рекламна кампанія для бренду води', 'New Year campaign for a water brand', 'marketing', 'performance', 2],
        ['detox-meta', 'Performance-кампанія Meta для бренду води', 'Meta performance campaign for a water brand', 'marketing', 'performance', 3],
        ['detox-google', 'Google Ads система для e-commerce води', 'Google Ads system for water e-commerce', 'marketing', 'ecommerce', 4],
        ['detox-seo', 'SEO і структура попиту для бренду води', 'SEO and demand structure for a water brand', 'content', 'seo', 5],
        ['detox-content', 'Контент-система для бренду води', 'Content system for a water brand', 'content', 'brand', 6],
        ['detox-brand', 'Бренд-кампанія без хаотичних акцій', 'Brand campaign without chaotic promotions', 'marketing', 'brand', 7],
        ['detox-architecture', 'Архітектура зростання для e-commerce', 'Growth architecture for e-commerce', 'development', 'ecommerce', 8],
        ['detox-delivery', 'Система поставки реклами і контенту', 'Delivery system for ads and content', 'projects', 'performance', 9],
        ['detox-retention', 'Retention-система для бренду води', 'Retention system for a water brand', 'marketing', 'performance', 10],
        ['detox-analytics', 'Наскрізна аналітика для e-commerce води', 'End-to-end analytics for water e-commerce', 'development', 'ecommerce', 11],
        ['detox-launch', 'Запуск системи попиту для бренду води', 'Demand system launch for a water brand', 'projects', 'brand', 12],
    ];

    $catalog = [[
        'slug' => 'porzellan-haus',
        'order' => 1,
        'cover' => 'porzellan-hero.jpg',
        'direction' => 'marketing',
        'project_type' => 'ecommerce',
        'title_uk' => 'PorzellanHaus',
        'title_en' => 'PorzellanHaus',
        'catalog_title_uk' => 'Система зростання для PorzellanHaus',
        'catalog_title_en' => 'Growth system for PorzellanHaus',
        'excerpt_uk' => 'Видимість сайту в Google зросла на 600% — до 42 запитів у ТОП-3 пошукової видачі.',
        'excerpt_en' => 'Site visibility in Google grew by 600% — to 42 queries in the top 3 search results.',
        'metrics' => gvspace_case_lines(['+600% | Видимість у пошуку', '42 | Запити в ТОП-3', '5800 | Показів на добу', '220 | Кліків на добу']),
        'team' => "Максим | Head of Growth | ",
        'detail_uk' => $detail_uk,
        'detail_en' => $detail_en,
        'seo_description_uk' => 'Кейс PorzellanHaus: система маркетингу, e-commerce і вимірюване зростання з GVSPACE.',
        'seo_description_en' => 'PorzellanHaus case: a marketing and e-commerce system and measurable growth with GVSPACE.',
    ]];

    foreach ($detox_cases as [$slug, $title_uk, $title_en, $direction, $project_type, $order]) {
        $catalog[] = [
            'slug' => $slug,
            'order' => $order,
            'cover' => 'detox.jpg',
            'direction' => $direction,
            'project_type' => $project_type,
            'title_uk' => $title_uk,
            'title_en' => $title_en,
            'catalog_title_uk' => $title_uk,
            'catalog_title_en' => $title_en,
            'excerpt_uk' => $detox_excerpt_uk,
            'excerpt_en' => $detox_excerpt_en,
            'metrics' => $shared_metrics,
            'team' => "Максим | Head of Growth | ",
            'detail_uk' => array_merge($detail_uk, ['problems' => $detox_problems_uk]),
            'detail_en' => array_merge($detail_en, ['problems' => $detox_problems_en]),
            'seo_description_uk' => $title_uk . ' — кейс GVSPACE про системне зростання.',
            'seo_description_en' => $title_en . ' — a GVSPACE case about systematic growth.',
        ];
    }

    return $catalog;
}

function gvspace_write_case_locale_fields(int $post_id, array $vacancy, string $locale, array $detail): void
{
    $prefix = '_gvspace_case_';
    update_post_meta($post_id, $prefix . 'title_' . $locale, $vacancy['title_' . $locale]);
    update_post_meta($post_id, $prefix . 'catalog_title_' . $locale, $vacancy['catalog_title_' . $locale]);
    update_post_meta($post_id, $prefix . 'excerpt_' . $locale, $vacancy['excerpt_' . $locale]);
    update_post_meta($post_id, $prefix . 'challenge_' . $locale, $detail['challenge'] ?? '');
    update_post_meta($post_id, $prefix . 'metrics_' . $locale, $vacancy['metrics']);
    update_post_meta($post_id, $prefix . 'problems_' . $locale, $detail['problems']);
    update_post_meta($post_id, $prefix . 'step1_' . $locale, $detail['step1']);
    update_post_meta($post_id, $prefix . 'step1_result_' . $locale, $detail['step1_result']);
    update_post_meta($post_id, $prefix . 'step2_' . $locale, $detail['step2']);
    update_post_meta($post_id, $prefix . 'architecture_' . $locale, $detail['architecture']);
    update_post_meta($post_id, $prefix . 'step3_' . $locale, $detail['step3']);
    update_post_meta($post_id, $prefix . 'step3_result_' . $locale, $detail['step3_result']);
    update_post_meta($post_id, $prefix . 'tasks_' . $locale, $detail['tasks']);
    update_post_meta($post_id, $prefix . 'documents_' . $locale, $detail['documents']);
    update_post_meta($post_id, $prefix . 'team_' . $locale, $vacancy['team']);
    update_post_meta($post_id, $prefix . 'testimonial_' . $locale, $detail['testimonial']);
    update_post_meta($post_id, $prefix . 'testimonial_author_' . $locale, $detail['testimonial_author']);
    update_post_meta($post_id, $prefix . 'testimonial_company_' . $locale, $detail['testimonial_company']);
}

function gvspace_find_seeded_case(string $slug): ?WP_Post
{
    $by_group = get_posts([
        'post_type' => 'gv_case',
        'post_status' => ['publish', 'draft', 'pending', 'private'],
        'numberposts' => 1,
        'meta_key' => '_gvspace_translation_group',
        'meta_value' => $slug,
    ]);
    if ($by_group) return $by_group[0];
    $by_slug = get_posts([
        'post_type' => 'gv_case',
        'post_status' => ['publish', 'draft', 'pending', 'private'],
        'numberposts' => 1,
        'name' => $slug,
    ]);
    return $by_slug[0] ?? null;
}

function gvspace_seed_cases(): void
{
    $catalog = gvspace_case_seed_catalog();
    $hash = md5('centralized-cases-v6' . (string) wp_json_encode($catalog));
    if ((string) get_option('gvspace_cases_catalog_hash') === $hash) return;
    $lock_time = (int) get_option('gvspace_cases_catalog_lock', 0);
    if ($lock_time && time() - $lock_time < 60) return;
    if ($lock_time) delete_option('gvspace_cases_catalog_lock');
    if (!add_option('gvspace_cases_catalog_lock', time(), '', false)) return;

    gvspace_seed_case_filter_terms();

    $keep_slugs = [];
    $shared_gallery = [];
    $team_photo = 0;

    foreach ($catalog as $index => $item) {
        $existing = gvspace_find_seeded_case($item['slug']);
        if ($existing) {
            $keep_slugs[] = $item['slug'];
            wp_update_post([
                'ID' => $existing->ID,
                'post_status' => 'publish',
                'post_title' => $item['title_uk'],
                'post_name' => $item['slug'],
                'menu_order' => $item['order'],
            ]);
            if (!has_post_thumbnail((int) $existing->ID)) {
                $cover_id = gvspace_sideload_case_asset($item['cover'], (int) $existing->ID);
                if ($cover_id) set_post_thumbnail((int) $existing->ID, $cover_id);
            }
            if ($index === 0 && !$shared_gallery) {
                $shared_gallery = gvspace_case_gallery_attachment_ids((int) $existing->ID);
            }
            gvspace_write_case_locale_fields((int) $existing->ID, $item, 'uk', $item['detail_uk']);
            gvspace_write_case_locale_fields((int) $existing->ID, $item, 'en', $item['detail_en']);
            continue;
        }
        $keep_slugs[] = $item['slug'];
        $post_id = wp_insert_post([
            'post_type' => 'gv_case',
            'post_status' => 'publish',
            'post_title' => $item['title_uk'],
            'post_name' => $item['slug'],
            'menu_order' => $item['order'],
        ]);
        if (!$post_id || is_wp_error($post_id)) continue;

        update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
        update_post_meta($post_id, '_gvspace_translation_group', $item['slug']);
        update_post_meta($post_id, '_gvspace_translation_status', 'published');
        update_post_meta($post_id, '_gvspace_case_seeded', 'catalog');
        gvspace_write_case_locale_fields((int) $post_id, $item, 'uk', $item['detail_uk']);
        gvspace_write_case_locale_fields((int) $post_id, $item, 'en', $item['detail_en']);
        wp_set_object_terms((int) $post_id, [$item['direction']], 'gv_case_direction', false);
        wp_set_object_terms((int) $post_id, [$item['project_type']], 'gv_case_project_type', false);

        if (!has_post_thumbnail((int) $post_id)) {
            $cover_id = gvspace_sideload_case_asset($item['cover'], (int) $post_id);
            if ($cover_id) set_post_thumbnail((int) $post_id, $cover_id);
        }

        if ($index === 0 && !$shared_gallery) {
            $shared_gallery = gvspace_case_gallery_attachment_ids((int) $post_id);
            if (!$shared_gallery) {
                $shop = gvspace_sideload_case_asset('gallery-shop.jpg', (int) $post_id);
                $analytics = gvspace_sideload_case_asset('gallery-analytics.jpg', (int) $post_id);
                $team_photo = gvspace_sideload_case_asset('team-max.jpg', (int) $post_id);
                if ($shop) $shared_gallery[] = (int) $shop;
                if ($analytics) $shared_gallery[] = (int) $analytics;
            }
        }

        if ($shared_gallery) {
            gvspace_save_case_gallery_ids((int) $post_id, $shared_gallery);
        }
        if ($team_photo) {
            $photo_url = (string) wp_get_attachment_url($team_photo);
            update_post_meta((int) $post_id, '_gvspace_case_team_uk', "Максим | Head of Growth | {$photo_url}");
            update_post_meta((int) $post_id, '_gvspace_case_team_en', "Maksym | Head of Growth | {$photo_url}");
        }

        update_post_meta((int) $post_id, '_gvspace_seo_title_uk', $item['title_uk']);
        update_post_meta((int) $post_id, '_gvspace_seo_title_en', $item['title_en']);
        update_post_meta((int) $post_id, '_gvspace_seo_h1_uk', $item['title_uk']);
        update_post_meta((int) $post_id, '_gvspace_seo_h1_en', $item['title_en']);
        update_post_meta((int) $post_id, '_gvspace_seo_description_uk', $item['seo_description_uk']);
        update_post_meta((int) $post_id, '_gvspace_seo_description_en', $item['seo_description_en']);
        update_post_meta((int) $post_id, '_gvspace_seo_og_title_uk', $item['title_uk']);
        update_post_meta((int) $post_id, '_gvspace_seo_og_title_en', $item['title_en']);
        update_post_meta((int) $post_id, '_gvspace_seo_og_description_uk', $item['seo_description_uk']);
        update_post_meta((int) $post_id, '_gvspace_seo_og_description_en', $item['seo_description_en']);
    }

    $cases = get_posts([
        'post_type' => 'gv_case',
        'post_status' => ['publish', 'draft', 'pending', 'private', 'trash'],
        'numberposts' => -1,
    ]);
    $seen_groups = [];
    foreach ($cases as $case_post) {
        $group = (string) get_post_meta($case_post->ID, '_gvspace_translation_group', true);
        $seeded = (string) get_post_meta($case_post->ID, '_gvspace_case_seeded', true);
        $name = (string) $case_post->post_name;
        $extra_copy = $group !== '' && $name !== $group;
        $unknown = $seeded === 'catalog' && $group !== '' && !in_array($group, $keep_slugs, true);
        $duplicate_group = $group !== '' && isset($seen_groups[$group]);
        if ($seeded === 'catalog' && ($extra_copy || $unknown || $duplicate_group)) {
            wp_delete_post((int) $case_post->ID, true);
            continue;
        }
        if ($group !== '') $seen_groups[$group] = true;
    }

    update_option('gvspace_cases_catalog_hash', $hash, false);
    delete_option('gvspace_cases_catalog_lock');
}
add_action('init', 'gvspace_seed_cases', 21);
add_action('admin_init', 'gvspace_seed_cases');
