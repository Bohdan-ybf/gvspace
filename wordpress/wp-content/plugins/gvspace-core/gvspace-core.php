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
