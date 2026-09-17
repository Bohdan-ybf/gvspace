<?php

if (!defined('ABSPATH')) {
    exit;
}

function gvspace_terms_of_use_catalog(): array
{
    return [
        'uk' => [
            'kicker' => 'LEGAL',
            'title' => 'Правила використання сайту',
            'dates' => 'Дата набрання чинності: [ДД.ММ.РРРР] · Остання редакція: [ДД.ММ.РРРР]',
            'contents' => 'зміст',
            'seo_title' => 'Правила використання сайту',
            'seo_description' => 'Правила використання вебсайту GVSPACE: доступ до сайту, інтелектуальна власність, відповідальність і контакти.',
            'sections' => [
                ['01', 'Загальні положення', "Ці Правила використання регулюють доступ та використання вебсайту GVSPACE (далі — «Сайт»), що знаходиться за адресою [gvspace.com].\n\nОтримуючи доступ до Сайту або використовуючи його, ви погоджуєтесь з цими Правилами.\n\nВласник Сайту: [Повна назва юридичної особи або ФОП], реєстраційний номер [ЄДРПОУ/ІПН], юридична адреса: [адреса]."],
                ['02', 'Використання сайту', "Ви зобов’язуєтесь використовувати Сайт виключно в законних цілях та у спосіб, що не порушує права третіх осіб.\n\nЗабороняється:\n— Використовувати Сайт з метою поширення незаконного або шкідливого контенту.\n— Здійснювати спроби несанкціонованого доступу до систем Сайту.\n— Копіювати, відтворювати або поширювати матеріали Сайту без письмового дозволу.\n— Використовувати автоматизовані засоби для збору даних (парсинг, скрейпінг).\n\nМи залишаємо за собою право обмежити або припинити доступ до Сайту будь-якому користувачу без попереднього повідомлення."],
                ['03', 'Інтелектуальна власність', "Усі матеріали, розміщені на Сайті — тексти, зображення, логотипи, графіка, код — є власністю GVSPACE або використовуються на підставі ліцензій.\n\nНазва «GVSPACE», логотип та фірмовий стиль є об’єктами інтелектуальної власності та охороняються відповідно до чинного законодавства України."],
                ['04', 'Відповідальність', "Сайт надається «як є». Ми не гарантуємо безперебійну роботу Сайту та не несемо відповідальності за:\n— Технічні збої або тимчасову недоступність Сайту.\n— Будь-які прямі чи непрямі збитки, пов’язані з використанням Сайту.\n— Точність, повноту або актуальність інформації, розміщеної на Сайті."],
                ['05', 'Посилання на сторонні ресурси', "Сайт може містити посилання на зовнішні вебсайти. GVSPACE не несе відповідальності за зміст, точність або доступність сторонніх ресурсів.\n\nПерехід на зовнішні сайти здійснюється на розсуд та відповідальність користувача."],
                ['06', 'Зміни до правил', "Ми залишаємо за собою право вносити зміни до цих Правил у будь-який час. Актуальна версія завжди доступна на цій сторінці.\n\nПродовження використання Сайту після публікації змін означає вашу згоду з оновленими Правилами."],
                ['07', 'Контакти', "З питань щодо обробки персональних даних звертайтесь:\n\nEmail: [legal@gvspace.com]\n\nАдреса: [юридична адреса]"],
            ],
        ],
        'en' => [
            'kicker' => 'LEGAL',
            'title' => 'Website Terms of Use',
            'dates' => 'Effective date: [DD.MM.YYYY] · Last revised: [DD.MM.YYYY]',
            'contents' => 'contents',
            'seo_title' => 'Website Terms of Use',
            'seo_description' => 'Terms of use for the GVSPACE website: access, intellectual property, liability and contact details.',
            'sections' => [
                ['01', 'General provisions', "These Terms of Use govern access to and use of the GVSPACE website (the “Website”), available at [gvspace.com].\n\nBy accessing or using the Website, you agree to these Terms.\n\nWebsite owner: [Full legal entity or sole proprietor name], registration number [registration/tax number], registered address: [address]."],
                ['02', 'Use of the website', "You agree to use the Website only for lawful purposes and in a way that does not infringe the rights of third parties.\n\nYou must not:\n— Use the Website to distribute illegal or harmful content.\n— Attempt to gain unauthorised access to Website systems.\n— Copy, reproduce, or distribute Website materials without written permission.\n— Use automated means to collect data, including parsing or scraping.\n\nWe reserve the right to restrict or terminate any user’s access to the Website without prior notice."],
                ['03', 'Intellectual property', "All materials on the Website, including text, images, logos, graphics, and code, are owned by GVSPACE or used under licence.\n\nThe GVSPACE name, logo, and visual identity are protected intellectual property under applicable Ukrainian law."],
                ['04', 'Liability', "The Website is provided “as is”. We do not guarantee uninterrupted operation and are not liable for:\n— Technical failures or temporary unavailability of the Website.\n— Any direct or indirect loss connected with use of the Website.\n— The accuracy, completeness, or currency of information published on the Website."],
                ['05', 'Links to third-party resources', "The Website may contain links to external websites. GVSPACE is not responsible for the content, accuracy, or availability of third-party resources.\n\nYou access external websites at your own discretion and risk."],
                ['06', 'Changes to these terms', "We reserve the right to change these Terms at any time. The current version is always available on this page.\n\nContinued use of the Website after changes are published constitutes acceptance of the updated Terms."],
                ['07', 'Contact us', "For questions about personal data processing, contact us at:\n\nEmail: [legal@gvspace.com]\n\nAddress: [registered address]"],
            ],
        ],
    ];
}

