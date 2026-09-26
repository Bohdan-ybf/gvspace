<?php

if (!defined('ABSPATH')) {
    exit;
}

function gvspace_tech_import_field_map(): array
{
    return [
        'мова' => 'locale',
        'назва технології (h1 на сторінці)' => 'title',
        'назва технології' => 'title',
        'короткий опис на картці каталогу' => 'description',
        'короткий опис' => 'description',
        'мітка на картці' => 'tag',
        'таби на сайті' => 'tabs',
        'таби' => 'tabs',
        'порядок картки' => 'order',
        'порядок' => 'order',
        'текст під назвою на білому банері' => 'intro',
        'чому ми обираємо це' => 'why',
        'коли обираємо цю технологію' => 'triggers',
        'як ми застосовуємо' => 'uses',
        'faq на сторінці' => 'faq',
        'питання та відповіді' => 'faq',
        'seo-текст — виділений перший рядок' => 'seo_lead',
        'seo-текст - виділений перший рядок' => 'seo_lead',
        'seo-текст' => 'seo_text',
        'кейс на сторінці технології' => 'related_case',
        'блок зустрічі — ім’я експерта' => 'meet_name',
        'блок зустрічі — ім\'я експерта' => 'meet_name',
        'блок зустрічі — посада' => 'meet_role',
        'блок зустрічі — цитата' => 'meet_quote',
        'блок зустрічі — років у компанії' => 'meet_years',
        'блок зустрічі — проєктів реалізовано' => 'meet_projects',
        'блок зустрічі — компетенції' => 'meet_tags',
        'seo title' => ['seo', 'title'],
        'meta description' => ['seo', 'description'],
        'h1 сторінки' => ['seo', 'h1'],
        'open graph title' => ['seo', 'og_title'],
        'open graph description' => ['seo', 'og_description'],
        'open graph image url' => ['seo', 'og_image'],
    ];
}

function gvspace_tech_import_empty_record(): array
{
    return [
        'filename' => '',
        'slug' => '',
        'locale' => 'uk',
        'title' => '',
        'description' => '',
        'tag' => '',
        'intro' => '',
        'why' => '',
        'triggers' => '',
        'uses' => '',
        'faq' => '',
        'seo_lead' => '',
        'seo_text' => '',
        'meet_name' => '',
        'meet_role' => '',
        'meet_quote' => '',
        'meet_years' => '',
        'meet_projects' => '',
        'meet_tags' => '',
        'tabs' => '',
        'related_case' => '',
        'order' => '',
        'icon_only' => false,
        'seo' => [
            'title' => '',
            'description' => '',
            'h1' => '',
            'og_title' => '',
            'og_description' => '',
            'og_image' => '',
        ],
        'warnings' => [],
    ];
}

function gvspace_tech_import_map_heading(string $heading): string|array|null
{
    $normalized = gvspace_l3_import_normalize_heading($heading);
    $normalized = str_replace("'", '’', $normalized);
    $map = gvspace_tech_import_field_map();
    if (isset($map[$normalized])) {
        return $map[$normalized];
    }
    $keys = array_keys($map);
    usort($keys, static fn (string $left, string $right): int => strlen($right) <=> strlen($left));
    foreach ($keys as $needle) {
        if (str_starts_with($normalized, $needle)) {
            return $map[$needle];
        }
    }
    return null;
}

function gvspace_tech_import_filename_parts(string $filename): array
{
    $base = strtolower((string) pathinfo($filename, PATHINFO_FILENAME));
    $base = preg_replace('/^gvspace-tech-/', '', $base) ?? $base;
    $locale = '';
    foreach (gvspace_l3_import_locale_codes() as $code) {
        $suffixes = ['.' . strtolower($code), '-' . strtolower($code)];
        foreach ($suffixes as $suffix) {
            if (str_ends_with($base, $suffix)) {
                $locale = $code;
                $base = substr($base, 0, -strlen($suffix));
                break 2;
            }
        }
    }
    $slug = sanitize_title($base);
    if (in_array($slug, ['technology', 'tech'], true)) {
        $slug = '';
    }
    return ['slug' => $slug, 'locale' => $locale];
}

