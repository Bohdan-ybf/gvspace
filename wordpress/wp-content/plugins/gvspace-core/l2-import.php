<?php

if (!defined('ABSPATH')) {
    exit;
}

function gvspace_l2_import_service_slug(WP_Post $post): string
{
    $catalog_slug = (string) get_post_meta($post->ID, '_gvspace_service_catalog_slug', true);
    $group = (string) get_post_meta($post->ID, '_gvspace_translation_group', true);
    return $catalog_slug !== '' ? $catalog_slug : ($group !== '' ? $group : $post->post_name);
}

function gvspace_l2_import_preview_payload(array $files, bool $replace_catalog): array
{
    $records = [];
    $errors = [];
    $seen = [];
    foreach ($files as $file) {
        $record = gvspace_l3_import_parse_markdown((string) $file['contents'], (string) $file['name']);
        if ($record['title'] === '' || $record['slug'] === '') {
            $errors[] = $record['filename'] . ': ' . implode(' ', $record['warnings'] ?: ['не вдалося розпізнати поля.']);
            continue;
        }
        $key = $record['slug'] . "\0" . $record['locale'];
        if (isset($seen[$key])) {
            $errors[] = $record['filename'] . ': «' . $record['slug'] . '» (' . $record['locale'] . ') уже є в цьому пакеті.';
            continue;
        }
        $seen[$key] = true;
        $records[] = $record;
    }

    $to_archive = [];
    if ($replace_catalog) {
        $keep = array_values(array_unique(array_column($records, 'slug')));
        $directions = get_posts([
            'post_type' => 'gv_service',
            'post_parent' => 0,
            'post_status' => ['publish', 'pending', 'private', 'future'],
            'numberposts' => -1,
            'suppress_filters' => true,
        ]);
        foreach ($directions as $direction) {
            $slug = gvspace_l2_import_service_slug($direction);
            if (in_array($slug, $keep, true)) {
                continue;
            }
            $children = get_posts([
                'post_type' => 'gv_service',
                'post_parent' => $direction->ID,
                'post_status' => ['publish', 'pending', 'private', 'future'],
                'numberposts' => -1,
                'suppress_filters' => true,
            ]);
            $to_archive[] = [
                'id' => (int) $direction->ID,
                'title' => $direction->post_title,
                'slug' => $slug,
                'children' => array_map(static fn (WP_Post $child): string => $child->post_title, $children),
            ];
        }
    }

    return [
        'replace_catalog' => $replace_catalog,
        'records' => $records,
        'archive' => $to_archive,
        'errors' => $errors,
    ];
}

function gvspace_l2_import_archive_directions(array $keep_slugs): array
{
    $directions = get_posts([
        'post_type' => 'gv_service',
        'post_parent' => 0,
        'post_status' => ['publish', 'pending', 'private', 'future'],
        'numberposts' => -1,
        'suppress_filters' => true,
    ]);
    $archived_l2 = 0;
    $archived_l3 = 0;
    foreach ($directions as $direction) {
        if (in_array(gvspace_l2_import_service_slug($direction), $keep_slugs, true)) {
            continue;
        }
        $children = get_posts([
            'post_type' => 'gv_service',
            'post_parent' => $direction->ID,
            'post_status' => ['publish', 'pending', 'private', 'future'],
            'numberposts' => -1,
            'suppress_filters' => true,
        ]);
        foreach ($children as $child) {
            wp_update_post([
                'ID' => $child->ID,
                'post_status' => 'draft',
            ]);
            update_post_meta($child->ID, '_gvspace_translation_status', 'draft');
            update_post_meta($child->ID, '_gvspace_service_archived', 'l2-import');
            clean_post_cache($child->ID);
            $archived_l3++;
        }
        wp_update_post([
            'ID' => $direction->ID,
            'post_status' => 'draft',
        ]);
        update_post_meta($direction->ID, '_gvspace_translation_status', 'draft');
        update_post_meta($direction->ID, '_gvspace_service_archived', 'l2-import');
        clean_post_cache($direction->ID);
        $archived_l2++;
    }
    return ['l2' => $archived_l2, 'l3' => $archived_l3];
}