function gvspace_decode_terms_sections(int $post_id, string $locale): array
{
    $raw = (string) get_post_meta($post_id, '_gvspace_terms_sections_' . $locale, true);
    $decoded = json_decode($raw, true);
    if (!is_array($decoded)) return [];
    return gvspace_normalize_legal_sections($decoded);
}

function gvspace_seed_terms_of_use(): void
{
    if (get_option('gvspace_terms_of_use_seeded_v1') === '1') return;
    $lock_time = (int) get_option('gvspace_terms_of_use_seed_v1_lock', 0);
    if ($lock_time) delete_option('gvspace_terms_of_use_seed_v1_lock');
    if (!add_option('gvspace_terms_of_use_seed_v1_lock', time(), '', false)) return;

    $existing = get_posts([
        'post_type' => 'gv_terms_of_use',
        'post_status' => 'any',
        'numberposts' => 1,
        'meta_key' => '_gvspace_translation_group',
        'meta_value' => 'terms-of-use',
    ]);
    $post_id = $existing ? (int) $existing[0]->ID : 0;
    $catalog = gvspace_terms_of_use_catalog();

    if (!$post_id) {
        $post_id = wp_insert_post([
            'post_type' => 'gv_terms_of_use',
            'post_status' => 'publish',
            'post_title' => $catalog['uk']['title'],
            'post_name' => 'terms-of-use',
        ]);
    }
    if (!$post_id || is_wp_error($post_id)) {
        delete_option('gvspace_terms_of_use_seed_v1_lock');
        return;
    }

    foreach ($catalog as $locale => $content) {
        update_post_meta($post_id, '_gvspace_terms_kicker_' . $locale, $content['kicker']);
        update_post_meta($post_id, '_gvspace_terms_title_' . $locale, $content['title']);
        update_post_meta($post_id, '_gvspace_terms_dates_' . $locale, $content['dates']);
        update_post_meta($post_id, '_gvspace_terms_contents_' . $locale, $content['contents']);
        $sections = [];
        foreach ($content['sections'] as [$number, $title, $body]) {
            $sections[] = ['number' => $number, 'title' => $title, 'body' => $body];
        }
        gvspace_update_legal_sections_meta($post_id, '_gvspace_terms_sections_' . $locale, $sections);
        update_post_meta($post_id, '_gvspace_seo_title_' . $locale, $content['seo_title']);
        update_post_meta($post_id, '_gvspace_seo_description_' . $locale, $content['seo_description']);
        update_post_meta($post_id, '_gvspace_seo_h1_' . $locale, $content['title']);
    }

    update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    update_post_meta($post_id, '_gvspace_translation_group', 'terms-of-use');
    wp_update_post(['ID' => $post_id, 'post_title' => $catalog['uk']['title']]);

    update_option('gvspace_terms_of_use_seeded_v1', '1', false);
    delete_option('gvspace_terms_of_use_seed_v1_lock');
}
add_action('init', 'gvspace_seed_terms_of_use', 24);

