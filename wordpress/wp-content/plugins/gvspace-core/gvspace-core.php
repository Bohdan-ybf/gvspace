<?php
/**
 * Plugin Name: GVSPACE Core
 * Description: Content types and GraphQL fields used by the GVSPACE frontend.
 * Version: 0.5.0
 * Author: GVSPACE
 * Text Domain: gvspace-core
 */

if (!defined('ABSPATH')) {
    exit;
}

const GVSPACE_VACANCY_FIELDS = [
    'title_en' => ['label' => 'Заголовок англійською', 'type' => 'text'],
    'excerpt_uk' => ['label' => 'Короткий опис українською', 'type' => 'textarea'],
    'excerpt_en' => ['label' => 'Короткий опис англійською', 'type' => 'textarea'],
    'salary' => ['label' => 'Зарплата', 'type' => 'text'],
    'tags' => ['label' => 'Теги (кожен з нового рядка)', 'type' => 'textarea'],
    'role_uk' => ['label' => 'Про роль українською (абзац з нового рядка)', 'type' => 'textarea'],
    'role_en' => ['label' => 'Про роль англійською (абзац з нового рядка)', 'type' => 'textarea'],
    'tasks_uk' => ['label' => 'Задачі українською (пункт з нового рядка)', 'type' => 'textarea'],
    'tasks_en' => ['label' => 'Задачі англійською (пункт з нового рядка)', 'type' => 'textarea'],
    'requirements_uk' => ['label' => 'Вимоги українською (пункт з нового рядка)', 'type' => 'textarea'],
    'requirements_en' => ['label' => 'Вимоги англійською (пункт з нового рядка)', 'type' => 'textarea'],
    'tools' => ['label' => 'Інструменти (кожен з нового рядка)', 'type' => 'textarea'],
    'benefits_uk' => ['label' => 'Ми пропонуємо українською (пункт з нового рядка)', 'type' => 'textarea'],
    'benefits_en' => ['label' => 'Ми пропонуємо англійською (пункт з нового рядка)', 'type' => 'textarea'],
];

const GVSPACE_AUTHOR_FIELDS = [
    'role' => 'Посада / роль',
    'headline' => 'Короткий заголовок профілю',
    'experience' => 'Років досвіду (наприклад, 8+)',
    'projects' => 'Успішних проєктів (наприклад, 50+)',
];

const GVSPACE_REVIEW_FIELDS = [
    'name_en' => ['label' => 'Ім’я англійською', 'type' => 'text'],
    'position_uk' => ['label' => 'Посада українською', 'type' => 'text'],
    'position_en' => ['label' => 'Посада англійською', 'type' => 'text'],
    'company' => ['label' => 'Компанія', 'type' => 'text'],
    'text_uk' => ['label' => 'Текст відгуку українською', 'type' => 'textarea'],
    'text_en' => ['label' => 'Текст відгуку англійською', 'type' => 'textarea'],
    'category' => ['label' => 'Категорія (strategy, marketing, development, content)', 'type' => 'text'],
    'rating' => ['label' => 'Оцінка від 1 до 5', 'type' => 'number'],
    'metrics' => ['label' => 'Метрики (кожна з нового рядка)', 'type' => 'textarea'],
];

const GVSPACE_SERVICE_FIELDS = [
    'title_en' => ['label' => 'Назва англійською', 'type' => 'text'],
    'headline_uk' => ['label' => 'Головний заголовок українською', 'type' => 'text'],
    'headline_en' => ['label' => 'Головний заголовок англійською', 'type' => 'text'],
    'description_uk' => ['label' => 'Опис українською', 'type' => 'textarea'],
    'description_en' => ['label' => 'Опис англійською', 'type' => 'textarea'],
    'includes_uk' => ['label' => 'Що входить (українською, кожне з нового рядка)', 'type' => 'textarea'],
    'includes_en' => ['label' => 'Що входить (англійською, кожне з нового рядка)', 'type' => 'textarea'],
    'steps_uk' => ['label' => 'Етапи українською: назва | термін | опис', 'type' => 'textarea'],
    'steps_en' => ['label' => 'Етапи англійською: назва | термін | опис', 'type' => 'textarea'],
    'metrics' => ['label' => 'Метрики результату (кожна з нового рядка)', 'type' => 'textarea'],
    'faq_uk' => ['label' => 'FAQ українською: питання | відповідь', 'type' => 'textarea'],
    'faq_en' => ['label' => 'FAQ англійською: питання | відповідь', 'type' => 'textarea'],
];

const GVSPACE_TEAM_MEMBER_FIELDS = [
    'role' => ['label' => 'Посада / роль', 'type' => 'text'],
    'tags' => ['label' => 'Компетенції (кожна з нового рядка)', 'type' => 'textarea'],
];

// New localized records contain exactly one language. Legacy field sets above
// remain available while existing UK + EN records are being migrated.
const GVSPACE_LOCALIZED_VACANCY_FIELDS = [
    'excerpt' => ['label' => 'Короткий опис', 'type' => 'textarea'],
    'salary' => ['label' => 'Зарплата', 'type' => 'text'],
    'tags' => ['label' => 'Теги (кожен з нового рядка)', 'type' => 'textarea'],
    'role' => ['label' => 'Про роль (абзац з нового рядка)', 'type' => 'textarea'],
    'tasks' => ['label' => 'Задачі (пункт з нового рядка)', 'type' => 'textarea'],
    'requirements' => ['label' => 'Вимоги (пункт з нового рядка)', 'type' => 'textarea'],
    'tools' => ['label' => 'Інструменти (кожен з нового рядка)', 'type' => 'textarea'],
    'benefits' => ['label' => 'Ми пропонуємо (пункт з нового рядка)', 'type' => 'textarea'],
];

const GVSPACE_LOCALIZED_REVIEW_FIELDS = [
    'position' => ['label' => 'Посада', 'type' => 'text'],
    'company' => ['label' => 'Компанія', 'type' => 'text'],
    'text' => ['label' => 'Текст відгуку', 'type' => 'textarea'],
    'category' => ['label' => 'Категорія (strategy, marketing, development, content)', 'type' => 'text'],
    'rating' => ['label' => 'Оцінка від 1 до 5', 'type' => 'number'],
    'metrics' => ['label' => 'Метрики (кожна з нового рядка)', 'type' => 'textarea'],
];

const GVSPACE_LOCALIZED_SERVICE_FIELDS = [
    'headline' => ['label' => 'Головний заголовок', 'type' => 'text'],
    'description' => ['label' => 'Опис', 'type' => 'textarea'],
    'includes' => ['label' => 'Що входить (кожне з нового рядка)', 'type' => 'textarea'],
    'steps' => ['label' => 'Етапи: назва | термін | опис', 'type' => 'textarea'],
    'metrics' => ['label' => 'Метрики результату (кожна з нового рядка)', 'type' => 'textarea'],
    'faq' => ['label' => 'FAQ: питання | відповідь', 'type' => 'textarea'],
];

const GVSPACE_CASE_FIELDS = [
    'result' => ['label' => 'Головний результат одним реченням', 'type' => 'textarea'],
    'services' => ['label' => 'Послуги (кожна з нового рядка)', 'type' => 'textarea'],
    'metrics' => ['label' => 'Метрики: значення | назва (кожна з нового рядка)', 'type' => 'textarea'],
    'challenge' => ['label' => 'З чим прийшов клієнт', 'type' => 'textarea'],
    'problems' => ['label' => 'Список проблем (кожна з нового рядка)', 'type' => 'textarea'],
    'discovery' => ['label' => 'Крок 1: що показав аудит', 'type' => 'textarea'],
    'discovery_result' => ['label' => 'Результат першого етапу', 'type' => 'textarea'],
    'architecture' => ['label' => 'Вектори: назва | опис (кожен з нового рядка)', 'type' => 'textarea'],
    'gallery' => ['label' => 'URL зображень галереї (кожен з нового рядка)', 'type' => 'textarea'],
    'testimonial' => ['label' => 'Текст відгуку', 'type' => 'textarea'],
    'testimonial_author' => ['label' => 'Автор відгуку', 'type' => 'text'],
    'project_type' => ['label' => 'Тип проєкту (ecommerce, strategy, development, marketing)', 'type' => 'text'],
    'industry' => ['label' => 'Індустрія (retail, services, technology)', 'type' => 'text'],
    'badge' => ['label' => 'Бейдж результату для картки', 'type' => 'text'],
];

const GVSPACE_LOCALIZED_POST_TYPES = [
    'post',
    'gv_vacancy',
    'gv_case',
    'gv_service',
    'gv_review',
    'gv_technology',
    'gv_team_member',
];

const GVSPACE_CONTENT_LOCALES = [
    'uk' => 'Українська (Україна)',
    'en' => 'English (International)',
    'pl' => 'Polski (Polska)',
    'de-DE' => 'Deutsch (Deutschland)',
    'de-AT' => 'Deutsch (Österreich)',
    'es' => 'Español (España)',
    'fr' => 'Français (France)',
    'it' => 'Italiano (Italia)',
    'nl' => 'Nederlands (Nederland)',
    'cs' => 'Čeština (Česko)',
    'sk' => 'Slovenčina (Slovensko)',
    'en-GB' => 'English (United Kingdom)',
];
const GVSPACE_TRANSLATION_STATUSES = ['missing', 'draft', 'published'];

