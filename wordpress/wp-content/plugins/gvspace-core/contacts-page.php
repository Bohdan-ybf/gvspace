<?php

if (!defined('ABSPATH')) {
    exit;
}

function gvspace_contacts_text_fields(): array
{
    return [
        'eyebrow' => ['label' => 'Бейдж над заголовком', 'type' => 'text'],
        'title' => ['label' => 'Заголовок сторінки (H1)', 'type' => 'text'],
        'intro' => ['label' => 'Текст під заголовком', 'type' => 'textarea'],
        'response' => ['label' => 'Бейдж відповіді (наприклад, протягом 2 годин)', 'type' => 'text'],
        'direct_label' => ['label' => 'Підпис лівої колонки', 'type' => 'text'],
        'form_label' => ['label' => 'Підпис форми', 'type' => 'text'],
        'name_placeholder' => ['label' => 'Плейсхолдер імені', 'type' => 'text'],
        'phone_placeholder' => ['label' => 'Плейсхолдер телефону', 'type' => 'text'],
        'email_placeholder' => ['label' => 'Плейсхолдер email', 'type' => 'text'],
        'topic_placeholder' => ['label' => 'Плейсхолдер теми', 'type' => 'text'],
        'message_placeholder' => ['label' => 'Плейсхолдер повідомлення', 'type' => 'textarea'],
        'submit' => ['label' => 'Текст кнопки', 'type' => 'text'],
        'consent' => ['label' => 'Текст згоди під кнопкою', 'type' => 'textarea'],
        'presence_eyebrow' => ['label' => 'Підпис над картою (ЛОКАЦІЯ)', 'type' => 'text'],
        'presence_title' => ['label' => 'Заголовок блоку карти', 'type' => 'text'],
        'presence_text' => ['label' => 'Текст блоку карти', 'type' => 'textarea'],
        'social_label' => ['label' => 'Підпис блоку соцмереж', 'type' => 'text'],
    ];
}

function gvspace_contacts_locale_field(int $post_id, string $field, string $locale): string
{
    $value = (string) get_post_meta($post_id, '_gvspace_contacts_' . $field . '_' . $locale, true);
    if ($value !== '') return $value;
    if ($locale !== 'uk') {
        $uk = (string) get_post_meta($post_id, '_gvspace_contacts_' . $field . '_uk', true);
        if ($uk !== '') return $uk;
    }
    return '';
}

function gvspace_contacts_decode_list(int $post_id, string $field, string $locale): array
{
    $raw = (string) get_post_meta($post_id, '_gvspace_contacts_' . $field . '_' . $locale, true);
    $decoded = json_decode($raw, true);
    if (!is_array($decoded) && $locale !== 'uk') {
        $raw = (string) get_post_meta($post_id, '_gvspace_contacts_' . $field . '_uk', true);
        $decoded = json_decode($raw, true);
    }
    return is_array($decoded) ? array_values($decoded) : [];
}

