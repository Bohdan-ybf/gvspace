import type { Locale } from "@/i18n";

export function TechnologiesOverviewSection({ locale }: { locale: Locale }) {
  const uk = locale === "uk";
  const stats = uk
    ? [
        ["30+", "ІНСТРУМЕНТІВ У СТЕКУ"],
        ["4", "НАПРЯМКИ ЗАСТОСУВАННЯ"],
        ["6", "CLOUD-ПРОВАЙДЕРІВ"],
        ["100%", "КАСТОМНА АНАЛІТИКА"],
      ]
    : [
        ["30+", "TOOLS IN THE STACK"],
        ["4", "AREAS OF APPLICATION"],
        ["6", "CLOUD PROVIDERS"],
        ["100%", "CUSTOM ANALYTICS"],
      ];

  return (
    <section className="technologies-overview section container">
      <div className="technologies-philosophy">
        <span className="mono">{uk ? "НАША ФІЛОСОФІЯ" : "OUR PHILOSOPHY"}</span>
        <p>
          {uk ? (
            <>
              Більшість агенцій мають один «улюблений» інструмент і застосовують його скрізь. Ми
              обираємо інструмент <b>під задачу клієнта</b>, а не навпаки. Саме тому наші системи не
              ламаються під час росту.
            </>
          ) : (
            <>
              Most agencies use one favorite tool everywhere. We select the tool{" "}
              <b>for the client’s task</b>, not the other way around. That is why our systems do not
              break as they grow.
            </>
          )}
        </p>
      </div>
      <div className="technologies-stats">
        {stats.map(([value, label]) => (
          <article key={label}>
            <strong>{value}</strong>
            <span className="mono">{label}</span>
          </article>
        ))}
      </div>
    </section>
  );
}