const GVSPACE_SEO_FIELDS = [
    'title' => ['label' => 'SEO Title', 'type' => 'text', 'limit' => 60],
    'description' => ['label' => 'Meta Description', 'type' => 'textarea', 'limit' => 160],
    'h1' => ['label' => 'H1 сторінки', 'type' => 'text', 'limit' => 0],
    'og_title' => ['label' => 'Open Graph Title', 'type' => 'text', 'limit' => 0],
    'og_description' => ['label' => 'Open Graph Description', 'type' => 'textarea', 'limit' => 0],
    'og_image' => ['label' => 'Open Graph Image URL', 'type' => 'url', 'limit' => 0],
];

add_action('after_setup_theme', function (): void {
    add_theme_support('post-thumbnails');
});

// Technology logos are best stored as SVG. Restrict SVG uploads to administrators
// because SVG files may contain scripts or other active content.
add_filter('upload_mimes', function (array $mimes): array {
    if (current_user_can('manage_options')) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
});

add_filter('wp_check_filetype_and_ext', function (array $data, string $file, string $filename, ?array $mimes): array {
    if (!current_user_can('manage_options') || strtolower((string) pathinfo($filename, PATHINFO_EXTENSION)) !== 'svg') {
        return $data;
    }

    $data['ext'] = 'svg';
    $data['type'] = 'image/svg+xml';
    $data['proper_filename'] = $filename;
    return $data;
}, 10, 4);

add_action('show_user_profile', 'gvspace_render_author_fields');
add_action('edit_user_profile', 'gvspace_render_author_fields');

function gvspace_render_author_fields(WP_User $user): void
{
    ?>
    <h2>Профіль автора GVSPACE</h2>
    <table class="form-table" role="presentation">
        <?php foreach (GVSPACE_AUTHOR_FIELDS as $key => $label) : ?>
            <tr>
                <th><label for="gvspace_author_<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label></th>
                <td><input class="regular-text" id="gvspace_author_<?php echo esc_attr($key); ?>" name="gvspace_author_<?php echo esc_attr($key); ?>" value="<?php echo esc_attr((string) get_user_meta($user->ID, '_gvspace_author_' . $key, true)); ?>"></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
}

add_action('personal_options_update', 'gvspace_save_author_fields');
add_action('edit_user_profile_update', 'gvspace_save_author_fields');

function gvspace_save_author_fields(int $user_id): void
{
    if (!current_user_can('edit_user', $user_id)) return;
    foreach (array_keys(GVSPACE_AUTHOR_FIELDS) as $key) {
        $value = isset($_POST['gvspace_author_' . $key])
            ? sanitize_text_field(wp_unslash($_POST['gvspace_author_' . $key]))
            : '';
        update_user_meta($user_id, '_gvspace_author_' . $key, $value);
    }
}

