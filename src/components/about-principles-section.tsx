import type { Locale } from "@/i18n";

export function AboutPrinciplesSection({ locale }: { locale: Locale }) {
  const uk = locale === "uk";

  return (
    <section className="about-principles section container">
      <div className="about-team">
        <div className="about-team-photo">{uk ? "ФОТО" : "PHOTO"}</div>
        <div>
          <h2>
            {uk
              ? "Наші люди — ваша операційна підтримка"
              : "Our people are your operational support"}
          </h2>
          <p>
            {uk
              ? "У нас немає «потокових» менеджерів. Над вашим проєктом працюють архітектори, аналітики та креативники, які стають частиною вашої команди."
              : "There are no assembly-line managers here. Architects, analysts, and creatives become part of your team."}
          </p>
        </div>
      </div>

      <div className="about-values">
        <h2>{uk ? "Наші цінності" : "Our values"}</h2>
        <span>{uk ? "Візія" : "Vision"}</span>
        <div className="about-values-grid">
          {(uk
            ? [
                [
                  "Ясність перед зростанням",
                  "Ми не масштабуємо хаос. Якщо система зламана, вливання бюджету лише прискорить її крах.",
                ],
                [
                  "Система замість хаосу",
                  "Жодних разових акцій. Тільки довгострокові архітектурні рішення.",
                ],
                [
                  "Прозорість на основі даних",
                  "Геть суб’єктивність. Тільки цифри, дашборди та факти.",
                ],
                [
                  "Радикальне партнерство",
                  "Ми інтегруємося у ваш бізнес як операційне розширення команди.",
                ],
              ]
            : [
                [
                  "Clarity before growth",
                  "We do not scale chaos. If the system is broken, more budget only accelerates its collapse.",
                ],
                [
                  "System instead of chaos",
                  "No one-off actions. Only long-term architectural solutions.",
                ],
                [
                  "Data-driven transparency",
                  "No subjectivity. Only numbers, dashboards, and facts.",
                ],
                [
                  "Radical partnership",
                  "We integrate into your business as an operational extension of the team.",
                ],
              ]
          ).map(([title, description]) => (
            <article key={title}>
              <h3>{title}</h3>
              <p>{description}</p>
            </article>
          ))}
        </div>
      </div>

      <div className="about-vision">
        <span>{uk ? "Наші цінності" : "Our values"}</span>
        <h2>{uk ? "Візія" : "Vision"}</h2>
        <div>
          <div>
            <p>
              {uk
                ? "Ми створюємо стандарт керованого зростання. Наша візія — перетворити цифровий маркетинг із «лотереї» на точну інженерну систему."
                : "We are creating a standard for manageable growth, turning digital marketing from a lottery into an engineering system."}
            </p>
            <p>
              {uk
                ? "Ми бачимо світ, де власник бізнесу не перебуває у стані постійного стресу від непередбачуваності алгоритмів, а відчуває спокій капіталу, який має чітку карту та справні прилади."
                : "We see a world where business owners are free from the constant stress of unpredictable algorithms and have a clear map with reliable instruments."}
            </p>
          </div>
          <p>
            {uk
              ? "GVSPACE — це центр стратегічного керування, де кожен бізнес отримує свій індивідуальний простір для масштабування без хаосу."
              : "GVSPACE is a strategic control center where every business gets an individual space to scale without chaos."}
          </p>
        </div>
      </div>
    </section>
  );
}
