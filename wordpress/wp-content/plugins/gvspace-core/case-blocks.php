<?php

if (!defined('ABSPATH')) {
    exit;
}

function gvspace_case_block_layouts(): array
{
    return [
        'text' => [
            'label' => 'Текстовий блок',
            'hint' => 'Заголовок і абзац. Медіа для цього варіанту немає.',
            'slots' => [],
            'group' => 'text',
        ],
        'card' => [
            'label' => 'Картка з текстом поруч',
            'hint' => 'Одна картка «до / після» і текст зліва або справа. Спочатку оберіть варіант, потім завантажте медіа.',
            'slots' => ['Медіа картки'],
            'group' => 'card',
        ],
        'top-two' => [
            'label' => 'Велике зверху і два знизу',
            'hint' => 'Одне широке фото або відео, під ним два менші.',
            'slots' => ['Верхнє широке', 'Нижнє ліве', 'Нижнє праве'],
            'group' => 'gallery',
        ],
        'stack-tall' => [
            'label' => 'Два зліва і високе справа',
            'hint' => 'Ліва колонка з двох кадрів і один високий кадр справа.',
            'slots' => ['Ліве верхнє', 'Ліве нижнє', 'Високе справа'],
            'group' => 'gallery',
        ],
        'mosaic' => [
            'label' => 'Три зверху, широке і високе знизу',
            'hint' => 'Три вертикальні кадри, потім широке і ще одне вертикальне справа.',
            'slots' => ['Верхнє 1', 'Верхнє 2', 'Верхнє 3', 'Широке знизу', 'Високе справа'],
            'group' => 'gallery',
        ],
        'duo' => [
            'label' => 'Дві картки в ряд',
            'hint' => 'Два рівних фото або відео поруч.',
            'slots' => ['Ліва картка', 'Права картка'],
            'group' => 'gallery',
        ],
        'metrics' => [
            'label' => 'Повтор метрик',
            'hint' => 'На сторінці ще раз показуються метрики першого екрана цієї мови.',
            'slots' => [],
            'group' => 'metrics',
        ],
    ];
}

function gvspace_case_block_layout(string $layout): array
{
    $layouts = gvspace_case_block_layouts();
    return $layouts[$layout] ?? $layouts['text'];
}

add_action('init', static function (): void {
    $auth = static fn (): bool => current_user_can('edit_posts');
    register_post_meta('gv_case', '_gvspace_case_logo_id', [
        'type' => 'integer',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'absint',
        'auth_callback' => $auth,
    ]);
    register_post_meta('gv_case', '_gvspace_case_cover_id', [
        'type' => 'integer',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'absint',
        'auth_callback' => $auth,
    ]);
    register_post_meta('gv_case', '_gvspace_case_blocks', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_textarea_field',
        'auth_callback' => $auth,
    ]);
    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        register_post_meta('gv_case', '_gvspace_case_block_copy_' . $locale, [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_textarea_field',
            'auth_callback' => $auth,
        ]);
    }
}, 11);

function gvspace_case_json_meta(int $post_id, string $key): array
{
    $decoded = json_decode((string) get_post_meta($post_id, $key, true), true);
    return is_array($decoded) ? $decoded : [];
}

function gvspace_case_update_json_meta(int $post_id, string $key, array $value): void
{
    update_post_meta($post_id, $key, wp_slash(wp_json_encode(array_values($value), JSON_UNESCAPED_UNICODE)));
}

function gvspace_case_read_blocks(int $post_id): array
{
    $blocks = [];
    foreach (gvspace_case_json_meta($post_id, '_gvspace_case_blocks') as $block) {
        if (!is_array($block)) {
            continue;
        }
        $layout = (string) ($block['layout'] ?? 'text');
        if (!isset(gvspace_case_block_layouts()[$layout])) {
            $layout = 'text';
        }
        $images = [];
        foreach ((array) ($block['images'] ?? []) as $image_id) {
            $images[] = absint($image_id);
        }
        $blocks[] = [
            'layout' => $layout,
            'side' => ($block['side'] ?? '') === 'right' ? 'right' : 'left',
            'tone' => ($block['tone'] ?? '') === 'light' ? 'light' : 'dark',
            'images' => $images,
        ];
    }
    return $blocks;
}

function gvspace_case_read_block_copy(int $post_id, string $locale): array
{
    $copies = [];
    foreach (gvspace_case_json_meta($post_id, '_gvspace_case_block_copy_' . $locale) as $copy) {
        if (!is_array($copy)) {
            $copies[] = ['heading' => '', 'body' => '', 'label' => ''];
            continue;
        }
        $copies[] = [
            'heading' => (string) ($copy['heading'] ?? ''),
            'body' => (string) ($copy['body'] ?? ''),
            'label' => (string) ($copy['label'] ?? ''),
        ];
    }
    return $copies;
}

function gvspace_case_attachment_public(int $attachment_id): array
{
    if ($attachment_id <= 0 || get_post_type($attachment_id) !== 'attachment') {
        return ['url' => '', 'kind' => ''];
    }
    $mime = (string) get_post_mime_type($attachment_id);
    $kind = str_starts_with($mime, 'video/') ? 'video' : 'image';
    $url = $kind === 'video'
        ? (string) wp_get_attachment_url($attachment_id)
        : (string) (wp_get_attachment_image_url($attachment_id, 'full') ?: wp_get_attachment_url($attachment_id));
    return ['url' => $url, 'kind' => $url !== '' ? $kind : ''];
}

function gvspace_case_named_media(int $post_id, array $meta_keys): array
{
    $items = [];
    foreach ($meta_keys as $meta_key) {
        $items[] = gvspace_case_attachment_public(absint(get_post_meta($post_id, $meta_key, true)));
    }
    return $items;
}

function gvspace_case_result_layouts(): array
{
    return [
        'wide-two' => 'Широкий кадр і два знизу',
        'stack-tall' => 'Два зліва і високий справа',
        'two-three' => 'Два зверху і три знизу',
    ];
}

