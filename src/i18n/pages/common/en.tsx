export default {
  header: {
    serviceDirections: [
      { slug: "strategy", title: "Strategy" },
      { slug: "marketing", title: "Marketing" },
      { slug: "development", title: "IT Development" },
      { slug: "content", title: "Content & Production" },
    ],
    companyLinks: [
      {
        href: "/about",
        title: "About us",
        description:
          "We are your strategic partner, turning marketing chaos into a predictable growth system.",
      },
      {
        href: "/reviews",
        title: "Clients & reviews",
        description:
          "We do not collect logos. We build systems and ask clients to speak about the results.",
      },
      {
        href: "/team",
        title: "Team",
        description:
          "Strategists, engineers, and marketers united around one outcome: managed business growth.",
      },
      {
        href: "/contacts",
        title: "Partnership",
        description:
          "We bring together trusted businesses and experts to create partnerships with mutual value.",
      },
      {
        href: "/careers",
        title: "Careers",
        description:
          "We seek people who value outcomes over process. If you are an expert in your field, let’s talk.",
      },
    ],
    mainNavigationLabel: "Main navigation",
    mobileAction: "Let’s grow",
    mobileNavigationLabel: "Mobile navigation",
  },
  systemTransition: {
    eyebrow: "OUR APPROACH",
    title: "From Chaos to System",
    action: "Book a Clarity Session",
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