add_action('init', function (): void {
    register_post_type('gv_vacancy', [
        'labels' => [
            'name' => 'Вакансії',
            'singular_name' => 'Вакансія',
            'add_new_item' => 'Додати вакансію',
            'edit_item' => 'Редагувати вакансію',
            'new_item' => 'Нова вакансія',
            'view_item' => 'Переглянути вакансію',
            'search_items' => 'Шукати вакансії',
            'not_found' => 'Вакансій не знайдено',
        ],
        'public' => true,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'vacancy',
        'graphql_plural_name' => 'vacancies',
        'menu_icon' => 'dashicons-businessperson',
        'rewrite' => ['slug' => 'careers'],
        'supports' => ['title', 'page-attributes'],
    ]);

    register_post_type('gv_case', [
        'labels' => [
            'name' => 'Кейси',
            'singular_name' => 'Кейс',
            'add_new_item' => 'Додати кейс',
            'edit_item' => 'Редагувати кейс',
            'new_item' => 'Новий кейс',
            'view_item' => 'Переглянути кейс',
            'search_items' => 'Шукати кейси',
            'not_found' => 'Кейсів не знайдено',
        ],
        'public' => true,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'projectCase',
        'graphql_plural_name' => 'projectCases',
        'menu_icon' => 'dashicons-portfolio',
        'rewrite' => ['slug' => 'cases'],
        'supports' => ['title', 'thumbnail', 'page-attributes'],
    ]);

    register_post_type('gv_service', [
        'labels' => [
            'name' => 'Послуги', 'singular_name' => 'Послуга', 'add_new_item' => 'Додати послугу',
            'edit_item' => 'Редагувати послугу', 'new_item' => 'Нова послуга', 'all_items' => 'Усі послуги',
            'parent_item_colon' => 'Батьківський напрямок:', 'not_found' => 'Послуг не знайдено',
        ],
        'public' => true, 'hierarchical' => true, 'show_in_rest' => true, 'show_in_graphql' => true,
        'graphql_single_name' => 'serviceOffering', 'graphql_plural_name' => 'serviceOfferings',
        'menu_icon' => 'dashicons-admin-generic', 'rewrite' => ['slug' => 'services'],
        'supports' => ['title', 'thumbnail', 'page-attributes'],
    ]);

    register_post_type('gv_review', [
        'labels' => [
            'name' => 'Відгуки', 'singular_name' => 'Відгук', 'add_new_item' => 'Додати відгук',
            'edit_item' => 'Редагувати відгук', 'new_item' => 'Новий відгук', 'all_items' => 'Усі відгуки',
            'not_found' => 'Відгуків не знайдено',
        ],
        'public' => true, 'publicly_queryable' => false, 'exclude_from_search' => true,
        'show_in_rest' => true, 'show_in_graphql' => true,
        'graphql_single_name' => 'clientReview', 'graphql_plural_name' => 'clientReviews',
        'menu_icon' => 'dashicons-star-filled',
        'supports' => ['title', 'thumbnail', 'page-attributes'],
    ]);

    register_taxonomy('gv_team_member_category', ['gv_team_member'], [
        'labels' => [
            'name' => 'Таби команди', 'singular_name' => 'Таб команди', 'menu_name' => 'Таби',
            'all_items' => 'Усі таби', 'edit_item' => 'Редагувати таб', 'add_new_item' => 'Додати таб',
        ],
        'public' => true, 'hierarchical' => false, 'show_admin_column' => true,
        'show_in_rest' => true, 'show_in_graphql' => true,
        'graphql_single_name' => 'teamMemberCategory',
        'graphql_plural_name' => 'teamMemberCategories',
    ]);

    register_post_type('gv_team_member', [
        'labels' => [
            'name' => 'Команда', 'singular_name' => 'Учасник команди', 'menu_name' => 'Команда',
            'add_new_item' => 'Додати людину', 'edit_item' => 'Редагувати профіль',
            'new_item' => 'Новий учасник команди', 'all_items' => 'Усі люди',
            'not_found' => 'Учасників команди не знайдено',
        ],
        'public' => true, 'publicly_queryable' => false, 'exclude_from_search' => true,
        'show_in_rest' => true, 'show_in_graphql' => true,
        'graphql_single_name' => 'teamMember', 'graphql_plural_name' => 'teamMembers',
        'menu_icon' => 'dashicons-groups',
        'supports' => ['title', 'thumbnail', 'page-attributes'],
        'taxonomies' => ['gv_team_member_category'],
    ]);

    register_taxonomy('gv_technology_category', ['gv_technology'], [
        'labels' => [
            'name' => 'Категорії технологій',
            'singular_name' => 'Категорія технологій',
            'menu_name' => 'Категорії',
            'all_items' => 'Усі категорії',
            'edit_item' => 'Редагувати категорію',
            'add_new_item' => 'Додати категорію',
        ],
        'public' => true,
        'hierarchical' => false,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'technologyCategory',
        'graphql_plural_name' => 'technologyCategories',
    ]);

    register_post_type('gv_technology', [
        'labels' => [
            'name' => 'Технології',
            'singular_name' => 'Технологія',
            'menu_name' => 'Технології',
            'add_new_item' => 'Додати технологію',
            'edit_item' => 'Редагувати технологію',
            'new_item' => 'Нова технологія',
            'all_items' => 'Усі технології',
            'not_found' => 'Технологій не знайдено',
        ],
        'public' => true,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'technology',
        'graphql_plural_name' => 'technologies',
        'menu_icon' => 'dashicons-admin-tools',
        'supports' => ['title', 'thumbnail', 'page-attributes'],
        'taxonomies' => ['gv_technology_category'],
    ]);

    foreach (GVSPACE_LOCALIZED_POST_TYPES as $post_type) {
        register_post_meta($post_type, '_gvspace_content_locale', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'gvspace_sanitize_content_locale',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
        register_post_meta($post_type, '_gvspace_translation_group', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_key',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
        register_post_meta($post_type, '_gvspace_translation_status', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'gvspace_sanitize_translation_status',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);

        foreach (array_keys(GVSPACE_SEO_FIELDS) as $seo_field) {
            foreach (['', '_uk', '_en'] as $locale_suffix) {
                register_post_meta($post_type, '_gvspace_seo_' . $seo_field . $locale_suffix, [
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true,
                    'sanitize_callback' => $seo_field === 'og_image' ? 'esc_url_raw' : 'sanitize_textarea_field',
                    'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
                ]);
            }
        }
    }

    register_post_meta('gv_technology', '_gvspace_technology_title_en', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
    ]);

    foreach (array_keys(GVSPACE_CASE_FIELDS) as $field) {
        register_post_meta('gv_case', '_gvspace_case_' . $field, [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_textarea_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
    }

    foreach (array_keys(GVSPACE_SERVICE_FIELDS) as $field) {
        register_post_meta('gv_service', '_gvspace_service_' . $field, [
            'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_textarea_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
    }

    foreach (array_keys(GVSPACE_LOCALIZED_SERVICE_FIELDS) as $field) {
        register_post_meta('gv_service', '_gvspace_service_localized_' . $field, [
            'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_textarea_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
    }

    foreach (array_keys(GVSPACE_REVIEW_FIELDS) as $field) {
        register_post_meta('gv_review', '_gvspace_review_' . $field, [
            'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_textarea_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
    }

    foreach (array_keys(GVSPACE_LOCALIZED_REVIEW_FIELDS) as $field) {
        register_post_meta('gv_review', '_gvspace_review_localized_' . $field, [
            'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_textarea_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
    }

    foreach (array_keys(GVSPACE_VACANCY_FIELDS) as $field) {
        register_post_meta('gv_vacancy', '_gvspace_' . $field, [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_textarea_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
    }

    foreach (array_keys(GVSPACE_LOCALIZED_VACANCY_FIELDS) as $field) {
        register_post_meta('gv_vacancy', '_gvspace_vacancy_localized_' . $field, [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_textarea_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
    }

    foreach (array_keys(GVSPACE_TEAM_MEMBER_FIELDS) as $field) {
        register_post_meta('gv_team_member', '_gvspace_team_member_' . $field, [
            'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_textarea_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
    }

    register_post_meta('gv_vacancy', '_gvspace_hot', [
        'type' => 'boolean',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
    ]);
});

function gvspace_sanitize_content_locale(string $value): string
{
    return array_key_exists($value, GVSPACE_CONTENT_LOCALES) ? $value : 'legacy';
}

function gvspace_get_content_locale(WP_Post $post): string
{
    if (isset($_POST['gvspace_content_locale'])) {
        return gvspace_sanitize_content_locale(sanitize_text_field(wp_unslash($_POST['gvspace_content_locale'])));
    }

    $stored_locale = (string) get_post_meta($post->ID, '_gvspace_content_locale', true);
    return $stored_locale ?: ($post->post_status === 'auto-draft' ? 'uk' : 'legacy');
}

function gvspace_render_field_set(WP_Post $post, array $fields, string $name_prefix, string $meta_prefix): void
{
    foreach ($fields as $key => $config) {
        $field_name = $name_prefix . $key;
        $value = (string) get_post_meta($post->ID, $meta_prefix . $key, true);
        echo '<p><label for="' . esc_attr($field_name) . '"><strong>' . esc_html($config['label']) . '</strong></label><br>';
        if ($config['type'] === 'textarea') {
            echo '<textarea id="' . esc_attr($field_name) . '" name="' . esc_attr($field_name) . '" rows="4" style="width:100%">' . esc_textarea($value) . '</textarea>';
        } else {
            echo '<input type="' . esc_attr($config['type'] === 'number' ? 'number' : 'text') . '" id="' . esc_attr($field_name) . '" name="' . esc_attr($field_name) . '" value="' . esc_attr($value) . '" style="width:100%">';
        }
        echo '</p>';
    }
}

function gvspace_save_field_set(int $post_id, array $fields, string $name_prefix, string $meta_prefix): void
{
    foreach (array_keys($fields) as $field) {
        if (!isset($_POST[$name_prefix . $field])) continue;
        update_post_meta($post_id, $meta_prefix . $field, sanitize_textarea_field(wp_unslash($_POST[$name_prefix . $field])));
    }
}

function gvspace_sanitize_translation_status(string $value): string
{
    return in_array($value, GVSPACE_TRANSLATION_STATUSES, true) ? $value : 'draft';
}

add_action('add_meta_boxes', function (): void {
    foreach (GVSPACE_LOCALIZED_POST_TYPES as $post_type) {
        add_meta_box(
            'gvspace-localization',
            'GVSPACE: локалізація',
            'gvspace_render_localization_fields',
            $post_type,
            'side',
            'high'
        );
    }
});

function gvspace_render_localization_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_localization', 'gvspace_localization_nonce');
    $stored_locale = (string) get_post_meta($post->ID, '_gvspace_content_locale', true);
    $locale = $stored_locale ?: ($post->post_status === 'auto-draft' ? 'uk' : 'legacy');
    $group = (string) get_post_meta($post->ID, '_gvspace_translation_group', true);
    $stored_status = (string) get_post_meta($post->ID, '_gvspace_translation_status', true);
    $status = $stored_status ?: ($post->post_status === 'auto-draft' ? 'draft' : 'published');
    ?>
    <p>
        <label for="gvspace_content_locale"><strong>Мова запису</strong></label><br>
        <select id="gvspace_content_locale" name="gvspace_content_locale" style="width:100%">
            <option value="legacy" <?php selected($locale, 'legacy'); ?>>Legacy: UK + EN в одному записі</option>
            <?php foreach (GVSPACE_CONTENT_LOCALES as $locale_code => $locale_label) : ?>
                <option value="<?php echo esc_attr($locale_code); ?>" <?php selected($locale, $locale_code); ?>><?php echo esc_html($locale_label); ?></option>
            <?php endforeach; ?>
        </select>
        <span class="description">Один запис містить контент лише вибраною мовою. Після зміни мови збережіть запис.</span>
    </p>
    <p>
        <label for="gvspace_translation_group"><strong>Група перекладів</strong></label><br>
        <input id="gvspace_translation_group" name="gvspace_translation_group" type="text" value="<?php echo esc_attr($group); ?>" placeholder="service-development" style="width:100%">
        <span class="description">Однаковий ключ пов’язує переклади та використовується як спільна частина URL. Після публікації не змінюйте його без налаштування редиректу.</span>
    </p>
    <p>
        <label for="gvspace_translation_status"><strong>Статус перекладу</strong></label><br>
        <select id="gvspace_translation_status" name="gvspace_translation_status" style="width:100%">
            <option value="missing" <?php selected($status, 'missing'); ?>>Відсутній</option>
            <option value="draft" <?php selected($status, 'draft'); ?>>Чернетка</option>
            <option value="published" <?php selected($status, 'published'); ?>>Опублікований</option>
        </select>
    </p>
    <?php if ($post->post_status !== 'auto-draft') : ?>
        <hr>
        <p><strong>Створити переклад</strong></p>
        <?php foreach (GVSPACE_CONTENT_LOCALES as $target_locale => $target_label) : ?>
            <?php if ($target_locale === $locale) continue; ?>
            <?php
            $translation_url = wp_nonce_url(
                add_query_arg([
                    'action' => 'gvspace_duplicate_post',
                    'post' => $post->ID,
                    'target_locale' => $target_locale,
                ], admin_url('admin-post.php')),
                'gvspace_duplicate_post_' . $post->ID
            );
            ?>
            <a class="button" href="<?php echo esc_url($translation_url); ?>" style="margin:0 4px 4px 0">
                <?php echo esc_html($target_label); ?>
            </a>
        <?php endforeach; ?>
        <p class="description">Буде створена чернетка в цій самій групі перекладів.</p>
    <?php endif; ?>
    <?php
}

add_action('save_post', function (int $post_id, WP_Post $post): void {
    if (
        !in_array($post->post_type, GVSPACE_LOCALIZED_POST_TYPES, true)
        || !isset($_POST['gvspace_localization_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_localization_nonce'])), 'gvspace_save_localization')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $locale = isset($_POST['gvspace_content_locale'])
        ? gvspace_sanitize_content_locale(sanitize_text_field(wp_unslash($_POST['gvspace_content_locale'])))
        : ($post->post_status === 'auto-draft' ? 'uk' : 'legacy');
    $group = isset($_POST['gvspace_translation_group'])
        ? sanitize_key(wp_unslash($_POST['gvspace_translation_group']))
        : '';
    $status = isset($_POST['gvspace_translation_status'])
        ? gvspace_sanitize_translation_status(sanitize_text_field(wp_unslash($_POST['gvspace_translation_status'])))
        : 'draft';

    update_post_meta($post_id, '_gvspace_content_locale', $locale);
    $default_group = sanitize_title($post->post_name ?: $post->post_title);
    update_post_meta($post_id, '_gvspace_translation_group', $group ?: ($default_group ?: $post->post_type . '-' . $post_id));
    update_post_meta($post_id, '_gvspace_translation_status', $status);
}, 10, 2);

add_action('add_meta_boxes', function (): void {
    foreach (GVSPACE_LOCALIZED_POST_TYPES as $post_type) {
        add_meta_box(
            'gvspace-seo',
            'GVSPACE: SEO та соцмережі',
            'gvspace_render_seo_fields',
            $post_type,
            'normal',
            'high'
        );
    }
});

function gvspace_render_seo_field(WP_Post $post, string $key, array $config, string $locale_suffix = ''): void
{
    $field_id = 'gvspace_seo_' . $key . $locale_suffix;
    $value = (string) get_post_meta($post->ID, '_gvspace_seo_' . $key . $locale_suffix, true);
    $limit = (int) ($config['limit'] ?? 0);
    echo '<p><label for="' . esc_attr($field_id) . '"><strong>' . esc_html($config['label']) . '</strong></label><br>';
    if ($config['type'] === 'textarea') {
        echo '<textarea id="' . esc_attr($field_id) . '" name="' . esc_attr($field_id) . '" rows="3" style="width:100%">' . esc_textarea($value) . '</textarea>';
    } else {
        $type = $config['type'] === 'url' ? 'url' : 'text';
        echo '<input type="' . esc_attr($type) . '" id="' . esc_attr($field_id) . '" name="' . esc_attr($field_id) . '" value="' . esc_attr($value) . '" style="width:100%">';
    }
    if ($limit) echo '<span class="description">Рекомендовано до ' . esc_html((string) $limit) . ' символів.</span>';
    echo '</p>';
}

function gvspace_render_seo_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_seo', 'gvspace_seo_nonce');
    $locale = gvspace_get_content_locale($post);
    echo '<p class="description">Поля належать лише цьому мовному запису. Якщо Open Graph поля порожні, сайт використає SEO Title, Meta Description і головне зображення. Canonical та hreflang генеруються автоматично.</p>';

    if ($locale === 'legacy') {
        foreach (['uk' => 'Українська', 'en' => 'English'] as $legacy_locale => $label) {
            echo '<hr><h3>' . esc_html($label) . '</h3>';
            foreach (GVSPACE_SEO_FIELDS as $key => $config) {
                gvspace_render_seo_field($post, $key, $config, '_' . $legacy_locale);
            }
        }
        return;
    }

    echo '<p><strong>Мова SEO: ' . esc_html(GVSPACE_CONTENT_LOCALES[$locale]) . '</strong></p>';
    foreach (GVSPACE_SEO_FIELDS as $key => $config) {
        gvspace_render_seo_field($post, $key, $config);
    }
}

add_action('save_post', function (int $post_id, WP_Post $post): void {
    if (
        !in_array($post->post_type, GVSPACE_LOCALIZED_POST_TYPES, true)
        || !isset($_POST['gvspace_seo_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_seo_nonce'])), 'gvspace_save_seo')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $locale = gvspace_get_content_locale($post);
    $suffixes = $locale === 'legacy' ? ['_uk', '_en'] : [''];
    foreach ($suffixes as $locale_suffix) {
        foreach (array_keys(GVSPACE_SEO_FIELDS) as $key) {
            $field_name = 'gvspace_seo_' . $key . $locale_suffix;
            if (!isset($_POST[$field_name])) continue;
            $raw_value = wp_unslash($_POST[$field_name]);
            $value = $key === 'og_image' ? esc_url_raw($raw_value) : sanitize_textarea_field($raw_value);
            update_post_meta($post_id, '_gvspace_seo_' . $key . $locale_suffix, $value);
        }
    }
}, 10, 2);

foreach (GVSPACE_LOCALIZED_POST_TYPES as $gvspace_localized_post_type) {
    add_filter("manage_{$gvspace_localized_post_type}_posts_columns", function (array $columns): array {
        $columns['gvspace_locale'] = 'Мова';
        $columns['gvspace_translation_status'] = 'Переклад';
        return $columns;
    });

    add_action("manage_{$gvspace_localized_post_type}_posts_custom_column", function (string $column, int $post_id): void {
        if ($column === 'gvspace_locale') {
            $locale = (string) get_post_meta($post_id, '_gvspace_content_locale', true) ?: 'legacy';
            echo esc_html(strtoupper($locale));
        }
        if ($column === 'gvspace_translation_status') {
            $status = (string) get_post_meta($post_id, '_gvspace_translation_status', true) ?: 'published';
            echo esc_html($status);
        }
    }, 10, 2);
}

add_action('add_meta_boxes', function (): void {
    add_meta_box('gvspace-case-details', 'Дані кейсу', 'gvspace_render_case_fields', 'gv_case', 'normal', 'high');
    add_meta_box('gvspace-technology-details', 'Налаштування технології', 'gvspace_render_technology_fields', 'gv_technology', 'normal', 'high');
    add_meta_box('gvspace-team-member-details', 'Дані учасника команди', 'gvspace_render_team_member_fields', 'gv_team_member', 'normal', 'high');
});

function gvspace_render_team_member_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_team_member', 'gvspace_team_member_nonce');
    echo '<p class="description">Ім’я вкажіть у заголовку, фото — у «Головному зображенні», таби — у блоці «Таби команди», позицію картки — у полі «Порядок».</p>';
    gvspace_render_field_set($post, GVSPACE_TEAM_MEMBER_FIELDS, 'gvspace_team_member_', '_gvspace_team_member_');
}

add_action('save_post_gv_team_member', function (int $post_id): void {
    if (
        !isset($_POST['gvspace_team_member_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_team_member_nonce'])), 'gvspace_save_team_member')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) return;
    gvspace_save_field_set($post_id, GVSPACE_TEAM_MEMBER_FIELDS, 'gvspace_team_member_', '_gvspace_team_member_');
});

function gvspace_render_technology_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_technology', 'gvspace_technology_nonce');
    $locale = gvspace_get_content_locale($post);
    if ($locale !== 'legacy') {
        echo '<p class="description">Назву введіть у стандартному полі заголовка вибраною мовою. Іконку завантажте через «Головне зображення», таб оберіть у «Категоріях», позицію — у полі «Порядок».</p>';
        return;
    }
    $title_en = (string) get_post_meta($post->ID, '_gvspace_technology_title_en', true);
    ?>
    <p class="description">
        Українську назву задайте у полі заголовка. Іконку завантажте через «Головне зображення».
        Таб оберіть у блоці «Категорії», а позицію картки — у полі «Порядок».
    </p>
    <p>
        <label for="gvspace_technology_title_en"><strong>Назва англійською</strong></label><br>
        <input class="regular-text" id="gvspace_technology_title_en" name="gvspace_technology_title_en" value="<?php echo esc_attr($title_en); ?>">
    </p>
    <?php
}

add_action('save_post_gv_technology', function (int $post_id): void {
    if (
        !isset($_POST['gvspace_technology_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_technology_nonce'])), 'gvspace_save_technology')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $post = get_post($post_id);
    if (!$post || gvspace_get_content_locale($post) !== 'legacy') return;

    $title_en = isset($_POST['gvspace_technology_title_en'])
        ? sanitize_text_field(wp_unslash($_POST['gvspace_technology_title_en']))
        : '';
    update_post_meta($post_id, '_gvspace_technology_title_en', $title_en);
});

function gvspace_render_case_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_case', 'gvspace_case_nonce');
    echo '<p class="description">Усі поля цього запису заповнюйте мовою, вибраною у блоці «GVSPACE: локалізація». Назва задається у стандартному заголовку, обкладинка — у «Головному зображенні».</p>';
    foreach (GVSPACE_CASE_FIELDS as $key => $config) {
        $value = (string) get_post_meta($post->ID, '_gvspace_case_' . $key, true);
        echo '<p><label for="gvspace_case_' . esc_attr($key) . '"><strong>' . esc_html($config['label']) . '</strong></label><br>';
        if ($config['type'] === 'textarea') {
            echo '<textarea id="gvspace_case_' . esc_attr($key) . '" name="gvspace_case_' . esc_attr($key) . '" rows="4" style="width:100%">' . esc_textarea($value) . '</textarea>';
        } else {
            echo '<input id="gvspace_case_' . esc_attr($key) . '" name="gvspace_case_' . esc_attr($key) . '" value="' . esc_attr($value) . '" style="width:100%">';
        }
        echo '</p>';
    }
}

add_action('save_post_gv_case', function (int $post_id): void {
    if (!isset($_POST['gvspace_case_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_case_nonce'])), 'gvspace_save_case') || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) return;
    foreach (array_keys(GVSPACE_CASE_FIELDS) as $field) {
        $value = isset($_POST['gvspace_case_' . $field]) ? sanitize_textarea_field(wp_unslash($_POST['gvspace_case_' . $field])) : '';
        update_post_meta($post_id, '_gvspace_case_' . $field, $value);
    }
});

add_action('add_meta_boxes', function (): void {
    add_meta_box('gvspace-review-details', 'Дані відгуку', 'gvspace_render_review_fields', 'gv_review', 'normal', 'high');
});

function gvspace_render_review_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_review', 'gvspace_review_nonce');
    $locale = gvspace_get_content_locale($post);
    echo '<p class="description">Ім’я задайте у заголовку, фото — у «Головному зображенні», порядок — у полі «Порядок».</p>';
    if ($locale === 'legacy') {
        echo '<p class="description"><strong>Legacy:</strong> старий запис із двома мовами.</p>';
        gvspace_render_field_set($post, GVSPACE_REVIEW_FIELDS, 'gvspace_review_', '_gvspace_review_');
    } else {
        echo '<p class="description">Заповнюйте всі поля мовою запису: <strong>' . esc_html(GVSPACE_CONTENT_LOCALES[$locale]) . '</strong>.</p>';
        gvspace_render_field_set($post, GVSPACE_LOCALIZED_REVIEW_FIELDS, 'gvspace_review_localized_', '_gvspace_review_localized_');
    }
}

add_action('save_post_gv_review', function (int $post_id): void {
    if (!isset($_POST['gvspace_review_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_review_nonce'])), 'gvspace_save_review') || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) return;
    $post = get_post($post_id);
    if (!$post) return;
    if (gvspace_get_content_locale($post) === 'legacy') gvspace_save_field_set($post_id, GVSPACE_REVIEW_FIELDS, 'gvspace_review_', '_gvspace_review_');
    else gvspace_save_field_set($post_id, GVSPACE_LOCALIZED_REVIEW_FIELDS, 'gvspace_review_localized_', '_gvspace_review_localized_');
});

add_action('add_meta_boxes', function (): void {
    add_meta_box('gvspace-service-details', 'Дані послуги', 'gvspace_render_service_fields', 'gv_service', 'normal', 'high');
});
function gvspace_render_service_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_service', 'gvspace_service_nonce');
    echo '<p class="description">Запис без батьківського елемента — напрямок (L2), дочірній запис — конкретна послуга (L3). Іконку напрямку завантажте як головне зображення.</p>';
    $locale = gvspace_get_content_locale($post);
    if ($locale === 'legacy') {
        echo '<p class="description"><strong>Legacy:</strong> старий запис із двома мовами.</p>';
        gvspace_render_field_set($post, GVSPACE_SERVICE_FIELDS, 'gvspace_service_', '_gvspace_service_');
    } else {
        echo '<p class="description">Назву введіть у заголовку, решту полів — мовою запису: <strong>' . esc_html(GVSPACE_CONTENT_LOCALES[$locale]) . '</strong>.</p>';
        gvspace_render_field_set($post, GVSPACE_LOCALIZED_SERVICE_FIELDS, 'gvspace_service_localized_', '_gvspace_service_localized_');
    }
}
add_action('save_post_gv_service', function (int $post_id): void {
    if (!isset($_POST['gvspace_service_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_service_nonce'])), 'gvspace_save_service') || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) return;
    $post = get_post($post_id);
    if (!$post) return;
    if (gvspace_get_content_locale($post) === 'legacy') gvspace_save_field_set($post_id, GVSPACE_SERVICE_FIELDS, 'gvspace_service_', '_gvspace_service_');
    else gvspace_save_field_set($post_id, GVSPACE_LOCALIZED_SERVICE_FIELDS, 'gvspace_service_localized_', '_gvspace_service_localized_');
});