function gvspace_contacts_catalog(): array
{
    return [
        'uk' => [
            'eyebrow' => 'CONTACTS',
            'title' => 'Все починається з розмови',
            'intro' => 'Розкажіть про ваш бізнес і задачу. Ми відповімо і запропонуємо перший крок — без зобов’язань.',
            'response' => 'Відповідаємо протягом 2 годин у робочий день',
            'direct_label' => 'НАПИСАТИ НАПРЯМУ',
            'form_label' => 'АБО ЗАЛИШИТИ ЗАЯВКУ',
            'name_placeholder' => "Ім'я",
            'phone_placeholder' => '+38 0__',
            'email_placeholder' => 'Email',
            'topic_placeholder' => 'Що вас цікавить?',
            'message_placeholder' => 'Розкажіть про ваш проєкт або задачу',
            'submit' => 'Відправити заявку',
            'consent' => 'Натискаючи кнопку, ви погоджуєтесь з обробкою персональних даних',
            'presence_eyebrow' => 'ЛОКАЦІЯ',
            'presence_title' => 'Працюємо там, де зручно вам',
            'presence_text' => 'Україна, ЄС та США — оформлюємо співпрацю у вашій юрисдикції.',
            'social_label' => 'СОЦІАЛЬНІ МЕРЕЖІ',
            'seo_title' => 'Контакти GVSPACE',
            'seo_description' => 'Зв’яжіться з командою GVSPACE: Telegram, email, телефон, офіси в Києві, Renton і Братиславі.',
            'channels' => [
                ['kind' => 'telegram', 'label' => 'TELEGRAM', 'value' => '@[username]', 'hint' => 'Найшвидший спосіб зв’язатись', 'url' => 'https://t.me/'],
                ['kind' => 'email', 'label' => 'EMAIL', 'value' => '[email@gvspace.com]', 'hint' => 'Для детальних запитів і документів', 'url' => 'mailto:email@gvspace.com'],
                ['kind' => 'phone', 'label' => 'ТЕЛЕФОН', 'value' => '+38 0__ ___ __ __', 'hint' => 'Пн–Пт, 10:00–19:00', 'url' => 'tel:+380000000000'],
            ],
            'offices' => [
                ['title' => 'Київ, Україна:', 'address' => "Україна, м. Київ, вул. Нижній Вал, 17/8", 'phone' => '+38 099 999 99 99', 'email' => 'gvspace.ua@gvspace.com'],
                ['title' => 'Renton, USA:', 'address' => "416 Monroe Ave NE, Apt 206\nRenton, WA 98056, USA", 'phone' => '+1 999 999 99 99', 'email' => 'gvspace.usa@gvspace.com'],
                ['title' => 'Bratislava, Slovakia:', 'address' => "G1 - Space s. r. o.\nKarpatské námestie 7770/10A\n831 06 Bratislava - Rača SLOVAKIA", 'phone' => '+421 999 99 99 99', 'email' => 'gvspace.eu@gvspace.com'],
            ],
            'socials' => [
                ['name' => 'LinkedIn', 'handle' => 'linkedin.com/company/[gvspace]', 'url' => 'https://www.linkedin.com/company/gvspace', 'network' => 'linkedin'],
                ['name' => 'Instagram', 'handle' => '@[gvspace]', 'url' => 'https://www.instagram.com/gvspace', 'network' => 'instagram'],
                ['name' => 'Facebook', 'handle' => 'facebook.com/[gvspace]', 'url' => 'https://www.facebook.com/gvspace', 'network' => 'facebook'],
                ['name' => 'Telegram-канал', 'handle' => 't.me/[gvspace]', 'url' => 'https://t.me/gvspace', 'network' => 'telegram'],
            ],
        ],
        'en' => [
            'eyebrow' => 'CONTACTS',
            'title' => 'Everything starts with a conversation',
            'intro' => 'Tell us about your business and challenge. We’ll respond and suggest a first step — with no obligation.',
            'response' => 'We respond within 2 hours on business days',
            'direct_label' => 'CONTACT US DIRECTLY',
            'form_label' => 'OR LEAVE A REQUEST',
            'name_placeholder' => 'Name',
            'phone_placeholder' => '+38 0__',
            'email_placeholder' => 'Email',
            'topic_placeholder' => 'What are you interested in?',
            'message_placeholder' => 'Tell us about your project or challenge',
            'submit' => 'Send request',
            'consent' => 'By clicking the button, you consent to the processing of personal data',
            'presence_eyebrow' => 'LOCATION',
            'presence_title' => 'We work where it is convenient for you',
            'presence_text' => 'Ukraine, the EU, and the USA — we set up cooperation in your jurisdiction.',
            'social_label' => 'SOCIAL MEDIA',
            'seo_title' => 'Contact GVSPACE',
            'seo_description' => 'Contact the GVSPACE team via Telegram, email, or phone. Offices in Kyiv, Renton, and Bratislava.',
            'channels' => [
                ['kind' => 'telegram', 'label' => 'TELEGRAM', 'value' => '@[username]', 'hint' => 'The fastest way to reach us', 'url' => 'https://t.me/'],
                ['kind' => 'email', 'label' => 'EMAIL', 'value' => '[email@gvspace.com]', 'hint' => 'For detailed enquiries and documents', 'url' => 'mailto:email@gvspace.com'],
                ['kind' => 'phone', 'label' => 'PHONE', 'value' => '+38 0__ ___ __ __', 'hint' => 'Mon–Fri, 10:00–19:00', 'url' => 'tel:+380000000000'],
            ],
            'offices' => [
                ['title' => 'Kyiv, Ukraine:', 'address' => "Ukraine, Kyiv, Nyzhnii Val St, 17/8", 'phone' => '+38 099 999 99 99', 'email' => 'gvspace.ua@gvspace.com'],
                ['title' => 'Renton, USA:', 'address' => "416 Monroe Ave NE, Apt 206\nRenton, WA 98056, USA", 'phone' => '+1 999 999 99 99', 'email' => 'gvspace.usa@gvspace.com'],
                ['title' => 'Bratislava, Slovakia:', 'address' => "G1 - Space s. r. o.\nKarpatské námestie 7770/10A\n831 06 Bratislava - Rača SLOVAKIA", 'phone' => '+421 999 99 99 99', 'email' => 'gvspace.eu@gvspace.com'],
            ],
            'socials' => [
                ['name' => 'LinkedIn', 'handle' => 'linkedin.com/company/[gvspace]', 'url' => 'https://www.linkedin.com/company/gvspace', 'network' => 'linkedin'],
                ['name' => 'Instagram', 'handle' => '@[gvspace]', 'url' => 'https://www.instagram.com/gvspace', 'network' => 'instagram'],
                ['name' => 'Facebook', 'handle' => 'facebook.com/[gvspace]', 'url' => 'https://www.facebook.com/gvspace', 'network' => 'facebook'],
                ['name' => 'Telegram channel', 'handle' => 't.me/[gvspace]', 'url' => 'https://t.me/gvspace', 'network' => 'telegram'],
            ],
        ],
    ];
}

