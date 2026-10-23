<?php
/** Blog category localization and admin helpers. */
if (!defined('ABSPATH')) exit;

add_action('init', function (): void {
    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        if ($locale === 'uk') continue;
        register_term_meta('category', '_gvspace_name_' . $locale, [
            'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback' => static fn (): bool => current_user_can('manage_categories'),
        ]);
    }
    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        foreach (['title', 'excerpt', 'content', 'markdown'] as $field) {
            register_post_meta('post', '_gvspace_blog_' . $field . '_' . $locale, [
                'type' => 'string', 'single' => true, 'show_in_rest' => true,
                'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
            ]);
        }
        register_post_meta('post', '_gvspace_blog_image_' . $locale, [
            'type' => 'integer', 'single' => true, 'show_in_rest' => true,
            'sanitize_callback' => 'absint',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
    }
}, 30);

function gvspace_blog_markdown_inline(string $value): string
{
    $value = esc_html($value);
    $value = preg_replace_callback('/`([^`]+)`/u', static fn (array $match): string => '<code>' . $match[1] . '</code>', $value) ?? $value;
    $value = preg_replace('/\*\*([^*]+)\*\*/u', '<strong>$1</strong>', $value) ?? $value;
    $value = preg_replace('/(?<!\*)\*([^*]+)\*(?!\*)/u', '<em>$1</em>', $value) ?? $value;
    $value = preg_replace_callback('/\[([^\]]+)\]\((https?:\/\/[^\s)]+|\/[^\s)]*)\)/u', static function (array $match): string {
        return '<a href="' . esc_url($match[2]) . '">' . $match[1] . '</a>';
    }, $value) ?? $value;
    return $value;
}

/** Convert the deliberately small editorial Markdown contract into safe article HTML. */
function gvspace_blog_markdown_to_html(string $markdown): string
{
    $markdown = str_replace(["\r\n", "\r"], "\n", preg_replace('/^\xEF\xBB\xBF/', '', $markdown) ?? $markdown);
    $lines = explode("\n", trim($markdown));
    $html = [];
    $paragraph = [];
    $list = '';
    $table = [];
    $flush_paragraph = static function () use (&$paragraph, &$html): void {
        if (!$paragraph) return;
        $html[] = '<p>' . gvspace_blog_markdown_inline(implode(' ', array_map('trim', $paragraph))) . '</p>';
        $paragraph = [];
    };
    $flush_list = static function () use (&$list, &$html): void {
        if ($list === '') return;
        $html[] = '</' . $list . '>';
        $list = '';
    };
    $flush_table = static function () use (&$table, &$html): void {
        if (!$table) return;
        $rows = array_map(static fn (string $row): array => array_map('trim', explode('|', trim($row, " \t|"))), $table);
        $header = array_shift($rows) ?: [];
        if ($rows && count($rows[0]) === count($header) && count(array_filter($rows[0], static fn (string $cell): bool => !preg_match('/^:?-{3,}:?$/', $cell))) === 0) array_shift($rows);
        $markup = '<div class="article-table-wrap"><table><thead><tr>';
        foreach ($header as $cell) $markup .= '<th>' . gvspace_blog_markdown_inline($cell) . '</th>';
        $markup .= '</tr></thead><tbody>';
        foreach ($rows as $row) {
            $markup .= '<tr>';
            foreach ($row as $cell) $markup .= '<td>' . gvspace_blog_markdown_inline($cell) . '</td>';
            $markup .= '</tr>';
        }
        $html[] = $markup . '</tbody></table></div>';
        $table = [];
    };

    foreach ($lines as $line) {
        $trimmed = trim($line);
        if (str_contains($trimmed, '|') && preg_match('/^\|?.+\|.+\|?$/u', $trimmed)) {
            $flush_paragraph(); $flush_list(); $table[] = $trimmed; continue;
        }
        $flush_table();
        if ($trimmed === '') { $flush_paragraph(); $flush_list(); continue; }
        if (preg_match('/^#{1,6}\s+(.+)$/u', $trimmed, $match)) {
            $flush_paragraph(); $flush_list();
            $level = min(6, strspn($trimmed, '#'));
            $html[] = '<h' . $level . '>' . gvspace_blog_markdown_inline($match[1]) . '</h' . $level . '>';
            continue;
        }
        if (preg_match('/^[-*+]\s+(.+)$/u', $trimmed, $match) || preg_match('/^\d+[.)]\s+(.+)$/u', $trimmed, $match)) {
            $flush_paragraph();
            $wanted = preg_match('/^\d/u', $trimmed) ? 'ol' : 'ul';
            if ($list !== $wanted) { $flush_list(); $html[] = '<' . $wanted . '>'; $list = $wanted; }
            $html[] = '<li>' . gvspace_blog_markdown_inline($match[1]) . '</li>';
            continue;
        }
        if (preg_match('/^>\s*(.+)$/u', $trimmed, $match)) {
            $flush_paragraph(); $flush_list(); $html[] = '<blockquote><p>' . gvspace_blog_markdown_inline($match[1]) . '</p></blockquote>'; continue;
        }
        $paragraph[] = $trimmed;
    }
    $flush_paragraph(); $flush_list(); $flush_table();
    return wp_kses_post(implode("\n", $html));
}

function gvspace_blog_parse_markdown(string $markdown, string $filename = ''): array
{
    $markdown = str_replace(["\r\n", "\r"], "\n", preg_replace('/^\xEF\xBB\xBF/', '', $markdown) ?? $markdown);
    $meta_title = '';
    $meta_description = '';
    $og_title = '';
    $og_description = '';
    $category = '';
    if (preg_match('/^\s*\*\*Meta title:\*\*\s*(.+)$/miu', $markdown, $match)) $meta_title = trim($match[1]);
    if (preg_match('/^\s*\*\*Meta description:\*\*\s*(.+)$/miu', $markdown, $match)) $meta_description = trim($match[1]);
    if (preg_match('/^\s*(?:\*\*)?Open Graph Title(?:\*\*)?\s*:\s*(.+)$/miu', $markdown, $match)) $og_title = trim($match[1]);
    if (preg_match('/^\s*(?:\*\*)?Open Graph Description(?:\*\*)?\s*:\s*(.+)$/miu', $markdown, $match)) $og_description = trim($match[1]);
    if (preg_match('/^\s*\*\*(?:Category|Категорія):\*\*\s*(.+)$/miu', $markdown, $match)) $category = trim($match[1]);
    $body = preg_replace('/^\s*\*\*(?:Meta (?:title|description)|Category|Категорія):\*\*\s*.*(?:\n|$)/miu', '', $markdown) ?? $markdown;
    $body = preg_replace('/^\s*(?:\*\*)?Open Graph (?:Title|Description)(?:\*\*)?\s*:.+(?:\n|$)/miu', '', $body) ?? $body;
    $title = preg_match('/^#\s+(.+)$/mu', $body, $match) ? trim($match[1]) : '';
    $labeled_title = gvspace_blog_labeled_open_graph($title);
    if ($labeled_title !== '') {
        if ($og_title === '') $og_title = $labeled_title;
        $title = $labeled_title;
    }
    $body = preg_replace('/^#\s+.*(?:\n|$)/mu', '', $body, 1) ?? $body;
    $plain = trim(wp_strip_all_tags(gvspace_blog_markdown_to_html($body)));
    $excerpt = preg_split('/\n\s*\n/u', trim($body))[0] ?? '';
    $excerpt = trim(wp_strip_all_tags(gvspace_blog_markdown_to_html($excerpt)));
    $labeled_excerpt = gvspace_blog_labeled_open_graph($excerpt);
    if ($labeled_excerpt !== '') {
        if ($og_description === '') $og_description = $labeled_excerpt;
        $excerpt = $labeled_excerpt;
    }
    $parts = function_exists('gvspace_l3_import_filename_parts') ? gvspace_l3_import_filename_parts($filename) : ['slug' => sanitize_title(pathinfo($filename, PATHINFO_FILENAME)), 'locale' => ''];
    return [
        'filename' => $filename,
        'slug' => (string) ($parts['slug'] ?? ''),
        'locale' => (string) ($parts['locale'] ?? ''),
        'title' => $title,
        'excerpt' => $excerpt,
        'content' => gvspace_blog_strip_open_graph_html(gvspace_blog_markdown_to_html($body)),
        'markdown' => trim($markdown),
        'meta_title' => $meta_title ?: $title,
        'meta_description' => $meta_description ?: wp_trim_words($plain, 24, ''),
        'og_title' => $og_title,
        'og_description' => $og_description,
        'category' => $category,
    ];
}

function gvspace_blog_labeled_open_graph(string $value): string
{
    $value = trim(html_entity_decode(wp_strip_all_tags($value), ENT_QUOTES, 'UTF-8'));
    if (!preg_match('/^(?:\*\*)?Open Graph (?:Title|Description)(?:\*\*)?\s*:\s*(.+)$/iu', $value, $match)) return '';
    return gvspace_blog_clean_open_graph_value($match[1]);
}

function gvspace_blog_clean_open_graph_value(string $value): string
{
    $value = trim(html_entity_decode(wp_strip_all_tags($value), ENT_QUOTES, 'UTF-8'));
    $value = preg_replace('/^\*\*\s*/u', '', $value) ?? $value;
    $value = preg_replace('/\s*\*\*$/u', '', $value) ?? $value;
    return trim(preg_replace('/\s+/u', ' ', $value) ?? $value);
}

function gvspace_blog_store_open_graph(int $post_id, string $locale, string $title, string $description): void
{
    foreach (['og_title' => $title, 'og_description' => $description] as $key => $incoming) {
        $incoming = trim($incoming);
        if ($incoming === '') continue;
        $meta_key = '_gvspace_seo_' . $key . '_' . $locale;
        $current = trim((string) get_post_meta($post_id, $meta_key, true));
        if ($current !== '' && gvspace_blog_labeled_open_graph($current) === '') continue;
        update_post_meta($post_id, $meta_key, $key === 'og_title' ? sanitize_text_field($incoming) : sanitize_textarea_field($incoming));
    }
}

function gvspace_blog_strip_open_graph_html(string $html): string
{
    $stripped = preg_replace(
        '/<(p|h[1-6])\b[^>]*>\s*(?:<(?:strong|b|em)[^>]*>\s*)?(?:\*\*)?Open Graph (?:Title|Description)(?:\*\*)?\s*:[\s\S]*?<\/\1>/iu',
        '',
        $html
    );
    return is_string($stripped) ? $stripped : $html;
}

