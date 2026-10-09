<?php

if (!defined('ABSPATH')) {
    exit;
}

function gvspace_case_import_media_extensions(): array
{
    return ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg', 'mp4', 'webm'];
}

function gvspace_case_import_empty_record(string $filename): array
{
    return [
        'filename' => $filename,
        'slug' => '',
        'direction' => '',
        'project_type' => '',
        'logo_file' => '',
        'cover_file' => '',
        'card_file' => '',
        'brief_1' => '',
        'brief_2' => '',
        'step1_media' => '',
        'step2_media' => '',
        'step3_media' => '',
        'result_layout' => '',
        'result_1' => '',
        'result_2' => '',
        'result_3' => '',
        'result_4' => '',
        'result_5' => '',
        'author_photo' => '',
        'order' => '',
        'has_blocks' => false,
        'blocks' => [],
        'locales' => [],
        'warnings' => [],
    ];
}

function gvspace_case_import_heading_key(string $heading): string
{
    $heading = html_entity_decode($heading, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $heading = str_replace(['—', '–', '−'], '-', $heading);
    $heading = preg_replace('/\s+/u', ' ', trim($heading)) ?? trim($heading);
    return function_exists('mb_strtolower') ? mb_strtolower($heading, 'UTF-8') : strtolower($heading);
}

function gvspace_case_import_filename_parts(string $filename): array
{
    $base = strtolower((string) pathinfo($filename, PATHINFO_FILENAME));
    $base = preg_replace('/^gvspace-case-/', '', $base) ?? $base;
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
    if (in_array($slug, ['case', 'template'], true)) {
        $slug = '';
    }
    return ['slug' => $slug, 'locale' => $locale];
}

function gvspace_case_import_section_scope(string $heading): string
{
    if (preg_match('/\(([a-z]{2}(?:-[a-z]{2})?)\)/i', $heading, $match)) {
        $code = gvspace_l3_import_normalize_locale($match[1]);
        if ($code !== '') {
            return $code;
        }
    }
    $key = gvspace_case_import_heading_key($heading);
    if (str_starts_with($key, 'спіль') || str_starts_with($key, 'shared')) {
        return 'shared';
    }
    return gvspace_l3_import_normalize_locale($heading);
}

function gvspace_case_import_split_files(string $value): array
{
    $parts = preg_split('/[\r\n,]+/', $value) ?: [];
    $files = [];
    foreach ($parts as $part) {
        $name = trim($part, " \t`*_");
        if ($name === '' || str_contains($name, ' ')) {
            continue;
        }
        $files[] = $name;
    }
    return $files;
}

function gvspace_case_import_ensure_block(array &$record, int $index): void
{
    while (count($record['blocks']) <= $index) {
        $record['blocks'][] = [
            'layout' => 'text',
            'side' => 'left',
            'tone' => 'dark',
            'files' => [],
        ];
    }
}

function gvspace_case_import_ensure_locale(array &$record, string $locale): void
{
    if ($locale === '' || $locale === 'shared') {
        return;
    }
    if (!isset($record['locales'][$locale])) {
        $record['locales'][$locale] = [
            'title' => '',
            'catalog_title' => '',
            'excerpt' => '',
            'subtitle' => '',
            'metrics' => '',
            'lead' => '',
            'context_title' => '',
            'context_body' => '',
            'has_copy' => false,
            'blocks' => [],
            'present' => [],
        ];
    }
}

function gvspace_case_import_ensure_copy(array &$record, string $locale, int $index): void
{
    gvspace_case_import_ensure_locale($record, $locale);
    while (count($record['locales'][$locale]['blocks']) <= $index) {
        $record['locales'][$locale]['blocks'][] = ['heading' => '', 'body' => '', 'label' => '', 'touched' => false];
    }
}

function gvspace_case_import_assign_field(array &$record, string $scope, string $heading, string $value): void
{
    $key = gvspace_case_import_heading_key($heading);
    if (preg_match('/^блок\s+(\d+)\s+-\s+(.+)$/u', $key, $match)) {
        $index = max(0, (int) $match[1] - 1);
        $part = trim($match[2]);
        if (in_array($part, ['варіант розкладки', 'сторона медіа', 'стиль картки', 'файли медіа'], true)) {
            gvspace_case_import_ensure_block($record, $index);
            $record['has_blocks'] = true;
            if ($part === 'варіант розкладки') {
                $layout = sanitize_key($value);
                $record['blocks'][$index]['layout'] = isset(gvspace_case_block_layouts()[$layout]) ? $layout : 'text';
                if (!isset(gvspace_case_block_layouts()[$layout]) && $value !== '') {
                    $record['warnings'][] = 'Блок ' . ($index + 1) . ': невідомий варіант розкладки, поставлено текстовий блок.';
                }
            } elseif ($part === 'сторона медіа') {
                $side = sanitize_key($value);
                $record['blocks'][$index]['side'] = in_array($side, ['left', 'right'], true) ? $side : 'left';
            } elseif ($part === 'стиль картки') {
                $tone = sanitize_key($value);
                $record['blocks'][$index]['tone'] = $tone === 'light' ? 'light' : 'dark';
            } else {
                $record['blocks'][$index]['files'] = gvspace_case_import_split_files($value);
            }
            return;
        }
        if ($scope === 'shared' || $scope === '') {
            return;
        }
        gvspace_case_import_ensure_copy($record, $scope, $index);
        $record['locales'][$scope]['has_copy'] = true;
        $record['locales'][$scope]['blocks'][$index]['touched'] = true;
        if ($part === 'заголовок') {
            $record['locales'][$scope]['blocks'][$index]['heading'] = $value;
        } elseif ($part === 'текст') {
            $record['locales'][$scope]['blocks'][$index]['body'] = $value;
        } elseif ($part === 'підпис картки') {
            $record['locales'][$scope]['blocks'][$index]['label'] = $value;
        }
        return;
    }

    $shared = [
        'slug' => 'slug',
        'напрямок' => 'direction',
        'тип проєкту' => 'project_type',
        'логотип клієнта - файл' => 'logo_file',
        'обкладинка сторінки - файл' => 'cover_file',
        'фото картки каталогу - файл' => 'card_file',
        'бриф - ліве медіа' => 'brief_1',
        'бриф - праве медіа' => 'brief_2',
        'крок 1 - медіа' => 'step1_media',
        'крок 2 - медіа' => 'step2_media',
        'крок 3 - медіа' => 'step3_media',
        'розкладка результату' => 'result_layout',
        'результат - широке медіа' => 'result_1',
        'результат - нижнє ліве' => 'result_2',
        'результат - нижнє праве' => 'result_3',
        'результат - кадр 4' => 'result_4',
        'результат - кадр 5' => 'result_5',
        'фото автора відгуку' => 'author_photo',
        'порядок' => 'order',
    ];
    if (isset($shared[$key])) {
        $field = $shared[$key];
        $record[$field] = $field === 'slug' ? sanitize_title($value) : trim($value);
        return;
    }

    if ($scope === 'shared' || $scope === '') {
        return;
    }
    $seo_fields = [
        'seo title' => 'title',
        'meta description' => 'description',
        'h1 сторінки' => 'h1',
        'open graph title' => 'og_title',
        'open graph description' => 'og_description',
        'open graph image url' => 'og_image',
        'canonical url' => 'canonical',
    ];
    if (isset($seo_fields[$key])) {
        gvspace_case_import_ensure_locale($record, $scope);
        $record['locales'][$scope]['seo'][$seo_fields[$key]] = trim($value);
        return;
    }
    $localized = [
        'мова' => 'locale_check',
        'назва кейсу на сторінці' => 'title',
        'назва на картці в каталозі' => 'catalog_title',
        'результат на картці одним реченням' => 'excerpt',
        'підзаголовок під назвою' => 'subtitle',
        'метрики' => 'metrics',
        'підпис сірої картки' => 'media_label',
        'текст зліва під фото' => 'lead',
        'індустрія' => 'industry',
        'ринок' => 'market',
        'період' => 'period',
        'статус' => 'status',
        'послуги' => 'service_tags',
        'технології' => 'tech_tags',
        'бриф - текст' => 'challenge',
        'список проблем' => 'problems',
        'цілі - заголовок' => 'goals_title',
        'цілі' => 'goals',
        'процес - заголовок' => 'process_title',
        'крок 1 - заголовок' => 'step1_title',
        'крок 1 - текст' => 'step1',
        'крок 1 - результат' => 'step1_result',
        'крок 2 - заголовок' => 'step2_title',
        'крок 2 - текст' => 'step2',
        'крок 2 - вектори' => 'architecture',
        'крок 3 - заголовок' => 'step3_title',
        'крок 3 - текст' => 'step3',
        'крок 3 - результат' => 'step3_result',
        'результат - заголовок' => 'result_title',
        'результат - текст' => 'result_lead',
        'відгук - цитата' => 'testimonial',
        'відгук - ім’я' => 'testimonial_author',
        'відгук - ім\'я' => 'testimonial_author',
        'відгук - посада' => 'testimonial_company',
    ];
    $target = null;
    foreach ($localized as $needle => $field) {
        if ($key === $needle || str_starts_with($key, $needle)) {
            $target = $field;
            break;
        }
    }
    if ($target === null || $target === 'locale_check') {
        return;
    }
    gvspace_case_import_ensure_locale($record, $scope);
    $record['locales'][$scope][$target] = $value;
    $record['locales'][$scope]['present'][$target] = true;
}

function gvspace_case_import_consume_sections(array &$record, string $scope, string $markdown): void
{
    $sections = preg_split('/^###\s+/m', $markdown) ?: [];
    array_shift($sections);
    foreach ($sections as $section) {
        $chunk = explode("\n", $section, 2);
        $heading = trim($chunk[0] ?? '');
        $value = gvspace_l3_import_extract_value($chunk[1] ?? '');
        if ($heading === '') {
            continue;
        }
        gvspace_case_import_assign_field($record, $scope, $heading, $value);
    }
}

function gvspace_case_import_parse_markdown(string $markdown, string $filename): array
{
    $record = gvspace_case_import_empty_record($filename);
    $parts = gvspace_case_import_filename_parts($filename);
    $record['slug'] = $parts['slug'];
    $markdown = str_replace(["\r\n", "\r"], "\n", $markdown);

    if (preg_match('/^##\s+/m', $markdown)) {
        $chunks = preg_split('/^##\s+/m', $markdown) ?: [];
        array_shift($chunks);
        foreach ($chunks as $chunk) {
            $lines = explode("\n", $chunk, 2);
            $scope = gvspace_case_import_section_scope(trim($lines[0] ?? ''));
            if ($scope === '') {
                $record['warnings'][] = 'Розділ «' . trim($lines[0] ?? '') . '» пропущено: мову не розпізнано.';
                continue;
            }
            gvspace_case_import_consume_sections($record, $scope, $lines[1] ?? '');
        }
    } else {
        $scope = $parts['locale'] !== '' ? $parts['locale'] : 'uk';
        gvspace_case_import_consume_sections($record, $scope, $markdown);
        if ($parts['locale'] === '') {
            $declared = '';
            if (preg_match('/^###\s+Мова\s*\n+```[^\n]*\n(.*?)```/isu', $markdown, $match)) {
                $declared = gvspace_l3_import_normalize_locale(trim($match[1]));
            }
            if ($declared !== '' && $declared !== 'uk') {
                $record['locales'][$declared] = $record['locales']['uk'] ?? [];
                unset($record['locales']['uk']);
            }
        }
    }

    if ($record['slug'] === '') {
        foreach ($record['locales'] as $locale_fields) {
            if (!empty($locale_fields['title'])) {
                $record['slug'] = sanitize_title((string) $locale_fields['title']);
                break;
            }
        }
    }
    if ($record['slug'] === '') {
        $record['warnings'][] = 'Немає slug і назви — файл пропущено.';
    }
    $has_title = false;
    foreach ($record['locales'] as $locale => $locale_fields) {
        if (trim((string) ($locale_fields['title'] ?? '')) !== '') {
            $has_title = true;
        } else {
            $record['warnings'][] = 'У мові ' . $locale . ' немає назви кейсу.';
        }
        $metrics = (string) ($locale_fields['metrics'] ?? '');
        if ($metrics !== '' && !str_contains($metrics, '|')) {
            $record['warnings'][] = 'Метрики мови ' . $locale . ' мають бути у форматі значення | підпис.';
        }
    }
    if (!$has_title) {
        $record['warnings'][] = 'Немає назви кейсу — файл пропущено.';
        $record['slug'] = '';
    }
    return $record;
}

function gvspace_case_import_temp_dir(): string
{
    $upload = wp_upload_dir();
    $root = trailingslashit($upload['basedir']) . 'gvspace-case-import';
    if (!is_dir($root) && !wp_mkdir_p($root)) {
        return '';
    }
    if (!is_file($root . '/index.php')) {
        file_put_contents($root . '/index.php', "<?php\n// Silence is golden.\n");
    }
    $dir = $root . '/' . get_current_user_id();
    if (!is_dir($dir)) {
        wp_mkdir_p($dir);
    }
    return $dir;
}

function gvspace_case_import_clean_temp(): void
{
    $dir = gvspace_case_import_temp_dir();
    if ($dir === '') {
        return;
    }
    $entries = glob(trailingslashit($dir) . '*') ?: [];
    foreach ($entries as $entry) {
        if (is_file($entry)) {
            unlink($entry);
        }
    }
}

function gvspace_case_import_read_zip(string $tmp_path): array
{
    if (!class_exists(ZipArchive::class)) {
        return ['files' => [], 'error' => 'На сервері немає ZipArchive. Завантажте .md і медіа окремими файлами.'];
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
        $allowed = $extension === 'md' || in_array($extension, gvspace_case_import_media_extensions(), true);
        if (!$allowed) {
            continue;
        }
        $contents = $zip->getFromIndex($i);
        if (!is_string($contents) || $contents === '') {
            continue;
        }
        $total += strlen($contents);
        if (count($files) >= 400 || $total > 128 * 1024 * 1024) {
            $zip->close();
            return ['files' => [], 'error' => 'Архів завеликий. Залиште в ньому .md і медіа цього пакета.'];
        }
        $files[] = ['name' => basename($name), 'contents' => $contents];
    }
    $zip->close();
    return ['files' => $files, 'error' => ''];
}

function gvspace_case_import_collect_uploads(string $field): array
{
    if (empty($_FILES[$field]) || !is_array($_FILES[$field]['name'])) {
        return ['files' => [], 'error' => 'Додайте .md, медіа або .zip.'];
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
            $extracted = gvspace_case_import_read_zip($tmp);
            if ($extracted['error'] !== '') {
                $errors[] = $extracted['error'];
                continue;
            }
            $files = array_merge($files, $extracted['files']);
            continue;
        }
        $allowed = $extension === 'md' || in_array($extension, gvspace_case_import_media_extensions(), true);
        if (!$allowed) {
            $errors[] = $name . ' пропущено: потрібен .md, зображення, відео або .zip.';
            continue;
        }
        $contents = (string) file_get_contents($tmp);
        if ($contents === '') {
            $errors[] = $name . ' порожній.';
            continue;
        }
        if (strlen($contents) > 20 * 1024 * 1024) {
            $errors[] = $name . ' більший за 20 МБ.';
            continue;
        }
        $files[] = ['name' => $name, 'contents' => $contents];
    }
    if (!$files && !$errors) {
        $errors[] = 'Додайте .md, медіа або .zip.';
    }
    return ['files' => $files, 'error' => implode(' ', $errors)];
}

