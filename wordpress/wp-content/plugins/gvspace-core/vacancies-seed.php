<?php

if (!defined('ABSPATH')) {
    exit;
}

function gvspace_vacancy_lines(array $items): string
{
    return implode("\n", $items);
}

function gvspace_vacancy_locale_field(int $post_id, string $field, string $locale): string
{
    $read = static fn (string $key): string => (string) get_post_meta($post_id, $key, true);
    $central = $field === 'title'
        ? $read('_gvspace_vacancy_title_' . $locale)
        : $read('_gvspace_vacancy_' . $field . '_' . $locale);
    if ($central !== '') return $central;

    if ($field === 'title') {
        if ($locale === 'en') {
            $en = $read('_gvspace_title_en');
            if ($en !== '') return $en;
        }
        if ($locale !== 'uk') {
            $uk = $read('_gvspace_vacancy_title_uk');
            if ($uk !== '') return $uk;
        }
        return (string) get_the_title($post_id);
    }

    $legacy_locale = $read('_gvspace_' . $field . '_' . ($locale === 'en' ? 'en' : 'uk'));
    if ($legacy_locale !== '') return $legacy_locale;
    $shared = $read('_gvspace_' . $field);
    if ($shared !== '') return $shared;
    $one_language = $read('_gvspace_vacancy_localized_' . $field);
    if ($one_language !== '') return $one_language;
    if ($locale !== 'uk') {
        $uk = $read('_gvspace_vacancy_' . $field . '_uk');
        if ($uk !== '') return $uk;
    }
    return '';
}

function gvspace_copy_vacancy_meta_if_empty(int $post_id, string $target, string $source): void
{
    if ((string) get_post_meta($post_id, $target, true) !== '') return;
    $value = (string) get_post_meta($post_id, $source, true);
    if ($value !== '') update_post_meta($post_id, $target, $value);
}

function gvspace_vacancy_direction_defaults(): array
{
    return [
        'marketing' => 'МАРКЕТИНГ',
        'development' => 'РОЗРОБКА',
        'projects' => 'ПРОЄКТИ',
        'content' => 'КОНТЕНТ',
    ];
}

function gvspace_vacancy_employment_defaults(): array
{
    return [
        'remote' => 'REMOTE',
        'full-time' => 'FULL-TIME',
        'part-time' => 'PART-TIME',
        'hybrid' => 'HYBRID',
        'office' => 'OFFICE',
    ];
}

function gvspace_normalize_vacancy_label(string $value): string
{
    $collapsed = preg_replace('/\s+/u', ' ', trim($value));
    $value = is_string($collapsed) ? $collapsed : trim($value);
    return function_exists('mb_strtoupper') ? mb_strtoupper($value, 'UTF-8') : strtoupper($value);
}

function gvspace_get_vacancy_taxonomy_options(string $taxonomy, array $preferred): array
{
    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ]);
    if (is_wp_error($terms) || !$terms) return $preferred;

    $preferred_slugs = array_keys($preferred);
    usort($terms, static function ($a, $b) use ($preferred_slugs): int {
        $ai = array_search($a->slug, $preferred_slugs, true);
        $bi = array_search($b->slug, $preferred_slugs, true);
        if ($ai === false && $bi === false) return strcasecmp($a->name, $b->name);
        if ($ai === false) return 1;
        if ($bi === false) return -1;
        return $ai - $bi;
    });

    $options = [];
    foreach ($terms as $term) {
        $options[$term->slug] = $term->name;
    }
    return $options;
}

function gvspace_get_vacancy_direction_options(): array
{
    return gvspace_get_vacancy_taxonomy_options('gv_vacancy_direction', gvspace_vacancy_direction_defaults());
}

function gvspace_get_vacancy_employment_options(): array
{
    return gvspace_get_vacancy_taxonomy_options('gv_vacancy_employment', gvspace_vacancy_employment_defaults());
}

function gvspace_is_vacancy_employment_label(string $label): bool
{
    $needle = str_replace(' ', '-', gvspace_normalize_vacancy_label($label));
    $aliases = ['REMOTE', 'FULL-TIME', 'FULLTIME', 'PART-TIME', 'PARTTIME', 'HYBRID', 'OFFICE', 'ONSITE', 'ON-SITE'];
    if (in_array($needle, $aliases, true)) return true;
    foreach (gvspace_get_vacancy_employment_options() as $name) {
        if (str_replace(' ', '-', gvspace_normalize_vacancy_label($name)) === $needle) return true;
    }
    return false;
}