function gvspace_case_result_layout(int $post_id): string
{
    $layout = (string) get_post_meta($post_id, '_gvspace_case_result_layout', true);
    return array_key_exists($layout, gvspace_case_result_layouts()) ? $layout : 'wide-two';
}

function gvspace_case_result_media(int $post_id, array $gallery_urls): array
{
    unset($gallery_urls);
    return gvspace_case_named_media($post_id, [
        '_gvspace_case_result_1',
        '_gvspace_case_result_2',
        '_gvspace_case_result_3',
        '_gvspace_case_result_4',
        '_gvspace_case_result_5',
    ]);
}

function gvspace_case_attachment_preview(int $attachment_id): array
{
    $public = gvspace_case_attachment_public($attachment_id);
    if ($public['url'] === '' || $public['kind'] === 'video') {
        return $public;
    }
    $thumb = (string) (wp_get_attachment_image_url($attachment_id, 'medium') ?: $public['url']);
    return ['url' => $thumb, 'kind' => 'image'];
}

function gvspace_case_public_blocks(int $post_id, string $locale): array
{
    $stored = gvspace_case_read_blocks($post_id);
    if (!$stored) {
        return gvspace_case_legacy_blocks($post_id, $locale);
    }
    $copy = gvspace_case_read_block_copy($post_id, $locale);
    $fallback = $locale === 'uk' ? [] : gvspace_case_read_block_copy($post_id, 'uk');
    $blocks = [];
    foreach ($stored as $index => $block) {
        $text = $copy[$index] ?? ['heading' => '', 'body' => '', 'label' => ''];
        if ($text['heading'] === '' && $text['body'] === '' && $text['label'] === '' && isset($fallback[$index])) {
            $text = $fallback[$index];
        }
        $slots = gvspace_case_block_layout($block['layout'])['slots'];
        $media = [];
        foreach ($slots as $slot_index => $slot_label) {
            unset($slot_label);
            $media[] = gvspace_case_attachment_public((int) ($block['images'][$slot_index] ?? 0));
        }
        $blocks[] = [
            'layout' => $block['layout'],
            'side' => $block['side'],
            'tone' => $block['tone'],
            'heading' => $text['heading'],
            'body' => $text['body'],
            'label' => $text['label'],
            'media' => $media,
        ];
    }
    return $blocks;
}

function gvspace_case_legacy_blocks(int $post_id, string $locale): array
{
    $blocks = [];
    foreach (['step1', 'step2', 'step3'] as $key) {
        $text = gvspace_case_locale_field($post_id, $key, $locale);
        if ($text === '' && $key === 'step1') {
            $text = gvspace_case_locale_field($post_id, 'discovery', $locale);
        }
        if (trim($text) === '') {
            continue;
        }
        $blocks[] = [
            'layout' => 'text',
            'side' => 'left',
            'tone' => 'light',
            'heading' => '',
            'body' => $text,
            'label' => '',
            'media' => [],
        ];
    }
    $gallery = gvspace_case_gallery_urls($post_id);
    if ($gallery) {
        $urls = array_slice($gallery, 0, 3);
        $media = array_map(static fn (string $url): array => ['url' => $url, 'kind' => 'image'], $urls);
        $layout = count($media) >= 3 ? 'top-two' : (count($media) === 2 ? 'duo' : 'card');
        $blocks[] = [
            'layout' => $layout,
            'side' => 'left',
            'tone' => 'light',
            'heading' => '',
            'body' => '',
            'label' => '',
            'media' => $media,
        ];
    }
    return $blocks;
}

function gvspace_case_brand_media(int $post_id): array
{
    $logo = gvspace_case_attachment_public(absint(get_post_meta($post_id, '_gvspace_case_logo_id', true)));
    $cover = gvspace_case_attachment_public(absint(get_post_meta($post_id, '_gvspace_case_cover_id', true)));
    return ['logo' => $logo, 'cover' => $cover];
}

function gvspace_render_case_single_media(WP_Post $post, string $meta_key, string $name, string $label, string $description): void
{
    $attachment_id = absint(get_post_meta($post->ID, $meta_key, true));
    $preview = gvspace_case_attachment_preview($attachment_id);
    echo '<div data-case-single-media style="margin:0 0 16px;padding:12px 14px;border:1px solid #dcdcde;border-radius:4px;background:#fff">';
    echo '<p style="margin:0 0 8px"><strong>' . esc_html($label) . '</strong></p>';
    echo '<input type="hidden" name="' . esc_attr($name) . '" value="' . esc_attr((string) $attachment_id) . '" data-preview-url="' . esc_attr($preview['url']) . '" data-kind="' . esc_attr($preview['kind']) . '">';
    echo '<div data-preview' . ($preview['url'] === '' ? ' hidden' : '') . ' style="margin:0 0 8px">';
    if ($preview['kind'] === 'video') {
        echo '<video src="' . esc_url($preview['url']) . '" muted style="display:block;width:220px;height:124px;object-fit:cover;background:#f0f0f1;border:1px solid #c3c4c7"></video>';
    } elseif ($preview['url'] !== '') {
        echo '<img src="' . esc_url($preview['url']) . '" alt="" style="display:block;width:220px;height:124px;object-fit:cover;background:#f0f0f1;border:1px solid #c3c4c7">';
    }
    echo '</div>';
    echo '<p style="margin:0"><button type="button" class="button" data-upload>Завантажити</button> ';
    echo '<button type="button" class="button-link" data-clear' . ($attachment_id ? '' : ' hidden') . '>Прибрати</button></p>';
    echo '<p class="description" style="margin:8px 0 0">' . esc_html($description) . '</p>';
    echo '</div>';
}