function gvspace_case_import_store_media(array $files): array
{
    gvspace_case_import_clean_temp();
    $dir = gvspace_case_import_temp_dir();
    $media = [];
    $errors = [];
    if ($dir === '') {
        return ['media' => [], 'errors' => ['Не вдалося створити тимчасову теку для медіа.']];
    }
    foreach ($files as $file) {
        $name = (string) $file['name'];
        $extension = strtolower((string) pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($extension, gvspace_case_import_media_extensions(), true)) {
            continue;
        }
        $contents = (string) $file['contents'];
        if ($extension === 'svg' && function_exists('gvspace_tech_import_svg_is_safe') && !gvspace_tech_import_svg_is_safe($contents)) {
            $errors[] = $name . ': SVG містить активний вміст і пропущений.';
            continue;
        }
        $key = strtolower(basename($name));
        $safe = sanitize_file_name($key);
        if ($safe === '') {
            $safe = 'media-' . md5($key) . '.' . $extension;
        }
        $path = trailingslashit($dir) . $safe;
        if (file_put_contents($path, $contents) === false) {
            $errors[] = $name . ': не вдалося зберегти файл.';
            continue;
        }
        $media[$key] = [
            'name' => basename($name),
            'path' => $path,
            'extension' => $extension,
        ];
    }
    return ['media' => $media, 'errors' => $errors];
}