function gvspace_ensure_vacancy_term(string $name, string $taxonomy): string
{
    $collapsed = preg_replace('/\s+/u', ' ', trim($name));
    $name = is_string($collapsed) ? $collapsed : trim($name);
    if ($name === '') return '';

    $needle = gvspace_normalize_vacancy_label($name);
    $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            if (gvspace_normalize_vacancy_label($term->name) === $needle) return $term->slug;
        }
    }

    $slug = sanitize_title($name);
    if ($slug === '') $slug = 'term-' . substr(md5($name), 0, 8);
    $existing = get_term_by('slug', $slug, $taxonomy);
    if ($existing && !is_wp_error($existing)) return $existing->slug;

    $result = wp_insert_term($name, $taxonomy, ['slug' => $slug]);
    if (is_wp_error($result)) {
        $term_id = (int) $result->get_error_data('term_exists');
        if ($term_id) {
            $term = get_term($term_id, $taxonomy);
            return $term && !is_wp_error($term) ? $term->slug : '';
        }
        return '';
    }

    $term = get_term((int) $result['term_id'], $taxonomy);
    return $term && !is_wp_error($term) ? $term->slug : $slug;
}

function gvspace_vacancy_term_names(int $post_id, string $taxonomy): array
{
    $terms = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'names']);
    return is_array($terms) ? array_values(array_filter(array_map('strval', $terms))) : [];
}

function gvspace_vacancy_tags_from_meta(int $post_id): array
{
    $tags = gvspace_split_meta_lines(gvspace_vacancy_locale_field($post_id, 'tags', 'uk'));
    if ($tags) return $tags;
    return gvspace_split_meta_lines(gvspace_vacancy_locale_field($post_id, 'tags', 'en'));
}

function gvspace_vacancy_direction_name(int $post_id): string
{
    $names = gvspace_vacancy_term_names($post_id, 'gv_vacancy_direction');
    if ($names) return $names[0];
    foreach (gvspace_vacancy_tags_from_meta($post_id) as $tag) {
        if (!gvspace_is_vacancy_employment_label($tag)) return $tag;
    }
    return '';
}

function gvspace_vacancy_employment_names(int $post_id): array
{
    $names = gvspace_vacancy_term_names($post_id, 'gv_vacancy_employment');
    if ($names) return $names;
    return array_values(array_filter(
        gvspace_vacancy_tags_from_meta($post_id),
        static fn (string $tag): bool => gvspace_is_vacancy_employment_label($tag)
    ));
}

function gvspace_vacancy_filter_tags(int $post_id): array
{
    $tags = array_merge(
        array_filter([gvspace_vacancy_direction_name($post_id)]),
        gvspace_vacancy_employment_names($post_id)
    );
    $seen = [];
    $unique = [];
    foreach ($tags as $tag) {
        $key = gvspace_normalize_vacancy_label($tag);
        if ($key === '' || isset($seen[$key])) continue;
        $seen[$key] = true;
        $unique[] = $tag;
    }
    return $unique;
}

function gvspace_sync_vacancy_tags_meta(int $post_id): void
{
    $value = implode("\n", gvspace_vacancy_filter_tags($post_id));
    foreach (array_keys(GVSPACE_CONTENT_LOCALES) as $locale) {
        update_post_meta($post_id, '_gvspace_vacancy_tags_' . $locale, $value);
    }
    update_post_meta($post_id, '_gvspace_tags', $value);
}

function gvspace_assign_vacancy_filter_terms_from_tags(int $post_id): void
{
    $direction_slugs = [];
    $employment_slugs = [];
    foreach (gvspace_vacancy_tags_from_meta($post_id) as $tag) {
        if (gvspace_is_vacancy_employment_label($tag)) {
            $slug = gvspace_ensure_vacancy_term($tag, 'gv_vacancy_employment');
            if ($slug !== '') $employment_slugs[] = $slug;
            continue;
        }
        $slug = gvspace_ensure_vacancy_term($tag, 'gv_vacancy_direction');
        if ($slug !== '') $direction_slugs[] = $slug;
    }
    if ($direction_slugs) {
        wp_set_object_terms($post_id, array_slice(array_values(array_unique($direction_slugs)), 0, 1), 'gv_vacancy_direction', false);
    }
    if ($employment_slugs) {
        wp_set_object_terms($post_id, array_values(array_unique($employment_slugs)), 'gv_vacancy_employment', false);
    }
    gvspace_sync_vacancy_tags_meta($post_id);
}