function gvspace_blog_backfill_open_graph(): void
{
    if (get_option('gvspace_blog_og_backfill_version') === '2') return;
    $posts = get_posts([
        'post_type' => 'post',
        'post_status' => 'any',
        'numberposts' => -1,
        'fields' => 'ids',
    ]);
    foreach ($posts as $post_id) {
        $post_id = (int) $post_id;
        foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
            $markdown = (string) get_post_meta($post_id, '_gvspace_blog_markdown_' . $locale, true);
            $content = (string) get_post_meta($post_id, '_gvspace_blog_content_' . $locale, true);
            $title = (string) get_post_meta($post_id, '_gvspace_blog_title_' . $locale, true);
            $excerpt = (string) get_post_meta($post_id, '_gvspace_blog_excerpt_' . $locale, true);
            $parsed = $markdown !== '' ? gvspace_blog_parse_markdown($markdown) : ['og_title' => '', 'og_description' => '', 'title' => '', 'excerpt' => '', 'content' => ''];
            $og_title = (string) ($parsed['og_title'] ?? '');
            $og_description = (string) ($parsed['og_description'] ?? '');
            if ($og_title === '') $og_title = gvspace_blog_labeled_open_graph($title);
            if ($og_description === '') $og_description = gvspace_blog_labeled_open_graph($excerpt);
            if ($og_title === '' || $og_description === '') {
                if (preg_match_all('/<(p|h[1-6])\b[^>]*>([\s\S]*?)<\/\1>/iu', $content, $blocks, PREG_SET_ORDER)) {
                    foreach ($blocks as $block) {
                        $plain = trim(preg_replace('/\s+/u', ' ', wp_strip_all_tags($block[2])) ?? '');
                        if ($og_title === '' && preg_match('/^Open Graph Title\s*:/iu', $plain)) $og_title = gvspace_blog_labeled_open_graph($plain);
                        if ($og_description === '' && preg_match('/^Open Graph Description\s*:/iu', $plain)) $og_description = gvspace_blog_labeled_open_graph($plain);
                    }
                }
            }
            gvspace_blog_store_open_graph($post_id, $locale, $og_title, $og_description);
            foreach (['og_title', 'og_description'] as $og_key) {
                $og_meta_key = '_gvspace_seo_' . $og_key . '_' . $locale;
                $og_current = (string) get_post_meta($post_id, $og_meta_key, true);
                $og_clean = gvspace_blog_clean_open_graph_value($og_current);
                if ($og_clean !== '' && $og_clean !== trim($og_current)) {
                    update_post_meta($post_id, $og_meta_key, $og_key === 'og_title' ? sanitize_text_field($og_clean) : sanitize_textarea_field($og_clean));
                }
            }
            $clean_title = gvspace_blog_labeled_open_graph($title);
            if ($clean_title !== '') update_post_meta($post_id, '_gvspace_blog_title_' . $locale, sanitize_text_field($clean_title));
            $clean_excerpt = gvspace_blog_labeled_open_graph($excerpt);
            if ($clean_excerpt !== '') update_post_meta($post_id, '_gvspace_blog_excerpt_' . $locale, sanitize_textarea_field($clean_excerpt));
            foreach (['title', 'h1'] as $seo_key) {
                $seo_value = (string) get_post_meta($post_id, '_gvspace_seo_' . $seo_key . '_' . $locale, true);
                $clean_seo = gvspace_blog_labeled_open_graph($seo_value);
                if ($clean_seo !== '') update_post_meta($post_id, '_gvspace_seo_' . $seo_key . '_' . $locale, sanitize_text_field($clean_seo));
            }
            $seo_description = (string) get_post_meta($post_id, '_gvspace_seo_description_' . $locale, true);
            $clean_seo_description = gvspace_blog_labeled_open_graph($seo_description);
            if ($clean_seo_description !== '') update_post_meta($post_id, '_gvspace_seo_description_' . $locale, sanitize_textarea_field($clean_seo_description));
            if ($markdown !== '' && ($parsed['og_title'] !== '' || $parsed['og_description'] !== '')) {
                update_post_meta($post_id, '_gvspace_blog_markdown_' . $locale, (string) $parsed['markdown']);
                if ((string) $parsed['title'] !== '') update_post_meta($post_id, '_gvspace_blog_title_' . $locale, sanitize_text_field((string) $parsed['title']));
                if ((string) $parsed['excerpt'] !== '') update_post_meta($post_id, '_gvspace_blog_excerpt_' . $locale, sanitize_textarea_field((string) $parsed['excerpt']));
                update_post_meta($post_id, '_gvspace_blog_content_' . $locale, (string) $parsed['content']);
            } else {
                $clean_content = gvspace_blog_strip_open_graph_html($content);
                if ($clean_content !== $content) update_post_meta($post_id, '_gvspace_blog_content_' . $locale, wp_kses_post($clean_content));
            }
        }
        $uk_title = (string) get_post_meta($post_id, '_gvspace_blog_title_uk', true);
        $uk_excerpt = (string) get_post_meta($post_id, '_gvspace_blog_excerpt_uk', true);
        $uk_content = (string) get_post_meta($post_id, '_gvspace_blog_content_uk', true);
        if ($uk_title !== '' && (get_post_field('post_title', $post_id) !== $uk_title || get_post_field('post_excerpt', $post_id) !== $uk_excerpt || get_post_field('post_content', $post_id) !== $uk_content)) {
            wp_update_post([
                'ID' => $post_id,
                'post_title' => $uk_title,
                'post_excerpt' => $uk_excerpt,
                'post_content' => $uk_content,
            ]);
        }
        clean_post_cache($post_id);
    }
    update_option('gvspace_blog_og_backfill_version', '2', false);
}
add_action('init', 'gvspace_blog_backfill_open_graph', 30);

function gvspace_blog_resolve_category_id(string $value, bool $create = true): int
{
    $value = trim($value);
    if ($value === '') return 0;
    $slug = sanitize_title($value);
    $term = get_term_by('slug', $slug, 'category');
    if (!$term) $term = get_term_by('name', $value, 'category');
    if (!$term) {
        $needle = function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
        foreach (get_terms(['taxonomy' => 'category', 'hide_empty' => false]) as $candidate) {
            if (!$candidate instanceof WP_Term) continue;
            foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
                if ($locale === 'uk') continue;
                $translated = trim((string) get_term_meta($candidate->term_id, '_gvspace_name_' . $locale, true));
                $haystack = function_exists('mb_strtolower') ? mb_strtolower($translated, 'UTF-8') : strtolower($translated);
                if ($translated !== '' && $haystack === $needle) return (int) $candidate->term_id;
            }
        }
    }
    if ($term instanceof WP_Term) return (int) $term->term_id;
    if (!$create) return 0;
    $created = wp_insert_term($value, 'category', ['slug' => $slug]);
    return is_wp_error($created) ? 0 : (int) $created['term_id'];
}

add_action('admin_head-post.php', 'gvspace_hide_native_blog_fields');
add_action('admin_head-post-new.php', 'gvspace_hide_native_blog_fields');
function gvspace_hide_native_blog_fields(): void
{
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'post') return;
    echo '<style>#titlediv,#postdivrich,#postexcerpt{display:none!important}</style>';
}

add_action('admin_enqueue_scripts', function (string $hook_suffix): void {
    if (!in_array($hook_suffix, ['post.php', 'post-new.php'], true)) return;
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'post') return;
    wp_enqueue_media();
});

add_action('add_meta_boxes', function (): void {
    remove_meta_box('postimagediv', 'post', 'side');
    add_meta_box('gvspace-blog-content', 'Контент статті блогу', 'gvspace_render_blog_fields', 'post', 'normal', 'high');
});

function gvspace_render_blog_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_blog', 'gvspace_blog_nonce');
    echo '<p class="description"><strong>Одна стаття — один запис.</strong> Оберіть мову та заповніть її заголовок, короткий опис, зображення і повний текст. У Markdown маркетолог має вказувати категорію окремим рядком: <code>**Category:** it-development</code>. Рекомендовано використовувати slug наявної категорії. Категорія і автор є спільними для всіх мов. Зображення задається окремо для кожної мови.</p>';
    echo '<p><label for="gvspace_blog_category"><strong>Категорія статті</strong></label><br>';
    wp_dropdown_categories(['id' => 'gvspace_blog_category', 'name' => 'gvspace_blog_category', 'taxonomy' => 'category', 'hide_empty' => false, 'selected' => (int) (wp_get_post_categories($post->ID)[0] ?? 0), 'show_option_none' => 'Оберіть категорію', 'option_none_value' => 0]);
    echo '<br><span class="description">Можна обрати вручну. Якщо в Markdown є <code>**Category:**</code>, категорія з файла матиме пріоритет.</span></p>';
    gvspace_render_blog_author_select($post);
    echo '<p><label for="gvspace-blog-language"><strong>Редагувати мовну версію</strong></label> ';
    echo '<select id="gvspace-blog-language" data-gvspace-language-select="blog-language">';
    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        echo '<option value="' . esc_attr($locale) . '">' . esc_html($label) . '</option>';
    }
    echo '</select></p>';

    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        $title = (string) get_post_meta($post->ID, '_gvspace_blog_title_' . $locale, true);
        $excerpt = (string) get_post_meta($post->ID, '_gvspace_blog_excerpt_' . $locale, true);
        $content = (string) get_post_meta($post->ID, '_gvspace_blog_content_' . $locale, true);
        $markdown = (string) get_post_meta($post->ID, '_gvspace_blog_markdown_' . $locale, true);
        $seo_title = (string) get_post_meta($post->ID, '_gvspace_seo_title_' . $locale, true);
        $seo_description = (string) get_post_meta($post->ID, '_gvspace_seo_description_' . $locale, true);
        if ($locale === 'uk') {
            if ($title === '') $title = $post->post_title;
            if ($excerpt === '') $excerpt = $post->post_excerpt;
            if ($content === '') $content = $post->post_content;
        }
        echo '<div data-gvspace-language-panel="blog-language" data-locale="' . esc_attr($locale) . '"' . ($locale === 'uk' ? '' : ' hidden') . '>';
        echo '<hr><h3>' . esc_html($label) . '</h3>';
        gvspace_render_blog_image_field($post, $locale);
        echo '<p><label for="gvspace_blog_title_' . esc_attr($locale) . '"><strong>Заголовок статті</strong></label><br>';
        echo '<input type="text" id="gvspace_blog_title_' . esc_attr($locale) . '" name="gvspace_blog_title_' . esc_attr($locale) . '" value="' . esc_attr($title) . '" style="width:100%"></p>';
        echo '<p><label for="gvspace_blog_excerpt_' . esc_attr($locale) . '"><strong>Короткий опис для картки</strong></label><br>';
        echo '<textarea id="gvspace_blog_excerpt_' . esc_attr($locale) . '" name="gvspace_blog_excerpt_' . esc_attr($locale) . '" rows="3" style="width:100%">' . esc_textarea($excerpt) . '</textarea></p>';
        echo '<p><label for="gvspace_blog_seo_title_' . esc_attr($locale) . '"><strong>Meta title</strong></label><br>';
        echo '<input type="text" id="gvspace_blog_seo_title_' . esc_attr($locale) . '" name="gvspace_blog_seo_title_' . esc_attr($locale) . '" value="' . esc_attr($seo_title) . '" maxlength="70" style="width:100%"></p>';
        echo '<p><label for="gvspace_blog_seo_description_' . esc_attr($locale) . '"><strong>Meta description</strong></label><br>';
        echo '<textarea id="gvspace_blog_seo_description_' . esc_attr($locale) . '" name="gvspace_blog_seo_description_' . esc_attr($locale) . '" rows="3" maxlength="180" style="width:100%">' . esc_textarea($seo_description) . '</textarea></p>';
        echo '<p><label for="gvspace_blog_markdown_' . esc_attr($locale) . '"><strong>Markdown статті</strong></label><br>';
        echo '<span class="description">Вставте весь файл у форматі маркетолога: Meta title, Meta description, H1, H2/H3, списки, таблиці та посилання. HTML для сайту генерується автоматично.</span><br>';
        echo '<textarea class="large-text code" id="gvspace_blog_markdown_' . esc_attr($locale) . '" name="gvspace_blog_markdown_' . esc_attr($locale) . '" rows="24">' . esc_textarea($markdown) . '</textarea></p>';
        echo '<details><summary><strong>Згенерований HTML / сумісність зі старими статтями</strong></summary>';
        wp_editor($content, 'gvspace_blog_content_' . $locale, ['textarea_name' => 'gvspace_blog_content_' . $locale, 'textarea_rows' => 12, 'media_buttons' => true, 'teeny' => false]);
        echo '</details>';
        echo '</div>';
    }
    gvspace_render_blog_image_script();
    gvspace_render_language_switcher_script();
}

