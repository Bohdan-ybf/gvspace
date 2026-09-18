<?php
/**
 * Plugin Name: GVSPACE Core
 * Description: Content types and GraphQL fields used by the GVSPACE frontend.
 * Version: 1.0.0
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

const GVSPACE_LOCALIZED_TEAM_MEMBER_FIELDS = [
    'role' => ['label' => 'Посада / роль', 'type' => 'text'],
    'tags' => ['label' => 'Компетенції (кожна з нового рядка)', 'type' => 'textarea'],
];

const GVSPACE_PARTNER_FIELDS = [
    'direction_uk' => ['label' => 'Напрямок українською (наприклад, IT-РОЗРОБКА)', 'type' => 'text'],
    'direction_en' => ['label' => 'Напрямок англійською (наприклад, IT DEVELOPMENT)', 'type' => 'text'],
];

const GVSPACE_FAQ_FIELDS = [
    'placement' => ['label' => 'Розміщення', 'type' => 'text'],
];

// New localized records contain exactly one language. Legacy field sets above
// remain available while existing UK + EN records are being migrated.
const GVSPACE_LOCALIZED_VACANCY_FIELDS = [
    'excerpt' => ['label' => 'Короткий опис', 'type' => 'textarea'],
    'salary' => ['label' => 'Зарплата', 'type' => 'text'],
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
    'headline' => ['label' => 'Заголовок першого екрана (H1)', 'type' => 'text'],
    'description' => ['label' => 'Опис під заголовком', 'type' => 'textarea'],
    'fit_cards' => ['label' => 'Кому і коли підходить — етап | заголовок | опис', 'type' => 'textarea'],
    'includes' => ['label' => 'Що входить у послугу — один пункт у рядку', 'type' => 'textarea'],
    'steps' => ['label' => 'Етапи роботи — назва | термін | опис', 'type' => 'textarea'],
    'metrics' => ['label' => 'Результати / показники — один пункт у рядку', 'type' => 'textarea'],
    'faq' => ['label' => 'Питання та відповіді на сторінці — питання | відповідь', 'type' => 'textarea'],
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
    'gallery' => ['label' => 'Галерея кейсу', 'type' => 'textarea'],
    'testimonial' => ['label' => 'Текст відгуку', 'type' => 'textarea'],
    'testimonial_author' => ['label' => 'Автор відгуку', 'type' => 'text'],
    'project_type' => ['label' => 'Тип проєкту (ecommerce, strategy, development, marketing)', 'type' => 'text'],
    'industry' => ['label' => 'Індустрія (retail, services, technology)', 'type' => 'text'],
    'badge' => ['label' => 'Бейдж результату для картки', 'type' => 'text'],
];

const GVSPACE_LOCALIZED_CASE_FIELDS = [
    'catalog_title' => ['label' => 'Назва на картці в каталозі', 'type' => 'text'],
    'excerpt' => ['label' => 'Короткий опис під заголовком на сторінці кейсу', 'type' => 'textarea'],
    'metrics' => ['label' => 'Метрики: значення | назва (кожна з нового рядка)', 'type' => 'textarea'],
    'problems' => ['label' => 'Проблеми клієнта (кожна з нового рядка)', 'type' => 'textarea'],
    'step1' => ['label' => 'Крок 1: Пошук можливостей', 'type' => 'textarea'],
    'step1_result' => ['label' => 'Результат кроку 1: заголовок | опис', 'type' => 'textarea'],
    'step2' => ['label' => 'Крок 2: Побудова архітектури зростання', 'type' => 'textarea'],
    'architecture' => ['label' => 'Картки послуг кроку 2: назва | опис', 'type' => 'textarea'],
    'step3' => ['label' => 'Крок 3: Масштабування та контроль', 'type' => 'textarea'],
    'step3_result' => ['label' => 'Результат кроку 3: заголовок | опис', 'type' => 'textarea'],
    'tasks' => ['label' => 'Хід робіт — задачі (кожна з нового рядка)', 'type' => 'textarea'],
    'documents' => ['label' => 'Хід робіт — документація (кожна з нового рядка)', 'type' => 'textarea'],
    'team' => ['label' => 'Команда кейсу: ім’я | роль | URL фото', 'type' => 'textarea'],
    'testimonial' => ['label' => 'Відгук після впровадження', 'type' => 'textarea'],
    'testimonial_author' => ['label' => 'Автор відгуку', 'type' => 'text'],
    'testimonial_company' => ['label' => 'Компанія автора відгуку', 'type' => 'text'],
];

const GVSPACE_LOCALIZED_POST_TYPES = [
    'post',
    'gv_vacancy',
    'gv_case',
    'gv_service',
    'gv_review',
    'gv_technology',
    'gv_team_member',
    'gv_faq',
    'gv_home_seo_text',
    'gv_privacy_policy',
    'gv_terms_of_use',
    'gv_contacts_page',
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
const GVSPACE_LOCALIZED_TECHNOLOGY_CARD_FIELDS = [
    'description' => ['label' => 'Короткий опис на картці каталогу', 'type' => 'textarea'],
    'tag' => ['label' => 'Мітка на картці (наприклад, ПЛАТНА РЕКЛАМА)', 'type' => 'text'],
];
const GVSPACE_LOCALIZED_TECHNOLOGY_PAGE_FIELDS = [
    'intro' => ['label' => 'Текст під назвою на білому банері', 'type' => 'textarea'],
    'why' => ['label' => 'Чому ми обираємо це — текст фіолетового банера. Фразу можна виділити так: **текст**', 'type' => 'textarea'],
    'triggers' => ['label' => 'Коли обираємо цю технологію — заголовок | опис (один рядок = одна картка)', 'type' => 'textarea'],
    'uses' => ['label' => 'Як ми застосовуємо — заголовок | опис (один рядок = один пункт)', 'type' => 'textarea'],
    'faq' => ['label' => 'FAQ на сторінці — питання | відповідь', 'type' => 'textarea'],
    'seo_lead' => ['label' => 'SEO-текст — виділений перший рядок', 'type' => 'text'],
    'seo_text' => ['label' => 'SEO-текст', 'type' => 'textarea'],
    'meet_name' => ['label' => 'Блок зустрічі — ім’я експерта', 'type' => 'text'],
    'meet_role' => ['label' => 'Блок зустрічі — посада', 'type' => 'text'],
    'meet_quote' => ['label' => 'Блок зустрічі — цитата', 'type' => 'textarea'],
    'meet_years' => ['label' => 'Блок зустрічі — років у компанії (наприклад, 6)', 'type' => 'text'],
    'meet_projects' => ['label' => 'Блок зустрічі — проєктів реалізовано (наприклад, 150)', 'type' => 'text'],
    'meet_tags' => ['label' => 'Блок зустрічі — компетенції (кожна з нового рядка)', 'type' => 'textarea'],
];

function gvspace_localized_technology_fields(): array
{
    return GVSPACE_LOCALIZED_TECHNOLOGY_CARD_FIELDS + GVSPACE_LOCALIZED_TECHNOLOGY_PAGE_FIELDS;
}

const GVSPACE_CENTRALIZED_POST_TYPES = ['gv_service', 'gv_team_member', 'gv_vacancy', 'gv_case', 'gv_faq', 'gv_home_seo_text', 'gv_privacy_policy', 'gv_terms_of_use', 'gv_contacts_page', 'gv_technology'];

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
    if (current_user_can('manage_options') || !empty($GLOBALS['gvspace_allow_svg_seed'])) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
});

add_filter('wp_check_filetype_and_ext', function (array $data, string $file, string $filename, ?array $mimes): array {
    $allow_svg = current_user_can('manage_options') || !empty($GLOBALS['gvspace_allow_svg_seed']);
    if (!$allow_svg || strtolower((string) pathinfo($filename, PATHINFO_EXTENSION)) !== 'svg') {
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
    register_taxonomy('gv_media_folder', ['attachment'], [
        'labels' => [
            'name' => 'Папки медіа', 'singular_name' => 'Папка медіа',
            'menu_name' => 'Папки', 'all_items' => 'Усі папки',
            'edit_item' => 'Редагувати папку', 'add_new_item' => 'Додати папку',
            'new_item_name' => 'Назва нової папки', 'search_items' => 'Шукати папки',
        ],
        'public' => false,
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => false,
    ]);

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
        'taxonomies' => ['gv_vacancy_direction', 'gv_vacancy_employment'],
    ]);

    register_taxonomy('gv_vacancy_direction', ['gv_vacancy'], [
        'labels' => [
            'name' => 'Напрямки вакансій',
            'singular_name' => 'Напрямок вакансії',
            'menu_name' => 'Напрямки',
            'all_items' => 'Усі напрямки',
            'edit_item' => 'Редагувати напрямок',
            'add_new_item' => 'Додати напрямок',
            'search_items' => 'Шукати напрямки',
        ],
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_quick_edit' => false,
        'meta_box_cb' => false,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'vacancyDirection',
        'graphql_plural_name' => 'vacancyDirections',
        'rewrite' => false,
    ]);

    register_taxonomy('gv_vacancy_employment', ['gv_vacancy'], [
        'labels' => [
            'name' => 'Види зайнятості',
            'singular_name' => 'Вид зайнятості',
            'menu_name' => 'Зайнятість',
            'all_items' => 'Усі види зайнятості',
            'edit_item' => 'Редагувати вид зайнятості',
            'add_new_item' => 'Додати вид зайнятості',
            'search_items' => 'Шукати види зайнятості',
        ],
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_quick_edit' => false,
        'meta_box_cb' => false,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'vacancyEmployment',
        'graphql_plural_name' => 'vacancyEmployments',
        'rewrite' => false,
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
        'taxonomies' => ['gv_case_direction', 'gv_case_project_type'],
    ]);

    register_taxonomy('gv_case_direction', ['gv_case'], [
        'labels' => [
            'name' => 'Напрямки кейсів',
            'singular_name' => 'Напрямок кейсу',
            'menu_name' => 'Напрямки',
            'all_items' => 'Усі напрямки',
            'edit_item' => 'Редагувати напрямок',
            'add_new_item' => 'Додати напрямок',
            'search_items' => 'Шукати напрямки',
        ],
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_quick_edit' => false,
        'meta_box_cb' => false,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'caseDirection',
        'graphql_plural_name' => 'caseDirections',
        'rewrite' => false,
    ]);

    register_taxonomy('gv_case_project_type', ['gv_case'], [
        'labels' => [
            'name' => 'Типи проєктів',
            'singular_name' => 'Тип проєкту',
            'menu_name' => 'Типи проєктів',
            'all_items' => 'Усі типи',
            'edit_item' => 'Редагувати тип проєкту',
            'add_new_item' => 'Додати тип проєкту',
            'search_items' => 'Шукати типи проєктів',
        ],
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_quick_edit' => false,
        'meta_box_cb' => false,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'caseProjectType',
        'graphql_plural_name' => 'caseProjectTypes',
        'rewrite' => false,
    ]);

    register_post_type('gv_service', [
        'labels' => [
            'name' => 'Послуги', 'singular_name' => 'Послуга', 'menu_name' => 'Послуги',
            'name_admin_bar' => 'Послугу або напрямок', 'add_new' => 'Додати',
            'add_new_item' => 'Додати послугу або напрямок', 'edit_item' => 'Редагувати послугу',
            'new_item' => 'Нова послуга', 'all_items' => 'Структура послуг',
            'parent_item_colon' => 'Належить до напрямку:', 'not_found' => 'Послуг не знайдено',
            'featured_image' => '3D-іконка банера', 'set_featured_image' => 'Завантажити 3D-іконку',
            'remove_featured_image' => 'Видалити 3D-іконку', 'use_featured_image' => 'Використати як 3D-іконку',
        ],
        'public' => true, 'hierarchical' => true, 'show_in_rest' => true, 'show_in_graphql' => true,
        'graphql_single_name' => 'serviceOffering', 'graphql_plural_name' => 'serviceOfferings',
        'menu_icon' => 'dashicons-index-card', 'rewrite' => ['slug' => 'services'],
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
        'public' => true,
        'hierarchical' => true,
        'show_admin_column' => true,
        'show_in_menu' => true,
        'show_in_quick_edit' => false,
        'meta_box_cb' => 'gvspace_render_team_tab_metabox',
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

    register_post_type('gv_partner', [
        'labels' => [
            'name' => 'Партнери', 'singular_name' => 'Партнер', 'menu_name' => 'Партнери',
            'add_new_item' => 'Додати партнера', 'edit_item' => 'Редагувати партнера',
            'new_item' => 'Новий партнер', 'all_items' => 'Усі партнери',
            'not_found' => 'Партнерів не знайдено',
        ],
        'public' => true, 'publicly_queryable' => false, 'exclude_from_search' => true,
        'show_in_rest' => true, 'show_in_graphql' => true,
        'graphql_single_name' => 'partner', 'graphql_plural_name' => 'partners',
        'show_in_menu' => 'gvspace-home',
        'supports' => ['title', 'thumbnail', 'page-attributes'],
    ]);

    register_post_type('gv_faq', [
        'labels' => [
            'name' => 'Часті запитання', 'singular_name' => 'Запитання', 'menu_name' => 'FAQ',
            'add_new_item' => 'Додати запитання', 'edit_item' => 'Редагувати запитання',
            'new_item' => 'Нове запитання', 'all_items' => 'FAQ',
            'not_found' => 'Запитань не знайдено',
        ],
        'public' => true, 'publicly_queryable' => false, 'exclude_from_search' => true,
        'show_in_rest' => true, 'show_in_graphql' => true,
        'graphql_single_name' => 'faqItem', 'graphql_plural_name' => 'faqItems',
        'show_in_menu' => 'gvspace-home',
        'supports' => ['title', 'page-attributes'],
    ]);

    register_post_type('gv_home_seo_text', [
        'labels' => [
            'name' => 'SEO-тексти', 'singular_name' => 'SEO-текст', 'menu_name' => 'SEO-текст',
            'add_new_item' => 'Додати SEO-текст', 'edit_item' => 'Редагувати SEO-текст',
            'new_item' => 'Новий SEO-текст', 'all_items' => 'SEO-тексти',
            'not_found' => 'SEO-текстів не знайдено',
        ],
        'public' => true, 'publicly_queryable' => false, 'exclude_from_search' => true,
        'show_in_rest' => true, 'show_in_graphql' => true,
        'graphql_single_name' => 'homeSeoText', 'graphql_plural_name' => 'homeSeoTexts',
        'show_in_menu' => 'gvspace-home',
        'supports' => ['title'],
    ]);

    register_post_type('gv_privacy_policy', [
        'labels' => [
            'name' => 'Політика конфіденційності',
            'singular_name' => 'Політика конфіденційності',
            'menu_name' => 'Політика конфіденційності',
            'add_new_item' => 'Додати політику',
            'edit_item' => 'Редагувати політику',
            'new_item' => 'Нова політика',
            'all_items' => 'Політика конфіденційності',
            'not_found' => 'Політику не знайдено',
        ],
        'public' => true, 'publicly_queryable' => false, 'exclude_from_search' => true,
        'show_in_rest' => true, 'show_in_graphql' => true,
        'graphql_single_name' => 'privacyPolicy', 'graphql_plural_name' => 'privacyPolicies',
        'menu_icon' => 'dashicons-privacy',
        'supports' => ['title'],
    ]);

    register_post_type('gv_terms_of_use', [
        'labels' => [
            'name' => 'Правила використання',
            'singular_name' => 'Правила використання',
            'menu_name' => 'Правила використання',
            'add_new_item' => 'Додати правила',
            'edit_item' => 'Редагувати правила',
            'new_item' => 'Нові правила',
            'all_items' => 'Правила використання',
            'not_found' => 'Правила не знайдено',
        ],
        'public' => true, 'publicly_queryable' => false, 'exclude_from_search' => true,
        'show_in_rest' => true, 'show_in_graphql' => true,
        'graphql_single_name' => 'termsOfUse', 'graphql_plural_name' => 'termsOfUses',
        'menu_icon' => 'dashicons-media-text',
        'supports' => ['title'],
    ]);

    register_post_type('gv_contacts_page', [
        'labels' => [
            'name' => 'Контакти',
            'singular_name' => 'Контакти',
            'menu_name' => 'Контакти',
            'add_new_item' => 'Додати сторінку контактів',
            'edit_item' => 'Редагувати контакти',
            'new_item' => 'Нова сторінка контактів',
            'all_items' => 'Контакти',
            'not_found' => 'Сторінку контактів не знайдено',
            'featured_image' => 'Карта локації',
            'set_featured_image' => 'Завантажити карту',
            'remove_featured_image' => 'Видалити карту',
            'use_featured_image' => 'Використати як карту',
        ],
        'public' => true, 'publicly_queryable' => false, 'exclude_from_search' => true,
        'show_in_rest' => true, 'show_in_graphql' => true,
        'graphql_single_name' => 'contactsPage', 'graphql_plural_name' => 'contactsPages',
        'menu_icon' => 'dashicons-email-alt',
        'supports' => ['title', 'thumbnail'],
    ]);

    register_taxonomy('gv_technology_category', ['gv_technology'], [
        'labels' => [
            'name' => 'Таби технологій',
            'singular_name' => 'Таб технологій',
            'menu_name' => 'Таби',
            'all_items' => 'Усі таби',
            'edit_item' => 'Редагувати таб',
            'add_new_item' => 'Додати таб',
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
        'menu_icon' => 'dashicons-editor-code',
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
            $seo_suffixes = array_merge([''], array_map(static fn (string $locale): string => '_' . $locale, array_keys(GVSPACE_CONTENT_LOCALES)));
            foreach ($seo_suffixes as $locale_suffix) {
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

    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        register_post_meta('gv_technology', '_gvspace_technology_title_' . $locale, [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
        foreach (array_keys(gvspace_localized_technology_fields()) as $field) {
            register_post_meta('gv_technology', '_gvspace_technology_' . $field . '_' . $locale, [
                'type' => 'string',
                'single' => true,
                'show_in_rest' => true,
                'sanitize_callback' => 'sanitize_textarea_field',
                'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
            ]);
        }
    }

    foreach (array_keys(GVSPACE_CASE_FIELDS) as $field) {
        register_post_meta('gv_case', '_gvspace_case_' . $field, [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_textarea_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
    }

    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        register_post_meta('gv_case', '_gvspace_case_title_' . $locale, [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
        foreach (array_keys(GVSPACE_LOCALIZED_CASE_FIELDS) as $field) {
            register_post_meta('gv_case', '_gvspace_case_' . $field . '_' . $locale, [
                'type' => 'string',
                'single' => true,
                'show_in_rest' => true,
                'sanitize_callback' => 'sanitize_textarea_field',
                'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
            ]);
        }
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

    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        register_post_meta('gv_vacancy', '_gvspace_vacancy_title_' . $locale, [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
        foreach (array_keys(GVSPACE_LOCALIZED_VACANCY_FIELDS) as $field) {
            register_post_meta('gv_vacancy', '_gvspace_vacancy_' . $field . '_' . $locale, [
                'type' => 'string',
                'single' => true,
                'show_in_rest' => true,
                'sanitize_callback' => 'sanitize_textarea_field',
                'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
            ]);
        }
    }

    foreach (array_keys(GVSPACE_TEAM_MEMBER_FIELDS) as $field) {
        register_post_meta('gv_team_member', '_gvspace_team_member_' . $field, [
            'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_textarea_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
    }

    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        register_post_meta('gv_team_member', '_gvspace_team_member_title_' . $locale, [
            'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
        foreach (array_keys(GVSPACE_LOCALIZED_TEAM_MEMBER_FIELDS) as $field) {
            register_post_meta('gv_team_member', '_gvspace_team_member_' . $field . '_' . $locale, [
                'type' => 'string', 'single' => true, 'show_in_rest' => true,
                'sanitize_callback' => 'sanitize_textarea_field',
                'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
            ]);
        }
    }

    foreach (array_keys(GVSPACE_PARTNER_FIELDS) as $field) {
        register_post_meta('gv_partner', '_gvspace_partner_' . $field, [
            'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
    }

    register_post_meta('gv_faq', '_gvspace_faq_placement', [
        'type' => 'string', 'single' => true, 'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_key',
        'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
    ]);

    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        register_post_meta('gv_faq', '_gvspace_faq_question_' . $locale, [
            'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
        register_post_meta('gv_faq', '_gvspace_faq_answer_' . $locale, [
            'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_textarea_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
        register_post_meta('gv_home_seo_text', '_gvspace_home_seo_title_' . $locale, [
            'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
        register_post_meta('gv_home_seo_text', '_gvspace_home_seo_content_' . $locale, [
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

function gvspace_seed_home_faqs(): void
{
    if (get_option('gvspace_home_faq_seed_version') === '1') return;
    if (!add_option('gvspace_home_faq_seed_lock', time(), '', false)) return;

    $answer_uk = 'Працюємо з B2C та B2B бізнесами з digital-залежною моделлю росту: eCommerce, EdTech, IT/SaaS, сервісні бізнеси з середнім і високим чеком.';
    $answer_en = 'We work with B2C and B2B companies with digital-led growth models: eCommerce, EdTech, IT/SaaS, and service businesses with medium and high average order values.';
    $items = [
        ['home-faq-implementation-time', 'uk', 'Скільки часу займає впровадження системи?', $answer_uk],
        ['home-faq-industry', 'uk', 'Чи працюєте ви з моєю нішею?', $answer_uk],
        ['home-faq-guarantees', 'uk', 'Які гарантії результату?', $answer_uk],
        ['home-faq-agency', 'uk', 'Чому не фриланс або інша агенція?', $answer_uk],
        ['home-faq-implementation-time', 'en', 'How long does system implementation take?', $answer_en],
        ['home-faq-industry', 'en', 'Do you work with my industry?', $answer_en],
        ['home-faq-guarantees', 'en', 'What results do you guarantee?', $answer_en],
        ['home-faq-agency', 'en', 'Why not a freelancer or another agency?', $answer_en],
    ];

    foreach ($items as $index => [$group, $locale, $question, $answer]) {
        $existing = get_posts([
            'post_type' => 'gv_faq', 'post_status' => 'any', 'numberposts' => 1,
            'meta_query' => [
                ['key' => '_gvspace_translation_group', 'value' => $group],
                ['key' => '_gvspace_content_locale', 'value' => $locale],
            ],
        ]);
        if ($existing) continue;

        $post_id = wp_insert_post([
            'post_type' => 'gv_faq', 'post_status' => 'publish',
            'post_title' => $question, 'post_content' => $answer,
            'menu_order' => $index % 4,
        ]);
        if (is_wp_error($post_id) || !$post_id) continue;

        update_post_meta($post_id, '_gvspace_faq_placement', 'home');
        update_post_meta($post_id, '_gvspace_content_locale', $locale);
        update_post_meta($post_id, '_gvspace_translation_group', $group);
        update_post_meta($post_id, '_gvspace_translation_status', 'published');
    }

    update_option('gvspace_home_faq_seed_version', '1', false);
    delete_option('gvspace_home_faq_seed_lock');
}
add_action('init', 'gvspace_seed_home_faqs', 20);

function gvspace_centralize_home_faqs(): void
{
    if (get_option('gvspace_faq_centralized_v1') === '1') return;
    $lock_time = (int) get_option('gvspace_faq_centralized_v1_lock', 0);
    if ($lock_time) delete_option('gvspace_faq_centralized_v1_lock');
    if (!add_option('gvspace_faq_centralized_v1_lock', time(), '', false)) return;

    $posts = get_posts([
        'post_type' => 'gv_faq',
        'post_status' => ['publish', 'draft', 'pending', 'private'],
        'numberposts' => -1,
        'orderby' => 'ID',
        'order' => 'ASC',
    ]);

    $groups = [];
    foreach ($posts as $post) {
        $group = (string) get_post_meta($post->ID, '_gvspace_translation_group', true);
        $groups[$group !== '' ? $group : 'faq-single-' . $post->ID][] = $post;
    }

    foreach ($groups as $group => $group_posts) {
        $keeper = null;
        foreach ($group_posts as $post) {
            $locale = (string) get_post_meta($post->ID, '_gvspace_content_locale', true) ?: 'legacy';
            if ($locale === 'uk' || $locale === 'legacy') {
                $keeper = $post;
                break;
            }
        }
        if (!$keeper) $keeper = $group_posts[0];

        foreach ($group_posts as $post) {
            $locale = (string) get_post_meta($post->ID, '_gvspace_content_locale', true) ?: 'uk';
            if ($locale === 'legacy' || !array_key_exists($locale, GVSPACE_CONTENT_LOCALES)) $locale = 'uk';
            if ((string) get_post_meta($keeper->ID, '_gvspace_faq_question_' . $locale, true) === '') {
                update_post_meta($keeper->ID, '_gvspace_faq_question_' . $locale, $post->post_title);
            }
            if ((string) get_post_meta($keeper->ID, '_gvspace_faq_answer_' . $locale, true) === '') {
                update_post_meta($keeper->ID, '_gvspace_faq_answer_' . $locale, wp_strip_all_tags((string) $post->post_content));
            }
            foreach (array_keys(GVSPACE_SEO_FIELDS) as $key) {
                $from = (string) get_post_meta($post->ID, '_gvspace_seo_' . $key . '_' . $locale, true);
                if ($from === '') $from = (string) get_post_meta($post->ID, '_gvspace_seo_' . $key, true);
                if ($from !== '' && (string) get_post_meta($keeper->ID, '_gvspace_seo_' . $key . '_' . $locale, true) === '') {
                    update_post_meta($keeper->ID, '_gvspace_seo_' . $key . '_' . $locale, $from);
                }
            }
        }

        $uk_title = (string) get_post_meta($keeper->ID, '_gvspace_faq_question_uk', true) ?: $keeper->post_title;
        wp_update_post([
            'ID' => $keeper->ID,
            'post_title' => $uk_title,
            'post_content' => (string) get_post_meta($keeper->ID, '_gvspace_faq_answer_uk', true),
        ]);
        update_post_meta($keeper->ID, '_gvspace_faq_placement', (string) get_post_meta($keeper->ID, '_gvspace_faq_placement', true) ?: 'home');
        update_post_meta($keeper->ID, '_gvspace_content_locale', 'legacy');
        update_post_meta($keeper->ID, '_gvspace_translation_status', 'published');
        $public_group = str_starts_with($group, 'faq-single-')
            ? (sanitize_title($uk_title) ?: 'faq-' . $keeper->ID)
            : $group;
        update_post_meta($keeper->ID, '_gvspace_translation_group', $public_group);
        update_post_meta($keeper->ID, '_gvspace_faq_centralized', '1');

        foreach ($group_posts as $post) {
            if ((int) $post->ID === (int) $keeper->ID) continue;
            wp_delete_post($post->ID, true);
        }
    }

    update_option('gvspace_faq_centralized_v1', '1', false);
    delete_option('gvspace_faq_centralized_v1_lock');
}
add_action('init', 'gvspace_centralize_home_faqs', 21);

function gvspace_seed_home_seo_texts(): void
{
    if (get_option('gvspace_home_seo_text_seed_version') === '1') return;
    if (!add_option('gvspace_home_seo_text_seed_lock', time(), '', false)) return;

    $items = [
        [
            'uk',
            'Ми віримо, що український бізнес заслуговує на простір для росту без хаосу.',
            'Наша місія — дати CEO інструменти керування, а не просто звіти. Ми допомагаємо побудувати прозору систему, у якій маркетинг, технології та стратегія працюють узгоджено. Це дає керівникам контроль над процесами, зрозумілі показники та основу для передбачуваного масштабування бізнесу.',
        ],
        [
            'en',
            'We believe Ukrainian businesses deserve room to grow without chaos.',
            'Our mission is to give CEOs management tools, not just reports. We help build a transparent system where marketing, technology, and strategy work together. This gives leaders control over processes, clear metrics, and a foundation for predictable business growth.',
        ],
    ];

    foreach ($items as [$locale, $title, $content]) {
        $existing = get_posts([
            'post_type' => 'gv_home_seo_text', 'post_status' => 'any', 'numberposts' => 1,
            'meta_query' => [['key' => '_gvspace_content_locale', 'value' => $locale]],
        ]);
        if ($existing) continue;

        $post_id = wp_insert_post([
            'post_type' => 'gv_home_seo_text', 'post_status' => 'publish',
            'post_title' => $title, 'post_content' => $content,
        ]);
        if (is_wp_error($post_id) || !$post_id) continue;

        update_post_meta($post_id, '_gvspace_content_locale', $locale);
        update_post_meta($post_id, '_gvspace_translation_group', 'home-seo-text');
        update_post_meta($post_id, '_gvspace_translation_status', 'published');
    }

    update_option('gvspace_home_seo_text_seed_version', '1', false);
    delete_option('gvspace_home_seo_text_seed_lock');
}
add_action('init', 'gvspace_seed_home_seo_texts', 20);

function gvspace_centralize_home_seo_texts(): void
{
    if (get_option('gvspace_home_seo_text_centralized_v1') === '1') return;
    $lock_time = (int) get_option('gvspace_home_seo_text_centralized_v1_lock', 0);
    if ($lock_time) delete_option('gvspace_home_seo_text_centralized_v1_lock');
    if (!add_option('gvspace_home_seo_text_centralized_v1_lock', time(), '', false)) return;

    $posts = get_posts([
        'post_type' => 'gv_home_seo_text',
        'post_status' => ['publish', 'draft', 'pending', 'private'],
        'numberposts' => -1,
        'orderby' => 'ID',
        'order' => 'ASC',
    ]);

    $groups = [];
    foreach ($posts as $post) {
        $group = (string) get_post_meta($post->ID, '_gvspace_translation_group', true);
        $groups[$group !== '' ? $group : 'home-seo-single-' . $post->ID][] = $post;
    }

    foreach ($groups as $group => $group_posts) {
        $keeper = null;
        foreach ($group_posts as $post) {
            $locale = (string) get_post_meta($post->ID, '_gvspace_content_locale', true) ?: 'legacy';
            if ($locale === 'uk' || $locale === 'legacy') {
                $keeper = $post;
                break;
            }
        }
        if (!$keeper) $keeper = $group_posts[0];

        foreach ($group_posts as $post) {
            $locale = (string) get_post_meta($post->ID, '_gvspace_content_locale', true) ?: 'uk';
            if ($locale === 'legacy' || !array_key_exists($locale, GVSPACE_CONTENT_LOCALES)) $locale = 'uk';
            if ((string) get_post_meta($keeper->ID, '_gvspace_home_seo_title_' . $locale, true) === '') {
                update_post_meta($keeper->ID, '_gvspace_home_seo_title_' . $locale, $post->post_title);
            }
            if ((string) get_post_meta($keeper->ID, '_gvspace_home_seo_content_' . $locale, true) === '') {
                update_post_meta($keeper->ID, '_gvspace_home_seo_content_' . $locale, wp_strip_all_tags((string) $post->post_content));
            }
        }

        $uk_title = (string) get_post_meta($keeper->ID, '_gvspace_home_seo_title_uk', true) ?: $keeper->post_title;
        wp_update_post([
            'ID' => $keeper->ID,
            'post_title' => $uk_title,
            'post_content' => (string) get_post_meta($keeper->ID, '_gvspace_home_seo_content_uk', true),
        ]);
        update_post_meta($keeper->ID, '_gvspace_content_locale', 'legacy');
        update_post_meta($keeper->ID, '_gvspace_translation_status', 'published');
        update_post_meta($keeper->ID, '_gvspace_translation_group', str_starts_with($group, 'home-seo-single-') ? 'home-seo-text' : $group);

        foreach ($group_posts as $post) {
            if ((int) $post->ID === (int) $keeper->ID) continue;
            wp_delete_post($post->ID, true);
        }
    }

    update_option('gvspace_home_seo_text_centralized_v1', '1', false);
    delete_option('gvspace_home_seo_text_centralized_v1_lock');
}
add_action('init', 'gvspace_centralize_home_seo_texts', 21);

function gvspace_seed_demo_partners(): void
{
    if (get_option('gvspace_demo_partners_seed_version') === '1') return;
    if (!add_option('gvspace_demo_partners_seed_lock', time(), '', false)) return;

    $existing = get_posts([
        'post_type' => 'gv_partner',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ]);
    $source_thumbnail_id = $existing ? get_post_thumbnail_id($existing[0]->ID) : 0;
    $names = ['NovaWorks', 'BrightLab', 'PixelCraft', 'GrowthPoint', 'DataNest', 'ScaleHub', 'DigitalForge', 'North Studio', 'PrimeFlow', 'Orbit Media'];
    $directions = ['IT-РОЗРОБКА', 'ПРОДАКШН', 'SEO & GEO', 'МАРКЕТИНГ'];
    $needed = max(0, 10 - count($existing));

    for ($index = 0; $index < $needed; $index++) {
        $position = count($existing) + $index;
        $name = $names[$position] ?? ('Partner ' . ($position + 1));
        $post_id = wp_insert_post([
            'post_type' => 'gv_partner',
            'post_status' => 'publish',
            'post_title' => $name,
            'menu_order' => $position,
        ]);
        if (is_wp_error($post_id) || !$post_id) continue;

        $direction_uk = $directions[$position % count($directions)];
        $direction_en_map = [
            'IT-РОЗРОБКА' => 'IT DEVELOPMENT', 'ПРОДАКШН' => 'PRODUCTION',
            'SEO & GEO' => 'SEO & GEO', 'МАРКЕТИНГ' => 'MARKETING',
        ];
        update_post_meta($post_id, '_gvspace_partner_direction_uk', $direction_uk);
        update_post_meta($post_id, '_gvspace_partner_direction_en', $direction_en_map[$direction_uk] ?? $direction_uk);
        update_post_meta($post_id, '_gvspace_content_locale', 'uk');
        update_post_meta($post_id, '_gvspace_translation_group', 'demo-partner-' . ($position + 1));
        update_post_meta($post_id, '_gvspace_translation_status', 'published');
        if ($source_thumbnail_id) set_post_thumbnail($post_id, $source_thumbnail_id);
    }

    update_option('gvspace_demo_partners_seed_version', '1', false);
    delete_option('gvspace_demo_partners_seed_lock');
}
add_action('init', 'gvspace_seed_demo_partners', 20);

/**
 * Remove duplicate demo records that could be created when several requests ran
 * the first content seed concurrently. Only records carrying our seed metadata
 * are touched; manually created CMS content is left intact.
 */
