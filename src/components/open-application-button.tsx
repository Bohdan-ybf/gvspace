"use client";

import { useEffect, useId, useRef, useState, type ReactNode } from "react";
import { createPortal } from "react-dom";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";
import { VacancyApplicationForm } from "./vacancy-application-form";

export function OpenApplicationButton({
  locale,
  children,
}: {
  locale: Locale;
  children: ReactNode;
}) {
  const [isOpen, setIsOpen] = useState(false);
  const triggerRef = useRef<HTMLButtonElement>(null);

  const closeModal = () => {
    setIsOpen(false);
    triggerRef.current?.focus();
  };

  return (
    <>
      <button
        className="btn btn-primary"
        type="button"
        ref={triggerRef}
        onClick={() => setIsOpen(true)}
      >
        {children}
      </button>
      {isOpen ? <VacancyApplicationModal locale={locale} onClose={closeModal} /> : null}
    </>
  );
}

function VacancyApplicationModal({ locale, onClose }: { locale: Locale; onClose: () => void }) {
  const application = getTranslations("vacancies", locale).application;
  const titleId = useId();
  const dialogRef = useRef<HTMLDivElement>(null);
  const [submitted, setSubmitted] = useState(false);
  const onCloseRef = useRef(onClose);

  useEffect(() => {
    onCloseRef.current = onClose;
  }, [onClose]);

  useEffect(() => {
    const previousOverflow = document.body.style.overflow;
    document.body.style.overflow = "hidden";
    dialogRef.current?.focus();

    const onKey = (event: KeyboardEvent) => {
      if (event.key === "Escape") onCloseRef.current();
    };

    document.addEventListener("keydown", onKey);
    return () => {
      document.body.style.overflow = previousOverflow;
      document.removeEventListener("keydown", onKey);
    };
  }, []);

  return createPortal(
    <div className="lead-modal" onClick={onClose}>
      <div
        className="lead-modal-dialog vacancy-application-dialog"
        ref={dialogRef}
        tabIndex={-1}
        role="dialog"
        aria-modal="true"
        aria-labelledby={titleId}
        onClick={(event) => event.stopPropagation()}
      >
        {submitted ? (
          <div className="lead-modal-success vacancy-application-success">
            <h2 id={titleId}>
              {application.successTitle.split("\n").map((line) => (
                <span key={line}>
                  {line}
                  <br />
                </span>
              ))}
            </h2>
            <p>{application.successDescription}</p>
          </div>
        ) : (
          <VacancyApplicationForm locale={locale} onSubmit={() => setSubmitted(true)} />
        )}
      </div>
    </div>,
    document.body,
  );
}
