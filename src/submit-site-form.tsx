"use client";

import { useRef, useState, type FormEvent } from "react";
import type { Locale } from "@/i18n";
import type { FormErrorCode } from "@/form-types";

export class SiteFormError extends Error {
  code: FormErrorCode;

  constructor(code: FormErrorCode) {
    super(code);
    this.code = code;
  }
}

export function formErrorMessage(locale: Locale, code: FormErrorCode) {
  if (locale === "uk") {
    if (code === "file_too_large") return "Файл завеликий. Максимальний розмір — 20 МБ.";
    if (code === "invalid") return "Перевірте заповнені поля і спробуйте ще раз.";
    if (code === "unavailable") return "Надсилання тимчасово недоступне. Спробуйте пізніше.";
    return "Не вдалося надіслати. Спробуйте ще раз.";
  }
  if (code === "file_too_large") return "The file is too large. Maximum size is 20 MB.";
  if (code === "invalid") return "Check the fields and try again.";
  if (code === "unavailable") return "Sending is temporarily unavailable. Please try later.";
  return "Could not send. Please try again.";
}

export function FormHoneypot() {
  return (
    <input
      hidden
      type="text"
      name="company_url_hp"
      tabIndex={-1}
      autoComplete="off"
      aria-hidden="true"
      defaultValue=""
    />
  );
}

export function sendingLabel(locale: Locale) {
  return locale === "uk" ? "Надсилаємо..." : "Sending...";
}

export function FormSubmitError({ message }: { message: string }) {
  if (!message) return null;
  return (
    <p className="form-submit-error" role="alert">
      {message}
    </p>
  );
}

export async function submitSiteForm(
  form: HTMLFormElement,
  formId: string,
  locale: Locale,
  extra?: Record<string, string>,
) {
  const data = new FormData(form);
  data.set("form", formId);
  data.set("locale", locale);
  data.set("page", window.location.href);
  if (extra) {
    for (const [key, value] of Object.entries(extra)) {
      if (value.trim()) data.set(key, value);
    }
  }

  let response: Response;
  try {
    response = await fetch("/api/forms", { method: "POST", body: data });
  } catch {
    throw new SiteFormError("send_failed");
  }

  if (!response.ok) {
    const body = (await response.json().catch(() => null)) as { error?: FormErrorCode } | null;
    throw new SiteFormError(body?.error ?? "send_failed");
  }
}

export function useSiteForm(locale: Locale) {
  const sendingRef = useRef(false);
  const [sending, setSending] = useState(false);
  const [success, setSuccess] = useState(false);
  const [error, setError] = useState("");
  const [formKey, setFormKey] = useState(0);

  async function submit(
    event: FormEvent<HTMLFormElement>,
    formId: string,
    extra?: Record<string, string>,
  ) {
    event.preventDefault();
    const form = event.currentTarget;
    if (sendingRef.current) return false;
    sendingRef.current = true;
    setSending(true);
    setError("");
    try {
      await submitSiteForm(form, formId, locale, extra);
      form.reset();
      setFormKey((key) => key + 1);
      setSuccess(true);
      return true;
    } catch (caught) {
      const code = caught instanceof SiteFormError ? caught.code : "send_failed";
      setError(formErrorMessage(locale, code));
      return false;
    } finally {
      sendingRef.current = false;
      setSending(false);
    }
  }

  function closeSuccess() {
    setSuccess(false);
  }

  return { sending, success, error, formKey, submit, closeSuccess };
}
