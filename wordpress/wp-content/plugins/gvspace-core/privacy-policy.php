<?php

if (!defined('ABSPATH')) {
    exit;
}

function gvspace_privacy_policy_catalog(): array
{
    return [
        'uk' => [
            'kicker' => 'LEGAL',
            'title' => 'Політика конфіденційності',
            'dates' => 'Дата набрання чинності: [ДД.ММ.РРРР] · Остання редакція: [ДД.ММ.РРРР]',
            'contents' => 'зміст',
            'seo_title' => 'Політика конфіденційності',
            'seo_description' => 'Як GVSPACE збирає, використовує та захищає персональні дані користувачів сайту.',
            'sections' => [
                ['01', 'Загальні положення', "Ця Політика конфіденційності описує, як [Назва компанії] (далі — «GVSPACE», «ми», «нас») збирає, використовує та захищає персональні дані користувачів вебсайту [gvspace.com] (далі — «Сайт»).\n\nВикористовуючи Сайт або заповнюючи будь-які форми на ньому, ви погоджуєтесь з умовами цієї Політики.\n\nМи обробляємо персональні дані відповідно до Закону України «Про захист персональних даних» та Регламенту GDPR для користувачів з ЄС."],
                ['02', 'Які дані ми збираємо', "Дані, які ви надаєте добровільно:\n— Ім'я та прізвище при заповненні форм.\n— Контактні дані: email, номер телефону, Telegram.\n— Інформація про ваш бізнес або проєкт, яку ви вказуєте у формах.\n— Резюме та супровідні матеріали при відгуку на вакансії.\n\nДані, що збираються автоматично:\n— IP-адреса та технічні дані пристрою та браузера.\n— Дані про поведінку на Сайті через Google Analytics 4.\n— Cookies та аналогічні технології відстеження."],
                ['03', 'Як ми використовуємо дані', "Зібрані дані використовуються виключно для:\n— Обробки та відповіді на ваші заявки і запити.\n— Надання інформації про послуги GVSPACE.\n— Покращення якості Сайту та користувацького досвіду.\n— Аналізу ефективності маркетингових кампаній.\n— Розгляду заявок на вакансії.\n\nМи не використовуємо ваші дані для автоматизованого прийняття рішень без вашої явної згоди."],
                ['04', 'Передача даних третім особам', "Ми не продаємо та не передаємо ваші персональні дані третім особам, за винятком випадків, необхідних для роботи Сайту:\n— Сервіси аналітики: Google Analytics, Looker Studio.\n— Рекламні платформи: Meta Pixel, Google Ads.\n— CRM та інструменти комунікації для обробки вашого запиту.\n\nМи можемо розкривати дані на законну вимогу державних органів відповідно до чинного законодавства."],
                ['05', 'Cookies та аналітика', "Сайт використовує cookies для забезпечення коректної роботи та аналітики.\n— Технічні cookies — необхідні для роботи Сайту.\n— Аналітичні cookies — Google Analytics 4 для аналізу відвідуваності.\n— Маркетингові cookies — Meta Pixel, Google Ads для оцінки ефективності реклами.\n\nВи можете керувати налаштуваннями cookies через банер при першому відвідуванні Сайту."],
                ['06', 'Зберігання та захист даних', "Дані заявок та звернень зберігаються не довше [24 місяців] з моменту останнього контакту. Резюме кандидатів — не довше [12 місяців].\n\nМи застосовуємо технічні та організаційні заходи захисту даних, включаючи шифрування передачі даних (SSL/TLS)."],
                ['07', 'Ваші права', "Відповідно до чинного законодавства ви маєте право:\n— Отримати доступ до персональних даних, які ми обробляємо.\n— Вимагати виправлення неточних або застарілих даних.\n— Вимагати видалення ваших персональних даних («право на забуття»).\n— Відкликати згоду на обробку даних у будь-який час.\n— Отримати копію ваших даних у машинозчитуваному форматі.\n\nДля реалізації будь-якого з цих прав надішліть запит на [legal@gvspace.com]. Ми відповімо протягом 30 календарних днів."],
                ['08', 'Зміни до політики', "Ми можемо час від часу оновлювати цю Політику. Актуальна версія завжди доступна на цій сторінці із зазначенням дати останньої редакції.\n\nЯкщо зміни є суттєвими, ми повідомимо вас через email або повідомлення на Сайті."],
                ['09', 'Контакти', "З питань щодо обробки персональних даних звертайтесь:\nEmail: [legal@gvspace.com]\nАдреса: [юридична адреса]"],
            ],
        ],
        'en' => [
            'kicker' => 'LEGAL',
            'title' => 'Privacy Policy',
            'dates' => 'Effective date: [DD.MM.YYYY] · Last revised: [DD.MM.YYYY]',
            'contents' => 'contents',
            'seo_title' => 'Privacy Policy',
            'seo_description' => 'How GVSPACE collects, uses and protects personal data of website users.',
            'sections' => [
                ['01', 'General provisions', "This Privacy Policy describes how [Company name] (hereinafter — “GVSPACE”, “we”, “us”) collects, uses and protects the personal data of users of the website [gvspace.com] (hereinafter — the “Website”).\n\nBy using the Website or submitting any forms on it, you agree to this Policy.\n\nWe process personal data in accordance with the Law of Ukraine “On Personal Data Protection” and the GDPR for users in the EU."],
                ['02', 'What data we collect', "Data you provide voluntarily:\n— First and last name when completing forms.\n— Contact details: email, phone number, Telegram.\n— Information about your business or project that you enter in forms.\n— CVs and supporting materials when applying for vacancies.\n\nData collected automatically:\n— IP address and technical data about the device and browser.\n— Behavioural data on the Website via Google Analytics 4.\n— Cookies and similar tracking technologies."],
                ['03', 'How we use data', "The collected data is used solely to:\n— Process and respond to your applications and enquiries.\n— Provide information about GVSPACE services.\n— Improve the Website and user experience.\n— Analyse the performance of marketing campaigns.\n— Review job applications.\n\nWe do not use your data for automated decision-making without your explicit consent."],
                ['04', 'Sharing data with third parties', "We do not sell or share your personal data with third parties, except where this is required to operate the Website:\n— Analytics services: Google Analytics, Looker Studio.\n— Advertising platforms: Meta Pixel, Google Ads.\n— CRM and communication tools used to handle your enquiry.\n\nWe may disclose data in response to a lawful request from public authorities under applicable law."],
                ['05', 'Cookies and analytics', "The Website uses cookies to operate correctly and for analytics.\n— Essential cookies — required for the Website to work.\n— Analytics cookies — Google Analytics 4 to measure visits.\n— Marketing cookies — Meta Pixel and Google Ads to measure advertising performance.\n\nYou can manage cookie settings through the banner shown on your first visit."],
                ['06', 'Data storage and protection', "Enquiry and request data is stored for no longer than [24 months] from the last contact. Candidate CVs are stored for no longer than [12 months].\n\nWe apply technical and organisational safeguards, including SSL/TLS encryption for data in transit."],
                ['07', 'Your rights', "Under applicable law you have the right to:\n— Access the personal data we process.\n— Request correction of inaccurate or outdated data.\n— Request deletion of your personal data (the “right to be forgotten”).\n— Withdraw consent to processing at any time.\n— Receive a copy of your data in a machine-readable format.\n\nTo exercise any of these rights, send a request to [legal@gvspace.com]. We will respond within 30 calendar days."],
                ['08', 'Changes to this policy', "We may update this Policy from time to time. The current version is always available on this page, with the latest revision date shown.\n\nIf changes are material, we will notify you by email or through a notice on the Website."],
                ['09', 'Contact us', "For questions about personal data processing, contact us at:\nEmail: [legal@gvspace.com]\nAddress: [registered address]"],
            ],
        ],
    ];
}