function gvspace_tech_import_parse_markdown(string $markdown, string $filename): array
{
    $record = gvspace_tech_import_empty_record();
    $record['filename'] = $filename;
    $parts = gvspace_tech_import_filename_parts($filename);
    $record['slug'] = $parts['slug'];
    $record['locale'] = $parts['locale'] !== '' ? $parts['locale'] : 'uk';

    $sections = preg_split('/^###\s+/mu', $markdown) ?: [];
    array_shift($sections);
    foreach ($sections as $section) {
        $chunk = explode("\n", $section, 2);
        $heading = trim($chunk[0] ?? '');
        $body = $chunk[1] ?? '';
        $target = gvspace_tech_import_map_heading($heading);
        if ($target === null) {
            continue;
        }
        $value = gvspace_l3_import_extract_value($body);
        if (is_array($target)) {
            $record[$target[0]][$target[1]] = $value;
            continue;
        }
        $record[$target] = $value;
    }

    $field_locale = gvspace_l3_import_normalize_locale((string) $record['locale']);
    if ($parts['locale'] !== '') {
        if ($field_locale !== '' && $field_locale !== $parts['locale']) {
            $record['warnings'][] = 'Мова у файлі (' . $field_locale . ') не збігається з іменем, використано ' . $parts['locale'] . '.';
        }
        $record['locale'] = $parts['locale'];
    } elseif ($field_locale !== '') {
        $record['locale'] = $field_locale;
    } else {
        if (trim((string) $record['locale']) !== '') {
            $record['warnings'][] = 'Мову не розпізнано, запис піде як uk.';
        }
        $record['locale'] = 'uk';
    }

    if ($record['title'] === '') {
        $record['warnings'][] = 'Немає назви технології — файл пропущено.';
    }
    foreach (['triggers' => 'Картки «коли обираємо»', 'uses' => 'Пункти «як застосовуємо»', 'faq' => 'FAQ'] as $field => $label) {
        if ($record[$field] !== '' && !str_contains($record[$field], '|')) {
            $record['warnings'][] = $label . ' мають бути у форматі через | .';
        }
    }
    if ($record['slug'] === '' && $record['title'] !== '') {
        $record['slug'] = sanitize_title($record['title']);
    }

    return $record;
}

function gvspace_tech_import_icon_extensions(): array
{
    return ['svg', 'png', 'webp', 'jpg', 'jpeg'];
}

function gvspace_tech_import_icon_rank(string $extension): int
{
    return ['svg' => 0, 'png' => 1, 'webp' => 2, 'jpg' => 3, 'jpeg' => 3][$extension] ?? 9;
}

function gvspace_tech_import_svg_is_safe(string $contents): bool
{
    if (!str_contains(strtolower($contents), '<svg')) {
        return false;
    }
    return !preg_match('/<\s*script|<\s*foreignobject|<\s*iframe|<\s*!entity|javascript\s*:|on[a-z]+\s*=/i', $contents);
}

function gvspace_tech_import_temp_dir(): string
{
    $upload = wp_upload_dir();
    $root = trailingslashit($upload['basedir']) . 'gvspace-tech-import';
    if (!is_dir($root)) {
        wp_mkdir_p($root);
    }
    $htaccess = $root . '/.htaccess';
    if (!is_file($htaccess)) {
        file_put_contents($htaccess, "Deny from all\n");
    }
    $index = $root . '/index.php';
    if (!is_file($index)) {
        file_put_contents($index, "<?php\n// Silence is golden.\n");
    }
    $dir = $root . '/' . get_current_user_id();
    if (!is_dir($dir)) {
        wp_mkdir_p($dir);
    }
    return $dir;
}

function gvspace_tech_import_clean_temp(): void
{
    $dir = gvspace_tech_import_temp_dir();
    $entries = glob(trailingslashit($dir) . '*');
    if (!is_array($entries)) {
        return;
    }
    foreach ($entries as $entry) {
        if (is_file($entry)) {
            unlink($entry);
        }
    }
}

function gvspace_tech_import_path_is_inside(string $path, string $dir): bool
{
    $real = realpath($path);
    $root = realpath($dir);
    if ($real === false || $root === false) {
        return false;
    }
    $real = wp_normalize_path($real);
    $root = trailingslashit(wp_normalize_path($root));
    return str_starts_with($real, $root);
}