function gvspace_seed_vacancy_filter_terms(): void
{
    foreach (gvspace_vacancy_direction_defaults() as $slug => $name) {
        if (!term_exists($slug, 'gv_vacancy_direction')) {
            wp_insert_term($name, 'gv_vacancy_direction', ['slug' => $slug]);
        }
    }
    foreach (gvspace_vacancy_employment_defaults() as $slug => $name) {
        if (!term_exists($slug, 'gv_vacancy_employment')) {
            wp_insert_term($name, 'gv_vacancy_employment', ['slug' => $slug]);
        }
    }
}
add_action('init', 'gvspace_seed_vacancy_filter_terms', 19);
add_action('admin_init', 'gvspace_seed_vacancy_filter_terms');

function gvspace_migrate_vacancy_filter_terms(): void
{
    if (get_option('gvspace_vacancy_filter_terms_v1') === '1') return;
    gvspace_seed_vacancy_filter_terms();
    $vacancy_ids = get_posts([
        'post_type' => 'gv_vacancy',
        'post_status' => 'any',
        'numberposts' => -1,
        'fields' => 'ids',
    ]);
    foreach ($vacancy_ids as $vacancy_id) {
        gvspace_assign_vacancy_filter_terms_from_tags((int) $vacancy_id);
    }
    update_option('gvspace_vacancy_filter_terms_v1', '1', false);
}
add_action('init', 'gvspace_migrate_vacancy_filter_terms', 22);
add_action('admin_init', 'gvspace_migrate_vacancy_filter_terms');