function gvspace_case_import_missing_files(array $record, array $media): array
{
    $wanted = array_filter([
        (string) $record['logo_file'],
        (string) $record['cover_file'],
        (string) $record['card_file'],
        (string) ($record['brief_1'] ?? ''),
        (string) ($record['brief_2'] ?? ''),
        (string) ($record['step1_media'] ?? ''),
        (string) ($record['step2_media'] ?? ''),
        (string) ($record['step3_media'] ?? ''),
        (string) ($record['result_1'] ?? ''),
        (string) ($record['result_2'] ?? ''),
        (string) ($record['result_3'] ?? ''),
        (string) ($record['author_photo'] ?? ''),
    ]);
    foreach ($record['blocks'] as $block) {
        foreach ((array) $block['files'] as $file) {
            $wanted[] = (string) $file;
        }
    }
    $missing = [];
    foreach ($wanted as $file) {
        if ($file !== '' && !isset($media[strtolower(basename($file))])) {
            $missing[] = $file;
        }
    }
    return array_values(array_unique($missing));
}

function gvspace_case_import_preview(array $files, bool $replace_catalog): array
{
    $stored = gvspace_case_import_store_media($files);
    $records = [];
    $errors = $stored['errors'];
    foreach ($files as $file) {
        if (strtolower((string) pathinfo((string) $file['name'], PATHINFO_EXTENSION)) !== 'md') {
            continue;
        }
        $record = gvspace_case_import_parse_markdown((string) $file['contents'], (string) $file['name']);
        if ($record['slug'] === '') {
            $errors = array_merge($errors, $record['warnings']);
            continue;
        }
        $records[] = $record;
    }
    if (!$records) {
        gvspace_case_import_clean_temp();
    }
    $archive = [];
    if ($replace_catalog && $records) {
        $keep = array_map(static fn (array $record): string => (string) $record['slug'], $records);
        $posts = get_posts([
            'post_type' => 'gv_case',
            'post_status' => 'publish',
            'posts_per_page' => 200,
        ]);
        foreach ($posts as $post) {
            $slug = (string) $post->post_name;
            if (!in_array($slug, $keep, true)) {
                $archive[] = ['title' => $post->post_title, 'slug' => $slug];
            }
        }
    }
    return [
        'records' => $records,
        'media' => $stored['media'],
        'errors' => $errors,
        'archive' => $archive,
        'replace_catalog' => $replace_catalog,
    ];
}

