<?php

if (!defined('ABSPATH')) {
    exit;
}

function gvspace_static_page_seo_catalog(): array
{
    return [
        'home' => [
            'label' => 'Головна',
            'path' => '/',
            'order' => 0,
            'seo' => [
                'uk' => [
                    'title' => 'GVSPACE — простір вашого масштабування',
                    'description' => 'Проєктуємо керовані системи маркетингу, IT та стратегії для масштабування бізнесу.',
                ],
                'en' => [
                    'title' => 'GVSPACE — Space for your growth',
                    'description' => 'We design manageable marketing, IT, and strategy systems that help businesses scale.',
                ],
            ],
        ],
        'services' => [
            'label' => 'Послуги',
            'path' => '/services',
            'order' => 10,
            'seo' => [
                'uk' => ['title' => 'Послуги', 'description' => 'Системні рішення для розвитку бізнесу'],
                'en' => ['title' => 'Services', 'description' => 'System solutions for business growth'],
            ],
        ],
        'cases' => [
            'label' => 'Кейси',
            'path' => '/cases',
            'order' => 20,
            'seo' => [
                'uk' => ['title' => 'Кейси', 'description' => 'Результати клієнтів та реалізовані проєкти GVSPACE'],
                'en' => ['title' => 'Cases', 'description' => 'GVSPACE client results and delivered projects'],
            ],
        ],
        'reviews' => [
            'label' => 'Відгуки',
            'path' => '/reviews',
            'order' => 30,
            'seo' => [
                'uk' => ['title' => 'Відгуки', 'description' => 'Відгуки клієнтів про співпрацю з GVSPACE'],
                'en' => ['title' => 'Reviews', 'description' => 'What clients say about working with GVSPACE'],
            ],
        ],
        'about' => [
            'label' => 'Про компанію',
            'path' => '/about',
            'order' => 40,
            'seo' => [
                'uk' => ['title' => 'Про компанію', 'description' => 'Команда й принципи роботи GVSPACE'],
                'en' => ['title' => 'About', 'description' => 'The GVSPACE team and operating principles'],
            ],
        ],
        'team' => [
            'label' => 'Команда',
            'path' => '/team',
            'order' => 50,
            'seo' => [
                'uk' => ['title' => 'Команда', 'description' => 'Експерти GVSPACE'],
                'en' => ['title' => 'Team', 'description' => 'GVSPACE experts'],
            ],
        ],
        'careers' => [
            'label' => 'Вакансії',
            'path' => '/careers',
            'order' => 60,
            'seo' => [
                'uk' => ['title' => 'Вакансії', 'description' => 'Кар’єрні можливості у GVSPACE'],
                'en' => ['title' => 'Careers', 'description' => 'Career opportunities at GVSPACE'],
            ],
        ],
        'technologies' => [
            'label' => 'Технології',
            'path' => '/technologies',
            'order' => 70,
            'seo' => [
                'uk' => ['title' => 'Технології', 'description' => 'Технологічний стек GVSPACE'],
                'en' => ['title' => 'Technologies', 'description' => 'The GVSPACE technology stack'],
            ],
        ],
        'industries' => [
            'label' => 'Індустрії',
            'path' => '/industries',
            'order' => 80,
            'seo' => [
                'uk' => ['title' => 'Індустрії', 'description' => 'Ніші, де GVSPACE знає специфіку звернення'],
                'en' => ['title' => 'Industries', 'description' => 'Niches where GVSPACE knows the specifics of the request'],
            ],
        ],
        'partners' => [
            'label' => 'Партнерство',
            'path' => '/partners',
            'order' => 90,
            'seo' => [
                'uk' => ['title' => 'Партнерство', 'description' => 'Партнерська програма GVSPACE Partners'],
                'en' => ['title' => 'Partnership', 'description' => 'The GVSPACE Partners program'],
            ],
        ],
        'blog' => [
            'label' => 'Блог',
            'path' => '/blog',
            'order' => 100,
            'seo' => [
                'uk' => ['title' => 'Блог', 'description' => 'Статті про маркетинг, стратегію та IT'],
                'en' => ['title' => 'Blog', 'description' => 'Insights on marketing, strategy, and IT'],
            ],
        ],
    ];
}

add_action('init', function (): void {
    register_post_type('gv_page_seo', [
        'labels' => [
            'name' => 'SEO сторінок',
            'singular_name' => 'SEO сторінки',
            'menu_name' => 'SEO сторінок',
            'edit_item' => 'SEO сторінки',
            'all_items' => 'Усі сторінки',
            'not_found' => 'Сторінок не знайдено',
            'search_items' => 'Шукати сторінки',
        ],
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'staticPageSeo',
        'graphql_plural_name' => 'staticPageSeos',
        'menu_icon' => 'dashicons-search',
        'menu_position' => 24,
        'rewrite' => false,
        'supports' => ['title'],
        'capability_type' => 'post',
        'capabilities' => [
            'create_posts' => 'do_not_allow',
        ],
        'map_meta_cap' => true,
    ]);
}, 9);

add_filter('map_meta_cap', function (array $caps, string $cap, int $user_id, array $args): array {
    if ($cap !== 'delete_post' || !isset($args[0])) {
        return $caps;
    }
    $post = get_post((int) $args[0]);
    if ($post instanceof WP_Post && $post->post_type === 'gv_page_seo') {
        $caps[] = 'do_not_allow';
    }
    return $caps;
}, 10, 4);

