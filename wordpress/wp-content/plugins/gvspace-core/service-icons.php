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
    if (preg_match('/^(?:gvspace[-_]+)?(l[23])[-_\s]+(.+)$/iu', $stem, $matches)) {
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

function gvspace_service_icons_prepare_banner_icon(string $tmp_path, string $filename): array
{
    $info = @getimagesize($tmp_path);
    $width = (int) ($info[0] ?? 0);
    $height = (int) ($info[1] ?? 0);
    $mime = (string) ($info['mime'] ?? '');
    $stem = (string) pathinfo($filename, PATHINFO_FILENAME);
    if ($stem === '' || $stem === '.') {
        $stem = 'service-icon';
    }
    if ($mime === 'image/webp' && $width > 0 && $width <= 1600 && $height <= 1600) {
        return ['tmp' => $tmp_path, 'name' => $stem . '.webp'];
    }

    $editor = wp_get_image_editor($tmp_path);
    if (is_wp_error($editor)) {
        return ['tmp' => $tmp_path, 'name' => $filename];
    }

    $editor->set_quality(82);
    if ($width > 1600 || $height > 1600) {
        $resized = $editor->resize(1600, 1600, false);
        if (is_wp_error($resized)) {
            return ['tmp' => $tmp_path, 'name' => $filename];
        }
    }

    $dest = wp_tempnam($stem . '.webp');
    if (!$dest) {
        return ['tmp' => $tmp_path, 'name' => $filename];
    }
    $saved = $editor->save($dest, 'image/webp');
    if (is_wp_error($saved) || empty($saved['path']) || !is_file((string) $saved['path'])) {
        if (is_file($dest)) {
            unlink($dest);
        }
        return ['tmp' => $tmp_path, 'name' => $filename];
    }

    $path = (string) $saved['path'];
    if ($path !== $tmp_path && is_file($tmp_path)) {
        unlink($tmp_path);
    }
    if ($dest !== $path && is_file($dest)) {
        unlink($dest);
    }
    return ['tmp' => $path, 'name' => $stem . '.webp'];
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

    $prepared = gvspace_service_icons_prepare_banner_icon($tmp_path, $filename);
    $tmp_path = $prepared['tmp'];
    $filename = $prepared['name'];

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
        if (($stat['size'] ?? 0) > 64 * 1024 * 1024) {
            $errors[] = $basename . ' більший за 64 МБ.';
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

function gvspace_service_icons_safe_relative(string $value, string $fallback): string
{
    $value = str_replace('\\', '/', trim($value));
    $value = ltrim($value, '/');
    if ($value === '' || str_contains($value, "\0") || str_contains($value, '..') || strlen($value) > 240) {
        return $fallback;
    }
    return $value;
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
    $relatives = isset($_POST['gvspace_service_icon_relatives']) && is_array($_POST['gvspace_service_icon_relatives'])
        ? $_POST['gvspace_service_icon_relatives']
        : [];

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
            'relative' => gvspace_service_icons_safe_relative(wp_unslash((string) ($relatives[$i] ?? '')), $name),
            'tmp' => $tmp,
        ];
    }

    if (!$files && !$errors) {
        $errors[] = 'Додайте WebP, PNG або ZIP з іконками.';
    }

    return ['files' => $files, 'error' => implode(' ', $errors)];
}

function gvspace_service_icons_match_staged(array $staged, bool $replace, array $catalog): array
{
    $items = [];
    $used = [];
    $lookup = [];
    foreach ($catalog as $service) {
        $lookup[$service['id']] = $service;
    }

    foreach ($staged as $file) {
        $relative = (string) ($file['relative'] ?? $file['filename'] ?? '');
        $attachment_id = (int) ($file['attachment_id'] ?? 0);
        if ($attachment_id <= 0) {
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
                $used[$post_id] = (string) ($file['filename'] ?? '');
            }
        } elseif (count($match_ids) > 1) {
            $status = 'ambiguous';
        }

        $items[] = [
            'attachment_id' => $attachment_id,
            'filename' => (string) ($file['filename'] ?? ''),
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
        'errors' => [],
    ];
}

function gvspace_service_icons_preview_payload(array $files, bool $replace, array $catalog): array
{
    $staged = [];
    $errors = [];

    foreach ($files as $file) {
        $attachment_id = gvspace_service_icons_stage_file((string) $file['tmp'], (string) $file['name']);
        if (is_wp_error($attachment_id)) {
            if (!empty($file['tmp']) && is_file((string) $file['tmp'])) {
                unlink((string) $file['tmp']);
            }
            $errors[] = (string) $file['name'] . ': ' . $attachment_id->get_error_message();
            continue;
        }

        $staged[] = [
            'attachment_id' => (int) $attachment_id,
            'filename' => (string) $file['name'],
            'relative' => (string) ($file['relative'] ?? $file['name']),
        ];
    }

    $payload = gvspace_service_icons_match_staged($staged, $replace, $catalog);
    if ($errors) {
        $payload['errors'] = $errors;
    }
    return $payload;
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

function gvspace_service_icons_ini_bytes(string $value): int
{
    $value = trim($value);
    if ($value === '' || $value === '-1') {
        return 0;
    }
    $unit = strtolower(substr($value, -1));
    $number = (float) $value;
    $bytes = match ($unit) {
        'g' => $number * 1024 * 1024 * 1024,
        'm' => $number * 1024 * 1024,
        'k' => $number * 1024,
        default => $number,
    };
    return max(0, (int) round($bytes));
}

function gvspace_service_icons_upload_limits(): array
{
    $post = gvspace_service_icons_ini_bytes((string) ini_get('post_max_size'));
    $upload = gvspace_service_icons_ini_bytes((string) ini_get('upload_max_filesize'));
    $positive = array_values(array_filter([$post, $upload], static fn (int $bytes): bool => $bytes > 0));
    $cap = $positive ? min($positive) : 8 * 1024 * 1024;
    $budget = (int) floor($cap * 0.7);
    if ($budget > 24 * 1024 * 1024) {
        $budget = 24 * 1024 * 1024;
    }
    if ($budget < 512 * 1024) {
        $budget = min($cap, 512 * 1024);
    }
    $max_files = (int) ini_get('max_file_uploads');
    if ($max_files < 2) {
        $max_files = 20;
    }

    return [
        'budget' => $budget,
        'single' => max(1, $cap - (256 * 1024)),
        'maxFiles' => min(8, $max_files - 1),
    ];
}

function gvspace_service_icons_notes_from_request(): array
{
    if (!isset($_POST['notes']) || !is_array($_POST['notes'])) {
        return [];
    }
    $notes = [];
    foreach (array_slice($_POST['notes'], 0, 50) as $note) {
        $note = sanitize_text_field(wp_unslash((string) $note));
        if ($note !== '') {
            $notes[] = $note;
        }
    }
    return $notes;
}

function gvspace_service_icons_ajax_guard(): void
{
    if (!current_user_can('edit_posts') || !current_user_can('upload_files')) {
        wp_send_json_error(['message' => 'Немає права завантажувати файли.'], 403);
    }
    if (!check_ajax_referer('gvspace_service_icons', '_wpnonce', false)) {
        wp_send_json_error(['message' => 'Сесія форми застаріла. Оновіть сторінку.'], 403);
    }
    if (function_exists('set_time_limit')) {
        set_time_limit(120);
    }
}

function gvspace_service_icons_ajax_chunk(): void
{
    gvspace_service_icons_ajax_guard();

    $key = gvspace_service_icons_transient_key();
    if (!empty($_POST['reset'])) {
        gvspace_service_icons_cleanup_pending([]);
        delete_transient($key);
        $state = [
            'phase' => 'staging',
            'replace' => !empty($_POST['gvspace_service_icons_replace']),
            'staged' => [],
            'errors' => [],
        ];
    } else {
        $state = get_transient($key);
        if (!is_array($state) || ($state['phase'] ?? '') !== 'staging') {
            wp_send_json_error(['message' => 'Завантаження перервалось. Спробуйте ще раз.']);
        }
    }

    $uploads = gvspace_service_icons_collect_uploads();
    if ($uploads['error'] !== '') {
        $state['errors'][] = $uploads['error'];
    }
    foreach ($uploads['files'] as $file) {
        if (count($state['staged']) >= 300) {
            $state['errors'][] = 'У пакеті більше 300 зображень. Решту пропущено.';
            break;
        }
        $attachment_id = gvspace_service_icons_stage_file((string) $file['tmp'], (string) $file['name']);
        if (is_wp_error($attachment_id)) {
            if (!empty($file['tmp']) && is_file((string) $file['tmp'])) {
                unlink((string) $file['tmp']);
            }
            $state['errors'][] = (string) $file['name'] . ': ' . $attachment_id->get_error_message();
            continue;
        }
        $state['staged'][] = [
            'attachment_id' => (int) $attachment_id,
            'filename' => (string) $file['name'],
            'relative' => (string) ($file['relative'] ?? $file['name']),
        ];
    }

    set_transient($key, $state, 30 * MINUTE_IN_SECONDS);
    wp_send_json_success([
        'staged' => count($state['staged']),
    ]);
}

function gvspace_service_icons_ajax_finish(): void
{
    gvspace_service_icons_ajax_guard();

    $key = gvspace_service_icons_transient_key();
    $state = get_transient($key);
    if (!is_array($state) || ($state['phase'] ?? '') !== 'staging') {
        wp_send_json_error(['message' => 'Завантаження перервалось. Спробуйте ще раз.']);
    }

    $preview = gvspace_service_icons_match_staged(
        (array) ($state['staged'] ?? []),
        !empty($state['replace']),
        gvspace_service_icon_catalog()
    );
    $preview['errors'] = array_values(array_filter(array_merge(
        gvspace_service_icons_notes_from_request(),
        (array) ($state['errors'] ?? []),
        $preview['errors']
    )));

    if (!$preview['items']) {
        delete_transient($key);
        gvspace_service_icons_cleanup_pending([]);
        $message = $preview['errors'] ? implode(' ', $preview['errors']) : 'Не вдалося прочитати жодного зображення.';
        wp_send_json_error(['message' => $message]);
    }

    set_transient($key, $preview, 30 * MINUTE_IN_SECONDS);
    wp_send_json_success(['count' => count($preview['items'])]);
}

add_action('wp_ajax_gvspace_service_icons_chunk', 'gvspace_service_icons_ajax_chunk');
add_action('wp_ajax_gvspace_service_icons_finish', 'gvspace_service_icons_ajax_finish');

function gvspace_service_icons_print_upload_script(): void
{
    $config = gvspace_service_icons_upload_limits();
    $config['ajaxUrl'] = admin_url('admin-ajax.php');
    $config['nonce'] = wp_create_nonce('gvspace_service_icons');
    ?>
    <script>
    window.gvspaceServiceIconsUpload = <?php echo wp_json_encode($config); ?>;
    </script>
    <script>
    (function () {
        var form = document.getElementById('gvspace-service-icons-upload');
        var config = window.gvspaceServiceIconsUpload;
        if (!form || !config) {
            return;
        }
        var progress = document.getElementById('gvspace-service-icons-progress');
        var button = form.querySelector('button[type="submit"]');
        var singleLimit = config.single || (8 * 1024 * 1024);

        function setProgress(text, isError) {
            if (!progress) {
                return;
            }
            progress.hidden = text === '';
            progress.textContent = text;
            progress.style.color = isError ? '#b32d2e' : '';
        }

        async function inflateRaw(bytes) {
            var stream = new Blob([bytes]).stream().pipeThrough(new DecompressionStream('deflate-raw'));
            return new Uint8Array(await new Response(stream).arrayBuffer());
        }

        function findEocd(bytes) {
            var start = Math.max(0, bytes.length - 22 - 65535);
            for (var i = bytes.length - 22; i >= start; i--) {
                if (bytes[i] === 0x50 && bytes[i + 1] === 0x4b && bytes[i + 2] === 0x05 && bytes[i + 3] === 0x06) {
                    return i;
                }
            }
            return -1;
        }

        async function readZip(file) {
            if (typeof DecompressionStream === 'undefined') {
                throw new Error('Браузер не вміє розкривати великі ZIP. Оновіть браузер або складіть менший архів.');
            }
            var bytes = new Uint8Array(await file.arrayBuffer());
            var eocd = findEocd(bytes);
            if (eocd < 0) {
                throw new Error(file.name + ': не вдалося прочитати ZIP.');
            }
            var view = new DataView(bytes.buffer, bytes.byteOffset, bytes.byteLength);
            var count = view.getUint16(eocd + 10, true);
            var cursor = view.getUint32(eocd + 16, true);
            var images = [];
            var notes = [];
            var decoder = new TextDecoder('utf-8');
            for (var n = 0; n < count; n++) {
                if (cursor + 46 > bytes.length || view.getUint32(cursor, true) !== 0x02014b50) {
                    throw new Error(file.name + ': архів пошкоджений.');
                }
                var flags = view.getUint16(cursor + 8, true);
                var method = view.getUint16(cursor + 10, true);
                var compressedSize = view.getUint32(cursor + 20, true);
                var nameLength = view.getUint16(cursor + 28, true);
                var extraLength = view.getUint16(cursor + 30, true);
                var commentLength = view.getUint16(cursor + 32, true);
                var localOffset = view.getUint32(cursor + 42, true);
                var name = decoder.decode(bytes.subarray(cursor + 46, cursor + 46 + nameLength)).replace(/\\/g, '/');
                cursor += 46 + nameLength + extraLength + commentLength;
                if (!name || name.endsWith('/') || name.indexOf('__MACOSX') !== -1 || name.indexOf('\0') !== -1) {
                    continue;
                }
                var base = name.split('/').pop() || name;
                if (!/\.(png|webp)$/i.test(base) || base.charAt(0) === '.') {
                    continue;
                }
                if ((flags & 1) !== 0 || compressedSize === 0xffffffff) {
                    notes.push(base + ' пропущено: архів із паролем або ZIP64.');
                    continue;
                }
                if (localOffset + 30 > bytes.length || view.getUint32(localOffset, true) !== 0x04034b50) {
                    notes.push(base + ': не вдалося прочитати.');
                    continue;
                }
                var localName = view.getUint16(localOffset + 26, true);
                var localExtra = view.getUint16(localOffset + 28, true);
                var dataOffset = localOffset + 30 + localName + localExtra;
                if (dataOffset + compressedSize > bytes.length) {
                    notes.push(base + ': не вдалося прочитати.');
                    continue;
                }
                var raw = method === 0
                    ? bytes.subarray(dataOffset, dataOffset + compressedSize)
                    : (method === 8 ? await inflateRaw(bytes.subarray(dataOffset, dataOffset + compressedSize)) : null);
                if (!raw) {
                    notes.push(base + ': невідоме стиснення.');
                    continue;
                }
                images.push({
                    blob: new Blob([raw], { type: /\.webp$/i.test(base) ? 'image/webp' : 'image/png' }),
                    name: base,
                    relative: name.replace(/^\/+/, ''),
                    size: raw.byteLength
                });
            }
            if (!images.length && !notes.length) {
                notes.push(file.name + ': у ZIP немає файлів WebP або PNG.');
            }
            return { images: images, notes: notes };
        }

        function batches(images) {
            var groups = [];
            var current = [];
            var size = 0;
            var budget = Math.max(256 * 1024, config.budget || 0);
            var maxFiles = Math.max(1, config.maxFiles || 8);
            images.forEach(function (image) {
                if (current.length && (size + image.size > budget || current.length >= maxFiles)) {
                    groups.push(current);
                    current = [];
                    size = 0;
                }
                current.push(image);
                size += image.size;
            });
            if (current.length) {
                groups.push(current);
            }
            return groups;
        }

        async function post(body) {
            var response = await fetch(config.ajaxUrl, {
                method: 'POST',
                body: body,
                credentials: 'same-origin'
            });
            var payload = null;
            try {
                payload = await response.json();
            } catch (error) {
                payload = null;
            }
            if (!payload || !payload.success) {
                throw new Error(payload && payload.data && payload.data.message
                    ? payload.data.message
                    : 'Не вдалося надіслати частину пакета.');
            }
            return payload.data || {};
        }

        function canvasBlob(canvas, type, quality) {
            return new Promise(function (resolve) {
                canvas.toBlob(resolve, type, quality);
            });
        }

        async function fitImage(image) {
            if (typeof createImageBitmap !== 'function') {
                if (image.size <= singleLimit) {
                    return image;
                }
                throw new Error(image.name + ' не вміщається в один запит, а браузер не може його зменшити.');
            }
            setProgress('Готую ' + image.name + '…', false);
            var bitmap = await createImageBitmap(image.blob);
            try {
                var longest = Math.max(bitmap.width, bitmap.height, 1);
                var edge = Math.min(longest, 1600);
                var quality = 0.82;
                var blob = null;
                for (var attempt = 0; attempt < 6; attempt++) {
                    var scale = edge / longest;
                    var width = Math.max(1, Math.round(bitmap.width * scale));
                    var height = Math.max(1, Math.round(bitmap.height * scale));
                    var canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    var context = canvas.getContext('2d');
                    context.clearRect(0, 0, width, height);
                    context.drawImage(bitmap, 0, 0, width, height);
                    blob = await canvasBlob(canvas, 'image/webp', quality);
                    if (blob && blob.size <= singleLimit) {
                        break;
                    }
                    quality = Math.max(0.62, quality - 0.08);
                    edge = Math.max(960, Math.round(edge * 0.85));
                }
                if (!blob || blob.size > singleLimit) {
                    throw new Error(image.name + ' не вдалося зберегти як легкий WebP.');
                }
                var stem = image.name.replace(/\.(png|webp)$/i, '');
                var relative = (image.relative || image.name).replace(/\.(png|webp)$/i, '.webp');
                return { blob: blob, name: stem + '.webp', relative: relative, size: blob.size };
            } finally {
                if (bitmap.close) {
                    bitmap.close();
                }
            }
        }

        form.addEventListener('submit', async function (event) {
            var input = form.querySelector('input[type="file"]');
            var files = input && input.files ? Array.from(input.files) : [];
            if (!files.length) {
                return;
            }
            var total = files.reduce(function (sum, file) { return sum + file.size; }, 0);
            if (total <= (config.budget || 0)) {
                return;
            }

            event.preventDefault();
            if (button) {
                button.disabled = true;
            }
            var replace = !!form.querySelector('input[name="gvspace_service_icons_replace"]:checked');
            var notes = [];
            try {
                var prepared = [];
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    if (/\.zip$/i.test(file.name)) {
                        setProgress('Читаю ' + file.name + '…', false);
                        prepared.push(await readZip(file));
                    } else if (/\.(png|webp)$/i.test(file.name)) {
                        prepared.push({
                            images: [{ blob: file, name: file.name, relative: file.name, size: file.size }],
                            notes: []
                        });
                    } else {
                        prepared.push({ images: [], notes: [file.name + ' — потрібен WebP, PNG або ZIP.'] });
                    }
                }
                for (var packIndex = 0; packIndex < prepared.length; packIndex++) {
                    var fitted = [];
                    for (var imageIndex = 0; imageIndex < prepared[packIndex].images.length; imageIndex++) {
                        fitted.push(await fitImage(prepared[packIndex].images[imageIndex]));
                    }
                    prepared[packIndex].images = fitted;
                }
                var planned = prepared.reduce(function (sum, pack) { return sum + pack.images.length; }, 0);
                prepared.forEach(function (pack) {
                    notes = notes.concat(pack.notes);
                });
                if (!planned) {
                    throw new Error(notes[0] || 'У вибраних файлах немає WebP або PNG.');
                }
                var sent = 0;
                var reset = true;
                for (var p = 0; p < prepared.length; p++) {
                    var groups = batches(prepared[p].images);
                    for (var g = 0; g < groups.length; g++) {
                        setProgress('Надіслано ' + sent + ' з ' + planned + ' зображень…', false);
                        var body = new FormData();
                        body.append('action', 'gvspace_service_icons_chunk');
                        body.append('_wpnonce', config.nonce);
                        if (reset) {
                            body.append('reset', '1');
                        }
                        if (replace) {
                            body.append('gvspace_service_icons_replace', '1');
                        }
                        groups[g].forEach(function (image) {
                            body.append('gvspace_service_icon_files[]', image.blob, image.name);
                            body.append('gvspace_service_icon_relatives[]', image.relative || image.name);
                        });
                        await post(body);
                        reset = false;
                        sent += groups[g].length;
                    }
                }
                setProgress('Звіряю назви файлів із послугами…', false);
                var finish = new FormData();
                finish.append('action', 'gvspace_service_icons_finish');
                finish.append('_wpnonce', config.nonce);
                notes.slice(0, 50).forEach(function (note) {
                    finish.append('notes[]', note);
                });
                await post(finish);
                window.location.reload();
            } catch (error) {
                setProgress(error && error.message ? error.message : 'Не вдалося завантажити іконки.', true);
                if (button) {
                    button.disabled = false;
                }
            }
        });
    })();
    </script>
    <?php
}

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
        <p>Завантажте пачку 3D-іконок. Файл стає іконкою банера тієї послуги, чиє ім’я збігається з назвою файлу. Підходить slug, українська назва або англійська назва. Формат — WebP або PNG з прозорим фоном. Перед збереженням іконка стає WebP до 1600 px по довшій стороні, тож на банері лишається чіткою і легкою.</p>
        <p>Приклади: <code>geo-audit.webp</code>, <code>gvspace-l3-ai-chatbot.png</code>, <code>l3-geo-audit.webp</code>. Префікс <code>gvspace-</code> перед <code>l2-</code> / <code>l3-</code> можна лишати. Якщо однакова назва є в кількох напрямках, покладіть файл у папку напрямку (<code>seo-prosuvannya/geo-audit.webp</code>). Можна вибрати багато файлів одразу або один ZIP — великий архів піде частинами і не впреться в ліміт сервера.</p>
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
            <form id="gvspace-service-icons-upload" method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('gvspace_service_icons'); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="gvspace_service_icon_files">Файли</label></th>
                        <td>
                            <input id="gvspace_service_icon_files" name="gvspace_service_icon_files[]" type="file" accept=".webp,.png,.zip,image/webp,image/png,application/zip" multiple required>
                            <p class="description">Кілька зображень або один чи кілька ZIP. Ім’я на кшталт <code>gvspace-l3-ai-chatbot.png</code> зіставляється зі slug послуги.</p>
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
                <p>
                    <button class="button button-primary" name="gvspace_service_icons_action" value="preview" type="submit">Показати прев’ю</button>
                </p>
                <p id="gvspace-service-icons-progress" class="description" hidden></p>
            </form>
            <?php gvspace_service_icons_print_upload_script(); ?>
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
