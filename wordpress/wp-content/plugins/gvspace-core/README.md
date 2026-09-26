# GVSPACE Core

## Localization workflow

Every post, service, case, vacancy, review and technology has a **GVSPACE: localization** panel:

1. Existing bilingual records remain `Legacy: UK + EN` and continue to work unchanged.
2. New records contain exactly one language. Available locales are `uk`, `en`, `pl`, `de-DE`, `de-AT`, `es`, `fr`, `it`, `nl`, `cs`, `sk` and `en-GB`.
3. Translations of the same item must share one **Translation group** key, for example `service-development`.
4. Only a translation marked **Published** is ready for the public frontend. **Draft** and **Missing** are editorial states and must not be indexed.
5. Do not convert legacy records until their separate UK and EN copies have been reviewed.

### Creating a translation

1. Create the source record, select its language and keep the translation status as **Draft** while editing.
2. Save it, then use **Create translation** in the localization panel.
3. The plugin creates a WordPress draft, copies the content and media, assigns the target language and keeps the same translation-group key.
4. Replace the copied text with the translation. Publish the WordPress record and then set the translation status to **Published**.
5. The frontend only returns records whose language matches the active domain and whose translation status is **Published**.

For new vacancies, services, reviews, cases, team members and technologies, the data panel contains one set of fields. Fill it in using the language selected in the content block. Changing the locale does not translate text automatically.

The extra locales are prepared for editorial work only. A domain must not be made public or indexable until its static frontend dictionary and reviewed dynamic content are ready.

New records default to Ukrainian and Draft. Records created before this workflow remain Legacy/Published until they are migrated.

For single-language records, the **Translation group** is also the stable public URL slug shared by every language. WordPress may add suffixes such as `-2` to its internal slug; those internal values are not exposed in public links. Choose a short semantic group before publishing and do not change it later without a redirect.

The fields are available through WPGraphQL as `gvspaceLocalization { locale translationGroup status }`. New localized vacancy, service and review records expose neutral GraphQL fields (`excerpt`, `headline`, `position`, etc.); legacy bilingual fields remain available during migration.

## Політика конфіденційності

- Редагується в розділі **Політика конфіденційності**.
- Один запис містить усі мовні версії. Селект **Редагувати мовну версію** синхронізований із блоком SEO.
- Після оновлення плагін один раз створює запис із текстом з дизайну українською та англійською.

## Контакти

- Редагується в розділі **Контакти**.
- Один запис містить усі мовні версії: банер, картки зв’язку, форму, офіси та соцмережі.
- Підпис «ЛОКАЦІЯ» і тексти карти — у блоці **Локація і карта**. Карту завантажте як **Карта локації**. Якщо зображення немає, на сайті показується заглушка.
- Селект мови синхронізований із блоком SEO.

## Послуги

Увесь каталог редагується в одному розділі **Послуги → Структура послуг**:

1. Запис верхнього рівня без батьківського елемента — це напрямок (L2), наприклад «Маркетинг».
2. Дочірній запис — окрема послуга (L3). Її напрямок обирається у блоці **Атрибути → Батьківський елемент**.
3. Один запис містить усі мовні версії. У блоці **Контент сторінки послуги** виберіть мову в селекті та введіть переклад.
4. Селект у SEO-блоці синхронізований із селектом контенту: перемикання мови показує відповідний контент і SEO без створення дублікатів запису.
5. Порядок напрямків і послуг задається у **Атрибути → Порядок**. Менше число відображається раніше.
6. Головне зображення напрямку використовується як його іконка.
7. У списку **Структура послуг** фільтруйте за типом (L2/L3) і за напрямком, щоб бачити лише одну «папку» каталогу.

Масово залити напрямки L2: **Послуги → Імпорт L2**. Завантажте `.md` або `.zip`, перевірте прев’ю і підтвердіть. Один файл = один напрямок однією мовою (`gvspace-l2-development.md` = uk, `gvspace-l2-development.en.md` = en). Галочка «замінити каталог» ховає в чернетки L2, яких немає в пакеті, разом із їхніми L3. Шаблон — кнопка на тій самій сторінці.