add_action('add_meta_boxes', function (): void {
    add_meta_box(
        'gvspace-vacancy-details',
        'Дані вакансії',
        'gvspace_render_vacancy_fields',
        'gv_vacancy',
        'normal',
        'high'
    );
});

function gvspace_render_vacancy_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_vacancy', 'gvspace_vacancy_nonce');
    $hot = (bool) get_post_meta($post->ID, '_gvspace_hot', true);
    ?>
    <p>
        <label>
            <input type="checkbox" name="gvspace_hot" value="1" <?php checked($hot); ?>>
            Позначити вакансію як гарячу
        </label>
    </p>
    <p class="description">Назву введіть у стандартному полі заголовка. Банер вакансії є спільним і задається у Next.js.</p>
    <?php
    $locale = gvspace_get_content_locale($post);
    if ($locale === 'legacy') {
        echo '<p class="description"><strong>Legacy:</strong> старий запис із двома мовами.</p>';
        gvspace_render_field_set($post, GVSPACE_VACANCY_FIELDS, 'gvspace_', '_gvspace_');
    } else {
        echo '<p class="description">Заповнюйте всі поля мовою запису: <strong>' . esc_html(GVSPACE_CONTENT_LOCALES[$locale]) . '</strong>.</p>';
        gvspace_render_field_set($post, GVSPACE_LOCALIZED_VACANCY_FIELDS, 'gvspace_vacancy_localized_', '_gvspace_vacancy_localized_');
    }
}

