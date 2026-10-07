"use client";

import Link from "next/link";
import {
  createContext,
  useContext,
  useEffect,
  useId,
  useRef,
  useState,
  type ButtonHTMLAttributes,
  type ReactNode,
} from "react";
import { createPortal } from "react-dom";
import type { Locale } from "@/i18n";
import { getTranslations } from "@/i18n/pages";

type LeadVariant = "lead" | "partner" | "question";

type LeadModalContextValue = {
  openLeadModal: (variant?: LeadVariant) => void;
};

const LeadModalContext = createContext<LeadModalContextValue | null>(null);

export function LeadModalProvider({ locale, children }: { locale: Locale; children: ReactNode }) {
  const [isOpen, setIsOpen] = useState(false);
  const [variant, setVariant] = useState<LeadVariant>("lead");
  const triggerRef = useRef<HTMLElement | null>(null);

  const openLeadModal = (next: LeadVariant = "lead") => {
    triggerRef.current =
      document.activeElement instanceof HTMLElement ? document.activeElement : null;
    setVariant(next);
    setIsOpen(true);
  };

  const closeLeadModal = () => {
    setIsOpen(false);
    triggerRef.current?.focus();
  };

  return (
    <LeadModalContext.Provider value={{ openLeadModal }}>
      {children}
      {isOpen ? <LeadModal locale={locale} variant={variant} onClose={closeLeadModal} /> : null}
    </LeadModalContext.Provider>
  );
}

export function LeadModalButton({
  className,
  children,
  onClick,
  variant = "lead",
  ...props
}: ButtonHTMLAttributes<HTMLButtonElement> & { variant?: LeadVariant }) {
  const context = useContext(LeadModalContext);

  return (
    <button
      {...props}
      type="button"
      className={className}
      onClick={(event) => {
        onClick?.(event);
        context?.openLeadModal(variant);
      }}
    >
      {children}
    </button>
  );
}

