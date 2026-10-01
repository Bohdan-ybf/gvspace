<?php

if (!defined('ABSPATH')) {
    exit;
}

function gvspace_service_icon_key(string $value): string
{
    $value = mb_strtolower(trim($value), 'UTF-8');
    $value = str_replace(['_', '—', '–', '−', '.'], '-', $value);
    $value = preg_replace('/\s+/u', '-', $value) ?? $value;
    $value = preg_replace('/[^\p{L}\p{N}\-]+/u', '', $value) ?? $value;
    $value = preg_replace('/-+/u', '-', $value) ?? $value;
    return trim($value, '-');
}

function gvspace_service_icon_keys(string ...$values): array
{
    $keys = [];
    foreach ($values as $value) {
        $value = trim(wp_strip_all_tags($value));
        if ($value === '') {
            continue;
        }
        $plain = gvspace_service_icon_key($value);
        if (mb_strlen($plain) >= 2) {
            $keys[$plain] = true;
        }
    }
    return array_keys($keys);
}

function gvspace_service_icon_catalog(): array
{
    $posts = get_posts([
        'post_type' => 'gv_service',
        'post_status' => ['publish', 'pending', 'private', 'future', 'draft'],
        'numberposts' => -1,
        'orderby' => ['menu_order' => 'ASC', 'title' => 'ASC'],
        'suppress_filters' => true,
    ]);

    $by_id = [];
    foreach ($posts as $post) {
        if ((string) get_post_meta($post->ID, '_gvspace_service_archived', true) !== '') {
            continue;
        }
        $by_id[(int) $post->ID] = $post;
    }

    $catalog = [];
    foreach ($by_id as $post) {
        $parent_id = (int) $post->post_parent;
        $parent = $parent_id && isset($by_id[$parent_id]) ? $by_id[$parent_id] : null;
        $title_uk = (string) get_post_meta($post->ID, '_gvspace_service_title_uk', true);
        $title_en = (string) get_post_meta($post->ID, '_gvspace_service_title_en', true);
        if ($title_uk === '') {
            $title_uk = $post->post_title;
        }
        $slug = gvspace_l2_import_service_slug($post);
        $parent_title_uk = '';
        $parent_slug = '';
        $parent_keys = [];
        if ($parent) {
            $parent_title_uk = (string) get_post_meta($parent->ID, '_gvspace_service_title_uk', true);
            if ($parent_title_uk === '') {
                $parent_title_uk = $parent->post_title;
            }
            $parent_title_en = (string) get_post_meta($parent->ID, '_gvspace_service_title_en', true);
            $parent_slug = gvspace_l2_import_service_slug($parent);
            $parent_keys = gvspace_service_icon_keys($parent_slug, $parent->post_name, $parent_title_uk, $parent->post_title, $parent_title_en);
        }

        $catalog[] = [
            'id' => (int) $post->ID,
            'level' => $parent_id === 0 ? 'l2' : 'l3',
            'parent_id' => $parent ? $parent_id : 0,
            'title' => $title_uk !== '' ? $title_uk : $post->post_title,
            'title_en' => $title_en,
            'slug' => $slug,
            'parent_title' => $parent_title_uk,
            'parent_slug' => $parent_slug,
            'menu_order' => (int) $post->menu_order,
            'has_icon' => (bool) get_post_thumbnail_id($post->ID),
            'keys' => gvspace_service_icon_keys($slug, $post->post_name, $title_uk, $post->post_title, $title_en),
            'parent_keys' => $parent_keys,
        ];
    }

    return $catalog;
}

function gvspace_service_icon_parse_name(string $relative): array
{
    $relative = str_replace('\\', '/', $relative);
    $relative = ltrim($relative, '/');
    $segments = [];
    foreach (explode('/', $relative) as $part) {
        if ($part === '' || $part === '.' || $part === '..' || str_contains($part, "\0")) {
            continue;
        }
        $segments[] = $part;
    }
    if (!$segments) {
        return ['level' => '', 'stem' => '', 'parent_key' => ''];
    }

    $level = '';
    $parent_key = '';
    foreach (array_slice($segments, 0, -1) as $folder) {
        $folder_key = gvspace_service_icon_key($folder);
        if ($folder_key === 'l2' || $folder_key === 'l3') {
            $level = $folder_key;
            continue;
        }
        if ($folder_key !== '') {
            $parent_key = $folder_key;
        }
    }

    $stem = (string) pathinfo($segments[count($segments) - 1], PATHINFO_FILENAME);
    if (preg_match('/^(l[23])[-_\s]+(.+)$/iu', $stem, $matches)) {
        $level = strtolower($matches[1]);
        $stem = $matches[2];
    }

    return [
        'level' => $level,
        'stem' => $stem,
        'parent_key' => $parent_key,
    ];
}