function gvspace_blog_locale_image_id(int $post_id, string $locale): int
{
    $image_id = (int) get_post_meta($post_id, '_gvspace_blog_image_' . $locale, true);
    if ($image_id > 0 && wp_attachment_is_image($image_id)) return $image_id;
    if ($locale === 'uk') {
        $thumbnail_id = (int) get_post_thumbnail_id($post_id);
        if ($thumbnail_id > 0 && wp_attachment_is_image($thumbnail_id)) return $thumbnail_id;
    }
    return 0;
}

function gvspace_blog_image_url(int $post_id, string $locale, string $size = 'full'): string
{
    $image_id = gvspace_blog_locale_image_id($post_id, $locale);
    if ($image_id <= 0 && $locale !== 'uk') $image_id = gvspace_blog_locale_image_id($post_id, 'uk');
    return $image_id > 0 ? (string) wp_get_attachment_image_url($image_id, $size) : '';
}

function gvspace_render_blog_image_field(WP_Post $post, string $locale): void
{
    $image_id = gvspace_blog_locale_image_id($post->ID, $locale);
    $preview = $image_id ? wp_get_attachment_image($image_id, 'medium_large', false, [
        'style' => 'width:100%;max-width:640px;height:auto;display:block;border-radius:8px;',
        'data-gvspace-blog-image-preview-img' => '1',
    ]) : '';
    $fallback = $locale === 'uk'
        ? ''
        : ' Якщо для цієї мови зображення не задано, на сайті використовується українське.';
    ?>
    <div class="gvspace-blog-image-field" data-gvspace-blog-image style="margin:18px 0;padding:18px;border:1px solid #dcdcde;background:#f6f7f7;">
        <h4 style="margin-top:0;">Зображення статті</h4>
        <p class="description">Для цієї мовної версії. Використовується у великій картці, каталозі, на сторінці статті та для Open Graph, якщо окремий SEO URL не заповнено. Рекомендовано WebP або JPEG, 1600 × 900 px (16:9).<?php echo esc_html($fallback); ?></p>
        <input type="hidden" name="gvspace_blog_image_<?php echo esc_attr($locale); ?>" value="<?php echo esc_attr((string) $image_id); ?>" data-gvspace-blog-image-input>
        <div data-gvspace-blog-image-preview style="margin:14px 0;"><?php echo $preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
        <button type="button" class="button button-secondary" data-gvspace-blog-image-select><?php echo $image_id ? 'Замінити зображення' : 'Завантажити зображення'; ?></button>
        <button type="button" class="button-link-delete" data-gvspace-blog-image-remove style="margin-left:12px;<?php echo $image_id ? '' : 'display:none;'; ?>">Видалити зображення</button>
    </div>
    <?php
}

function gvspace_render_blog_image_script(): void
{
    static $rendered = false;
    if ($rendered) return;
    $rendered = true;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.wp || !window.wp.media) return;
        document.querySelectorAll('[data-gvspace-blog-image]').forEach(function (root) {
            const selectButton = root.querySelector('[data-gvspace-blog-image-select]');
            const removeButton = root.querySelector('[data-gvspace-blog-image-remove]');
            const input = root.querySelector('[data-gvspace-blog-image-input]');
            const preview = root.querySelector('[data-gvspace-blog-image-preview]');
            if (!selectButton || !removeButton || !input || !preview) return;

            let frame;
            selectButton.addEventListener('click', function () {
                if (frame) {
                    frame.open();
                    return;
                }
                frame = window.wp.media({
                    title: 'Оберіть зображення статті',
                    button: { text: 'Використати зображення' },
                    library: { type: 'image' },
                    multiple: false
                });
                frame.on('select', function () {
                    const attachment = frame.state().get('selection').first().toJSON();
                    const sizes = attachment.sizes || {};
                    const imageUrl = (sizes.medium_large && sizes.medium_large.url)
                        || (sizes.medium && sizes.medium.url)
                        || attachment.url;
                    input.value = attachment.id;
                    preview.innerHTML = '';
                    const image = document.createElement('img');
                    image.src = imageUrl;
                    image.alt = attachment.alt || attachment.title || '';
                    image.style.cssText = 'width:100%;max-width:640px;height:auto;display:block;border-radius:8px;';
                    preview.appendChild(image);
                    selectButton.textContent = 'Замінити зображення';
                    removeButton.style.display = '';
                });
                frame.open();
            });

            removeButton.addEventListener('click', function () {
                input.value = '';
                preview.innerHTML = '';
                selectButton.textContent = 'Завантажити зображення';
                removeButton.style.display = 'none';
            });
        });
    });
    </script>
    <?php
}

add_action('save_post_post', function (int $post_id): void {
    static $saving = false;
    if ($saving || !isset($_POST['gvspace_blog_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_blog_nonce'])), 'gvspace_save_blog') || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) return;

    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        $title_field = 'gvspace_blog_title_' . $locale;
        $excerpt_field = 'gvspace_blog_excerpt_' . $locale;
        $content_field = 'gvspace_blog_content_' . $locale;
        $markdown_field = 'gvspace_blog_markdown_' . $locale;
        $seo_title_field = 'gvspace_blog_seo_title_' . $locale;
        $seo_description_field = 'gvspace_blog_seo_description_' . $locale;
        $markdown = isset($_POST[$markdown_field]) ? trim((string) wp_unslash($_POST[$markdown_field])) : '';
        if ($markdown !== '') {
            $parsed = gvspace_blog_parse_markdown($markdown);
            $_POST[$title_field] = $parsed['title'] ?: ($_POST[$title_field] ?? '');
            $_POST[$excerpt_field] = $parsed['excerpt'] ?: ($_POST[$excerpt_field] ?? '');
            $_POST[$content_field] = $parsed['content'];
            if (empty($_POST[$seo_title_field])) $_POST[$seo_title_field] = $parsed['meta_title'];
            if (empty($_POST[$seo_description_field])) $_POST[$seo_description_field] = $parsed['meta_description'];
            gvspace_blog_store_open_graph($post_id, $locale, (string) $parsed['og_title'], (string) $parsed['og_description']);
            if ($parsed['category'] !== '') $_POST['gvspace_blog_category_from_markdown'] = $parsed['category'];
            update_post_meta($post_id, '_gvspace_blog_markdown_' . $locale, $markdown);
        } elseif (isset($_POST[$markdown_field])) {
            delete_post_meta($post_id, '_gvspace_blog_markdown_' . $locale);
        }
        if (isset($_POST[$title_field])) update_post_meta($post_id, '_gvspace_blog_title_' . $locale, sanitize_text_field(wp_unslash($_POST[$title_field])));
        if (isset($_POST[$excerpt_field])) update_post_meta($post_id, '_gvspace_blog_excerpt_' . $locale, sanitize_textarea_field(wp_unslash($_POST[$excerpt_field])));
        if (isset($_POST[$content_field])) update_post_meta($post_id, '_gvspace_blog_content_' . $locale, wp_kses_post(wp_unslash($_POST[$content_field])));
        if (isset($_POST[$seo_title_field])) update_post_meta($post_id, '_gvspace_seo_title_' . $locale, sanitize_text_field(wp_unslash($_POST[$seo_title_field])));
        if (isset($_POST[$seo_description_field])) update_post_meta($post_id, '_gvspace_seo_description_' . $locale, sanitize_textarea_field(wp_unslash($_POST[$seo_description_field])));
    }

    $category_id = isset($_POST['gvspace_blog_category_from_markdown'])
        ? gvspace_blog_resolve_category_id(sanitize_text_field(wp_unslash($_POST['gvspace_blog_category_from_markdown'])))
        : absint($_POST['gvspace_blog_category'] ?? 0);
    if ($category_id > 0) wp_set_post_categories($post_id, [$category_id], false);

    if (isset($_POST['gvspace_blog_author_id'])) {
        $author_id = absint(wp_unslash($_POST['gvspace_blog_author_id']));
        if ($author_id > 0 && get_post_type($author_id) === 'gv_blog_author') {
            update_post_meta($post_id, '_gvspace_blog_author_id', $author_id);
        } else {
            delete_post_meta($post_id, '_gvspace_blog_author_id');
        }
    }

    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        $image_field = 'gvspace_blog_image_' . $locale;
        if (!isset($_POST[$image_field])) continue;
        $image_id = absint(wp_unslash($_POST[$image_field]));
        if ($image_id > 0 && get_post_type($image_id) === 'attachment' && wp_attachment_is_image($image_id)) {
            update_post_meta($post_id, '_gvspace_blog_image_' . $locale, $image_id);
        } else {
            delete_post_meta($post_id, '_gvspace_blog_image_' . $locale);
        }
    }
    if (isset($_POST['gvspace_blog_image_uk'])) {
        $uk_image_id = (int) get_post_meta($post_id, '_gvspace_blog_image_uk', true);
        if ($uk_image_id > 0 && wp_attachment_is_image($uk_image_id)) set_post_thumbnail($post_id, $uk_image_id);
        else delete_post_thumbnail($post_id);
    }

    update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    $uk_title = (string) get_post_meta($post_id, '_gvspace_blog_title_uk', true);
    $uk_excerpt = (string) get_post_meta($post_id, '_gvspace_blog_excerpt_uk', true);
    $uk_content = (string) get_post_meta($post_id, '_gvspace_blog_content_uk', true);
    $group = (string) get_post_meta($post_id, '_gvspace_translation_group', true);
    if ($group === '') update_post_meta($post_id, '_gvspace_translation_group', sanitize_title((string) get_post_field('post_name', $post_id) ?: $uk_title));
    if ($uk_title !== '' && (get_post_field('post_title', $post_id) !== $uk_title || get_post_field('post_excerpt', $post_id) !== $uk_excerpt || get_post_field('post_content', $post_id) !== $uk_content)) {
        $current_slug = (string) get_post_field('post_name', $post_id);
        $saving = true;
        wp_update_post([
            'ID' => $post_id,
            'post_title' => $uk_title,
            'post_name' => $current_slug === '' || ctype_digit($current_slug) ? sanitize_title($uk_title) : $current_slug,
            'post_excerpt' => $uk_excerpt,
            'post_content' => $uk_content,
        ]);
        $saving = false;
    }
});

add_filter('admin_post_thumbnail_html', function (string $content, int $post_id): string {
    if (get_post_type($post_id) !== 'post') return $content;
    return $content . '<p class="description">Це українське зображення статті. Інші мови задаються в блоці «Контент статті блогу».</p>';
}, 10, 2);