function gvspace_render_case_brand_media(WP_Post $post): void
{
    echo '<h3 style="margin:18px 0 8px">Медіа першого екрана</h3>';
    echo '<p class="description">Ці файли спільні для всіх мов. Фото картки в каталозі і в блоці «Схожі кейси» як і раніше ставиться в «Головне зображення».</p>';
    gvspace_render_case_single_media(
        $post,
        '_gvspace_case_logo_id',
        'gvspace_case_logo_id',
        'Логотип клієнта на першому екрані',
        'Невеликий знак біля назви кейсу. Фото або SVG.'
    );
    gvspace_render_case_single_media(
        $post,
        '_gvspace_case_cover_id',
        'gvspace_case_cover_id',
        'Велике фото або відео під метриками',
        'Широка обкладинка. Поки порожньо — на сторінці сіра заглушка.'
    );
    gvspace_render_case_media_picker_script();
}

function gvspace_case_design_media_fields(): array
{
    return [
        'gvspace_case_brief_1' => ['_gvspace_case_brief_1', 'Бриф — ліва картка', 'Два кадри під списком проблем. Поки немає файлу, видно підпис «ДО НАС».'],
        'gvspace_case_brief_2' => ['_gvspace_case_brief_2', 'Бриф — права картка', 'Другий кадр у тому ж ряду.'],
        'gvspace_case_step1_media' => ['_gvspace_case_step1_media', 'Крок 1 — медіа справа', 'Картка справа від тексту першого кроку.'],
        'gvspace_case_step2_media' => ['_gvspace_case_step2_media', 'Крок 2 — медіа зліва', 'Картка зліва від карток векторів.'],
        'gvspace_case_step3_media' => ['_gvspace_case_step3_media', 'Крок 3 — медіа справа', 'Картка справа від тексту третього кроку.'],
        'gvspace_case_result_1' => ['_gvspace_case_result_1', 'Результат — кадр 1', 'Перший кадр обраної розкладки.'],
        'gvspace_case_result_2' => ['_gvspace_case_result_2', 'Результат — кадр 2', 'Другий кадр обраної розкладки.'],
        'gvspace_case_result_3' => ['_gvspace_case_result_3', 'Результат — кадр 3', 'Третій кадр обраної розкладки.'],
        'gvspace_case_result_4' => ['_gvspace_case_result_4', 'Результат — кадр 4', 'Нижній середній кадр розкладки «два зверху і три знизу».'],
        'gvspace_case_result_5' => ['_gvspace_case_result_5', 'Результат — кадр 5', 'Нижній правий кадр розкладки «два зверху і три знизу».'],
        'gvspace_case_author_photo' => ['_gvspace_case_author_photo', 'Відгук — фото автора', 'Кругле фото в картці цитати. Поки порожньо — сіре коло.'],
    ];
}

function gvspace_render_case_design_media(WP_Post $post): void
{
    echo '<h3 style="margin:18px 0 8px">Кадри сторінки</h3>';
    echo '<p class="description">Кожне поле — своє місце в макеті. Кнопка «Завантажити» ставить фото або відео замість заглушки.</p>';
    $layout = gvspace_case_result_layout($post->ID);
    $result_open = false;
    foreach (gvspace_case_design_media_fields() as $name => [$meta, $label, $description]) {
        if (str_starts_with($name, 'gvspace_case_result_')) {
            if (!$result_open) {
                gvspace_render_case_result_layout_field($layout);
                $result_open = true;
            }
            $slot = (int) substr($name, strlen('gvspace_case_result_'));
            $hidden = $slot > 3 && $layout !== 'two-three';
            echo '<div data-result-slot="' . esc_attr((string) $slot) . '"' . ($hidden ? ' hidden' : '') . '>';
            gvspace_render_case_single_media($post, $meta, $name, $label, $description);
            echo '</div>';
            continue;
        }
        gvspace_render_case_single_media($post, $meta, $name, $label, $description);
    }
    gvspace_render_case_result_layout_script();
}

function gvspace_render_case_result_layout_field(string $layout): void
{
    echo '<div style="margin:18px 0 12px;padding:12px 14px;border:1px solid #dcdcde;border-radius:4px;background:#fff">';
    echo '<p style="margin:0 0 8px"><label for="gvspace_case_result_layout"><strong>Розкладка кадрів результату</strong></label></p>';
    echo '<select id="gvspace_case_result_layout" name="gvspace_case_result_layout" data-result-layout>';
    foreach (gvspace_case_result_layouts() as $value => $label) {
        echo '<option value="' . esc_attr($value) . '"' . selected($layout, $value, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select>';
    echo '<p class="description" data-result-layout-note style="margin:8px 0 0"></p>';
    echo '</div>';
}

function gvspace_render_case_result_layout_script(): void
{
    static $rendered = false;
    if ($rendered) {
        return;
    }
    $rendered = true;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var select = document.querySelector('[data-result-layout]');
        if (!select) return;
        var notes = {
            'wide-two': 'Кадр 1 — широкий зверху. Кадри 2 і 3 — нижній ряд.',
            'stack-tall': 'Кадр 1 — верхній лівий, кадр 2 — нижній лівий, кадр 3 — високий справа.',
            'two-three': 'Кадри 1 і 2 — верхній ряд. Кадри 3, 4 і 5 — нижній ряд.'
        };
        var apply = function () {
            var layout = select.value;
            document.querySelectorAll('[data-result-slot]').forEach(function (node) {
                var slot = parseInt(node.getAttribute('data-result-slot'), 10);
                node.hidden = layout !== 'two-three' && slot > 3;
            });
            var note = document.querySelector('[data-result-layout-note]');
            if (note) note.textContent = notes[layout] || '';
        };
        select.addEventListener('change', apply);
        apply();
    });
    </script>
    <?php
}