function gvspace_service_icon_match_ids(string $relative, array $catalog): array
{
    $parsed = gvspace_service_icon_parse_name($relative);
    $wanted = gvspace_service_icon_keys($parsed['stem']);
    if (!$wanted) {
        return [];
    }

    $candidates = [];
    foreach ($catalog as $service) {
        if ($parsed['level'] !== '' && $service['level'] !== $parsed['level']) {
            continue;
        }
        if (!array_intersect($wanted, $service['keys'])) {
            continue;
        }
        $candidates[$service['id']] = $service;
    }

    $parent_key = $parsed['parent_key'];
    if ($parent_key === '' || !$candidates) {
        return array_map('intval', array_keys($candidates));
    }

    $known_parent = false;
    foreach ($catalog as $service) {
        if (in_array($parent_key, $service['parent_keys'], true) || ($service['level'] === 'l2' && in_array($parent_key, $service['keys'], true))) {
            $known_parent = true;
            break;
        }
    }
    if (!$known_parent) {
        return array_map('intval', array_keys($candidates));
    }

    $narrowed = [];
    foreach ($candidates as $id => $service) {
        $matches_parent = $service['level'] === 'l3' && in_array($parent_key, $service['parent_keys'], true);
        $matches_self = $service['level'] === 'l2' && in_array($parent_key, $service['keys'], true);
        if ($matches_parent || $matches_self) {
            $narrowed[$id] = $service;
        }
    }
    return array_map('intval', array_keys($narrowed));
}

function gvspace_service_icons_transient_key(): string
{
    return 'gvspace_service_icons_' . get_current_user_id();
}

function gvspace_service_icons_pending_ids(): array
{
    $ids = get_posts([
        'post_type' => 'attachment',
        'post_status' => 'any',
        'numberposts' => -1,
        'fields' => 'ids',
        'meta_key' => '_gvspace_service_icon_pending',
        'meta_value' => (string) get_current_user_id(),
        'suppress_filters' => true,
    ]);
    return array_map('intval', is_array($ids) ? $ids : []);
}

function gvspace_service_icons_cleanup_pending(array $keep_ids): void
{
    foreach (gvspace_service_icons_pending_ids() as $attachment_id) {
        if (in_array($attachment_id, $keep_ids, true)) {
            continue;
        }
        wp_delete_attachment($attachment_id, true);
    }
}

function gvspace_service_icons_batch_ids(?array $batch): array
{
    if (!is_array($batch) || empty($batch['items']) || !is_array($batch['items'])) {
        return [];
    }
    return array_values(array_filter(array_map(static fn ($item): int => (int) ($item['attachment_id'] ?? 0), $batch['items'])));
}

function gvspace_service_icons_folder_term_id(): int
{
    $existing = get_term_by('slug', 'service-icons', 'gv_media_folder');
    if ($existing && !is_wp_error($existing)) {
        return (int) $existing->term_id;
    }
    $created = wp_insert_term('Іконки послуг', 'gv_media_folder', ['slug' => 'service-icons']);
    if (is_wp_error($created) || empty($created['term_id'])) {
        return 0;
    }
    return (int) $created['term_id'];
}

function gvspace_service_icons_stage_file(string $tmp_path, string $filename): int|WP_Error
{
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $checked = wp_check_filetype_and_ext($tmp_path, $filename, [
        'webp' => 'image/webp',
        'png' => 'image/png',
    ]);
    $ext = strtolower((string) ($checked['ext'] ?? ''));
    if (!in_array($ext, ['webp', 'png'], true)) {
        return new WP_Error('gvspace_icon_type', 'Потрібен WebP або PNG.');
    }

    $safe_name = sanitize_file_name($filename);
    $safe_stem = (string) pathinfo($safe_name, PATHINFO_FILENAME);
    if ($safe_stem === '' || $safe_stem === '.') {
        $safe_name = 'service-icon-' . wp_generate_password(6, false) . '.' . $ext;
    }

    $attachment_id = media_handle_sideload([
        'name' => $safe_name,
        'tmp_name' => $tmp_path,
    ], 0);

    if (is_wp_error($attachment_id)) {
        return $attachment_id;
    }

    update_post_meta((int) $attachment_id, '_gvspace_service_icon_pending', (string) get_current_user_id());
    return (int) $attachment_id;
}