function gvspace_render_blog_category_translations(?WP_Term $term, bool $table): void
{
    $group = 'blog-category-language';
    $locales = GVSPACE_CONTENT_LOCALES;
    unset($locales['uk']);
    $active = (string) array_key_first($locales);
    if ($table) {
        echo '<tr class="form-field"><th scope="row"><label for="gvspace-blog-category-language">Переклад назви</label></th><td>';
    } else {
        echo '<div class="form-field"><label for="gvspace-blog-category-language">Переклад назви</label>';
    }
    echo '<select id="gvspace-blog-category-language" data-gvspace-language-select="' . esc_attr($group) . '">';
    foreach ($locales as $locale => $label) {
        echo '<option value="' . esc_attr($locale) . '"' . selected($active, $locale, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select>';
    echo '<p class="description">Поле «Назва» — українська версія. Для інших мов заповніть переклад. Якщо переклад порожній, на сайті лишається українська назва.</p>';
    if ($table) echo '</td></tr>';
    else echo '</div>';

    foreach ($locales as $locale => $label) {
        $value = $term instanceof WP_Term ? (string) get_term_meta($term->term_id, '_gvspace_name_' . $locale, true) : '';
        $field_id = 'gvspace_category_name_' . $locale;
        $hidden = $locale === $active ? '' : ' hidden';
        if ($table) {
            echo '<tr class="form-field" data-gvspace-language-panel="' . esc_attr($group) . '" data-locale="' . esc_attr($locale) . '"' . $hidden . '>';
            echo '<th scope="row"><label for="' . esc_attr($field_id) . '">' . esc_html($label) . '</label></th><td>';
        } else {
            echo '<div class="form-field" data-gvspace-language-panel="' . esc_attr($group) . '" data-locale="' . esc_attr($locale) . '"' . $hidden . '>';
            echo '<label for="' . esc_attr($field_id) . '">' . esc_html($label) . '</label>';
        }
        echo '<input type="text" id="' . esc_attr($field_id) . '" name="' . esc_attr($field_id) . '" value="' . esc_attr($value) . '">';
        if ($table) echo '</td></tr>';
        else echo '</div>';
    }
    gvspace_render_language_switcher_script();
}

add_action('category_add_form_fields', static function (): void {
    gvspace_render_blog_category_translations(null, false);
});

add_action('category_edit_form_fields', static function (WP_Term $term): void {
    gvspace_render_blog_category_translations($term, true);
});

function gvspace_save_category_translation(int $term_id): void
{
    if (!current_user_can('manage_categories')) return;
    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        if ($locale === 'uk') continue;
        $key = 'gvspace_category_name_' . $locale;
        if (!isset($_POST[$key])) continue;
        update_term_meta($term_id, '_gvspace_name_' . $locale, sanitize_text_field(wp_unslash($_POST[$key])));
    }
}
add_action('created_category', 'gvspace_save_category_translation');
add_action('edited_category', 'gvspace_save_category_translation');

function gvspace_blog_import_transient_key(): string
{
    return 'gvspace_blog_import_' . get_current_user_id();
}

function gvspace_blog_import_preview(array $files, string $default_locale, int $category_id = 0): array
{
    $records = [];
    $errors = [];
    foreach ($files as $file) {
        $record = gvspace_blog_parse_markdown((string) $file['contents'], (string) $file['name']);
        $base = strtolower((string) pathinfo((string) $file['name'], PATHINFO_FILENAME));
        $filename_locale = function_exists('gvspace_l3_import_normalize_locale') ? gvspace_l3_import_normalize_locale($base) : '';
        if ($filename_locale === '' && preg_match('/^(.*?)[.-](ua|uk|en|pl|de-de|de-at|es|fr|it|nl|cs|sk|en-gb)$/i', $base, $match)) {
            $filename_locale = gvspace_l3_import_normalize_locale($match[2]);
            $record['slug'] = sanitize_title($match[1]);
        }
        $record['locale'] = $record['locale'] ?: ($filename_locale ?: $default_locale);
        if (!array_key_exists($record['locale'], GVSPACE_CONTENT_LOCALES)) $record['locale'] = $default_locale;
        if ($record['title'] === '' || trim(wp_strip_all_tags($record['content'])) === '') {
            $errors[] = $record['filename'] . ': не знайдено H1 або текст статті.';
            continue;
        }
        if ($record['slug'] === '' || ($filename_locale !== '' && in_array($base, ['ua', 'uk', 'en'], true))) $record['slug'] = '';
        $records[] = $record;
    }
    $shared_slug = '';
    if ($records && count(array_filter($records, static fn (array $record): bool => $record['slug'] !== '')) === 0) {
        $shared_slug = sanitize_title((string) $records[0]['title']);
    }
    foreach ($records as &$record) {
        if ($record['slug'] === '') $record['slug'] = $shared_slug ?: sanitize_title((string) $record['title']);
    }
    unset($record);
    $seen = [];
    foreach ($records as $record) {
        $key = $record['slug'] . "\0" . $record['locale'];
        if (isset($seen[$key])) $errors[] = $record['filename'] . ': ця мовна версія дублюється у пакеті.';
        $seen[$key] = true;
    }
    return ['records' => $records, 'errors' => $errors, 'category_id' => $category_id];
}

function gvspace_blog_import_apply(array $payload): int
{
    $updated = 0;
    $fallback_category_id = absint($payload['category_id'] ?? 0);
    foreach ((array) ($payload['records'] ?? []) as $record) {
        $slug = sanitize_title((string) $record['slug']);
        $locale = (string) $record['locale'];
        $existing = get_posts(['post_type' => 'post', 'post_status' => 'any', 'numberposts' => 1, 'meta_key' => '_gvspace_translation_group', 'meta_value' => $slug]);
        if (!$existing) $existing = get_posts(['post_type' => 'post', 'post_status' => 'any', 'numberposts' => 1, 'name' => $slug]);
        $post_id = $existing ? (int) $existing[0]->ID : 0;
        if (!$post_id) {
            $created = wp_insert_post(['post_type' => 'post', 'post_status' => 'publish', 'post_name' => $slug, 'post_title' => (string) $record['title']], true);
            if (is_wp_error($created)) continue;
            $post_id = (int) $created;
        }
        update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
        update_post_meta($post_id, '_gvspace_translation_group', $slug);
        update_post_meta($post_id, '_gvspace_translation_status', 'published');
        foreach (['title', 'excerpt', 'content', 'markdown'] as $field) update_post_meta($post_id, '_gvspace_blog_' . $field . '_' . $locale, (string) $record[$field]);
        update_post_meta($post_id, '_gvspace_seo_title_' . $locale, sanitize_text_field((string) $record['meta_title']));
        update_post_meta($post_id, '_gvspace_seo_h1_' . $locale, sanitize_text_field((string) $record['title']));
        update_post_meta($post_id, '_gvspace_seo_description_' . $locale, sanitize_textarea_field((string) $record['meta_description']));
        gvspace_blog_store_open_graph($post_id, $locale, (string) ($record['og_title'] ?? ''), (string) ($record['og_description'] ?? ''));
        $category_id = gvspace_blog_resolve_category_id((string) ($record['category'] ?? '')) ?: $fallback_category_id;
        if ($category_id > 0 && term_exists($category_id, 'category')) {
            wp_set_post_categories($post_id, [$category_id], false);
        }
        if ($locale === 'uk' || trim((string) get_post_field('post_title', $post_id)) === '') {
            wp_update_post(['ID' => $post_id, 'post_title' => (string) $record['title'], 'post_name' => $slug, 'post_excerpt' => (string) $record['excerpt'], 'post_content' => (string) $record['content'], 'post_status' => 'publish']);
        }
        clean_post_cache($post_id);
        $updated++;
    }
    return $updated;
}

add_action('admin_menu', static function (): void {
    add_submenu_page('edit.php', 'Масовий імпорт статей', 'Імпорт Markdown', 'edit_posts', 'gvspace-blog-import', 'gvspace_render_blog_import_page');
}, 9);

function gvspace_render_blog_import_page(): void
{
    if (!current_user_can('edit_posts')) wp_die('Недостатньо прав.');
    $notice = '';
    $preview = get_transient(gvspace_blog_import_transient_key());
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        check_admin_referer('gvspace_blog_import');
        $action = sanitize_key((string) ($_POST['gvspace_blog_import_action'] ?? ''));
        if ($action === 'preview') {
            $default_locale = gvspace_sanitize_content_locale((string) ($_POST['gvspace_blog_default_locale'] ?? 'uk'));
            if ($default_locale === 'legacy') $default_locale = 'uk';
            $category_id = absint($_POST['gvspace_blog_category'] ?? 0);
            $uploads = function_exists('gvspace_l3_import_collect_uploads') ? gvspace_l3_import_collect_uploads('gvspace_blog_files') : ['files' => [], 'errors' => ['Завантаження недоступне.']];
            $preview = gvspace_blog_import_preview((array) $uploads['files'], $default_locale, $category_id);
            $preview['errors'] = array_merge((array) ($uploads['errors'] ?? []), (array) $preview['errors']);
            set_transient(gvspace_blog_import_transient_key(), $preview, 30 * MINUTE_IN_SECONDS);
        } elseif ($action === 'apply' && is_array($preview) && empty($preview['errors'])) {
            $count = gvspace_blog_import_apply($preview);
            delete_transient(gvspace_blog_import_transient_key());
            $preview = false;
            $notice = 'Імпорт завершено: оновлено мовних версій — ' . $count . '.';
        } elseif ($action === 'cancel') {
            delete_transient(gvspace_blog_import_transient_key());
            $preview = false;
        }
    }
    ?>
    <div class="wrap"><h1>Масовий імпорт статей із Markdown</h1>
        <p>Один файл — одна мовна версія. Для пакетів використовуйте назви <code>article-slug.uk.md</code>, <code>article-slug.en.md</code>. Для однієї статті можна разом завантажити <code>ua.md</code> та <code>en.md</code> — вони будуть об’єднані. У кожному файлі рекомендовано вказати <code>**Category:** it-development</code>; категорія з Markdown має пріоритет над загальним селектом.</p>
        <?php if ($notice !== ''): ?><div class="notice notice-success"><p><?php echo esc_html($notice); ?></p></div><?php endif; ?>
        <?php if (is_array($preview)): ?>
            <?php foreach ((array) $preview['errors'] as $error): ?><div class="notice notice-error"><p><?php echo esc_html($error); ?></p></div><?php endforeach; ?>
            <p><strong>Категорія:</strong> <?php $preview_category = get_term(absint($preview['category_id'] ?? 0), 'category'); echo esc_html($preview_category instanceof WP_Term ? $preview_category->name : 'Не вибрана'); ?></p>
            <table class="widefat striped"><thead><tr><th>Файл</th><th>Slug</th><th>Мова</th><th>Категорія з MD</th><th>H1</th><th>Meta title</th></tr></thead><tbody>
            <?php foreach ((array) $preview['records'] as $record): ?><tr><td><?php echo esc_html($record['filename']); ?></td><td><code><?php echo esc_html($record['slug']); ?></code></td><td><code><?php echo esc_html($record['locale']); ?></code></td><td><code><?php echo esc_html($record['category'] ?: '—'); ?></code></td><td><?php echo esc_html($record['title']); ?></td><td><?php echo esc_html($record['meta_title']); ?></td></tr><?php endforeach; ?>
            </tbody></table>
            <form method="post" style="margin-top:16px"><?php wp_nonce_field('gvspace_blog_import'); ?>
                <button class="button button-primary" name="gvspace_blog_import_action" value="apply" <?php disabled(!empty($preview['errors'])); ?>>Імпортувати</button>
                <button class="button" name="gvspace_blog_import_action" value="cancel">Скасувати</button>
            </form>
        <?php else: ?>
            <form method="post" enctype="multipart/form-data"><?php wp_nonce_field('gvspace_blog_import'); ?>
                <table class="form-table"><tr><th><label for="gvspace_blog_default_locale">Мова за замовчуванням</label></th><td><select id="gvspace_blog_default_locale" name="gvspace_blog_default_locale">
                    <?php foreach (GVSPACE_CONTENT_LOCALES as $code => $label): ?><option value="<?php echo esc_attr($code); ?>"><?php echo esc_html($label); ?></option><?php endforeach; ?>
                </select><p class="description">Застосовується лише якщо мову не вказано в назві файла.</p></td></tr>
                <tr><th><label for="gvspace_blog_category">Категорія</label></th><td><?php wp_dropdown_categories(['id' => 'gvspace_blog_category', 'name' => 'gvspace_blog_category', 'taxonomy' => 'category', 'hide_empty' => false, 'show_option_none' => 'Оберіть категорію', 'option_none_value' => 0]); ?><p class="description">Категорія буде застосована до всіх статей у поточному пакеті.</p></td></tr>
                <tr><th><label for="gvspace_blog_files">Markdown-файли</label></th><td><input id="gvspace_blog_files" name="gvspace_blog_files[]" type="file" accept=".md,.zip,text/markdown,application/zip" multiple required></td></tr></table>
                <p class="submit"><button class="button button-primary" name="gvspace_blog_import_action" value="preview">Перевірити пакет</button></p>
            </form>
        <?php endif; ?>
    </div><?php
}