function LeadModal({
  locale,
  variant,
  onClose,
}: {
  locale: Locale;
  variant: LeadVariant;
  onClose: () => void;
}) {
  const text = getTranslations("global", locale).contact;
  const partner = variant === "partner" ? getTranslations("partners", locale).form : null;
  const question = variant === "question" ? getTranslations("blog", locale).questionForm : null;
  const titleId = useId();
  const closeRef = useRef<HTMLButtonElement>(null);
  const [interest, setInterest] = useState("development");
  const [fileName, setFileName] = useState("");
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
        className={`lead-modal-dialog${
          submitted ? " is-success" : partner ? " is-partner" : question ? " is-question" : ""
        }`}
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
          <img src="/images/lead-modal/close.svg" width="24.8091" height="24.8091" alt="" />
        </button>
        {submitted ? (
          <div className="lead-modal-success">
            <h2 id={titleId}>
              {text.successTitle.split("\n").map((line) => (
                <span key={line}>
                  {line}
                  <br />
                </span>
              ))}
            </h2>
            <p>
              {text.successLead}
              <br />
              {text.successDetail}
            </p>
          </div>
        ) : (
          <div className="lead-modal-scroll">
            <div className="lead-modal-copy">
              <h2 id={titleId}>
                {partner ? (
                  partner.title
                ) : question ? (
                  question.title
                ) : (
                  <>
                    {text.title}
                    <br />
                    {text.titleSecond}
                  </>
                )}
              </h2>
              {partner || question ? null : <p>{text.intro}</p>}
            </div>

            <form
              className="lead-modal-form"
              onSubmit={(event) => {
                event.preventDefault();
                setSubmitted(true);
                closeRef.current?.focus();
              }}
            >
              {question ? null : (
                <>
                  <div
                    className="lead-modal-interests"
                    role="group"
                    aria-label={partner ? partner.direction : text.interestLabel}
                  >
                    <p>{partner ? partner.direction : text.interestLabel}</p>
                    <div>
                      {text.interests.map((item) => (
                        <button
                          key={item.value}
                          type="button"
                          aria-pressed={interest === item.value}
                          className={interest === item.value ? "is-selected" : undefined}
                          onClick={() => setInterest(item.value)}
                        >
                          {item.label}
                        </button>
                      ))}
                    </div>
                  </div>
                  <input type="hidden" name="interest" value={interest} />
                </>
              )}

              {question ? (
                <>
                  <div className="lead-modal-row">
                    <input
                      name="name"
                      aria-label={question.name}
                      placeholder={question.name}
                      required
                    />
                    <input
                      name="company"
                      aria-label={question.company}
                      placeholder={question.company}
                    />
                  </div>
                  <div className="lead-modal-row">
                    <input
                      name="email"
                      type="email"
                      aria-label={question.email}
                      placeholder={question.email}
                      required
                    />
                    <label className="lead-modal-phone">
                      <input
                        name="phone"
                        type="tel"
                        inputMode="tel"
                        aria-label={text.phone}
                        placeholder={text.phoneShort}
                        required
                      />
                      <span aria-hidden="true">*</span>
                    </label>
                  </div>
                  <input
                    name="question"
                    aria-label={question.question}
                    placeholder={question.question}
                    required
                  />
                </>
              ) : partner ? (
                <>
                  <div className="lead-modal-row">
                    <input
                      name="name"
                      aria-label={partner.name}
                      placeholder={partner.name}
                      required
                    />
                    <label className="lead-modal-phone">
                      <input
                        name="phone"
                        type="tel"
                        inputMode="tel"
                        aria-label={text.phone}
                        placeholder={text.phoneShort}
                        required
                      />
                      <span aria-hidden="true">*</span>
                    </label>
                  </div>
                  <div className="lead-modal-row">
                    <input
                      name="email"
                      type="email"
                      aria-label={partner.email}
                      placeholder={partner.email}
                      required
                    />
                    <input
                      name="company"
                      aria-label={partner.company}
                      placeholder={partner.company}
                    />
                  </div>
                  <input
                    name="website"
                    aria-label={partner.website}
                    placeholder={partner.website}
                  />
                  <textarea name="message" aria-label={partner.about} placeholder={partner.about} />
                </>
              ) : (
                <>
                  <div className="lead-modal-row">
                    <input name="name" aria-label={text.name} placeholder={text.name} required />
                    <input
                      name="company"
                      aria-label={text.company}
                      placeholder={text.company}
                      required
                    />
                  </div>
                  <div className="lead-modal-row">
                    <input
                      name="email"
                      type="email"
                      aria-label={text.email}
                      placeholder={text.email}
                      required
                    />
                    <label className="lead-modal-phone">
                      <input
                        name="phone"
                        type="tel"
                        inputMode="tel"
                        aria-label={text.phone}
                        placeholder={text.phoneShort}
                        required
                      />
                      <span aria-hidden="true">*</span>
                    </label>
                  </div>
                  <input name="source" aria-label={text.source} placeholder={text.source} />
                  <textarea
                    name="message"
                    aria-label={text.description}
                    placeholder={text.description}
                  />
                </>
              )}

              <label className="lead-modal-file">
                <input
                  type="file"
                  name="file"
                  accept={partner ? ".pdf,application/pdf" : undefined}
                  onChange={(event) => setFileName(event.target.files?.[0]?.name ?? "")}
                />
                <img src="/images/lead-modal/file-add.svg" width="24" height="24" alt="" />
                <span>
                  {question ? text.attachment : partner ? partner.attachment : text.attachment}
                  {fileName ? ` — ${fileName}` : ""}
                </span>
              </label>

              <div className="lead-modal-submit">
                <button type="submit">
                  {question ? question.submit : partner ? partner.submit : text.submit}
                </button>
                <p className="lead-modal-consent">
                  {question ? question.consent : text.consent}{" "}
                  <Link href={`/${locale}/privacy-policy`}>
                    {question ? question.consentMore : text.consentMore}
                  </Link>
                </p>
              </div>
            </form>
          </div>
        )}
      </div>
    </div>,
    document.body,
  );
}