function gvspace_service_icons_read_zip(string $tmp_path): array
{
    if (!class_exists(ZipArchive::class)) {
        return ['files' => [], 'error' => 'На сервері немає ZipArchive. Завантажте зображення окремими файлами.'];
    }
    $zip = new ZipArchive();
    if ($zip->open($tmp_path) !== true) {
        return ['files' => [], 'error' => 'Не вдалося відкрити ZIP.'];
    }

    $files = [];
    $errors = [];
    $count = 0;
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $name = (string) $zip->getNameIndex($i);
        if ($name === '' || str_ends_with($name, '/') || str_contains($name, '__MACOSX') || str_contains($name, "\0")) {
            continue;
        }
        $basename = basename(str_replace('\\', '/', $name));
        if ($basename === '' || str_starts_with($basename, '.') || strcasecmp($basename, 'Thumbs.db') === 0) {
            continue;
        }
        $ext = strtolower((string) pathinfo($basename, PATHINFO_EXTENSION));
        if (!in_array($ext, ['webp', 'png'], true)) {
            continue;
        }
        $stat = $zip->statIndex($i);
        if (($stat['size'] ?? 0) > 8 * 1024 * 1024) {
            $errors[] = $basename . ' більший за 8 МБ.';
            continue;
        }
        if ($count >= 300) {
            $errors[] = 'У пакеті більше 300 зображень. Решту пропущено.';
            break;
        }
        $bytes = $zip->getFromIndex($i);
        if (!is_string($bytes) || $bytes === '') {
            $errors[] = $basename . ' порожній.';
            continue;
        }
        $temp = wp_tempnam($basename);
        if (!$temp) {
            $errors[] = $basename . ': не вдалося підготувати тимчасовий файл.';
            continue;
        }
        file_put_contents($temp, $bytes);
        $files[] = [
            'name' => $basename,
            'relative' => str_replace('\\', '/', $name),
            'tmp' => $temp,
        ];
        $count++;
    }
    $zip->close();
    if (!$files && !$errors) {
        $errors[] = 'У ZIP немає файлів WebP або PNG.';
    }
    return ['files' => $files, 'error' => implode(' ', $errors)];
}

