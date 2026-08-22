import { LegalNavigation } from "./legal-navigation";
import type { Locale } from "@/i18n";

type LegalKind = "privacy" | "terms";
type LegalSection = {
  title: string;
  paragraphs: string[];
  items?: string[];
  afterItems?: string[];
};

const privacyContent = {
  uk: {
    eyebrow: "LEGAL",
    title: "Політика конфіденційності",
    updated: "Дата набрання чинності: [ДД.ММ.РРРР]  ·  Остання редакція: [ДД.ММ.РРРР]",
    intro:
      "Ми поважаємо вашу приватність і дбаємо про безпеку персональних даних. У цій політиці пояснюємо, які дані збирає GVSPACE, навіщо ми їх використовуємо та які права маєте ви.",
    contents: "Зміст",
    sections: [
      {
        title: "1. Загальні положення",
        paragraphs: [
          "Ця Політика конфіденційності описує, як [Назва компанії] (далі — «GVSPACE», «ми», «нас») збирає, використовує та захищає персональні дані користувачів вебсайту [gvspace.com] (далі — «Сайт»).",
          "Використовуючи Сайт або заповнюючи будь-які форми на ньому, ви погоджуєтесь з умовами цієї Політики.",
          "Ми обробляємо персональні дані відповідно до Закону України «Про захист персональних даних» та Регламенту GDPR для користувачів з ЄС.",
        ],
      },
      {
        title: "2. Які дані ми збираємо",
        paragraphs: ["Дані, які ви надаєте добровільно:"],
        items: [
          "Ім’я та прізвище при заповненні форм.",
          "Контактні дані: email, номер телефону, Telegram.",
          "Інформація про ваш бізнес або проєкт, яку ви вказуєте у формах.",
          "Резюме та супровідні матеріали при відгуку на вакансії.",
        ],
        afterItems: [
          "Дані, що збираються автоматично:",
          "— IP-адреса та технічні дані пристрою та браузера.\n— Дані про поведінку на Сайті через Google Analytics 4.\n— Cookies та аналогічні технології відстеження.",
        ],
      },
      {
        title: "3. Як ми використовуємо дані",
        paragraphs: ["Зібрані дані використовуються виключно для:"],
        items: [
          "Обробки та відповіді на ваші заявки і запити.",
          "Надання інформації про послуги GVSPACE.",
          "Покращення якості Сайту та користувацького досвіду.",
          "Аналізу ефективності маркетингових кампаній.",
          "Розгляду заявок на вакансії.",
        ],
        afterItems: [
          "Ми не використовуємо ваші дані для автоматизованого прийняття рішень без вашої явної згоди.",
        ],
      },
      {
        title: "4. Передача даних третім особам",
        paragraphs: [
          "Ми не продаємо та не передаємо ваші персональні дані третім особам, за винятком випадків, необхідних для роботи Сайту:",
        ],
        items: [
          "Сервісів аналітики: Google Analytics, Looker Studio.",
          "Рекламні платформи: Meta Pixel, Google Ads.",
          "CRM та інструменти комунікації для обробки вашого запиту.",
        ],
        afterItems: [
          "Ми можемо розкривати дані на законну вимогу державних органів відповідно до чинного законодавства.",
        ],
      },
      {
        title: "5. Cookie та аналітика",
        paragraphs: ["Сайт використовує cookies для забезпечення коректної роботи та аналітики."],
        items: [
          "Технічні cookies — необхідні для роботи Сайту.",
          "Аналітичні cookies — Google Analytics 4 для аналізу відвідуваності.",
          "Маркетингові cookies — Meta Pixel, Google Ads для оцінки ефективності реклами.",
        ],
        afterItems: [
          "Ви можете керувати налаштуваннями cookies через банер при першому відвідуванні Сайту.",
        ],
      },
      {
        title: "6. Зберігання та захист даних",
        paragraphs: [
          "Дані заявок та звернень зберігаються не довше [24 місяців] з моменту останнього контакту. Резюме кандидатів — не довше [12 місяців].",
          "Ми застосовуємо технічні та організаційні заходи захисту даних, включаючи шифрування передачі даних (SSL/TLS).",
        ],
      },
      {
        title: "7. Ваші права",
        paragraphs: ["Відповідно до чинного законодавства ви маєте право:"],
        items: [
          "Отримати доступ до персональних даних, які ми обробляємо.",
          "Вимагати виправлення неточних або застарілих даних.",
          "Вимагати видалення ваших персональних даних («право на забуття»).",
          "Відкликати згоду на обробку даних у будь-який час.",
          "Отримати копію ваших даних у машиночитуваному форматі.",
        ],
        afterItems: [
          "Для реалізації будь-якого з цих прав надішліть запит на [legal@gvspace.com]. Ми відповімо протягом 30 календарних днів.",
        ],
      },
      {
        title: "8. Зміни до політики",
        paragraphs: [
          "Ми можемо час від часу оновлювати цю Політику. Актуальна версія завжди доступна на цій сторінці із зазначенням дати останньої редакції.",
          "Якщо зміни є суттєвими, ми повідомимо вас через email або повідомленням на Сайті.",
        ],
      },
      {
        title: "9. Контакти",
        paragraphs: [
          "З питань щодо обробки персональних даних звертайтесь:",
          "Email: [legal@gvspace.com]",
          "Адреса: [юридична адреса]",
        ],
      },
    ] satisfies LegalSection[],
  },
  en: {
    eyebrow: "LEGAL",
    title: "Privacy Policy",
    updated: "Effective date: [DD.MM.YYYY]  ·  Last revised: [DD.MM.YYYY]",
    intro:
      "We respect your privacy and take care of your personal data. This policy explains what data GVSPACE collects, why we use it, and what rights you have.",
    contents: "Contents",
    sections: [
      {
        title: "1. General provisions",
        paragraphs: [
          "This Privacy Policy applies to the GVSPACE website and to enquiries you send through its forms, by email, or through other communication channels.",
          "By using the website, you confirm that you have read this Policy. If you do not agree with it, please do not provide us with your personal data.",
        ],
      },
      {
        title: "2. Data we collect",
        paragraphs: [
          "We may receive information you provide voluntarily and technical data generated while you use the website:",
        ],
        items: [
          "your name, telephone number, email address, and company name;",
          "information about your project or enquiry submitted through a form;",
          "your IP address, device and browser type, pages viewed, and visit time;",
          "cookie and similar technology data where you have provided consent.",
        ],
      },
      {
        title: "3. How we use data",
        paragraphs: [
          "We process personal data only for specific and lawful purposes, including to:",
        ],
        items: [
          "respond to enquiries and provide consultations;",
          "prepare proposals and deliver requested services;",
          "improve the performance, usability, and security of the website;",
          "comply with legal requirements and protect our legitimate interests.",
        ],
      },
      {
        title: "4. Sharing data with third parties",
        paragraphs: [
          "We do not sell your personal data. We may share it with trusted analytics, advertising, CRM, and communication providers only when required to operate the website or process your enquiry.",
          "We may also disclose data in response to a lawful request from public authorities.",
        ],
      },
      {
        title: "5. Cookies and analytics",
        paragraphs: [
          "The website may use essential cookies to operate correctly and analytics cookies to help us understand how visitors use it. You can restrict or delete cookies in your browser settings, although this may affect some features.",
        ],
      },
      {
        title: "6. Data storage and protection",
        paragraphs: [
          "Enquiry data is stored only for as long as necessary for its processing purpose. Candidate résumés are retained for no longer than 12 months.",
          "We apply technical and organisational safeguards, including SSL/TLS encryption for data in transit.",
        ],
      },
      {
        title: "7. Your rights",
        paragraphs: ["You may contact us to:"],
        items: [
          "confirm whether we process your personal data and request a copy;",
          "correct inaccurate or incomplete information;",
          "request deletion or restriction of processing;",
          "withdraw consent where consent is the legal basis for processing;",
          "object to processing where permitted by law.",
        ],
      },
      {
        title: "8. Changes to this policy",
        paragraphs: [
          "We may update this Policy from time to time. The current version is always available on this page, with the latest revision date shown at the top.",
        ],
      },
      {
        title: "9. Contact us",
        paragraphs: [
          "If you have any questions about this Policy or our processing of personal data, contact us at email@gvspace.com.",
        ],
      },
    ] satisfies LegalSection[],
  },
} as const satisfies Record<Locale, unknown>;