function gvspace_tech_import_read_zip(string $tmp_path): array
{
    if (!class_exists(ZipArchive::class)) {
        return ['files' => [], 'error' => 'На сервері немає ZipArchive. Завантажте .md та іконки окремими файлами.'];
    }
    $zip = new ZipArchive();
    if ($zip->open($tmp_path) !== true) {
        return ['files' => [], 'error' => 'Не вдалося відкрити ZIP.'];
    }
    $files = [];
    $total = 0;
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $name = (string) $zip->getNameIndex($i);
        if ($name === '' || str_ends_with($name, '/') || str_contains($name, '__MACOSX') || str_contains($name, '..')) {
            continue;
        }
        $extension = strtolower((string) pathinfo($name, PATHINFO_EXTENSION));
        $allowed = $extension === 'md' || in_array($extension, gvspace_tech_import_icon_extensions(), true);
        if (!$allowed) {
            continue;
        }
        $contents = $zip->getFromIndex($i);
        if (!is_string($contents) || $contents === '') {
            continue;
        }
        $total += strlen($contents);
        if ($total > 20 * 1024 * 1024 || count($files) >= 300) {
            $zip->close();
            return ['files' => [], 'error' => 'Архів завеликий. Розбийте імпорт на менші пакети.'];
        }
        $files[] = [
            'name' => basename($name),
            'contents' => $contents,
        ];
    }
    $zip->close();
    return ['files' => $files, 'error' => ''];
}

