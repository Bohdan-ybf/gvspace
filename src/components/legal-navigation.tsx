"use client";

import { useEffect, useState } from "react";

type NavigationSection = {
  id: string;
  title: string;
};

export function LegalNavigation({
  label,
  sections,
}: {
  label: string;
  sections: NavigationSection[];
}) {
  const [activeId, setActiveId] = useState(sections[0]?.id ?? "");

  useEffect(() => {
    let animationFrame = 0;

    const updateActiveSection = () => {
      const activationLine = 150;
      let currentId = sections[0]?.id ?? "";

      const isAtPageEnd =
        window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 8;

      if (isAtPageEnd) {
        setActiveId(sections.at(-1)?.id ?? currentId);
        return;
      }

      for (const section of sections) {
        const element = document.getElementById(section.id);
        if (element && element.getBoundingClientRect().top <= activationLine) {
          currentId = section.id;
        }
      }

      setActiveId(currentId);
    };

    const handleScroll = () => {
      cancelAnimationFrame(animationFrame);
      animationFrame = requestAnimationFrame(updateActiveSection);
    };

    updateActiveSection();
    window.addEventListener("scroll", handleScroll, { passive: true });
    window.addEventListener("resize", handleScroll);

    return () => {
      cancelAnimationFrame(animationFrame);
      window.removeEventListener("scroll", handleScroll);
      window.removeEventListener("resize", handleScroll);
    };
  }, [sections]);

  return (
    <nav aria-label={label}>
      <span className="mono">{label}</span>
      {sections.map((section) => (
        <a
          className={activeId === section.id ? "is-active" : undefined}
          key={section.id}
          href={`#${section.id}`}
          aria-current={activeId === section.id ? "location" : undefined}
          onClick={() => setActiveId(section.id)}
        >
          {section.title}
        </a>
      ))}
    </nav>
  );
}
