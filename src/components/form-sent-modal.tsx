"use client";

import Image from "next/image";
import { useEffect, useId, useRef } from "react";
import { createPortal } from "react-dom";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";
import { lockPageScroll } from "@/lock-page-scroll";

type FormSentModalProps = {
  locale: Locale;
  onClose: () => void;
  title?: string;
  lead?: string;
  detail?: string;
};

export function FormSentModal({ locale, onClose, title, lead, detail }: FormSentModalProps) {
  const text = getTranslations("global", locale).contact;
  const titleId = useId();
  const closeRef = useRef<HTMLButtonElement>(null);
  const onCloseRef = useRef(onClose);

  useEffect(() => {
    onCloseRef.current = onClose;
  }, [onClose]);

  useEffect(() => {
    const unlockScroll = lockPageScroll();
    closeRef.current?.focus();

    const onKey = (event: KeyboardEvent) => {
      if (event.key === "Escape") onCloseRef.current();
    };

    document.addEventListener("keydown", onKey);
    return () => {
      unlockScroll();
      document.removeEventListener("keydown", onKey);
    };
  }, []);

  const heading = title || text.successTitle;
  const message = lead || text.successLead;
  const extra = detail === undefined && !lead ? text.successDetail : detail;

  return createPortal(
    <div className="lead-modal" onClick={onClose}>
      <div
        className="lead-modal-dialog is-success"
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
        <div className="lead-modal-success">
          <h2 id={titleId}>
            {heading.split("\n").map((line) => (
              <span key={line}>
                {line}
                <br />
              </span>
            ))}
          </h2>
          <p>
            {message}
            {extra ? (
              <>
                <br />
                {extra}
              </>
            ) : null}
          </p>
        </div>
      </div>
    </div>,
    document.body,
  );
}