add_action('graphql_register_types', function (): void {
    if (!function_exists('register_graphql_field')) return;
    register_graphql_object_type('GvspaceBlogContent', [
        'description' => 'Resolved content for one language of a centralized blog post.',
        'fields' => [
            'title' => ['type' => 'String'],
            'excerpt' => ['type' => 'String'],
            'content' => ['type' => 'String'],
            'image' => ['type' => 'String'],
            'isPublished' => ['type' => 'Boolean'],
        ],
    ]);
    register_graphql_field('Post', 'gvspaceBlog', [
        'type' => 'GvspaceBlogContent',
        'args' => ['locale' => ['type' => 'String', 'defaultValue' => 'uk']],
        'resolve' => static function ($source, array $args): array {
            $post_id = (int) ($source->databaseId ?? $source->ID ?? 0);
            $locale = gvspace_sanitize_content_locale((string) ($args['locale'] ?? 'uk'));
            if ($locale === 'legacy') $locale = 'uk';
            $title = trim((string) get_post_meta($post_id, '_gvspace_blog_title_' . $locale, true));
            $excerpt = trim((string) get_post_meta($post_id, '_gvspace_blog_excerpt_' . $locale, true));
            $content = (string) get_post_meta($post_id, '_gvspace_blog_content_' . $locale, true);
            if ($locale === 'uk') {
                if ($title === '') $title = trim((string) get_the_title($post_id));
                if ($excerpt === '') $excerpt = trim((string) get_post_field('post_excerpt', $post_id));
                if ($content === '') $content = (string) get_post_field('post_content', $post_id);
            }
            return [
                'title' => $title,
                'excerpt' => $excerpt,
                'content' => $content,
                'image' => gvspace_blog_image_url($post_id, $locale),
                'isPublished' => $title !== '' && trim(wp_strip_all_tags($content)) !== '',
            ];
        },
    ]);
    register_graphql_field('Category', 'gvspaceName', [
        'type' => 'String',
        'description' => 'Localized blog category label. Ukrainian uses the category name.',
        'args' => ['locale' => ['type' => 'String', 'defaultValue' => 'uk']],
        'resolve' => static function ($term, array $args): string {
            $term_id = (int) ($term->term_id ?? $term->databaseId ?? 0);
            $locale = gvspace_sanitize_content_locale((string) ($args['locale'] ?? 'uk'));
            if ($locale === 'legacy' || $locale === 'uk') return '';
            $name = trim((string) get_term_meta($term_id, '_gvspace_name_' . $locale, true));
            if ($name === '' && $locale !== 'en') $name = trim((string) get_term_meta($term_id, '_gvspace_name_en', true));
            return $name;
        },
    ]);
    register_graphql_field('Category', 'gvspaceNameEn', [
        'type' => 'String',
        'description' => 'English category label for the GVSPACE blog.',
        'resolve' => static function ($term): string {
            $term_id = (int) ($term->term_id ?? $term->databaseId ?? 0);
            return (string) get_term_meta((int) $term_id, '_gvspace_name_en', true);
        },
    ]);
});

/** Merge legacy categories that have the same visible name but different slugs. */
function gvspace_merge_duplicate_blog_categories(): void
{
    if (get_option('gvspace_blog_category_dedup_version') === '2') return;

    $terms = get_terms(['taxonomy' => 'category', 'hide_empty' => false]);
    if (is_wp_error($terms)) return;

    $preferred_slugs = [
        'стратегія' => 'strategy',
        'маркетинг' => 'marketing',
        'it-розробка' => 'it-development',
        'кейси' => 'cases',
        'аналітика' => 'analytics',
        'контент & продакшн' => 'content-production',
    ];
    $groups = [];
    foreach ($terms as $term) {
        $key = function_exists('mb_strtolower')
            ? mb_strtolower(trim($term->name), 'UTF-8')
            : strtolower(trim($term->name));
        $groups[$key][] = $term;
    }

    foreach ($groups as $name => $duplicates) {
        if (count($duplicates) < 2) continue;
        usort($duplicates, static function (WP_Term $left, WP_Term $right) use ($preferred_slugs, $name): int {
            $preferred = $preferred_slugs[$name] ?? '';
            if ($left->slug === $preferred) return -1;
            if ($right->slug === $preferred) return 1;
            return $left->term_id <=> $right->term_id;
        });
        $primary = array_shift($duplicates);

        foreach ($duplicates as $duplicate) {
            $primary_english = (string) get_term_meta($primary->term_id, '_gvspace_name_en', true);
            $duplicate_english = (string) get_term_meta($duplicate->term_id, '_gvspace_name_en', true);
            if ($primary_english === '' && $duplicate_english !== '') {
                update_term_meta($primary->term_id, '_gvspace_name_en', $duplicate_english);
            }

            $object_ids = get_objects_in_term($duplicate->term_id, 'category');
            if (!is_wp_error($object_ids)) {
                foreach ($object_ids as $object_id) {
                    wp_set_post_terms((int) $object_id, [(int) $primary->term_id], 'category', true);
                }
            }
            wp_delete_term($duplicate->term_id, 'category');
        }
    }

    update_option('gvspace_blog_category_dedup_version', '2', false);
}
add_action('admin_init', 'gvspace_merge_duplicate_blog_categories');

/**
 * Run the approved blog replacement once per persistent WordPress database.
 * The completed version prevents later deploys from overwriting editorial work.
 */
function gvspace_apply_blog_design_migration(): void
{
    if (get_option('gvspace_blog_design_migration_version') === '1') return;

    $lock = (int) get_option('gvspace_blog_design_migration_lock', 0);
    if ($lock > time() - 600) return;
    delete_option('gvspace_blog_design_migration_lock');
    if (!add_option('gvspace_blog_design_migration_lock', time(), '', false)) return;

    $result = gvspace_replace_blog_with_design_content();
    if ($result['created'] === 7) {
        gvspace_merge_duplicate_blog_categories();
        update_option('gvspace_blog_design_migration_version', '1', false);
    } else {
        error_log(sprintf('GVSPACE blog migration incomplete: created %d of 7 posts.', $result['created']));
    }
    delete_option('gvspace_blog_design_migration_lock');
}
add_action('init', 'gvspace_apply_blog_design_migration', 40);

function gvspace_replace_blog_with_design_content(): array
{
    $removed = 0;
    foreach (get_posts(['post_type' => 'post', 'post_status' => ['publish', 'draft', 'pending', 'private'], 'numberposts' => -1]) as $post) {
        if (wp_trash_post($post->ID)) $removed++;
    }
    $categories = [
        'strategy' => ['Стратегія', 'Strategy'], 'marketing' => ['Маркетинг', 'Marketing'],
        'it-development' => ['IT-розробка', 'IT development'], 'cases' => ['Кейси', 'Cases'],
        'analytics' => ['Аналітика', 'Analytics'],
    ];
    $term_ids = [];
    foreach ($categories as $slug => [$uk, $en]) {
        $existing = get_term_by('slug', $slug, 'category');
        $term = $existing instanceof WP_Term ? $existing->term_id : wp_insert_term($uk, 'category', ['slug' => $slug]);
        if (is_wp_error($term)) continue;
        $id = (int) (is_array($term) ? $term['term_id'] : $term);
        wp_update_term($id, 'category', ['name' => $uk, 'slug' => $slug]);
        update_term_meta($id, '_gvspace_name_en', $en);
        $term_ids[$slug] = $id;
    }
    $articles = [
        ['marketing-lottery', 'strategy', 'Чому ваш маркетинг виглядає як лотерея — і як це виправити за 30 днів', 'Why your marketing feels like a lottery — and how to fix it in 30 days'],
        ['cac-vs-ltv', 'marketing', 'CAC vs LTV: як власник бізнесу має читати цифри рекламних кабінетів', 'CAC vs LTV: how business owners should read advertising metrics'],
        ['cpl-case', 'cases', 'Як ми знизили CPL на 30% без збільшення бюджету: розбір кейсу', 'How we reduced CPL by 30% without increasing the budget'],
        ['technical-debt', 'it-development', 'Технічний борг: що це таке і як він зупиняє масштабування бізнесу', 'Technical debt: what it is and how it stops business growth'],
        ['ga4-business-metrics', 'analytics', 'GA4 для власника бізнесу: 5 метрик, які реально мають значення', 'GA4 for business owners: 5 metrics that actually matter'],
        ['north-star-metric', 'strategy', 'North Star Metric: як обрати одну метрику, яка об’єднає команду', 'North Star Metric: choosing one metric that aligns the team'],
        ['nextjs-vs-react', 'it-development', 'Next.js або React: як обрати правильний фреймворк для продукту', 'Next.js or React: choosing the right framework for your product'],
    ];
    $created = 0;
    foreach ($articles as $index => [$group, $category, $uk_title, $en_title]) {
        $uk_excerpt = 'Практичний матеріал про системне зростання бізнесу від команди GVSPACE.';
        $en_excerpt = 'A practical guide to systematic business growth from the GVSPACE team.';
        $uk_content = '<p>' . esc_html($uk_excerpt) . '</p>';
        $en_content = '<p>' . esc_html($en_excerpt) . '</p>';
        $id = wp_insert_post(['post_type' => 'post', 'post_status' => 'publish', 'post_title' => $uk_title, 'post_name' => $group, 'post_excerpt' => $uk_excerpt, 'post_content' => $uk_content, 'post_date' => wp_date('Y-m-d H:i:s', strtotime('-' . $index . ' days'))], true);
        if (is_wp_error($id)) continue;
        if (isset($term_ids[$category])) wp_set_post_categories($id, [$term_ids[$category]], false);
        update_post_meta($id, '_gvspace_content_locale', 'legacy');
        update_post_meta($id, '_gvspace_translation_group', $group);
        update_post_meta($id, '_gvspace_translation_status', 'published');
        update_post_meta($id, '_gvspace_blog_design_seed', '1');
        foreach (['uk' => [$uk_title, $uk_excerpt, $uk_content], 'en' => [$en_title, $en_excerpt, $en_content]] as $locale => [$title, $excerpt, $content]) {
            update_post_meta($id, '_gvspace_blog_title_' . $locale, $title);
            update_post_meta($id, '_gvspace_blog_excerpt_' . $locale, $excerpt);
            update_post_meta($id, '_gvspace_blog_content_' . $locale, $content);
            update_post_meta($id, '_gvspace_seo_title_' . $locale, $title);
            update_post_meta($id, '_gvspace_seo_description_' . $locale, $excerpt);
        }
        $created++;
    }
    return compact('removed', 'created');
}