function gvspace_cleanup_duplicate_home_seed_content(): void
{
    if (get_option('gvspace_home_seed_dedup_version') === '1') return;
    if (!add_option('gvspace_home_seed_dedup_lock', time(), '', false)) return;

    $groups = [];
    $seeded_posts = get_posts([
        'post_type' => ['gv_faq', 'gv_home_seo_text', 'gv_partner'],
        'post_status' => 'any',
        'numberposts' => -1,
        'orderby' => 'ID',
        'order' => 'ASC',
        'meta_query' => [[
            'key' => '_gvspace_translation_group',
            'compare' => 'EXISTS',
        ]],
    ]);

    foreach ($seeded_posts as $post) {
        $translation_group = (string) get_post_meta($post->ID, '_gvspace_translation_group', true);
        $locale = (string) get_post_meta($post->ID, '_gvspace_content_locale', true);
        $is_seed_record = str_starts_with($translation_group, 'home-faq-')
            || $translation_group === 'home-seo-text'
            || preg_match('/^demo-partner-[0-9]+$/', $translation_group);
        if (!$is_seed_record) continue;

        $key = $post->post_type . '|' . $translation_group . '|' . $locale;
        if (!isset($groups[$key])) {
            $groups[$key] = $post->ID;
            continue;
        }

        // Trash instead of permanently deleting so an administrator can recover it.
        wp_trash_post($post->ID);
    }

    update_option('gvspace_home_seed_dedup_version', '1', false);
    delete_option('gvspace_home_seed_dedup_lock');
}
add_action('init', 'gvspace_cleanup_duplicate_home_seed_content', 21);

function gvspace_migrate_partner_directions(): void
{
    if (get_option('gvspace_partner_direction_migration_version') === '1') return;
    $translations = [
        'IT-РОЗРОБКА' => 'IT DEVELOPMENT', 'ПРОДАКШН' => 'PRODUCTION',
        'Продакшн' => 'PRODUCTION', 'SEO & GEO' => 'SEO & GEO', 'МАРКЕТИНГ' => 'MARKETING',
    ];
    $partners = get_posts(['post_type' => 'gv_partner', 'post_status' => 'any', 'numberposts' => -1]);
    foreach ($partners as $partner) {
        $legacy = trim((string) get_post_meta($partner->ID, '_gvspace_partner_direction', true));
        $direction_uk = trim((string) get_post_meta($partner->ID, '_gvspace_partner_direction_uk', true));
        $direction_en = trim((string) get_post_meta($partner->ID, '_gvspace_partner_direction_en', true));
        if ($direction_uk === '' && $legacy !== '') {
            $direction_uk = $legacy;
            update_post_meta($partner->ID, '_gvspace_partner_direction_uk', $direction_uk);
        }
        if ($direction_en === '' && $direction_uk !== '') {
            update_post_meta($partner->ID, '_gvspace_partner_direction_en', $translations[$direction_uk] ?? $direction_uk);
        }
    }
    update_option('gvspace_partner_direction_migration_version', '1', false);
}
add_action('init', 'gvspace_migrate_partner_directions', 21);

function gvspace_seed_media_folders(): void
{
    if (get_option('gvspace_media_folder_seed_version') === '1') return;
    $folders = [
        'partner-logos' => 'Логотипи партнерів',
        'technologies' => 'Технології',
        'cases' => 'Кейси',
        'team' => 'Команда',
        'reviews' => 'Відгуки',
        'blog' => 'Блог',
        'backgrounds' => 'Фони та банери',
    ];
    foreach ($folders as $slug => $name) {
        if (!term_exists($slug, 'gv_media_folder')) {
            wp_insert_term($name, 'gv_media_folder', ['slug' => $slug]);
        }
    }
    update_option('gvspace_media_folder_seed_version', '1', false);
}
add_action('init', 'gvspace_seed_media_folders', 22);

function gvspace_sort_existing_media_into_folders(): void
{
    if (get_option('gvspace_media_sort_version') === '1') return;
    $post_type_folders = [
        'gv_partner' => 'partner-logos', 'gv_technology' => 'technologies',
        'gv_case' => 'cases', 'gv_team_member' => 'team',
        'gv_review' => 'reviews', 'post' => 'blog',
    ];
    foreach ($post_type_folders as $post_type => $folder_slug) {
        $term = get_term_by('slug', $folder_slug, 'gv_media_folder');
        if (!$term) continue;
        $posts = get_posts(['post_type' => $post_type, 'post_status' => 'any', 'numberposts' => -1]);
        foreach ($posts as $post) {
            $thumbnail_id = get_post_thumbnail_id($post->ID);
            if ($thumbnail_id) wp_set_object_terms($thumbnail_id, [$term->term_id], 'gv_media_folder', true);
        }
    }
    $background_term = get_term_by('slug', 'backgrounds', 'gv_media_folder');
    if ($background_term) {
        $attachments = get_posts(['post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => -1]);
        foreach ($attachments as $attachment) {
            $filename = strtolower((string) get_attached_file($attachment->ID));
            if (str_contains($filename, 'hero') || str_contains($filename, 'background') || str_contains($filename, '-bg')) {
                wp_set_object_terms($attachment->ID, [$background_term->term_id], 'gv_media_folder', true);
            }
        }
    }
    update_option('gvspace_media_sort_version', '1', false);
}
add_action('init', 'gvspace_sort_existing_media_into_folders', 23);

add_filter('attachment_fields_to_edit', function (array $fields, WP_Post $post): array {
    $selected = wp_get_object_terms($post->ID, 'gv_media_folder', ['fields' => 'ids']);
    $fields['gv_media_folder'] = [
        'label' => 'Папка',
        'input' => 'html',
        'html' => wp_dropdown_categories([
            'taxonomy' => 'gv_media_folder', 'name' => "attachments[{$post->ID}][gv_media_folder]",
            'id' => "attachments-{$post->ID}-gv-media-folder", 'show_option_none' => 'Без папки',
            'option_none_value' => '0', 'hide_empty' => false,
            'hierarchical' => true, 'selected' => $selected[0] ?? 0, 'echo' => false,
        ]),
        'helps' => 'Використовується лише для впорядкування медіабібліотеки. URL файлу не змінюється.',
    ];
    return $fields;
}, 10, 2);

add_filter('attachment_fields_to_save', function (array $post, array $attachment): array {
    if (!current_user_can('upload_files')) return $post;
    $term_id = isset($attachment['gv_media_folder']) ? absint($attachment['gv_media_folder']) : 0;
    wp_set_object_terms((int) $post['ID'], $term_id ? [$term_id] : [], 'gv_media_folder', false);
    return $post;
}, 10, 2);

add_action('restrict_manage_posts', function (string $post_type): void {
    if ($post_type !== 'attachment') return;
    $selected = isset($_GET['gv_media_folder']) ? absint($_GET['gv_media_folder']) : 0;
    wp_dropdown_categories([
        'taxonomy' => 'gv_media_folder', 'name' => 'gv_media_folder',
        'show_option_all' => 'Усі папки', 'hide_empty' => false,
        'hierarchical' => true, 'selected' => $selected, 'value_field' => 'term_id',
    ]);
});

add_action('pre_get_posts', function (WP_Query $query): void {
    if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'attachment') return;
    $term_id = isset($_GET['gv_media_folder']) ? absint($_GET['gv_media_folder']) : 0;
    if ($term_id) {
        $query->set('tax_query', [[
            'taxonomy' => 'gv_media_folder', 'field' => 'term_id', 'terms' => [$term_id],
        ]]);
    }
});

add_filter('ajax_query_attachments_args', function (array $query): array {
    $request_query = isset($_REQUEST['query']) && is_array($_REQUEST['query'])
        ? wp_unslash($_REQUEST['query'])
        : [];
    $term_id = isset($query['gv_media_folder'])
        ? absint($query['gv_media_folder'])
        : (isset($request_query['gv_media_folder']) ? absint($request_query['gv_media_folder']) : 0);
    unset($query['gv_media_folder']);
    if ($term_id) {
        $query['tax_query'] = array_merge($query['tax_query'] ?? [], [[
            'taxonomy' => 'gv_media_folder', 'field' => 'term_id', 'terms' => [$term_id],
        ]]);
    }
    return $query;
});

add_action('admin_enqueue_scripts', function (string $hook): void {
    if (in_array($hook, ['post.php', 'post-new.php'], true)) {
        $screen = function_exists('get_current_screen') ? get_current_screen() : null;
        if ($screen && in_array($screen->post_type, ['gv_case', 'gv_technology'], true)) {
            wp_enqueue_media();
        }
    }
    if ($hook !== 'upload.php') return;
    $terms = get_terms(['taxonomy' => 'gv_media_folder', 'hide_empty' => false]);
    if (is_wp_error($terms)) return;
    $folders = array_map(static fn (WP_Term $term): array => [
        'id' => $term->term_id, 'name' => $term->name,
    ], $terms);
    wp_enqueue_media();
    wp_add_inline_script('media-views', 'window.gvspaceMediaFolders = ' . wp_json_encode($folders) . ';', 'before');
    wp_add_inline_script('media-views', <<<'JS'
(function ($, wp) {
    if (!wp || !wp.media || !wp.media.view || !Array.isArray(window.gvspaceMediaFolders)) return;
    var FolderFilter = wp.media.view.AttachmentFilters.extend({
        id: 'gvspace-media-folder-filter',
        createFilters: function () {
            var filters = { all: { text: 'Усі папки', props: { gv_media_folder: null }, priority: 10 } };
            window.gvspaceMediaFolders.forEach(function (folder, index) {
                filters['folder-' + folder.id] = {
                    text: folder.name,
                    props: { gv_media_folder: folder.id },
                    priority: 20 + index
                };
            });
            this.filters = filters;
        }
    });
    var originalCreateToolbar = wp.media.view.AttachmentsBrowser.prototype.createToolbar;
    wp.media.view.AttachmentsBrowser.prototype.createToolbar = function () {
        originalCreateToolbar.apply(this, arguments);
        this.toolbar.set('gvspaceMediaFolder', new FolderFilter({
            controller: this.controller,
            model: this.collection.props,
            priority: -75
        }).render());
    };
})(jQuery, window.wp);
JS
    );
});

add_action('admin_menu', function (): void {
    add_menu_page(
        'Головна сторінка',
        'Головна сторінка',
        'edit_posts',
        'gvspace-home',
        'gvspace_render_home_admin_page',
        'dashicons-admin-home',
        22
    );
});

add_action('admin_menu', function (): void {
    remove_submenu_page('gvspace-home', 'gvspace-home');
}, 99);

function gvspace_render_home_admin_page(): void
{
    if (!current_user_can('edit_posts')) return;
    ?>
    <div class="wrap">
        <h1>Головна сторінка</h1>
        <p>Тут згруповані динамічні блоки, які відображаються на головній сторінці сайту.</p>
        <p>
            <a class="button button-primary" href="<?php echo esc_url(admin_url('edit.php?post_type=gv_faq')); ?>">Редагувати FAQ</a>
            <a class="button" href="<?php echo esc_url(admin_url('edit.php?post_type=gv_home_seo_text')); ?>">Редагувати SEO-текст</a>
            <a class="button" href="<?php echo esc_url(admin_url('edit.php?post_type=gv_partner')); ?>">Редагувати партнерів</a>
        </p>
    </div>
    <?php
}

add_filter('manage_gv_faq_posts_columns', function (array $columns): array {
    $result = [];
    foreach ($columns as $key => $label) {
        $result[$key] = $label;
        if ($key === 'title') $result['gvspace_faq_page'] = 'Сторінка';
    }
    return $result;
});

add_action('manage_gv_faq_posts_custom_column', function (string $column): void {
    if ($column === 'gvspace_faq_page') echo 'Головна';
});

function gvspace_sanitize_content_locale(string $value): string
{
    return array_key_exists($value, GVSPACE_CONTENT_LOCALES) ? $value : 'legacy';
}

function gvspace_split_meta_lines(string $value): array
{
    return array_values(array_filter(array_map('trim', preg_split('/\r\n|\n|\r/', $value) ?: []), static fn (string $line): bool => $line !== ''));
}

function gvspace_case_gallery_attachment_ids(int $post_id): array
{
    $ids_raw = trim((string) get_post_meta($post_id, '_gvspace_case_gallery_ids', true));
    if ($ids_raw !== '') {
        return array_values(array_unique(array_filter(array_map('absint', explode(',', $ids_raw)))));
    }

    $ids = [];
    foreach (gvspace_split_meta_lines((string) get_post_meta($post_id, '_gvspace_case_gallery', true)) as $line) {
        if (ctype_digit($line)) {
            $ids[] = absint($line);
            continue;
        }
        $found = attachment_url_to_postid($line);
        if ($found) $ids[] = $found;
    }

    return array_values(array_unique(array_filter($ids)));
}

function gvspace_case_gallery_urls(int $post_id): array
{
    $urls = [];
    foreach (gvspace_case_gallery_attachment_ids($post_id) as $id) {
        $url = wp_get_attachment_image_url($id, 'full');
        if ($url) $urls[] = $url;
    }
    if ($urls) return $urls;

    $fallback = gvspace_split_meta_lines((string) get_post_meta($post_id, '_gvspace_case_gallery', true));
    return array_values(array_filter($fallback, static fn (string $line): bool => !ctype_digit($line) && $line !== ''));
}

function gvspace_save_case_gallery_ids(int $post_id, array $ids): void
{
    $ids = array_values(array_unique(array_filter(array_map('absint', $ids))));
    update_post_meta($post_id, '_gvspace_case_gallery_ids', implode(',', $ids));
    $urls = [];
    foreach ($ids as $id) {
        $url = wp_get_attachment_image_url($id, 'full');
        if ($url) $urls[] = $url;
    }
    update_post_meta($post_id, '_gvspace_case_gallery', implode("\n", $urls));
}

function gvspace_get_content_locale(WP_Post $post): string
{
    if (isset($_POST['gvspace_content_locale'])) {
        return gvspace_sanitize_content_locale(sanitize_text_field(wp_unslash($_POST['gvspace_content_locale'])));
    }

    $stored_locale = (string) get_post_meta($post->ID, '_gvspace_content_locale', true);
    return $stored_locale ?: ($post->post_status === 'auto-draft' ? 'uk' : 'legacy');
}

function gvspace_centralized_language_group(string $post_type): string
{
    return match ($post_type) {
        'gv_service' => 'service-language',
        'gv_team_member' => 'team-member-language',
        'gv_vacancy' => 'vacancy-language',
        'gv_case' => 'case-language',
        'gv_faq' => 'faq-language',
        'gv_home_seo_text' => 'home-seo-text-language',
        'gv_privacy_policy' => 'privacy-policy-language',
        'gv_terms_of_use' => 'terms-of-use-language',
        'gv_contacts_page' => 'contacts-page-language',
        'gv_technology' => 'technology-language',
        default => 'language',
    };
}

function gvspace_render_field_set(WP_Post $post, array $fields, string $name_prefix, string $meta_prefix, string $meta_suffix = ''): void
{
    foreach ($fields as $key => $config) {
        $field_name = $name_prefix . $key;
        $value = (string) get_post_meta($post->ID, $meta_prefix . $key . $meta_suffix, true);
        echo '<p><label for="' . esc_attr($field_name) . '"><strong>' . esc_html($config['label']) . '</strong></label><br>';
        if ($config['type'] === 'textarea') {
            echo '<textarea id="' . esc_attr($field_name) . '" name="' . esc_attr($field_name) . '" rows="4" style="width:100%">' . esc_textarea($value) . '</textarea>';
        } else {
            echo '<input type="' . esc_attr($config['type'] === 'number' ? 'number' : 'text') . '" id="' . esc_attr($field_name) . '" name="' . esc_attr($field_name) . '" value="' . esc_attr($value) . '" style="width:100%">';
        }
        echo '</p>';
    }
}

function gvspace_render_language_switcher_script(): void
{
    static $rendered = false;
    if ($rendered) return;
    $rendered = true;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-gvspace-language-select]').forEach(function (select) {
            var group = select.getAttribute('data-gvspace-language-select');
            var update = function () {
                document.querySelectorAll('[data-gvspace-language-select="' + group + '"]').forEach(function (linkedSelect) {
                    linkedSelect.value = select.value;
                });
                document.querySelectorAll('[data-gvspace-language-panel="' + group + '"]').forEach(function (panel) {
                    panel.hidden = panel.getAttribute('data-locale') !== select.value;
                });
            };
            select.addEventListener('change', update);
            update();
        });
    });
    </script>
    <?php
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
        if (in_array($post_type, GVSPACE_CENTRALIZED_POST_TYPES, true)) continue;
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
        if (in_array($post_type, ['gv_faq', 'gv_home_seo_text', 'gv_team_member'], true)) continue;
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
    $help = [
        'title' => 'Заголовок вкладки браузера та результату в Google. На самій сторінці не показується.',
        'description' => 'Опис для Google та інших пошукових систем. На самій сторінці не показується.',
        'h1' => 'Видимий головний заголовок сторінки. Якщо порожньо — використовується заголовок першого екрана з блоку «Контент сторінки послуги».',
        'og_title' => 'Заголовок прев’ю при поширенні посилання в соцмережах. Якщо порожньо — використовується SEO Title.',
        'og_description' => 'Опис прев’ю в соцмережах. Якщо порожньо — використовується Meta Description.',
        'og_image' => 'Повна URL-адреса зображення для прев’ю в соцмережах. Якщо порожньо — використовується головне зображення.',
    ];
    echo '<span class="description">' . esc_html($help[$key] ?? '') . '</span>';
    if ($limit) echo '<br><span class="description">Рекомендовано до ' . esc_html((string) $limit) . ' символів.</span>';
    echo '</p>';
}

