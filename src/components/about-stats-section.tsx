import type { Locale } from "@/i18n";

export function AboutStatsSection({ locale }: { locale: Locale }) {
  const uk = locale === "uk";
  const stats = uk
    ? [
        [
          "$10M+",
          "Сумарний капітал під управлінням",
          "Досвід масштабування бюджетів для бізнесів різного розміру.",
        ],
        ["15+", "Індустрій", "Від Highload E-commerce до складних SaaS-платформ."],
        ["4", "Вектори", "Аудит, IT, Маркетинг та Сенси — більше не окремі частини."],
      ]
    : [
        [
          "$10M+",
          "Total capital managed",
          "Experience scaling budgets for businesses of different sizes.",
        ],
        ["15+", "Industries", "From high-load e-commerce to complex SaaS platforms."],
        ["4", "Vectors", "Audit, IT, marketing, and content working as one system."],
      ];

  return (
    <section className="about-stats section container">
      <h2>
        {uk
          ? "Ми будуємо простір, де кожен клік та кожен долар працюють на масштаб вашого бізнесу."
          : "We build a space where every click and every dollar work toward scaling your business."}
      </h2>
      <div className="about-stats-grid">
        {stats.map(([value, title, description]) => (
          <article key={value}>
            <strong>{value}</strong>
            <h3>{title}</h3>
            <p>{description}</p>
          </article>
        ))}
      </div>
    </section>
  );
}
