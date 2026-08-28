<?php
/**
 * Plugin Name: GVSPACE Core
 * Description: Content types and GraphQL fields used by the GVSPACE frontend.
 * Version: 0.1.0
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

    foreach (array_keys(GVSPACE_VACANCY_FIELDS) as $field) {
        register_post_meta('gv_vacancy', '_gvspace_' . $field, [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
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

add_action('add_meta_boxes', function (): void {
    add_meta_box('gvspace-case-details', 'Дані кейсу', 'gvspace_render_case_fields', 'gv_case', 'normal', 'high');
    add_meta_box('gvspace-technology-details', 'Налаштування технології', 'gvspace_render_technology_fields', 'gv_technology', 'normal', 'high');
});

function gvspace_render_technology_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_technology', 'gvspace_technology_nonce');
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

    $title_en = isset($_POST['gvspace_technology_title_en'])
        ? sanitize_text_field(wp_unslash($_POST['gvspace_technology_title_en']))
        : '';
    update_post_meta($post_id, '_gvspace_technology_title_en', $title_en);
});

function gvspace_render_case_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_case', 'gvspace_case_nonce');
    echo '<p class="description">Назва кейсу задається у стандартному полі заголовка, а обкладинка — у полі «Головне зображення».</p>';
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
    <p class="description">Назва українською задається у стандартному полі заголовка. Банер вакансії є спільним і задається у Next.js.</p>
    <?php foreach (GVSPACE_VACANCY_FIELDS as $key => $config) :
        $value = (string) get_post_meta($post->ID, '_gvspace_' . $key, true);
        ?>
        <p>
            <label for="gvspace_<?php echo esc_attr($key); ?>"><strong><?php echo esc_html($config['label']); ?></strong></label><br>
            <?php if ($config['type'] === 'textarea') : ?>
                <textarea id="gvspace_<?php echo esc_attr($key); ?>" name="gvspace_<?php echo esc_attr($key); ?>" rows="5" style="width:100%;"><?php echo esc_textarea($value); ?></textarea>
            <?php else : ?>
                <input id="gvspace_<?php echo esc_attr($key); ?>" name="gvspace_<?php echo esc_attr($key); ?>" type="text" value="<?php echo esc_attr($value); ?>" style="width:100%;">
            <?php endif; ?>
        </p>
    <?php endforeach;
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

    foreach (array_keys(GVSPACE_VACANCY_FIELDS) as $field) {
        $value = isset($_POST['gvspace_' . $field])
            ? sanitize_textarea_field(wp_unslash($_POST['gvspace_' . $field]))
            : '';
        update_post_meta($post_id, '_gvspace_' . $field, $value);
    }
});

add_action('graphql_register_types', function (): void {
    if (!function_exists('register_graphql_object_type')) {
        return;
    }

    register_graphql_field('Technology', 'technologyTitleEn', [
        'type' => 'String',
        'description' => 'English technology name.',
        'resolve' => static function ($source): string {
            return (string) get_post_meta((int) $source->databaseId, '_gvspace_technology_title_en', true);
        },
    ]);

    register_graphql_object_type('VacancyDetails', [
        'description' => 'Editable GVSPACE vacancy fields.',
        'fields' => [
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
            $lines = static function (string $key) use ($value): array {
                return array_values(array_filter(array_map('trim', preg_split('/\R/', $value($key)) ?: [])));
            };

            return [
                'titleEn' => $value('title_en'),
                'excerptUk' => $value('excerpt_uk'),
                'excerptEn' => $value('excerpt_en'),
                'salary' => $value('salary'),
                'hot' => (bool) get_post_meta($post_id, '_gvspace_hot', true),
                'tags' => $lines('tags'),
                'roleUk' => $lines('role_uk'),
                'roleEn' => $lines('role_en'),
                'tasksUk' => $lines('tasks_uk'),
                'tasksEn' => $lines('tasks_en'),
                'requirementsUk' => $lines('requirements_uk'),
                'requirementsEn' => $lines('requirements_en'),
                'tools' => $lines('tools'),
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

});

register_activation_hook(__FILE__, function (): void {
    do_action('init');
    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, 'flush_rewrite_rules');

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

const GVSPACE_DUPLICABLE_POST_TYPES = ['post', 'gv_case', 'gv_vacancy', 'gv_technology'];

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