function gvspace_case_import_create_attachment(array $file, int $parent_id, array &$cache): int
{
    $key = strtolower((string) ($file['name'] ?? ''));
    if ($key !== '' && isset($cache[$key])) {
        return (int) $cache[$key];
    }
    $path = (string) ($file['path'] ?? '');
    $dir = gvspace_case_import_temp_dir();
    if ($path === '' || $dir === '' || !gvspace_tech_import_path_is_inside($path, $dir)) {
        return 0;
    }
    $extension = strtolower((string) ($file['extension'] ?? pathinfo($path, PATHINFO_EXTENSION)));
    $contents = (string) file_get_contents($path);
    if ($contents === '') {
        return 0;
    }
    if ($extension === 'svg' && function_exists('gvspace_tech_import_svg_is_safe') && !gvspace_tech_import_svg_is_safe($contents)) {
        return 0;
    }
    $mime = [
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'webp' => 'image/webp',
        'gif' => 'image/gif',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'mp4' => 'video/mp4',
        'webm' => 'video/webm',
    ][$extension] ?? '';
    if ($mime === '') {
        return 0;
    }
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    $GLOBALS['gvspace_allow_svg_seed'] = true;
    $upload = wp_upload_bits(sanitize_file_name((string) $file['name']), null, $contents);
    $GLOBALS['gvspace_allow_svg_seed'] = false;
    if (!empty($upload['error']) || empty($upload['file'])) {
        return 0;
    }
    $attachment_id = wp_insert_attachment([
        'post_mime_type' => $mime,
        'post_title' => sanitize_file_name((string) pathinfo((string) $file['name'], PATHINFO_FILENAME)),
        'post_status' => 'inherit',
        'guid' => $upload['url'],
    ], $upload['file'], $parent_id);
    if (!$attachment_id || is_wp_error($attachment_id)) {
        return 0;
    }
    if (str_starts_with($mime, 'image/')) {
        wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata((int) $attachment_id, $upload['file']));
    }
    $cache[$key] = (int) $attachment_id;
    return (int) $attachment_id;
}

