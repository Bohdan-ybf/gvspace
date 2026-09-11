export default {
  header: {
    serviceDirections: [
      { slug: "strategy", title: "Стратегія" },
      { slug: "marketing", title: "Маркетинг" },
      { slug: "development", title: "IT-розробка" },
      { slug: "content", title: "Контент & Продакшн" },
    ],
    mainNavigationLabel: "Головна навігація",
    mobileAction: "Хочу ріст",
    mobileNavigationLabel: "Мобільна навігація",
  },
  systemTransition: {
    eyebrow: "НАШ ПІДХІД",
    title: "Від Хаосу до Системи",
    beforeLabel: "ХАОС (ТОЧКА А)",
    beforeTitle: "Дії як лотерея",
    beforeDescription: "Втрачені бюджети, неузгоджені звіти, рішення на основі інтуїції.",
    afterLabel: "GVSPACE (ТОЧКА Б)",
    afterTitle: "Простір для рішень",
    afterDescription: "Прозорі дашборди, масштабування як свідомий крок, а не випадковість.",
  },
  footer: {
    socialNavigationLabel: "Соціальні мережі",
    legalNavigationLabel: "Юридична інформація",
  },
} as const;