function gvspace_service_icons_collect_uploads(string $field = 'gvspace_service_icon_files'): array
{
    if (empty($_FILES[$field]) || !is_array($_FILES[$field]['name'] ?? null)) {
        return ['files' => [], 'error' => 'Додайте WebP, PNG або ZIP з іконками.'];
    }

    $bag = $_FILES[$field];
    $count = count((array) $bag['name']);
    $files = [];
    $errors = [];

    for ($i = 0; $i < $count; $i++) {
        $error = (int) ($bag['error'][$i] ?? UPLOAD_ERR_NO_FILE);
        $name = (string) ($bag['name'][$i] ?? '');
        if ($error === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ($error !== UPLOAD_ERR_OK) {
            $errors[] = 'Не вдалося завантажити ' . sanitize_file_name($name) . '.';
            continue;
        }
        $tmp = (string) ($bag['tmp_name'][$i] ?? '');
        $ext = strtolower((string) pathinfo($name, PATHINFO_EXTENSION));
        if ($ext === 'zip') {
            $extracted = gvspace_service_icons_read_zip($tmp);
            if ($extracted['error'] !== '') {
                $errors[] = $extracted['error'];
            }
            $files = array_merge($files, $extracted['files']);
            continue;
        }
        if (!in_array($ext, ['webp', 'png'], true)) {
            $errors[] = $name . ' — потрібен WebP, PNG або ZIP.';
            continue;
        }
        $files[] = [
            'name' => $name,
            'relative' => $name,
            'tmp' => $tmp,
        ];
    }

    if (!$files && !$errors) {
        $errors[] = 'Додайте WebP, PNG або ZIP з іконками.';
    }

    return ['files' => $files, 'error' => implode(' ', $errors)];
}

function gvspace_service_icons_preview_payload(array $files, bool $replace, array $catalog): array
{
    $items = [];
    $errors = [];
    $used = [];
    $lookup = [];
    foreach ($catalog as $service) {
        $lookup[$service['id']] = $service;
    }

    foreach ($files as $file) {
        $relative = (string) ($file['relative'] ?? $file['name']);
        $attachment_id = gvspace_service_icons_stage_file((string) $file['tmp'], (string) $file['name']);
        if (is_wp_error($attachment_id)) {
            if (!empty($file['tmp']) && is_file((string) $file['tmp'])) {
                unlink((string) $file['tmp']);
            }
            $errors[] = (string) $file['name'] . ': ' . $attachment_id->get_error_message();
            continue;
        }

        $match_ids = gvspace_service_icon_match_ids($relative, $catalog);
        $status = 'unmatched';
        $post_id = 0;
        if (count($match_ids) === 1) {
            $post_id = $match_ids[0];
            if (isset($used[$post_id])) {
                $status = 'duplicate';
            } elseif (!$replace && !empty($lookup[$post_id]['has_icon'])) {
                $status = 'exists';
            } else {
                $status = 'matched';
                $used[$post_id] = (string) $file['name'];
            }
        } elseif (count($match_ids) > 1) {
            $status = 'ambiguous';
        }

        $items[] = [
            'attachment_id' => (int) $attachment_id,
            'filename' => (string) $file['name'],
            'relative' => $relative,
            'status' => $status,
            'post_id' => $post_id,
            'candidate_ids' => $match_ids,
            'had_icon' => $post_id && !empty($lookup[$post_id]['has_icon']),
        ];
    }

    return [
        'replace' => $replace,
        'items' => $items,
        'errors' => $errors,
    ];
}

function gvspace_service_icons_apply(array $batch): array
{
    $items = (array) ($batch['items'] ?? []);
    $assigned = 0;
    $skipped = 0;
    $user_id = (string) get_current_user_id();
    $folder_id = gvspace_service_icons_folder_term_id();

    foreach ($items as $item) {
        $attachment_id = (int) ($item['attachment_id'] ?? 0);
        $post_id = (int) ($item['post_id'] ?? 0);
        $status = (string) ($item['status'] ?? '');
        if ($attachment_id <= 0 || get_post_type($attachment_id) !== 'attachment') {
            continue;
        }
        if ((string) get_post_meta($attachment_id, '_gvspace_service_icon_pending', true) !== $user_id) {
            continue;
        }
        if ($status !== 'matched' || $post_id <= 0 || get_post_type($post_id) !== 'gv_service') {
            $skipped++;
            continue;
        }

        set_post_thumbnail($post_id, $attachment_id);
        delete_post_meta($attachment_id, '_gvspace_service_icon_pending');
        $title = (string) get_post_meta($post_id, '_gvspace_service_title_uk', true);
        if ($title === '') {
            $title = get_the_title($post_id);
        }
        wp_update_post([
            'ID' => $attachment_id,
            'post_title' => $title,
        ]);
        if ($folder_id) {
            wp_set_object_terms($attachment_id, [$folder_id], 'gv_media_folder', false);
        }
        clean_post_cache($post_id);
        $assigned++;
    }

    return [
        'ok' => true,
        'message' => sprintf('Іконки призначено: %d. Без збігу або пропущено: %d.', $assigned, $skipped),
    ];
}

function gvspace_service_icon_filename(array $service): string
{
    $name = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', (string) $service['title']);
    $name = trim($name);
    if ($name === '') {
        $name = (string) $service['slug'];
    }
    return $name . '.webp';
}
function gvspace_service_icons_csv(array $catalog): string
{
    $rows = ["\xEF\xBB\xBF" . 'Рівень;Напрямок;Назва;Назва EN;Slug;Ім’я файлу;Іконка'];
    foreach (gvspace_service_icons_sorted_catalog($catalog) as $service) {
        $rows[] = implode(';', [
            $service['level'] === 'l2' ? 'L2' : 'L3',
            gvspace_service_icons_csv_cell($service['parent_title']),
            gvspace_service_icons_csv_cell($service['title']),
            gvspace_service_icons_csv_cell($service['title_en']),
            gvspace_service_icons_csv_cell($service['slug']),
            gvspace_service_icons_csv_cell(gvspace_service_icon_filename($service)),
            $service['has_icon'] ? 'є' : 'немає',
        ]);
    }
    return implode("\r\n", $rows) . "\r\n";
}

function gvspace_service_icons_csv_cell(string $value): string
{
    $value = str_replace(["\r", "\n", ';'], [' ', ' ', ','], $value);
    if (preg_match('/^[=+\-@]/', $value)) {
        $value = "'" . $value;
    }
    return $value;
}

function gvspace_service_icons_sorted_catalog(array $catalog): array
{
    $directions = array_values(array_filter($catalog, static fn (array $service): bool => $service['level'] === 'l2'));
    usort($directions, static function (array $a, array $b): int {
        return [$a['menu_order'], $a['title']] <=> [$b['menu_order'], $b['title']];
    });

    $children = [];
    foreach ($catalog as $service) {
        if ($service['level'] !== 'l3') {
            continue;
        }
        $children[$service['parent_id']][] = $service;
    }
    foreach ($children as &$group) {
        usort($group, static function (array $a, array $b): int {
            return [$a['menu_order'], $a['title']] <=> [$b['menu_order'], $b['title']];
        });
    }
    unset($group);

    $sorted = [];
    $seen = [];
    foreach ($directions as $direction) {
        $sorted[] = $direction;
        $seen[$direction['id']] = true;
        foreach ($children[$direction['id']] ?? [] as $child) {
            $sorted[] = $child;
            $seen[$child['id']] = true;
        }
    }
    foreach ($catalog as $service) {
        if (!isset($seen[$service['id']])) {
            $sorted[] = $service;
        }
    }
    return $sorted;
}

function gvspace_service_icons_find(array $catalog, int $post_id): ?array
{
    foreach ($catalog as $service) {
        if ((int) $service['id'] === $post_id) {
            return $service;
        }
    }
    return null;
}

function gvspace_service_icons_status_label(string $status, bool $had_icon): string
{
    return match ($status) {
        'matched' => $had_icon ? 'Замінить поточну іконку' : 'Буде призначено',
        'exists' => 'Уже є іконка, файл пропущено',
        'duplicate' => 'Дубль: цю послугу вже закриває інший файл',
        'ambiguous' => 'Кілька послуг з такою назвою',
        default => 'Послугу не знайдено',
    };
}

add_action('admin_menu', static function (): void {
    add_submenu_page(
        'edit.php?post_type=gv_service',
        'Іконки банерів',
        'Іконки банерів',
        'edit_posts',
        'gvspace-service-icons',
        'gvspace_render_service_icons_page'
    );
}, 11);

add_action('admin_init', static function (): void {
    if (!is_admin() || !current_user_can('edit_posts')) {
        return;
    }
    if (($_GET['page'] ?? '') !== 'gvspace-service-icons' || ($_GET['download'] ?? '') !== 'names') {
        return;
    }
    check_admin_referer('gvspace_service_icons_names');
    nocache_headers();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="gvspace-service-icon-names.csv"');
    echo gvspace_service_icons_csv(gvspace_service_icon_catalog());
    exit;
});

add_filter('manage_gv_service_posts_columns', static function (array $columns): array {
    $result = [];
    foreach ($columns as $key => $label) {
        if ($key === 'date') {
            $result['gvspace_service_icon'] = 'Іконка';
        }
        $result[$key] = $label;
    }
    if (!isset($result['gvspace_service_icon'])) {
        $result['gvspace_service_icon'] = 'Іконка';
    }
    return $result;
});

add_action('manage_gv_service_posts_custom_column', static function (string $column, int $post_id): void {
    if ($column !== 'gvspace_service_icon') {
        return;
    }
    $thumbnail_id = (int) get_post_thumbnail_id($post_id);
    if (!$thumbnail_id) {
        echo '—';
        return;
    }
    echo wp_get_attachment_image($thumbnail_id, [48, 48], false, [
        'style' => 'width:36px;height:36px;object-fit:contain',
    ]);
}, 10, 2);

function gvspace_render_service_icons_page(): void
{
    if (!current_user_can('edit_posts')) {
        return;
    }
    if (!current_user_can('upload_files')) {
        echo '<div class="wrap"><h1>Іконки банерів</h1><div class="notice notice-error"><p>Для завантаження іконок потрібне право upload_files.</p></div></div>';
        return;
    }

    $notice = '';
    $notice_type = 'info';
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if ($method === 'POST' && empty($_POST) && empty($_FILES)) {
        $notice = 'Пакет завеликий для сервера. Складіть іконки в менший ZIP.';
        $notice_type = 'error';
    } elseif ($method === 'POST') {
        check_admin_referer('gvspace_service_icons');
        $action = sanitize_key((string) ($_POST['gvspace_service_icons_action'] ?? ''));
        if ($action === 'preview') {
            gvspace_service_icons_cleanup_pending([]);
            delete_transient(gvspace_service_icons_transient_key());
            $uploads = gvspace_service_icons_collect_uploads();
            if ($uploads['error'] !== '' && !$uploads['files']) {
                $notice = $uploads['error'];
                $notice_type = 'error';
            } else {
                $replace = !empty($_POST['gvspace_service_icons_replace']);
                $preview = gvspace_service_icons_preview_payload($uploads['files'], $replace, gvspace_service_icon_catalog());
                if ($uploads['error'] !== '') {
                    $preview['errors'][] = $uploads['error'];
                }
                if ($preview['items']) {
                    set_transient(gvspace_service_icons_transient_key(), $preview, 30 * MINUTE_IN_SECONDS);
                } else {
                    $notice = $preview['errors'] ? implode(' ', $preview['errors']) : 'Не вдалося прочитати жодного зображення.';
                    $notice_type = 'error';
                }
            }
        } elseif ($action === 'apply') {
            $stored = get_transient(gvspace_service_icons_transient_key());
            if (!is_array($stored) || empty($stored['items'])) {
                $notice = 'Прев’ю застаріло. Завантажте файли ще раз.';
                $notice_type = 'error';
            } else {
                $result = gvspace_service_icons_apply($stored);
                delete_transient(gvspace_service_icons_transient_key());
                gvspace_service_icons_cleanup_pending([]);
                $notice = $result['message'];
                $notice_type = $result['ok'] ? 'success' : 'error';
            }
        } elseif ($action === 'cancel') {
            delete_transient(gvspace_service_icons_transient_key());
            gvspace_service_icons_cleanup_pending([]);
            $notice = 'Завантаження скасовано. Іконки послуг не змінено.';
        }
    }

    $batch = get_transient(gvspace_service_icons_transient_key());
    if (!is_array($batch) || empty($batch['items'])) {
        $batch = null;
    }
    gvspace_service_icons_cleanup_pending(gvspace_service_icons_batch_ids($batch));

    $catalog = gvspace_service_icon_catalog();
    $missing = array_values(array_filter($catalog, static fn (array $service): bool => !$service['has_icon']));
    $names_url = wp_nonce_url(
        admin_url('edit.php?post_type=gv_service&page=gvspace-service-icons&download=names'),
        'gvspace_service_icons_names'
    );
    $lookup = [];
    foreach ($catalog as $service) {
        $lookup[$service['id']] = $service;
    }
    ?>
    <div class="wrap">
        <h1>Іконки банерів</h1>
        <p>Завантажте пачку 3D-іконок. Файл стає іконкою банера тієї послуги, чиє ім’я збігається з назвою файлу. Підходить slug, українська назва або англійська назва. Формат — WebP або PNG з прозорим фоном, близько 1000 × 800 px.</p>
        <p>Приклади: <code>geo-audit.webp</code>, <code>GEO-аудит.png</code>, <code>l3-geo-audit.webp</code>. Якщо однакова назва є в кількох напрямках, покладіть файл у папку напрямку (<code>seo-prosuvannya/geo-audit.webp</code>) або додайте префікс <code>l2-</code> / <code>l3-</code>. Можна вибрати багато файлів одразу або один ZIP.</p>
        <p>
            <a class="button" href="<?php echo esc_url($names_url); ?>">Завантажити список назв для маркетолога</a>
            <span class="description" style="margin-left:8px;">Без іконки: <?php echo (int) count($missing); ?> з <?php echo (int) count($catalog); ?>.</span>
        </p>
        <?php if ($notice !== '') : ?>
            <div class="notice notice-<?php echo esc_attr($notice_type); ?> is-dismissible"><p><?php echo esc_html($notice); ?></p></div>
        <?php endif; ?>

        <?php if (is_array($batch)) : ?>
            <?php
            $matched = count(array_filter($batch['items'], static fn (array $item): bool => $item['status'] === 'matched'));
            ?>
            <h2>Прев’ю</h2>
            <?php if (!empty($batch['errors'])) : ?>
                <div class="notice notice-warning"><p><?php echo esc_html(implode(' ', $batch['errors'])); ?></p></div>
            <?php endif; ?>
            <p>Заміна наявних іконок: <strong><?php echo !empty($batch['replace']) ? 'так' : 'ні'; ?></strong>. Буде призначено: <strong><?php echo (int) $matched; ?></strong> з <?php echo count($batch['items']); ?>.</p>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>Файл</th>
                        <th>Іконка</th>
                        <th>Результат</th>
                        <th>Послуга</th>
                        <th>Тип</th>
                        <th>Напрямок</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($batch['items'] as $item) :
                    $service = $item['post_id'] ? ($lookup[(int) $item['post_id']] ?? null) : null;
                    $candidates = [];
                    foreach ((array) $item['candidate_ids'] as $candidate_id) {
                        if (isset($lookup[(int) $candidate_id])) {
                            $candidates[] = $lookup[(int) $candidate_id]['title'];
                        }
                    }
                    ?>
                    <tr>
                        <td>
                            <?php echo esc_html((string) $item['filename']); ?>
                            <?php if ((string) $item['relative'] !== (string) $item['filename']) : ?>
                                <br><span class="description"><?php echo esc_html((string) $item['relative']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo wp_get_attachment_image((int) $item['attachment_id'], [64, 64], false, ['style' => 'width:48px;height:48px;object-fit:contain']); ?></td>
                        <td><?php echo esc_html(gvspace_service_icons_status_label((string) $item['status'], !empty($item['had_icon']))); ?></td>
                        <td>
                            <?php if ($service) : ?>
                                <?php echo esc_html($service['title']); ?>
                            <?php elseif ($candidates) : ?>
                                <?php echo esc_html(implode(', ', $candidates)); ?>
                            <?php else : ?>
                                —
                            <?php endif; ?>
                        </td>
                        <td><?php echo $service ? esc_html($service['level'] === 'l2' ? 'Напрямок (L2)' : 'Послуга (L3)') : '—'; ?></td>
                        <td><?php echo $service && $service['parent_title'] !== '' ? esc_html($service['parent_title']) : '—'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <form method="post">
                <?php wp_nonce_field('gvspace_service_icons'); ?>
                <p>
                    <?php if ($matched > 0) : ?>
                        <button class="button button-primary" name="gvspace_service_icons_action" value="apply" type="submit">Призначити іконки</button>
                    <?php endif; ?>
                    <button class="button" name="gvspace_service_icons_action" value="cancel" type="submit">Скасувати</button>
                </p>
            </form>
        <?php else : ?>
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('gvspace_service_icons'); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="gvspace_service_icon_files">Файли</label></th>
                        <td>
                            <input id="gvspace_service_icon_files" name="gvspace_service_icon_files[]" type="file" accept=".webp,.png,.zip,image/webp,image/png,application/zip" multiple required>
                            <p class="description">Кілька зображень або один ZIP. Ім’я файлу без розширення має збігатися зі slug або назвою послуги зі списку.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Наявні іконки</th>
                        <td>
                            <label>
                                <input type="checkbox" name="gvspace_service_icons_replace" value="1" checked>
                                Замінити іконку, якщо в послуги вона вже стоїть
                            </label>
                        </td>
                    </tr>
                </table>
                <p><button class="button button-primary" name="gvspace_service_icons_action" value="preview" type="submit">Показати прев’ю</button></p>
            </form>
            <?php if ($missing) : ?>
                <h2>Ще без іконки</h2>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th>Тип</th>
                            <th>Напрямок</th>
                            <th>Послуга</th>
                            <th>Ім’я файлу</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach (gvspace_service_icons_sorted_catalog($missing) as $service) : ?>
                        <tr>
                            <td><?php echo esc_html($service['level'] === 'l2' ? 'L2' : 'L3'); ?></td>
                            <td><?php echo $service['parent_title'] !== '' ? esc_html($service['parent_title']) : '—'; ?></td>
                            <td><?php echo esc_html($service['title']); ?></td>
                            <td><code><?php echo esc_html(gvspace_service_icon_filename($service)); ?></code></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php
}