/** Merge previously deployed UK/EN post pairs into one centralized editor record. */
function gvspace_centralize_blog_records(): void
{
    if (get_option('gvspace_blog_centralized_migration_version') === '1') return;
    if (!add_option('gvspace_blog_centralized_migration_lock', time(), '', false)) return;

    $posts = get_posts(['post_type' => 'post', 'post_status' => ['publish', 'draft', 'pending', 'private'], 'numberposts' => -1]);
    $groups = [];
    foreach ($posts as $post) {
        $group = (string) get_post_meta($post->ID, '_gvspace_translation_group', true);
        if ($group === '') $group = preg_replace('/-(uk|en)$/', '', $post->post_name) ?: $post->post_name;
        $groups[$group][] = $post;
    }

    foreach ($groups as $group => $group_posts) {
        $by_locale = [];
        $primary = null;
        foreach ($group_posts as $post) {
            $locale = (string) get_post_meta($post->ID, '_gvspace_content_locale', true) ?: 'legacy';
            if ($locale === 'legacy') $primary = $post;
            if (in_array($locale, ['uk', 'en'], true)) $by_locale[$locale] = $post;
        }
        $primary = $primary ?: ($by_locale['uk'] ?? $group_posts[0]);
        $term_ids = [];
        foreach ($group_posts as $post) {
            $terms = wp_get_post_categories($post->ID);
            if (!is_wp_error($terms)) $term_ids = array_merge($term_ids, $terms);
        }

        foreach (['uk', 'en'] as $locale) {
            $source = $by_locale[$locale] ?? $primary;
            $title = trim((string) get_post_meta($source->ID, '_gvspace_blog_title_' . $locale, true));
            $excerpt = (string) get_post_meta($source->ID, '_gvspace_blog_excerpt_' . $locale, true);
            $content = (string) get_post_meta($source->ID, '_gvspace_blog_content_' . $locale, true);
            $source_locale = (string) get_post_meta($source->ID, '_gvspace_content_locale', true) ?: 'legacy';
            if ($title === '' && ($source_locale === $locale || ($locale === 'uk' && $source_locale === 'legacy'))) $title = $source->post_title;
            if ($excerpt === '' && ($source_locale === $locale || ($locale === 'uk' && $source_locale === 'legacy'))) $excerpt = $source->post_excerpt;
            if ($content === '' && ($source_locale === $locale || ($locale === 'uk' && $source_locale === 'legacy'))) $content = $source->post_content;
            update_post_meta($primary->ID, '_gvspace_blog_title_' . $locale, $title);
            update_post_meta($primary->ID, '_gvspace_blog_excerpt_' . $locale, $excerpt);
            update_post_meta($primary->ID, '_gvspace_blog_content_' . $locale, $content);
            foreach (array_keys(GVSPACE_SEO_FIELDS) as $seo_field) {
                $localized = (string) get_post_meta($source->ID, '_gvspace_seo_' . $seo_field . '_' . $locale, true);
                if ($localized === '') $localized = (string) get_post_meta($source->ID, '_gvspace_seo_' . $seo_field, true);
                if ($localized !== '') update_post_meta($primary->ID, '_gvspace_seo_' . $seo_field . '_' . $locale, $localized);
            }
        }

        $uk_title = (string) get_post_meta($primary->ID, '_gvspace_blog_title_uk', true);
        $uk_excerpt = (string) get_post_meta($primary->ID, '_gvspace_blog_excerpt_uk', true);
        $uk_content = (string) get_post_meta($primary->ID, '_gvspace_blog_content_uk', true);
        wp_update_post(['ID' => $primary->ID, 'post_title' => $uk_title ?: $primary->post_title, 'post_name' => $group, 'post_excerpt' => $uk_excerpt, 'post_content' => $uk_content]);
        update_post_meta($primary->ID, '_gvspace_content_locale', 'legacy');
        update_post_meta($primary->ID, '_gvspace_translation_group', $group);
        update_post_meta($primary->ID, '_gvspace_translation_status', 'published');
        if ($term_ids) wp_set_post_categories($primary->ID, array_values(array_unique(array_map('absint', $term_ids))), false);
        foreach ($group_posts as $post) {
            if ($post->ID !== $primary->ID) wp_trash_post($post->ID);
        }
    }

    update_option('gvspace_blog_centralized_migration_version', '1', false);
    delete_option('gvspace_blog_centralized_migration_lock');
}
add_action('init', 'gvspace_centralize_blog_records', 45);

/**
 * Add one complete bilingual reference article for the editorial team.
 *
 * This migration runs once per database. Future deployments therefore keep
 * every change made by the marketing team in the WordPress editor.
 */
function gvspace_seed_blog_reference_article(): void
{
    if (get_option('gvspace_blog_reference_article_version') === '4') return;
    if (!add_option('gvspace_blog_reference_article_lock', time(), '', false)) return;

    $posts = get_posts([
        'post_type' => 'post',
        'post_status' => ['publish', 'draft', 'pending', 'private'],
        'numberposts' => 1,
        'meta_key' => '_gvspace_translation_group',
        'meta_value' => 'marketing-lottery',
    ]);
    $post = $posts[0] ?? get_page_by_path('marketing-lottery', OBJECT, 'post');

    if (!$post instanceof WP_Post) {
        delete_option('gvspace_blog_reference_article_lock');
        return;
    }

    $title_uk = 'Чому ваш маркетинг виглядає як лотерея — і як це виправити за 30 днів';
    $excerpt_uk = 'Розбираємо три системні помилки, які перетворюють рекламний бюджет на непередбачувані результати.';
    $content_uk = <<<'HTML'
<p>Більшість власників бізнесу, з якими ми спілкуємося на Clarity Session, описують свій маркетинг однаково: «щось працює, але не розуміємо що». Це не проблема бюджету. Це проблема системи.</p>
<h2>Три ознаки того, що ваш маркетинг — це лотерея</h2>
<p>Перша й найочевидніша ознака — ви не можете передбачити, скільки лідів отримаєте наступного місяця. Навіть приблизно. Якщо відповідь «не знаємо, дивимося по ситуації» — це і є лотерея.</p>
<ol>
<li><strong>Бюджет витрачається, але незрозуміло, які канали приносять гроші.</strong></li>
<li><strong>Кожен місяць — нова гіпотеза замість оптимізації системи, що вже працює.</strong></li>
<li><strong>Звіти від агенції є, але рішення на їх основі не ухвалюються.</strong></li>
</ol>
<blockquote><p>Ріст можливий лише тоді, коли є простір, ясність і система.</p><cite>GVSPACE, Brand Strategy 2026</cite></blockquote>
<h2>Що таке керований маркетинг і як він виглядає на практиці</h2>
<p>Керований маркетинг — це не про те, щоб запускати більше реклами. Це про те, щоб розуміти причинно-наслідковий зв’язок між кожною дією та результатом.</p>
<aside><small>КЛЮЧОВИЙ ІНСАЙТ</small><p>Наскрізна аналітика — єдиний інструмент, який перетворює маркетинг з витрати на інвестицію. Без неї ви не знаєте, що саме повернуло гроші.</p></aside>
<h3>Крок 1: Clarity before growth</h3>
<p>Перш ніж масштабуватися, потрібно зрозуміти, що саме працює. Це звучить очевидно, але більшість агенцій пропускають цей крок і одразу переходять до запуску реклами.</p>
<h3>Крок 2: Побудова системи вимірювання</h3>
<p>GA4 + GTM + CRM-інтеграція — це мінімальний стек, який дозволяє бачити повний шлях клієнта. Без цього будь-яка оптимізація — це стрільба в темряві.</p>
<h2>Що можна зробити за 30 днів</h2>
<p>30 днів — це реальний горизонт, щоб перейти від хаосу до перших вимірюваних результатів. Але тільки якщо почати з правильного кроку.</p>
<ul>
<li><b>Тиждень 1:</b> Clarity Session + аудит поточного стану аналітики.</li>
<li><b>Тиждень 2:</b> Налаштування наскрізного трекінгу та дашборда.</li>
<li><b>Тиждень 3:</b> Перша оптимізація на основі даних, а не відчуттів.</li>
<li><b>Тиждень 4:</b> Перший звіт з реальними цифрами по кожному каналу.</li>
</ul>
<div class="article-conclusion"><small>ВИСНОВОК</small><p>Маркетинг стає системою не тоді, коли ви витрачаєте більше бюджету, а тоді, коли кожна дія має вимірюваний наслідок. Саме з цього починається простір для росту.</p></div>
HTML;

    $title_en = 'Why your marketing feels like a lottery — and how to fix it in 30 days';
    $excerpt_en = 'Three systemic mistakes that turn an advertising budget into unpredictable results — and a practical way to fix them.';
    $content_en = <<<'HTML'
<p>Most business owners we meet during a Clarity Session describe their marketing in the same way: “something works, but we do not know what.” This is not a budget problem. It is a system problem.</p>
<h2>Three signs your marketing is a lottery</h2>
<p>The first and clearest sign is that you cannot predict how many leads you will receive next month. If the answer is “we will see,” the business is relying on chance instead of a repeatable system.</p>
<ol>
<li><strong>The budget is being spent, but nobody can show which channels generate revenue.</strong></li>
<li><strong>Every month starts with a new hypothesis instead of improving what already works.</strong></li>
<li><strong>Reports exist, but they do not lead to clear business decisions.</strong></li>
</ol>
<blockquote><p>Growth becomes possible when there is space, clarity and a system.</p><cite>GVSPACE, Brand Strategy 2026</cite></blockquote>
<h2>What managed marketing looks like in practice</h2>
<p>Managed marketing is not about launching more advertising. It is about understanding the cause-and-effect relationship between every action and the result it creates.</p>
<aside><small>KEY INSIGHT</small><p>End-to-end analytics is what turns marketing from an expense into an investment. Without it, you cannot know what actually returned the money.</p></aside>
<h3>Step 1: Clarity before growth</h3>
<p>Before scaling, identify what already works. It sounds obvious, yet many teams skip this step and move directly to launching more campaigns.</p>
<h3>Step 2: Build a measurement system</h3>
<p>GA4 + GTM + CRM integration is the minimum stack required to see the complete customer journey. Without it, every optimisation is a shot in the dark.</p>
<h2>What can be done in 30 days</h2>
<p>Thirty days is a realistic horizon for moving from chaos to the first measurable results — when the work begins with the right priorities.</p>
<ul>
<li><b>Week 1:</b> Clarity Session and an audit of the current analytics setup.</li>
<li><b>Week 2:</b> End-to-end tracking and dashboard configuration.</li>
<li><b>Week 3:</b> The first optimisation based on data rather than assumptions.</li>
<li><b>Week 4:</b> A report with real numbers for every acquisition channel.</li>
</ul>
<div class="article-conclusion"><small>CONCLUSION</small><p>Marketing becomes a system not when you spend more, but when every action has a measurable consequence. That is where sustainable growth begins.</p></div>
HTML;

    foreach ([
        'uk' => [$title_uk, $excerpt_uk, $content_uk],
        'en' => [$title_en, $excerpt_en, $content_en],
    ] as $locale => [$title, $excerpt, $content]) {
        $seo_title = $locale === 'uk'
            ? 'Чому маркетинг схожий на лотерею та як це виправити'
            : 'Why marketing feels like a lottery and how to fix it';
        $seo_description = $locale === 'uk'
            ? 'Три ознаки хаотичного маркетингу та план на 30 днів: як налаштувати аналітику, вимірювання і кероване зростання бізнесу.'
            : 'Three signs your marketing relies on chance and a 30-day plan to set up analytics, measurement and a manageable growth system.';
        update_post_meta($post->ID, '_gvspace_blog_title_' . $locale, $title);
        update_post_meta($post->ID, '_gvspace_blog_excerpt_' . $locale, $excerpt);
        update_post_meta($post->ID, '_gvspace_blog_content_' . $locale, $content);
        update_post_meta($post->ID, '_gvspace_seo_title_' . $locale, $seo_title);
        update_post_meta($post->ID, '_gvspace_seo_description_' . $locale, $seo_description);
        update_post_meta($post->ID, '_gvspace_seo_h1_' . $locale, $title);
        update_post_meta($post->ID, '_gvspace_seo_og_title_' . $locale, $title);
        update_post_meta($post->ID, '_gvspace_seo_og_description_' . $locale, $excerpt);
        delete_post_meta($post->ID, '_gvspace_seo_og_image_' . $locale);
    }

    wp_update_post([
        'ID' => $post->ID,
        'post_title' => $title_uk,
        'post_name' => 'marketing-lottery',
        'post_excerpt' => $excerpt_uk,
        'post_content' => $content_uk,
        'post_status' => 'publish',
    ]);
    wp_set_post_tags($post->ID, ['Strategy', 'Analytics', 'Performance', 'GA4'], false);
    update_post_meta($post->ID, '_gvspace_blog_reference_example', '1');
    update_option('gvspace_blog_reference_article_version', '4', false);
    delete_option('gvspace_blog_reference_article_lock');
}
add_action('init', 'gvspace_seed_blog_reference_article', 50);