add_action('save_post_gv_vacancy', function (int $post_id): void {
    if (
        !isset($_POST['gvspace_vacancy_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_vacancy_nonce'])), 'gvspace_save_vacancy')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    update_post_meta($post_id, '_gvspace_hot', isset($_POST['gvspace_hot']));

    $post = get_post($post_id);
    if (!$post) return;
    if (gvspace_get_content_locale($post) === 'legacy') gvspace_save_field_set($post_id, GVSPACE_VACANCY_FIELDS, 'gvspace_', '_gvspace_');
    else gvspace_save_field_set($post_id, GVSPACE_LOCALIZED_VACANCY_FIELDS, 'gvspace_vacancy_localized_', '_gvspace_vacancy_localized_');
});

add_action('graphql_register_types', function (): void {
    if (!function_exists('register_graphql_object_type')) {
        return;
    }

    register_graphql_object_type('GvspaceLocalization', [
        'description' => 'Locale, translation group and editorial readiness for a GVSPACE content item.',
        'fields' => [
            'locale' => ['type' => 'String'],
            'translationGroup' => ['type' => 'String'],
            'status' => ['type' => 'String'],
        ],
    ]);

    register_graphql_object_type('GvspaceSeo', [
        'description' => 'Resolved SEO data for one GVSPACE content language.',
        'fields' => [
            'title' => ['type' => 'String'],
            'description' => ['type' => 'String'],
            'h1' => ['type' => 'String'],
            'openGraphTitle' => ['type' => 'String'],
            'openGraphDescription' => ['type' => 'String'],
            'openGraphImage' => ['type' => 'String'],
            'datePublished' => ['type' => 'String'],
            'dateModified' => ['type' => 'String'],
        ],
    ]);

    foreach (['Post', 'Vacancy', 'ProjectCase', 'ServiceOffering', 'ClientReview', 'Technology', 'TeamMember'] as $graphql_type) {
        register_graphql_field($graphql_type, 'gvspaceLocalization', [
            'type' => 'GvspaceLocalization',
            'resolve' => static function ($source): array {
                $post_id = (int) ($source->databaseId ?? $source->ID ?? 0);
                return [
                    'locale' => (string) get_post_meta($post_id, '_gvspace_content_locale', true) ?: 'legacy',
                    'translationGroup' => (string) get_post_meta($post_id, '_gvspace_translation_group', true),
                    'status' => (string) get_post_meta($post_id, '_gvspace_translation_status', true) ?: 'published',
                ];
            },
        ]);

        register_graphql_field($graphql_type, 'gvspaceSeo', [
            'type' => 'GvspaceSeo',
            'args' => [
                'locale' => [
                    'type' => 'String',
                    'description' => 'Requested language for a legacy bilingual record (uk or en).',
                ],
            ],
            'resolve' => static function ($source, array $args): array {
                $post_id = (int) ($source->databaseId ?? $source->ID ?? 0);
                $post = get_post($post_id);
                if (!$post) return [];

                $content_locale = (string) get_post_meta($post_id, '_gvspace_content_locale', true) ?: 'legacy';
                $requested_locale = isset($args['locale']) ? sanitize_key((string) $args['locale']) : 'uk';
                $suffix = $content_locale === 'legacy' ? '_' . ($requested_locale === 'en' ? 'en' : 'uk') : '';
                $value = static fn (string $key): string => trim((string) get_post_meta($post_id, '_gvspace_seo_' . $key . $suffix, true));

                $fallback_description = trim((string) $post->post_excerpt);
                if ($fallback_description === '') {
                    $fallback_description = wp_trim_words(wp_strip_all_tags(strip_shortcodes((string) $post->post_content)), 30, '…');
                }
                $title = $value('title') ?: get_the_title($post_id);
                $description = $value('description') ?: $fallback_description;
                $featured_image = get_post_thumbnail_id($post_id);
                $featured_image_url = $featured_image ? (string) wp_get_attachment_image_url($featured_image, 'full') : '';

                return [
                    'title' => $title,
                    'description' => $description,
                    'h1' => $value('h1') ?: get_the_title($post_id),
                    'openGraphTitle' => $value('og_title') ?: $title,
                    'openGraphDescription' => $value('og_description') ?: $description,
                    'openGraphImage' => $value('og_image') ?: $featured_image_url,
                    'datePublished' => get_post_time(DATE_W3C, true, $post_id),
                    'dateModified' => get_post_modified_time(DATE_W3C, true, $post_id),
                ];
            },
        ]);
    }

    register_graphql_field('Technology', 'technologyTitleEn', [
        'type' => 'String',
        'description' => 'English technology name.',
        'resolve' => static function ($source): string {
            return (string) get_post_meta((int) $source->databaseId, '_gvspace_technology_title_en', true);
        },
    ]);

    register_graphql_object_type('GvspaceTeamMemberDetails', [
        'description' => 'Editable fields displayed on a GVSPACE team card.',
        'fields' => [
            'role' => ['type' => 'String'],
            'tags' => ['type' => ['list_of' => 'String']],
        ],
    ]);
    register_graphql_field('TeamMember', 'teamMemberDetails', [
        'type' => 'GvspaceTeamMemberDetails',
        'resolve' => static function ($source): array {
            $post_id = (int) $source->databaseId;
            $role = (string) get_post_meta($post_id, '_gvspace_team_member_role', true);
            $tags = (string) get_post_meta($post_id, '_gvspace_team_member_tags', true);
            return [
                'role' => $role,
                'tags' => array_values(array_filter(array_map('trim', preg_split('/\R/', $tags) ?: []))),
            ];
        },
    ]);

    register_graphql_object_type('VacancyDetails', [
        'description' => 'Editable GVSPACE vacancy fields.',
        'fields' => [
            'excerpt' => ['type' => 'String'],
            'role' => ['type' => ['list_of' => 'String']],
            'tasks' => ['type' => ['list_of' => 'String']],
            'requirements' => ['type' => ['list_of' => 'String']],
            'benefits' => ['type' => ['list_of' => 'String']],
            'titleEn' => ['type' => 'String'],
            'excerptUk' => ['type' => 'String'],
            'excerptEn' => ['type' => 'String'],
            'salary' => ['type' => 'String'],
            'hot' => ['type' => 'Boolean'],
            'tags' => ['type' => ['list_of' => 'String']],
            'roleUk' => ['type' => ['list_of' => 'String']],
            'roleEn' => ['type' => ['list_of' => 'String']],
            'tasksUk' => ['type' => ['list_of' => 'String']],
            'tasksEn' => ['type' => ['list_of' => 'String']],
            'requirementsUk' => ['type' => ['list_of' => 'String']],
            'requirementsEn' => ['type' => ['list_of' => 'String']],
            'tools' => ['type' => ['list_of' => 'String']],
            'benefitsUk' => ['type' => ['list_of' => 'String']],
            'benefitsEn' => ['type' => ['list_of' => 'String']],
        ],
    ]);

    register_graphql_field('Vacancy', 'vacancyDetails', [
        'type' => 'VacancyDetails',
        'resolve' => static function ($source): array {
            $post_id = (int) $source->databaseId;
            $value = static fn (string $key): string => (string) get_post_meta($post_id, '_gvspace_' . $key, true);
            $localized_value = static fn (string $key): string => (string) get_post_meta($post_id, '_gvspace_vacancy_localized_' . $key, true);
            $lines = static function (string $key) use ($value): array {
                return array_values(array_filter(array_map('trim', preg_split('/\R/', $value($key)) ?: [])));
            };
            $localized_lines = static function (string $key) use ($localized_value): array {
                return array_values(array_filter(array_map('trim', preg_split('/\R/', $localized_value($key)) ?: [])));
            };
            $locale = (string) get_post_meta($post_id, '_gvspace_content_locale', true) ?: 'legacy';

            return [
                'excerpt' => $localized_value('excerpt'),
                'role' => $localized_lines('role'),
                'tasks' => $localized_lines('tasks'),
                'requirements' => $localized_lines('requirements'),
                'benefits' => $localized_lines('benefits'),
                'titleEn' => $value('title_en'),
                'excerptUk' => $value('excerpt_uk'),
                'excerptEn' => $value('excerpt_en'),
                'salary' => $locale === 'legacy' ? $value('salary') : $localized_value('salary'),
                'hot' => (bool) get_post_meta($post_id, '_gvspace_hot', true),
                'tags' => $locale === 'legacy' ? $lines('tags') : $localized_lines('tags'),
                'roleUk' => $lines('role_uk'),
                'roleEn' => $lines('role_en'),
                'tasksUk' => $lines('tasks_uk'),
                'tasksEn' => $lines('tasks_en'),
                'requirementsUk' => $lines('requirements_uk'),
                'requirementsEn' => $lines('requirements_en'),
                'tools' => $locale === 'legacy' ? $lines('tools') : $localized_lines('tools'),
                'benefitsUk' => $lines('benefits_uk'),
                'benefitsEn' => $lines('benefits_en'),
            ];
        },
    ]);

    register_graphql_object_type('GvspaceAuthorProfile', [
        'description' => 'Editable public profile fields for a GVSPACE blog author.',
        'fields' => [
            'role' => ['type' => 'String'],
            'headline' => ['type' => 'String'],
            'experience' => ['type' => 'String'],
            'projects' => ['type' => 'String'],
        ],
    ]);

    register_graphql_field('User', 'gvspaceAuthorProfile', [
        'type' => 'GvspaceAuthorProfile',
        'resolve' => static function ($source): array {
            $user_id = (int) $source->databaseId;
            $value = static fn (string $key): string => (string) get_user_meta($user_id, '_gvspace_author_' . $key, true);
            return [
                'role' => $value('role'),
                'headline' => $value('headline'),
                'experience' => $value('experience'),
                'projects' => $value('projects'),
            ];
        },
    ]);

    register_graphql_object_type('GvspaceCaseMetric', ['fields' => ['value' => ['type' => 'String'], 'label' => ['type' => 'String']]]);
    register_graphql_object_type('GvspaceCaseVector', ['fields' => ['title' => ['type' => 'String'], 'description' => ['type' => 'String']]]);
    register_graphql_object_type('GvspaceCaseDetails', [
        'fields' => [
            'result' => ['type' => 'String'],
            'services' => ['type' => ['list_of' => 'String']],
            'metrics' => ['type' => ['list_of' => 'GvspaceCaseMetric']],
            'challenge' => ['type' => 'String'],
            'problems' => ['type' => ['list_of' => 'String']],
            'discovery' => ['type' => 'String'],
            'discoveryResult' => ['type' => 'String'],
            'architecture' => ['type' => ['list_of' => 'GvspaceCaseVector']],
            'gallery' => ['type' => ['list_of' => 'String']],
            'testimonial' => ['type' => 'String'],
            'testimonialAuthor' => ['type' => 'String'],
            'projectType' => ['type' => 'String'],
            'industry' => ['type' => 'String'],
            'badge' => ['type' => 'String'],
        ],
    ]);
    register_graphql_field('ProjectCase', 'caseDetails', [
        'type' => 'GvspaceCaseDetails',
        'resolve' => static function ($source): array {
            $post_id = (int) $source->databaseId;
            $value = static fn (string $key): string => (string) get_post_meta($post_id, '_gvspace_case_' . $key, true);
            $lines = static fn (string $key): array => array_values(array_filter(array_map('trim', preg_split('/\R/', $value($key)) ?: [])));
            $pairs = static function (string $key, string $first, string $second) use ($lines): array {
                return array_map(static function (string $line) use ($first, $second): array {
                    $parts = array_map('trim', explode('|', $line, 2));
                    return [$first => $parts[0] ?? '', $second => $parts[1] ?? ''];
                }, $lines($key));
            };
            return [
                'result' => $value('result'), 'services' => $lines('services'),
                'metrics' => $pairs('metrics', 'value', 'label'), 'challenge' => $value('challenge'),
                'problems' => $lines('problems'), 'discovery' => $value('discovery'),
                'discoveryResult' => $value('discovery_result'),
                'architecture' => $pairs('architecture', 'title', 'description'),
                'gallery' => $lines('gallery'), 'testimonial' => $value('testimonial'),
                'testimonialAuthor' => $value('testimonial_author'),
                'projectType' => $value('project_type'), 'industry' => $value('industry'), 'badge' => $value('badge'),
            ];
        },
    ]);

    register_graphql_object_type('GvspaceReviewDetails', [
        'fields' => [
            'position' => ['type' => 'String'], 'text' => ['type' => 'String'],
            'nameEn' => ['type' => 'String'], 'positionUk' => ['type' => 'String'],
            'positionEn' => ['type' => 'String'], 'company' => ['type' => 'String'],
            'textUk' => ['type' => 'String'], 'textEn' => ['type' => 'String'],
            'category' => ['type' => 'String'], 'rating' => ['type' => 'Int'],
            'metrics' => ['type' => ['list_of' => 'String']],
        ],
    ]);
    register_graphql_object_type('GvspaceServiceStep', ['fields' => ['title' => ['type' => 'String'], 'duration' => ['type' => 'String'], 'description' => ['type' => 'String']]]);
    register_graphql_object_type('GvspaceServiceFaq', ['fields' => ['question' => ['type' => 'String'], 'answer' => ['type' => 'String']]]);
    register_graphql_object_type('GvspaceServiceDetails', ['fields' => [
        'headline' => ['type' => 'String'], 'description' => ['type' => 'String'],
        'includes' => ['type' => ['list_of' => 'String']], 'steps' => ['type' => ['list_of' => 'GvspaceServiceStep']],
        'faq' => ['type' => ['list_of' => 'GvspaceServiceFaq']],
        'titleEn' => ['type' => 'String'], 'headlineUk' => ['type' => 'String'], 'headlineEn' => ['type' => 'String'],
        'descriptionUk' => ['type' => 'String'], 'descriptionEn' => ['type' => 'String'],
        'includesUk' => ['type' => ['list_of' => 'String']], 'includesEn' => ['type' => ['list_of' => 'String']],
        'stepsUk' => ['type' => ['list_of' => 'GvspaceServiceStep']], 'stepsEn' => ['type' => ['list_of' => 'GvspaceServiceStep']],
        'metrics' => ['type' => ['list_of' => 'String']], 'faqUk' => ['type' => ['list_of' => 'GvspaceServiceFaq']], 'faqEn' => ['type' => ['list_of' => 'GvspaceServiceFaq']],
    ]]);
    register_graphql_field('ServiceOffering', 'serviceDetails', ['type' => 'GvspaceServiceDetails', 'resolve' => static function ($source): array {
        $id = (int) $source->databaseId;
        $value = static fn (string $key): string => (string) get_post_meta($id, '_gvspace_service_' . $key, true);
        $localizedValue = static fn (string $key): string => (string) get_post_meta($id, '_gvspace_service_localized_' . $key, true);
        $lines = static fn (string $key): array => array_values(array_filter(array_map('trim', preg_split('/\R/', $value($key)) ?: [])));
        $localizedLines = static fn (string $key): array => array_values(array_filter(array_map('trim', preg_split('/\R/', $localizedValue($key)) ?: [])));
        $steps = static fn (string $key): array => array_map(static function ($line): array { $p = array_map('trim', explode('|', $line, 3)); return ['title' => $p[0] ?? '', 'duration' => $p[1] ?? '', 'description' => $p[2] ?? '']; }, $lines($key));
        $localizedSteps = static fn (): array => array_map(static function ($line): array { $p = array_map('trim', explode('|', $line, 3)); return ['title' => $p[0] ?? '', 'duration' => $p[1] ?? '', 'description' => $p[2] ?? '']; }, $localizedLines('steps'));
        $faq = static fn (string $key): array => array_map(static function ($line): array { $p = array_map('trim', explode('|', $line, 2)); return ['question' => $p[0] ?? '', 'answer' => $p[1] ?? '']; }, $lines($key));
        $localizedFaq = static fn (): array => array_map(static function ($line): array { $p = array_map('trim', explode('|', $line, 2)); return ['question' => $p[0] ?? '', 'answer' => $p[1] ?? '']; }, $localizedLines('faq'));
        $locale = (string) get_post_meta($id, '_gvspace_content_locale', true) ?: 'legacy';
        return ['headline' => $localizedValue('headline'), 'description' => $localizedValue('description'), 'includes' => $localizedLines('includes'), 'steps' => $localizedSteps(), 'faq' => $localizedFaq(), 'titleEn' => $value('title_en'), 'headlineUk' => $value('headline_uk'), 'headlineEn' => $value('headline_en'), 'descriptionUk' => $value('description_uk'), 'descriptionEn' => $value('description_en'), 'includesUk' => $lines('includes_uk'), 'includesEn' => $lines('includes_en'), 'stepsUk' => $steps('steps_uk'), 'stepsEn' => $steps('steps_en'), 'metrics' => $locale === 'legacy' ? $lines('metrics') : $localizedLines('metrics'), 'faqUk' => $faq('faq_uk'), 'faqEn' => $faq('faq_en')];
    }]);
    register_graphql_field('ClientReview', 'reviewDetails', [
        'type' => 'GvspaceReviewDetails',
        'resolve' => static function ($source): array {
            $post_id = (int) $source->databaseId;
            $value = static fn (string $key): string => (string) get_post_meta($post_id, '_gvspace_review_' . $key, true);
            $localizedValue = static fn (string $key): string => (string) get_post_meta($post_id, '_gvspace_review_localized_' . $key, true);
            $locale = (string) get_post_meta($post_id, '_gvspace_content_locale', true) ?: 'legacy';
            return [
                'position' => $localizedValue('position'), 'text' => $localizedValue('text'),
                'nameEn' => $value('name_en'), 'positionUk' => $value('position_uk'),
                'positionEn' => $value('position_en'),
                'textUk' => $value('text_uk'), 'textEn' => $value('text_en'),
                'company' => $locale === 'legacy' ? $value('company') : $localizedValue('company'),
                'category' => $locale === 'legacy' ? $value('category') : $localizedValue('category'),
                'rating' => max(1, min(5, (int) ($locale === 'legacy' ? $value('rating') : $localizedValue('rating')))),
                'metrics' => array_values(array_filter(array_map('trim', preg_split('/\R/', $locale === 'legacy' ? $value('metrics') : $localizedValue('metrics')) ?: []))),
            ];
        },
    ]);

});