function gvspace_tech_import_collect_uploads(string $field = 'gvspace_tech_files'): array
{
    if (empty($_FILES[$field]) || !is_array($_FILES[$field]['name'])) {
        return ['files' => [], 'error' => 'Додайте .md, іконки або .zip.'];
    }

    $bag = $_FILES[$field];
    $count = count((array) $bag['name']);
    $files = [];
    $errors = [];
    for ($i = 0; $i < $count; $i++) {
        $error = (int) ($bag['error'][$i] ?? UPLOAD_ERR_NO_FILE);
        if ($error === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ($error !== UPLOAD_ERR_OK) {
            $errors[] = 'Не вдалося завантажити файл ' . sanitize_file_name((string) $bag['name'][$i]) . '.';
            continue;
        }
        $name = (string) $bag['name'][$i];
        $tmp = (string) $bag['tmp_name'][$i];
        $extension = strtolower((string) pathinfo($name, PATHINFO_EXTENSION));
        if ($extension === 'zip') {
            $extracted = gvspace_tech_import_read_zip($tmp);
            if ($extracted['error'] !== '') {
                $errors[] = $extracted['error'];
                continue;
            }
            $files = array_merge($files, $extracted['files']);
            continue;
        }
        $allowed = $extension === 'md' || in_array($extension, gvspace_tech_import_icon_extensions(), true);
        if (!$allowed) {
            $errors[] = esc_html($name) . ' — потрібен .md, іконка (svg, png, jpg, webp) або .zip.';
            continue;
        }
        $contents = (string) file_get_contents($tmp);
        if ($contents === '') {
            $errors[] = esc_html($name) . ' порожній.';
            continue;
        }
        if (strlen($contents) > 4 * 1024 * 1024) {
            $errors[] = esc_html($name) . ' більший за 4 МБ.';
            continue;
        }
        $files[] = ['name' => $name, 'contents' => $contents];
    }

    if (!$files && !$errors) {
        $errors[] = 'Додайте .md, іконки або .zip.';
    }

    return ['files' => $files, 'error' => implode(' ', $errors)];
}

function gvspace_tech_import_store_icons(array $files): array
{
    gvspace_tech_import_clean_temp();
    $dir = gvspace_tech_import_temp_dir();
    $icons = [];
    $errors = [];
    foreach ($files as $file) {
        $name = (string) $file['name'];
        $extension = strtolower((string) pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($extension, gvspace_tech_import_icon_extensions(), true)) {
            continue;
        }
        $parts = gvspace_tech_import_filename_parts($name);
        if ($parts['slug'] === '') {
            $errors[] = $name . ': не вдалося визначити slug іконки.';
            continue;
        }
        $contents = (string) $file['contents'];
        if ($extension === 'svg' && !gvspace_tech_import_svg_is_safe($contents)) {
            $errors[] = $name . ': SVG містить скрипти або інший активний вміст і пропущений.';
            continue;
        }
        $slug = $parts['slug'];
        if (isset($icons[$slug]) && gvspace_tech_import_icon_rank($icons[$slug]['extension']) <= gvspace_tech_import_icon_rank($extension)) {
            $errors[] = $name . ': для «' . $slug . '» уже є іконка ' . $icons[$slug]['name'] . '.';
            continue;
        }
        $path = trailingslashit($dir) . $slug . '.' . $extension;
        if (file_put_contents($path, $contents) === false) {
            $errors[] = $name . ': не вдалося зберегти іконку.';
            continue;
        }
        $icons[$slug] = [
            'name' => $name,
            'path' => $path,
            'extension' => $extension,
        ];
    }
    return ['icons' => $icons, 'errors' => $errors];
}

function gvspace_tech_import_preview_payload(array $files, bool $replace_catalog): array
{
    $markdown = [];
    foreach ($files as $file) {
        if (strtolower((string) pathinfo((string) $file['name'], PATHINFO_EXTENSION)) === 'md') {
            $markdown[] = $file;
        }
    }
    $stored = gvspace_tech_import_store_icons($files);
    $icons = $stored['icons'];
    $errors = $stored['errors'];

    $records = [];
    $seen = [];
    foreach ($markdown as $file) {
        $record = gvspace_tech_import_parse_markdown((string) $file['contents'], (string) $file['name']);
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

    $slugs_with_copy = [];
    foreach ($records as $index => $record) {
        $slug = (string) $record['slug'];
        $slugs_with_copy[$slug] = true;
        if (isset($icons[$slug])) {
            continue;
        }
        $existing = gvspace_find_technology_post_id($slug);
        if ($existing && has_post_thumbnail($existing)) {
            continue;
        }
        if (!isset($records[$index]['warnings'])) {
            $records[$index]['warnings'] = [];
        }
        $records[$index]['warnings'][] = 'Немає іконки. Покладіть поряд файл gvspace-tech-' . $slug . '.svg (або png, jpg, webp).';
    }

    foreach ($icons as $slug => $icon) {
        if (isset($slugs_with_copy[$slug])) {
            continue;
        }
        $existing = gvspace_find_technology_post_id($slug);
        if (!$existing) {
            $errors[] = $icon['name'] . ': немає файлу gvspace-tech-' . $slug . '.md і технології з таким slug в адмінці.';
            unset($icons[$slug]);
            continue;
        }
        $records[] = array_merge(gvspace_tech_import_empty_record(), [
            'filename' => $icon['name'],
            'slug' => $slug,
            'locale' => '—',
            'title' => get_the_title($existing),
            'icon_only' => true,
            'warnings' => ['Лише іконка: тексти цієї технології не зміняться.'],
        ]);
    }

    if (!$markdown) {
        $replace_catalog = false;
        if ($icons) {
            $errors[] = 'Без .md файлів каталог не замінюється, оновлюються лише іконки існуючих технологій.';
        }
    }

    $to_archive = [];
    if ($replace_catalog) {
        $keep = array_values(array_unique(array_map(static fn (array $record): string => (string) $record['slug'], $records)));
        $posts = get_posts([
            'post_type' => 'gv_technology',
            'post_status' => ['publish', 'pending', 'private', 'future'],
            'numberposts' => -1,
            'suppress_filters' => true,
        ]);
        foreach ($posts as $post) {
            $group = (string) get_post_meta($post->ID, '_gvspace_translation_group', true);
            $slug = $group !== '' ? $group : $post->post_name;
            if (in_array($slug, $keep, true)) {
                continue;
            }
            $to_archive[] = [
                'id' => (int) $post->ID,
                'title' => $post->post_title,
                'slug' => $slug,
            ];
        }
    }

    return [
        'replace_catalog' => $replace_catalog,
        'records' => $records,
        'icons' => $icons,
        'archive' => $to_archive,
        'errors' => $errors,
    ];
}

function gvspace_tech_import_parse_tabs(string $value): array
{
    $value = str_replace([',', ';', '|'], "\n", $value);
    $lines = preg_split('/\r\n|\r|\n/', $value) ?: [];
    $known = [];
    foreach (gvspace_get_technology_tab_options() as $slug => $name) {
        $known[gvspace_l3_import_normalize_heading((string) $slug)] = (string) $slug;
        $known[gvspace_l3_import_normalize_heading((string) $name)] = (string) $slug;
    }
    foreach (gvspace_default_technology_tabs() as $slug => $names) {
        foreach ($names as $name) {
            $known[gvspace_l3_import_normalize_heading((string) $name)] = (string) $slug;
        }
    }

    $slugs = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        $key = gvspace_l3_import_normalize_heading($line);
        if (isset($known[$key])) {
            $slugs[] = $known[$key];
            continue;
        }
        $created = gvspace_ensure_technology_tab($line);
        if ($created !== '') {
            $slugs[] = $created;
        }
    }
    return array_values(array_unique($slugs));
}

function gvspace_tech_import_upsert(array $fields, int $fallback_order): int
{
    $slug = (string) $fields['slug'];
    if ($slug === '') {
        return 0;
    }
    $post_id = gvspace_find_technology_post_id($slug);
    if (!empty($fields['icon_only'])) {
        return $post_id;
    }

    $locale = gvspace_l3_import_normalize_locale((string) ($fields['locale'] ?? 'uk')) ?: 'uk';
    $title = sanitize_text_field((string) ($fields['title'] ?? ''));
    $post_data = [
        'post_type' => 'gv_technology',
        'post_status' => 'publish',
    ];
    if ($fields['order'] !== '' && is_numeric($fields['order'])) {
        $post_data['menu_order'] = (int) $fields['order'];
    } elseif (!$post_id) {
        $post_data['menu_order'] = $fallback_order;
    }
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
        $post_data['post_name'] = $slug;
        if (!isset($post_data['menu_order'])) {
            $post_data['menu_order'] = 0;
        }
        $created = wp_insert_post($post_data, true);
        if (!$created || is_wp_error($created)) {
            return 0;
        }
        $post_id = (int) $created;
    }

    update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($post_id, '_gvspace_translation_group', $slug);
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    delete_post_meta($post_id, '_gvspace_technology_archived');
    update_post_meta($post_id, '_gvspace_technology_title_' . $locale, $title);
    foreach (array_keys(gvspace_localized_technology_fields()) as $field) {
        update_post_meta(
            $post_id,
            '_gvspace_technology_' . $field . '_' . $locale,
            sanitize_textarea_field((string) ($fields[$field] ?? ''))
        );
    }
    foreach ((array) ($fields['seo'] ?? []) as $seo_field => $value) {
        $clean = $seo_field === 'og_image' ? esc_url_raw((string) $value) : sanitize_textarea_field((string) $value);
        update_post_meta($post_id, '_gvspace_seo_' . $seo_field . '_' . $locale, $clean);
    }
    clean_post_cache($post_id);
    return $post_id;
}