function gvspace_vacancy_seed_catalog(): array
{
    $shared_benefits_uk = gvspace_vacancy_lines([
        'Ставка $[XXX–XXX]/міс залежно від досвіду та результатів.',
        'Remote, гнучкий графік без зайвих нарад.',
        'Власна зона відповідальності без мікроменеджменту.',
        'Реальні кейси для портфоліо з вимірюваними результатами.',
        'Можливість рости разом з агенцією.',
    ]);
    $shared_benefits_en = gvspace_vacancy_lines([
        'Compensation of $[XXX–XXX]/month depending on experience and results.',
        'Remote work and a flexible schedule without unnecessary meetings.',
        'Your own area of responsibility without micromanagement.',
        'Real portfolio cases with measurable results.',
        'The opportunity to grow with the agency.',
    ]);

    return [
        [
            'slug' => 'performance-marketing-manager',
            'order' => 1,
            'hot' => true,
            'title_uk' => 'Performance Marketing Manager',
            'title_en' => 'Performance Marketing Manager',
            'excerpt_uk' => 'Шукаємо фахівця з досвідом у Meta та Google Ads, який вміє будувати системи, а не запускати кампанії наосліп.',
            'excerpt_en' => 'We are looking for a Meta and Google Ads specialist who can build systems rather than launch campaigns blindly.',
            'salary' => '$[XXX–XXX] / міс',
            'tags' => gvspace_vacancy_lines(['МАРКЕТИНГ', 'REMOTE', 'FULL-TIME']),
            'role_uk' => gvspace_vacancy_lines([
                '[Опис ролі: що саме робить ця людина в команді, яка її основна функція, як виглядає типовий день або тиждень. 2–3 речення.]',
                '[Контекст: на якому етапі знаходиться компанія, чому зараз потрібна ця роль, який вплив матиме ця людина на результат.]',
            ]),
            'role_en' => gvspace_vacancy_lines([
                '[Role description: what this person does, their main function, and what a typical day or week looks like. 2–3 sentences.]',
                '[Context: where the company is now, why this role is needed, and how this person will influence the result.]',
            ]),
            'tasks_uk' => gvspace_vacancy_lines([
                'Налаштування та оптимізація рекламних кампаній у Meta та Google Ads.',
                'Побудова наскрізної аналітики: GA4, GTM, Pixel, CRM-інтеграція.',
                'Написання медіапланів і прогнозів для клієнтів.',
                'Щотижневі звіти без жаргону — тільки бізнесові метрики.',
                'Участь у Clarity Session з клієнтами на старті кожного проєкту.',
            ]),
            'tasks_en' => gvspace_vacancy_lines([
                'Set up and optimize advertising campaigns in Meta and Google Ads.',
                'Build end-to-end analytics: GA4, GTM, Pixel, and CRM integration.',
                'Prepare media plans and forecasts for clients.',
                'Produce weekly reports without jargon — only business metrics.',
                'Participate in client Clarity Sessions at the start of each project.',
            ]),
            'requirements_uk' => gvspace_vacancy_lines([
                '2+ роки у performance-маркетингу, є підтверджені результати по ROAS/CPL.',
                'Впевнена робота з Meta Ads Manager і Google Ads на рівні вище середнього.',
                'Розуміння юніт-економіки: CAC, LTV, ROAS, CPL — не просто терміни.',
                'Вмієш пояснювати результати клієнту без жаргону.',
                'Системне мислення: шукаєш першопричину, а не латаєш симптоми.',
            ]),
            'requirements_en' => gvspace_vacancy_lines([
                '2+ years in performance marketing with proven ROAS/CPL results.',
                'Confident, above-average use of Meta Ads Manager and Google Ads.',
                'A practical understanding of unit economics: CAC, LTV, ROAS, and CPL.',
                'Ability to explain results to clients without jargon.',
                'Systematic thinking: finding root causes rather than patching symptoms.',
            ]),
            'tools' => gvspace_vacancy_lines(['Meta Ads', 'Google Ads', 'GA4', 'GTM', 'Looker Studio', 'TikTok Ads', 'Notion']),
            'benefits_uk' => $shared_benefits_uk,
            'benefits_en' => $shared_benefits_en,
            'seo_description_uk' => 'Вакансія Performance Marketing Manager у GVSPACE: Meta Ads, Google Ads, аналітика та системний performance.',
            'seo_description_en' => 'Performance Marketing Manager role at GVSPACE: Meta Ads, Google Ads, analytics, and systematic performance.',
        ],
        [
            'slug' => 'full-stack-developer',
            'order' => 2,
            'hot' => false,
            'title_uk' => 'Full-stack Developer',
            'title_en' => 'Full-stack Developer',
            'excerpt_uk' => 'Шукаємо розробника, який збирає продукти від архітектури до релізу і тримає якість коду без хаосу.',
            'excerpt_en' => 'We are looking for a developer who can take products from architecture to release and keep code quality without chaos.',
            'salary' => '$[XXX–XXX] / міс',
            'tags' => gvspace_vacancy_lines(['РОЗРОБКА', 'REMOTE', 'FULL-TIME']),
            'role_uk' => gvspace_vacancy_lines([
                'Ви відповідаєте за розробку та підтримку продуктів GVSPACE і клієнтських систем: від архітектури до релізу.',
                'Працюєте в парі зі стратегією і маркетингом, щоб технічні рішення підсилювали бізнес-результат, а не існували окремо.',
            ]),
            'role_en' => gvspace_vacancy_lines([
                'You own the development and support of GVSPACE products and client systems: from architecture to release.',
                'You work alongside strategy and marketing so technical decisions reinforce business results rather than living in isolation.',
            ]),
            'tasks_uk' => gvspace_vacancy_lines([
                'Проєктування та розробка вебпродуктів на Next.js, WordPress і пов’язаному стеку.',
                'Інтеграції з CRM, аналітикою, платежами та внутрішніми сервісами.',
                'Підтримка якості: рев’ю, тести, документація, стабільні релізи.',
                'Оцінка складності й ризиків до старту розробки.',
                'Участь у Clarity Session, коли рішення залежить від технічної архітектури.',
            ]),
            'tasks_en' => gvspace_vacancy_lines([
                'Design and build web products on Next.js, WordPress, and the related stack.',
                'Integrate CRM, analytics, payments, and internal services.',
                'Protect quality through reviews, tests, documentation, and stable releases.',
                'Estimate complexity and risks before development starts.',
                'Join Clarity Sessions when the decision depends on technical architecture.',
            ]),
            'requirements_uk' => gvspace_vacancy_lines([
                '3+ роки комерційної розробки, є живі продукти в портфоліо.',
                'Впевнений TypeScript, React/Next.js і досвід з WordPress або headless CMS.',
                'Розуміння API, баз даних і базової інфраструктури релізу.',
                'Вмієш пояснювати технічні обмеження бізнесу без жаргону.',
                'Системне мислення: шукаєш першопричину, а не латаєш симптоми.',
            ]),
            'requirements_en' => gvspace_vacancy_lines([
                '3+ years of commercial development with live products in the portfolio.',
                'Confident TypeScript and React/Next.js, plus WordPress or headless CMS experience.',
                'A working understanding of APIs, databases, and basic release infrastructure.',
                'Ability to explain technical constraints to the business without jargon.',
                'Systematic thinking: finding root causes rather than patching symptoms.',
            ]),
            'tools' => gvspace_vacancy_lines(['Next.js', 'TypeScript', 'WordPress', 'GraphQL', 'MySQL', 'Docker', 'Git']),
            'benefits_uk' => $shared_benefits_uk,
            'benefits_en' => $shared_benefits_en,
            'seo_description_uk' => 'Вакансія Full-stack Developer у GVSPACE: Next.js, WordPress, інтеграції та якісний релізний цикл.',
            'seo_description_en' => 'Full-stack Developer role at GVSPACE: Next.js, WordPress, integrations, and a reliable release cycle.',
        ],
        [
            'slug' => 'project-manager',
            'order' => 3,
            'hot' => false,
            'title_uk' => 'Project Manager',
            'title_en' => 'Project Manager',
            'excerpt_uk' => 'Шукаємо людину, яка тримає проєкти в системі: строки, комунікацію і результат без хаосу.',
            'excerpt_en' => 'We are looking for someone who keeps projects in a system: timelines, communication, and results without chaos.',
            'salary' => '$[XXX–XXX] / міс',
            'tags' => gvspace_vacancy_lines(['ПРОЄКТИ', 'REMOTE', 'FULL-TIME']),
            'role_uk' => gvspace_vacancy_lines([
                'Ви власник поставки: тримаєте строки, якість комунікації і прозорість статусу для команди та клієнта.',
                'Роль потрібна, щоб проєкти не розпадалися на задачі. Ви збираєте процес у систему і не даєте втратити фокус на результаті.',
            ]),
            'role_en' => gvspace_vacancy_lines([
                'You own delivery: timelines, communication quality, and status transparency for the team and the client.',
                'The role exists so projects do not fragment into tasks. You turn the process into a system and keep the result in focus.',
            ]),
            'tasks_uk' => gvspace_vacancy_lines([
                'Ведення клієнтських проєктів від Clarity Session до релізу.',
                'Планування спринтів, дедлайнів і залежностей між маркетингом, контентом і розробкою.',
                'Регулярний статус клієнту без шуму — що зроблено, що блокує, який наступний крок.',
                'Збір вимог і фіксація рішень, щоб команда не працювала з усних домовленостей.',
                'Контроль якості здачі: чеклісти, рев’ю, передача в підтримку.',
            ]),
            'tasks_en' => gvspace_vacancy_lines([
                'Run client projects from the Clarity Session through to release.',
                'Plan sprints, deadlines, and dependencies across marketing, content, and development.',
                'Give the client a calm status: what is done, what is blocked, and what happens next.',
                'Capture requirements and decisions so the team is not working from verbal agreements.',
                'Control handover quality with checklists, reviews, and a clean pass into support.',
            ]),
            'requirements_uk' => gvspace_vacancy_lines([
                '2+ роки в проєктному менеджменті digital / IT, є кейси з живими клієнтами.',
                'Вмієш вести кілька проєктів паралельно без втрати деталей.',
                'Сильна письмова комунікація: протоколи, статуси, брифи без води.',
                'Розумієш, як пов’язані маркетинг, контент і розробка в одному проєкті.',
                'Системне мислення: шукаєш першопричину, а не латаєш симптоми.',
            ]),
            'requirements_en' => gvspace_vacancy_lines([
                '2+ years in digital/IT project management with live client cases.',
                'Able to run several projects in parallel without losing the details.',
                'Strong written communication: notes, status updates, and briefs without filler.',
                'You understand how marketing, content, and development connect in one project.',
                'Systematic thinking: finding root causes rather than patching symptoms.',
            ]),
            'tools' => gvspace_vacancy_lines(['Notion', 'ClickUp', 'Google Meet', 'Slack', 'Google Sheets', 'Figma']),
            'benefits_uk' => $shared_benefits_uk,
            'benefits_en' => $shared_benefits_en,
            'seo_description_uk' => 'Вакансія Project Manager у GVSPACE: поставка проєктів, комунікація з клієнтом і системний процес.',
            'seo_description_en' => 'Project Manager role at GVSPACE: project delivery, client communication, and a systematic process.',
        ],
        [
            'slug' => 'content-manager',
            'order' => 4,
            'hot' => false,
            'title_uk' => 'Content Manager',
            'title_en' => 'Content Manager',
            'excerpt_uk' => 'Шукаємо спеціаліста, який будує контент як систему: сенс, SEO і регулярність без хаотичних постів.',
            'excerpt_en' => 'We are looking for a specialist who builds content as a system: meaning, SEO, and cadence instead of chaotic posts.',
            'salary' => '$[XXX–XXX] / міс',
            'tags' => gvspace_vacancy_lines(['КОНТЕНТ', 'REMOTE', 'FULL-TIME']),
            'role_uk' => gvspace_vacancy_lines([
                'Ви відповідаєте за контент-систему клієнта: від сенсу і структури до публікації та вимірюваного ефекту.',
                'Роль закриває розрив між стратегією і щоденним контентом. Ви робите так, щоб тексти працювали на ріст, а не заповнювали стрічку.',
            ]),
            'role_en' => gvspace_vacancy_lines([
                'You own the client content system: from meaning and structure through to publishing and measurable effect.',
                'The role closes the gap between strategy and daily content. You make texts work for growth rather than filling a feed.',
            ]),
            'tasks_uk' => gvspace_vacancy_lines([
                'Побудова контент-системи: рубрики, меседжі, календар, критерії якості.',
                'Написання та редактура матеріалів для сайту, блогу, розсилок і соцмереж.',
                'SEO-каркас сторінок: структура, Title/H1, внутрішні зв’язки.',
                'Брифи для дизайну і продакшену, щоб візуал підтримував сенс, а не навпаки.',
                'Звіт по контенту: що вийшло, що спрацювало, що змінюємо далі.',
            ]),
            'tasks_en' => gvspace_vacancy_lines([
                'Build the content system: pillars, messages, calendar, and quality criteria.',
                'Write and edit materials for the website, blog, email, and social channels.',
                'Build the SEO skeleton of pages: structure, Title/H1, and internal links.',
                'Brief design and production so visuals support meaning rather than the other way around.',
                'Report on content: what shipped, what worked, and what we change next.',
            ]),
            'requirements_uk' => gvspace_vacancy_lines([
                '2+ роки в контенті / SEO / редактурі, є кейси з вимірюваним результатом.',
                'Сильна українська та англійська письмова мова.',
                'Розуміння SEO не як списку ключів, а як структури сторінки і сенсу для людини.',
                'Вмієш збирати бриф, ставити питання і не писати «в порожнечу».',
                'Системне мислення: шукаєш першопричину, а не латаєш симптоми.',
            ]),
            'requirements_en' => gvspace_vacancy_lines([
                '2+ years in content, SEO, or editing with cases that have measurable results.',
                'Strong written Ukrainian and English.',
                'SEO understood as page structure and meaning for people, not a keyword list.',
                'Able to gather a brief, ask questions, and avoid writing into a vacuum.',
                'Systematic thinking: finding root causes rather than patching symptoms.',
            ]),
            'tools' => gvspace_vacancy_lines(['Notion', 'Google Docs', 'Ahrefs', 'Surfer', 'Meta', 'Figma']),
            'benefits_uk' => $shared_benefits_uk,
            'benefits_en' => $shared_benefits_en,
            'seo_description_uk' => 'Вакансія Content Manager у GVSPACE: контент-система, SEO-структура та вимірюваний ефект текстів.',
            'seo_description_en' => 'Content Manager role at GVSPACE: content systems, SEO structure, and measurable writing impact.',
        ],
    ];
}