function gvspace_decode_privacy_sections(int $post_id, string $locale): array
{
    $raw = (string) get_post_meta($post_id, '_gvspace_privacy_sections_' . $locale, true);
    $decoded = json_decode($raw, true);
    if (!is_array($decoded)) return [];
    return gvspace_normalize_legal_sections($decoded);
}

function gvspace_seed_privacy_policy(): void
{
    if (get_option('gvspace_privacy_policy_seeded_v1') === '1') return;
    $lock_time = (int) get_option('gvspace_privacy_policy_seed_v1_lock', 0);
    if ($lock_time) delete_option('gvspace_privacy_policy_seed_v1_lock');
    if (!add_option('gvspace_privacy_policy_seed_v1_lock', time(), '', false)) return;

    $existing = get_posts([
        'post_type' => 'gv_privacy_policy',
        'post_status' => 'any',
        'numberposts' => 1,
        'meta_key' => '_gvspace_translation_group',
        'meta_value' => 'privacy-policy',
    ]);
    $post_id = $existing ? (int) $existing[0]->ID : 0;
    $catalog = gvspace_privacy_policy_catalog();

    if (!$post_id) {
        $post_id = wp_insert_post([
            'post_type' => 'gv_privacy_policy',
            'post_status' => 'publish',
            'post_title' => $catalog['uk']['title'],
            'post_name' => 'privacy-policy',
        ]);
    }
    if (!$post_id || is_wp_error($post_id)) {
        delete_option('gvspace_privacy_policy_seed_v1_lock');
        return;
    }

    foreach ($catalog as $locale => $content) {
        update_post_meta($post_id, '_gvspace_privacy_kicker_' . $locale, $content['kicker']);
        update_post_meta($post_id, '_gvspace_privacy_title_' . $locale, $content['title']);
        update_post_meta($post_id, '_gvspace_privacy_dates_' . $locale, $content['dates']);
        update_post_meta($post_id, '_gvspace_privacy_contents_' . $locale, $content['contents']);
        $sections = [];
        foreach ($content['sections'] as [$number, $title, $body]) {
            $sections[] = ['number' => $number, 'title' => $title, 'body' => $body];
        }
        gvspace_update_legal_sections_meta($post_id, '_gvspace_privacy_sections_' . $locale, $sections);
        update_post_meta($post_id, '_gvspace_seo_title_' . $locale, $content['seo_title']);
        update_post_meta($post_id, '_gvspace_seo_description_' . $locale, $content['seo_description']);
        update_post_meta($post_id, '_gvspace_seo_h1_' . $locale, $content['title']);
    }

    update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    update_post_meta($post_id, '_gvspace_translation_group', 'privacy-policy');
    wp_update_post(['ID' => $post_id, 'post_title' => $catalog['uk']['title']]);

    update_option('gvspace_privacy_policy_seeded_v1', '1', false);
    delete_option('gvspace_privacy_policy_seed_v1_lock');
}
add_action('init', 'gvspace_seed_privacy_policy', 24);