function gvspace_tech_import_apply_shared(int $post_id, array $shared): void
{
    if ($shared['tabs'] !== '') {
        $tabs = gvspace_tech_import_parse_tabs((string) $shared['tabs']);
        if ($tabs) {
            wp_set_object_terms($post_id, $tabs, 'gv_technology_category', false);
        }
    }
    if ($shared['related_case'] !== '') {
        update_post_meta($post_id, '_gvspace_technology_related_case', sanitize_title((string) $shared['related_case']));
    }
}

function gvspace_tech_import_attach_icon(int $post_id, array $icon): bool
{
    $path = (string) ($icon['path'] ?? '');
    if ($post_id <= 0 || $path === '' || !gvspace_tech_import_path_is_inside($path, gvspace_tech_import_temp_dir())) {
        return false;
    }
    $extension = strtolower((string) ($icon['extension'] ?? pathinfo($path, PATHINFO_EXTENSION)));
    $contents = (string) file_get_contents($path);
    if ($contents === '') {
        return false;
    }
    if ($extension === 'svg' && !gvspace_tech_import_svg_is_safe($contents)) {
        return false;
    }
    $mime = [
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'webp' => 'image/webp',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
    ][$extension] ?? '';
    if ($mime === '') {
        return false;
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $slug = (string) get_post_meta($post_id, '_gvspace_translation_group', true);
    if ($slug === '') {
        $slug = (string) get_post_field('post_name', $post_id);
    }
    $GLOBALS['gvspace_allow_svg_seed'] = true;
    $upload = wp_upload_bits($slug . '.' . $extension, null, $contents);
    $GLOBALS['gvspace_allow_svg_seed'] = false;
    if (!empty($upload['error']) || empty($upload['file'])) {
        return false;
    }

    $attachment_id = wp_insert_attachment([
        'post_mime_type' => $mime,
        'post_title' => $slug,
        'post_status' => 'inherit',
        'guid' => $upload['url'],
    ], $upload['file'], $post_id);
    if (!$attachment_id || is_wp_error($attachment_id)) {
        return false;
    }
    wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $upload['file']));
    update_post_meta($attachment_id, '_gvspace_technology_icon', $slug);
    set_post_thumbnail($post_id, (int) $attachment_id);
    return true;
}

