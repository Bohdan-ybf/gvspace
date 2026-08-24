import type { Locale } from "@/i18n";

export function CareersValuesSection({ locale }: { locale: Locale }) {
  const uk = locale === "uk";
  const values = uk
    ? [
        [
          "Власна зона відповідальності",
          "Ви власник результату, а не виконавець задач. Ми обговорюємо ціль, а шлях — за вами.",
        ],
        [
          "Реальні проєкти, реальний вплив",
          "Кожен проєкт — це бізнес, який росте або не росте завдяки вашій роботі.",
        ],
        ["Remote-first", "Оцінюємо за результатом, а не за годинами онлайн. Де ви — не важливо."],
        ["Прозора економіка", "Ставка, умови і очікування — зафіксовані до старту. Без сюрпризів."],
      ]
    : [
        [
          "Your own area of responsibility",
          "You own the result rather than simply completing tasks. We agree on the goal; the route is yours.",
        ],
        [
          "Real projects, real impact",
          "Every project is a business that grows — or does not — because of your work.",
        ],
        ["Remote-first", "We evaluate results, not hours online. Your location does not matter."],
        [
          "Transparent economics",
          "Compensation, terms, and expectations are agreed before the start. No surprises.",
        ],
      ];

  return (
    <section className="careers-values section container">
      <span className="mono">{uk ? "ЧОМУ GVSPACE" : "WHY GVSPACE"}</span>
      <div className="careers-values-grid">
        {values.map(([title, description], index) => (
          <article key={title}>
            <span className="mono">[ 0{index + 1} ]</span>
            <h3>{title}</h3>
            <p>{description}</p>
          </article>
        ))}
      </div>
    </section>
  );
}
