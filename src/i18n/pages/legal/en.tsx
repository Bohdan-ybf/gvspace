type LegalSection = {
  title: string;
  paragraphs: string[];
  items?: string[];
  afterItems?: string[];
};

export default {
  privacy: {
    eyebrow: "LEGAL",
    title: "Privacy Policy",
    updated: "Effective date: [DD.MM.YYYY]  ·  Last revised: [DD.MM.YYYY]",
    intro:
      "We respect your privacy and take care of your personal data. This policy explains what data GVSPACE collects, why we use it, and what rights you have.",
    contents: "Contents",
    sections: [
      {
        title: "1. General provisions",
        paragraphs: [
          "This Privacy Policy applies to the GVSPACE website and to enquiries you send through its forms, by email, or through other communication channels.",
          "By using the website, you confirm that you have read this Policy. If you do not agree with it, please do not provide us with your personal data.",
        ],
      },
      {
        title: "2. Data we collect",
        paragraphs: [
          "We may receive information you provide voluntarily and technical data generated while you use the website:",
        ],
        items: [
          "your name, telephone number, email address, and company name;",
          "information about your project or enquiry submitted through a form;",
          "your IP address, device and browser type, pages viewed, and visit time;",
          "cookie and similar technology data where you have provided consent.",
        ],
      },
      {
        title: "3. How we use data",
        paragraphs: [
          "We process personal data only for specific and lawful purposes, including to:",
        ],
        items: [
          "respond to enquiries and provide consultations;",
          "prepare proposals and deliver requested services;",
          "improve the performance, usability, and security of the website;",
          "comply with legal requirements and protect our legitimate interests.",
        ],
      },
      {
        title: "4. Sharing data with third parties",
        paragraphs: [
          "We do not sell your personal data. We may share it with trusted analytics, advertising, CRM, and communication providers only when required to operate the website or process your enquiry.",
          "We may also disclose data in response to a lawful request from public authorities.",
        ],
      },
      {
        title: "5. Cookies and analytics",
        paragraphs: [
          "The website may use essential cookies to operate correctly and analytics cookies to help us understand how visitors use it. You can restrict or delete cookies in your browser settings, although this may affect some features.",
        ],
      },
      {
        title: "6. Data storage and protection",
        paragraphs: [
          "Enquiry data is stored only for as long as necessary for its processing purpose. Candidate résumés are retained for no longer than 12 months.",
          "We apply technical and organisational safeguards, including SSL/TLS encryption for data in transit.",
        ],
      },
      {
        title: "7. Your rights",
        paragraphs: ["You may contact us to:"],
        items: [
          "confirm whether we process your personal data and request a copy;",
          "correct inaccurate or incomplete information;",
          "request deletion or restriction of processing;",
          "withdraw consent where consent is the legal basis for processing;",
          "object to processing where permitted by law.",
        ],
      },
      {
        title: "8. Changes to this policy",
        paragraphs: [
          "We may update this Policy from time to time. The current version is always available on this page, with the latest revision date shown at the top.",
        ],
      },
      {
        title: "9. Contact us",
        paragraphs: [
          "If you have any questions about this Policy or our processing of personal data, contact us at email@gvspace.com.",
        ],
      },
    ] satisfies LegalSection[],
  },
  terms: {
    eyebrow: "LEGAL",
    title: "Website Terms of Use",
    updated: "Effective date: [DD.MM.YYYY]  ·  Last revised: [DD.MM.YYYY]",
    contents: "Contents",
    sections: [
      {
        title: "1. General provisions",
        paragraphs: [
          "These Terms of Use govern access to and use of the GVSPACE website (the “Website”), available at [gvspace.com].",
          "By accessing or using the Website, you agree to these Terms.",
          "Website owner: [Full legal entity or sole proprietor name], registration number [registration/tax number], registered address: [address].",
        ],
      },
      {
        title: "2. Use of the website",
        paragraphs: [
          "You agree to use the Website only for lawful purposes and in a way that does not infringe the rights of third parties.",
          "You must not:",
        ],
        items: [
          "Use the Website to distribute illegal or harmful content.",
          "Attempt to gain unauthorised access to Website systems.",
          "Copy, reproduce, or distribute Website materials without written permission.",
          "Use automated means to collect data, including parsing or scraping.",
        ],
        afterItems: [
          "We reserve the right to restrict or terminate any user’s access to the Website without prior notice.",
        ],
      },
      {
        title: "3. Intellectual property",
        paragraphs: [
          "All materials on the Website, including text, images, logos, graphics, and code, are owned by GVSPACE or used under licence.",
          "The GVSPACE name, logo, and visual identity are protected intellectual property under applicable Ukrainian law.",
        ],
      },
      {
        title: "4. Liability",
        paragraphs: [
          "The Website is provided “as is”. We do not guarantee uninterrupted operation and are not liable for:",
        ],
        items: [
          "Technical failures or temporary unavailability of the Website.",
          "Any direct or indirect loss connected with use of the Website.",
          "The accuracy, completeness, or currency of information published on the Website.",
        ],
      },
      {
        title: "5. Links to third-party resources",
        paragraphs: [
          "The Website may contain links to external websites. GVSPACE is not responsible for the content, accuracy, or availability of third-party resources.",
          "You access external websites at your own discretion and risk.",
        ],
      },
      {
        title: "6. Changes to these terms",
        paragraphs: [
          "We reserve the right to change these Terms at any time. The current version is always available on this page.",
          "Continued use of the Website after changes are published constitutes acceptance of the updated Terms.",
        ],
      },
      {
        title: "7. Contact us",
        paragraphs: [
          "For questions about personal data processing, contact us at:",
          "Email: [legal@gvspace.com]",
          "Address: [registered address]",
        ],
      },
    ] satisfies LegalSection[],
  },
} as const;