add_action('init', function (): void {
    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        foreach (['kicker', 'title', 'dates', 'contents'] as $field) {
            register_post_meta('gv_terms_of_use', '_gvspace_terms_' . $field . '_' . $locale, [
                'type' => 'string', 'single' => true, 'show_in_rest' => true,
                'sanitize_callback' => 'sanitize_textarea_field',
                'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
            ]);
        }
        register_post_meta('gv_terms_of_use', '_gvspace_terms_sections_' . $locale, [
            'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'sanitize_callback' => 'gvspace_sanitize_legal_sections_meta',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
    }
}, 11);

add_action('add_meta_boxes', function (): void {
    add_meta_box('gvspace-terms-of-use-content', 'Контент правил використання', 'gvspace_render_terms_of_use_fields', 'gv_terms_of_use', 'normal', 'high');
});

function gvspace_render_terms_of_use_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_terms_of_use', 'gvspace_terms_of_use_nonce');
    $stored_locale = gvspace_get_content_locale($post);
    $active_locale = array_key_exists($stored_locale, GVSPACE_CONTENT_LOCALES) ? $stored_locale : 'uk';
    echo '<p class="description"><strong>Одні правила — один запис.</strong> Оберіть мову та заповніть її переклад. Перемикання мови не перезавантажує сторінку й не видаляє введений текст.</p>';
    echo '<p><label for="gvspace-terms-of-use-language"><strong>Редагувати мовну версію</strong></label> ';
    echo '<select id="gvspace-terms-of-use-language" data-gvspace-language-select="terms-of-use-language">';
    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        echo '<option value="' . esc_attr($locale) . '"' . selected($active_locale, $locale, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select></p>';

    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        $kicker = (string) get_post_meta($post->ID, '_gvspace_terms_kicker_' . $locale, true);
        $title = (string) get_post_meta($post->ID, '_gvspace_terms_title_' . $locale, true);
        $dates = (string) get_post_meta($post->ID, '_gvspace_terms_dates_' . $locale, true);
        $contents = (string) get_post_meta($post->ID, '_gvspace_terms_contents_' . $locale, true);
        $sections = gvspace_decode_terms_sections($post->ID, $locale);
        if ($sections === []) $sections = array_fill(0, 7, ['number' => '', 'title' => '', 'body' => '']);
        echo '<div data-gvspace-language-panel="terms-of-use-language" data-locale="' . esc_attr($locale) . '"' . ($locale === $active_locale ? '' : ' hidden') . '>';
        echo '<hr><h3>' . esc_html($label) . '</h3>';
        echo '<p><label><strong>Бейдж</strong><br><input type="text" name="gvspace_terms_kicker_' . esc_attr($locale) . '" value="' . esc_attr($kicker) . '" style="width:100%"></label></p>';
        echo '<p><label><strong>Заголовок сторінки</strong><br><input type="text" name="gvspace_terms_title_' . esc_attr($locale) . '" value="' . esc_attr($title) . '" style="width:100%"></label></p>';
        echo '<p><label><strong>Дати</strong><br><input type="text" name="gvspace_terms_dates_' . esc_attr($locale) . '" value="' . esc_attr($dates) . '" style="width:100%"></label></p>';
        echo '<p><label><strong>Заголовок змісту</strong><br><input type="text" name="gvspace_terms_contents_' . esc_attr($locale) . '" value="' . esc_attr($contents) . '" style="width:100%"></label></p>';
        foreach ($sections as $index => $section) {
            echo '<fieldset style="margin:16px 0;padding:12px;border:1px solid #dcdcde">';
            echo '<legend><strong>Розділ ' . esc_html((string) ($index + 1)) . '</strong></legend>';
            echo '<p><label>Номер<br><input type="text" name="gvspace_terms_section_number_' . esc_attr($locale) . '[]" value="' . esc_attr($section['number']) . '" style="width:120px"></label></p>';
            echo '<p><label>Назва<br><input type="text" name="gvspace_terms_section_title_' . esc_attr($locale) . '[]" value="' . esc_attr($section['title']) . '" style="width:100%"></label></p>';
            echo '<p><label>Текст<br><textarea name="gvspace_terms_section_body_' . esc_attr($locale) . '[]" rows="8" style="width:100%">' . esc_textarea($section['body']) . '</textarea></label></p>';
            echo '</fieldset>';
        }
        echo '</div>';
    }
    gvspace_render_language_switcher_script();
}