add_action('init', function (): void {
    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        foreach (['kicker', 'title', 'dates', 'contents'] as $field) {
            register_post_meta('gv_privacy_policy', '_gvspace_privacy_' . $field . '_' . $locale, [
                'type' => 'string', 'single' => true, 'show_in_rest' => true,
                'sanitize_callback' => 'sanitize_textarea_field',
                'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
            ]);
        }
        register_post_meta('gv_privacy_policy', '_gvspace_privacy_sections_' . $locale, [
            'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'sanitize_callback' => 'gvspace_sanitize_legal_sections_meta',
            'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);
    }
}, 11);

add_action('add_meta_boxes', function (): void {
    add_meta_box('gvspace-privacy-policy-content', 'Контент політики конфіденційності', 'gvspace_render_privacy_policy_fields', 'gv_privacy_policy', 'normal', 'high');
});

function gvspace_render_privacy_policy_fields(WP_Post $post): void
{
    wp_nonce_field('gvspace_save_privacy_policy', 'gvspace_privacy_policy_nonce');
    $stored_locale = gvspace_get_content_locale($post);
    $active_locale = array_key_exists($stored_locale, GVSPACE_CONTENT_LOCALES) ? $stored_locale : 'uk';
    echo '<p class="description"><strong>Одна політика — один запис.</strong> Оберіть мову та заповніть її переклад. Перемикання мови не перезавантажує сторінку й не видаляє введений текст.</p>';
    echo '<p><label for="gvspace-privacy-policy-language"><strong>Редагувати мовну версію</strong></label> ';
    echo '<select id="gvspace-privacy-policy-language" data-gvspace-language-select="privacy-policy-language">';
    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        echo '<option value="' . esc_attr($locale) . '"' . selected($active_locale, $locale, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select></p>';

    foreach (GVSPACE_CONTENT_LOCALES as $locale => $label) {
        $kicker = (string) get_post_meta($post->ID, '_gvspace_privacy_kicker_' . $locale, true);
        $title = (string) get_post_meta($post->ID, '_gvspace_privacy_title_' . $locale, true);
        $dates = (string) get_post_meta($post->ID, '_gvspace_privacy_dates_' . $locale, true);
        $contents = (string) get_post_meta($post->ID, '_gvspace_privacy_contents_' . $locale, true);
        $sections = gvspace_decode_privacy_sections($post->ID, $locale);
        if ($sections === []) $sections = array_fill(0, 9, ['number' => '', 'title' => '', 'body' => '']);
        echo '<div data-gvspace-language-panel="privacy-policy-language" data-locale="' . esc_attr($locale) . '"' . ($locale === $active_locale ? '' : ' hidden') . '>';
        echo '<hr><h3>' . esc_html($label) . '</h3>';
        echo '<p><label><strong>Бейдж</strong><br><input type="text" name="gvspace_privacy_kicker_' . esc_attr($locale) . '" value="' . esc_attr($kicker) . '" style="width:100%"></label></p>';
        echo '<p><label><strong>Заголовок сторінки</strong><br><input type="text" name="gvspace_privacy_title_' . esc_attr($locale) . '" value="' . esc_attr($title) . '" style="width:100%"></label></p>';
        echo '<p><label><strong>Дати</strong><br><input type="text" name="gvspace_privacy_dates_' . esc_attr($locale) . '" value="' . esc_attr($dates) . '" style="width:100%"></label></p>';
        echo '<p><label><strong>Заголовок змісту</strong><br><input type="text" name="gvspace_privacy_contents_' . esc_attr($locale) . '" value="' . esc_attr($contents) . '" style="width:100%"></label></p>';
        foreach ($sections as $index => $section) {
            echo '<fieldset style="margin:16px 0;padding:12px;border:1px solid #dcdcde">';
            echo '<legend><strong>Розділ ' . esc_html((string) ($index + 1)) . '</strong></legend>';
            echo '<p><label>Номер<br><input type="text" name="gvspace_privacy_section_number_' . esc_attr($locale) . '[]" value="' . esc_attr($section['number']) . '" style="width:120px"></label></p>';
            echo '<p><label>Назва<br><input type="text" name="gvspace_privacy_section_title_' . esc_attr($locale) . '[]" value="' . esc_attr($section['title']) . '" style="width:100%"></label></p>';
            echo '<p><label>Текст<br><textarea name="gvspace_privacy_section_body_' . esc_attr($locale) . '[]" rows="8" style="width:100%">' . esc_textarea($section['body']) . '</textarea></label></p>';
            echo '</fieldset>';
        }
        echo '</div>';
    }
    gvspace_render_language_switcher_script();
}

add_action('save_post_gv_privacy_policy', function (int $post_id): void {
    static $saving_title = false;
    if ($saving_title) return;
    if (
        !isset($_POST['gvspace_privacy_policy_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gvspace_privacy_policy_nonce'])), 'gvspace_save_privacy_policy')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) return;

    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        foreach (['kicker', 'title', 'dates', 'contents'] as $field) {
            $key = 'gvspace_privacy_' . $field . '_' . $locale;
            if (!isset($_POST[$key])) continue;
            update_post_meta($post_id, '_gvspace_privacy_' . $field . '_' . $locale, sanitize_text_field(wp_unslash($_POST[$key])));
        }
        $numbers = isset($_POST['gvspace_privacy_section_number_' . $locale]) ? (array) wp_unslash($_POST['gvspace_privacy_section_number_' . $locale]) : [];
        $titles = isset($_POST['gvspace_privacy_section_title_' . $locale]) ? (array) wp_unslash($_POST['gvspace_privacy_section_title_' . $locale]) : [];
        $bodies = isset($_POST['gvspace_privacy_section_body_' . $locale]) ? (array) wp_unslash($_POST['gvspace_privacy_section_body_' . $locale]) : [];
        $sections = [];
        $count = max(count($numbers), count($titles), count($bodies));
        for ($index = 0; $index < $count; $index++) {
            $title = sanitize_text_field((string) ($titles[$index] ?? ''));
            $body = sanitize_textarea_field((string) ($bodies[$index] ?? ''));
            $number = sanitize_text_field((string) ($numbers[$index] ?? ''));
            if ($title === '' && $body === '') continue;
            $sections[] = ['number' => $number, 'title' => $title, 'body' => $body];
        }
        gvspace_update_legal_sections_meta($post_id, '_gvspace_privacy_sections_' . $locale, $sections);
    }

    update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
    update_post_meta($post_id, '_gvspace_translation_status', 'published');
    if ((string) get_post_meta($post_id, '_gvspace_translation_group', true) === '') {
        update_post_meta($post_id, '_gvspace_translation_group', 'privacy-policy');
    }

    $uk_title = (string) get_post_meta($post_id, '_gvspace_privacy_title_uk', true);
    if ($uk_title !== '' && get_post_field('post_title', $post_id) !== $uk_title) {
        $saving_title = true;
        wp_update_post(['ID' => $post_id, 'post_title' => $uk_title]);
        $saving_title = false;
    }
});