function gvspace_case_import_resolve_file(string $filename, array $media, int $parent_id, array &$cache, int $previous_id): int
{
    $filename = trim($filename);
    if ($filename === '') {
        return 0;
    }
    $key = strtolower(basename($filename));
    if (!isset($media[$key])) {
        return $previous_id;
    }
    $created = gvspace_case_import_create_attachment($media[$key], $parent_id, $cache);
    return $created ?: $previous_id;
}

function gvspace_case_import_upsert(array $record, array $media): int
{
    $slug = (string) $record['slug'];
    if ($slug === '') {
        return 0;
    }
    $existing = gvspace_find_seeded_case($slug);
    $post_id = $existing ? (int) $existing->ID : 0;
    $uk_title = sanitize_text_field((string) ($record['locales']['uk']['title'] ?? ''));
    $any_title = $uk_title;
    if ($any_title === '') {
        foreach ($record['locales'] as $locale_fields) {
            if (trim((string) ($locale_fields['title'] ?? '')) !== '') {
                $any_title = sanitize_text_field((string) $locale_fields['title']);
                break;
            }
        }
    }
    $post_data = [
        'post_type' => 'gv_case',
        'post_status' => 'publish',
    ];
    if ($record['order'] !== '' && is_numeric($record['order'])) {
        $post_data['menu_order'] = (int) $record['order'];
    }
    if ($post_id) {
        $post_data['ID'] = $post_id;
        if ($uk_title !== '') {
            $post_data['post_title'] = $uk_title;
        }
        $updated = wp_update_post($post_data, true);
        if (is_wp_error($updated)) {
            return 0;
        }
    } else {
        $post_data['post_title'] = $any_title !== '' ? $any_title : $slug;
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

    if ($record['direction'] !== '') {
        $direction = gvspace_ensure_case_term((string) $record['direction'], 'gv_case_direction');
        if ($direction !== '') {
            wp_set_object_terms($post_id, [$direction], 'gv_case_direction', false);
        }
    }
    if ($record['project_type'] !== '') {
        $type = gvspace_ensure_case_term((string) $record['project_type'], 'gv_case_project_type');
        if ($type !== '') {
            wp_set_object_terms($post_id, [$type], 'gv_case_project_type', false);
        }
    }

    $cache = [];
    $previous_blocks = gvspace_case_read_blocks($post_id);
    if (!empty($record['has_blocks'])) {
        $blocks = [];
        foreach ($record['blocks'] as $index => $block) {
            $layout = (string) $block['layout'];
            if (!isset(gvspace_case_block_layouts()[$layout])) {
                $layout = 'text';
            }
            $slots = gvspace_case_block_layout($layout)['slots'];
            $images = [];
            foreach ($slots as $slot_index => $slot_label) {
                unset($slot_label);
                $previous_id = (int) ($previous_blocks[$index]['images'][$slot_index] ?? 0);
                if (($previous_blocks[$index]['layout'] ?? '') !== $layout) {
                    $previous_id = 0;
                }
                $images[] = gvspace_case_import_resolve_file(
                    (string) ($block['files'][$slot_index] ?? ''),
                    $media,
                    $post_id,
                    $cache,
                    $previous_id
                );
            }
            $blocks[] = [
                'layout' => $layout,
                'side' => ($block['side'] ?? '') === 'right' ? 'right' : 'left',
                'tone' => ($block['tone'] ?? '') === 'light' ? 'light' : 'dark',
                'images' => $images,
            ];
        }
        gvspace_case_update_json_meta($post_id, '_gvspace_case_blocks', $blocks);
    }

    $logo_id = gvspace_case_import_resolve_file(
        (string) $record['logo_file'],
        $media,
        $post_id,
        $cache,
        $record['logo_file'] !== '' ? absint(get_post_meta($post_id, '_gvspace_case_logo_id', true)) : 0
    );
    if ($record['logo_file'] !== '') {
        if ($logo_id) {
            update_post_meta($post_id, '_gvspace_case_logo_id', $logo_id);
        }
    }
    $cover_id = gvspace_case_import_resolve_file(
        (string) $record['cover_file'],
        $media,
        $post_id,
        $cache,
        $record['cover_file'] !== '' ? absint(get_post_meta($post_id, '_gvspace_case_cover_id', true)) : 0
    );
    if ($record['cover_file'] !== '') {
        if ($cover_id) {
            update_post_meta($post_id, '_gvspace_case_cover_id', $cover_id);
        }
    }
    $card_id = gvspace_case_import_resolve_file((string) $record['card_file'], $media, $post_id, $cache, 0);
    if ($card_id) {
        set_post_thumbnail($post_id, $card_id);
    }
    $slot_files = [
        'brief_1' => '_gvspace_case_brief_1',
        'brief_2' => '_gvspace_case_brief_2',
        'step1_media' => '_gvspace_case_step1_media',
        'step2_media' => '_gvspace_case_step2_media',
        'step3_media' => '_gvspace_case_step3_media',
        'result_1' => '_gvspace_case_result_1',
        'result_2' => '_gvspace_case_result_2',
        'result_3' => '_gvspace_case_result_3',
        'result_4' => '_gvspace_case_result_4',
        'result_5' => '_gvspace_case_result_5',
        'author_photo' => '_gvspace_case_author_photo',
    ];
    foreach ($slot_files as $file_key => $meta_key) {
        $filename = (string) ($record[$file_key] ?? '');
        if ($filename === '') {
            continue;
        }
        $attachment_id = gvspace_case_import_resolve_file(
            $filename,
            $media,
            $post_id,
            $cache,
            absint(get_post_meta($post_id, $meta_key, true))
        );
        if ($attachment_id) {
            update_post_meta($post_id, $meta_key, $attachment_id);
        }
    }
    $result_layout = sanitize_key((string) ($record['result_layout'] ?? ''));
    if ($result_layout !== '' && function_exists('gvspace_case_result_layouts') && array_key_exists($result_layout, gvspace_case_result_layouts())) {
        update_post_meta($post_id, '_gvspace_case_result_layout', $result_layout);
    }

    foreach ($record['locales'] as $locale => $fields) {
        if (!isset(GVSPACE_CONTENT_LOCALES[$locale])) {
            continue;
        }
        if (trim((string) ($fields['title'] ?? '')) !== '') {
            update_post_meta($post_id, '_gvspace_case_title_' . $locale, sanitize_text_field((string) $fields['title']));
        }
        foreach (array_keys(GVSPACE_LOCALIZED_CASE_FIELDS) as $field) {
            if (empty($fields['present'][$field])) {
                continue;
            }
            update_post_meta($post_id, '_gvspace_case_' . $field . '_' . $locale, sanitize_textarea_field((string) ($fields[$field] ?? '')));
        }
        foreach ((array) ($fields['seo'] ?? []) as $seo_field => $value) {
            if (!is_string($seo_field) || !array_key_exists($seo_field, GVSPACE_SEO_FIELDS)) {
                continue;
            }
            $raw = trim((string) $value);
            if ($seo_field === 'og_image' && $raw !== '' && !preg_match('#^https?://#i', $raw)) {
                $attachment_id = gvspace_case_import_resolve_file($raw, $media, $post_id, $cache, 0);
                $raw = $attachment_id ? (string) wp_get_attachment_url($attachment_id) : '';
            }
            if ($raw === '') {
                continue;
            }
            update_post_meta(
                $post_id,
                '_gvspace_seo_' . $seo_field . '_' . $locale,
                gvspace_sanitize_seo_meta_value($seo_field, $raw)
            );
        }
        if (!empty($fields['has_copy'])) {
            $existing_copy = gvspace_case_read_block_copy($post_id, $locale);
            $copies = $existing_copy;
            foreach ($fields['blocks'] as $index => $copy) {
                if (empty($copy['touched'])) {
                    continue;
                }
                $copies[$index] = [
                    'heading' => sanitize_text_field((string) ($copy['heading'] ?? '')),
                    'body' => sanitize_textarea_field((string) ($copy['body'] ?? '')),
                    'label' => sanitize_text_field((string) ($copy['label'] ?? '')),
                ];
            }
            ksort($copies);
            gvspace_case_update_json_meta($post_id, '_gvspace_case_block_copy_' . $locale, $copies);
        }
    }

    clean_post_cache($post_id);
    return $post_id;
}

function gvspace_case_import_apply(array $payload): array
{
    $records = (array) ($payload['records'] ?? []);
    $media = (array) ($payload['media'] ?? []);
    if (!$records) {
        return ['ok' => false, 'message' => 'Немає розпізнаних кейсів для імпорту.'];
    }
    $updated = 0;
    $locales = [];
    foreach ($records as $record) {
        $post_id = gvspace_case_import_upsert($record, $media);
        if (!$post_id) {
            continue;
        }
        $updated++;
        foreach (array_keys((array) ($record['locales'] ?? [])) as $locale) {
            $locales[$locale] = true;
        }
    }
    $archived = 0;
    if (!empty($payload['replace_catalog'])) {
        $keep = array_map(static fn (array $record): string => (string) $record['slug'], $records);
        $posts = get_posts([
            'post_type' => 'gv_case',
            'post_status' => 'publish',
            'posts_per_page' => 200,
        ]);
        foreach ($posts as $post) {
            if (in_array((string) $post->post_name, $keep, true)) {
                continue;
            }
            wp_update_post(['ID' => $post->ID, 'post_status' => 'draft']);
            $archived++;
        }
    }
    gvspace_case_import_clean_temp();
    return [
        'ok' => true,
        'message' => sprintf(
            'Імпорт кейсів завершено: оновлено %d записів (%s). В чернетки перенесено %d.',
            $updated,
            implode(', ', array_keys($locales)) ?: 'без мов',
            $archived
        ),
    ];
}

function gvspace_case_import_transient_key(): string
{
    return 'gvspace_case_import_' . get_current_user_id();
}

add_action('admin_menu', static function (): void {
    add_submenu_page(
        'edit.php?post_type=gv_case',
        'Імпорт кейсів',
        'Імпорт',
        'edit_posts',
        'gvspace-import-cases',
        'gvspace_render_case_import_page'
    );
}, 9);

add_action('admin_init', static function (): void {
    if (!is_admin() || !current_user_can('edit_posts')) {
        return;
    }
    if (($_GET['page'] ?? '') !== 'gvspace-import-cases' || ($_GET['download'] ?? '') !== 'example') {
        return;
    }
    check_admin_referer('gvspace_case_import_example');
    $path = __DIR__ . '/templates/gvspace-case-porzellan-haus.md';
    if (!is_readable($path)) {
        wp_die('Приклад не знайдено.');
    }
    nocache_headers();
    header('Content-Type: text/markdown; charset=utf-8');
    header('Content-Disposition: attachment; filename="gvspace-case-porzellan-haus.md"');
    readfile($path);
    exit;
});

function gvspace_render_case_import_page(): void
{
    if (!current_user_can('edit_posts')) {
        return;
    }
    $notice = '';
    $notice_type = 'info';
    $preview = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        check_admin_referer('gvspace_case_import');
        $action = sanitize_key((string) ($_POST['gvspace_case_import_action'] ?? ''));
        if ($action === 'preview') {
            $uploads = gvspace_case_import_collect_uploads('gvspace_case_files');
            if ($uploads['error'] !== '' && !$uploads['files']) {
                $notice = $uploads['error'];
                $notice_type = 'error';
            } else {
                $preview = gvspace_case_import_preview($uploads['files'], !empty($_POST['gvspace_case_replace_catalog']));
                if ($uploads['error'] !== '') {
                    $preview['errors'][] = $uploads['error'];
                }
                if ($preview['records']) {
                    set_transient(gvspace_case_import_transient_key(), $preview, 30 * MINUTE_IN_SECONDS);
                } else {
                    gvspace_case_import_clean_temp();
                    $notice = $preview['errors'] ? implode(' ', $preview['errors']) : 'Не вдалося розпізнати жодного кейсу.';
                    $notice_type = 'error';
                    $preview = null;
                }
            }
        } elseif ($action === 'apply') {
            $stored = get_transient(gvspace_case_import_transient_key());
            if (!is_array($stored) || empty($stored['records'])) {
                $notice = 'Прев’ю застаріло. Завантажте файли ще раз.';
                $notice_type = 'error';
            } else {
                $result = gvspace_case_import_apply($stored);
                $notice = $result['message'];
                $notice_type = $result['ok'] ? 'success' : 'error';
                if ($result['ok']) {
                    delete_transient(gvspace_case_import_transient_key());
                }
            }
        } elseif ($action === 'cancel') {
            delete_transient(gvspace_case_import_transient_key());
            gvspace_case_import_clean_temp();
            $notice = 'Імпорт скасовано. Файли не застосовано.';
        }
    }
    $example_url = wp_nonce_url(
        admin_url('edit.php?post_type=gv_case&page=gvspace-import-cases&download=example'),
        'gvspace_case_import_example'
    );
    ?>
    <div class="wrap">
        <h1>Імпорт кейсів</h1>
        <p>Завантажте один або кілька <code>.md</code>. Один файл може містити всі мови одного кейсу: розділи <code>## Українська (uk)</code> і <code>## English (en)</code>. Кілька кейсів — кілька файлів або один <code>.zip</code>. Однаковий slug оновлює вже існуючий кейс, а не створює дубль. Картинки з файлу підставляються, лише якщо лежать у тому самому пакеті. Якщо їх немає, кейс усе одно створюється, а медіа можна завантажити кнопкою в записі.</p>
        <p><a class="button" href="<?php echo esc_url($example_url); ?>">Завантажити приклад PorzellanHaus (.md)</a></p>
        <?php if ($notice !== '') : ?>
            <div class="notice notice-<?php echo esc_attr($notice_type); ?> is-dismissible"><p><?php echo esc_html($notice); ?></p></div>
        <?php endif; ?>
        <?php if (is_array($preview)) : ?>
            <h2>Прев’ю</h2>
            <?php if ($preview['errors']) : ?>
                <div class="notice notice-warning"><p><?php echo esc_html(implode(' ', $preview['errors'])); ?></p></div>
            <?php endif; ?>
            <p>Кейси, яких немає у файлах, підуть у чернетки: <strong><?php echo !empty($preview['replace_catalog']) ? 'так' : 'ні'; ?></strong></p>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>Файл</th>
                        <th>Slug</th>
                        <th>Мови</th>
                        <th>Назва</th>
                        <th>Блоки</th>
                        <th>Медіа не в пакеті</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($preview['records'] as $record) : ?>
                    <tr>
                        <td><?php echo esc_html($record['filename']); ?></td>
                        <td><code><?php echo esc_html($record['slug']); ?></code></td>
                        <td><code><?php echo esc_html(implode(', ', array_keys($record['locales']))); ?></code></td>
                        <td><?php echo esc_html((string) ($record['locales']['uk']['title'] ?? '')); ?></td>
                        <td><?php echo esc_html((string) count($record['blocks'])); ?></td>
                        <td>
                            <?php
                            $missing = gvspace_case_import_missing_files($record, $preview['media']);
                            echo esc_html($missing ? implode(', ', $missing) : '—');
                            ?>
                            <?php if ($record['warnings']) : ?>
                                <br><span class="description"><?php echo esc_html(implode(' ', $record['warnings'])); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($preview['archive']) : ?>
                <h3>Підуть у чернетки (<?php echo count($preview['archive']); ?>)</h3>
                <ul>
                    <?php foreach ($preview['archive'] as $item) : ?>
                        <li><?php echo esc_html($item['title']); ?> <code><?php echo esc_html($item['slug']); ?></code></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <form method="post">
                <?php wp_nonce_field('gvspace_case_import'); ?>
                <p>
                    <button class="button button-primary" name="gvspace_case_import_action" value="apply" type="submit">Підтвердити імпорт</button>
                    <button class="button" name="gvspace_case_import_action" value="cancel" type="submit">Скасувати</button>
                </p>
            </form>
        <?php else : ?>
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('gvspace_case_import'); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="gvspace_case_files">Файли</label></th>
                        <td>
                            <input id="gvspace_case_files" name="gvspace_case_files[]" type="file" accept=".md,.zip,.png,.jpg,.jpeg,.webp,.gif,.svg,.mp4,.webm,text/markdown,application/zip" multiple required>
                            <p class="description">Можна вибрати кілька <code>.md</code> одразу. Файл <code>gvspace-case-slug.md</code> — усі мови разом. Окремий переклад: <code>gvspace-case-slug.en.md</code>. Медіа в пакеті має збігатися з іменем у полі, регістр не важливий.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Каталог</th>
                        <td>
                            <label>
                                <input type="checkbox" name="gvspace_case_replace_catalog" value="1">
                                Замінити каталог: опубліковані кейси, яких немає у файлах, стануть чернетками
                            </label>
                        </td>
                    </tr>
                </table>
                <p><button class="button button-primary" name="gvspace_case_import_action" value="preview" type="submit">Показати прев’ю</button></p>
            </form>
        <?php endif; ?>
    </div>
    <?php
}