function gvspace_l2_import_upsert_direction(array $fields, int $order): int
{
    $slug = (string) $fields['slug'];
    $existing = get_posts([
        'post_type' => 'gv_service',
        'post_parent' => 0,
        'post_status' => 'any',
        'numberposts' => 1,
        'meta_key' => '_gvspace_service_catalog_slug',
        'meta_value' => $slug,
    ]);
    $post_id = $existing ? (int) $existing[0]->ID : 0;
    if (!$post_id) {
        $by_name = get_posts([
            'post_type' => 'gv_service',
            'post_parent' => 0,
            'post_status' => 'any',
            'name' => $slug,
            'numberposts' => 1,
        ]);
        $post_id = $by_name ? (int) $by_name[0]->ID : 0;
    }

    $locale = gvspace_l3_import_normalize_locale((string) ($fields['locale'] ?? 'uk')) ?: 'uk';
    $title = sanitize_text_field((string) ($fields['title'] ?? ''));
    $post_data = [
        'post_type' => 'gv_service',
        'post_status' => 'publish',
        'post_name' => $slug,
        'post_parent' => 0,
        'menu_order' => $order,
    ];
    if ($post_id) {
        $post_data['ID'] = $post_id;
        if ($locale === 'uk' && $title !== '') {
            $post_data['post_title'] = $title;
        }
        $updated = wp_update_post($post_data, true);
        if (is_wp_error($updated)) {
            return 0;
        }
    } else {
        $post_data['post_title'] = $title !== '' ? $title : $slug;
        $created = wp_insert_post($post_data, true);
        if (!$created || is_wp_error($created)) {
            return 0;
        }
        $post_id = (int) $created;
    }

    update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($post_id, '_gvspace_translation_group', $slug);
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    update_post_meta($post_id, '_gvspace_service_catalog_slug', $slug);
    delete_post_meta($post_id, '_gvspace_service_archived');

    update_post_meta($post_id, '_gvspace_service_title_' . $locale, $title);
    foreach (['headline', 'description', 'steps', 'faq'] as $field) {
        update_post_meta($post_id, '_gvspace_service_' . $field . '_' . $locale, sanitize_textarea_field((string) ($fields[$field] ?? '')));
    }
    foreach ((array) ($fields['seo'] ?? []) as $seo_field => $value) {
        $clean = gvspace_sanitize_seo_meta_value((string) $seo_field, (string) $value);
        update_post_meta($post_id, '_gvspace_seo_' . $seo_field . '_' . $locale, $clean);
    }
    clean_post_cache($post_id);
    return $post_id;
}

function gvspace_l2_import_apply(array $payload): array
{
    $records = (array) ($payload['records'] ?? []);
    if (!$records) {
        return ['ok' => false, 'message' => 'Немає розпізнаних напрямків для імпорту.'];
    }

    $keep = array_values(array_unique(array_filter(array_map(static fn (array $record): string => (string) $record['slug'], $records))));
    $archived = !empty($payload['replace_catalog'])
        ? gvspace_l2_import_archive_directions($keep)
        : ['l2' => 0, 'l3' => 0];

    $order_by_slug = [];
    foreach ($records as $index => $record) {
        $slug = (string) $record['slug'];
        if (!isset($order_by_slug[$slug])) {
            $order_by_slug[$slug] = (int) $index;
        }
    }

    $created = 0;
    $locales = [];
    foreach ($records as $record) {
        $order = $order_by_slug[(string) $record['slug']] ?? 0;
        if (gvspace_l2_import_upsert_direction($record, $order)) {
            $created++;
            $locales[(string) $record['locale']] = true;
        }
    }
    flush_rewrite_rules(false);
    return [
        'ok' => true,
        'message' => sprintf(
            'Імпорт L2 завершено: оновлено %d мовних версій (%s). В архів перенесено %d напрямків і %d їхніх L3.',
            $created,
            implode(', ', array_keys($locales)) ?: '—',
            (int) $archived['l2'],
            (int) $archived['l3']
        ),
    ];
}

