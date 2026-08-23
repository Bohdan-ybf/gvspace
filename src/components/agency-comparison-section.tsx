import type { Locale } from "@/i18n";

export function AgencyComparisonSection({ locale }: { locale: Locale }) {
  const uk = locale === "uk";
  const rows = uk
    ? [
        [
          "Підхід до стратегії",
          "Шаблон, що застосовують до всіх клієнтів",
          "Індивідуально, але без системного бачення",
          "Персональна архітектура під вашу Unit-економіку",
        ],
        [
          "Команда",
          "Молодший спеціаліст + плинність кадрів",
          "Одна людина на все — вузьке місце",
          "Кваліфіковані фахівці під кожен напрямок",
        ],
        [
          "Прозорість результатів",
          "Vanity metrics — охоплення, лайки",
          "Залежить від сумлінності виконавця",
          "Наскрізна аналітика, дашборди в реальному часі",
        ],
        [
          "Масштабування",
          "Система «тріщить» при рості бюджету",
          "Обмежено ресурсом однієї людини",
          "Архітектура готова до росту в декілька разів",
        ],
        [
          "Інтеграція IT + Маркетинг",
          "Розрізнені підрядники, дані втрачаються",
          "Залежний технічний стек",
          "Єдина система: маркетинг, IT, контент, аналітика",
        ],
        [
          "Комунікація",
          "Через акаунт-менеджера, довгі узгодження",
          "Пряма, але без структури",
          "Прямий доступ до команди, щотижнева звітність",
        ],
      ]
    : [
        [
          "Strategy approach",
          "A template applied to every client",
          "Individual, but without a systemic view",
          "A custom architecture built around your unit economics",
        ],
        [
          "Team",
          "Junior specialist and high turnover",
          "One person doing everything",
          "Qualified specialists for every direction",
        ],
        [
          "Result transparency",
          "Vanity metrics",
          "Depends on the contractor",
          "End-to-end analytics and live dashboards",
        ],
        [
          "Scaling",
          "The system breaks as budgets grow",
          "Limited by one person's capacity",
          "Architecture ready for multiple growth",
        ],
        [
          "IT and marketing",
          "Disconnected contractors and lost data",
          "A limited technical stack",
          "One system for marketing, IT, content, and analytics",
        ],
        [
          "Communication",
          "Account managers and long approvals",
          "Direct but unstructured",
          "Direct team access and weekly reporting",
        ],
      ];

  return (
    <section className="agency-comparison section container">
      <span className="mono">{uk ? "ЧОМУ GVSPACE" : "WHY GVSPACE"}</span>
      <h2>
        {uk
          ? "Порівняйте самі, кому довірити зростання"
          : "See who you should trust with your growth"}
      </h2>
      <p>
        {uk
          ? "Ми навмисно порівнюємо себе з конкретними компаніями, а з моделями роботи, які найчастіше зустрічаються на ринку. Оберіть те, що резонує з вашим досвідом."
          : "We compare working models commonly found in the market. Choose the one that matches your experience."}
      </p>
      <div className="comparison-table">
        <div className="comparison-head mono">
          <span />
          <span>{uk ? "Типова агенція" : "Typical agency"}</span>
          <span>{uk ? "Фрілансер" : "Freelancer"}</span>
          <strong>GVSPACE</strong>
        </div>
        {rows.map((row) => (
          <div className="comparison-row" key={row[0]}>
            <strong>{row[0]}</strong>
            <span>{row[1]}</span>
            <span>{row[2]}</span>
            <b>{row[3]}</b>
          </div>
        ))}
      </div>
    </section>
  );
}