function gvspace_find_seeded_vacancy(string $slug): ?WP_Post
{
    $by_group = get_posts([
        'post_type' => 'gv_vacancy',
        'post_status' => ['publish', 'draft', 'pending', 'private'],
        'numberposts' => 1,
        'orderby' => 'ID',
        'order' => 'ASC',
        'meta_key' => '_gvspace_translation_group',
        'meta_value' => $slug,
    ]);
    if ($by_group) return $by_group[0];

    $by_slug = get_posts([
        'post_type' => 'gv_vacancy',
        'post_status' => ['publish', 'draft', 'pending', 'private'],
        'numberposts' => 1,
        'name' => $slug,
    ]);
    return $by_slug[0] ?? null;
}

function gvspace_seed_vacancies(): void
{
    $catalog = gvspace_vacancy_seed_catalog();
    $hash = md5('centralized-v1' . (string) wp_json_encode($catalog));
    if ((string) get_option('gvspace_vacancies_catalog_hash') === $hash) return;
    $lock_time = (int) get_option('gvspace_vacancies_catalog_lock', 0);
    if ($lock_time && time() - $lock_time < 300) return;
    if ($lock_time) delete_option('gvspace_vacancies_catalog_lock');
    if (!add_option('gvspace_vacancies_catalog_lock', time(), '', false)) return;

    $keep_slugs = [];
    foreach ($catalog as $vacancy) {
        $keep_slugs[] = $vacancy['slug'];
        $existing = gvspace_find_seeded_vacancy($vacancy['slug']);
        $post_data = [
            'post_type' => 'gv_vacancy',
            'post_status' => 'publish',
            'post_title' => $vacancy['title_uk'],
            'post_name' => $vacancy['slug'],
            'menu_order' => $vacancy['order'],
        ];
        if ($existing) $post_data['ID'] = $existing->ID;
        $post_id = wp_insert_post($post_data);
        if (!$post_id || is_wp_error($post_id)) continue;

        update_post_meta($post_id, '_gvspace_content_locale', 'legacy');
        update_post_meta($post_id, '_gvspace_translation_group', $vacancy['slug']);
        update_post_meta($post_id, '_gvspace_translation_status', 'published');
        update_post_meta($post_id, '_gvspace_vacancy_seeded', 'catalog');
        update_post_meta($post_id, '_gvspace_hot', $vacancy['hot'] ? '1' : '');
        update_post_meta($post_id, '_gvspace_vacancy_title_uk', $vacancy['title_uk']);
        update_post_meta($post_id, '_gvspace_vacancy_title_en', $vacancy['title_en']);
        update_post_meta($post_id, '_gvspace_vacancy_excerpt_uk', $vacancy['excerpt_uk']);
        update_post_meta($post_id, '_gvspace_vacancy_excerpt_en', $vacancy['excerpt_en']);
        update_post_meta($post_id, '_gvspace_vacancy_salary_uk', $vacancy['salary']);
        update_post_meta($post_id, '_gvspace_vacancy_salary_en', str_replace('/ міс', '/ month', $vacancy['salary']));
        update_post_meta($post_id, '_gvspace_vacancy_tags_uk', $vacancy['tags']);
        update_post_meta($post_id, '_gvspace_vacancy_tags_en', $vacancy['tags']);
        update_post_meta($post_id, '_gvspace_vacancy_role_uk', $vacancy['role_uk']);
        update_post_meta($post_id, '_gvspace_vacancy_role_en', $vacancy['role_en']);
        update_post_meta($post_id, '_gvspace_vacancy_tasks_uk', $vacancy['tasks_uk']);
        update_post_meta($post_id, '_gvspace_vacancy_tasks_en', $vacancy['tasks_en']);
        update_post_meta($post_id, '_gvspace_vacancy_requirements_uk', $vacancy['requirements_uk']);
        update_post_meta($post_id, '_gvspace_vacancy_requirements_en', $vacancy['requirements_en']);
        update_post_meta($post_id, '_gvspace_vacancy_tools_uk', $vacancy['tools']);
        update_post_meta($post_id, '_gvspace_vacancy_tools_en', $vacancy['tools']);
        update_post_meta($post_id, '_gvspace_vacancy_benefits_uk', $vacancy['benefits_uk']);
        update_post_meta($post_id, '_gvspace_vacancy_benefits_en', $vacancy['benefits_en']);
        update_post_meta($post_id, '_gvspace_seo_title_uk', $vacancy['title_uk']);
        update_post_meta($post_id, '_gvspace_seo_title_en', $vacancy['title_en']);
        update_post_meta($post_id, '_gvspace_seo_h1_uk', $vacancy['title_uk']);
        update_post_meta($post_id, '_gvspace_seo_h1_en', $vacancy['title_en']);
        update_post_meta($post_id, '_gvspace_seo_description_uk', $vacancy['seo_description_uk']);
        update_post_meta($post_id, '_gvspace_seo_description_en', $vacancy['seo_description_en']);
        update_post_meta($post_id, '_gvspace_seo_og_title_uk', $vacancy['title_uk']);
        update_post_meta($post_id, '_gvspace_seo_og_title_en', $vacancy['title_en']);
        update_post_meta($post_id, '_gvspace_seo_og_description_uk', $vacancy['seo_description_uk']);
        update_post_meta($post_id, '_gvspace_seo_og_description_en', $vacancy['seo_description_en']);
        gvspace_assign_vacancy_filter_terms_from_tags((int) $post_id);
    }

    $vacancies = get_posts([
        'post_type' => 'gv_vacancy',
        'post_status' => ['publish', 'draft', 'pending', 'private', 'trash'],
        'numberposts' => -1,
    ]);
    foreach ($vacancies as $vacancy_post) {
        $group = (string) get_post_meta($vacancy_post->ID, '_gvspace_translation_group', true);
        $seeded = (string) get_post_meta($vacancy_post->ID, '_gvspace_vacancy_seeded', true);
        $obsolete = $seeded === 'catalog' && $group !== '' && !in_array($group, $keep_slugs, true);
        if ($obsolete) wp_delete_post((int) $vacancy_post->ID, true);
    }

    update_option('gvspace_vacancies_catalog_hash', $hash, false);
    delete_option('gvspace_vacancies_catalog_lock');
}
add_action('init', 'gvspace_seed_vacancies', 21);