function gvspace_render_seo_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_seo', 'gvspace_seo_nonce');
    $locale = gvspace_get_content_locale($post);
    echo '<p class="description"><strong>SEO Title і Meta Description не є текстом сторінки.</strong> Вони відображаються у коді сторінки, вкладці браузера та пошуковій видачі. Для видимого заголовка заповніть «H1 сторінки». Зміни на сайті можуть з’явитися із затримкою до 60 секунд через кеш.</p>';

    if (in_array($post->post_type, GVSPACE_CENTRALIZED_POST_TYPES, true)) {
        $language_group = gvspace_centralized_language_group($post->post_type);
        $active_locale = $locale !== 'legacy' && array_key_exists($locale, GVSPACE_CONTENT_LOCALES) ? $locale : 'uk';
        echo '<p><label for="gvspace-seo-language"><strong>Мова SEO</strong></label> ';
        echo '<select id="gvspace-seo-language" data-gvspace-language-select="' . esc_attr($language_group) . '">';
        foreach (GVSPACE_CONTENT_LOCALES as $content_locale => $label) {
            echo '<option value="' . esc_attr($content_locale) . '"' . selected($active_locale, $content_locale, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select></p>';
        foreach (GVSPACE_CONTENT_LOCALES as $content_locale => $label) {
            echo '<div data-gvspace-language-panel="' . esc_attr($language_group) . '" data-locale="' . esc_attr($content_locale) . '"' . ($content_locale === $active_locale ? '' : ' hidden') . '>';
            echo '<hr><h3>' . esc_html($label) . '</h3>';
            foreach (GVSPACE_SEO_FIELDS as $key => $config) {
                gvspace_render_seo_field($post, $key, $config, '_' . $content_locale);
            }
            echo '</div>';
        }
        gvspace_render_language_switcher_script();
        return;
    }

    if ($locale === 'legacy') {
        foreach (['uk' => 'Українська', 'en' => 'English'] as $legacy_locale => $label) {
            echo '<hr><h3>' . esc_html($label) . '</h3>';
            foreach (GVSPACE_SEO_FIELDS as $key => $config) gvspace_render_seo_field($post, $key, $config, '_' . $legacy_locale);
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
    $suffixes = in_array($post->post_type, GVSPACE_CENTRALIZED_POST_TYPES, true)
        ? array_map(static fn (string $content_locale): string => '_' . $content_locale, array_keys(GVSPACE_CONTENT_LOCALES))
        : ($locale === 'legacy' ? ['_uk', '_en'] : ['']);
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
    if (in_array($gvspace_localized_post_type, GVSPACE_CENTRALIZED_POST_TYPES, true)) continue;
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
    add_meta_box('gvspace-partner-details', 'Дані партнера', 'gvspace_render_partner_fields', 'gv_partner', 'normal', 'high');
    add_meta_box('gvspace-faq-content', 'Контент FAQ', 'gvspace_render_faq_content_fields', 'gv_faq', 'normal', 'high');
    add_meta_box('gvspace-faq-details', 'Розміщення FAQ', 'gvspace_render_faq_fields', 'gv_faq', 'side', 'default');
    add_meta_box('gvspace-home-seo-text-content', 'Контент SEO-тексту', 'gvspace_render_home_seo_text_fields', 'gv_home_seo_text', 'normal', 'high');
});

add_action('admin_head', function (): void {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || !in_array($screen->post_type, ['gv_faq', 'gv_vacancy', 'gv_home_seo_text', 'gv_privacy_policy', 'gv_terms_of_use', 'gv_contacts_page', 'gv_technology'], true) || !in_array($screen->base, ['post', 'post-new'], true)) return;
    echo '<style>#titlediv{display:none!important}</style>';
});

function gvspace_render_faq_content_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_faq', 'gvspace_faq_nonce');
    $stored_locale = gvspace_get_content_locale($post);
    $active_locale = array_key_exists($stored_locale, GVSPACE_CONTENT_LOCALES) ? $stored_locale : 'uk';
    echo '<p class="description"><strong>Одне питання — один запис.</strong> Оберіть мову та заповніть її переклад. Перемикання мови не перезавантажує сторінку й не видаляє введений текст.</p>';
    echo '<p><label for="gvspace-faq-language"><strong>Редагувати мовну версію</strong></label> ';
    echo '<select id="gvspace-faq-language" data-gvspace-language-select="faq-language">';
    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        echo '<option value="' . esc_attr($locale) . '"' . selected($active_locale, $locale, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select></p>';

    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        $question = (string) get_post_meta($post->ID, '_gvspace_faq_question_' . $locale, true);
        $answer = (string) get_post_meta($post->ID, '_gvspace_faq_answer_' . $locale, true);
        if ($locale === 'uk' && $question === '') $question = $post->post_title;
        if ($locale === 'uk' && $answer === '') $answer = wp_strip_all_tags((string) $post->post_content);
        echo '<div data-gvspace-language-panel="faq-language" data-locale="' . esc_attr($locale) . '"' . ($locale === $active_locale ? '' : ' hidden') . '>';
        echo '<hr><h3>' . esc_html($label) . '</h3>';
        echo '<p><label for="gvspace_faq_question_' . esc_attr($locale) . '"><strong>Питання</strong></label><br>';
        echo '<input type="text" id="gvspace_faq_question_' . esc_attr($locale) . '" name="gvspace_faq_question_' . esc_attr($locale) . '" value="' . esc_attr($question) . '" style="width:100%"></p>';
        echo '<p><label for="gvspace_faq_answer_' . esc_attr($locale) . '"><strong>Відповідь</strong></label><br>';
        echo '<textarea id="gvspace_faq_answer_' . esc_attr($locale) . '" name="gvspace_faq_answer_' . esc_attr($locale) . '" rows="6" style="width:100%">' . esc_textarea($answer) . '</textarea></p>';
        echo '</div>';
    }
    gvspace_render_language_switcher_script();
}

function gvspace_render_faq_fields(WP_Post $post): void
{
    $placement = (string) get_post_meta($post->ID, '_gvspace_faq_placement', true) ?: 'home';
    ?>
    <p><label for="gvspace_faq_placement"><strong>Показувати на сторінці</strong></label></p>
    <select id="gvspace_faq_placement" name="gvspace_faq_placement" style="width:100%">
        <option value="home" <?php selected($placement, 'home'); ?>>Головна сторінка</option>
    </select>
    <p class="description">Порядок на сторінці задається у блоці «Атрибути».</p>
    <?php
}

add_action('save_post_gv_faq', function (int $post_id): void {
    static $saving_title = false;
    if ($saving_title) return;
    if (
        !isset($_POST['gvspace_faq_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_faq_nonce'])), 'gvspace_save_faq')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) return;

    $placement = isset($_POST['gvspace_faq_placement'])
        ? sanitize_key(wp_unslash($_POST['gvspace_faq_placement']))
        : 'home';
    update_post_meta($post_id, '_gvspace_faq_placement', $placement === 'home' ? 'home' : 'home');

    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        $question_field = 'gvspace_faq_question_' . $locale;
        $answer_field = 'gvspace_faq_answer_' . $locale;
        if (isset($_POST[$question_field])) {
            update_post_meta($post_id, '_gvspace_faq_question_' . $locale, sanitize_text_field(wp_unslash($_POST[$question_field])));
        }
        if (isset($_POST[$answer_field])) {
            update_post_meta($post_id, '_gvspace_faq_answer_' . $locale, sanitize_textarea_field(wp_unslash($_POST[$answer_field])));
        }
    }

    update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    if ((string) get_post_meta($post_id, '_gvspace_translation_group', true) === '') {
        $default_group = sanitize_title((string) get_post_field('post_name', $post_id) ?: (string) get_post_field('post_title', $post_id));
        update_post_meta($post_id, '_gvspace_translation_group', $default_group ?: 'faq-' . $post_id);
    }

    $uk_title = (string) get_post_meta($post_id, '_gvspace_faq_question_uk', true);
    $uk_answer = (string) get_post_meta($post_id, '_gvspace_faq_answer_uk', true);
    if ($uk_title !== '' && (get_post_field('post_title', $post_id) !== $uk_title || get_post_field('post_content', $post_id) !== $uk_answer)) {
        $saving_title = true;
        wp_update_post(['ID' => $post_id, 'post_title' => $uk_title, 'post_content' => $uk_answer]);
        $saving_title = false;
    }
});

function gvspace_render_home_seo_text_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_home_seo_text', 'gvspace_home_seo_text_nonce');
    $stored_locale = gvspace_get_content_locale($post);
    $active_locale = array_key_exists($stored_locale, GVSPACE_CONTENT_LOCALES) ? $stored_locale : 'uk';
    echo '<p class="description"><strong>Один SEO-текст — один запис.</strong> Оберіть мову та заповніть її переклад. Перемикання мови не перезавантажує сторінку й не видаляє введений текст. Достатньо одного опублікованого запису для всіх мов головної сторінки.</p>';
    echo '<p><label for="gvspace-home-seo-text-language"><strong>Редагувати мовну версію</strong></label> ';
    echo '<select id="gvspace-home-seo-text-language" data-gvspace-language-select="home-seo-text-language">';
    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        echo '<option value="' . esc_attr($locale) . '"' . selected($active_locale, $locale, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select></p>';

    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        $title = (string) get_post_meta($post->ID, '_gvspace_home_seo_title_' . $locale, true);
        $content = (string) get_post_meta($post->ID, '_gvspace_home_seo_content_' . $locale, true);
        if ($locale === 'uk' && $title === '') $title = $post->post_title;
        if ($locale === 'uk' && $content === '') $content = wp_strip_all_tags((string) $post->post_content);
        echo '<div data-gvspace-language-panel="home-seo-text-language" data-locale="' . esc_attr($locale) . '"' . ($locale === $active_locale ? '' : ' hidden') . '>';
        echo '<hr><h3>' . esc_html($label) . '</h3>';
        echo '<p><label for="gvspace_home_seo_title_' . esc_attr($locale) . '"><strong>Виділений перший рядок</strong></label><br>';
        echo '<input type="text" id="gvspace_home_seo_title_' . esc_attr($locale) . '" name="gvspace_home_seo_title_' . esc_attr($locale) . '" value="' . esc_attr($title) . '" style="width:100%"></p>';
        echo '<p><label for="gvspace_home_seo_content_' . esc_attr($locale) . '"><strong>SEO-текст</strong></label><br>';
        echo '<textarea id="gvspace_home_seo_content_' . esc_attr($locale) . '" name="gvspace_home_seo_content_' . esc_attr($locale) . '" rows="8" style="width:100%">' . esc_textarea($content) . '</textarea></p>';
        echo '</div>';
    }
    gvspace_render_language_switcher_script();
}

add_action('save_post_gv_home_seo_text', function (int $post_id): void {
    static $saving_title = false;
    if ($saving_title) return;
    if (
        !isset($_POST['gvspace_home_seo_text_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_home_seo_text_nonce'])), 'gvspace_save_home_seo_text')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) return;

    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        $title_field = 'gvspace_home_seo_title_' . $locale;
        $content_field = 'gvspace_home_seo_content_' . $locale;
        if (isset($_POST[$title_field])) {
            update_post_meta($post_id, '_gvspace_home_seo_title_' . $locale, sanitize_text_field(wp_unslash($_POST[$title_field])));
        }
        if (isset($_POST[$content_field])) {
            update_post_meta($post_id, '_gvspace_home_seo_content_' . $locale, sanitize_textarea_field(wp_unslash($_POST[$content_field])));
        }
    }

    update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    if ((string) get_post_meta($post_id, '_gvspace_translation_group', true) === '') {
        update_post_meta($post_id, '_gvspace_translation_group', 'home-seo-text');
    }

    $uk_title = (string) get_post_meta($post_id, '_gvspace_home_seo_title_uk', true);
    $uk_content = (string) get_post_meta($post_id, '_gvspace_home_seo_content_uk', true);
    if ($uk_title !== '' && (get_post_field('post_title', $post_id) !== $uk_title || get_post_field('post_content', $post_id) !== $uk_content)) {
        $saving_title = true;
        wp_update_post(['ID' => $post_id, 'post_title' => $uk_title, 'post_content' => $uk_content]);
        $saving_title = false;
    }
});

function gvspace_render_partner_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_partner', 'gvspace_partner_nonce');
    echo '<p class="description">Назву компанії вкажіть у заголовку, логотип завантажте як «Головне зображення», позицію у слайдері задайте в полі «Порядок».</p>';
    gvspace_render_field_set($post, GVSPACE_PARTNER_FIELDS, 'gvspace_partner_', '_gvspace_partner_');
}

add_action('save_post_gv_partner', function (int $post_id): void {
    if (
        !isset($_POST['gvspace_partner_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_partner_nonce'])), 'gvspace_save_partner')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) return;
    gvspace_save_field_set($post_id, GVSPACE_PARTNER_FIELDS, 'gvspace_partner_', '_gvspace_partner_');
});

function gvspace_render_team_member_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_team_member', 'gvspace_team_member_nonce');
    $stored_locale = gvspace_get_content_locale($post);
    $active_locale = array_key_exists($stored_locale, GVSPACE_CONTENT_LOCALES) ? $stored_locale : 'uk';
    echo '<p class="description"><strong>Один учасник — один запис.</strong> Оберіть мову та заповніть її переклад. Фото, таби команди й порядок картки є спільними для всіх мов.</p>';
    echo '<p><label for="gvspace-team-member-language"><strong>Редагувати мовну версію</strong></label> ';
    echo '<select id="gvspace-team-member-language" data-gvspace-language-select="team-member-language">';
    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        echo '<option value="' . esc_attr($locale) . '"' . selected($active_locale, $locale, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select></p>';

    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        $title = (string) get_post_meta($post->ID, '_gvspace_team_member_title_' . $locale, true);
        if ($locale === 'uk' && $title === '') $title = $post->post_title;
        echo '<div data-gvspace-language-panel="team-member-language" data-locale="' . esc_attr($locale) . '"' . ($locale === $active_locale ? '' : ' hidden') . '>';
        echo '<hr><h3>' . esc_html($label) . '</h3>';
        echo '<p><label for="gvspace_team_member_title_' . esc_attr($locale) . '"><strong>Ім’я учасника</strong></label><br>';
        echo '<input type="text" id="gvspace_team_member_title_' . esc_attr($locale) . '" name="gvspace_team_member_title_' . esc_attr($locale) . '" value="' . esc_attr($title) . '" style="width:100%"></p>';
        gvspace_render_field_set($post, GVSPACE_LOCALIZED_TEAM_MEMBER_FIELDS, 'gvspace_team_member_' . $locale . '_', '_gvspace_team_member_', '_' . $locale);
        echo '</div>';
    }
    gvspace_render_language_switcher_script();
}

add_action('save_post_gv_team_member', function (int $post_id): void {
    static $saving_title = false;
    if ($saving_title) return;
    if (
        !isset($_POST['gvspace_team_member_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_team_member_nonce'])), 'gvspace_save_team_member')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) return;
    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        $title_field = 'gvspace_team_member_title_' . $locale;
        if (isset($_POST[$title_field])) {
            update_post_meta($post_id, '_gvspace_team_member_title_' . $locale, sanitize_text_field(wp_unslash($_POST[$title_field])));
        }
        foreach (array_keys(GVSPACE_LOCALIZED_TEAM_MEMBER_FIELDS) as $field) {
            $field_name = 'gvspace_team_member_' . $locale . '_' . $field;
            if (!isset($_POST[$field_name])) continue;
            update_post_meta($post_id, '_gvspace_team_member_' . $field . '_' . $locale, sanitize_textarea_field(wp_unslash($_POST[$field_name])));
        }
    }

    update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    if ((string) get_post_meta($post_id, '_gvspace_translation_group', true) === '') {
        $default_group = sanitize_title((string) get_post_field('post_name', $post_id) ?: (string) get_post_field('post_title', $post_id));
        update_post_meta($post_id, '_gvspace_translation_group', $default_group ?: 'team-member-' . $post_id);
    }

    if (isset($_POST['gvspace_team_tabs_submitted'])) {
        $allowed = array_keys(gvspace_get_team_tab_options());
        $submitted = array_map('sanitize_key', wp_unslash((array) ($_POST['gvspace_team_tabs'] ?? [])));
        wp_set_object_terms($post_id, array_values(array_intersect($allowed, $submitted)), 'gv_team_member_category', false);
    }

    $uk_title = (string) get_post_meta($post_id, '_gvspace_team_member_title_uk', true);
    if ($uk_title !== '' && get_post_field('post_title', $post_id) !== $uk_title) {
        $saving_title = true;
        wp_update_post(['ID' => $post_id, 'post_title' => $uk_title]);
        $saving_title = false;
    }
});