function gvspace_seed_contacts_page(): void
{
    if (get_option('gvspace_contacts_page_seeded_v1') === '1') return;
    if (!add_option('gvspace_contacts_page_seed_v1_lock', time(), '', false)) return;

    $existing = get_posts([
        'post_type' => 'gv_contacts_page',
        'post_status' => 'any',
        'numberposts' => 1,
        'meta_key' => '_gvspace_translation_group',
        'meta_value' => 'contacts-page',
    ]);
    $post_id = $existing ? (int) $existing[0]->ID : 0;
    $catalog = gvspace_contacts_catalog();

    if (!$post_id) {
        $post_id = wp_insert_post([
            'post_type' => 'gv_contacts_page',
            'post_status' => 'publish',
            'post_title' => $catalog['uk']['title'],
            'post_name' => 'contacts',
        ]);
    }
    if (!$post_id || is_wp_error($post_id)) {
        delete_option('gvspace_contacts_page_seed_v1_lock');
        return;
    }

    foreach ($catalog as $locale => $content) {
        foreach (array_keys(gvspace_contacts_text_fields()) as $field) {
            update_post_meta($post_id, '_gvspace_contacts_' . $field . '_' . $locale, (string) $content[$field]);
        }
        update_post_meta($post_id, '_gvspace_contacts_channels_' . $locale, wp_slash(wp_json_encode($content['channels'], JSON_UNESCAPED_UNICODE)));
        update_post_meta($post_id, '_gvspace_contacts_offices_' . $locale, wp_slash(wp_json_encode($content['offices'], JSON_UNESCAPED_UNICODE)));
        update_post_meta($post_id, '_gvspace_contacts_socials_' . $locale, wp_slash(wp_json_encode($content['socials'], JSON_UNESCAPED_UNICODE)));
        update_post_meta($post_id, '_gvspace_seo_title_' . $locale, $content['seo_title']);
        update_post_meta($post_id, '_gvspace_seo_description_' . $locale, $content['seo_description']);
        update_post_meta($post_id, '_gvspace_seo_h1_' . $locale, $content['title']);
    }

    update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    update_post_meta($post_id, '_gvspace_translation_group', 'contacts-page');
    wp_update_post(['ID' => $post_id, 'post_title' => $catalog['uk']['title']]);

    update_option('gvspace_contacts_page_seeded_v1', '1', false);
    delete_option('gvspace_contacts_page_seed_v1_lock');
}
add_action('init', 'gvspace_seed_contacts_page', 24);