register_activation_hook(__FILE__, function (): void {
    do_action('init');
    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, 'flush_rewrite_rules');

function gvspace_seed_team_tabs(): void
{
    $tabs = [
        'core-team' => 'CORE TEAM',
        'strategy' => 'STRATEGY',
        'marketing' => 'MARKETING',
        'development' => 'DEVELOPMENT',
        'content' => 'CONTENT',
    ];
    foreach ($tabs as $slug => $name) {
        if (!term_exists($slug, 'gv_team_member_category')) {
            wp_insert_term($name, 'gv_team_member_category', ['slug' => $slug]);
        }
    }
}
add_action('admin_init', 'gvspace_seed_team_tabs');

add_filter('manage_gv_team_member_posts_columns', function (array $columns): array {
    $columns['menu_order'] = 'Порядок';
    return $columns;
});

add_action('manage_gv_team_member_posts_custom_column', function (string $column, int $post_id): void {
    if ($column === 'menu_order') echo esc_html((string) get_post_field('menu_order', $post_id));
}, 10, 2);

add_filter('manage_edit-gv_team_member_sortable_columns', function (array $columns): array {
    $columns['menu_order'] = 'menu_order';
    return $columns;
});

add_action('restrict_manage_posts', function (string $post_type): void {
    if ($post_type !== 'gv_team_member') return;
    $selected = isset($_GET['gv_team_member_category'])
        ? sanitize_key(wp_unslash($_GET['gv_team_member_category']))
        : '';
    wp_dropdown_categories([
        'show_option_all' => 'Усі таби команди',
        'taxonomy' => 'gv_team_member_category',
        'name' => 'gv_team_member_category',
        'orderby' => 'name',
        'selected' => $selected,
        'hierarchical' => false,
        'hide_empty' => false,
        'value_field' => 'slug',
    ]);
});

add_action('pre_get_posts', function (WP_Query $query): void {
    if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'gv_team_member') return;
    if (!$query->get('orderby')) {
        $query->set('orderby', ['menu_order' => 'ASC', 'date' => 'DESC']);
    }
});