add_action('graphql_register_types', function (): void {
    register_graphql_object_type('GvspacePrivacySection', [
        'fields' => [
            'number' => ['type' => 'String'],
            'title' => ['type' => 'String'],
            'body' => ['type' => 'String'],
        ],
    ]);
    register_graphql_object_type('GvspacePrivacyPolicyDetails', [
        'fields' => [
            'kicker' => ['type' => 'String'],
            'title' => ['type' => 'String'],
            'dates' => ['type' => 'String'],
            'contents' => ['type' => 'String'],
            'sections' => ['type' => ['list_of' => 'GvspacePrivacySection']],
        ],
    ]);
    register_graphql_field('PrivacyPolicy', 'privacyPolicyDetails', [
        'type' => 'GvspacePrivacyPolicyDetails',
        'args' => ['locale' => ['type' => 'String', 'defaultValue' => 'uk']],
        'resolve' => static function ($source, array $args): array {
            $post_id = (int) $source->databaseId;
            $requested_locale = gvspace_sanitize_content_locale((string) ($args['locale'] ?? 'uk'));
            $locale = $requested_locale === 'legacy' ? 'uk' : $requested_locale;
            $title = (string) get_post_meta($post_id, '_gvspace_privacy_title_' . $locale, true);
            $kicker = (string) get_post_meta($post_id, '_gvspace_privacy_kicker_' . $locale, true);
            $dates = (string) get_post_meta($post_id, '_gvspace_privacy_dates_' . $locale, true);
            $contents = (string) get_post_meta($post_id, '_gvspace_privacy_contents_' . $locale, true);
            $sections = gvspace_decode_privacy_sections($post_id, $locale);
            if ($title === '') $title = (string) get_post_meta($post_id, '_gvspace_privacy_title_uk', true);
            if ($kicker === '') $kicker = (string) get_post_meta($post_id, '_gvspace_privacy_kicker_uk', true);
            if ($dates === '') $dates = (string) get_post_meta($post_id, '_gvspace_privacy_dates_uk', true);
            if ($contents === '') $contents = (string) get_post_meta($post_id, '_gvspace_privacy_contents_uk', true);
            if ($sections === []) $sections = gvspace_decode_privacy_sections($post_id, 'uk');
            if ($title === '') $title = (string) get_the_title($post_id);
            return compact('kicker', 'title', 'dates', 'contents', 'sections');
        },
    ]);
});