function gvspace_seed_contacts_page_v2(): void
{
    if (get_option('gvspace_contacts_page_seeded_v2') === '1') return;
    if (!add_option('gvspace_contacts_page_seed_v2_lock', time(), '', false)) return;

    $existing = get_posts([
        'post_type' => 'gv_contacts_page',
        'post_status' => 'any',
        'numberposts' => 1,
        'meta_key' => '_gvspace_translation_group',
        'meta_value' => 'contacts-page',
    ]);
    $post_id = $existing ? (int) $existing[0]->ID : 0;
    if ($post_id) {
        $catalog = gvspace_contacts_catalog();
        $current_uk = (string) get_post_meta($post_id, '_gvspace_contacts_presence_eyebrow_uk', true);
        $current_en = (string) get_post_meta($post_id, '_gvspace_contacts_presence_eyebrow_en', true);
        if ($current_uk === '' || $current_uk === 'ПРИСУТНІСТЬ') {
            update_post_meta($post_id, '_gvspace_contacts_presence_eyebrow_uk', $catalog['uk']['presence_eyebrow']);
        }
        if ($current_en === '' || $current_en === 'PRESENCE') {
            update_post_meta($post_id, '_gvspace_contacts_presence_eyebrow_en', $catalog['en']['presence_eyebrow']);
        }
    }

    update_option('gvspace_contacts_page_seeded_v2', '1', false);
    delete_option('gvspace_contacts_page_seed_v2_lock');
}
add_action('init', 'gvspace_seed_contacts_page_v2', 25);

add_action('init', function (): void {
    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        foreach (array_keys(gvspace_contacts_text_fields()) as $field) {
            register_post_meta('gv_contacts_page', '_gvspace_contacts_' . $field . '_' . $locale, [
                'type' => 'string', 'single' => true, 'show_in_rest' => true,
                'sanitize_callback' => 'sanitize_textarea_field',
                'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
            ]);
        }
        foreach (['channels', 'offices', 'socials'] as $list) {
            register_post_meta('gv_contacts_page', '_gvspace_contacts_' . $list . '_' . $locale, [
                'type' => 'string', 'single' => true, 'show_in_rest' => true,
                'sanitize_callback' => 'sanitize_textarea_field',
                'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
            ]);
        }
    }
}, 11);

add_action('add_meta_boxes', function (): void {
    add_meta_box('gvspace-contacts-page-content', 'Контент сторінки контактів', 'gvspace_render_contacts_page_fields', 'gv_contacts_page', 'normal', 'high');
});

