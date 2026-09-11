export default {
  values: {
    items: [
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
    ],
    eyebrow: "WHY GVSPACE",
  },
  page: {
    heroTitle: (
      <>
        Building a team
        <br />
        that thinks systematically
      </>
    ),
    heroDescription:
      "We are looking for people who value results over process. If you are an expert in your field, we should talk.",
    workEyebrow: "HOW WE WORK",
    workDescription: (
      <>
        There is no micromanagement or unnecessary meetings.{" "}
        <b>There is ownership, clear goals, and a team</b> that supports rather than controls.
      </>
    ),
    vacanciesEyebrow: "OPEN POSITIONS",
  },
  banner: {
    title: "We are looking for systematic thinkers",
    description:
      "If you are an expert who wants to work where results matter more than process, get in touch.",
    action: "View vacancies",
  },
  openApplication: {
    title: (
      <>
        Did not find your position?
        <br />
        Send an open application
      </>
    ),
    description:
      "If you are an expert who wants to become part of GVSPACE, write to us. We keep a list of people we want to work with.",
    action: "Send application",
  },
} as const;