const GVSPACE_BLOG_AUTHOR_SHARED_FIELDS = [
    'linkedin' => 'LinkedIn (посилання або нік, наприклад /vasyl-horaichuk)',
    'instagram' => 'Instagram (посилання або нік, наприклад @vasyl.horaichuk)',
    'experience' => 'Досвід (наприклад, 8+)',
    'projects' => 'Проєкти (наприклад, 50+)',
];

const GVSPACE_BLOG_AUTHOR_LOCALIZED_FIELDS = [
    'name' => ['label' => 'Ім’я', 'type' => 'text'],
    'role' => ['label' => 'Посада', 'type' => 'text'],
    'headline' => ['label' => 'Заголовок блоку «Про автора»', 'type' => 'text'],
    'quote' => ['label' => 'Цитата', 'type' => 'textarea'],
    'bio' => ['label' => 'Текст про автора (абзац з нового порожнього рядка)', 'type' => 'textarea'],
    'card_bio' => ['label' => 'Короткий опис у картці автора в статті', 'type' => 'textarea'],
];

add_action('init', function (): void {
    register_post_type('gv_blog_author', [
        'labels' => [
            'name' => 'Автори блогу',
            'singular_name' => 'Автор блогу',
            'menu_name' => 'Автор блогу',
            'add_new' => 'Додати автора',
            'add_new_item' => 'Додати автора',
            'edit_item' => 'Редагувати автора',
            'new_item' => 'Новий автор',
            'view_item' => 'Переглянути автора',
            'all_items' => 'Усі автори',
            'search_items' => 'Шукати авторів',
            'not_found' => 'Авторів не знайдено',
            'not_found_in_trash' => 'У кошику авторів немає',
        ],
        'public' => true,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'show_ui' => true,
        'show_in_menu' => 'edit.php',
        'show_in_rest' => true,
        'rewrite' => false,
        'supports' => ['title'],
        'capability_type' => 'post',
        'map_meta_cap' => true,
    ]);

    register_post_meta('post', '_gvspace_blog_author_id', [
        'type' => 'integer',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'absint',
        'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
    ]);
    register_post_meta('gv_blog_author', '_gvspace_blog_author_photo', [
        'type' => 'integer',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'absint',
        'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
    ]);
    foreach (array_keys(GVSPACE_BLOG_AUTHOR_SHARED_FIELDS) as $field) {
        register_post_meta('gv_blog_author', '_gvspace_blog_author_' . $field, [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
    }
    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        foreach (array_keys(GVSPACE_BLOG_AUTHOR_LOCALIZED_FIELDS) as $field) {
            register_post_meta('gv_blog_author', '_gvspace_blog_author_' . $field . '_' . $locale, [
                'type' => 'string',
                'single' => true,
                'show_in_rest' => true,
                'sanitize_callback' => 'sanitize_textarea_field',
                'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
            ]);
        }
    }
}, 30);

add_filter('enter_title_here', function (string $text, WP_Post $post): string {
    return $post->post_type === 'gv_blog_author' ? 'Ім’я автора' : $text;
}, 10, 2);

add_action('admin_enqueue_scripts', function (string $hook_suffix): void {
    if (!in_array($hook_suffix, ['post.php', 'post-new.php'], true)) return;
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'gv_blog_author') return;
    wp_enqueue_media();
});

add_action('add_meta_boxes', function (): void {
    add_meta_box('gvspace-blog-author', 'Профіль автора', 'gvspace_render_blog_author_fields', 'gv_blog_author', 'normal', 'high');
});

function gvspace_blog_author_posts(): array
{
    return get_posts([
        'post_type' => 'gv_blog_author',
        'post_status' => ['publish', 'draft', 'pending', 'private'],
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ]);
}

function gvspace_render_blog_author_select(WP_Post $post): void
{
    $selected = (int) get_post_meta($post->ID, '_gvspace_blog_author_id', true);
    $authors = array_values(array_filter(
        gvspace_blog_author_posts(),
        static fn (WP_Post $author): bool => $author->post_status === 'publish' || $author->ID === $selected
    ));
    $manage_url = admin_url('edit.php?post_type=gv_blog_author');
    echo '<p><label for="gvspace_blog_author_id"><strong>Автор блогу</strong></label><br>';
    echo '<select id="gvspace_blog_author_id" name="gvspace_blog_author_id">';
    echo '<option value="0">Оберіть автора блогу</option>';
    foreach ($authors as $author) {
        $label = $author->post_title !== '' ? $author->post_title : 'Автор #' . $author->ID;
        if ($author->post_status !== 'publish') $label .= ' (не опубліковано)';
        echo '<option value="' . esc_attr((string) $author->ID) . '"' . selected($selected, $author->ID, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select><br><span class="description">Список береться з розділу <a href="' . esc_url($manage_url) . '">Блог → Автор блогу</a>. Обраний автор з’являється в картках блогу, у статті та на своїй сторінці.</span></p>';
}

function gvspace_render_blog_author_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_blog_author', 'gvspace_blog_author_nonce');
    $photo_id = (int) get_post_meta($post->ID, '_gvspace_blog_author_photo', true);
    $photo = $photo_id ? (string) wp_get_attachment_image_url($photo_id, 'medium') : '';
    echo '<p class="description">Ці поля збирають сторінку автора: ім’я і посада зверху, фото, блок «Про автора» з цитатою та текстом, лічильники досвіду і проєктів, соцмережі. Slug під назвою стає адресою <code>/blog/author/slug</code>.</p>';
    echo '<div data-gvspace-author-photo style="margin:12px 0 18px">';
    echo '<p style="margin-bottom:8px"><strong>Фото автора</strong></p>';
    echo '<input type="hidden" name="gvspace_blog_author_photo" value="' . esc_attr((string) $photo_id) . '">';
    echo '<div data-gvspace-author-photo-preview style="margin:0 0 8px' . ($photo === '' ? ';display:none' : '') . '">';
    echo '<img src="' . esc_url($photo) . '" alt="" style="display:block;width:160px;height:200px;object-fit:cover;border:1px solid #c3c4c7;background:#f0f0f1">';
    echo '</div><p style="margin:0"><button type="button" class="button" data-gvspace-author-photo-select>' . ($photo_id ? 'Замінити фото' : 'Обрати фото') . '</button> ';
    echo '<button type="button" class="button-link-delete" data-gvspace-author-photo-remove' . ($photo_id ? '' : ' hidden') . '>Видалити</button></p></div>';

    foreach (GVSPACE_BLOG_AUTHOR_SHARED_FIELDS as $key => $label) {
        $value = (string) get_post_meta($post->ID, '_gvspace_blog_author_' . $key, true);
        $width = in_array($key, ['experience', 'projects'], true) ? '160px' : '100%';
        echo '<p><label for="gvspace_blog_author_' . esc_attr($key) . '"><strong>' . esc_html($label) . '</strong></label><br>';
        echo '<input type="text" id="gvspace_blog_author_' . esc_attr($key) . '" name="gvspace_blog_author_' . esc_attr($key) . '" value="' . esc_attr($value) . '" style="width:' . esc_attr($width) . '"></p>';
    }

    echo '<p><label for="gvspace-blog-author-language"><strong>Редагувати мовну версію</strong></label> ';
    echo '<select id="gvspace-blog-author-language" data-gvspace-language-select="blog-author-language">';
    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        echo '<option value="' . esc_attr($locale) . '">' . esc_html($label) . '</option>';
    }
    echo '</select></p>';

    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        echo '<div data-gvspace-language-panel="blog-author-language" data-locale="' . esc_attr($locale) . '"' . ($locale === 'uk' ? '' : ' hidden') . '>';
        echo '<hr><h3>' . esc_html($label) . '</h3>';
        foreach (GVSPACE_BLOG_AUTHOR_LOCALIZED_FIELDS as $key => $config) {
            $field_name = 'gvspace_blog_author_' . $key . '_' . $locale;
            $value = (string) get_post_meta($post->ID, '_gvspace_blog_author_' . $key . '_' . $locale, true);
            if ($key === 'name' && $locale === 'uk' && $value === '') $value = $post->post_title;
            echo '<p><label for="' . esc_attr($field_name) . '"><strong>' . esc_html($config['label']) . '</strong></label><br>';
            if ($config['type'] === 'textarea') {
                echo '<textarea id="' . esc_attr($field_name) . '" name="' . esc_attr($field_name) . '" rows="4" style="width:100%">' . esc_textarea($value) . '</textarea>';
            } else {
                echo '<input type="text" id="' . esc_attr($field_name) . '" name="' . esc_attr($field_name) . '" value="' . esc_attr($value) . '" style="width:100%">';
            }
            echo '</p>';
        }
        echo '</div>';
    }
    gvspace_render_language_switcher_script();
    gvspace_render_blog_author_photo_script();
}