function gvspace_migrate_vacancy_language_fields(): void
{
    if (get_option('gvspace_vacancy_languages_v1') === '1') return;
    $vacancy_ids = get_posts([
        'post_type' => 'gv_vacancy',
        'post_status' => 'any',
        'numberposts' => -1,
        'fields' => 'ids',
    ]);
    foreach ($vacancy_ids as $vacancy_id) {
        $id = (int) $vacancy_id;
        $stored_locale = (string) get_post_meta($id, '_gvspace_content_locale', true);
        if ((string) get_post_meta($id, '_gvspace_vacancy_title_uk', true) === '') {
            update_post_meta($id, '_gvspace_vacancy_title_uk', (string) get_post_field('post_title', $id));
        }
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_title_en', '_gvspace_title_en');
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_excerpt_uk', '_gvspace_excerpt_uk');
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_excerpt_en', '_gvspace_excerpt_en');
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_salary_uk', '_gvspace_salary');
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_salary_en', '_gvspace_salary');
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_tags_uk', '_gvspace_tags');
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_tags_en', '_gvspace_tags');
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_role_uk', '_gvspace_role_uk');
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_role_en', '_gvspace_role_en');
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_tasks_uk', '_gvspace_tasks_uk');
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_tasks_en', '_gvspace_tasks_en');
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_requirements_uk', '_gvspace_requirements_uk');
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_requirements_en', '_gvspace_requirements_en');
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_tools_uk', '_gvspace_tools');
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_tools_en', '_gvspace_tools');
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_benefits_uk', '_gvspace_benefits_uk');
        gvspace_copy_vacancy_meta_if_empty($id, '_gvspace_vacancy_benefits_en', '_gvspace_benefits_en');

        $single_locale = array_key_exists($stored_locale, GVSPACE_CONTENT_LOCALES) ? $stored_locale : '';
        if ($single_locale !== '') {
            if ((string) get_post_meta($id, '_gvspace_vacancy_title_' . $single_locale, true) === '') {
                update_post_meta($id, '_gvspace_vacancy_title_' . $single_locale, (string) get_post_field('post_title', $id));
            }
            foreach (array_keys(GVSPACE_LOCALIZED_VACANCY_FIELDS) as $field) {
                gvspace_copy_vacancy_meta_if_empty(
                    $id,
                    '_gvspace_vacancy_' . $field . '_' . $single_locale,
                    '_gvspace_vacancy_localized_' . $field
                );
            }
        }

        update_post_meta($id, '_gvspace_content_locale', 'legacy');
        update_post_meta($id, '_gvspace_translation_status', 'published');
        if ((string) get_post_meta($id, '_gvspace_translation_group', true) === '') {
            $default_group = sanitize_title((string) get_post_field('post_name', $id) ?: (string) get_post_field('post_title', $id));
            update_post_meta($id, '_gvspace_translation_group', $default_group ?: 'vacancy-' . $id);
        }
    }
    update_option('gvspace_vacancy_languages_v1', '1', false);
}
add_action('admin_init', 'gvspace_migrate_vacancy_language_fields');
add_action('init', 'gvspace_migrate_vacancy_language_fields', 22);
