<?php
/** Blog category localization and admin helpers. */
if (!defined('ABSPATH')) exit;

add_action('init', function (): void {
    register_term_meta('category', '_gvspace_name_en', [
        'type' => 'string', 'single' => true, 'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback' => static fn (): bool => current_user_can('manage_categories'),
    ]);
    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        foreach (['title', 'excerpt', 'content'] as $field) {
            register_post_meta('post', '_gvspace_blog_' . $field . '_' . $locale, [
                'type' => 'string', 'single' => true, 'show_in_rest' => true,
                'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
            ]);
        }
    }
}, 30);

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
    add_meta_box('gvspace-blog-content', 'Контент статті блогу', 'gvspace_render_blog_fields', 'post', 'normal', 'high');
});

function gvspace_render_blog_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_blog', 'gvspace_blog_nonce');
    echo '<p class="description"><strong>Одна стаття — один запис.</strong> Оберіть мову та заповніть її заголовок, короткий опис і повний текст. Перемикання мови не перезавантажує сторінку та не видаляє введений текст. Категорія, автор і головне зображення є спільними для всіх мов.</p>';
    gvspace_render_blog_image_field($post);
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
        if ($locale === 'uk') {
            if ($title === '') $title = $post->post_title;
            if ($excerpt === '') $excerpt = $post->post_excerpt;
            if ($content === '') $content = $post->post_content;
        }
        echo '<div data-gvspace-language-panel="blog-language" data-locale="' . esc_attr($locale) . '"' . ($locale === 'uk' ? '' : ' hidden') . '>';
        echo '<hr><h3>' . esc_html($label) . '</h3>';
        echo '<p><label for="gvspace_blog_title_' . esc_attr($locale) . '"><strong>Заголовок статті</strong></label><br>';
        echo '<input type="text" id="gvspace_blog_title_' . esc_attr($locale) . '" name="gvspace_blog_title_' . esc_attr($locale) . '" value="' . esc_attr($title) . '" style="width:100%"></p>';
        echo '<p><label for="gvspace_blog_excerpt_' . esc_attr($locale) . '"><strong>Короткий опис для картки</strong></label><br>';
        echo '<textarea id="gvspace_blog_excerpt_' . esc_attr($locale) . '" name="gvspace_blog_excerpt_' . esc_attr($locale) . '" rows="3" style="width:100%">' . esc_textarea($excerpt) . '</textarea></p>';
        echo '<p><strong>Текст статті</strong></p>';
        wp_editor($content, 'gvspace_blog_content_' . $locale, [
            'textarea_name' => 'gvspace_blog_content_' . $locale,
            'textarea_rows' => 18,
            'media_buttons' => true,
            'teeny' => false,
        ]);
        echo '</div>';
    }
    gvspace_render_language_switcher_script();
}

function gvspace_render_blog_image_field(WP_Post $post): void
{
    $thumbnail_id = (int) get_post_thumbnail_id($post->ID);
    $preview = $thumbnail_id ? wp_get_attachment_image($thumbnail_id, 'medium_large', false, [
        'style' => 'width:100%;max-width:640px;height:auto;display:block;border-radius:8px;',
    ]) : '';
    ?>
    <div class="gvspace-blog-image-field" style="margin:18px 0;padding:18px;border:1px solid #dcdcde;background:#f6f7f7;">
        <h3 style="margin-top:0;">Зображення статті</h3>
        <p class="description">Спільне для всіх мов. Використовується у великій картці, каталозі, на сторінці статті та для Open Graph, якщо окремий SEO URL не заповнено. Рекомендовано WebP або JPEG, 1600 × 900 px (16:9).</p>
        <input type="hidden" id="gvspace_blog_thumbnail_id" name="gvspace_blog_thumbnail_id" value="<?php echo esc_attr((string) $thumbnail_id); ?>">
        <div id="gvspace-blog-image-preview" style="margin:14px 0;"><?php echo $preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
        <button type="button" class="button button-secondary" id="gvspace-blog-image-select"><?php echo $thumbnail_id ? 'Замінити зображення' : 'Завантажити зображення'; ?></button>
        <button type="button" class="button-link-delete" id="gvspace-blog-image-remove" style="margin-left:12px;<?php echo $thumbnail_id ? '' : 'display:none;'; ?>">Видалити зображення</button>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectButton = document.getElementById('gvspace-blog-image-select');
        const removeButton = document.getElementById('gvspace-blog-image-remove');
        const input = document.getElementById('gvspace_blog_thumbnail_id');
        const preview = document.getElementById('gvspace-blog-image-preview');
        if (!selectButton || !removeButton || !input || !preview || !window.wp || !window.wp.media) return;

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
        if (isset($_POST[$title_field])) update_post_meta($post_id, '_gvspace_blog_title_' . $locale, sanitize_text_field(wp_unslash($_POST[$title_field])));
        if (isset($_POST[$excerpt_field])) update_post_meta($post_id, '_gvspace_blog_excerpt_' . $locale, sanitize_textarea_field(wp_unslash($_POST[$excerpt_field])));
        if (isset($_POST[$content_field])) update_post_meta($post_id, '_gvspace_blog_content_' . $locale, wp_kses_post(wp_unslash($_POST[$content_field])));
    }

    if (isset($_POST['gvspace_blog_thumbnail_id'])) {
        $thumbnail_id = absint(wp_unslash($_POST['gvspace_blog_thumbnail_id']));
        if ($thumbnail_id > 0 && get_post_type($thumbnail_id) === 'attachment' && wp_attachment_is_image($thumbnail_id)) {
            set_post_thumbnail($post_id, $thumbnail_id);
        } else {
            delete_post_thumbnail($post_id);
        }
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
    return $content . '<p class="description">Зображення використовується у великій картці, каталозі та Open Graph за відсутності окремого SEO-зображення. Рекомендований формат — WebP, пропорція 16:9.</p>';
}, 10, 2);

add_action('category_add_form_fields', function (): void { ?>
    <div class="form-field"><label for="gvspace_name_en">English name</label>
        <input name="gvspace_name_en" id="gvspace_name_en" type="text">
        <p>Shown in the English version of the blog.</p>
    </div>
<?php });

add_action('category_edit_form_fields', function (WP_Term $term): void { ?>
    <tr class="form-field"><th><label for="gvspace_name_en">English name</label></th><td>
        <input name="gvspace_name_en" id="gvspace_name_en" type="text" value="<?php echo esc_attr((string) get_term_meta($term->term_id, '_gvspace_name_en', true)); ?>">
        <p class="description">Shown in the English version of the blog.</p>
    </td></tr>
<?php });

function gvspace_save_category_translation(int $term_id): void
{
    if (!current_user_can('manage_categories') || !isset($_POST['gvspace_name_en'])) return;
    update_term_meta($term_id, '_gvspace_name_en', sanitize_text_field(wp_unslash($_POST['gvspace_name_en'])));
}
add_action('created_category', 'gvspace_save_category_translation');
add_action('edited_category', 'gvspace_save_category_translation');

add_action('graphql_register_types', function (): void {
    if (!function_exists('register_graphql_field')) return;
    register_graphql_object_type('GvspaceBlogContent', [
        'description' => 'Resolved content for one language of a centralized blog post.',
        'fields' => [
            'title' => ['type' => 'String'],
            'excerpt' => ['type' => 'String'],
            'content' => ['type' => 'String'],
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
                'isPublished' => $title !== '' && trim(wp_strip_all_tags($content)) !== '',
            ];
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