function gvspace_render_case_language_sections(WP_Post $post, string $locale): void
{
    $groups = [
        'Картка каталогу' => ['catalog_title', 'excerpt'],
        'Перший екран' => ['subtitle', 'metrics', 'media_label'],
        'Під обкладинкою' => ['lead', 'industry', 'market', 'period', 'status', 'service_tags', 'tech_tags'],
        'Бриф' => ['challenge', 'problems'],
        'Цілі та задачі' => ['goals_title', 'goals'],
        'Процес' => ['process_title', 'step1_title', 'step1', 'step1_result', 'step2_title', 'architecture', 'step3_title', 'step3', 'step3_result'],
        'Результат' => ['result_title', 'result_lead'],
        'Відгук' => ['testimonial', 'testimonial_author', 'testimonial_company'],
    ];
    foreach ($groups as $title => $keys) {
        $fields = array_intersect_key(GVSPACE_LOCALIZED_CASE_FIELDS, array_flip($keys));
        echo '<h4 style="margin:18px 0 8px">' . esc_html($title) . '</h4>';
        gvspace_render_field_set($post, $fields, 'gvspace_case_' . $locale . '_', '_gvspace_case_', '_' . $locale);
    }
}

function gvspace_render_case_media_picker_script(): void
{
    static $rendered = false;
    if ($rendered) {
        return;
    }
    $rendered = true;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.wp || !wp.media) return;
        var thumbUrl = function (attachment) {
            if (attachment.sizes && attachment.sizes.medium) return attachment.sizes.medium.url;
            if (attachment.sizes && attachment.sizes.thumbnail) return attachment.sizes.thumbnail.url;
            return attachment.url;
        };
        var paint = function (box, url, kind) {
            if (!box) return;
            box.innerHTML = '';
            if (!url) {
                box.hidden = true;
                return;
            }
            box.hidden = false;
            var media = document.createElement(kind === 'video' ? 'video' : 'img');
            media.src = url;
            if (kind === 'video') media.muted = true;
            media.alt = '';
            box.appendChild(media);
        };
        document.querySelectorAll('[data-case-single-media]').forEach(function (wrap) {
            var input = wrap.querySelector('input[type="hidden"]');
            var preview = wrap.querySelector('[data-preview]');
            var upload = wrap.querySelector('[data-upload]');
            var clear = wrap.querySelector('[data-clear]');
            if (!input || !preview || !upload || !clear) return;
            var setMedia = function (id, url, kind) {
                input.value = id ? String(id) : '';
                paint(preview, url, kind);
                clear.hidden = !id;
            };
            upload.addEventListener('click', function (event) {
                event.preventDefault();
                var frame = wp.media({
                    title: 'Завантажити',
                    button: { text: 'Завантажити' },
                    multiple: false,
                    library: { type: ['image', 'video'] }
                });
                frame.on('select', function () {
                    var attachment = frame.state().get('selection').first();
                    if (!attachment) return;
                    var data = attachment.toJSON();
                    if (!data.id) return;
                    var kind = String(data.mime || '').indexOf('video/') === 0 ? 'video' : 'image';
                    setMedia(data.id, kind === 'video' ? data.url : thumbUrl(data), kind);
                });
                frame.open();
            });
            clear.addEventListener('click', function (event) {
                event.preventDefault();
                setMedia(0, '', '');
            });
        });
    });
    </script>
    <?php
}

function gvspace_render_case_block_slot(int $index, int $slot_index, string $slot_label, int $attachment_id): void
{
    $preview = gvspace_case_attachment_preview($attachment_id);
    echo '<div data-slot style="width:180px">';
    echo '<p style="margin:0 0 6px"><strong data-slot-label>' . esc_html($slot_label) . '</strong></p>';
    echo '<input type="hidden" name="gvspace_case_blocks[' . esc_attr((string) $index) . '][images][' . esc_attr((string) $slot_index) . ']" value="' . esc_attr((string) $attachment_id) . '" data-preview-url="' . esc_attr($preview['url']) . '" data-kind="' . esc_attr($preview['kind']) . '">';
    echo '<div data-preview' . ($preview['url'] === '' ? ' hidden' : '') . ' style="margin:0 0 6px">';
    if ($preview['kind'] === 'video' && $preview['url'] !== '') {
        echo '<video src="' . esc_url($preview['url']) . '" muted style="display:block;width:160px;height:100px;object-fit:cover;background:#f0f0f1;border:1px solid #c3c4c7"></video>';
    } elseif ($preview['url'] !== '') {
        echo '<img src="' . esc_url($preview['url']) . '" alt="" style="display:block;width:160px;height:100px;object-fit:cover;background:#f0f0f1;border:1px solid #c3c4c7">';
    }
    echo '</div>';
    echo '<p style="margin:0"><button type="button" class="button" data-upload>Завантажити</button><br>';
    echo '<button type="button" class="button-link" data-clear' . ($attachment_id ? '' : ' hidden') . ' style="margin-top:4px">Прибрати</button></p>';
    echo '</div>';
}

function gvspace_render_case_blocks_editor(WP_Post $post): void
{
    $blocks = gvspace_case_read_blocks($post->ID);
    $layouts = gvspace_case_block_layouts();
    echo '<div data-gvspace-case-blocks>';
    echo '<input type="hidden" name="gvspace_case_blocks_submitted" value="1">';
    echo '<h3 style="margin:18px 0 8px">Блоки сторінки</h3>';
    echo '<p class="description">Порядок блоків зверху вниз — це порядок на сторінці після тексту контексту. Варіант розкладки спільний для всіх мов. Тексти кожного блоку заповнюються нижче, у вибраній мові. «Схожі кейси» підставляються самі з інших кейсів.</p>';
    echo '<div data-case-block-list>';
    foreach ($blocks as $index => $block) {
        gvspace_render_case_block_section($index, $block, $layouts);
    }
    echo '</div>';
    echo '<p style="margin:12px 0 0"><button type="button" class="button button-primary" data-add-block>Додати блок розкладки</button></p>';
    echo '</div>';
    gvspace_render_case_blocks_script();
}