add_action('save_post_gv_terms_of_use', function (int $post_id): void {
    static $saving_title = false;
    if ($saving_title) return;
    if (
        !isset($_POST['gvspace_terms_of_use_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_terms_of_use_nonce'])), 'gvspace_save_terms_of_use')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) return;

    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        foreach (['kicker', 'title', 'dates', 'contents'] as $field) {
            $key = 'gvspace_terms_' . $field . '_' . $locale;
            if (!isset($_POST[$key])) continue;
            update_post_meta($post_id, '_gvspace_terms_' . $field . '_' . $locale, sanitize_text_field(wp_unslash($_POST[$key])));
        }
        $numbers = isset($_POST['gvspace_terms_section_number_' . $locale]) ? (array) wp_unslash($_POST['gvspace_terms_section_number_' . $locale]) : [];
        $titles = isset($_POST['gvspace_terms_section_title_' . $locale]) ? (array) wp_unslash($_POST['gvspace_terms_section_title_' . $locale]) : [];
        $bodies = isset($_POST['gvspace_terms_section_body_' . $locale]) ? (array) wp_unslash($_POST['gvspace_terms_section_body_' . $locale]) : [];
        $sections = [];
        $count = max(count($numbers), count($titles), count($bodies));
        for ($index = 0; $index < $count; $index++) {
            $title = sanitize_text_field((string) ($titles[$index] ?? ''));
            $body = sanitize_textarea_field((string) ($bodies[$index] ?? ''));
            $number = sanitize_text_field((string) ($numbers[$index] ?? ''));
            if ($title === '' && $body === '') continue;
            $sections[] = ['number' => $number, 'title' => $title, 'body' => $body];
        }
        gvspace_update_legal_sections_meta($post_id, '_gvspace_terms_sections_' . $locale, $sections);
    }

    update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    if ((string) get_post_meta($post_id, '_gvspace_translation_group', true) === '') {
        update_post_meta($post_id, '_gvspace_translation_group', 'terms-of-use');
    }

    $uk_title = (string) get_post_meta($post_id, '_gvspace_terms_title_uk', true);
    if ($uk_title !== '' && get_post_field('post_title', $post_id) !== $uk_title) {
        $saving_title = true;
        wp_update_post(['ID' => $post_id, 'post_title' => $uk_title]);
        $saving_title = false;
    }
});

add_action('graphql_register_types', function (): void {
    register_graphql_object_type('GvspaceTermsSection', [
        'fields' => [
            'number' => ['type' => 'String'],
            'title' => ['type' => 'String'],
            'body' => ['type' => 'String'],
        ],
    ]);
    register_graphql_object_type('GvspaceTermsOfUseDetails', [
        'fields' => [
            'kicker' => ['type' => 'String'],
            'title' => ['type' => 'String'],
            'dates' => ['type' => 'String'],
            'contents' => ['type' => 'String'],
            'sections' => ['type' => ['list_of' => 'GvspaceTermsSection']],
        ],
    ]);
    register_graphql_field('TermsOfUse', 'termsOfUseDetails', [
        'type' => 'GvspaceTermsOfUseDetails',
        'args' => ['locale' => ['type' => 'String', 'defaultValue' => 'uk']],
        'resolve' => static function ($source, array $args): array {
            $post_id = (int) $source->databaseId;
            $requested_locale = gvspace_sanitize_content_locale((string) ($args['locale'] ?? 'uk'));
            $locale = $requested_locale === 'legacy' ? 'uk' : $requested_locale;
            $title = (string) get_post_meta($post_id, '_gvspace_terms_title_' . $locale, true);
            $kicker = (string) get_post_meta($post_id, '_gvspace_terms_kicker_' . $locale, true);
            $dates = (string) get_post_meta($post_id, '_gvspace_terms_dates_' . $locale, true);
            $contents = (string) get_post_meta($post_id, '_gvspace_terms_contents_' . $locale, true);
            $sections = gvspace_decode_terms_sections($post_id, $locale);
            if ($title === '') $title = (string) get_post_meta($post_id, '_gvspace_terms_title_uk', true);
            if ($kicker === '') $kicker = (string) get_post_meta($post_id, '_gvspace_terms_kicker_uk', true);
            if ($dates === '') $dates = (string) get_post_meta($post_id, '_gvspace_terms_dates_uk', true);
            if ($contents === '') $contents = (string) get_post_meta($post_id, '_gvspace_terms_contents_uk', true);
            if ($sections === []) $sections = gvspace_decode_terms_sections($post_id, 'uk');
            if ($title === '') $title = (string) get_the_title($post_id);
            return compact('kicker', 'title', 'dates', 'contents', 'sections');
        },
    ]);
});