function gvspace_technology_meet_photo_value(int $post_id): string
{
    return (string) get_post_meta($post_id, '_gvspace_technology_meet_photo', true);
}

function gvspace_technology_meet_photo_url(int $post_id): string
{
    $raw = gvspace_technology_meet_photo_value($post_id);
    if ($raw === '') return '';
    if (ctype_digit($raw)) {
        $url = wp_get_attachment_image_url((int) $raw, 'large');
        return $url ?: '';
    }
    return $raw;
}

function gvspace_render_technology_meet_photo_field(WP_Post $post): void
{
    $raw = gvspace_technology_meet_photo_value($post->ID);
    $preview = gvspace_technology_meet_photo_url($post->ID);
    if ($preview === '' && $raw !== '' && !ctype_digit($raw)) $preview = $raw;
    echo '<div data-gvspace-meet-photo style="margin:12px 0 18px">';
    echo '<p style="margin-bottom:8px"><strong>Фото експерта в блоці зустрічі</strong></p>';
    echo '<input type="hidden" name="gvspace_technology_meet_photo" value="' . esc_attr($raw) . '">';
    echo '<div data-gvspace-meet-photo-preview style="margin:0 0 8px' . ($preview === '' ? ';display:none' : '') . '">';
    echo '<img src="' . esc_url($preview) . '" alt="" style="display:block;width:140px;height:180px;object-fit:cover;border:1px solid #c3c4c7;border-radius:2px;background:#f0f0f1">';
    echo '</div>';
    echo '<p style="margin:0">';
    echo '<button type="button" class="button" data-gvspace-meet-photo-select>Обрати фото</button> ';
    echo '<button type="button" class="button-link" data-gvspace-meet-photo-remove' . ($raw === '' ? ' hidden' : '') . '>Видалити</button>';
    echo '</p>';
    echo '<p class="description">Оберіть зображення з медіатеки. Якщо порожньо, береться фото з розділу «Команда» або заглушка.</p>';
    echo '</div>';
    gvspace_render_technology_meet_photo_script();
}

function gvspace_render_technology_meet_photo_script(): void
{
    static $rendered = false;
    if ($rendered) return;
    $rendered = true;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var root = document.querySelector('[data-gvspace-meet-photo]');
        if (!root || !window.wp || !wp.media) return;
        var input = root.querySelector('input[name="gvspace_technology_meet_photo"]');
        var previewWrap = root.querySelector('[data-gvspace-meet-photo-preview]');
        var preview = previewWrap ? previewWrap.querySelector('img') : null;
        var selectButton = root.querySelector('[data-gvspace-meet-photo-select]');
        var removeButton = root.querySelector('[data-gvspace-meet-photo-remove]');
        if (!input || !previewWrap || !preview || !selectButton || !removeButton) return;

        var thumbUrl = function (attachment) {
            if (attachment.sizes && attachment.sizes.medium) return attachment.sizes.medium.url;
            if (attachment.sizes && attachment.sizes.thumbnail) return attachment.sizes.thumbnail.url;
            return attachment.url;
        };

        var setPhoto = function (id, url) {
            input.value = id ? String(id) : '';
            preview.src = url || '';
            previewWrap.style.display = url ? '' : 'none';
            if (url) removeButton.removeAttribute('hidden');
            else removeButton.setAttribute('hidden', 'hidden');
        };

        selectButton.addEventListener('click', function (event) {
            event.preventDefault();
            var selectedId = parseInt(input.value, 10) || 0;
            var frame = wp.media({
                title: 'Фото експерта',
                button: { text: 'Обрати фото' },
                multiple: false,
                library: { type: 'image' }
            });
            frame.on('open', function () {
                if (!selectedId) return;
                var selection = frame.state().get('selection');
                var attachment = wp.media.attachment(selectedId);
                attachment.fetch();
                selection.reset([attachment]);
            });
            frame.on('select', function () {
                var attachment = frame.state().get('selection').first();
                if (!attachment) return;
                var data = attachment.toJSON();
                if (!data.id) return;
                setPhoto(data.id, thumbUrl(data));
            });
            frame.open();
        });

        removeButton.addEventListener('click', function (event) {
            event.preventDefault();
            setPhoto(0, '');
        });
    });
    </script>
    <?php
}

function gvspace_render_technology_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_technology', 'gvspace_technology_nonce');
    $stored_locale = gvspace_get_content_locale($post);
    $active_locale = array_key_exists($stored_locale, GVSPACE_CONTENT_LOCALES) ? $stored_locale : 'uk';
    $selected_tabs = wp_get_object_terms($post->ID, 'gv_technology_category', ['fields' => 'slugs']);
    if (!is_array($selected_tabs)) $selected_tabs = [];
    $tabs_url = admin_url('edit-tags.php?taxonomy=gv_technology_category&post_type=gv_technology');

    echo '<p class="description"><strong>Одна технологія — один запис.</strong> Іконку для каталогу і білого банера завантажте як «Головне зображення» (SVG або PNG). Назва технології є H1 на сторінці. Порядок картки задається у «Атрибути → Порядок». Оберіть мову та заповніть її переклад. Перемикання мови не перезавантажує сторінку й не видаляє вже введені тексти. Зразок заповнення — записи Next.js та Google Ads.</p>';
    echo '<input type="hidden" name="gvspace_technology_tabs_submitted" value="1">';
    echo '<p><strong>Таби на сайті</strong></p>';
    echo '<div style="display:flex;flex-wrap:wrap;gap:8px 18px;margin-bottom:8px">';
    foreach (gvspace_get_technology_tab_options() as $slug => $label) {
        echo '<label><input type="checkbox" name="gvspace_technology_tabs[]" value="'
            . esc_attr($slug) . '"'
            . checked(in_array($slug, $selected_tabs, true), true, false)
            . '> ' . esc_html($label) . '</label>';
    }
    echo '</div>';
    echo '<p><label for="gvspace_technology_tab_new">Або додайте новий таб</label><br>';
    echo '<input type="text" id="gvspace_technology_tab_new" name="gvspace_technology_tab_new" placeholder="Наприклад, AI" style="width:100%;max-width:420px"></p>';
    echo '<p class="description">Таб визначає, які послуги і кейси підтягнуться на сторінку: Розробка → IT-послуги та IT-кейси, Маркетинг → маркетингові тощо. Список табів також можна редагувати в <a href="' . esc_url($tabs_url) . '">Технології → Таби</a>. Таби спільні для всіх мов.</p>';
    $related_case = (string) get_post_meta($post->ID, '_gvspace_technology_related_case', true);
    echo '<p><label for="gvspace_technology_related_case"><strong>Кейс на сторінці технології</strong></label><br>';
    echo '<input type="text" id="gvspace_technology_related_case" name="gvspace_technology_related_case" value="' . esc_attr($related_case) . '" placeholder="detox-new-year" style="width:100%;max-width:420px"></p>';
    echo '<p class="description">Публічний slug кейсу, спільний для всіх мов. Якщо порожньо — підтягнемо кейс за табом технології (наприклад, IT-кейс для розробки).</p>';
    gvspace_render_technology_meet_photo_field($post);

    echo '<p><label for="gvspace-technology-language"><strong>Редагувати мовну версію</strong></label> ';
    echo '<select id="gvspace-technology-language" data-gvspace-language-select="technology-language">';
    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        echo '<option value="' . esc_attr($locale) . '"' . selected($active_locale, $locale, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select></p>';

    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        $title = (string) get_post_meta($post->ID, '_gvspace_technology_title_' . $locale, true);
        if ($title === '') {
            if ($locale === 'uk') $title = $post->post_title;
            if ($locale === 'en') $title = (string) get_post_meta($post->ID, '_gvspace_technology_title_en', true);
        }
        echo '<div data-gvspace-language-panel="technology-language" data-locale="' . esc_attr($locale) . '"' . ($locale === $active_locale ? '' : ' hidden') . '>';
        echo '<hr><h3>' . esc_html($label) . '</h3>';
        echo '<p><label for="gvspace_technology_title_' . esc_attr($locale) . '"><strong>Назва технології (H1 на сторінці)</strong></label><br>';
        echo '<input type="text" id="gvspace_technology_title_' . esc_attr($locale) . '" name="gvspace_technology_title_' . esc_attr($locale) . '" value="' . esc_attr($title) . '" style="width:100%"></p>';
        echo '<h4>Картка в каталозі</h4>';
        gvspace_render_field_set($post, GVSPACE_LOCALIZED_TECHNOLOGY_CARD_FIELDS, 'gvspace_technology_' . $locale . '_', '_gvspace_technology_', '_' . $locale);
        echo '<h4>Сторінка технології</h4>';
        gvspace_render_field_set($post, GVSPACE_LOCALIZED_TECHNOLOGY_PAGE_FIELDS, 'gvspace_technology_' . $locale . '_', '_gvspace_technology_', '_' . $locale);
        echo '</div>';
    }
    gvspace_render_language_switcher_script();
}

add_action('save_post_gv_technology', function (int $post_id): void {
    static $saving_title = false;
    if ($saving_title) return;
    if (
        !isset($_POST['gvspace_technology_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_technology_nonce'])), 'gvspace_save_technology')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    if (isset($_POST['gvspace_technology_tabs_submitted'])) {
        $tabs = [];
        if (isset($_POST['gvspace_technology_tabs']) && is_array($_POST['gvspace_technology_tabs'])) {
            foreach ($_POST['gvspace_technology_tabs'] as $slug) {
                $clean = sanitize_title(wp_unslash((string) $slug));
                if ($clean !== '') $tabs[] = $clean;
            }
        }
        $new_tab = sanitize_text_field(wp_unslash((string) ($_POST['gvspace_technology_tab_new'] ?? '')));
        if ($new_tab !== '') {
            $created = gvspace_ensure_technology_tab($new_tab);
            if ($created !== '') $tabs[] = $created;
        }
        wp_set_object_terms($post_id, array_values(array_unique($tabs)), 'gv_technology_category', false);
    }

    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        $title_field = 'gvspace_technology_title_' . $locale;
        if (isset($_POST[$title_field])) {
            update_post_meta($post_id, '_gvspace_technology_title_' . $locale, sanitize_text_field(wp_unslash($_POST[$title_field])));
        }
        foreach (array_keys(gvspace_localized_technology_fields()) as $field) {
            $field_name = 'gvspace_technology_' . $locale . '_' . $field;
            if (!isset($_POST[$field_name])) continue;
            update_post_meta($post_id, '_gvspace_technology_' . $field . '_' . $locale, sanitize_textarea_field(wp_unslash($_POST[$field_name])));
        }
    }

    if (isset($_POST['gvspace_technology_related_case'])) {
        update_post_meta($post_id, '_gvspace_technology_related_case', sanitize_title(wp_unslash($_POST['gvspace_technology_related_case'])));
    }
    if (isset($_POST['gvspace_technology_meet_photo'])) {
        $meet_photo = sanitize_text_field(wp_unslash($_POST['gvspace_technology_meet_photo']));
        if ($meet_photo !== '' && ctype_digit($meet_photo)) {
            $attachment_id = (int) $meet_photo;
            $meet_photo = get_post_type($attachment_id) === 'attachment' ? (string) $attachment_id : '';
        } elseif ($meet_photo !== '') {
            $meet_photo = esc_url_raw($meet_photo);
        }
        if ($meet_photo !== '') {
            update_post_meta($post_id, '_gvspace_technology_meet_photo', $meet_photo);
        } else {
            delete_post_meta($post_id, '_gvspace_technology_meet_photo');
        }
    }
    if (isset($_POST['gvspace_technology_visual'])) {
        update_post_meta($post_id, '_gvspace_technology_visual', esc_url_raw(wp_unslash($_POST['gvspace_technology_visual'])));
    }

    update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    if ((string) get_post_meta($post_id, '_gvspace_translation_group', true) === '') {
        $default_group = sanitize_title((string) get_post_field('post_name', $post_id) ?: (string) get_post_field('post_title', $post_id));
        update_post_meta($post_id, '_gvspace_translation_group', $default_group ?: 'technology-' . $post_id);
    }

    $uk_title = (string) get_post_meta($post_id, '_gvspace_technology_title_uk', true);
    if ($uk_title !== '' && get_post_field('post_title', $post_id) !== $uk_title) {
        $saving_title = true;
        wp_update_post(['ID' => $post_id, 'post_title' => $uk_title]);
        $saving_title = false;
    }
});

add_action('add_meta_boxes', function (): void {
    remove_meta_box('tagsdiv-gv_technology_category', 'gv_technology', 'side');
}, 20);

add_action('restrict_manage_posts', function (string $post_type): void {
    if ($post_type !== 'gv_technology') return;
    $selected = isset($_GET['gv_technology_tab']) ? sanitize_title(wp_unslash((string) $_GET['gv_technology_tab'])) : '';
    echo '<label for="gv_technology_tab" class="screen-reader-text">Фільтр за табом</label>';
    echo '<select name="gv_technology_tab" id="gv_technology_tab">';
    echo '<option value="">Усі таби</option>';
    foreach (gvspace_get_technology_tab_options() as $slug => $label) {
        echo '<option value="' . esc_attr($slug) . '"' . selected($selected, $slug, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select>';
});

add_action('pre_get_posts', function (WP_Query $query): void {
    if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'gv_technology') return;
    $tab = isset($_GET['gv_technology_tab']) ? sanitize_title(wp_unslash((string) $_GET['gv_technology_tab'])) : '';
    if ($tab === '') return;
    $tax_query = $query->get('tax_query');
    if (!is_array($tax_query)) $tax_query = [];
    $tax_query[] = [
        'taxonomy' => 'gv_technology_category',
        'field' => 'slug',
        'terms' => [$tab],
    ];
    $query->set('tax_query', $tax_query);
});

function gvspace_render_case_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_case', 'gvspace_case_nonce');
    $stored_locale = gvspace_get_content_locale($post);
    $active_locale = array_key_exists($stored_locale, GVSPACE_CONTENT_LOCALES) ? $stored_locale : 'uk';
    echo '<p class="description"><strong>Один кейс — один запис.</strong> Оберіть мову та заповніть її переклад. Обкладинка картки й банера — у «Головному зображенні». Галерея спільна для всіх мов.</p>';
    gvspace_render_case_filter_fields($post);
    gvspace_render_case_gallery_field($post);
    echo '<p><label for="gvspace-case-language"><strong>Редагувати мовну версію</strong></label> ';
    echo '<select id="gvspace-case-language" data-gvspace-language-select="case-language">';
    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        echo '<option value="' . esc_attr($locale) . '"' . selected($active_locale, $locale, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select></p>';
    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        $title = (string) get_post_meta($post->ID, '_gvspace_case_title_' . $locale, true);
        if ($title === '') {
            if ($locale === 'uk') $title = $post->post_title;
            if ($locale === 'en') $title = (string) get_post_meta($post->ID, '_gvspace_case_title_en', true);
        }
        echo '<div data-gvspace-language-panel="case-language" data-locale="' . esc_attr($locale) . '"' . ($locale === $active_locale ? '' : ' hidden') . '>';
        echo '<hr><h3>' . esc_html($label) . '</h3>';
        echo '<p><label for="gvspace_case_title_' . esc_attr($locale) . '"><strong>Назва кейсу на сторінці</strong></label><br>';
        echo '<input type="text" id="gvspace_case_title_' . esc_attr($locale) . '" name="gvspace_case_title_' . esc_attr($locale) . '" value="' . esc_attr($title) . '" style="width:100%"></p>';
        gvspace_render_field_set($post, GVSPACE_LOCALIZED_CASE_FIELDS, 'gvspace_case_' . $locale . '_', '_gvspace_case_', '_' . $locale);
        echo '</div>';
    }
    gvspace_render_language_switcher_script();
}