function gvspace_tech_import_archive_missing(array $keep_slugs): int
{
    $posts = get_posts([
        'post_type' => 'gv_technology',
        'post_status' => ['publish', 'pending', 'private', 'future'],
        'numberposts' => -1,
        'suppress_filters' => true,
    ]);
    $archived = 0;
    foreach ($posts as $post) {
        $group = (string) get_post_meta($post->ID, '_gvspace_translation_group', true);
        $slug = $group !== '' ? $group : $post->post_name;
        if (in_array($slug, $keep_slugs, true)) {
            continue;
        }
        wp_update_post([
            'ID' => $post->ID,
            'post_status' => 'draft',
        ]);
        update_post_meta($post->ID, '_gvspace_translation_status', 'draft');
        update_post_meta($post->ID, '_gvspace_technology_archived', 'import');
        clean_post_cache($post->ID);
        $archived++;
    }
    return $archived;
}

function gvspace_tech_import_shared_fields(array $records): array
{
    $shared = [];
    foreach ($records as $record) {
        if (!empty($record['icon_only'])) {
            continue;
        }
        $slug = (string) $record['slug'];
        if (!isset($shared[$slug])) {
            $shared[$slug] = ['tabs' => '', 'related_case' => '', 'from_uk' => false];
        }
        $from_uk = (string) $record['locale'] === 'uk';
        if ($record['tabs'] !== '' && ($shared[$slug]['tabs'] === '' || ($from_uk && !$shared[$slug]['from_uk']))) {
            $shared[$slug]['tabs'] = (string) $record['tabs'];
        }
        if ($record['related_case'] !== '' && ($shared[$slug]['related_case'] === '' || ($from_uk && !$shared[$slug]['from_uk']))) {
            $shared[$slug]['related_case'] = (string) $record['related_case'];
        }
        if ($from_uk) {
            $shared[$slug]['from_uk'] = true;
        }
    }
    return $shared;
}

function gvspace_tech_import_apply(array $payload): array
{
    $records = (array) ($payload['records'] ?? []);
    $icons = (array) ($payload['icons'] ?? []);
    if (!$records) {
        return ['ok' => false, 'message' => 'Немає розпізнаних технологій для імпорту.'];
    }

    $keep = array_values(array_unique(array_filter(array_map(static fn (array $record): string => (string) $record['slug'], $records))));
    $archived = !empty($payload['replace_catalog']) ? gvspace_tech_import_archive_missing($keep) : 0;
    $shared = gvspace_tech_import_shared_fields($records);

    $order_by_slug = [];
    foreach ($records as $index => $record) {
        $slug = (string) $record['slug'];
        if (!isset($order_by_slug[$slug])) {
            $order_by_slug[$slug] = (int) $index;
        }
    }

    $updated = 0;
    $icons_set = 0;
    $locales = [];
    $done_icons = [];
    foreach ($records as $record) {
        $slug = (string) $record['slug'];
        $post_id = gvspace_tech_import_upsert($record, $order_by_slug[$slug] ?? 0);
        if (!$post_id) {
            continue;
        }
        $updated++;
        if (empty($record['icon_only'])) {
            $locales[(string) $record['locale']] = true;
            if (isset($shared[$slug])) {
                gvspace_tech_import_apply_shared($post_id, $shared[$slug]);
            }
        }
        if (isset($icons[$slug]) && !isset($done_icons[$slug])) {
            if (gvspace_tech_import_attach_icon($post_id, $icons[$slug])) {
                $icons_set++;
            }
            $done_icons[$slug] = true;
        }
    }

    gvspace_tech_import_clean_temp();
    return [
        'ok' => true,
        'message' => sprintf(
            'Імпорт технологій завершено: оновлено %d записів (%s), іконок встановлено %d. В архів перенесено %d.',
            $updated,
            implode(', ', array_keys($locales)) ?: 'іконки',
            $icons_set,
            $archived
        ),
    ];
}