Масово залити L3 з файлів маркетолога: **Послуги → Імпорт L3**. Оберіть напрямок, завантажте `.md` або `.zip`, перевірте прев’ю і підтвердіть. Один файл = одна послуга однією мовою (`gvspace-l3-android.md` = uk, `gvspace-l3-android.en.md` = en). Повторна заливка дописує мову й не затирає інші. Галочка «замінити каталог» ховає в чернетки L3, яких немає в пакеті; для самих перекладів її знімають. Шаблон — кнопка на тій самій сторінці. Файли в Git не кладіть.

Назви та структура з цього каталогу використовуються на загальній сторінці послуг, на головній і в меню. Каталог наповнюється вручну або через імпорт L2 і L3.

## SEO fields

Every localized content type has a **GVSPACE: SEO та соцмережі** panel with SEO Title, Meta Description, H1 and Open Graph fields. New records have one field set in the selected language; Legacy records have separate Ukrainian and English sets.

Empty fields use safe fallbacks: the WordPress title for SEO Title and H1, the excerpt/content for Meta Description, and the featured image for Open Graph Image. Canonical and hreflang are generated by the frontend from the active market and translation group and are intentionally not entered manually.

WPGraphQL exposes the resolved structure through `gvspaceSeo(locale: "uk")` or `gvspaceSeo(locale: "en")`, including `datePublished` and `dateModified`.

## Технології

У WordPress технології редагуються в розділі **Технології**.

1. Таби сторінки додаються в **Технології → Таби**. Українська назва — у стандартному полі, англійська — окремо, порядок — числом.
2. Саму технологію додайте через **Технології → Додати технологію**.
3. Один запис містить усі мовні версії. У блоці **Налаштування технології** оберіть мову селектом і заповніть картку та сторінку. Перемикач синхронізований із блоком SEO.
4. **Назва технології** є H1 на білому банері. Текст під назвою, фіолетовий блок «чому обираємо», тригери, «як застосовуємо», FAQ, SEO-текст і блок зустрічі заповнюються в мовній панелі.
5. Іконку картки й банера завантажте як **Головне зображення** (SVG або PNG) — та сама іконка, що в каталозі.
6. Фото експерта в блоці зустрічі оберіть з **медіатеки**. Якщо порожньо — береться фото з «Команда» або заглушка.
7. Таб визначає послуги й кейси: Розробка підтягне IT-послуги та IT-кейси, Маркетинг — маркетингові. Конкретний кейс можна зафіксувати slug-ом.
8. Зразок для маркетолога: записи **Next.js** і **Google Ads**. Нові технології створюйте за їхньою структурою.
9. Порядок карток задається у **Атрибути → Порядок**.

SVG дозволені лише адміністраторам. Завантажуйте тільки оптимізовані SVG із надійних джерел,
оскільки формат може містити активний вміст.

## Дублювання

У списках блогу, кейсів, вакансій і технологій під назвою запису доступна дія
**Дублювати**. Вона копіює запис разом із категоріями, позначками, метаполями та головним
зображенням і відкриває копію як чернетку.

Project-owned WordPress functionality for the headless GVSPACE website.

## Vacancy editing

- Одна вакансія — один запис. У блоці **Дані вакансії** оберіть **Редагувати мовну версію** і заповніть переклад.
- Напрямок обирається зі списку. Вид зайнятості — галочками. Новий напрямок або вид зайнятості можна вписати прямо в картці або додати в **Вакансії → Напрямки** / **Вакансії → Зайнятість**.
- Перемикання мови не перезавантажує сторінку й не видаляє вже введені тексти. Селект у блоці SEO синхронізований із контентом.
- Повторювані значення — один пункт у рядку.
- Порядок публікації задається у **Атрибути → Порядок**. Бейдж «гаряча», напрямок і зайнятість спільні для всіх мов.
- Банер вакансії є спільним статичним зображенням на сайті.
- Після оновлення плагіна каталог із 4 вакансіями з дизайну переноситься на цю схему; окремі UK/EN поля зводяться в мовні панелі.

Activate **GVSPACE Core** together with **WPGraphQL** in WordPress Admin.

## Папки медіа