function gvspace_render_case_filter_fields(WP_Post $post): void
{
    $direction_terms = wp_get_object_terms($post->ID, 'gv_case_direction', ['fields' => 'slugs']);
    $type_terms = wp_get_object_terms($post->ID, 'gv_case_project_type', ['fields' => 'slugs']);
    $selected_direction = is_array($direction_terms) ? (string) ($direction_terms[0] ?? '') : '';
    $selected_type = is_array($type_terms) ? (string) ($type_terms[0] ?? '') : '';
    $directions_url = admin_url('edit-tags.php?taxonomy=gv_case_direction&post_type=gv_case');
    $types_url = admin_url('edit-tags.php?taxonomy=gv_case_project_type&post_type=gv_case');
    echo '<input type="hidden" name="gvspace_case_filters_submitted" value="1">';
    echo '<p><label for="gvspace_case_direction"><strong>Напрямок</strong></label><br>';
    echo '<select id="gvspace_case_direction" name="gvspace_case_direction" style="min-width:280px">';
    echo '<option value="">— оберіть напрямок —</option>';
    foreach (gvspace_get_case_direction_options() as $slug => $label) {
        echo '<option value="' . esc_attr($slug) . '"' . selected($selected_direction, $slug, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select></p>';
    echo '<p><label for="gvspace_case_direction_new">Або додайте новий напрямок</label><br>';
    echo '<input type="text" id="gvspace_case_direction_new" name="gvspace_case_direction_new" placeholder="Наприклад, МАРКЕТИНГ" style="width:100%;max-width:420px"></p>';
    echo '<p class="description">Список також можна редагувати в <a href="' . esc_url($directions_url) . '">Кейси → Напрямки</a>.</p>';
    echo '<p><label for="gvspace_case_project_type"><strong>Тип проєкту</strong></label><br>';
    echo '<select id="gvspace_case_project_type" name="gvspace_case_project_type" style="min-width:280px">';
    echo '<option value="">— оберіть тип проєкту —</option>';
    foreach (gvspace_get_case_project_type_options() as $slug => $label) {
        echo '<option value="' . esc_attr($slug) . '"' . selected($selected_type, $slug, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select></p>';
    echo '<p><label for="gvspace_case_project_type_new">Або додайте новий тип проєкту</label><br>';
    echo '<input type="text" id="gvspace_case_project_type_new" name="gvspace_case_project_type_new" placeholder="Наприклад, E-COMMERCE" style="width:100%;max-width:420px"></p>';
    echo '<p class="description">Список також можна редагувати в <a href="' . esc_url($types_url) . '">Кейси → Типи проєктів</a>.</p>';
}

function gvspace_render_case_gallery_field(WP_Post $post): void
{
    $ids = gvspace_case_gallery_attachment_ids($post->ID);
    echo '<div class="gvspace-case-gallery" data-gvspace-gallery>';
    echo '<p><strong>Галерея</strong></p>';
    echo '<input type="hidden" name="gvspace_case_gallery_ids" value="' . esc_attr(implode(',', $ids)) . '">';
    echo '<ul class="gvspace-case-gallery-list" data-gvspace-gallery-list style="display:flex;flex-wrap:wrap;gap:12px;margin:0 0 12px;padding:0;list-style:none">';
    foreach ($ids as $id) {
        $thumb = wp_get_attachment_image_url($id, 'medium') ?: wp_get_attachment_image_url($id, 'full');
        if (!$thumb) continue;
        echo '<li class="gvspace-case-gallery-item" data-id="' . esc_attr((string) $id) . '" style="width:140px;margin:0">';
        echo '<img src="' . esc_url($thumb) . '" alt="" style="display:block;width:140px;height:90px;object-fit:cover;border:1px solid #c3c4c7;border-radius:2px;background:#f0f0f1">';
        echo '<button type="button" class="button-link" data-gvspace-gallery-remove style="margin-top:4px">Видалити</button>';
        echo '</li>';
    }
    echo '</ul>';
    echo '<p><button type="button" class="button" data-gvspace-gallery-add>Додати фото з галереї</button></p>';
    echo '<p class="description">Оберіть зображення з медіатеки WordPress. Вони спільні для всіх мов. На сторінці кейсу показуються перші два фото.</p>';
    echo '</div>';
    gvspace_render_case_gallery_script();
}

function gvspace_render_case_gallery_script(): void
{
    static $rendered = false;
    if ($rendered) return;
    $rendered = true;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var root = document.querySelector('[data-gvspace-gallery]');
        if (!root || !window.wp || !wp.media) return;
        var input = root.querySelector('input[name="gvspace_case_gallery_ids"]');
        var list = root.querySelector('[data-gvspace-gallery-list]');
        var addButton = root.querySelector('[data-gvspace-gallery-add]');
        if (!input || !list || !addButton) return;

        var currentIds = function () {
            return (input.value || '').split(',').map(function (value) {
                return parseInt(value, 10);
            }).filter(Boolean);
        };

        var setIds = function (ids) {
            input.value = ids.join(',');
        };

        var thumbUrl = function (attachment) {
            if (attachment.sizes && attachment.sizes.medium) return attachment.sizes.medium.url;
            if (attachment.sizes && attachment.sizes.thumbnail) return attachment.sizes.thumbnail.url;
            return attachment.url;
        };

        addButton.addEventListener('click', function (event) {
            event.preventDefault();
            var frame = wp.media({
                title: 'Галерея кейсу',
                button: { text: 'Додати в кейс' },
                multiple: true,
                library: { type: 'image' }
            });
            frame.on('select', function () {
                var ids = currentIds();
                frame.state().get('selection').toJSON().forEach(function (attachment) {
                    if (!attachment.id || ids.indexOf(attachment.id) !== -1) return;
                    ids.push(attachment.id);
                    var item = document.createElement('li');
                    item.className = 'gvspace-case-gallery-item';
                    item.setAttribute('data-id', String(attachment.id));
                    item.style.cssText = 'width:140px;margin:0';
                    var image = document.createElement('img');
                    image.src = thumbUrl(attachment);
                    image.alt = '';
                    image.style.cssText = 'display:block;width:140px;height:90px;object-fit:cover;border:1px solid #c3c4c7;border-radius:2px;background:#f0f0f1';
                    var remove = document.createElement('button');
                    remove.type = 'button';
                    remove.className = 'button-link';
                    remove.setAttribute('data-gvspace-gallery-remove', '');
                    remove.style.marginTop = '4px';
                    remove.textContent = 'Видалити';
                    item.appendChild(image);
                    item.appendChild(remove);
                    list.appendChild(item);
                });
                setIds(ids);
            });
            frame.open();
        });

        list.addEventListener('click', function (event) {
            var button = event.target.closest('[data-gvspace-gallery-remove]');
            if (!button) return;
            event.preventDefault();
            var item = button.closest('.gvspace-case-gallery-item');
            if (!item) return;
            var id = parseInt(item.getAttribute('data-id'), 10);
            item.remove();
            setIds(currentIds().filter(function (value) { return value !== id; }));
        });
    });
    </script>
    <?php
}

add_action('save_post_gv_case', function (int $post_id): void {
    static $saving_title = false;
    if ($saving_title) return;
    if (!isset($_POST['gvspace_case_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_case_nonce'])), 'gvspace_save_case') || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['gvspace_case_gallery_ids'])) {
        $gallery_ids = array_values(array_unique(array_filter(array_map('absint', explode(',', (string) wp_unslash($_POST['gvspace_case_gallery_ids']))))));
        if ($gallery_ids || gvspace_case_gallery_attachment_ids($post_id)) {
            gvspace_save_case_gallery_ids($post_id, $gallery_ids);
        }
    }

    if (isset($_POST['gvspace_case_filters_submitted'])) {
        $direction_slug = sanitize_title(wp_unslash((string) ($_POST['gvspace_case_direction'] ?? '')));
        $new_direction = sanitize_text_field(wp_unslash((string) ($_POST['gvspace_case_direction_new'] ?? '')));
        if ($new_direction !== '') {
            $created = gvspace_ensure_case_term($new_direction, 'gv_case_direction');
            if ($created !== '') $direction_slug = $created;
        }
        wp_set_object_terms($post_id, $direction_slug !== '' ? [$direction_slug] : [], 'gv_case_direction', false);

        $type_slug = sanitize_title(wp_unslash((string) ($_POST['gvspace_case_project_type'] ?? '')));
        $new_type = sanitize_text_field(wp_unslash((string) ($_POST['gvspace_case_project_type_new'] ?? '')));
        if ($new_type !== '') {
            $created = gvspace_ensure_case_term($new_type, 'gv_case_project_type');
            if ($created !== '') $type_slug = $created;
        }
        wp_set_object_terms($post_id, $type_slug !== '' ? [$type_slug] : [], 'gv_case_project_type', false);
    }

    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        $title_field = 'gvspace_case_title_' . $locale;
        if (isset($_POST[$title_field])) {
            update_post_meta($post_id, '_gvspace_case_title_' . $locale, sanitize_text_field(wp_unslash($_POST[$title_field])));
        }
        foreach (array_keys(GVSPACE_LOCALIZED_CASE_FIELDS) as $field) {
            $field_name = 'gvspace_case_' . $locale . '_' . $field;
            if (!isset($_POST[$field_name])) continue;
            update_post_meta($post_id, '_gvspace_case_' . $field . '_' . $locale, sanitize_textarea_field(wp_unslash($_POST[$field_name])));
        }
    }

    update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    if ((string) get_post_meta($post_id, '_gvspace_translation_group', true) === '') {
        $default_group = sanitize_title((string) get_post_field('post_name', $post_id) ?: (string) get_post_field('post_title', $post_id));
        update_post_meta($post_id, '_gvspace_translation_group', $default_group ?: 'case-' . $post_id);
    }

    $uk_title = (string) get_post_meta($post_id, '_gvspace_case_title_uk', true);
    if ($uk_title !== '' && get_post_field('post_title', $post_id) !== $uk_title) {
        $saving_title = true;
        wp_update_post(['ID' => $post_id, 'post_title' => $uk_title]);
        $saving_title = false;
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
    add_meta_box('gvspace-service-details', 'Контент сторінки послуги', 'gvspace_render_service_fields', 'gv_service', 'normal', 'high');
});
function gvspace_render_service_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_service', 'gvspace_service_nonce');
    if ((string) get_post_meta($post->ID, '_gvspace_service_reference_template', true) === '1') {
        echo '<div class="notice notice-success inline"><p><strong>Еталонна L3-послуга.</strong> Використовуйте структуру та рівень деталізації цього запису як приклад для заповнення інших послуг.</p></div>';
    }
    echo '<p class="description"><strong>Одна послуга — один запис.</strong> Оберіть мову та заповніть її переклад. Перемикання мови не перезавантажує сторінку й не видаляє введений текст. Запис без батьківського елемента — напрямок (L2), дочірній — послуга (L3).</p>';
    $service_level = (int) $post->post_parent === 0 ? 'L2' : 'L3';
    echo '<div class="notice notice-info inline"><p><strong>Банер ' . esc_html($service_level) . ':</strong> фон є спільним і зберігається на сайті. Завантажте прозору 3D-іконку цієї сторінки в блоці «3D-іконка банера» праворуч. Рекомендований формат — WebP або PNG із прозорістю, приблизно 1000 × 800 px.</p></div>';
    echo '<p><label for="gvspace-service-language"><strong>Редагувати мовну версію</strong></label> ';
    echo '<select id="gvspace-service-language" data-gvspace-language-select="service-language">';
    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        echo '<option value="' . esc_attr($locale) . '">' . esc_html($label) . '</option>';
    }
    echo '</select></p>';
    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        $title = (string) get_post_meta($post->ID, '_gvspace_service_title_' . $locale, true);
        if ($locale === 'uk' && $title === '') $title = $post->post_title;
        echo '<div data-gvspace-language-panel="service-language" data-locale="' . esc_attr($locale) . '"' . ($locale === 'uk' ? '' : ' hidden') . '>';
        echo '<hr><h3>' . esc_html($label) . '</h3>';
        echo '<p><label for="gvspace_service_title_' . esc_attr($locale) . '"><strong>Назва послуги / напрямку</strong></label><br>';
        echo '<input type="text" id="gvspace_service_title_' . esc_attr($locale) . '" name="gvspace_service_title_' . esc_attr($locale) . '" value="' . esc_attr($title) . '" style="width:100%"></p>';
        gvspace_render_field_set($post, GVSPACE_LOCALIZED_SERVICE_FIELDS, 'gvspace_service_' . $locale . '_', '_gvspace_service_', '_' . $locale);
        echo '</div>';
    }
    gvspace_render_language_switcher_script();
}
add_action('save_post_gv_service', function (int $post_id): void {
    static $saving_title = false;
    if ($saving_title) return;
    if (!isset($_POST['gvspace_service_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_service_nonce'])), 'gvspace_save_service') || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) return;
    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        $title_field = 'gvspace_service_title_' . $locale;
        if (isset($_POST[$title_field])) {
            update_post_meta($post_id, '_gvspace_service_title_' . $locale, sanitize_text_field(wp_unslash($_POST[$title_field])));
        }
        foreach (array_keys(GVSPACE_LOCALIZED_SERVICE_FIELDS) as $field) {
            $field_name = 'gvspace_service_' . $locale . '_' . $field;
            if (!isset($_POST[$field_name])) continue;
            update_post_meta($post_id, '_gvspace_service_' . $field . '_' . $locale, sanitize_textarea_field(wp_unslash($_POST[$field_name])));
        }
    }
    $uk_title = (string) get_post_meta($post_id, '_gvspace_service_title_uk', true);
    if ($uk_title !== '' && get_post_field('post_title', $post_id) !== $uk_title) {
        $saving_title = true;
        wp_update_post(['ID' => $post_id, 'post_title' => $uk_title]);
        $saving_title = false;
    }
});

add_filter('admin_post_thumbnail_html', function (string $content, int $post_id): string {
    if (get_post_type($post_id) !== 'gv_service') return $content;
    $post = get_post($post_id);
    if (!$post) return $content;
    $service_level = (int) $post->post_parent === 0 ? 'L2' : 'L3';
    return $content . '<p class="description"><strong>3D-іконка банера ' . esc_html($service_level) . '.</strong><br>WebP або PNG із прозорим фоном, рекомендовано близько 1000 × 800 px. Фон банера додається сайтом автоматично.</p>';
}, 10, 2);

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
    $stored_locale = gvspace_get_content_locale($post);
    $active_locale = array_key_exists($stored_locale, GVSPACE_CONTENT_LOCALES) ? $stored_locale : 'uk';
    ?>
    <p>
        <label>
            <input type="checkbox" name="gvspace_hot" value="1" <?php checked($hot); ?>>
            Позначити вакансію як гарячу
        </label>
    </p>
    <?php gvspace_render_vacancy_filter_fields($post); ?>
    <p class="description"><strong>Одна вакансія — один запис.</strong> Оберіть мову та заповніть її переклад. Перемикання мови не перезавантажує сторінку й не видаляє введений текст. Банер вакансії є спільним і задається на сайті.</p>
    <p><label for="gvspace-vacancy-language"><strong>Редагувати мовну версію</strong></label>
    <select id="gvspace-vacancy-language" data-gvspace-language-select="vacancy-language">
    <?php foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) : ?>
        <option value="<?php echo esc_attr($locale); ?>" <?php selected($active_locale, $locale); ?>><?php echo esc_html($label); ?></option>
    <?php endforeach; ?>
    </select></p>
    <?php
    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        $title = (string) get_post_meta($post->ID, '_gvspace_vacancy_title_' . $locale, true);
        if ($title === '') {
            if ($locale === 'uk') $title = $post->post_title;
            if ($locale === 'en') $title = (string) get_post_meta($post->ID, '_gvspace_title_en', true);
        }
        echo '<div data-gvspace-language-panel="vacancy-language" data-locale="' . esc_attr($locale) . '"' . ($locale === $active_locale ? '' : ' hidden') . '>';
        echo '<hr><h3>' . esc_html($label) . '</h3>';
        echo '<p><label for="gvspace_vacancy_title_' . esc_attr($locale) . '"><strong>Назва вакансії</strong></label><br>';
        echo '<input type="text" id="gvspace_vacancy_title_' . esc_attr($locale) . '" name="gvspace_vacancy_title_' . esc_attr($locale) . '" value="' . esc_attr($title) . '" style="width:100%"></p>';
        gvspace_render_field_set($post, GVSPACE_LOCALIZED_VACANCY_FIELDS, 'gvspace_vacancy_' . $locale . '_', '_gvspace_vacancy_', '_' . $locale);
        echo '</div>';
    }
    gvspace_render_language_switcher_script();
}

