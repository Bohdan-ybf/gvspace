export default {
  header: {
    serviceDirections: [
      { slug: "strategy", title: "Стратегія" },
      { slug: "marketing", title: "Маркетинг" },
      { slug: "development", title: "IT-розробка" },
      { slug: "content", title: "Контент & Продакшн" },
    ],
    companyLinks: [
      {
        href: "/about",
        title: "Про компанію",
        description:
          "Ми — ваш стратегічний партнер, який перетворює хаос маркетингу на прогнозовану систему зростання.",
      },
      {
        href: "/reviews",
        title: "Клієнти і відгуки",
        description:
          "Ми не збираємо логотипи. Ми будуємо системи — і просимо клієнтів говорити про результат.",
      },
      {
        href: "/team",
        title: "Команда",
        description:
          "Команда стратегів, інженерів і маркетологів, яка об’єднує дисципліни навколо одного результату: керованого росту вашого бізнесу.",
      },
      {
        href: "/contacts",
        title: "Партнерство",
        description:
          "Об’єднуємо навколо себе бізнеси та фахівців, яким довіряємо. Разом ми будуємо простір, де партнерство приносить взаємну вигоду.",
      },
      {
        href: "/careers",
        title: "Вакансії",
        description:
          "Шукаємо людей, для яких результат важливіший за процес. Якщо ви фахівець у своїй зоні — нам є про що поговорити.",
      },
    ],
    mainNavigationLabel: "Головна навігація",
    mobileAction: "Хочу ріст",
    mobileNavigationLabel: "Мобільна навігація",
  },
  systemTransition: {
    eyebrow: "НАШ ПІДХІД",
    title: "Від Хаосу до Системи",
    action: "Записатися на Clarity Session",
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