function gvspace_render_case_block_section(int $index, array $block, array $layouts): void
{
    $layout = (string) ($block['layout'] ?? 'text');
    $config = gvspace_case_block_layout($layout);
    $block_id = 'b' . $index;
    echo '<section data-case-block data-block-id="' . esc_attr($block_id) . '" style="margin:12px 0;padding:12px 14px;border:1px solid #c3c4c7;border-radius:4px;background:#f6f7f7">';
    echo '<p style="display:flex;gap:8px;align-items:center;margin:0 0 10px">';
    echo '<strong data-block-title>Блок ' . esc_html((string) ($index + 1)) . '</strong>';
    echo '<button type="button" class="button" data-block-up>Вгору</button>';
    echo '<button type="button" class="button" data-block-down>Вниз</button>';
    echo '<button type="button" class="button-link" data-block-remove style="margin-left:auto">Видалити блок</button>';
    echo '</p>';
    echo '<p><label><strong>Варіант розкладки</strong><br>';
    echo '<select name="gvspace_case_blocks[' . esc_attr((string) $index) . '][layout]" data-layout style="min-width:320px">';
    foreach ($layouts as $key => $item) {
        echo '<option value="' . esc_attr($key) . '"' . selected($layout, $key, false) . '>' . esc_html($item['label']) . '</option>';
    }
    echo '</select></label></p>';
    echo '<p class="description" data-layout-hint>' . esc_html($config['hint']) . '</p>';
    $card_hidden = $config['group'] === 'card' ? '' : ' hidden';
    echo '<div data-card-options' . $card_hidden . ' style="display:flex;flex-wrap:wrap;gap:16px">';
    echo '<p><label><strong>Сторона картки</strong><br><select name="gvspace_case_blocks[' . esc_attr((string) $index) . '][side]">';
    echo '<option value="left"' . selected($block['side'] ?? 'left', 'left', false) . '>Медіа зліва, текст справа</option>';
    echo '<option value="right"' . selected($block['side'] ?? '', 'right', false) . '>Текст зліва, медіа справа</option>';
    echo '</select></label></p>';
    echo '<p><label><strong>Стиль картки</strong><br><select name="gvspace_case_blocks[' . esc_attr((string) $index) . '][tone]">';
    echo '<option value="dark"' . selected($block['tone'] ?? 'dark', 'dark', false) . '>Темна</option>';
    echo '<option value="light"' . selected($block['tone'] ?? '', 'light', false) . '>Світла</option>';
    echo '</select></label></p>';
    echo '</div>';
    echo '<div data-slots style="display:flex;flex-wrap:wrap;gap:16px;margin-top:8px">';
    foreach ($config['slots'] as $slot_index => $slot_label) {
        gvspace_render_case_block_slot($index, (int) $slot_index, $slot_label, (int) ($block['images'][$slot_index] ?? 0));
    }
    echo '</div>';
    echo '</section>';
}

function gvspace_render_case_block_copies(WP_Post $post, string $locale): void
{
    $blocks = gvspace_case_read_blocks($post->ID);
    if (!$blocks) {
        echo '<div data-case-copy-list data-locale="' . esc_attr($locale) . '"></div>';
        return;
    }
    $copies = gvspace_case_read_block_copy($post->ID, $locale);
    echo '<div data-case-copy-list data-locale="' . esc_attr($locale) . '">';
    echo '<h4 style="margin:18px 0 8px">Тексти блоків розкладки</h4>';
    foreach ($blocks as $index => $block) {
        $copy = $copies[$index] ?? ['heading' => '', 'body' => '', 'label' => ''];
        gvspace_render_case_block_copy($locale, $index, $block, $copy);
    }
    echo '</div>';
}

function gvspace_render_case_block_copy(string $locale, int $index, array $block, array $copy): void
{
    $layout = (string) ($block['layout'] ?? 'text');
    $config = gvspace_case_block_layout($layout);
    $group = $config['group'];
    $block_id = 'b' . $index;
    $base = 'gvspace_case_block_copy[' . $locale . '][' . $index . ']';
    echo '<div data-case-block-copy data-block-id="' . esc_attr($block_id) . '" style="margin:0 0 12px;padding:10px 12px;border:1px solid #dcdcde;background:#fff">';
    echo '<p style="margin:0 0 8px"><strong data-copy-title>Блок ' . esc_html((string) ($index + 1)) . ' — ' . esc_html($config['label']) . '</strong></p>';
    $label_hidden = $group === 'card' ? '' : ' hidden';
    echo '<p data-copy-field="card"' . $label_hidden . '><label><strong>Підпис на картці</strong><br>';
    echo '<input type="text" name="' . esc_attr($base) . '[label]" value="' . esc_attr($copy['label']) . '" placeholder="ДО НАС" style="width:100%"></label></p>';
    $text_hidden = in_array($group, ['text', 'card', 'gallery'], true) ? '' : ' hidden';
    echo '<p data-copy-field="text card gallery"' . $text_hidden . '><label><strong>Заголовок блоку</strong><br>';
    echo '<input type="text" name="' . esc_attr($base) . '[heading]" value="' . esc_attr($copy['heading']) . '" style="width:100%"></label></p>';
    echo '<p data-copy-field="text card gallery"' . $text_hidden . '><label><strong>Текст блоку</strong><br>';
    echo '<textarea name="' . esc_attr($base) . '[body]" rows="4" style="width:100%">' . esc_textarea($copy['body']) . '</textarea></label></p>';
    echo '<p class="description" data-copy-note="metrics"' . ($group === 'metrics' ? '' : ' hidden') . '>Для цього варіанту окремий текст не потрібен: на сторінці повторюються метрики цієї мови.</p>';
    echo '</div>';
}