function gvspace_render_contacts_page_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_contacts_page', 'gvspace_contacts_page_nonce');
    $stored_locale = gvspace_get_content_locale($post);
    $active_locale = array_key_exists($stored_locale, GVSPACE_CONTENT_LOCALES) ? $stored_locale : 'uk';
    echo '<p class="description"><strong>Одна сторінка контактів — один запис.</strong> Підпис «ЛОКАЦІЯ» — у блоці «Локація і карта» одразу під банером. Карту завантажте праворуч як «Карта локації». Якщо карти немає, на сайті буде заглушка. Оберіть мову та заповніть переклад.</p>';
    echo '<p><label for="gvspace-contacts-page-language"><strong>Редагувати мовну версію</strong></label> ';
    echo '<select id="gvspace-contacts-page-language" data-gvspace-language-select="contacts-page-language">';
    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        echo '<option value="' . esc_attr($locale) . '"' . selected($active_locale, $locale, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select></p>';

    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        $channels = gvspace_contacts_decode_list($post->ID, 'channels', $locale);
        $offices = gvspace_contacts_decode_list($post->ID, 'offices', $locale);
        $socials = gvspace_contacts_decode_list($post->ID, 'socials', $locale);
        while (count($channels) < 3) $channels[] = ['kind' => '', 'label' => '', 'value' => '', 'hint' => '', 'url' => ''];
        while (count($offices) < 3) $offices[] = ['title' => '', 'address' => '', 'phone' => '', 'email' => ''];
        while (count($socials) < 4) $socials[] = ['name' => '', 'handle' => '', 'url' => '', 'network' => ''];

        echo '<div data-gvspace-language-panel="contacts-page-language" data-locale="' . esc_attr($locale) . '"' . ($locale === $active_locale ? '' : ' hidden') . '>';
        echo '<hr><h3>' . esc_html($label) . '</h3>';
        echo '<h4>Банер</h4>';
        gvspace_render_field_set(
            $post,
            array_intersect_key(gvspace_contacts_text_fields(), array_flip(['eyebrow', 'title', 'intro', 'response'])),
            'gvspace_contacts_' . $locale . '_',
            '_gvspace_contacts_',
            '_' . $locale
        );

        echo '<h4>Локація і карта</h4>';
        gvspace_render_field_set(
            $post,
            array_intersect_key(gvspace_contacts_text_fields(), array_flip(['presence_eyebrow', 'presence_title', 'presence_text'])),
            'gvspace_contacts_' . $locale . '_',
            '_gvspace_contacts_',
            '_' . $locale
        );

        echo '<h4>Написати напряму</h4>';
        echo '<p><label><strong>Підпис лівої колонки</strong><br>';
        echo '<input type="text" name="gvspace_contacts_' . esc_attr($locale) . '_direct_label" value="' . esc_attr(gvspace_contacts_locale_field($post->ID, 'direct_label', $locale)) . '" style="width:100%"></label></p>';
        foreach ($channels as $index => $channel) {
            echo '<fieldset style="margin:12px 0;padding:12px;border:1px solid #dcdcde">';
            echo '<legend><strong>Картка ' . esc_html((string) ($index + 1)) . '</strong></legend>';
            echo '<p><label>Тип<br><select name="gvspace_contacts_channel_kind_' . esc_attr($locale) . '[]">';
            foreach (['telegram' => 'Telegram', 'email' => 'Email', 'phone' => 'Телефон'] as $kind => $kind_label) {
                echo '<option value="' . esc_attr($kind) . '"' . selected(($channel['kind'] ?? '') === $kind, true, false) . '>' . esc_html($kind_label) . '</option>';
            }
            echo '</select></label></p>';
            echo '<p><label>Підпис<br><input type="text" name="gvspace_contacts_channel_label_' . esc_attr($locale) . '[]" value="' . esc_attr((string) ($channel['label'] ?? '')) . '" style="width:100%"></label></p>';
            echo '<p><label>Значення<br><input type="text" name="gvspace_contacts_channel_value_' . esc_attr($locale) . '[]" value="' . esc_attr((string) ($channel['value'] ?? '')) . '" style="width:100%"></label></p>';
            echo '<p><label>Підказка<br><input type="text" name="gvspace_contacts_channel_hint_' . esc_attr($locale) . '[]" value="' . esc_attr((string) ($channel['hint'] ?? '')) . '" style="width:100%"></label></p>';
            echo '<p><label>Посилання<br><input type="url" name="gvspace_contacts_channel_url_' . esc_attr($locale) . '[]" value="' . esc_attr((string) ($channel['url'] ?? '')) . '" style="width:100%"></label></p>';
            echo '</fieldset>';
        }

        echo '<h4>Форма заявки</h4>';
        gvspace_render_field_set(
            $post,
            array_intersect_key(gvspace_contacts_text_fields(), array_flip(['form_label', 'name_placeholder', 'phone_placeholder', 'email_placeholder', 'topic_placeholder', 'message_placeholder', 'submit', 'consent'])),
            'gvspace_contacts_' . $locale . '_',
            '_gvspace_contacts_',
            '_' . $locale
        );

        echo '<h4>Офіси</h4>';
        foreach ($offices as $index => $office) {
            echo '<fieldset style="margin:12px 0;padding:12px;border:1px solid #dcdcde">';
            echo '<legend><strong>Офіс ' . esc_html((string) ($index + 1)) . '</strong></legend>';
            echo '<p><label>Місто / країна<br><input type="text" name="gvspace_contacts_office_title_' . esc_attr($locale) . '[]" value="' . esc_attr((string) ($office['title'] ?? '')) . '" style="width:100%"></label></p>';
            echo '<p><label>Адреса<br><textarea name="gvspace_contacts_office_address_' . esc_attr($locale) . '[]" rows="3" style="width:100%">' . esc_textarea((string) ($office['address'] ?? '')) . '</textarea></label></p>';
            echo '<p><label>Телефон<br><input type="text" name="gvspace_contacts_office_phone_' . esc_attr($locale) . '[]" value="' . esc_attr((string) ($office['phone'] ?? '')) . '" style="width:100%"></label></p>';
            echo '<p><label>Email<br><input type="text" name="gvspace_contacts_office_email_' . esc_attr($locale) . '[]" value="' . esc_attr((string) ($office['email'] ?? '')) . '" style="width:100%"></label></p>';
            echo '</fieldset>';
        }

        echo '<h4>Соцмережі</h4>';
        echo '<p><label><strong>Підпис блоку соцмереж</strong><br>';
        echo '<input type="text" name="gvspace_contacts_' . esc_attr($locale) . '_social_label" value="' . esc_attr(gvspace_contacts_locale_field($post->ID, 'social_label', $locale)) . '" style="width:100%"></label></p>';
        foreach ($socials as $index => $social) {
            echo '<fieldset style="margin:12px 0;padding:12px;border:1px solid #dcdcde">';
            echo '<legend><strong>Мережа ' . esc_html((string) ($index + 1)) . '</strong></legend>';
            echo '<p><label>Назва<br><input type="text" name="gvspace_contacts_social_name_' . esc_attr($locale) . '[]" value="' . esc_attr((string) ($social['name'] ?? '')) . '" style="width:100%"></label></p>';
            echo '<p><label>Підпис / нік<br><input type="text" name="gvspace_contacts_social_handle_' . esc_attr($locale) . '[]" value="' . esc_attr((string) ($social['handle'] ?? '')) . '" style="width:100%"></label></p>';
            echo '<p><label>Посилання<br><input type="url" name="gvspace_contacts_social_url_' . esc_attr($locale) . '[]" value="' . esc_attr((string) ($social['url'] ?? '')) . '" style="width:100%"></label></p>';
            echo '<p><label>Іконка<br><select name="gvspace_contacts_social_network_' . esc_attr($locale) . '[]">';
            foreach (['linkedin' => 'LinkedIn', 'instagram' => 'Instagram', 'facebook' => 'Facebook', 'telegram' => 'Telegram'] as $network => $network_label) {
                echo '<option value="' . esc_attr($network) . '"' . selected(($social['network'] ?? '') === $network, true, false) . '>' . esc_html($network_label) . '</option>';
            }
            echo '</select></label></p>';
            echo '</fieldset>';
        }
        echo '</div>';
    }
    gvspace_render_language_switcher_script();
}