function gvspace_seed_services(): void
{
    if (get_option('gvspace_services_seeded_v1')) return;
    $directions = [
        'strategy' => ['Стратегія', 'Strategy', [
            ['strategic-audit', 'Стратегічний аудит', 'Strategic audit'],
            ['digital-audit', 'Комплексний Digital-аудит', 'Comprehensive Digital audit'],
            ['market-analysis', 'Аналіз ринку та конкурентне позиціонування', 'Market analysis and competitive positioning'],
            ['clarity-session', 'Clarity Session', 'Clarity Session'],
            ['growth-roadmap', 'Архітектура зростання (Roadmap)', 'Growth architecture (Roadmap)'],
            ['marketing-process-audit', 'Аудит маркетингових процесів', 'Marketing process audit'],
        ]],
        'marketing' => ['Маркетинг', 'Marketing', [
            ['performance-marketing', 'Performance Marketing (Meta & Google Ads)', 'Performance Marketing (Meta & Google Ads)'],
            ['analytics-dashboards', 'Побудова системної аналітики & Dashboards', 'Analytics systems & Dashboards'],
            ['smm-strategy', 'SMM Стратегія та присутність', 'SMM strategy and presence'],
            ['seo', 'SEO-просування', 'SEO promotion'],
            ['retention-crm', 'Retention & CRM Маркетинг', 'Retention & CRM Marketing'],
        ]],
        'development' => ['IT-розробка', 'IT Development', [
            ['corporate-websites', 'Розробка корпоративних сайтів та лендингів', 'Corporate websites and landing pages'],
            ['business-systems', 'Розробка складних систем (CRM, ERP, Dashboards)', 'Complex systems (CRM, ERP, Dashboards)'],
            ['technical-support', 'Технічна підтримка та інфраструктура', 'Technical support and infrastructure'],
            ['ecommerce', 'E-commerce рішення (Інтернет-магазини)', 'E-commerce solutions'],
            ['product-discovery', 'Product Discovery & Архітектура', 'Product Discovery & Architecture'],
        ]],
        'content' => ['Контент & Продакшн', 'Content & Production', [
            ['brand-design', 'Бренд-дизайн та Візуальна айдентика', 'Brand design and visual identity'],
            ['photo-production', 'Фото-продакшн (Food, Product, Lifestyle)', 'Photo production (Food, Product, Lifestyle)'],
            ['creative-concepts', 'Креативні концепції та спецпроєкти', 'Creative concepts and special projects'],
            ['video-production', 'Video Production (Рекламні та іміджеві ролики)', 'Video Production'],
            ['copywriting', 'Копірайтинг & Storytelling', 'Copywriting & Storytelling'],
        ]],
    ];
    foreach ($directions as $slug => [$title, $title_en, $children]) {
        $parent = get_page_by_path($slug, OBJECT, 'gv_service');
        $parent_id = $parent ? (int) $parent->ID : (int) wp_insert_post(['post_type' => 'gv_service', 'post_status' => 'publish', 'post_title' => $title, 'post_name' => $slug]);
        if (!$parent_id) continue;
        update_post_meta($parent_id, '_gvspace_service_title_en', $title_en);
        foreach ($children as $order => [$child_slug, $child_title, $child_title_en]) {
            $existing = get_page_by_path($slug . '/' . $child_slug, OBJECT, 'gv_service');
            $child_id = $existing ? (int) $existing->ID : (int) wp_insert_post(['post_type' => 'gv_service', 'post_status' => 'publish', 'post_title' => $child_title, 'post_name' => $child_slug, 'post_parent' => $parent_id, 'menu_order' => $order]);
            if ($child_id) update_post_meta($child_id, '_gvspace_service_title_en', $child_title_en);
        }
    }
    update_option('gvspace_services_seeded_v1', '1', false);
    flush_rewrite_rules(false);
}
add_action('admin_init', 'gvspace_seed_services');