add_action('init', 'gvspace_seed_static_page_seo', 30);
add_action('init', 'gvspace_repair_static_page_seo_duplicates', 31);

function gvspace_repair_static_page_seo_duplicates(): void
{
    if (get_option('gvspace_static_page_seo_deduped_v1') === '1' || !post_type_exists('gv_page_seo')) {
        return;
    }

    $posts = get_posts([
        'post_type' => 'gv_page_seo',
        'post_status' => 'any',
        'numberposts' => -1,
    ]);
    $catalog = gvspace_static_page_seo_catalog();

    foreach ($catalog as $key => $page) {
        $matches = array_values(array_filter(
            $posts,
            static fn (WP_Post $post): bool => $post->post_name === $key || (bool) preg_match('/^' . preg_quote($key, '/') . '-\d+$/', $post->post_name)
        ));
        if (!$matches) {
            continue;
        }

        usort($matches, static fn (WP_Post $a, WP_Post $b): int => strcmp($b->post_modified_gmt, $a->post_modified_gmt));
        $keeper = $matches[0];
        foreach (array_slice($matches, 1) as $duplicate) {
            wp_delete_post($duplicate->ID, true);
        }
        if ($keeper->post_name !== $key || $keeper->post_title !== $page['label']) {
            wp_update_post([
                'ID' => $keeper->ID,
                'post_name' => $key,
                'post_title' => $page['label'],
            ]);
        }
    }

    update_option('gvspace_static_page_seo_deduped_v1', '1', false);
}

function gvspace_seed_static_page_seo(): void
{
    if (get_option('gvspace_static_page_seo_v1') === '1') {
        return;
    }
    if (!post_type_exists('gv_page_seo')) {
        return;
    }

    foreach (gvspace_static_page_seo_catalog() as $key => $page) {
        $existing = get_posts([
            'post_type' => 'gv_page_seo',
            'name' => $key,
            'post_status' => 'any',
            'numberposts' => 1,
        ]);
        $post_id = $existing ? (int) $existing[0]->ID : 0;
        if (!$post_id) {
            $created = wp_insert_post([
                'post_type' => 'gv_page_seo',
                'post_status' => 'publish',
                'post_title' => $page['label'],
                'post_name' => $key,
                'menu_order' => (int) $page['order'],
            ], true);
            if (is_wp_error($created) || !$created) {
                return;
            }
            $post_id = (int) $created;
        }

        foreach ($page['seo'] as $locale => $fields) {
            foreach ($fields as $field => $value) {
                $meta_key = '_gvspace_seo_' . $field . '_' . $locale;
                if (metadata_exists('post', $post_id, $meta_key)) {
                    continue;
                }
                update_post_meta($post_id, $meta_key, $value);
            }
        }
    }

    update_option('gvspace_static_page_seo_v1', '1', false);
}

add_action('pre_get_posts', function (WP_Query $query): void {
    if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'gv_page_seo') {
        return;
    }
    $query->set('orderby', 'menu_order');
    $query->set('order', 'ASC');
});

add_filter('manage_gv_page_seo_posts_columns', function (array $columns): array {
    $result = [];
    foreach ($columns as $key => $label) {
        $result[$key] = $label;
        if ($key === 'title') {
            $result['gvspace_page_path'] = 'Адреса';
        }
    }
    unset($result['date']);
    return $result;
});

add_action('manage_gv_page_seo_posts_custom_column', function (string $column, int $post_id): void {
    if ($column !== 'gvspace_page_path') {
        return;
    }
    $key = (string) get_post_field('post_name', $post_id);
    $catalog = gvspace_static_page_seo_catalog();
    echo esc_html($catalog[$key]['path'] ?? '/');
}, 10, 2);

add_action('add_meta_boxes', function (): void {
    add_meta_box(
        'gvspace-page-seo-info',
        'Сторінка',
        'gvspace_render_page_seo_info',
        'gv_page_seo',
        'side',
        'high'
    );
});

function gvspace_render_page_seo_info(WP_Post $post): void
{
    $catalog = gvspace_static_page_seo_catalog();
    $page = $catalog[$post->post_name] ?? null;
    $path = $page['path'] ?? '/';
    echo '<p><strong>' . esc_html($page['label'] ?? $post->post_title) . '</strong><br>';
    echo '<code>' . esc_html($path) . '</code></p>';
    echo '<p class="description">Тут змінюються заголовок вкладки, опис для Google, прев’ю в соцмережах і канонікал. Текст самої сторінки лишається як у макеті. Мову перемикайте в блоці SEO. Якщо поле порожнє, сайт використовує поточний текст за замовчуванням.</p>';
}

add_action('all_admin_notices', function (): void {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->post_type !== 'gv_page_seo' || $screen->base !== 'edit') {
        return;
    }
    echo '<div class="notice notice-info"><p>SEO для сторінок, які не редагуються як окремі записи: головна, каталоги послуг, кейсів, блогу та інші розділи. Окремі послуги, кейси, статті, вакансії й технології налаштовуються у своїх розділах.</p></div>';
});
