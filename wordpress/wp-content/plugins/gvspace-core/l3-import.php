<?php

if (!defined('ABSPATH')) {
    exit;
}

function gvspace_l3_import_field_map(): array
{
    return [
        'seo title' => ['seo', 'title'],
        'meta description' => ['seo', 'description'],
        'h1 сторінки' => ['seo', 'h1'],
        'open graph title' => ['seo', 'og_title'],
        'open graph description' => ['seo', 'og_description'],
        'open graph image url' => ['seo', 'og_image'],
        'мова' => 'locale',
        'назва послуги / напрямку' => 'title',
        'назва послуги' => 'title',
        'заголовок першого екрана (h1)' => 'headline',
        'заголовок першого екрана' => 'headline',
        'опис під заголовком' => 'description',
        'кому і коли підходить' => 'fit_cards',
        'що входить у послугу' => 'includes',
        'що входить' => 'includes',
        'етапи роботи' => 'steps',
        'результати / показники' => 'metrics',
        'питання та відповіді на сторінці' => 'faq',
        'питання та відповіді' => 'faq',
    ];
}

function gvspace_l3_import_empty_record(): array
{
    return [
        'filename' => '',
        'slug' => '',
        'locale' => 'uk',
        'title' => '',
        'headline' => '',
        'description' => '',
        'fit_cards' => '',
        'includes' => '',
        'steps' => '',
        'metrics' => '',
        'faq' => '',
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

function gvspace_l3_import_normalize_heading(string $heading): string
{
    $heading = html_entity_decode($heading, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $heading = str_replace(["«", "»", "“", "”", "„", '"', "'"], '', $heading);
    $heading = preg_replace('/\s+/u', ' ', trim($heading)) ?? trim($heading);
    return function_exists('mb_strtolower') ? mb_strtolower($heading, 'UTF-8') : strtolower($heading);
}

function gvspace_l3_import_map_heading(string $heading): string|array|null
{
    $normalized = gvspace_l3_import_normalize_heading($heading);
    $normalized = preg_replace('/\s*[—–-]\s*.+$/u', '', $normalized) ?? $normalized;
    foreach (gvspace_l3_import_field_map() as $needle => $target) {
        if ($normalized === $needle || str_starts_with($normalized, $needle)) {
            return $target;
        }
    }
    return null;
}

function gvspace_l3_import_extract_value(string $body): string
{
    $body = trim($body);
    if ($body === '') {
        return '';
    }
    if (preg_match('/```[^\n]*\n(.*?)```/s', $body, $match)) {
        return trim($match[1]);
    }
    if (preg_match('/^(порожнє|залишити порожнім)/iu', $body)) {
        return '';
    }
    return $body;
}

function gvspace_l3_import_locale_codes(): array
{
    $codes = array_keys(GVSPACE_CONTENT_LOCALES);
    usort($codes, static fn (string $left, string $right): int => strlen($right) <=> strlen($left));
    return $codes;
}

function gvspace_l3_import_normalize_locale(string $value): string
{
    $value = trim($value);
    if ($value === '') {
        return '';
    }
    foreach (GVSPACE_CONTENT_LOCALES as $code => $label) {
        if (strcasecmp($value, $code) === 0 || strcasecmp($value, $label) === 0) {
            return $code;
        }
    }
    $aliases = [
        'ua' => 'uk',
        'укр' => 'uk',
        'українська' => 'uk',
        'english' => 'en',
        'англ' => 'en',
        'англійська' => 'en',
    ];
    $lookup = function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
    return $aliases[$lookup] ?? '';
}

function gvspace_l3_import_filename_parts(string $filename): array
{
    $base = strtolower((string) pathinfo($filename, PATHINFO_FILENAME));
    $base = preg_replace('/^gvspace-l[23]-/', '', $base) ?? $base;
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
    if (in_array($slug, ['l3-service', 'l2-direction', 'l2-service'], true)) {
        $slug = '';
    }
    return ['slug' => $slug, 'locale' => $locale];
}

function gvspace_l3_import_parse_markdown(string $markdown, string $filename): array
{
    $record = gvspace_l3_import_empty_record();
    $record['filename'] = $filename;
    $parts = gvspace_l3_import_filename_parts($filename);
    $record['slug'] = $parts['slug'];
    $record['locale'] = $parts['locale'] !== '' ? $parts['locale'] : 'uk';

    $markdown = preg_replace('/^##\s+(Фактичні довжини|Не має полів).*/isu', '', $markdown) ?? $markdown;
    $sections = preg_split('/^###\s+/mu', $markdown) ?: [];
    array_shift($sections);

    foreach ($sections as $section) {
        $chunk = explode("\n", $section, 2);
        $heading = trim($chunk[0] ?? '');
        $body = $chunk[1] ?? '';
        $target = gvspace_l3_import_map_heading($heading);
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

    $locale_from_file = gvspace_l3_import_normalize_locale((string) $record['locale']);
    $record['locale'] = $locale_from_file !== '' ? $locale_from_file : ($parts['locale'] !== '' ? $parts['locale'] : 'uk');

    if ($record['title'] === '') {
        $record['warnings'][] = 'Немає назви послуги — файл пропущено.';
    }
    if ($record['headline'] === '') {
        $record['warnings'][] = 'Немає заголовка першого екрана.';
    }
    if ($record['fit_cards'] !== '' && !str_contains($record['fit_cards'], '|')) {
        $record['warnings'][] = 'Картки «кому підходить» мають бути у форматі етап | заголовок | опис.';
    }
    if ($record['steps'] !== '' && !str_contains($record['steps'], '|')) {
        $record['warnings'][] = 'Етапи мають бути у форматі назва | термін | опис.';
    }
    if ($record['faq'] !== '' && !str_contains($record['faq'], '|')) {
        $record['warnings'][] = 'FAQ має бути у форматі питання | відповідь.';
    }
    if ($record['slug'] === '' && $record['title'] !== '') {
        $record['slug'] = sanitize_title($record['title']);
    }

    return $record;
}

function gvspace_l3_import_read_zip(string $tmp_path): array
{
    if (!class_exists(ZipArchive::class)) {
        return ['files' => [], 'error' => 'На сервері немає ZipArchive. Завантажте окремі .md файли.'];
    }
    $zip = new ZipArchive();
    if ($zip->open($tmp_path) !== true) {
        return ['files' => [], 'error' => 'Не вдалося відкрити ZIP.'];
    }
    $files = [];
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $name = (string) $zip->getNameIndex($i);
        if ($name === '' || str_ends_with($name, '/') || str_contains($name, '__MACOSX')) {
            continue;
        }
        if (strtolower((string) pathinfo($name, PATHINFO_EXTENSION)) !== 'md') {
            continue;
        }
        $contents = $zip->getFromIndex($i);
        if (!is_string($contents) || trim($contents) === '') {
            continue;
        }
        $files[] = [
            'name' => basename($name),
            'contents' => $contents,
        ];
    }
    $zip->close();
    return ['files' => $files, 'error' => ''];
}

function gvspace_l3_import_collect_uploads(string $field = 'gvspace_l3_files'): array
{
    if (empty($_FILES[$field]) || !is_array($_FILES[$field]['name'])) {
        return ['files' => [], 'error' => 'Додайте .md або .zip із файлами послуг.'];
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
        $ext = strtolower((string) pathinfo($name, PATHINFO_EXTENSION));
        if ($ext === 'zip') {
            $extracted = gvspace_l3_import_read_zip($tmp);
            if ($extracted['error'] !== '') {
                $errors[] = $extracted['error'];
                continue;
            }
            $files = array_merge($files, $extracted['files']);
            continue;
        }
        if ($ext !== 'md') {
            $errors[] = esc_html($name) . ' — потрібен .md або .zip.';
            continue;
        }
        $contents = (string) file_get_contents($tmp);
        if (trim($contents) === '') {
            $errors[] = esc_html($name) . ' порожній.';
            continue;
        }
        $files[] = ['name' => $name, 'contents' => $contents];
    }

    if (!$files && !$errors) {
        $errors[] = 'Додайте .md або .zip із файлами послуг.';
    }

    return ['files' => $files, 'error' => implode(' ', $errors)];
}

function gvspace_l3_import_preview_payload(int $parent_id, array $files, bool $replace_catalog): array
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
    if ($replace_catalog && $parent_id > 0) {
        $children = get_posts([
            'post_type' => 'gv_service',
            'post_parent' => $parent_id,
            'post_status' => ['publish', 'pending', 'private', 'future'],
            'numberposts' => -1,
            'suppress_filters' => true,
        ]);
        $keep = array_values(array_unique(array_column($records, 'slug')));
        foreach ($children as $child) {
            $catalog_slug = (string) get_post_meta($child->ID, '_gvspace_service_catalog_slug', true);
            $group = (string) get_post_meta($child->ID, '_gvspace_translation_group', true);
            $slug = $catalog_slug !== '' ? $catalog_slug : ($group !== '' ? $group : $child->post_name);
            if (in_array($slug, $keep, true)) {
                continue;
            }
            $to_archive[] = [
                'id' => (int) $child->ID,
                'title' => $child->post_title,
                'slug' => $slug,
            ];
        }
    }

    return [
        'parent_id' => $parent_id,
        'replace_catalog' => $replace_catalog,
        'records' => $records,
        'archive' => $to_archive,
        'errors' => $errors,
    ];
}

function gvspace_l3_import_archive_children(int $parent_id, array $keep_slugs): int
{
    $children = get_posts([
        'post_type' => 'gv_service',
        'post_parent' => $parent_id,
        'post_status' => ['publish', 'pending', 'private', 'future'],
        'numberposts' => -1,
        'suppress_filters' => true,
    ]);
    $archived = 0;
    foreach ($children as $child) {
        $catalog_slug = (string) get_post_meta($child->ID, '_gvspace_service_catalog_slug', true);
        $group = (string) get_post_meta($child->ID, '_gvspace_translation_group', true);
        $slug = $catalog_slug !== '' ? $catalog_slug : ($group !== '' ? $group : $child->post_name);
        if (in_array($slug, $keep_slugs, true)) {
            continue;
        }
        wp_update_post([
            'ID' => $child->ID,
            'post_status' => 'draft',
        ]);
        update_post_meta($child->ID, '_gvspace_translation_status', 'draft');
        update_post_meta($child->ID, '_gvspace_service_archived', 'l3-import');
        clean_post_cache($child->ID);
        $archived++;
    }
    return $archived;
}

function gvspace_l3_import_upsert_service(int $parent_id, array $fields, int $order): int
{
    $slug = (string) $fields['slug'];
    $existing = get_posts([
        'post_type' => 'gv_service',
        'post_parent' => $parent_id,
        'post_status' => 'any',
        'numberposts' => 1,
        'meta_key' => '_gvspace_service_catalog_slug',
        'meta_value' => $slug,
    ]);
    $post_id = $existing ? (int) $existing[0]->ID : 0;
    if (!$post_id) {
        $by_name = get_posts([
            'post_type' => 'gv_service',
            'post_parent' => $parent_id,
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
        'post_parent' => $parent_id,
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
        $post_data['menu_order'] = $order;
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
    foreach (['headline', 'description', 'fit_cards', 'includes', 'steps', 'metrics', 'faq'] as $field) {
        update_post_meta($post_id, '_gvspace_service_' . $field . '_' . $locale, sanitize_textarea_field((string) ($fields[$field] ?? '')));
    }
    foreach ((array) ($fields['seo'] ?? []) as $seo_field => $value) {
        $clean = gvspace_sanitize_seo_meta_value((string) $seo_field, (string) $value);
        update_post_meta($post_id, '_gvspace_seo_' . $seo_field . '_' . $locale, $clean);
    }
    clean_post_cache($post_id);
    return $post_id;
}

function gvspace_l3_import_apply(array $payload): array
{
    $parent_id = (int) ($payload['parent_id'] ?? 0);
    $records = (array) ($payload['records'] ?? []);
    $parent = get_post($parent_id);
    if (!$parent || $parent->post_type !== 'gv_service' || (int) $parent->post_parent !== 0) {
        return ['ok' => false, 'message' => 'Оберіть напрямок L2.'];
    }
    if (!$records) {
        return ['ok' => false, 'message' => 'Немає розпізнаних послуг для імпорту.'];
    }

    $keep = array_values(array_unique(array_filter(array_map(static fn (array $record): string => (string) $record['slug'], $records))));
    $archived = !empty($payload['replace_catalog']) ? gvspace_l3_import_archive_children($parent_id, $keep) : 0;
    $created = 0;
    $locales = [];
    foreach ($records as $order => $record) {
        if (gvspace_l3_import_upsert_service($parent_id, $record, (int) $order)) {
            $created++;
            $locales[(string) $record['locale']] = true;
        }
    }
    flush_rewrite_rules(false);
    return [
        'ok' => true,
        'message' => sprintf(
            'Імпорт завершено: оновлено %d мовних версій (%s). В архів перенесено %d послуг.',
            $created,
            implode(', ', array_keys($locales)) ?: '—',
            $archived
        ),
    ];
}

function gvspace_l3_import_transient_key(): string
{
    return 'gvspace_l3_import_' . get_current_user_id();
}

add_action('admin_menu', static function (): void {
    add_submenu_page(
        'edit.php?post_type=gv_service',
        'Імпорт послуг L3',
        'Імпорт L3',
        'edit_posts',
        'gvspace-import-l3',
        'gvspace_render_l3_import_page'
    );
});

add_action('admin_init', static function (): void {
    if (!is_admin() || !current_user_can('edit_posts')) {
        return;
    }
    if (($_GET['page'] ?? '') !== 'gvspace-import-l3' || ($_GET['download'] ?? '') !== 'template') {
        return;
    }
    check_admin_referer('gvspace_l3_import_template');
    $path = __DIR__ . '/templates/l3-service.md';
    if (!is_readable($path)) {
        wp_die('Шаблон не знайдено.');
    }
    nocache_headers();
    header('Content-Type: text/markdown; charset=utf-8');
    header('Content-Disposition: attachment; filename="gvspace-l3-service.md"');
    readfile($path);
    exit;
});

function gvspace_render_l3_import_page(): void
{
    if (!current_user_can('edit_posts')) {
        return;
    }

    $notice = '';
    $notice_type = 'info';
    $preview = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        check_admin_referer('gvspace_l3_import');
        $action = sanitize_key((string) ($_POST['gvspace_l3_action'] ?? ''));
        if ($action === 'preview') {
            $parent_id = absint($_POST['gvspace_l3_parent'] ?? 0);
            $uploads = gvspace_l3_import_collect_uploads('gvspace_l3_files');
            if ($uploads['error'] !== '' && !$uploads['files']) {
                $notice = $uploads['error'];
                $notice_type = 'error';
            } elseif ($parent_id <= 0) {
                $notice = 'Оберіть напрямок L2, у який завантажуємо послуги.';
                $notice_type = 'error';
            } else {
                $replace_catalog = !empty($_POST['gvspace_l3_replace_catalog']);
                $preview = gvspace_l3_import_preview_payload($parent_id, $uploads['files'], $replace_catalog);
                if ($uploads['error'] !== '') {
                    $preview['errors'][] = $uploads['error'];
                }
                if ($preview['records']) {
                    set_transient(gvspace_l3_import_transient_key(), $preview, 30 * MINUTE_IN_SECONDS);
                } else {
                    $notice = $preview['errors'] ? implode(' ', $preview['errors']) : 'Не вдалося розпізнати жодної послуги.';
                    $notice_type = 'error';
                    $preview = null;
                }
            }
        } elseif ($action === 'apply') {
            $stored = get_transient(gvspace_l3_import_transient_key());
            if (!is_array($stored) || empty($stored['records'])) {
                $notice = 'Прев’ю застаріло. Завантажте файли ще раз.';
                $notice_type = 'error';
            } else {
                $result = gvspace_l3_import_apply($stored);
                $notice = $result['message'];
                $notice_type = $result['ok'] ? 'success' : 'error';
                if ($result['ok']) {
                    delete_transient(gvspace_l3_import_transient_key());
                }
            }
        } elseif ($action === 'cancel') {
            delete_transient(gvspace_l3_import_transient_key());
            $notice = 'Імпорт скасовано. Файли не застосовано.';
        }
    }

    $template_url = wp_nonce_url(
        admin_url('edit.php?post_type=gv_service&page=gvspace-import-l3&download=template'),
        'gvspace_l3_import_template'
    );
    $directions = gvspace_get_service_directions();
    ?>
    <div class="wrap">
        <h1>Імпорт послуг L3</h1>
        <p>Завантажте файли маркетолога у форматі адмінки. Один файл = одна послуга однією мовою. Українська вже залита не затирається, якщо в пакеті лише інші мови. Файли в репозиторій не потрапляють.</p>
        <p><a class="button" href="<?php echo esc_url($template_url); ?>">Завантажити шаблон .md</a></p>
        <?php if ($notice !== '') : ?>
            <div class="notice notice-<?php echo esc_attr($notice_type); ?> is-dismissible"><p><?php echo esc_html($notice); ?></p></div>
        <?php endif; ?>

        <?php if (is_array($preview)) : ?>
            <h2>Прев’ю</h2>
            <?php if ($preview['errors']) : ?>
                <div class="notice notice-warning"><p><?php echo esc_html(implode(' ', $preview['errors'])); ?></p></div>
            <?php endif; ?>
            <p>Напрямок: <strong><?php echo esc_html(get_the_title((int) $preview['parent_id'])); ?></strong>
                · архів каталогу: <strong><?php echo !empty($preview['replace_catalog']) ? 'так' : 'ні, лише дописуємо мови'; ?></strong></p>
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
                            foreach (['fit_cards' => 'кому підходить', 'includes' => 'що входить', 'steps' => 'етапи', 'metrics' => 'результати', 'faq' => 'FAQ'] as $key => $label) {
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
                        <li><?php echo esc_html($item['title']); ?> <code><?php echo esc_html($item['slug']); ?></code></li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>Опублікованих L3, яких немає у файлах, не знайдено. Існуючі з тим самим slug будуть оновлені.</p>
            <?php endif; ?>
            <form method="post">
                <?php wp_nonce_field('gvspace_l3_import'); ?>
                <p>
                    <button class="button button-primary" name="gvspace_l3_action" value="apply" type="submit">Підтвердити імпорт</button>
                    <button class="button" name="gvspace_l3_action" value="cancel" type="submit">Скасувати</button>
                </p>
            </form>
        <?php else : ?>
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('gvspace_l3_import'); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="gvspace_l3_parent">Напрямок L2</label></th>
                        <td>
                            <select id="gvspace_l3_parent" name="gvspace_l3_parent" required>
                                <option value="">Оберіть напрямок</option>
                                <?php foreach ($directions as $direction) : ?>
                                    <option value="<?php echo esc_attr((string) $direction->ID); ?>"><?php echo esc_html($direction->post_title); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="gvspace_l3_files">Файли</label></th>
                        <td>
                            <input id="gvspace_l3_files" name="gvspace_l3_files[]" type="file" accept=".md,.zip,text/markdown,application/zip" multiple required>
                            <p class="description">Кілька .md або один .zip. Мова з імені: <code>gvspace-l3-android.md</code> = uk, <code>gvspace-l3-android.en.md</code> = en, <code>gvspace-l3-android.de-DE.md</code> = de-DE. Можна також вказати поле «Мова» в файлі.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Каталог</th>
                        <td>
                            <label>
                                <input type="checkbox" name="gvspace_l3_replace_catalog" value="1" checked>
                                Замінити L3 цього напрямку: послуги, яких немає у файлах, підуть у чернетки
                            </label>
                            <p class="description">Зніміть галочку, якщо доливаєте переклади до вже існуючих послуг. Інші мови в адмінці не затираються в будь-якому разі.</p>
                        </td>
                    </tr>
                </table>
                <p><button class="button button-primary" name="gvspace_l3_action" value="preview" type="submit">Показати прев’ю</button></p>
            </form>
        <?php endif; ?>
    </div>
    <?php
}
