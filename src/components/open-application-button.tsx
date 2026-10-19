"use client";

import Image from "next/image";
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

  return (
    <>
      <button className="btn btn-primary" type="button" onClick={() => setIsOpen(true)}>
        {children}
      </button>
      {isOpen ? <VacancyApplicationModal locale={locale} onClose={() => setIsOpen(false)} /> : null}
    </>
  );
}

function VacancyApplicationModal({ locale, onClose }: { locale: Locale; onClose: () => void }) {
  const text = getTranslations("global", locale).contact;
  const titleId = useId();
  const closeRef = useRef<HTMLButtonElement>(null);
  const [submitted, setSubmitted] = useState(false);
  const onCloseRef = useRef(onClose);

  useEffect(() => {
    onCloseRef.current = onClose;
  }, [onClose]);

  useEffect(() => {
    const previousOverflow = document.body.style.overflow;
    document.body.style.overflow = "hidden";
    closeRef.current?.focus();

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
        role="dialog"
        aria-modal="true"
        aria-labelledby={titleId}
        onClick={(event) => event.stopPropagation()}
      >
        <button
          className="lead-modal-close"
          type="button"
          ref={closeRef}
          aria-label={text.close}
          onClick={onClose}
        >
          <Image src="/images/lead-modal/close.svg" width={25} height={25} alt="" />
        </button>
        {submitted ? (
          <div className="lead-modal-success vacancy-application-success">
            <h2 id={titleId}>
              {text.successTitle.split("\n").map((line) => (
                <span key={line}>
                  {line}
                  <br />
                </span>
              ))}
            </h2>
            <p>{text.successLead}</p>
          </div>
        ) : (
          <VacancyApplicationForm locale={locale} onSubmit={() => setSubmitted(true)} />
        )}
      </div>
    </div>,
    document.body,
  );
}