function gvspace_save_case_page_fields(int $post_id): void
{
    $media_fields = [
        'gvspace_case_logo_id' => '_gvspace_case_logo_id',
        'gvspace_case_cover_id' => '_gvspace_case_cover_id',
    ];
    foreach (gvspace_case_design_media_fields() as $field => $config) {
        $media_fields[$field] = $config[0];
    }
    if (isset($_POST['gvspace_case_result_layout'])) {
        $layout = sanitize_key(wp_unslash((string) $_POST['gvspace_case_result_layout']));
        if (!array_key_exists($layout, gvspace_case_result_layouts())) {
            $layout = 'wide-two';
        }
        update_post_meta($post_id, '_gvspace_case_result_layout', $layout);
    }
    foreach ($media_fields as $field => $meta_key) {
        if (!isset($_POST[$field])) {
            continue;
        }
        $attachment_id = absint(wp_unslash($_POST[$field]));
        if ($attachment_id && get_post_type($attachment_id) === 'attachment') {
            update_post_meta($post_id, $meta_key, $attachment_id);
        } else {
            delete_post_meta($post_id, $meta_key);
        }
    }

    if (!isset($_POST['gvspace_case_blocks_submitted'])) {
        return;
    }

    $raw_blocks = isset($_POST['gvspace_case_blocks']) && is_array($_POST['gvspace_case_blocks'])
        ? wp_unslash($_POST['gvspace_case_blocks'])
        : [];
    ksort($raw_blocks);
    $blocks = [];
    foreach ($raw_blocks as $block) {
        if (!is_array($block) || count($blocks) >= 30) {
            continue;
        }
        $layout = sanitize_key((string) ($block['layout'] ?? 'text'));
        if (!isset(gvspace_case_block_layouts()[$layout])) {
            $layout = 'text';
        }
        $images = [];
        $raw_images = is_array($block['images'] ?? null) ? $block['images'] : [];
        ksort($raw_images);
        $slot_count = count(gvspace_case_block_layout($layout)['slots']);
        for ($slot = 0; $slot < $slot_count; $slot++) {
            $attachment_id = absint($raw_images[$slot] ?? 0);
            $images[] = $attachment_id && get_post_type($attachment_id) === 'attachment' ? $attachment_id : 0;
        }
        $blocks[] = [
            'layout' => $layout,
            'side' => (($block['side'] ?? '') === 'right') ? 'right' : 'left',
            'tone' => (($block['tone'] ?? '') === 'light') ? 'light' : 'dark',
            'images' => $images,
        ];
    }
    gvspace_case_update_json_meta($post_id, '_gvspace_case_blocks', $blocks);

    $raw_copy = isset($_POST['gvspace_case_block_copy']) && is_array($_POST['gvspace_case_block_copy'])
        ? wp_unslash($_POST['gvspace_case_block_copy'])
        : [];
    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        $locale_rows = is_array($raw_copy[$locale] ?? null) ? $raw_copy[$locale] : [];
        ksort($locale_rows);
        $copies = [];
        foreach ($blocks as $index => $block) {
            $row = is_array($locale_rows[$index] ?? null) ? $locale_rows[$index] : [];
            $copies[] = [
                'heading' => sanitize_text_field((string) ($row['heading'] ?? '')),
                'body' => sanitize_textarea_field((string) ($row['body'] ?? '')),
                'label' => sanitize_text_field((string) ($row['label'] ?? '')),
            ];
        }
        gvspace_case_update_json_meta($post_id, '_gvspace_case_block_copy_' . $locale, $copies);
    }
}