const termsContent = {
  uk: {
    eyebrow: "LEGAL",
    title: "Правила використання сайту",
    updated: "Дата набрання чинності: [ДД.ММ.РРРР]  ·  Остання редакція: [ДД.ММ.РРРР]",
    contents: "Зміст",
    sections: [
      {
        title: "1. Загальні положення",
        paragraphs: [
          "Ці Правила використання регулюють доступ та використання вебсайту GVSPACE (далі — «Сайт»), що знаходиться за адресою [gvspace.com].",
          "Отримуючи доступ до Сайту або використовуючи його, ви погоджуєтесь з цими Правилами.",
          "Власник Сайту: [Повна назва юридичної особи або ФОП], реєстраційний номер [ЄДРПОУ/ІПН], юридична адреса: [адреса].",
        ],
      },
      {
        title: "2. Використання сайту",
        paragraphs: [
          "Ви зобов’язуєтесь використовувати Сайт виключно в законних цілях та у спосіб, що не порушує права третіх осіб.",
          "Забороняється:",
        ],
        items: [
          "Використовувати Сайт з метою поширення незаконного або шкідливого контенту.",
          "Здійснювати спроби несанкціонованого доступу до систем Сайту.",
          "Копіювати, відтворювати або поширювати матеріали Сайту без письмового дозволу.",
          "Використовувати автоматизовані засоби для збору даних (парсинг, скрейпінг).",
        ],
        afterItems: [
          "Ми залишаємо за собою право обмежити або припинити доступ до Сайту будь-якому користувачу без попереднього повідомлення.",
        ],
      },
      {
        title: "3. Інтелектуальна власність",
        paragraphs: [
          "Усі матеріали, розміщені на Сайті — тексти, зображення, логотипи, графіка, код — є власністю GVSPACE або використовуються на підставі ліцензій.",
          "Назва «GVSPACE», логотип та фірмовий стиль є об’єктами інтелектуальної власності та охороняються відповідно до чинного законодавства України.",
        ],
      },
      {
        title: "4. Відповідальність",
        paragraphs: [
          "Сайт надається «як є». Ми не гарантуємо безперебійну роботу Сайту та не несемо відповідальності за:",
        ],
        items: [
          "Технічні збої або тимчасову недоступність Сайту.",
          "Будь-які прямі чи непрямі збитки, пов’язані з використанням Сайту.",
          "Точність, повноту або актуальність інформації, розміщеної на Сайті.",
        ],
      },
      {
        title: "5. Посилання на сторонні ресурси",
        paragraphs: [
          "Сайт може містити посилання на зовнішні вебсайти. GVSPACE не несе відповідальності за зміст, точність або доступність сторонніх ресурсів.",
          "Перехід на зовнішні сайти здійснюється на розсуд та відповідальність користувача.",
        ],
      },
      {
        title: "6. Зміни до правил",
        paragraphs: [
          "Ми залишаємо за собою право вносити зміни до цих Правил у будь-який час. Актуальна версія завжди доступна на цій сторінці.",
          "Продовження використання Сайту після публікації змін означає вашу згоду з оновленими Правилами.",
        ],
      },
      {
        title: "7. Контакти",
        paragraphs: [
          "З питань щодо обробки персональних даних звертайтесь:",
          "Email: [legal@gvspace.com]",
          "Адреса: [юридична адреса]",
        ],
      },
    ] satisfies LegalSection[],
  },
  en: {
    eyebrow: "LEGAL",
    title: "Website Terms of Use",
    updated: "Effective date: [DD.MM.YYYY]  ·  Last revised: [DD.MM.YYYY]",
    contents: "Contents",
    sections: [
      {
        title: "1. General provisions",
        paragraphs: [
          "These Terms of Use govern access to and use of the GVSPACE website (the “Website”), available at [gvspace.com].",
          "By accessing or using the Website, you agree to these Terms.",
          "Website owner: [Full legal entity or sole proprietor name], registration number [registration/tax number], registered address: [address].",
        ],
      },
      {
        title: "2. Use of the website",
        paragraphs: [
          "You agree to use the Website only for lawful purposes and in a way that does not infringe the rights of third parties.",
          "You must not:",
        ],
        items: [
          "Use the Website to distribute illegal or harmful content.",
          "Attempt to gain unauthorised access to Website systems.",
          "Copy, reproduce, or distribute Website materials without written permission.",
          "Use automated means to collect data, including parsing or scraping.",
        ],
        afterItems: [
          "We reserve the right to restrict or terminate any user’s access to the Website without prior notice.",
        ],
      },
      {
        title: "3. Intellectual property",
        paragraphs: [
          "All materials on the Website, including text, images, logos, graphics, and code, are owned by GVSPACE or used under licence.",
          "The GVSPACE name, logo, and visual identity are protected intellectual property under applicable Ukrainian law.",
        ],
      },
      {
        title: "4. Liability",
        paragraphs: [
          "The Website is provided “as is”. We do not guarantee uninterrupted operation and are not liable for:",
        ],
        items: [
          "Technical failures or temporary unavailability of the Website.",
          "Any direct or indirect loss connected with use of the Website.",
          "The accuracy, completeness, or currency of information published on the Website.",
        ],
      },
      {
        title: "5. Links to third-party resources",
        paragraphs: [
          "The Website may contain links to external websites. GVSPACE is not responsible for the content, accuracy, or availability of third-party resources.",
          "You access external websites at your own discretion and risk.",
        ],
      },
      {
        title: "6. Changes to these terms",
        paragraphs: [
          "We reserve the right to change these Terms at any time. The current version is always available on this page.",
          "Continued use of the Website after changes are published constitutes acceptance of the updated Terms.",
        ],
      },
      {
        title: "7. Contact us",
        paragraphs: [
          "For questions about personal data processing, contact us at:",
          "Email: [legal@gvspace.com]",
          "Address: [registered address]",
        ],
      },
    ] satisfies LegalSection[],
  },
} as const satisfies Record<Locale, unknown>;