add_action('save_post_gv_contacts_page', function (int $post_id): void {
    static $saving_title = false;
    if ($saving_title) return;
    if (
        !isset($_POST['gvspace_contacts_page_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_contacts_page_nonce'])), 'gvspace_save_contacts_page')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) return;

    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        foreach (array_keys(gvspace_contacts_text_fields()) as $field) {
            $key = 'gvspace_contacts_' . $locale . '_' . $field;
            if (!isset($_POST[$key])) continue;
            update_post_meta($post_id, '_gvspace_contacts_' . $field . '_' . $locale, sanitize_textarea_field(wp_unslash($_POST[$key])));
        }

        $channel_kinds = isset($_POST['gvspace_contacts_channel_kind_' . $locale]) ? (array) wp_unslash($_POST['gvspace_contacts_channel_kind_' . $locale]) : [];
        $channel_labels = isset($_POST['gvspace_contacts_channel_label_' . $locale]) ? (array) wp_unslash($_POST['gvspace_contacts_channel_label_' . $locale]) : [];
        $channel_values = isset($_POST['gvspace_contacts_channel_value_' . $locale]) ? (array) wp_unslash($_POST['gvspace_contacts_channel_value_' . $locale]) : [];
        $channel_hints = isset($_POST['gvspace_contacts_channel_hint_' . $locale]) ? (array) wp_unslash($_POST['gvspace_contacts_channel_hint_' . $locale]) : [];
        $channel_urls = isset($_POST['gvspace_contacts_channel_url_' . $locale]) ? (array) wp_unslash($_POST['gvspace_contacts_channel_url_' . $locale]) : [];
        $channels = [];
        $channel_count = max(count($channel_kinds), count($channel_labels), count($channel_values));
        for ($index = 0; $index < $channel_count; $index++) {
            $value = sanitize_text_field((string) ($channel_values[$index] ?? ''));
            $label = sanitize_text_field((string) ($channel_labels[$index] ?? ''));
            if ($value === '' && $label === '') continue;
            $kind = sanitize_key((string) ($channel_kinds[$index] ?? 'telegram'));
            if (!in_array($kind, ['telegram', 'email', 'phone'], true)) $kind = 'telegram';
            $channels[] = [
                'kind' => $kind,
                'label' => $label,
                'value' => $value,
                'hint' => sanitize_text_field((string) ($channel_hints[$index] ?? '')),
                'url' => esc_url_raw((string) ($channel_urls[$index] ?? '')),
            ];
        }
        update_post_meta($post_id, '_gvspace_contacts_channels_' . $locale, wp_slash(wp_json_encode($channels, JSON_UNESCAPED_UNICODE)));

        $office_titles = isset($_POST['gvspace_contacts_office_title_' . $locale]) ? (array) wp_unslash($_POST['gvspace_contacts_office_title_' . $locale]) : [];
        $office_addresses = isset($_POST['gvspace_contacts_office_address_' . $locale]) ? (array) wp_unslash($_POST['gvspace_contacts_office_address_' . $locale]) : [];
        $office_phones = isset($_POST['gvspace_contacts_office_phone_' . $locale]) ? (array) wp_unslash($_POST['gvspace_contacts_office_phone_' . $locale]) : [];
        $office_emails = isset($_POST['gvspace_contacts_office_email_' . $locale]) ? (array) wp_unslash($_POST['gvspace_contacts_office_email_' . $locale]) : [];
        $offices = [];
        $office_count = max(count($office_titles), count($office_addresses));
        for ($index = 0; $index < $office_count; $index++) {
            $title = sanitize_text_field((string) ($office_titles[$index] ?? ''));
            $address = sanitize_textarea_field((string) ($office_addresses[$index] ?? ''));
            if ($title === '' && $address === '') continue;
            $offices[] = [
                'title' => $title,
                'address' => $address,
                'phone' => sanitize_text_field((string) ($office_phones[$index] ?? '')),
                'email' => sanitize_text_field((string) ($office_emails[$index] ?? '')),
            ];
        }
        update_post_meta($post_id, '_gvspace_contacts_offices_' . $locale, wp_slash(wp_json_encode($offices, JSON_UNESCAPED_UNICODE)));

        $social_names = isset($_POST['gvspace_contacts_social_name_' . $locale]) ? (array) wp_unslash($_POST['gvspace_contacts_social_name_' . $locale]) : [];
        $social_handles = isset($_POST['gvspace_contacts_social_handle_' . $locale]) ? (array) wp_unslash($_POST['gvspace_contacts_social_handle_' . $locale]) : [];
        $social_urls = isset($_POST['gvspace_contacts_social_url_' . $locale]) ? (array) wp_unslash($_POST['gvspace_contacts_social_url_' . $locale]) : [];
        $social_networks = isset($_POST['gvspace_contacts_social_network_' . $locale]) ? (array) wp_unslash($_POST['gvspace_contacts_social_network_' . $locale]) : [];
        $socials = [];
        $social_count = max(count($social_names), count($social_handles));
        for ($index = 0; $index < $social_count; $index++) {
            $name = sanitize_text_field((string) ($social_names[$index] ?? ''));
            if ($name === '') continue;
            $network = sanitize_key((string) ($social_networks[$index] ?? 'linkedin'));
            if (!in_array($network, ['linkedin', 'instagram', 'facebook', 'telegram'], true)) $network = 'linkedin';
            $socials[] = [
                'name' => $name,
                'handle' => sanitize_text_field((string) ($social_handles[$index] ?? '')),
                'url' => esc_url_raw((string) ($social_urls[$index] ?? '')),
                'network' => $network,
            ];
        }
        update_post_meta($post_id, '_gvspace_contacts_socials_' . $locale, wp_slash(wp_json_encode($socials, JSON_UNESCAPED_UNICODE)));
    }

    update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    if ((string) get_post_meta($post_id, '_gvspace_translation_group', true) === '') {
        update_post_meta($post_id, '_gvspace_translation_group', 'contacts-page');
    }

    $uk_title = (string) get_post_meta($post_id, '_gvspace_contacts_title_uk', true);
    if ($uk_title !== '' && get_post_field('post_title', $post_id) !== $uk_title) {
        $saving_title = true;
        wp_update_post(['ID' => $post_id, 'post_title' => $uk_title]);
        $saving_title = false;
    }
});