function gvspace_tech_import_transient_key(): string
{
    return 'gvspace_tech_import_' . get_current_user_id();
}

add_action('admin_menu', static function (): void {
    add_submenu_page(
        'edit.php?post_type=gv_technology',
        'Імпорт технологій',
        'Імпорт',
        'edit_posts',
        'gvspace-import-technologies',
        'gvspace_render_tech_import_page'
    );
}, 9);

add_action('admin_init', static function (): void {
    if (!is_admin() || !current_user_can('edit_posts')) {
        return;
    }
    if (($_GET['page'] ?? '') !== 'gvspace-import-technologies' || ($_GET['download'] ?? '') !== 'template') {
        return;
    }
    check_admin_referer('gvspace_tech_import_template');
    $path = __DIR__ . '/templates/technology.md';
    if (!is_readable($path)) {
        wp_die('Шаблон не знайдено.');
    }
    nocache_headers();
    header('Content-Type: text/markdown; charset=utf-8');
    header('Content-Disposition: attachment; filename="gvspace-tech-aws.md"');
    readfile($path);
    exit;
});

function gvspace_render_tech_import_page(): void
{
    if (!current_user_can('edit_posts')) {
        return;
    }

    $notice = '';
    $notice_type = 'info';
    $preview = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        check_admin_referer('gvspace_tech_import');
        $action = sanitize_key((string) ($_POST['gvspace_tech_action'] ?? ''));
        if ($action === 'preview') {
            $uploads = gvspace_tech_import_collect_uploads('gvspace_tech_files');
            if ($uploads['error'] !== '' && !$uploads['files']) {
                $notice = $uploads['error'];
                $notice_type = 'error';
            } else {
                $replace_catalog = !empty($_POST['gvspace_tech_replace_catalog']);
                $preview = gvspace_tech_import_preview_payload($uploads['files'], $replace_catalog);
                if ($uploads['error'] !== '') {
                    $preview['errors'][] = $uploads['error'];
                }
                if ($preview['records']) {
                    set_transient(gvspace_tech_import_transient_key(), $preview, 30 * MINUTE_IN_SECONDS);
                } else {
                    gvspace_tech_import_clean_temp();
                    $notice = $preview['errors'] ? implode(' ', $preview['errors']) : 'Не вдалося розпізнати жодної технології.';
                    $notice_type = 'error';
                    $preview = null;
                }
            }
        } elseif ($action === 'apply') {
            $stored = get_transient(gvspace_tech_import_transient_key());
            if (!is_array($stored) || empty($stored['records'])) {
                $notice = 'Прев’ю застаріло. Завантажте файли ще раз.';
                $notice_type = 'error';
            } else {
                $result = gvspace_tech_import_apply($stored);
                $notice = $result['message'];
                $notice_type = $result['ok'] ? 'success' : 'error';
                if ($result['ok']) {
                    delete_transient(gvspace_tech_import_transient_key());
                }
            }
        } elseif ($action === 'cancel') {
            delete_transient(gvspace_tech_import_transient_key());
            gvspace_tech_import_clean_temp();
            $notice = 'Імпорт скасовано. Файли не застосовано.';
        }
    }

    $template_url = wp_nonce_url(
        admin_url('edit.php?post_type=gv_technology&page=gvspace-import-technologies&download=template'),
        'gvspace_tech_import_template'
    );
    ?>
    <div class="wrap">
        <h1>Імпорт технологій</h1>
        <p>Завантажте файли у форматі адмінки. Один <code>.md</code> = одна технологія однією мовою. Іконка — окремий файл з тим самим slug: <code>gvspace-tech-aws.md</code> і <code>gvspace-tech-aws.svg</code>. Українська вже залита не затирається, якщо в пакеті лише інші мови.</p>
        <p><a class="button" href="<?php echo esc_url($template_url); ?>">Завантажити шаблон .md</a></p>
        <?php if ($notice !== '') : ?>
            <div class="notice notice-<?php echo esc_attr($notice_type); ?> is-dismissible"><p><?php echo esc_html($notice); ?></p></div>
        <?php endif; ?>

        <?php if (is_array($preview)) : ?>
            <h2>Прев’ю</h2>
            <?php if ($preview['errors']) : ?>
                <div class="notice notice-warning"><p><?php echo esc_html(implode(' ', $preview['errors'])); ?></p></div>
            <?php endif; ?>
            <p>Архів каталогу: <strong><?php echo !empty($preview['replace_catalog']) ? 'так' : 'ні, існуючі технології лишаються'; ?></strong></p>
            <h3>Будуть записані (<?php echo count($preview['records']); ?>)</h3>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>Файл</th>
                        <th>Slug</th>
                        <th>Мова</th>
                        <th>Назва</th>
                        <th>Іконка</th>
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
                        <td>
                            <?php
                            $icon = $preview['icons'][$record['slug']] ?? null;
                            echo esc_html(is_array($icon) ? (string) $icon['name'] : '—');
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($record['icon_only'])) {
                                echo 'лише іконка';
                            } else {
                                $filled = [];
                                foreach ([
                                    'description' => 'опис картки',
                                    'tag' => 'мітка',
                                    'tabs' => 'таби',
                                    'intro' => 'банер',
                                    'why' => 'чому обираємо',
                                    'triggers' => 'коли обираємо',
                                    'uses' => 'як застосовуємо',
                                    'faq' => 'FAQ',
                                    'seo_text' => 'SEO-текст',
                                ] as $key => $label) {
                                    if (trim((string) $record[$key]) !== '') {
                                        $filled[] = $label;
                                    }
                                }
                                echo esc_html($filled ? implode(', ', $filled) : 'лише назва');
                            }
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
                        <li><?php echo esc_html($item['title']); ?> <code><?php echo esc_html($item['slug']); ?></code></li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>Опублікованих технологій, яких немає у файлах, не зачіпаємо. Існуючі з тим самим slug будуть оновлені.</p>
            <?php endif; ?>
            <form method="post">
                <?php wp_nonce_field('gvspace_tech_import'); ?>
                <p>
                    <button class="button button-primary" name="gvspace_tech_action" value="apply" type="submit">Підтвердити імпорт</button>
                    <button class="button" name="gvspace_tech_action" value="cancel" type="submit">Скасувати</button>
                </p>
            </form>
        <?php else : ?>
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('gvspace_tech_import'); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="gvspace_tech_files">Файли</label></th>
                        <td>
                            <input id="gvspace_tech_files" name="gvspace_tech_files[]" type="file" accept=".md,.zip,.svg,.png,.jpg,.jpeg,.webp,text/markdown,application/zip,image/svg+xml,image/png,image/jpeg,image/webp" multiple required>
                            <p class="description">Кілька файлів або один .zip. Текст: <code>gvspace-tech-aws.md</code> = uk, <code>gvspace-tech-aws.en.md</code> = en. Іконка: <code>gvspace-tech-aws.svg</code> (png, jpg, webp теж підходять) — одна на технологію, для всіх мов.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Каталог</th>
                        <td>
                            <label>
                                <input type="checkbox" name="gvspace_tech_replace_catalog" value="1">
                                Замінити каталог: технології, яких немає у файлах, підуть у чернетки
                            </label>
                            <p class="description">Залиште вимкненим, якщо доливаєте нові технології або переклади. Інші мови в адмінці не затираються в будь-якому разі.</p>
                        </td>
                    </tr>
                </table>
                <p><button class="button button-primary" name="gvspace_tech_action" value="preview" type="submit">Показати прев’ю</button></p>
            </form>
        <?php endif; ?>
    </div>
    <?php
}
