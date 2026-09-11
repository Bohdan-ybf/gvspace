export default {
  header: {
    serviceDirections: [
      { slug: "strategy", title: "Strategy" },
      { slug: "marketing", title: "Marketing" },
      { slug: "development", title: "IT Development" },
      { slug: "content", title: "Content & Production" },
    ],
    mainNavigationLabel: "Main navigation",
    mobileAction: "Let’s grow",
    mobileNavigationLabel: "Mobile navigation",
  },
  systemTransition: {
    eyebrow: "OUR APPROACH",
    title: "From Chaos to System",
    beforeLabel: "CHAOS (POINT A)",
    beforeTitle: "Actions as a lottery",
    beforeDescription: "Lost budgets, disconnected reports, decisions based on intuition.",
    afterLabel: "GVSPACE (POINT B)",
    afterTitle: "Space for decisions",
    afterDescription: "Transparent dashboards and deliberate scaling.",
  },
  footer: {
    socialNavigationLabel: "Social media",
    legalNavigationLabel: "Legal information",
  },
} as const;