function LegalDocument({ locale, kind }: { locale: Locale; kind: LegalKind }) {
  const document = kind === "privacy" ? privacyContent[locale] : termsContent[locale];
  const sections = document.sections as readonly LegalSection[];
  const navigationSections = sections.map((section) => ({
    id: `section-${section.title.split(".")[0]}`,
    title: section.title,
  }));

  return (
    <main className="privacy-page">
      <header className="privacy-hero container">
        <p className="privacy-eyebrow mono">{document.eyebrow}</p>
        <h1>{document.title}</h1>
        <p className="privacy-updated mono">{document.updated}</p>
      </header>
      <div className="privacy-layout container">
        <aside className="privacy-aside">
          <LegalNavigation label={document.contents} sections={navigationSections} />
        </aside>
        <article className="privacy-document">
          {sections.map((section) => (
            <section key={section.title} id={`section-${section.title.split(".")[0]}`}>
              <span className="privacy-section-number mono">
                {section.title.split(".")[0].padStart(2, "0")}
              </span>
              <h2>{section.title.replace(/^\d+\.\s*/, "")}</h2>
              {section.paragraphs.map((paragraph) => (
                <p key={paragraph}>{paragraph}</p>
              ))}
              {section.items && (
                <ul>
                  {section.items.map((item) => (
                    <li key={item}>{item}</li>
                  ))}
                </ul>
              )}
              {section.afterItems?.map((paragraph) => (
                <p className="privacy-after-items" key={paragraph}>
                  {paragraph}
                </p>
              ))}
            </section>
          ))}
        </article>
      </div>
    </main>
  );
}

export function LegalPage({ locale, kind }: { locale: Locale; kind: LegalKind }) {
  return <LegalDocument locale={locale} kind={kind} />;
}