// GVSPACE does not use the native WordPress comments interface.
add_action('admin_menu', function (): void {
    remove_menu_page('edit-comments.php');
});

add_action('admin_bar_menu', function (WP_Admin_Bar $admin_bar): void {
    $admin_bar->remove_node('comments');
}, 999);

add_action('init', function (): void {
    foreach (get_post_types([], 'names') as $post_type) {
        remove_post_type_support($post_type, 'comments');
        remove_post_type_support($post_type, 'trackbacks');
    }
}, 100);

add_filter('comments_open', '__return_false', 100);
add_filter('pings_open', '__return_false', 100);

const GVSPACE_DUPLICABLE_POST_TYPES = ['post', 'gv_case', 'gv_service', 'gv_review', 'gv_vacancy', 'gv_technology', 'gv_team_member'];

function gvspace_prepare_localized_duplicate(int $source_id, int $duplicate_id, string $target_locale): void
{
    $source_locale = (string) get_post_meta($source_id, '_gvspace_content_locale', true) ?: 'legacy';
    if ($source_locale !== 'legacy') return;

    $language_suffix = $target_locale === 'uk' ? 'uk' : 'en';
    $post_type = get_post_type($source_id);
    $maps = [
        'gv_vacancy' => [
            'prefix' => '_gvspace_vacancy_localized_',
            'fields' => [
                'excerpt' => 'excerpt_' . $language_suffix,
                'salary' => 'salary', 'tags' => 'tags', 'role' => 'role_' . $language_suffix,
                'tasks' => 'tasks_' . $language_suffix, 'requirements' => 'requirements_' . $language_suffix,
                'tools' => 'tools', 'benefits' => 'benefits_' . $language_suffix,
            ],
            'source_prefix' => '_gvspace_',
            'title_meta' => '_gvspace_title_en',
        ],
        'gv_review' => [
            'prefix' => '_gvspace_review_localized_',
            'fields' => [
                'position' => 'position_' . $language_suffix, 'company' => 'company',
                'text' => 'text_' . $language_suffix, 'category' => 'category',
                'rating' => 'rating', 'metrics' => 'metrics',
            ],
            'source_prefix' => '_gvspace_review_',
            'title_meta' => '_gvspace_review_name_en',
        ],
        'gv_service' => [
            'prefix' => '_gvspace_service_localized_',
            'fields' => [
                'headline' => 'headline_' . $language_suffix, 'description' => 'description_' . $language_suffix,
                'includes' => 'includes_' . $language_suffix, 'steps' => 'steps_' . $language_suffix,
                'metrics' => 'metrics', 'faq' => 'faq_' . $language_suffix,
            ],
            'source_prefix' => '_gvspace_service_',
            'title_meta' => '_gvspace_service_title_en',
        ],
    ];

    if (isset($maps[$post_type])) {
        $map = $maps[$post_type];
        foreach ($map['fields'] as $localized_field => $legacy_field) {
            update_post_meta(
                $duplicate_id,
                $map['prefix'] . $localized_field,
                (string) get_post_meta($source_id, $map['source_prefix'] . $legacy_field, true)
            );
        }
        if ($language_suffix === 'en') {
            $translated_title = (string) get_post_meta($source_id, $map['title_meta'], true);
            if ($translated_title !== '') wp_update_post(['ID' => $duplicate_id, 'post_title' => $translated_title]);
        }
    }

    if ($post_type === 'gv_technology' && $language_suffix === 'en') {
        $translated_title = (string) get_post_meta($source_id, '_gvspace_technology_title_en', true);
        if ($translated_title !== '') wp_update_post(['ID' => $duplicate_id, 'post_title' => $translated_title]);
    }
}

function gvspace_duplicate_post_link(array $actions, WP_Post $post): array
{
    if (!in_array($post->post_type, GVSPACE_DUPLICABLE_POST_TYPES, true) || !current_user_can('edit_post', $post->ID)) {
        return $actions;
    }

    $url = wp_nonce_url(
        admin_url('admin-post.php?action=gvspace_duplicate_post&post=' . $post->ID),
        'gvspace_duplicate_post_' . $post->ID
    );
    $actions['gvspace_duplicate'] = '<a href="' . esc_url($url) . '">Дублювати</a>';
    return $actions;
}

add_filter('post_row_actions', 'gvspace_duplicate_post_link', 10, 2);
add_filter('page_row_actions', 'gvspace_duplicate_post_link', 10, 2);

add_action('admin_post_gvspace_duplicate_post', function (): void {
    $post_id = isset($_GET['post']) ? absint($_GET['post']) : 0;
    $post = $post_id ? get_post($post_id) : null;
    $target_locale = isset($_GET['target_locale'])
        ? gvspace_sanitize_content_locale(sanitize_text_field(wp_unslash($_GET['target_locale'])))
        : '';

    if (
        !$post
        || !in_array($post->post_type, GVSPACE_DUPLICABLE_POST_TYPES, true)
        || !current_user_can('edit_post', $post_id)
        || !isset($_GET['_wpnonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'gvspace_duplicate_post_' . $post_id)
    ) {
        wp_die('Недостатньо прав або некоректний запит.', 'Не вдалося дублювати запис', ['response' => 403]);
    }

    $duplicate_id = wp_insert_post([
        'post_type' => $post->post_type,
        'post_status' => 'draft',
        'post_title' => $post->post_title . ' — копія',
        'post_content' => $post->post_content,
        'post_excerpt' => $post->post_excerpt,
        'post_author' => get_current_user_id(),
        'post_parent' => $post->post_parent,
        'menu_order' => $post->menu_order,
        'comment_status' => 'closed',
        'ping_status' => 'closed',
    ], true);

    if (is_wp_error($duplicate_id)) {
        wp_die(esc_html($duplicate_id->get_error_message()), 'Не вдалося дублювати запис');
    }

    foreach (get_object_taxonomies($post->post_type) as $taxonomy) {
        $term_ids = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'ids']);
        if (!is_wp_error($term_ids)) {
            wp_set_object_terms($duplicate_id, $term_ids, $taxonomy);
        }
    }

    foreach (get_post_meta($post_id) as $meta_key => $values) {
        if (in_array($meta_key, ['_edit_lock', '_edit_last', '_wp_old_slug'], true)) continue;
        foreach ($values as $value) {
            add_post_meta($duplicate_id, $meta_key, maybe_unserialize($value));
        }
    }

    if ($target_locale && $target_locale !== 'legacy') {
        $translation_group = (string) get_post_meta($post_id, '_gvspace_translation_group', true);
        if (!$translation_group) {
            $translation_group = sanitize_title($post->post_name ?: $post->post_title);
            if (!$translation_group) $translation_group = sanitize_key($post->post_type . '-' . $post_id);
            update_post_meta($post_id, '_gvspace_translation_group', $translation_group);
        }

        update_post_meta($duplicate_id, '_gvspace_content_locale', $target_locale);
        update_post_meta($duplicate_id, '_gvspace_translation_group', $translation_group);
        update_post_meta($duplicate_id, '_gvspace_translation_status', 'draft');
        gvspace_prepare_localized_duplicate($post_id, $duplicate_id, $target_locale);
    }

    wp_safe_redirect(admin_url('post.php?action=edit&post=' . $duplicate_id));
    exit;
});

// Present native WordPress posts as the GVSPACE blog in the admin interface.
add_action('init', function (): void {
    $post_type = get_post_type_object('post');
    if (!$post_type) return;

    $labels = $post_type->labels;
    $labels->name = 'Блог';
    $labels->singular_name = 'Стаття';
    $labels->menu_name = 'Блог';
    $labels->name_admin_bar = 'Статтю';
    $labels->add_new = 'Додати статтю';
    $labels->add_new_item = 'Додати статтю';
    $labels->edit_item = 'Редагувати статтю';
    $labels->new_item = 'Нова стаття';
    $labels->view_item = 'Переглянути статтю';
    $labels->search_items = 'Шукати статті';
    $labels->not_found = 'Статей не знайдено';
    $labels->not_found_in_trash = 'У кошику статей немає';
    $labels->all_items = 'Усі статті';
}, 20);

add_action('admin_menu', function (): void {
    global $menu;
    foreach ($menu as &$item) {
        if (($item[2] ?? '') === 'edit.php') {
            $item[0] = 'Блог';
            $item[6] = 'dashicons-welcome-write-blog';
            break;
        }
    }
    unset($item);
}, 20);