function gvspace_render_case_blocks_script(): void
{
    static $rendered = false;
    if ($rendered) {
        return;
    }
    $rendered = true;
    $layouts = [];
    foreach (gvspace_case_block_layouts() as $key => $layout) {
        $layouts[$key] = [
            'label' => $layout['label'],
            'hint' => $layout['hint'],
            'slots' => $layout['slots'],
            'group' => $layout['group'],
        ];
    }
    $config = wp_json_encode([
        'layouts' => $layouts,
        'locales' => array_keys(GVSPACE_CONTENT_LOCALES),
    ], JSON_UNESCAPED_UNICODE);
    ?>
    <style>
        [data-case-block] [data-preview] img,
        [data-case-block] [data-preview] video,
        [data-case-single-media] [data-preview] img,
        [data-case-single-media] [data-preview] video {
            display: block;
            width: 160px;
            height: 100px;
            object-fit: cover;
            background: #f0f0f1;
            border: 1px solid #c3c4c7;
        }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var root = document.querySelector('[data-gvspace-case-blocks]');
        var config = <?php echo $config ?: '{}'; ?>;
        var layouts = config.layouts || {};
        var locales = config.locales || [];
        if (!root || !window.wp || !wp.media) return;
        var list = root.querySelector('[data-case-block-list]');
        var addButton = root.querySelector('[data-add-block]');
        if (!list || !addButton) return;

        var layoutOf = function (block) {
            var select = block.querySelector('[data-layout]');
            return select && layouts[select.value] ? select.value : 'text';
        };

        var paintPreview = function (box, url, kind) {
            if (!box) return;
            box.innerHTML = '';
            if (!url) {
                box.hidden = true;
                return;
            }
            box.hidden = false;
            var media = document.createElement(kind === 'video' ? 'video' : 'img');
            media.src = url;
            if (kind === 'video') media.muted = true;
            media.alt = '';
            box.appendChild(media);
        };

        var thumbUrl = function (attachment) {
            if (attachment.sizes && attachment.sizes.medium) return attachment.sizes.medium.url;
            if (attachment.sizes && attachment.sizes.thumbnail) return attachment.sizes.thumbnail.url;
            return attachment.url;
        };

        var bindSingle = function (wrap) {
            if (wrap.getAttribute('data-bound') === '1') return;
            wrap.setAttribute('data-bound', '1');
            var input = wrap.querySelector('input[type="hidden"]');
            var preview = wrap.querySelector('[data-preview]');
            var upload = wrap.querySelector('[data-upload]');
            var clear = wrap.querySelector('[data-clear]');
            if (!input || !preview || !upload || !clear) return;
            var setMedia = function (id, url, kind) {
                input.value = id ? String(id) : '';
                input.setAttribute('data-preview-url', url || '');
                input.setAttribute('data-kind', kind || '');
                paintPreview(preview, url, kind);
                if (id) clear.hidden = false;
                else clear.hidden = true;
            };
            upload.addEventListener('click', function (event) {
                event.preventDefault();
                var frame = wp.media({
                    title: 'Завантажити',
                    button: { text: 'Завантажити' },
                    multiple: false,
                    library: { type: ['image', 'video'] }
                });
                frame.on('select', function () {
                    var attachment = frame.state().get('selection').first();
                    if (!attachment) return;
                    var data = attachment.toJSON();
                    if (!data.id) return;
                    var kind = String(data.mime || '').indexOf('video/') === 0 ? 'video' : 'image';
                    setMedia(data.id, kind === 'video' ? data.url : thumbUrl(data), kind);
                });
                frame.open();
            });
            clear.addEventListener('click', function (event) {
                event.preventDefault();
                setMedia(0, '', '');
            });
        };

        document.querySelectorAll('[data-case-single-media]').forEach(bindSingle);

        var renderSlots = function (block) {
            var layout = layoutOf(block);
            var slots = layouts[layout].slots || [];
            var holder = block.querySelector('[data-slots]');
            var previous = [];
            holder.querySelectorAll('[data-slot] input[type="hidden"]').forEach(function (input) {
                previous.push({
                    id: input.value || '',
                    url: input.getAttribute('data-preview-url') || '',
                    kind: input.getAttribute('data-kind') || ''
                });
            });
            holder.innerHTML = '';
            slots.forEach(function (label, slotIndex) {
                var stored = previous[slotIndex] || { id: '', url: '', kind: '' };
                var slot = document.createElement('div');
                slot.setAttribute('data-slot', '');
                slot.style.width = '180px';
                slot.innerHTML = '<p style="margin:0 0 6px"><strong data-slot-label></strong></p>'
                    + '<input type="hidden" data-preview-url="" data-kind="">'
                    + '<div data-preview hidden style="margin:0 0 6px"></div>'
                    + '<p style="margin:0"><button type="button" class="button" data-upload>Завантажити</button><br>'
                    + '<button type="button" class="button-link" data-clear hidden style="margin-top:4px">Прибрати</button></p>';
                slot.querySelector('[data-slot-label]').textContent = label;
                var input = slot.querySelector('input');
                input.value = stored.id;
                input.setAttribute('data-preview-url', stored.url);
                input.setAttribute('data-kind', stored.kind);
                paintPreview(slot.querySelector('[data-preview]'), stored.url, stored.kind);
                slot.querySelector('[data-clear]').hidden = !stored.id;
                holder.appendChild(slot);
            });
        };

        var syncCopy = function (block) {
            var layout = layoutOf(block);
            var group = layouts[layout].group;
            var id = block.getAttribute('data-block-id');
            document.querySelectorAll('[data-case-block-copy][data-block-id="' + id + '"]').forEach(function (copy) {
                copy.querySelectorAll('[data-copy-field]').forEach(function (field) {
                    var modes = (field.getAttribute('data-copy-field') || '').split(' ');
                    field.hidden = modes.indexOf(group) === -1;
                });
                var note = copy.querySelector('[data-copy-note="metrics"]');
                if (note) note.hidden = group !== 'metrics';
            });
            var hint = block.querySelector('[data-layout-hint]');
            if (hint) hint.textContent = layouts[layout].hint || '';
            var cardOptions = block.querySelector('[data-card-options]');
            if (cardOptions) cardOptions.hidden = group !== 'card';
        };

        var reindex = function () {
            var blocks = list.querySelectorAll('[data-case-block]');
            blocks.forEach(function (block, index) {
                var title = block.querySelector('[data-block-title]');
                if (title) title.textContent = 'Блок ' + (index + 1);
                block.querySelectorAll('[name]').forEach(function (input) {
                    input.name = input.name.replace(/gvspace_case_blocks\[\d+\]/, 'gvspace_case_blocks[' + index + ']');
                });
                block.querySelectorAll('[data-slot]').forEach(function (slot, slotIndex) {
                    var input = slot.querySelector('input[type="hidden"]');
                    if (input) input.name = 'gvspace_case_blocks[' + index + '][images][' + slotIndex + ']';
                });
                var id = block.getAttribute('data-block-id');
                var layout = layoutOf(block);
                document.querySelectorAll('[data-case-block-copy][data-block-id="' + id + '"]').forEach(function (copy) {
                    copy.querySelectorAll('[name]').forEach(function (input) {
                        input.name = input.name.replace(/gvspace_case_block_copy\[([^\]]+)\]\[\d+\]/, 'gvspace_case_block_copy[$1][' + index + ']');
                    });
                    var heading = copy.querySelector('[data-copy-title]');
                    if (heading) heading.textContent = 'Блок ' + (index + 1) + ' — ' + (layouts[layout].label || '');
                });
            });
        };

        var applyLayout = function (block) {
            renderSlots(block);
            syncCopy(block);
            reindex();
        };

        list.querySelectorAll('[data-case-block]').forEach(function (block) {
            syncCopy(block);
        });

        list.addEventListener('change', function (event) {
            var select = event.target.closest('[data-layout]');
            if (!select || !list.contains(select)) return;
            applyLayout(select.closest('[data-case-block]'));
        });

        list.addEventListener('click', function (event) {
            var upload = event.target.closest('[data-upload]');
            var clear = event.target.closest('[data-clear]');
            var remove = event.target.closest('[data-block-remove]');
            var up = event.target.closest('[data-block-up]');
            var down = event.target.closest('[data-block-down]');
            var block = event.target.closest('[data-case-block]');
            if (!block || !list.contains(block)) return;
            if (upload) {
                event.preventDefault();
                var slot = upload.closest('[data-slot]');
                var input = slot ? slot.querySelector('input[type="hidden"]') : null;
                var label = slot ? slot.querySelector('[data-slot-label]') : null;
                if (!input) return;
                var frame = wp.media({
                    title: label ? label.textContent : 'Завантажити',
                    button: { text: 'Завантажити' },
                    multiple: false,
                    library: { type: ['image', 'video'] }
                });
                frame.on('select', function () {
                    var attachment = frame.state().get('selection').first();
                    if (!attachment) return;
                    var data = attachment.toJSON();
                    if (!data.id) return;
                    var kind = String(data.mime || '').indexOf('video/') === 0 ? 'video' : 'image';
                    var url = kind === 'video' ? data.url : thumbUrl(data);
                    input.value = String(data.id);
                    input.setAttribute('data-preview-url', url || '');
                    input.setAttribute('data-kind', kind);
                    paintPreview(slot.querySelector('[data-preview]'), url, kind);
                    var clearButton = slot.querySelector('[data-clear]');
                    if (clearButton) clearButton.hidden = false;
                });
                frame.open();
                return;
            }
            if (clear) {
                event.preventDefault();
                var cleared = clear.closest('[data-slot]');
                if (!cleared) return;
                var clearedInput = cleared.querySelector('input[type="hidden"]');
                if (clearedInput) {
                    clearedInput.value = '';
                    clearedInput.setAttribute('data-preview-url', '');
                    clearedInput.setAttribute('data-kind', '');
                }
                paintPreview(cleared.querySelector('[data-preview]'), '', '');
                clear.hidden = true;
                return;
            }
            if (remove) {
                event.preventDefault();
                var id = block.getAttribute('data-block-id');
                document.querySelectorAll('[data-case-block-copy][data-block-id="' + id + '"]').forEach(function (copy) {
                    copy.remove();
                });
                block.remove();
                reindex();
                return;
            }
            if (up && block.previousElementSibling) {
                event.preventDefault();
                list.insertBefore(block, block.previousElementSibling);
                reindex();
                return;
            }
            if (down && block.nextElementSibling) {
                event.preventDefault();
                list.insertBefore(block.nextElementSibling, block);
                reindex();
            }
        });

        var optionMarkup = function (selected) {
            return Object.keys(layouts).map(function (key) {
                return '<option value="' + key + '"' + (key === selected ? ' selected' : '') + '>' + layouts[key].label + '</option>';
            }).join('');
        };

        addButton.addEventListener('click', function (event) {
            event.preventDefault();
            var index = list.querySelectorAll('[data-case-block]').length;
            var id = 'b' + Date.now();
            var section = document.createElement('section');
            section.setAttribute('data-case-block', '');
            section.setAttribute('data-block-id', id);
            section.style.cssText = 'margin:12px 0;padding:12px 14px;border:1px solid #c3c4c7;border-radius:4px;background:#f6f7f7';
            section.innerHTML = '<p style="display:flex;gap:8px;align-items:center;margin:0 0 10px"><strong data-block-title>Блок ' + (index + 1) + '</strong>'
                + '<button type="button" class="button" data-block-up>Вгору</button>'
                + '<button type="button" class="button" data-block-down>Вниз</button>'
                + '<button type="button" class="button-link" data-block-remove style="margin-left:auto">Видалити блок</button></p>'
                + '<p><label><strong>Варіант розкладки</strong><br><select data-layout name="gvspace_case_blocks[' + index + '][layout]" style="min-width:320px">' + optionMarkup('text') + '</select></label></p>'
                + '<p class="description" data-layout-hint></p>'
                + '<div data-card-options hidden style="display:flex;flex-wrap:wrap;gap:16px">'
                + '<p><label><strong>Сторона картки</strong><br><select name="gvspace_case_blocks[' + index + '][side]"><option value="left">Медіа зліва, текст справа</option><option value="right">Текст зліва, медіа справа</option></select></label></p>'
                + '<p><label><strong>Стиль картки</strong><br><select name="gvspace_case_blocks[' + index + '][tone]"><option value="dark">Темна</option><option value="light">Світла</option></select></label></p>'
                + '</div><div data-slots style="display:flex;flex-wrap:wrap;gap:16px;margin-top:8px"></div>';
            list.appendChild(section);
            locales.forEach(function (locale) {
                var panel = document.querySelector('[data-gvspace-language-panel="case-language"][data-locale="' + locale + '"]');
                if (!panel) return;
                var copyList = panel.querySelector('[data-case-copy-list]');
                if (!copyList) return;
                if (!copyList.querySelector('h4')) {
                    var title = document.createElement('h4');
                    title.style.cssText = 'margin:18px 0 8px';
                    title.textContent = 'Тексти блоків розкладки';
                    copyList.appendChild(title);
                }
                var copy = document.createElement('div');
                copy.setAttribute('data-case-block-copy', '');
                copy.setAttribute('data-block-id', id);
                copy.style.cssText = 'margin:0 0 12px;padding:10px 12px;border:1px solid #dcdcde;background:#fff';
                var base = 'gvspace_case_block_copy[' + locale + '][' + index + ']';
                copy.innerHTML = '<p style="margin:0 0 8px"><strong data-copy-title></strong></p>'
                    + '<p data-copy-field="card" hidden><label><strong>Підпис на картці</strong><br><input type="text" name="' + base + '[label]" placeholder="ДО НАС" style="width:100%"></label></p>'
                    + '<p data-copy-field="text card gallery"><label><strong>Заголовок блоку</strong><br><input type="text" name="' + base + '[heading]" style="width:100%"></label></p>'
                    + '<p data-copy-field="text card gallery"><label><strong>Текст блоку</strong><br><textarea name="' + base + '[body]" rows="4" style="width:100%"></textarea></label></p>'
                    + '<p class="description" data-copy-note="metrics" hidden>Для цього варіанту окремий текст не потрібен: на сторінці повторюються метрики цієї мови.</p>';
                copyList.appendChild(copy);
            });
            applyLayout(section);
        });
    });
    </script>
    <?php
}