function gvspace_render_blog_author_photo_script(): void
{
    static $rendered = false;
    if ($rendered) return;
    $rendered = true;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var root = document.querySelector('[data-gvspace-author-photo]');
        if (!root || !window.wp || !wp.media) return;
        var input = root.querySelector('input[name="gvspace_blog_author_photo"]');
        var previewWrap = root.querySelector('[data-gvspace-author-photo-preview]');
        var preview = previewWrap ? previewWrap.querySelector('img') : null;
        var selectButton = root.querySelector('[data-gvspace-author-photo-select]');
        var removeButton = root.querySelector('[data-gvspace-author-photo-remove]');
        if (!input || !previewWrap || !preview || !selectButton || !removeButton) return;
        var setPhoto = function (id, url) {
            input.value = id ? String(id) : '';
            preview.src = url || '';
            previewWrap.style.display = url ? '' : 'none';
            selectButton.textContent = url ? 'Замінити фото' : 'Обрати фото';
            if (url) removeButton.removeAttribute('hidden');
            else removeButton.setAttribute('hidden', 'hidden');
        };
        selectButton.addEventListener('click', function (event) {
            event.preventDefault();
            var frame = wp.media({ title: 'Фото автора', button: { text: 'Обрати фото' }, library: { type: 'image' }, multiple: false });
            frame.on('select', function () {
                var attachment = frame.state().get('selection').first();
                if (!attachment) return;
                var data = attachment.toJSON();
                var url = (data.sizes && data.sizes.medium && data.sizes.medium.url) || data.url;
                setPhoto(data.id, url);
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

add_action('save_post_gv_blog_author', function (int $post_id): void {
    static $saving_title = false;
    if ($saving_title) return;
    if (
        !isset($_POST['gvspace_blog_author_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_blog_author_nonce'])), 'gvspace_save_blog_author')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) return;

    $photo_id = absint(wp_unslash($_POST['gvspace_blog_author_photo'] ?? 0));
    if ($photo_id > 0 && get_post_type($photo_id) === 'attachment' && wp_attachment_is_image($photo_id)) {
        update_post_meta($post_id, '_gvspace_blog_author_photo', $photo_id);
    } else {
        delete_post_meta($post_id, '_gvspace_blog_author_photo');
    }
    foreach (array_keys(GVSPACE_BLOG_AUTHOR_SHARED_FIELDS) as $field) {
        $field_name = 'gvspace_blog_author_' . $field;
        if (!isset($_POST[$field_name])) continue;
        update_post_meta($post_id, '_gvspace_blog_author_' . $field, sanitize_text_field(wp_unslash($_POST[$field_name])));
    }
    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        foreach (array_keys(GVSPACE_BLOG_AUTHOR_LOCALIZED_FIELDS) as $field) {
            $field_name = 'gvspace_blog_author_' . $field . '_' . $locale;
            if (!isset($_POST[$field_name])) continue;
            update_post_meta($post_id, '_gvspace_blog_author_' . $field . '_' . $locale, sanitize_textarea_field(wp_unslash($_POST[$field_name])));
        }
    }
    $uk_name = trim((string) get_post_meta($post_id, '_gvspace_blog_author_name_uk', true));
    if ($uk_name !== '' && get_post_field('post_title', $post_id) !== $uk_name) {
        $saving_title = true;
        wp_update_post(['ID' => $post_id, 'post_title' => $uk_name]);
        $saving_title = false;
    }
    $current_slug = rawurldecode((string) get_post_field('post_name', $post_id));
    $latin_slug = gvspace_latin_slug($current_slug !== '' ? $current_slug : $uk_name);
    if ($latin_slug !== '' && $latin_slug !== $current_slug) {
        $saving_title = true;
        wp_update_post(['ID' => $post_id, 'post_name' => $latin_slug]);
        $saving_title = false;
    }
});

add_filter('manage_gv_blog_author_posts_columns', function (array $columns): array {
    $result = [];
    foreach ($columns as $key => $label) {
        $result[$key] = $label;
        if ($key === 'title') $result['gvspace_blog_author_role'] = 'Посада';
    }
    return $result;
});

add_action('manage_gv_blog_author_posts_custom_column', function (string $column, int $post_id): void {
    if ($column !== 'gvspace_blog_author_role') return;
    $role = trim((string) get_post_meta($post_id, '_gvspace_blog_author_role_uk', true));
    echo esc_html($role !== '' ? $role : '—');
}, 10, 2);

add_filter('manage_post_posts_columns', function (array $columns): array {
    $result = [];
    foreach ($columns as $key => $label) {
        if ($key === 'author') {
            $result['gvspace_blog_author'] = 'Автор блогу';
            continue;
        }
        $result[$key] = $label;
    }
    if (!isset($result['gvspace_blog_author'])) $result['gvspace_blog_author'] = 'Автор блогу';
    return $result;
});

add_action('manage_post_posts_custom_column', function (string $column, int $post_id): void {
    if ($column !== 'gvspace_blog_author') return;
    $author_id = (int) get_post_meta($post_id, '_gvspace_blog_author_id', true);
    $author = $author_id ? get_post($author_id) : null;
    if ($author instanceof WP_Post && $author->post_type === 'gv_blog_author') {
        $edit = get_edit_post_link($author_id);
        $title = $author->post_title !== '' ? $author->post_title : 'Автор #' . $author_id;
        echo $edit ? '<a href="' . esc_url($edit) . '">' . esc_html($title) . '</a>' : esc_html($title);
        return;
    }
    echo '—';
}, 10, 2);

function gvspace_blog_author_localized(int $post_id, string $field, string $locale): string
{
    $value = trim((string) get_post_meta($post_id, '_gvspace_blog_author_' . $field . '_' . $locale, true));
    if ($value === '' && $locale !== 'uk') {
        $value = trim((string) get_post_meta($post_id, '_gvspace_blog_author_' . $field . '_uk', true));
    }
    return $value;
}

function gvspace_latin_slug(string $value): string
{
    $value = rawurldecode($value);
    $value = function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
    $letters = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'h', 'ґ' => 'g', 'д' => 'd', 'е' => 'e', 'є' => 'ie',
        'ж' => 'zh', 'з' => 'z', 'и' => 'y', 'і' => 'i', 'ї' => 'i', 'й' => 'i', 'к' => 'k', 'л' => 'l',
        'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
        'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ь' => '',
        'ю' => 'iu', 'я' => 'ia', "'" => '', '’' => '', 'ʼ' => '',
    ];
    $word_start = ['є' => 'ye', 'ї' => 'yi', 'й' => 'y', 'ю' => 'yu', 'я' => 'ya'];
    $chars = preg_split('//u', $value, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $result = '';
    $at_word_start = true;
    foreach ($chars as $char) {
        if ($char === '-' || $char === ' ' || $char === '_') {
            $result .= '-';
            $at_word_start = true;
            continue;
        }
        if ($at_word_start && isset($word_start[$char])) {
            $result .= $word_start[$char];
        } elseif (isset($letters[$char])) {
            $result .= $letters[$char];
        } elseif (preg_match('/[a-z0-9]/', $char)) {
            $result .= $char;
        }
        $at_word_start = false;
    }
    return trim((string) preg_replace('/-+/', '-', $result), '-');
}

function gvspace_find_blog_author(string $slug): ?WP_Post
{
    $wanted = gvspace_latin_slug($slug);
    if ($wanted === '') return null;
    $names = array_unique(array_filter([
        sanitize_title($slug),
        rawurldecode($slug),
        rawurlencode(rawurldecode($slug)),
        strtolower(rawurlencode(rawurldecode($slug))),
    ]));
    foreach ($names as $name) {
        $posts = get_posts([
            'name' => $name,
            'post_type' => 'gv_blog_author',
            'post_status' => 'publish',
            'numberposts' => 1,
        ]);
        $author = $posts[0] ?? null;
        if ($author instanceof WP_Post) return $author;
    }
    $authors = get_posts([
        'post_type' => 'gv_blog_author',
        'post_status' => 'publish',
        'numberposts' => -1,
    ]);
    foreach ($authors as $author) {
        if (!$author instanceof WP_Post) continue;
        if (gvspace_latin_slug((string) $author->post_name) === $wanted) return $author;
    }
    return null;
}

function gvspace_blog_author_payload(int $post_id, string $locale): ?array
{
    $post = get_post($post_id);
    if (!$post instanceof WP_Post || $post->post_type !== 'gv_blog_author' || $post->post_status !== 'publish') return null;
    $locale = gvspace_sanitize_content_locale($locale);
    if ($locale === 'legacy' || !array_key_exists($locale, GVSPACE_CONTENT_LOCALES)) $locale = 'uk';
    $photo_id = (int) get_post_meta($post_id, '_gvspace_blog_author_photo', true);
    $name = gvspace_blog_author_localized($post_id, 'name', $locale);
    return [
        'slug' => gvspace_latin_slug((string) $post->post_name),
        'name' => $name !== '' ? $name : (string) get_the_title($post_id),
        'role' => gvspace_blog_author_localized($post_id, 'role', $locale),
        'headline' => gvspace_blog_author_localized($post_id, 'headline', $locale),
        'quote' => gvspace_blog_author_localized($post_id, 'quote', $locale),
        'bio' => gvspace_blog_author_localized($post_id, 'bio', $locale),
        'cardBio' => gvspace_blog_author_localized($post_id, 'card_bio', $locale),
        'experience' => trim((string) get_post_meta($post_id, '_gvspace_blog_author_experience', true)),
        'projects' => trim((string) get_post_meta($post_id, '_gvspace_blog_author_projects', true)),
        'photo' => $photo_id ? (string) wp_get_attachment_image_url($photo_id, 'large') : '',
        'linkedin' => trim((string) get_post_meta($post_id, '_gvspace_blog_author_linkedin', true)),
        'instagram' => trim((string) get_post_meta($post_id, '_gvspace_blog_author_instagram', true)),
    ];
}

add_action('graphql_register_types', function (): void {
    if (!function_exists('register_graphql_object_type') || !function_exists('register_graphql_field')) return;
    register_graphql_object_type('GvspaceBlogAuthor', [
        'description' => 'Public GVSPACE blog author profile.',
        'fields' => [
            'slug' => ['type' => 'String'],
            'name' => ['type' => 'String'],
            'role' => ['type' => 'String'],
            'headline' => ['type' => 'String'],
            'quote' => ['type' => 'String'],
            'bio' => ['type' => 'String'],
            'cardBio' => ['type' => 'String'],
            'experience' => ['type' => 'String'],
            'projects' => ['type' => 'String'],
            'photo' => ['type' => 'String'],
            'linkedin' => ['type' => 'String'],
            'instagram' => ['type' => 'String'],
        ],
    ]);
    register_graphql_field('RootQuery', 'gvspaceBlogAuthor', [
        'type' => 'GvspaceBlogAuthor',
        'args' => [
            'slug' => ['type' => ['non_null' => 'String']],
            'locale' => ['type' => 'String', 'defaultValue' => 'uk'],
        ],
        'resolve' => static function ($source, array $args): ?array {
            $author = gvspace_find_blog_author((string) ($args['slug'] ?? ''));
            return $author instanceof WP_Post
                ? gvspace_blog_author_payload($author->ID, (string) ($args['locale'] ?? 'uk'))
                : null;
        },
    ]);
    register_graphql_field('Post', 'gvspaceBlogAuthor', [
        'type' => 'GvspaceBlogAuthor',
        'args' => ['locale' => ['type' => 'String', 'defaultValue' => 'uk']],
        'resolve' => static function ($source, array $args): ?array {
            $post_id = (int) ($source->databaseId ?? $source->ID ?? 0);
            $author_id = (int) get_post_meta($post_id, '_gvspace_blog_author_id', true);
            return $author_id > 0
                ? gvspace_blog_author_payload($author_id, (string) ($args['locale'] ?? 'uk'))
                : null;
        },
    ]);
});