- Папки редагуються у **Медіафайли → Папки**.
- Для файлу папка вибирається в його властивостях у медіабібліотеці.
- У режимі плитки й таблиці доступний фільтр за папкою.
- Папки є віртуальними: вони не змінюють URL і фізичне розташування файлів.
- Початкові папки: логотипи партнерів, технології, кейси, команда, відгуки, блог, фони та банери.

## Команда

- Додавайте людей у розділі **Команда → Додати людину**.
- Один запис містить українську та англійську версії: ім’я, посаду й компетенції заповнюйте в блоці **Дані учасника команди**, перемикаючи мову.
- Фото — як **Головне зображення**. Якщо фото немає, на сайті показується заглушка.
- У блоці **Таби команди** поставте галочки. Одна людина може бути в кількох табах.
- Новий напрямок додається в **Команда → Таби**: назва (як на сайті, наприклад `SALES`) і ярлик латиницею (`sales`). Після збереження він з’явиться в чекбоксах на картці людини.
- Порядок карток задається числом у **Атрибути → Порядок**: менше число показується раніше.
- Після оновлення плагін синхронізує картки з каталогу в коді: створює відсутніх і видаляє прибраних (зараз без Христини, Вікторії, Ярослава і Мар’яни). Фото не підставляються — їх потрібно завантажити вручну.
- Партнери є спільними для всіх мовних версій сайту: один запис відображається і в українській, і в англійській версії без дублювання.
- Початкове наповнення доводить список до 10 тестових партнерів, дублюючи наявний логотип; назви та напрямки призначені для подальшої заміни маркетологами.

## Blog editing

- Create and publish articles in **Posts → Add New**.
- The post title, excerpt, body, publish date, author, category, tags and featured image are used by the Next.js blog automatically.
- The newest published post becomes the featured article in the catalog.
- Edit an author under **Users → Profile**. The **GVSPACE author profile** section controls the public role, headline and statistics.
- The standard WordPress biography and avatar are also displayed on the author page.
- After editing, allow up to 60 seconds for the frontend cache to refresh.

## Головна сторінка → Партнери

- Додавайте компанії у розділі **Головна сторінка → Партнери → Додати партнера**.
- Назва компанії задається в заголовку, а напрямок окремо українською та англійською — у блоці **Дані партнера**.
- Логотип завантажується як **Головне зображення**.
- Порядок у слайдері задається числом у полі **Атрибути → Порядок**.
- Для іншої мови створіть переклад через блок локалізації та залиште ту саму групу перекладів.

## Головна сторінка → FAQ

- Питання для головної сторінки редагуються у **Головна сторінка → FAQ**.
- Один запис містить усі мовні версії. У блоці **Контент FAQ** виберіть мову в селекті та введіть питання й відповідь.
- Перемикання мови не перезавантажує сторінку й не видаляє вже введені переклади.
- У блоці **Розміщення FAQ** виберіть **Головна сторінка**.
- Черговість визначається числом у полі **Атрибути → Порядок**.
- Після оновлення плагіна окремі UK/EN дописи з однаковою групою перекладів зводяться в один запис.

## Головна сторінка → SEO-текст

- Текст для блоку місії на головній редагується у **Головна сторінка → SEO-текст**.
- Один запис містить усі мовні версії. У блоці **Контент SEO-тексту** виберіть мову, заповніть виділений перший рядок і сам текст.
- Перемикання мови не перезавантажує сторінку й не видаляє вже введені переклади.
- Для сайту використовується один опублікований запис; окремі UK/EN дописи зводяться в нього після оновлення плагіна.

## Case studies

- Один кейс — один запис. У блоці **Дані кейсу** оберіть **Редагувати мовну версію** і заповніть переклад.
- Напрямок і тип проєкту обираються зі списку. Нове значення можна вписати в картці або додати в **Кейси → Напрямки** / **Кейси → Типи проєктів**.
- Обкладинка картки й банера сторінки кейсу — **Головне зображення**. Галерея додається кнопкою **Додати фото з галереї** з медіатеки й спільна для всіх мов.
- Повторювані значення — один пункт у рядку. Метрики та картки послуг: `значення | назва`.
- Селект у блоці SEO синхронізований із контентом.
- Після оновлення плагіна старі кейси замінюються каталогом із дизайну.