add_action('graphql_register_types', function (): void {
    register_graphql_object_type('GvspaceContactsChannel', [
        'fields' => [
            'kind' => ['type' => 'String'],
            'label' => ['type' => 'String'],
            'value' => ['type' => 'String'],
            'hint' => ['type' => 'String'],
            'url' => ['type' => 'String'],
        ],
    ]);
    register_graphql_object_type('GvspaceContactsOffice', [
        'fields' => [
            'title' => ['type' => 'String'],
            'address' => ['type' => 'String'],
            'phone' => ['type' => 'String'],
            'email' => ['type' => 'String'],
        ],
    ]);
    register_graphql_object_type('GvspaceContactsSocial', [
        'fields' => [
            'name' => ['type' => 'String'],
            'handle' => ['type' => 'String'],
            'url' => ['type' => 'String'],
            'network' => ['type' => 'String'],
        ],
    ]);
    register_graphql_object_type('GvspaceContactsPageDetails', [
        'fields' => [
            'eyebrow' => ['type' => 'String'],
            'title' => ['type' => 'String'],
            'intro' => ['type' => 'String'],
            'response' => ['type' => 'String'],
            'directLabel' => ['type' => 'String'],
            'formLabel' => ['type' => 'String'],
            'namePlaceholder' => ['type' => 'String'],
            'phonePlaceholder' => ['type' => 'String'],
            'emailPlaceholder' => ['type' => 'String'],
            'topicPlaceholder' => ['type' => 'String'],
            'messagePlaceholder' => ['type' => 'String'],
            'submit' => ['type' => 'String'],
            'consent' => ['type' => 'String'],
            'presenceEyebrow' => ['type' => 'String'],
            'presenceTitle' => ['type' => 'String'],
            'presenceText' => ['type' => 'String'],
            'socialLabel' => ['type' => 'String'],
            'channels' => ['type' => ['list_of' => 'GvspaceContactsChannel']],
            'offices' => ['type' => ['list_of' => 'GvspaceContactsOffice']],
            'socials' => ['type' => ['list_of' => 'GvspaceContactsSocial']],
        ],
    ]);
    register_graphql_field('ContactsPage', 'contactsPageDetails', [
        'type' => 'GvspaceContactsPageDetails',
        'args' => ['locale' => ['type' => 'String', 'defaultValue' => 'uk']],
        'resolve' => static function ($source, array $args): array {
            $post_id = (int) $source->databaseId;
            $requested_locale = gvspace_sanitize_content_locale((string) ($args['locale'] ?? 'uk'));
            $locale = $requested_locale === 'legacy' ? 'uk' : $requested_locale;
            $read = static fn (string $field): string => gvspace_contacts_locale_field($post_id, $field, $locale);
            return [
                'eyebrow' => $read('eyebrow'),
                'title' => $read('title') ?: (string) get_the_title($post_id),
                'intro' => $read('intro'),
                'response' => $read('response'),
                'directLabel' => $read('direct_label'),
                'formLabel' => $read('form_label'),
                'namePlaceholder' => $read('name_placeholder'),
                'phonePlaceholder' => $read('phone_placeholder'),
                'emailPlaceholder' => $read('email_placeholder'),
                'topicPlaceholder' => $read('topic_placeholder'),
                'messagePlaceholder' => $read('message_placeholder'),
                'submit' => $read('submit'),
                'consent' => $read('consent'),
                'presenceEyebrow' => $read('presence_eyebrow'),
                'presenceTitle' => $read('presence_title'),
                'presenceText' => $read('presence_text'),
                'socialLabel' => $read('social_label'),
                'channels' => gvspace_contacts_decode_list($post_id, 'channels', $locale),
                'offices' => gvspace_contacts_decode_list($post_id, 'offices', $locale),
                'socials' => gvspace_contacts_decode_list($post_id, 'socials', $locale),
            ];
        },
    ]);
});