function gvspace_render_vacancy_filter_fields(WP_Post $post): void
{
    $direction_terms = wp_get_object_terms($post->ID, 'gv_vacancy_direction', ['fields' => 'slugs']);
    $employment_terms = wp_get_object_terms($post->ID, 'gv_vacancy_employment', ['fields' => 'slugs']);
    $selected_direction = is_array($direction_terms) ? (string) ($direction_terms[0] ?? '') : '';
    $selected_employment = is_array($employment_terms) ? $employment_terms : [];
    $directions_url = admin_url('edit-tags.php?taxonomy=gv_vacancy_direction&post_type=gv_vacancy');
    $employment_url = admin_url('edit-tags.php?taxonomy=gv_vacancy_employment&post_type=gv_vacancy');
    echo '<input type="hidden" name="gvspace_vacancy_filters_submitted" value="1">';
    echo '<p><label for="gvspace_vacancy_direction"><strong>Напрямок</strong></label><br>';
    echo '<select id="gvspace_vacancy_direction" name="gvspace_vacancy_direction" style="min-width:280px">';
    echo '<option value="">— оберіть напрямок —</option>';
    foreach (gvspace_get_vacancy_direction_options() as $slug => $label) {
        echo '<option value="' . esc_attr($slug) . '"' . selected($selected_direction, $slug, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select></p>';
    echo '<p><label for="gvspace_vacancy_direction_new">Або додайте новий напрямок</label><br>';
    echo '<input type="text" id="gvspace_vacancy_direction_new" name="gvspace_vacancy_direction_new" placeholder="Наприклад, SEO" style="width:100%;max-width:420px"></p>';
    echo '<p class="description">Список напрямків також можна редагувати в <a href="' . esc_url($directions_url) . '">Вакансії → Напрямки</a>.</p>';
    echo '<p><strong>Вид зайнятості</strong></p>';
    echo '<div style="display:flex;flex-wrap:wrap;gap:8px 18px;margin-bottom:8px">';
    foreach (gvspace_get_vacancy_employment_options() as $slug => $label) {
        echo '<label><input type="checkbox" name="gvspace_vacancy_employment[]" value="'
            . esc_attr($slug) . '"'
            . checked(in_array($slug, $selected_employment, true), true, false)
            . '> ' . esc_html($label) . '</label>';
    }
    echo '</div>';
    echo '<p><label for="gvspace_vacancy_employment_new">Або додайте новий вид зайнятості</label><br>';
    echo '<input type="text" id="gvspace_vacancy_employment_new" name="gvspace_vacancy_employment_new" placeholder="Наприклад, HYBRID" style="width:100%;max-width:420px"></p>';
    echo '<p class="description">Список видів зайнятості також можна редагувати в <a href="' . esc_url($employment_url) . '">Вакансії → Зайнятість</a>. Ці значення спільні для всіх мов і з’являються у фільтрах на сайті.</p>';
}

add_action('save_post_gv_vacancy', function (int $post_id): void {
    static $saving_title = false;
    if ($saving_title) return;
    if (
        !isset($_POST['gvspace_vacancy_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_vacancy_nonce'])), 'gvspace_save_vacancy')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    update_post_meta($post_id, '_gvspace_hot', isset($_POST['gvspace_hot']));
    if (isset($_POST['gvspace_vacancy_filters_submitted'])) {
        $direction_slug = sanitize_title(wp_unslash((string) ($_POST['gvspace_vacancy_direction'] ?? '')));
        $new_direction = sanitize_text_field(wp_unslash((string) ($_POST['gvspace_vacancy_direction_new'] ?? '')));
        if ($new_direction !== '') {
            $created = gvspace_ensure_vacancy_term($new_direction, 'gv_vacancy_direction');
            if ($created !== '') $direction_slug = $created;
        }
        wp_set_object_terms($post_id, $direction_slug !== '' ? [$direction_slug] : [], 'gv_vacancy_direction', false);

        $employment_slugs = [];
        if (isset($_POST['gvspace_vacancy_employment']) && is_array($_POST['gvspace_vacancy_employment'])) {
            foreach ($_POST['gvspace_vacancy_employment'] as $slug) {
                $clean = sanitize_title(wp_unslash((string) $slug));
                if ($clean !== '') $employment_slugs[] = $clean;
            }
        }
        $new_employment = sanitize_text_field(wp_unslash((string) ($_POST['gvspace_vacancy_employment_new'] ?? '')));
        if ($new_employment !== '') {
            $created = gvspace_ensure_vacancy_term($new_employment, 'gv_vacancy_employment');
            if ($created !== '') $employment_slugs[] = $created;
        }
        wp_set_object_terms($post_id, array_values(array_unique($employment_slugs)), 'gv_vacancy_employment', false);
        gvspace_sync_vacancy_tags_meta($post_id);
    }
    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        $title_field = 'gvspace_vacancy_title_' . $locale;
        if (isset($_POST[$title_field])) {
            update_post_meta($post_id, '_gvspace_vacancy_title_' . $locale, sanitize_text_field(wp_unslash($_POST[$title_field])));
        }
        foreach (array_keys(GVSPACE_LOCALIZED_VACANCY_FIELDS) as $field) {
            $field_name = 'gvspace_vacancy_' . $locale . '_' . $field;
            if (!isset($_POST[$field_name])) continue;
            update_post_meta($post_id, '_gvspace_vacancy_' . $field . '_' . $locale, sanitize_textarea_field(wp_unslash($_POST[$field_name])));
        }
    }

    update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    if ((string) get_post_meta($post_id, '_gvspace_translation_group', true) === '') {
        $default_group = sanitize_title((string) get_post_field('post_name', $post_id) ?: (string) get_post_field('post_title', $post_id));
        update_post_meta($post_id, '_gvspace_translation_group', $default_group ?: 'vacancy-' . $post_id);
    }

    $uk_title = (string) get_post_meta($post_id, '_gvspace_vacancy_title_uk', true);
    if ($uk_title !== '' && get_post_field('post_title', $post_id) !== $uk_title) {
        $saving_title = true;
        wp_update_post(['ID' => $post_id, 'post_title' => $uk_title]);
        $saving_title = false;
    }
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

    foreach (['Post', 'Vacancy', 'ProjectCase', 'ServiceOffering', 'ClientReview', 'Technology', 'TeamMember', 'FaqItem', 'HomeSeoText', 'PrivacyPolicy', 'TermsOfUse', 'ContactsPage'] as $graphql_type) {
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
                $requested_locale = isset($args['locale']) ? gvspace_sanitize_content_locale((string) $args['locale']) : 'uk';
                if ($requested_locale === 'legacy') $requested_locale = 'uk';
                if (in_array($post->post_type, GVSPACE_CENTRALIZED_POST_TYPES, true)) {
                    $suffix = '_' . $requested_locale;
                } elseif ($content_locale === 'legacy') {
                    $suffix = '_' . ($requested_locale === 'en' ? 'en' : 'uk');
                } else {
                    $suffix = '';
                }
                $value = static fn (string $key): string => trim((string) get_post_meta($post_id, '_gvspace_seo_' . $key . $suffix, true));
                if (in_array($post->post_type, GVSPACE_CENTRALIZED_POST_TYPES, true) && $requested_locale !== 'uk') {
                    $uk_value = static fn (string $key): string => trim((string) get_post_meta($post_id, '_gvspace_seo_' . $key . '_uk', true));
                } else {
                    $uk_value = static fn (string $key): string => '';
                }

                $fallback_description = trim((string) $post->post_excerpt);
                if ($fallback_description === '') {
                    $fallback_description = wp_trim_words(wp_strip_all_tags(strip_shortcodes((string) $post->post_content)), 30, '…');
                }
                $content_title = (string) get_the_title($post_id);
                $h1_fallback = $content_title;
                if ($post->post_type === 'gv_vacancy') {
                    $vacancy_title = trim((string) get_post_meta($post_id, '_gvspace_vacancy_title_' . $requested_locale, true));
                    if ($vacancy_title === '' && $requested_locale !== 'uk') {
                        $vacancy_title = trim((string) get_post_meta($post_id, '_gvspace_vacancy_title_uk', true));
                    }
                    if ($vacancy_title !== '') {
                        $content_title = $vacancy_title;
                        $h1_fallback = $vacancy_title;
                    }
                    $vacancy_excerpt = trim((string) get_post_meta($post_id, '_gvspace_vacancy_excerpt_' . $requested_locale, true));
                    if ($vacancy_excerpt !== '') $fallback_description = $vacancy_excerpt;
                }
                if ($post->post_type === 'gv_case') {
                    $case_title = trim((string) get_post_meta($post_id, '_gvspace_case_title_' . $requested_locale, true));
                    if ($case_title === '' && $requested_locale !== 'uk') {
                        $case_title = trim((string) get_post_meta($post_id, '_gvspace_case_title_uk', true));
                    }
                    if ($case_title !== '') {
                        $content_title = $case_title;
                        $h1_fallback = $case_title;
                    }
                    $case_excerpt = trim((string) get_post_meta($post_id, '_gvspace_case_excerpt_' . $requested_locale, true));
                    if ($case_excerpt === '') $case_excerpt = trim((string) get_post_meta($post_id, '_gvspace_case_excerpt_uk', true));
                    if ($case_excerpt !== '') $fallback_description = $case_excerpt;
                }
                if ($post->post_type === 'gv_service') {
                    $service_title = trim((string) get_post_meta($post_id, '_gvspace_service_title_' . $requested_locale, true));
                    $service_headline = trim((string) get_post_meta($post_id, '_gvspace_service_headline_' . $requested_locale, true));
                    if ($service_title !== '') $content_title = $service_title;
                    if ($service_headline !== '') $h1_fallback = $service_headline;
                }
                if ($post->post_type === 'gv_technology') {
                    $technology_title = trim(gvspace_technology_locale_field($post_id, 'title', $requested_locale));
                    $technology_intro = trim(gvspace_technology_locale_field($post_id, 'intro', $requested_locale));
                    $technology_description = trim(gvspace_technology_locale_field($post_id, 'description', $requested_locale));
                    if ($technology_title !== '') {
                        $content_title = $technology_title;
                        $h1_fallback = $technology_title;
                    }
                    if ($technology_intro !== '') $fallback_description = $technology_intro;
                    elseif ($technology_description !== '') $fallback_description = $technology_description;
                }
                if ($post->post_type === 'gv_contacts_page') {
                    $contacts_title = trim((string) get_post_meta($post_id, '_gvspace_contacts_title_' . $requested_locale, true));
                    $contacts_intro = trim((string) get_post_meta($post_id, '_gvspace_contacts_intro_' . $requested_locale, true));
                    if ($contacts_title === '' && $requested_locale !== 'uk') {
                        $contacts_title = trim((string) get_post_meta($post_id, '_gvspace_contacts_title_uk', true));
                    }
                    if ($contacts_intro === '' && $requested_locale !== 'uk') {
                        $contacts_intro = trim((string) get_post_meta($post_id, '_gvspace_contacts_intro_uk', true));
                    }
                    if ($contacts_title !== '') {
                        $content_title = $contacts_title;
                        $h1_fallback = $contacts_title;
                    }
                    if ($contacts_intro !== '') $fallback_description = $contacts_intro;
                }
                if ($post->post_type === 'gv_privacy_policy') {
                    $privacy_locale = $requested_locale === 'en' ? 'en' : 'uk';
                    $privacy_title = trim((string) get_post_meta($post_id, '_gvspace_privacy_title_' . $privacy_locale, true));
                    if ($privacy_title !== '') {
                        $content_title = $privacy_title;
                        $h1_fallback = $privacy_title;
                    }
                }
                if ($post->post_type === 'gv_terms_of_use') {
                    $terms_locale = $requested_locale === 'en' ? 'en' : 'uk';
                    $terms_title = trim((string) get_post_meta($post_id, '_gvspace_terms_title_' . $terms_locale, true));
                    if ($terms_title !== '') {
                        $content_title = $terms_title;
                        $h1_fallback = $terms_title;
                    }
                }
                $title = $value('title') ?: $uk_value('title') ?: $content_title;
                $description = $value('description') ?: $uk_value('description') ?: $fallback_description;
                $featured_image = get_post_thumbnail_id($post_id);
                $featured_image_url = $featured_image ? (string) wp_get_attachment_image_url($featured_image, 'full') : '';

                return [
                    'title' => $title,
                    'description' => $description,
                    'h1' => $value('h1') ?: $uk_value('h1') ?: $h1_fallback,
                    'openGraphTitle' => $value('og_title') ?: $uk_value('og_title') ?: $title,
                    'openGraphDescription' => $value('og_description') ?: $uk_value('og_description') ?: $description,
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
            return gvspace_technology_locale_field((int) $source->databaseId, 'title', 'en');
        },
    ]);

    register_graphql_object_type('GvspaceTechnologyDetails', [
        'description' => 'Localized technology card and page fields.',
        'fields' => [
            'title' => ['type' => 'String'],
            'description' => ['type' => 'String'],
            'tag' => ['type' => 'String'],
            'headline' => ['type' => 'String'],
            'intro' => ['type' => 'String'],
            'why' => ['type' => 'String'],
            'triggers' => ['type' => 'String'],
            'uses' => ['type' => 'String'],
            'stats' => ['type' => 'String'],
            'benefits' => ['type' => 'String'],
            'faq' => ['type' => 'String'],
            'seoLead' => ['type' => 'String'],
            'seoText' => ['type' => 'String'],
            'meetName' => ['type' => 'String'],
            'meetRole' => ['type' => 'String'],
            'meetQuote' => ['type' => 'String'],
            'meetYears' => ['type' => 'String'],
            'meetProjects' => ['type' => 'String'],
            'meetTags' => ['type' => 'String'],
        ],
    ]);
    register_graphql_field('Technology', 'technologyDetails', [
        'type' => 'GvspaceTechnologyDetails',
        'args' => ['locale' => ['type' => 'String', 'defaultValue' => 'uk']],
        'resolve' => static function ($source, array $args): array {
            $post_id = (int) $source->databaseId;
            $requested_locale = gvspace_sanitize_content_locale((string) ($args['locale'] ?? 'uk'));
            $locale = $requested_locale === 'legacy' ? 'uk' : $requested_locale;
            $intro = gvspace_technology_locale_field($post_id, 'intro', $locale);
            if ($intro === '') $intro = gvspace_technology_locale_field($post_id, 'body', $locale);
            $why = gvspace_technology_locale_field($post_id, 'why', $locale);
            if ($why === '') $why = gvspace_technology_locale_field($post_id, 'headline', $locale);
            $triggers = gvspace_technology_locale_field($post_id, 'triggers', $locale);
            if ($triggers === '') $triggers = gvspace_technology_locale_field($post_id, 'benefits', $locale);
            return [
                'title' => gvspace_technology_locale_field($post_id, 'title', $locale),
                'description' => gvspace_technology_locale_field($post_id, 'description', $locale),
                'tag' => gvspace_technology_locale_field($post_id, 'tag', $locale),
                'headline' => gvspace_technology_locale_field($post_id, 'headline', $locale),
                'intro' => $intro,
                'why' => $why,
                'triggers' => $triggers,
                'uses' => gvspace_technology_locale_field($post_id, 'uses', $locale),
                'stats' => gvspace_technology_locale_field($post_id, 'stats', $locale),
                'benefits' => gvspace_technology_locale_field($post_id, 'benefits', $locale),
                'faq' => gvspace_technology_locale_field($post_id, 'faq', $locale),
                'seoLead' => gvspace_technology_locale_field($post_id, 'seo_lead', $locale),
                'seoText' => gvspace_technology_locale_field($post_id, 'seo_text', $locale),
                'meetName' => gvspace_technology_locale_field($post_id, 'meet_name', $locale),
                'meetRole' => gvspace_technology_locale_field($post_id, 'meet_role', $locale),
                'meetQuote' => gvspace_technology_locale_field($post_id, 'meet_quote', $locale),
                'meetYears' => gvspace_technology_locale_field($post_id, 'meet_years', $locale),
                'meetProjects' => gvspace_technology_locale_field($post_id, 'meet_projects', $locale),
                'meetTags' => gvspace_technology_locale_field($post_id, 'meet_tags', $locale),
            ];
        },
    ]);
    register_graphql_field('Technology', 'relatedCase', [
        'type' => 'String',
        'resolve' => static function ($source): string {
            return (string) get_post_meta((int) $source->databaseId, '_gvspace_technology_related_case', true);
        },
    ]);
    register_graphql_field('Technology', 'visual', [
        'type' => 'String',
        'resolve' => static function ($source): string {
            return (string) get_post_meta((int) $source->databaseId, '_gvspace_technology_visual', true);
        },
    ]);
    register_graphql_field('Technology', 'meetPhoto', [
        'type' => 'String',
        'resolve' => static function ($source): string {
            return gvspace_technology_meet_photo_url((int) $source->databaseId);
        },
    ]);
    register_graphql_field('TechnologyCategory', 'localizedName', [
        'type' => 'String',
        'args' => ['locale' => ['type' => 'String', 'defaultValue' => 'uk']],
        'resolve' => static function ($source, array $args): string {
            $term_id = (int) ($source->term_id ?? $source->databaseId ?? 0);
            $term = get_term($term_id, 'gv_technology_category');
            if (!$term instanceof WP_Term) return '';
            $requested_locale = gvspace_sanitize_content_locale((string) ($args['locale'] ?? 'uk'));
            $locale = $requested_locale === 'legacy' ? 'uk' : $requested_locale;
            return gvspace_technology_tab_name($term, $locale);
        },
    ]);
    register_graphql_field('TechnologyCategory', 'menuOrder', [
        'type' => 'Int',
        'resolve' => static function ($source): int {
            $term_id = (int) ($source->term_id ?? $source->databaseId ?? 0);
            return (int) get_term_meta($term_id, '_gvspace_order', true);
        },
    ]);

    register_graphql_object_type('GvspaceTeamMemberDetails', [
        'description' => 'Editable fields displayed on a GVSPACE team card.',
        'fields' => [
            'name' => ['type' => 'String'],
            'role' => ['type' => 'String'],
            'tags' => ['type' => ['list_of' => 'String']],
        ],
    ]);
    register_graphql_field('TeamMember', 'teamMemberDetails', [
        'type' => 'GvspaceTeamMemberDetails',
        'args' => ['locale' => ['type' => 'String', 'defaultValue' => 'uk']],
        'resolve' => static function ($source, array $args): array {
            $post_id = (int) $source->databaseId;
            $requested_locale = gvspace_sanitize_content_locale((string) ($args['locale'] ?? 'uk'));
            $locale = $requested_locale === 'legacy' ? 'uk' : $requested_locale;
            $name = (string) get_post_meta($post_id, '_gvspace_team_member_title_' . $locale, true);
            $role = (string) get_post_meta($post_id, '_gvspace_team_member_role_' . $locale, true);
            $tags = (string) get_post_meta($post_id, '_gvspace_team_member_tags_' . $locale, true);
            if ($name === '') $name = (string) get_the_title($post_id);
            if ($role === '') $role = (string) get_post_meta($post_id, '_gvspace_team_member_role', true);
            if ($tags === '') $tags = (string) get_post_meta($post_id, '_gvspace_team_member_tags', true);
            return [
                'name' => $name,
                'role' => $role,
                'tags' => gvspace_split_meta_lines($tags),
            ];
        },
    ]);

    register_graphql_object_type('GvspacePartnerDetails', [
        'description' => 'Editable fields displayed on a GVSPACE partner card.',
        'fields' => [
            'directionUk' => ['type' => 'String'],
            'directionEn' => ['type' => 'String'],
        ],
    ]);
    register_graphql_field('Partner', 'partnerDetails', [
        'type' => 'GvspacePartnerDetails',
        'resolve' => static function ($source): array {
            return [
                'directionUk' => (string) get_post_meta((int) $source->databaseId, '_gvspace_partner_direction_uk', true)
                    ?: (string) get_post_meta((int) $source->databaseId, '_gvspace_partner_direction', true),
                'directionEn' => (string) get_post_meta((int) $source->databaseId, '_gvspace_partner_direction_en', true)
                    ?: (string) get_post_meta((int) $source->databaseId, '_gvspace_partner_direction', true),
            ];
        },
    ]);

    register_graphql_field('FaqItem', 'faqPlacement', [
        'type' => 'String',
        'resolve' => static function ($source): string {
            return (string) get_post_meta((int) $source->databaseId, '_gvspace_faq_placement', true) ?: 'home';
        },
    ]);
    register_graphql_object_type('GvspaceFaqDetails', [
        'description' => 'Localized question and answer for a GVSPACE FAQ item.',
        'fields' => [
            'question' => ['type' => 'String'],
            'answer' => ['type' => 'String'],
        ],
    ]);
    register_graphql_field('FaqItem', 'faqDetails', [
        'type' => 'GvspaceFaqDetails',
        'args' => ['locale' => ['type' => 'String', 'defaultValue' => 'uk']],
        'resolve' => static function ($source, array $args): array {
            $post_id = (int) $source->databaseId;
            $requested_locale = gvspace_sanitize_content_locale((string) ($args['locale'] ?? 'uk'));
            $locale = $requested_locale === 'legacy' ? 'uk' : $requested_locale;
            $question = (string) get_post_meta($post_id, '_gvspace_faq_question_' . $locale, true);
            $answer = (string) get_post_meta($post_id, '_gvspace_faq_answer_' . $locale, true);
            if ($question === '') $question = (string) get_post_meta($post_id, '_gvspace_faq_question_uk', true);
            if ($answer === '') $answer = (string) get_post_meta($post_id, '_gvspace_faq_answer_uk', true);
            if ($question === '') $question = (string) get_the_title($post_id);
            if ($answer === '') $answer = wp_strip_all_tags((string) get_post_field('post_content', $post_id));
            return ['question' => $question, 'answer' => $answer];
        },
    ]);
    register_graphql_object_type('GvspaceHomeSeoTextDetails', [
        'description' => 'Localized homepage SEO text.',
        'fields' => [
            'title' => ['type' => 'String'],
            'content' => ['type' => 'String'],
        ],
    ]);
    register_graphql_field('HomeSeoText', 'homeSeoTextDetails', [
        'type' => 'GvspaceHomeSeoTextDetails',
        'args' => ['locale' => ['type' => 'String', 'defaultValue' => 'uk']],
        'resolve' => static function ($source, array $args): array {
            $post_id = (int) $source->databaseId;
            $requested_locale = gvspace_sanitize_content_locale((string) ($args['locale'] ?? 'uk'));
            $locale = $requested_locale === 'legacy' ? 'uk' : $requested_locale;
            $title = (string) get_post_meta($post_id, '_gvspace_home_seo_title_' . $locale, true);
            $content = (string) get_post_meta($post_id, '_gvspace_home_seo_content_' . $locale, true);
            if ($title === '') $title = (string) get_post_meta($post_id, '_gvspace_home_seo_title_uk', true);
            if ($content === '') $content = (string) get_post_meta($post_id, '_gvspace_home_seo_content_uk', true);
            if ($title === '') $title = (string) get_the_title($post_id);
            if ($content === '') $content = wp_strip_all_tags((string) get_post_field('post_content', $post_id));
            return ['title' => $title, 'content' => $content];
        },
    ]);

    register_graphql_object_type('VacancyDetails', [
        'description' => 'Editable GVSPACE vacancy fields.',
        'fields' => [
            'title' => ['type' => 'String'],
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
            'direction' => ['type' => 'String'],
            'employmentTags' => ['type' => ['list_of' => 'String']],
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
        'args' => ['locale' => ['type' => 'String', 'defaultValue' => 'uk']],
        'resolve' => static function ($source, array $args): array {
            $post_id = (int) $source->databaseId;
            $requested_locale = gvspace_sanitize_content_locale((string) ($args['locale'] ?? 'uk'));
            $locale = $requested_locale === 'legacy' ? 'uk' : $requested_locale;
            $field = static fn (string $key): string => gvspace_vacancy_locale_field($post_id, $key, $locale);
            $lines = static fn (string $key): array => gvspace_split_meta_lines($field($key));

            return [
                'title' => $field('title'),
                'excerpt' => $field('excerpt'),
                'role' => $lines('role'),
                'tasks' => $lines('tasks'),
                'requirements' => $lines('requirements'),
                'benefits' => $lines('benefits'),
                'titleEn' => gvspace_vacancy_locale_field($post_id, 'title', 'en'),
                'excerptUk' => gvspace_vacancy_locale_field($post_id, 'excerpt', 'uk'),
                'excerptEn' => gvspace_vacancy_locale_field($post_id, 'excerpt', 'en'),
                'salary' => $field('salary'),
                'hot' => (bool) get_post_meta($post_id, '_gvspace_hot', true),
                'tags' => gvspace_vacancy_filter_tags($post_id) ?: $lines('tags'),
                'direction' => gvspace_vacancy_direction_name($post_id),
                'employmentTags' => gvspace_vacancy_employment_names($post_id),
                'roleUk' => gvspace_split_meta_lines(gvspace_vacancy_locale_field($post_id, 'role', 'uk')),
                'roleEn' => gvspace_split_meta_lines(gvspace_vacancy_locale_field($post_id, 'role', 'en')),
                'tasksUk' => gvspace_split_meta_lines(gvspace_vacancy_locale_field($post_id, 'tasks', 'uk')),
                'tasksEn' => gvspace_split_meta_lines(gvspace_vacancy_locale_field($post_id, 'tasks', 'en')),
                'requirementsUk' => gvspace_split_meta_lines(gvspace_vacancy_locale_field($post_id, 'requirements', 'uk')),
                'requirementsEn' => gvspace_split_meta_lines(gvspace_vacancy_locale_field($post_id, 'requirements', 'en')),
                'tools' => $lines('tools'),
                'benefitsUk' => gvspace_split_meta_lines(gvspace_vacancy_locale_field($post_id, 'benefits', 'uk')),
                'benefitsEn' => gvspace_split_meta_lines(gvspace_vacancy_locale_field($post_id, 'benefits', 'en')),
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
    register_graphql_object_type('GvspaceCasePerson', ['fields' => [
        'name' => ['type' => 'String'],
        'role' => ['type' => 'String'],
        'photo' => ['type' => 'String'],
    ]]);
    register_graphql_object_type('GvspaceCaseDetails', [
        'fields' => [
            'title' => ['type' => 'String'],
            'catalogTitle' => ['type' => 'String'],
            'excerpt' => ['type' => 'String'],
            'result' => ['type' => 'String'],
            'services' => ['type' => ['list_of' => 'String']],
            'metrics' => ['type' => ['list_of' => 'GvspaceCaseMetric']],
            'challenge' => ['type' => 'String'],
            'problems' => ['type' => ['list_of' => 'String']],
            'discovery' => ['type' => 'String'],
            'discoveryResult' => ['type' => 'String'],
            'step1' => ['type' => 'String'],
            'step1Result' => ['type' => 'GvspaceCaseVector'],
            'step2' => ['type' => 'String'],
            'architecture' => ['type' => ['list_of' => 'GvspaceCaseVector']],
            'step3' => ['type' => 'String'],
            'step3Result' => ['type' => 'GvspaceCaseVector'],
            'gallery' => ['type' => ['list_of' => 'String']],
            'tasks' => ['type' => ['list_of' => 'String']],
            'documents' => ['type' => ['list_of' => 'String']],
            'team' => ['type' => ['list_of' => 'GvspaceCasePerson']],
            'testimonial' => ['type' => 'String'],
            'testimonialAuthor' => ['type' => 'String'],
            'testimonialCompany' => ['type' => 'String'],
            'projectType' => ['type' => 'String'],
            'industry' => ['type' => 'String'],
            'direction' => ['type' => 'String'],
            'badge' => ['type' => 'String'],
        ],
    ]);
    register_graphql_field('ProjectCase', 'caseDetails', [
        'type' => 'GvspaceCaseDetails',
        'args' => ['locale' => ['type' => 'String', 'defaultValue' => 'uk']],
        'resolve' => static function ($source, array $args): array {
            $post_id = (int) $source->databaseId;
            $requested_locale = gvspace_sanitize_content_locale((string) ($args['locale'] ?? 'uk'));
            $locale = $requested_locale === 'legacy' ? 'uk' : $requested_locale;
            $field = static fn (string $key): string => gvspace_case_locale_field($post_id, $key, $locale);
            $lines = static fn (string $key): array => gvspace_split_meta_lines($field($key));
            $pairs = static function (string $key, string $first, string $second) use ($lines): array {
                return array_map(static function (string $line) use ($first, $second): array {
                    $parts = array_map('trim', explode('|', $line, 2));
                    return [$first => $parts[0] ?? '', $second => $parts[1] ?? ''];
                }, $lines($key));
            };
            $pair = static function (string $key) use ($field): array {
                $parts = array_map('trim', explode('|', $field($key), 2));
                return ['title' => $parts[0] ?? '', 'description' => $parts[1] ?? ''];
            };
            $team = array_map(static function (string $line): array {
                $parts = array_map('trim', explode('|', $line, 3));
                return ['name' => $parts[0] ?? '', 'role' => $parts[1] ?? '', 'photo' => $parts[2] ?? ''];
            }, $lines('team'));
            $direction = gvspace_case_term_name($post_id, 'gv_case_direction');
            $project_type = gvspace_case_term_name($post_id, 'gv_case_project_type');
            $gallery = gvspace_case_gallery_urls($post_id);
            if (!$gallery) $gallery = $lines('gallery');
            $excerpt = $field('excerpt') ?: $field('result');
            return [
                'title' => $field('title'),
                'catalogTitle' => $field('catalog_title') ?: $field('title'),
                'excerpt' => $excerpt,
                'result' => $excerpt,
                'services' => $direction !== '' ? [$direction] : $lines('services'),
                'metrics' => $pairs('metrics', 'value', 'label'),
                'challenge' => $field('challenge'),
                'problems' => $lines('problems'),
                'discovery' => $field('step1') ?: $field('discovery'),
                'discoveryResult' => $field('step1_result') ?: $field('discovery_result'),
                'step1' => $field('step1') ?: $field('discovery'),
                'step1Result' => $pair('step1_result'),
                'step2' => $field('step2'),
                'architecture' => $pairs('architecture', 'title', 'description'),
                'step3' => $field('step3'),
                'step3Result' => $pair('step3_result'),
                'gallery' => $gallery,
                'tasks' => $lines('tasks'),
                'documents' => $lines('documents'),
                'team' => $team,
                'testimonial' => $field('testimonial'),
                'testimonialAuthor' => $field('testimonial_author'),
                'testimonialCompany' => $field('testimonial_company'),
                'projectType' => $project_type ?: $field('project_type'),
                'industry' => $field('industry'),
                'direction' => $direction,
                'badge' => $direction ?: $field('badge'),
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
    register_graphql_object_type('GvspaceServiceFitCard', ['fields' => ['label' => ['type' => 'String'], 'title' => ['type' => 'String'], 'description' => ['type' => 'String']]]);
    register_graphql_object_type('GvspaceServiceFaq', ['fields' => ['question' => ['type' => 'String'], 'answer' => ['type' => 'String']]]);
    register_graphql_object_type('GvspaceServiceDetails', ['fields' => [
        'title' => ['type' => 'String'], 'headline' => ['type' => 'String'], 'description' => ['type' => 'String'],
        'order' => ['type' => 'Int'], 'fitCards' => ['type' => ['list_of' => 'GvspaceServiceFitCard']],
        'includes' => ['type' => ['list_of' => 'String']], 'steps' => ['type' => ['list_of' => 'GvspaceServiceStep']],
        'faq' => ['type' => ['list_of' => 'GvspaceServiceFaq']],
        'titleEn' => ['type' => 'String'], 'headlineUk' => ['type' => 'String'], 'headlineEn' => ['type' => 'String'],
        'descriptionUk' => ['type' => 'String'], 'descriptionEn' => ['type' => 'String'],
        'includesUk' => ['type' => ['list_of' => 'String']], 'includesEn' => ['type' => ['list_of' => 'String']],
        'stepsUk' => ['type' => ['list_of' => 'GvspaceServiceStep']], 'stepsEn' => ['type' => ['list_of' => 'GvspaceServiceStep']],
        'metrics' => ['type' => ['list_of' => 'String']], 'faqUk' => ['type' => ['list_of' => 'GvspaceServiceFaq']], 'faqEn' => ['type' => ['list_of' => 'GvspaceServiceFaq']],
    ]]);
    register_graphql_field('ServiceOffering', 'serviceDetails', [
        'type' => 'GvspaceServiceDetails',
        'args' => ['locale' => ['type' => 'String', 'defaultValue' => 'uk']],
        'resolve' => static function ($source, array $args): array {
        $id = (int) $source->databaseId;
        $requested_locale = gvspace_sanitize_content_locale((string) ($args['locale'] ?? 'uk'));
        $service_locale = $requested_locale === 'legacy' ? 'uk' : $requested_locale;
        $value = static fn (string $key): string => (string) get_post_meta($id, '_gvspace_service_' . $key, true);
        $localizedValue = static fn (string $key): string => (string) get_post_meta($id, '_gvspace_service_' . $key . '_' . $service_locale, true);
        $lines = static fn (string $key): array => array_values(array_filter(array_map('trim', preg_split('/\R/u', $value($key)) ?: [])));
        $localizedLines = static fn (string $key): array => array_values(array_filter(array_map('trim', preg_split('/\R/u', $localizedValue($key)) ?: [])));
        $steps = static fn (string $key): array => array_map(static function ($line): array { $p = array_map('trim', explode('|', $line, 3)); return ['title' => $p[0] ?? '', 'duration' => $p[1] ?? '', 'description' => $p[2] ?? '']; }, $lines($key));
        $localizedSteps = static fn (): array => array_map(static function ($line): array { $p = array_map('trim', explode('|', $line, 3)); return ['title' => $p[0] ?? '', 'duration' => $p[1] ?? '', 'description' => $p[2] ?? '']; }, $localizedLines('steps'));
        $localizedFitCards = static fn (): array => array_map(static function ($line): array { $p = array_map('trim', explode('|', $line, 3)); return ['label' => $p[0] ?? '', 'title' => $p[1] ?? '', 'description' => $p[2] ?? '']; }, $localizedLines('fit_cards'));
        $faq = static fn (string $key): array => array_map(static function ($line): array { $p = array_map('trim', explode('|', $line, 2)); return ['question' => $p[0] ?? '', 'answer' => $p[1] ?? '']; }, $lines($key));
        $localizedFaq = static fn (): array => array_map(static function ($line): array { $p = array_map('trim', explode('|', $line, 2)); return ['question' => $p[0] ?? '', 'answer' => $p[1] ?? '']; }, $localizedLines('faq'));
        $title = (string) get_post_meta($id, '_gvspace_service_title_' . $service_locale, true);
        return ['title' => $title ?: (string) get_the_title($id), 'headline' => $localizedValue('headline'), 'description' => $localizedValue('description'), 'order' => (int) get_post_field('menu_order', $id), 'fitCards' => $localizedFitCards(), 'includes' => $localizedLines('includes'), 'steps' => $localizedSteps(), 'faq' => $localizedFaq(), 'titleEn' => $value('title_en'), 'headlineUk' => $value('headline_uk'), 'headlineEn' => $value('headline_en'), 'descriptionUk' => $value('description_uk'), 'descriptionEn' => $value('description_en'), 'includesUk' => $lines('includes_uk'), 'includesEn' => $lines('includes_en'), 'stepsUk' => $steps('steps_uk'), 'stepsEn' => $steps('steps_en'), 'metrics' => $localizedLines('metrics'), 'faqUk' => $faq('faq_uk'), 'faqEn' => $faq('faq_en')];
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
                'metrics' => gvspace_split_meta_lines($locale === 'legacy' ? $value('metrics') : $localizedValue('metrics')),
            ];
        },
    ]);

});

register_activation_hook(__FILE__, function (): void {
    do_action('init');
    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, 'flush_rewrite_rules');

function gvspace_default_team_tab_options(): array
{
    return [
        'core-team' => 'CORE TEAM',
        'strategy' => 'STRATEGY',
        'marketing' => 'MARKETING',
        'development' => 'DEVELOPMENT',
        'content' => 'CONTENT',
    ];
}

function gvspace_get_team_tab_options(): array
{
    $terms = get_terms([
        'taxonomy' => 'gv_team_member_category',
        'hide_empty' => false,
    ]);
    if (is_wp_error($terms) || !$terms) return gvspace_default_team_tab_options();

    $options = [];
    $preferred = array_keys(gvspace_default_team_tab_options());
    usort($terms, static function ($a, $b) use ($preferred): int {
        $ai = array_search($a->slug, $preferred, true);
        $bi = array_search($b->slug, $preferred, true);
        if ($ai === false && $bi === false) return strcasecmp($a->name, $b->name);
        if ($ai === false) return 1;
        if ($bi === false) return -1;
        return $ai - $bi;
    });
    foreach ($terms as $term) {
        $options[$term->slug] = $term->name;
    }
    return $options;
}

function gvspace_render_team_tab_metabox(WP_Post $post): void
{
    $selected = wp_get_object_terms($post->ID, 'gv_team_member_category', ['fields' => 'slugs']);
    if (!is_array($selected)) $selected = [];
    $tabs_url = admin_url('edit-tags.php?taxonomy=gv_team_member_category&post_type=gv_team_member');
    echo '<p class="description">Оберіть один або кілька табів. Новий напрямок додайте в <a href="' . esc_url($tabs_url) . '">Команда → Таби</a>.</p>';
    echo '<input type="hidden" name="gvspace_team_tabs_submitted" value="1">';
    foreach (gvspace_get_team_tab_options() as $slug => $label) {
        echo '<p style="margin:8px 0"><label><input type="checkbox" name="gvspace_team_tabs[]" value="'
            . esc_attr($slug) . '"'
            . checked(in_array($slug, $selected, true), true, false)
            . '> ' . esc_html($label) . '</label></p>';
    }
}

function gvspace_seed_team_tabs(): void
{
    foreach (gvspace_default_team_tab_options() as $slug => $name) {
        if (!term_exists($slug, 'gv_team_member_category')) {
            wp_insert_term($name, 'gv_team_member_category', ['slug' => $slug]);
        }
    }
}
add_action('init', 'gvspace_seed_team_tabs', 19);
add_action('admin_init', 'gvspace_seed_team_tabs');

add_action('admin_head-edit-tags.php', function (): void {
    $screen = get_current_screen();
    if (!$screen || $screen->taxonomy !== 'gv_team_member_category') return;
    echo '<style>.term-parent-wrap{display:none}</style>';
});
add_action('admin_head-term.php', function (): void {
    $screen = get_current_screen();
    if (!$screen || $screen->taxonomy !== 'gv_team_member_category') return;
    echo '<style>.term-parent-wrap{display:none}</style>';
});

function gvspace_team_member_seed_catalog(): array
{
    return [
        [
            'group' => 'team-vasyl-horaichuk',
            'order' => 0,
            'categories' => ['core-team', 'strategy', 'marketing'],
            'uk' => ['Василь Горайчук', 'CEO & FOUNDER', "META ADS\nGOOGLE ADS\nANALYTICS"],
            'en' => ['Vasyl Horaichuk', 'CEO & FOUNDER', "META ADS\nGOOGLE ADS\nANALYTICS"],
        ],
        [
            'group' => 'team-head-of-marketing',
            'order' => 1,
            'categories' => ['core-team', 'marketing'],
            'uk' => ['[Ім’я Прізвище]', 'HEAD OF MARKETING', "PERFORMANCE\nFUNNELS\nANALYTICS"],
            'en' => ['[First Last Name]', 'HEAD OF MARKETING', "PERFORMANCE\nFUNNELS\nANALYTICS"],
        ],
        [
            'group' => 'team-head-of-content',
            'order' => 2,
            'categories' => ['core-team', 'content'],
            'uk' => ['[Ім’я Прізвище]', 'HEAD OF CONTENT', "COPY\nSEO\nGEO"],
            'en' => ['[First Last Name]', 'HEAD OF CONTENT', "COPY\nSEO\nGEO"],
        ],
        [
            'group' => 'team-project-manager',
            'order' => 3,
            'categories' => ['core-team'],
            'uk' => ['[Ім’я Прізвище]', 'PROJECT MANAGER', "DELIVERY\nTIMELINES\nCOORDINATION"],
            'en' => ['[First Last Name]', 'PROJECT MANAGER', "DELIVERY\nTIMELINES\nCOORDINATION"],
        ],
        [
            'group' => 'team-performance-specialist',
            'order' => 4,
            'categories' => ['core-team', 'marketing'],
            'uk' => ['[Ім’я Прізвище]', 'PERFORMANCE SPECIALIST', "META ADS\nGOOGLE ADS"],
            'en' => ['[First Last Name]', 'PERFORMANCE SPECIALIST', "META ADS\nGOOGLE ADS"],
        ],
        [
            'group' => 'team-crm-specialist',
            'order' => 5,
            'categories' => ['core-team', 'strategy'],
            'uk' => ['[Ім’я Прізвище]', 'CRM SPECIALIST', "AUTOMATION\nPIPELINES"],
            'en' => ['[First Last Name]', 'CRM SPECIALIST', "AUTOMATION\nPIPELINES"],
        ],
        [
            'group' => 'team-content-specialist',
            'order' => 6,
            'categories' => ['core-team', 'content'],
            'uk' => ['[Ім’я Прізвище]', 'CONTENT SPECIALIST', "SMM\nCOPY"],
            'en' => ['[First Last Name]', 'CONTENT SPECIALIST', "SMM\nCOPY"],
        ],
        [
            'group' => 'team-designer',
            'order' => 7,
            'categories' => ['core-team', 'marketing', 'content'],
            'uk' => ['[Ім’я Прізвище]', 'DESIGNER', "UI\nVISUAL SYSTEMS"],
            'en' => ['[First Last Name]', 'DESIGNER', "UI\nVISUAL SYSTEMS"],
        ],
    ];
}

function gvspace_find_seeded_team_member(string $group, string $uk_title): ?WP_Post
{
    $by_group = get_posts([
        'post_type' => 'gv_team_member',
        'post_status' => ['publish', 'draft', 'pending', 'private'],
        'numberposts' => 1,
        'orderby' => 'ID',
        'order' => 'ASC',
        'meta_key' => '_gvspace_translation_group',
        'meta_value' => $group,
    ]);
    if ($by_group) return $by_group[0];
    if (str_starts_with($uk_title, '[')) return null;

    $by_title = get_posts([
        'post_type' => 'gv_team_member',
        'post_status' => ['publish', 'draft', 'pending', 'private'],
        'numberposts' => 1,
        'orderby' => 'ID',
        'order' => 'ASC',
        'title' => $uk_title,
    ]);
    return $by_title[0] ?? null;
}

function gvspace_seed_team_members(): void
{
    $catalog = gvspace_team_member_seed_catalog();
    $hash = md5((string) wp_json_encode($catalog));
    if ((string) get_option('gvspace_team_members_catalog_hash') === $hash) return;
    $lock_time = (int) get_option('gvspace_team_members_catalog_lock', 0);
    if ($lock_time && time() - $lock_time < 300) return;
    if ($lock_time) delete_option('gvspace_team_members_catalog_lock');
    if (!add_option('gvspace_team_members_catalog_lock', time(), '', false)) return;

    gvspace_seed_team_tabs();
    $keep_groups = [];
    foreach ($catalog as $member) {
        $keep_groups[] = $member['group'];
        $existing = gvspace_find_seeded_team_member($member['group'], $member['uk'][0]);
        $post_data = [
            'post_type' => 'gv_team_member',
            'post_status' => 'publish',
            'post_title' => $member['uk'][0],
            'post_name' => $member['group'],
            'menu_order' => $member['order'],
        ];
        if ($existing) $post_data['ID'] = $existing->ID;
        $post_id = wp_insert_post($post_data);
        if (!$post_id || is_wp_error($post_id)) continue;

        update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
        update_post_meta($post_id, '_gvspace_translation_group', $member['group']);
        update_post_meta($post_id, '_gvspace_translation_status', 'published');
        update_post_meta($post_id, '_gvspace_team_member_seeded', 'catalog');
        update_post_meta($post_id, '_gvspace_team_member_title_uk', $member['uk'][0]);
        update_post_meta($post_id, '_gvspace_team_member_role_uk', $member['uk'][1]);
        update_post_meta($post_id, '_gvspace_team_member_tags_uk', $member['uk'][2]);
        update_post_meta($post_id, '_gvspace_team_member_title_en', $member['en'][0]);
        update_post_meta($post_id, '_gvspace_team_member_role_en', $member['en'][1]);
        update_post_meta($post_id, '_gvspace_team_member_tags_en', $member['en'][2]);
        update_post_meta($post_id, '_gvspace_team_member_role', $member['uk'][1]);
        update_post_meta($post_id, '_gvspace_team_member_tags', $member['uk'][2]);
        wp_set_object_terms((int) $post_id, $member['categories'], 'gv_team_member_category', false);
    }

    $retired_groups = [
        'team-khrystyna-horaichuk',
        'team-viktoriia-horaichuk',
        'team-yaroslav-horaichuk',
        'team-mariana-horaichuk',
    ];
    $members = get_posts([
        'post_type' => 'gv_team_member',
        'post_status' => ['publish', 'draft', 'pending', 'private', 'trash'],
        'numberposts' => -1,
    ]);
    foreach ($members as $member_post) {
        $group = (string) get_post_meta($member_post->ID, '_gvspace_translation_group', true);
        $seeded = (string) get_post_meta($member_post->ID, '_gvspace_team_member_seeded', true);
        $obsolete = in_array($group, $retired_groups, true)
            || ($seeded === 'catalog' && $group !== '' && !in_array($group, $keep_groups, true));
        if ($obsolete) wp_delete_post((int) $member_post->ID, true);
    }

    update_option('gvspace_team_members_catalog_hash', $hash, false);
    delete_option('gvspace_team_members_catalog_lock');
}
add_action('init', 'gvspace_seed_team_members', 20);

function gvspace_migrate_team_member_language_fields(): void
{
    if (get_option('gvspace_team_member_languages_v1') === '1') return;
    $member_ids = get_posts([
        'post_type' => 'gv_team_member',
        'post_status' => 'any',
        'numberposts' => -1,
        'fields' => 'ids',
    ]);
    foreach ($member_ids as $member_id) {
        $stored_locale = (string) get_post_meta((int) $member_id, '_gvspace_content_locale', true);
        $locale = array_key_exists($stored_locale, GVSPACE_CONTENT_LOCALES) ? $stored_locale : 'uk';
        $title_key = '_gvspace_team_member_title_' . $locale;
        if ((string) get_post_meta((int) $member_id, $title_key, true) === '') {
            update_post_meta((int) $member_id, $title_key, (string) get_post_field('post_title', (int) $member_id));
        }
        foreach (array_keys(GVSPACE_LOCALIZED_TEAM_MEMBER_FIELDS) as $field) {
            $localized_key = '_gvspace_team_member_' . $field . '_' . $locale;
            if ((string) get_post_meta((int) $member_id, $localized_key, true) !== '') continue;
            $legacy_value = (string) get_post_meta((int) $member_id, '_gvspace_team_member_' . $field, true);
            if ($legacy_value !== '') update_post_meta((int) $member_id, $localized_key, $legacy_value);
        }
    }
    update_option('gvspace_team_member_languages_v1', '1', false);
}
add_action('admin_init', 'gvspace_migrate_team_member_language_fields');

function gvspace_consolidate_vasyl_team_translation(): void
{
    if (get_option('gvspace_team_vasyl_consolidated_v1') === '1') return;
    $members = get_posts([
        'post_type' => 'gv_team_member',
        'post_status' => ['publish', 'draft', 'pending', 'private', 'trash'],
        'numberposts' => -1,
        'meta_key' => '_gvspace_translation_group',
        'meta_value' => 'team-vasyl-horaichuk',
    ]);
    if (!$members) return;

    $primary = null;
    $english_duplicate = null;
    foreach ($members as $member) {
        $locale = (string) get_post_meta($member->ID, '_gvspace_content_locale', true);
        if ($locale === 'en') $english_duplicate = $member;
        if ($locale === 'uk' || $locale === 'legacy') $primary = $member;
    }
    if (!$primary) return;

    $primary_id = (int) $primary->ID;
    $english_id = $english_duplicate ? (int) $english_duplicate->ID : 0;
    $uk_role = (string) get_post_meta($primary_id, '_gvspace_team_member_role', true);
    $uk_tags = (string) get_post_meta($primary_id, '_gvspace_team_member_tags', true);
    $en_role = $english_id ? (string) get_post_meta($english_id, '_gvspace_team_member_role', true) : $uk_role;
    $en_tags = $english_id ? (string) get_post_meta($english_id, '_gvspace_team_member_tags', true) : $uk_tags;

    update_post_meta($primary_id, '_gvspace_team_member_title_uk', 'Василь Горайчук');
    update_post_meta($primary_id, '_gvspace_team_member_role_uk', $uk_role);
    update_post_meta($primary_id, '_gvspace_team_member_tags_uk', $uk_tags);
    update_post_meta($primary_id, '_gvspace_team_member_title_en', 'Vasyl Horaichuk');
    update_post_meta($primary_id, '_gvspace_team_member_role_en', $en_role);
    update_post_meta($primary_id, '_gvspace_team_member_tags_en', $en_tags);
    foreach (array_keys(GVSPACE_SEO_FIELDS) as $seo_field) {
        $uk_target = '_gvspace_seo_' . $seo_field . '_uk';
        $en_target = '_gvspace_seo_' . $seo_field . '_en';
        $uk_value = (string) get_post_meta($primary_id, $uk_target, true)
            ?: (string) get_post_meta($primary_id, '_gvspace_seo_' . $seo_field, true);
        $en_value = (string) get_post_meta($primary_id, $en_target, true);
        if ($en_value === '' && $english_id) {
            $en_value = (string) get_post_meta($english_id, '_gvspace_seo_' . $seo_field . '_en', true)
                ?: (string) get_post_meta($english_id, '_gvspace_seo_' . $seo_field, true);
        }
        if ($uk_value !== '') update_post_meta($primary_id, $uk_target, $uk_value);
        if ($en_value !== '') update_post_meta($primary_id, $en_target, $en_value);
    }
    update_post_meta($primary_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($primary_id, '_gvspace_translation_status', 'published');

    if ($english_id && $english_id !== $primary_id && get_post_status($english_id) !== 'trash') {
        wp_trash_post($english_id);
        if (get_post_status($english_id) !== 'trash') {
            wp_update_post(['ID' => $english_id, 'post_status' => 'trash']);
        }
    }
    update_option('gvspace_team_vasyl_consolidated_v1', '1', false);
}
add_action('admin_init', 'gvspace_consolidate_vasyl_team_translation');

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

function gvspace_service_seed_catalog(): array
{
    return [
        'strategy' => [
            'uk' => ['Стратегія', 'Системна стратегія керованого зростання', 'Ми проводимо глибоку діагностику вашої Unit-економіки та воронки, щоб бачити, куди інвестувати кожен долар для масштабування.'],
            'en' => ['Strategy', 'System strategy for managed growth', 'We diagnose your unit economics and funnel to reveal where every dollar should be invested for growth.'],
            'children' => [
                'strategic-audit' => ['Стратегічний аудит', 'Strategic audit'],
                'digital-audit' => ['Комплексний Digital-аудит', 'Comprehensive digital audit'],
                'market-analysis' => ['Аналіз ринку та конкурентне позиціонування', 'Market analysis and competitive positioning'],
                'clarity-session' => ['Clarity Session', 'Clarity Session'],
                'growth-roadmap' => ['Архітектура зростання (Roadmap)', 'Growth architecture (Roadmap)'],
                'marketing-process-audit' => ['Аудит маркетингових процесів', 'Marketing process audit'],
            ],
        ],
        'marketing' => [
            'uk' => ['Маркетинг', 'Маркетинг, який перетворює трафік на капітал', 'Ми будуємо Performance-стратегії, що базуються на цифрах, а не на гіпотезах. Впроваджуємо наскрізну аналітику та рекламні інструменти, які дозволяють зростати без хаосу.'],
            'en' => ['Marketing', 'Marketing that turns traffic into capital', 'We build data-led performance strategies and marketing systems that scale without chaos.'],
            'children' => [
                'performance-marketing' => ['Performance Marketing (Meta & Google Ads)', 'Performance Marketing (Meta & Google Ads)'],
                'analytics-dashboards' => ['Побудова системної аналітики & Dashboards', 'Analytics systems & Dashboards'],
                'smm-strategy' => ['SMM Стратегія та присутність', 'SMM strategy and presence'],
                'seo' => ['SEO-просування', 'SEO promotion'],
                'retention-crm' => ['Retention & CRM Маркетинг', 'Retention & CRM Marketing'],
            ],
        ],
        'development' => [
            'uk' => ['IT-розробка', 'Цифрові системи для масштабування', 'Ми створюємо відмовостійкі цифрові системи, до яких бізнес може підключати маркетинг без страху технічних обмежень.'],
            'en' => ['IT Development', 'Digital systems built to scale', 'We create resilient digital systems that let businesses connect marketing without technical limitations.'],
            'children' => [
                'corporate-websites' => ['Розробка корпоративних сайтів та лендингів', 'Corporate websites and landing pages'],
                'business-systems' => ['Розробка складних систем (CRM, ERP, Dashboards)', 'Complex systems (CRM, ERP, Dashboards)'],
                'technical-support' => ['Технічна підтримка та інфраструктура', 'Technical support and infrastructure'],
                'ecommerce' => ['E-commerce рішення (Інтернет-магазини)', 'E-commerce solutions'],
                'product-discovery' => ['Product Discovery & Архітектура', 'Product Discovery & Architecture'],
            ],
        ],
        'content' => [
            'uk' => ['Контент & Продакшн', 'Контент, який формує довіру', 'Ми не просто створюємо візуал — ми будуємо систему комунікації, яка пояснює цінність продукту без зайвих слів.'],
            'en' => ['Content & Production', 'Content that builds trust', 'We build a communication system that conveys product value without unnecessary words.'],
            'children' => [
                'brand-design' => ['Бренд-дизайн та Візуальна айдентика', 'Brand design and visual identity'],
                'photo-production' => ['Фото-продакшн (Food, Product, Lifestyle)', 'Photo production (Food, Product, Lifestyle)'],
                'creative-concepts' => ['Креативні концепції та спецпроєкти', 'Creative concepts and special projects'],
                'video-production' => ['Video Production (Рекламні та іміджеві ролики)', 'Video Production'],
                'copywriting' => ['Копірайтинг & Storytelling', 'Copywriting & Storytelling'],
            ],
        ],
    ];
}

function gvspace_find_localized_service(string $group, string $locale): ?WP_Post
{
    $posts = get_posts([
        'post_type' => 'gv_service', 'post_status' => 'any', 'numberposts' => 1,
        'meta_query' => [
            ['key' => '_gvspace_translation_group', 'value' => $group],
            ['key' => '_gvspace_content_locale', 'value' => $locale],
        ],
    ]);
    return $posts[0] ?? null;
}

function gvspace_migrate_legacy_service_fields(int $post_id, string $group, string $locale): void
{
    $source = gvspace_find_localized_service($group, 'uk');
    if (!$source) return;
    $suffix = $locale === 'en' ? 'en' : 'uk';
    $field_map = [
        'headline' => 'headline_' . $suffix, 'description' => 'description_' . $suffix,
        'includes' => 'includes_' . $suffix, 'steps' => 'steps_' . $suffix,
        'metrics' => 'metrics', 'faq' => 'faq_' . $suffix,
    ];
    foreach ($field_map as $localized_field => $legacy_field) {
        $target_key = '_gvspace_service_localized_' . $localized_field;
        if ((string) get_post_meta($post_id, $target_key, true) !== '') continue;
        $legacy_value = (string) get_post_meta($source->ID, '_gvspace_service_' . $legacy_field, true);
        if ($legacy_value !== '') update_post_meta($post_id, $target_key, $legacy_value);
    }
    foreach (array_keys(GVSPACE_SEO_FIELDS) as $seo_field) {
        $target_key = '_gvspace_seo_' . $seo_field;
        if ((string) get_post_meta($post_id, $target_key, true) !== '') continue;
        $legacy_value = (string) get_post_meta($source->ID, $target_key . '_' . $suffix, true);
        if ($legacy_value !== '') update_post_meta($post_id, $target_key, $legacy_value);
    }
}

function gvspace_upsert_seeded_service(string $group, string $locale, string $title, int $parent_id, int $order): int
{
    $existing = gvspace_find_localized_service($group, $locale);
    if (!$existing && $locale === 'uk') {
        $legacy = get_page_by_path($parent_id ? get_post_field('post_name', $parent_id) . '/' . $group : $group, OBJECT, 'gv_service');
        if ($legacy && gvspace_get_content_locale($legacy) === 'legacy') $existing = $legacy;
    }

    $post_data = [
        'post_type' => 'gv_service', 'post_status' => 'publish', 'post_title' => $title,
        'post_name' => $locale === 'uk' ? $group : $group . '-' . strtolower($locale),
        'post_parent' => $parent_id, 'menu_order' => $order,
    ];
    if ($existing) $post_data['ID'] = $existing->ID;
    $post_id = wp_insert_post($post_data);
    if (!$post_id || is_wp_error($post_id)) return 0;

    update_post_meta($post_id, '_gvspace_content_locale', $locale);
    update_post_meta($post_id, '_gvspace_translation_group', $group);
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    update_post_meta($post_id, '_gvspace_service_seeded', '1');
    gvspace_migrate_legacy_service_fields((int) $post_id, $group, $locale);
    return (int) $post_id;
}

function gvspace_seed_services(): void
{
    if (get_option('gvspace_services_seeded_v3') === '1') return;
    $lock_time = (int) get_option('gvspace_services_seed_v3_lock', 0);
    if ($lock_time && time() - $lock_time < 300) return;
    if ($lock_time) delete_option('gvspace_services_seed_v3_lock');
    if (!add_option('gvspace_services_seed_v3_lock', time(), '', false)) return;
    $directions = gvspace_service_seed_catalog();
    foreach (['uk', 'en'] as $locale) {
        foreach ($directions as $slug => $direction) {
            $direction_order = array_search($slug, array_keys($directions), true);
            [$title, $headline, $description] = $direction[$locale];
            $parent_id = gvspace_upsert_seeded_service($slug, $locale, $title, 0, $direction_order);
            if (!$parent_id) continue;
            if ((string) get_post_meta($parent_id, '_gvspace_service_localized_headline', true) === '') {
                update_post_meta($parent_id, '_gvspace_service_localized_headline', $headline);
            }
            if ((string) get_post_meta($parent_id, '_gvspace_service_localized_description', true) === '') {
                update_post_meta($parent_id, '_gvspace_service_localized_description', $description);
            }
            foreach ($direction['children'] as $child_slug => $child) {
                $child_order = array_search($child_slug, array_keys($direction['children']), true);
                $child_title = $child[$locale === 'uk' ? 0 : 1];
                $child_id = gvspace_upsert_seeded_service($child_slug, $locale, $child_title, $parent_id, $child_order);
                if ($child_id && (string) get_post_meta($child_id, '_gvspace_service_localized_headline', true) === '') {
                    update_post_meta($child_id, '_gvspace_service_localized_headline', $child_title);
                }
            }
        }
    }
    update_option('gvspace_services_seeded_v3', '1', false);
    delete_option('gvspace_services_seed_v3_lock');
    flush_rewrite_rules(false);
}
function gvspace_seed_centralized_services(): void
{
    if (get_option('gvspace_services_seeded_v4') === '1') return;
    $lock_time = (int) get_option('gvspace_services_seed_v4_lock', 0);
    if ($lock_time && time() - $lock_time < 300) return;
    if ($lock_time) delete_option('gvspace_services_seed_v4_lock');
    if (!add_option('gvspace_services_seed_v4_lock', time(), '', false)) return;

    $old_services = get_posts([
        'post_type' => 'gv_service', 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids',
    ]);
    foreach ($old_services as $old_service_id) wp_delete_post((int) $old_service_id, true);

    $catalog = gvspace_service_seed_catalog();
    foreach ($catalog as $direction_slug => $direction) {
        $direction_order = array_search($direction_slug, array_keys($catalog), true);
        $parent_id = wp_insert_post([
            'post_type' => 'gv_service', 'post_status' => 'publish',
            'post_title' => $direction['uk'][0], 'post_name' => $direction_slug,
            'post_parent' => 0, 'menu_order' => $direction_order,
        ]);
        if (!$parent_id || is_wp_error($parent_id)) continue;
        update_post_meta($parent_id, '_gvspace_content_locale', 'legacy');
        update_post_meta($parent_id, '_gvspace_translation_group', $direction_slug);
        update_post_meta($parent_id, '_gvspace_translation_status', 'published');
        update_post_meta($parent_id, '_gvspace_service_seeded', 'centralized-v4');
        update_post_meta($parent_id, '_gvspace_service_catalog_slug', $direction_slug);
        foreach (['uk', 'en'] as $locale) {
            update_post_meta($parent_id, '_gvspace_service_title_' . $locale, $direction[$locale][0]);
            update_post_meta($parent_id, '_gvspace_service_headline_' . $locale, $direction[$locale][1]);
            update_post_meta($parent_id, '_gvspace_service_description_' . $locale, $direction[$locale][2]);
        }

        foreach ($direction['children'] as $child_slug => $child) {
            $child_order = array_search($child_slug, array_keys($direction['children']), true);
            $child_id = wp_insert_post([
                'post_type' => 'gv_service', 'post_status' => 'publish',
                'post_title' => $child[0], 'post_name' => $child_slug,
                'post_parent' => $parent_id, 'menu_order' => $child_order,
            ]);
            if (!$child_id || is_wp_error($child_id)) continue;
            update_post_meta($child_id, '_gvspace_content_locale', 'legacy');
            update_post_meta($child_id, '_gvspace_translation_group', $child_slug);
            update_post_meta($child_id, '_gvspace_translation_status', 'published');
            update_post_meta($child_id, '_gvspace_service_seeded', 'centralized-v4');
            update_post_meta($child_id, '_gvspace_service_catalog_slug', $child_slug);
            update_post_meta($child_id, '_gvspace_service_title_uk', $child[0]);
            update_post_meta($child_id, '_gvspace_service_title_en', $child[1]);
            update_post_meta($child_id, '_gvspace_service_headline_uk', $child[0]);
            update_post_meta($child_id, '_gvspace_service_headline_en', $child[1]);
        }
    }
    update_option('gvspace_services_seeded_v4', '1', false);
    delete_option('gvspace_services_seed_v4_lock');
    flush_rewrite_rules(false);
}
add_action('init', 'gvspace_seed_centralized_services', 23);

function gvspace_normalize_centralized_service_slugs(): void
{
    if (get_option('gvspace_services_seeded_v5') === '1') return;
    global $wpdb;
    $service_ids = get_posts([
        'post_type' => 'gv_service', 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids',
        'meta_key' => '_gvspace_service_seeded', 'meta_value' => 'centralized-v4',
    ]);
    foreach ($service_ids as $service_id) {
        $slug = (string) get_post_meta((int) $service_id, '_gvspace_translation_group', true);
        if ($slug === '') continue;
        $wpdb->update($wpdb->posts, ['post_name' => sanitize_title($slug)], ['ID' => (int) $service_id], ['%s'], ['%d']);
        clean_post_cache((int) $service_id);
    }
    update_option('gvspace_services_seeded_v5', '1', false);
    flush_rewrite_rules(false);
}
add_action('init', 'gvspace_normalize_centralized_service_slugs', 24);

function gvspace_repair_centralized_service_slugs(): void
{
    if (get_option('gvspace_services_seeded_v6') === '1') return;
    global $wpdb;
    $slugs_by_title = [];
    foreach (gvspace_service_seed_catalog() as $direction_slug => $direction) {
        $slugs_by_title[$direction['uk'][0]] = $direction_slug;
        foreach ($direction['children'] as $child_slug => $child) $slugs_by_title[$child[0]] = $child_slug;
    }
    $service_ids = get_posts([
        'post_type' => 'gv_service', 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids',
        'meta_key' => '_gvspace_service_seeded', 'meta_value' => 'centralized-v4',
    ]);
    foreach ($service_ids as $service_id) {
        $uk_title = (string) get_post_meta((int) $service_id, '_gvspace_service_title_uk', true);
        $slug = $slugs_by_title[$uk_title] ?? '';
        if ($slug === '') continue;
        update_post_meta((int) $service_id, '_gvspace_service_catalog_slug', $slug);
        $wpdb->update($wpdb->posts, ['post_name' => $slug], ['ID' => (int) $service_id], ['%s'], ['%d']);
        clean_post_cache((int) $service_id);
    }
    update_option('gvspace_services_seeded_v6', '1', false);
    flush_rewrite_rules(false);
}
add_action('init', 'gvspace_repair_centralized_service_slugs', 25);

function gvspace_repair_centralized_service_order(): void
{
    if (get_option('gvspace_services_seeded_v7') === '1') return;
    global $wpdb;
    $order_by_title = [];
    foreach (gvspace_service_seed_catalog() as $direction_order => $direction) {
        $direction_index = array_search($direction_order, array_keys(gvspace_service_seed_catalog()), true);
        $order_by_title[$direction['uk'][0]] = $direction_index;
        foreach ($direction['children'] as $child_order => $child) {
            $order_by_title[$child[0]] = array_search($child_order, array_keys($direction['children']), true);
        }
    }
    $service_ids = get_posts([
        'post_type' => 'gv_service', 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids',
        'meta_key' => '_gvspace_service_seeded', 'meta_value' => 'centralized-v4',
    ]);
    foreach ($service_ids as $service_id) {
        $uk_title = (string) get_post_meta((int) $service_id, '_gvspace_service_title_uk', true);
        if (!array_key_exists($uk_title, $order_by_title)) continue;
        $wpdb->update($wpdb->posts, ['menu_order' => (int) $order_by_title[$uk_title]], ['ID' => (int) $service_id], ['%d'], ['%d']);
        clean_post_cache((int) $service_id);
    }
    update_option('gvspace_services_seeded_v7', '1', false);
}
add_action('init', 'gvspace_repair_centralized_service_order', 26);

function gvspace_seed_reference_l3_service(): void
{
    if (get_option('gvspace_services_seeded_v10') === '1') return;

    $service_ids = get_posts([
        'post_type' => 'gv_service', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids',
        'meta_key' => '_gvspace_service_catalog_slug', 'meta_value' => 'performance-marketing',
    ]);
    if (!$service_ids) {
        $service = get_page_by_path('performance-marketing', OBJECT, 'gv_service');
        if ($service) $service_ids = [$service->ID];
    }
    if (!$service_ids) return;

    $service_id = (int) $service_ids[0];
    $content = [
        'uk' => [
            'title' => 'Performance Marketing (Meta & Google Ads)',
            'headline' => 'Реклама, яка повертає більше, ніж витрачає',
            'description' => 'Будуємо рекламні системи, де кожен долар має траєкторію повернення. Від аудиту рекламних кабінетів до наскрізної аналітики — ми контролюємо весь шлях клієнта.',
            'fit_cards' => "EARLY STAGE | Clarity Session | Бізнес шукає перший стабільний потік лідів. Немає чіткої системи залучення клієнтів.\nGROWTH STAGE | Архітектура системи | Є кілька каналів, але CPL зростає разом із бюджетом. Потрібна система масштабування без зливу.\nSCALE STAGE | Запуск і оптимізація | Кілька каналів і складна воронка. Потрібен контроль та прогноз по кожному каналу й сегменту.",
            'includes' => "Аудит рекламних кабінетів (Meta, Google)\nМедіаплан і розподіл бюджету по каналах\nНалаштування та оптимізація кампаній\nНаскрізна аналітика (GA4 + GTM + Pixel)\nЩотижневі звіти без жаргону\nЩомісячна стратегічна оптимізація",
            'steps' => "Clarity Session | безкоштовно · 30 хв | Розбираємо вашу поточну ситуацію та визначаємо точки росту. Тільки факти, цифри й потенціал.\nСтратегія та медіаплан | 14 днів | Ви отримуєте повний план дій: канали, бюджет, KPI та точки контролю.\nЗапуск і перші результати | від 30 днів | Перші вимірювані результати за місяць. Далі — оптимізація та масштабування без пропорційного зростання бюджету.\nМасштабування системи | постійно | На основі даних масштабуємо те, що працює. Ріст бюджету не дорівнює росту хаосу.",
            'metrics' => "+140% ROAS\n-30% CPL\n+210% органічний трафік",
            'faq' => "Скільки часу займає запуск рекламної системи? | Перший аудит і медіаплан готуємо до 14 днів. Запуск та накопичення даних для перших обґрунтованих висновків зазвичай займають від 30 днів.\nЧи працюєте ви з моєю нішею? | Перед стартом ми аналізуємо продукт, економіку та рекламні обмеження ніші. Якщо не бачимо реалістичного потенціалу, чесно повідомляємо про це до початку робіт.\nЯкі гарантії результату? | Ми не гарантуємо наперед конкретну цифру продажів, але гарантуємо прозору аналітику, контроль KPI, системну оптимізацію та зрозумілу звітність.\nЧому не фриланс або інша агенція? | Над проєктом працює команда зі стратегії, реклами й аналітики. Ви отримуєте керовану систему, а не лише налаштування рекламного кабінету.",
            'seo' => [
                'title' => 'Performance Marketing у Meta та Google',
                'description' => 'Системний Performance Marketing: аудит, медіаплан, Meta та Google Ads, наскрізна аналітика й оптимізація рекламного бюджету.',
                'h1' => 'Реклама, яка повертає більше, ніж витрачає',
                'og_title' => 'Performance Marketing у Meta та Google | GVSPACE',
                'og_description' => 'Будуємо керовану рекламну систему з прозорою аналітикою та прогнозованим масштабуванням.',
            ],
        ],
        'en' => [
            'title' => 'Performance Marketing (Meta & Google Ads)',
            'headline' => 'Advertising that returns more than it spends',
            'description' => 'We build advertising systems where every dollar has a measurable return path. From account audits to end-to-end analytics, we control the entire customer journey.',
            'fit_cards' => "EARLY STAGE | Clarity Session | The business needs its first stable flow of leads and does not yet have a clear customer acquisition system.\nGROWTH STAGE | System architecture | Several channels are active, but CPL rises with the budget. The business needs a scalable system without wasted spend.\nSCALE STAGE | Launch and optimization | Multiple channels and a complex funnel require control and forecasting for every channel and segment.",
            'includes' => "Advertising account audit (Meta, Google)\nMedia plan and channel budget allocation\nCampaign setup and optimization\nEnd-to-end analytics (GA4 + GTM + Pixel)\nWeekly reports without jargon\nMonthly strategic optimization",
            'steps' => "Clarity Session | free · 30 min | We review the current situation, identify growth points, and focus on facts, numbers, and potential.\nStrategy and media plan | 14 days | You receive a complete action plan with channels, budget, KPIs, and control points.\nLaunch and first results | from 30 days | We collect measurable results in the first month, then optimize and scale without proportional budget growth.\nSystem scaling | ongoing | We use data to scale what works. A larger budget does not have to create more chaos.",
            'metrics' => "+140% ROAS\n-30% CPL\n+210% organic traffic",
            'faq' => "How long does it take to launch the advertising system? | We prepare the initial audit and media plan within 14 days. Launch and data collection for the first reliable conclusions usually take at least 30 days.\nDo you work with my industry? | Before starting, we assess the product, unit economics, and advertising restrictions. If we do not see realistic potential, we say so before the engagement begins.\nWhat results do you guarantee? | We cannot promise a specific sales figure in advance, but we guarantee transparent analytics, KPI control, systematic optimization, and clear reporting.\nWhy not hire a freelancer or another agency? | Your project is handled by strategy, advertising, and analytics specialists. You receive a managed system, not merely configured ad accounts.",
            'seo' => [
                'title' => 'Performance Marketing for Meta & Google',
                'description' => 'Systematic Performance Marketing: audits, media planning, Meta and Google Ads, end-to-end analytics, and budget optimization.',
                'h1' => 'Advertising that returns more than it spends',
                'og_title' => 'Performance Marketing for Meta & Google | GVSPACE',
                'og_description' => 'We build a manageable advertising system with transparent analytics and predictable scaling.',
            ],
        ],
    ];

    foreach ($content as $locale => $fields) {
        update_post_meta($service_id, '_gvspace_service_title_' . $locale, $fields['title']);
        foreach (['headline', 'description', 'fit_cards', 'includes', 'steps', 'metrics', 'faq'] as $field) {
            $meta_key = '_gvspace_service_' . $field . '_' . $locale;
            update_post_meta($service_id, $meta_key, $fields[$field]);
        }
        foreach ($fields['seo'] as $field => $value) {
            $meta_key = '_gvspace_seo_' . $field . '_' . $locale;
            update_post_meta($service_id, $meta_key, $value);
        }
    }

    update_post_meta($service_id, '_gvspace_service_reference_template', '1');
    update_option('gvspace_services_seeded_v10', '1', false);
    clean_post_cache($service_id);
}
add_action('init', 'gvspace_seed_reference_l3_service', 27);

function gvspace_get_service_directions(): array
{
    return get_posts([
        'post_type' => 'gv_service',
        'post_parent' => 0,
        'post_status' => ['publish', 'draft', 'pending', 'private', 'future'],
        'numberposts' => -1,
        'orderby' => ['menu_order' => 'ASC', 'title' => 'ASC'],
        'suppress_filters' => true,
    ]);
}

add_filter('manage_gv_service_posts_columns', function (array $columns): array {
    $result = [];
    foreach ($columns as $key => $label) {
        $result[$key] = $label;
        if ($key === 'title') {
            $result['gvspace_service_level'] = 'Тип';
            $result['gvspace_service_direction'] = 'Напрямок';
            $result['gvspace_service_locale'] = 'Мови';
            $result['menu_order'] = 'Порядок';
        }
    }
    return $result;
});

add_action('manage_gv_service_posts_custom_column', function (string $column, int $post_id): void {
    if ($column === 'gvspace_service_level') echo get_post_field('post_parent', $post_id) ? 'Послуга (L3)' : 'Напрямок (L2)';
    if ($column === 'gvspace_service_direction') {
        $parent_id = (int) get_post_field('post_parent', $post_id);
        echo $parent_id ? esc_html(get_the_title($parent_id)) : '—';
    }
    if ($column === 'gvspace_service_locale') echo 'UK, EN +';
    if ($column === 'menu_order') echo esc_html((string) get_post_field('menu_order', $post_id));
}, 10, 2);

add_filter('manage_edit-gv_service_sortable_columns', function (array $columns): array {
    $columns['menu_order'] = 'menu_order';
    return $columns;
});

add_action('restrict_manage_posts', function (string $post_type): void {
    if ($post_type !== 'gv_service') return;

    $selected_level = isset($_GET['gv_service_level']) ? sanitize_key(wp_unslash($_GET['gv_service_level'])) : '';
    $selected_direction = isset($_GET['gv_service_direction']) ? absint($_GET['gv_service_direction']) : 0;

    echo '<label for="gv_service_level" class="screen-reader-text">Фільтр за типом</label>';
    echo '<select name="gv_service_level" id="gv_service_level">';
    echo '<option value="">Усі типи</option>';
    echo '<option value="l2"' . selected($selected_level, 'l2', false) . '>Лише напрямки (L2)</option>';
    echo '<option value="l3"' . selected($selected_level, 'l3', false) . '>Лише послуги (L3)</option>';
    echo '</select>';

    echo '<label for="gv_service_direction" class="screen-reader-text">Фільтр за напрямком</label>';
    echo '<select name="gv_service_direction" id="gv_service_direction">';
    echo '<option value="0">Усі напрямки</option>';
    foreach (gvspace_get_service_directions() as $direction) {
        echo '<option value="' . esc_attr((string) $direction->ID) . '"' . selected($selected_direction, (int) $direction->ID, false) . '>' . esc_html($direction->post_title) . '</option>';
    }
    echo '</select>';
});

add_action('pre_get_posts', function (WP_Query $query): void {
    if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'gv_service') return;
    if (!$query->get('orderby')) $query->set('orderby', ['menu_order' => 'ASC', 'title' => 'ASC']);

    $level = isset($_GET['gv_service_level']) ? sanitize_key(wp_unslash($_GET['gv_service_level'])) : '';
    $direction_id = isset($_GET['gv_service_direction']) ? absint($_GET['gv_service_direction']) : 0;

    if ($direction_id) {
        if ($level === 'l2') {
            $query->set('post__in', [$direction_id]);
        } elseif ($level === 'l3') {
            $query->set('post_parent', $direction_id);
        } else {
            $child_ids = get_posts([
                'post_type' => 'gv_service',
                'post_parent' => $direction_id,
                'post_status' => 'any',
                'numberposts' => -1,
                'fields' => 'ids',
                'suppress_filters' => true,
            ]);
            $query->set('post__in', array_map('intval', array_merge([$direction_id], $child_ids)));
            $query->set('posts_per_page', -1);
        }
        return;
    }

    if ($level === 'l2') {
        $query->set('post_parent', 0);
        return;
    }

    if ($level === 'l3') {
        $direction_ids = array_map('intval', wp_list_pluck(gvspace_get_service_directions(), 'ID'));
        $query->set('post_parent__in', $direction_ids ?: [0]);
    }
});

/* Legacy v1 seed kept only as a migration marker. */
function gvspace_seed_services_v1_retired(): void
{
    return;
    /*
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
    */
}

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

const GVSPACE_DUPLICABLE_POST_TYPES = ['post', 'gv_case', 'gv_service', 'gv_review', 'gv_vacancy', 'gv_technology', 'gv_team_member', 'gv_partner', 'gv_faq'];

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
                'salary' => 'salary', 'role' => 'role_' . $language_suffix,
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

        if ($post->post_type === 'gv_service' && $post->post_parent) {
            $parent_group = (string) get_post_meta($post->post_parent, '_gvspace_translation_group', true);
            $translated_parent = $parent_group ? gvspace_find_localized_service($parent_group, $target_locale) : null;
            if ($translated_parent) wp_update_post(['ID' => $duplicate_id, 'post_parent' => $translated_parent->ID]);
        }
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

function gvspace_restore_stripped_json_newlines(string $body): string
{
    if ($body === '' || str_contains($body, "\n")) {
        return $body;
    }
    $body = preg_replace('/n(?=—)/u', "\n", $body) ?? $body;
    $body = preg_replace('/([.:;!?…»“”"\'\]\)])nn/u', "$1\n\n", $body) ?? $body;
    return $body;
}

function gvspace_normalize_legal_sections(array $decoded): array
{
    $sections = [];
    foreach ($decoded as $section) {
        if (!is_array($section)) continue;
        $sections[] = [
            'number' => (string) ($section['number'] ?? ''),
            'title' => (string) ($section['title'] ?? ''),
            'body' => gvspace_restore_stripped_json_newlines((string) ($section['body'] ?? '')),
        ];
    }
    return $sections;
}

function gvspace_sanitize_legal_sections_meta($value): string
{
    $decoded = is_array($value) ? $value : json_decode((string) $value, true);
    if (!is_array($decoded)) {
        return is_string($value) ? sanitize_textarea_field($value) : '';
    }
    $sections = [];
    foreach (gvspace_normalize_legal_sections($decoded) as $section) {
        $sections[] = [
            'number' => sanitize_text_field($section['number']),
            'title' => sanitize_text_field($section['title']),
            'body' => sanitize_textarea_field($section['body']),
        ];
    }
    return wp_json_encode($sections, JSON_UNESCAPED_UNICODE);
}

function gvspace_update_legal_sections_meta(int $post_id, string $key, array $sections): void
{
    update_post_meta($post_id, $key, wp_slash(wp_json_encode($sections, JSON_UNESCAPED_UNICODE)));
}

require_once __DIR__ . '/privacy-policy.php';
require_once __DIR__ . '/terms-of-use.php';
require_once __DIR__ . '/contacts-page.php';
require_once __DIR__ . '/vacancies-seed.php';
require_once __DIR__ . '/cases-seed.php';
require_once __DIR__ . '/technologies-seed.php';