function gvspace_l2_import_transient_key(): string
{
    return 'gvspace_l2_import_' . get_current_user_id();
}

add_action('admin_menu', static function (): void {
    add_submenu_page(
        'edit.php?post_type=gv_service',
        'Імпорт напрямків L2',
        'Імпорт L2',
        'edit_posts',
        'gvspace-import-l2',
        'gvspace_render_l2_import_page'
    );
}, 9);

add_action('admin_init', static function (): void {
    if (!is_admin() || !current_user_can('edit_posts')) {
        return;
    }
    if (($_GET['page'] ?? '') !== 'gvspace-import-l2' || ($_GET['download'] ?? '') !== 'template') {
        return;
    }
    check_admin_referer('gvspace_l2_import_template');
    $path = __DIR__ . '/templates/l2-direction.md';
    if (!is_readable($path)) {
        wp_die('Шаблон не знайдено.');
    }
    nocache_headers();
    header('Content-Type: text/markdown; charset=utf-8');
    header('Content-Disposition: attachment; filename="gvspace-l2-direction.md"');
    readfile($path);
    exit;
});

function gvspace_render_l2_import_page(): void
{
    if (!current_user_can('edit_posts')) {
        return;
    }

    $notice = '';
    $notice_type = 'info';
    $preview = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        check_admin_referer('gvspace_l2_import');
        $action = sanitize_key((string) ($_POST['gvspace_l2_action'] ?? ''));
        if ($action === 'preview') {
            $uploads = gvspace_l3_import_collect_uploads('gvspace_l2_files');
            if ($uploads['error'] !== '' && !$uploads['files']) {
                $notice = $uploads['error'];
                $notice_type = 'error';
            } else {
                $replace_catalog = !empty($_POST['gvspace_l2_replace_catalog']);
                $preview = gvspace_l2_import_preview_payload($uploads['files'], $replace_catalog);
                if ($uploads['error'] !== '') {
                    $preview['errors'][] = $uploads['error'];
                }
                if ($preview['records']) {
                    set_transient(gvspace_l2_import_transient_key(), $preview, 30 * MINUTE_IN_SECONDS);
                } else {
                    $notice = $preview['errors'] ? implode(' ', $preview['errors']) : 'Не вдалося розпізнати жодного напрямку.';
                    $notice_type = 'error';
                    $preview = null;
                }
            }
        } elseif ($action === 'apply') {
            $stored = get_transient(gvspace_l2_import_transient_key());
            if (!is_array($stored) || empty($stored['records'])) {
                $notice = 'Прев’ю застаріло. Завантажте файли ще раз.';
                $notice_type = 'error';
            } else {
                $result = gvspace_l2_import_apply($stored);
                $notice = $result['message'];
                $notice_type = $result['ok'] ? 'success' : 'error';
                if ($result['ok']) {
                    delete_transient(gvspace_l2_import_transient_key());
                }
            }
        } elseif ($action === 'cancel') {
            delete_transient(gvspace_l2_import_transient_key());
            $notice = 'Імпорт скасовано. Файли не застосовано.';
        }
    }

    $template_url = wp_nonce_url(
        admin_url('edit.php?post_type=gv_service&page=gvspace-import-l2&download=template'),
        'gvspace_l2_import_template'
    );
    ?>
    <div class="wrap">
        <h1>Імпорт напрямків L2</h1>
        <p>Завантажте файли маркетолога у форматі адмінки. Один файл = один напрямок однією мовою. Після L2 залийте послуги через <a href="<?php echo esc_url(admin_url('edit.php?post_type=gv_service&page=gvspace-import-l3')); ?>">Імпорт L3</a> в кожен напрямок. Українська вже залита не затирається, якщо в пакеті лише інші мови.</p>
        <p><a class="button" href="<?php echo esc_url($template_url); ?>">Завантажити шаблон .md</a></p>
        <?php if ($notice !== '') : ?>
            <div class="notice notice-<?php echo esc_attr($notice_type); ?> is-dismissible"><p><?php echo esc_html($notice); ?></p></div>
        <?php endif; ?>

        <?php if (is_array($preview)) : ?>
            <h2>Прев’ю</h2>
            <?php if ($preview['errors']) : ?>
                <div class="notice notice-warning"><p><?php echo esc_html(implode(' ', $preview['errors'])); ?></p></div>
            <?php endif; ?>
            <p>Архів каталогу: <strong><?php echo !empty($preview['replace_catalog']) ? 'так' : 'ні, лише дописуємо мови'; ?></strong></p>
            <h3>Будуть записані (<?php echo count($preview['records']); ?>)</h3>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>Файл</th>
                        <th>Slug</th>
                        <th>Мова</th>
                        <th>Назва</th>
                        <th>H1</th>
                        <th>Поля</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($preview['records'] as $record) : ?>
                    <tr>
                        <td><?php echo esc_html($record['filename']); ?></td>
                        <td><code><?php echo esc_html($record['slug']); ?></code></td>
                        <td><code><?php echo esc_html($record['locale']); ?></code></td>
                        <td><?php echo esc_html($record['title']); ?></td>
                        <td><?php echo esc_html($record['headline']); ?></td>
                        <td>
                            <?php
                            $filled = [];
                            foreach (['steps' => 'етапи', 'faq' => 'FAQ'] as $key => $label) {
                                if (trim((string) $record[$key]) !== '') {
                                    $filled[] = $label;
                                }
                            }
                            echo esc_html($filled ? implode(', ', $filled) : 'лише назва / H1');
                            ?>
                            <?php if ($record['warnings']) : ?>
                                <br><span class="description"><?php echo esc_html(implode(' ', $record['warnings'])); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <h3>Підуть в архів (<?php echo count($preview['archive']); ?>)</h3>
            <?php if ($preview['archive']) : ?>
                <ul>
                    <?php foreach ($preview['archive'] as $item) : ?>
                        <li>
                            <?php echo esc_html($item['title']); ?> <code><?php echo esc_html($item['slug']); ?></code>
                            <?php if (!empty($item['children'])) : ?>
                                — разом із L3: <?php echo esc_html(implode(', ', $item['children'])); ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>Опублікованих L2, яких немає у файлах, не знайдено. Існуючі з тим самим slug будуть оновлені.</p>
            <?php endif; ?>
            <form method="post">
                <?php wp_nonce_field('gvspace_l2_import'); ?>
                <p>
                    <button class="button button-primary" name="gvspace_l2_action" value="apply" type="submit">Підтвердити імпорт</button>
                    <button class="button" name="gvspace_l2_action" value="cancel" type="submit">Скасувати</button>
                </p>
            </form>
        <?php else : ?>
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('gvspace_l2_import'); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="gvspace_l2_files">Файли</label></th>
                        <td>
                            <input id="gvspace_l2_files" name="gvspace_l2_files[]" type="file" accept=".md,.zip,text/markdown,application/zip" multiple required>
                            <p class="description">Кілька .md або один .zip. Мова з імені: <code>gvspace-l2-development.md</code> = uk, <code>gvspace-l2-development.en.md</code> = en. Можна також вказати поле «Мова» в файлі.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Каталог</th>
                        <td>
                            <label>
                                <input type="checkbox" name="gvspace_l2_replace_catalog" value="1" checked>
                                Замінити каталог L2: напрямки, яких немає у файлах, разом із їхніми L3 підуть у чернетки
                            </label>
                            <p class="description">Зніміть галочку, якщо доливаєте переклади до вже існуючих напрямків. Інші мови в адмінці не затираються в будь-якому разі. L3 під напрямками, які залишаються, ця галочка не чіпає — їх оновлюйте через Імпорт L3.</p>
                        </td>
                    </tr>
                </table>
                <p><button class="button button-primary" name="gvspace_l2_action" value="preview" type="submit">Показати прев’ю</button></p>
            </form>
        <?php endif; ?>
    </div>
    <?php
}
