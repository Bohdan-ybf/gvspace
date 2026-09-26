export default {
  page: {
    heroTitle: "Niches where we know the specifics",
    heroTitleSecond: "from the inside",
    heroDescription:
      "Every industry has its own economics, decision cycle, and pain points. We do not transfer one niche's template to another.",
    heroAction: "Discuss your project",
    statementEyebrow: "WHY THIS MATTERS",
    statementLead: "An e-commerce strategy does not work for SaaS.",
    statementEmphasis:
      "We build a system around a specific niche's economics, rather than adapting a universal template.",
    listEyebrow: "AREAS OF EXPERTISE",
    listTitle: "6 niches where we work systematically",
    projectsLabel: "projects",
    casesAction: "View cases",
    processEyebrow: "WORK PROCESS",
    processTitle: "How we work",
    processStat: "[50+] projects",
    processStatNote: "over [X] years of work",
    projectsAction: "Our projects",
    casesEyebrow: "CASES",
    casesTitle: "From idea to result",
    contactEyebrow: "THE SPECIFICS OF YOUR BUSINESS",
    contactTitle: "We’ll break down the specifics of your business",
    contactTitleSecond: "on a Clarity Session",
    contactIntro: "30 minutes on niche economics, the decision cycle, and the next step.",
    items: [
      {
        slug: "ecommerce",
        title: "E-commerce",
        description:
          "Online stores and D2C brands, where marketing and sales live in one cycle. The goal is not just to drive traffic, but to keep unit economics in the black: lower CAC, raise LTV and repeat purchases without burning the budget while scaling. We work with seasonality, complex catalogs, and connecting ads to the CRM.",
        tags: ["PERFORMANCE ADS", "RETENTION", "CRO"],
      },
      {
        slug: "saas",
        title: "SaaS / IT products",
        description:
          "Growth depends on activation, retention, and LTV, not on the number of leads. We connect product, analytics, and demand into one loop.",
        tags: ["Activation", "LTV", "Analytics"],
      },
      {
        slug: "edtech",
        title: "EdTech / Education products",
        description:
          "The decision is long: trust, the program, and the cohort matter more than a click. We build the path from first contact to payment without forcing a template funnel.",
        tags: ["Long cycle", "Trust", "Cohorts"],
      },
      {
        slug: "high-ticket",
        title: "High-ticket services",
        description:
          "Leads are few and the cost of a mistake is high. We work on qualification and the sale, not on traffic volume for its own sake.",
        tags: ["High ticket", "Qualification", "Sales"],
      },
      {
        slug: "health",
        title: "Medicine / Health",
        description:
          "Trust and communication limits set the rules. We do not move an e-commerce offer into a clinic: we count bookings, the patient path, and what can be said in public.",
        tags: ["Trust", "Booking", "Limits"],
      },
      {
        slug: "real-estate",
        title: "Real estate",
        description:
          "A long cycle, a high ticket, and local demand. We build demand for the property, not generic “real estate” traffic.",
        tags: ["Long cycle", "Local demand", "Properties"],
      },
    ],
    steps: [
      {
        icon: "clarity",
        title: "Clarity",
        points: ["Strategic session", "Customer Discovery", "Market Research"],
      },
      {
        icon: "architecture",
        title: "Architecture",
        points: ["UX/UI design", "Media plan and funnel", "Technical architecture"],
      },
      {
        icon: "execution",
        title: "Execution",
        points: ["Development / campaign launch", "CRM and analytics integration", "Testing"],
      },
      {
        icon: "growth",
        title: "Growth",
        points: ["Results monitoring", "Weekly reporting", "Scaling"],
      },
    ],
  },
} as const;
