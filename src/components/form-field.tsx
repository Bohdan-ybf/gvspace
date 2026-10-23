"use client";

import { useState, type InputHTMLAttributes, type ReactNode } from "react";
import type { Locale } from "@/i18n";

export const PHONE_PLACEHOLDER = "+___ ___ ___ ___";
const MIN_PHONE_DIGITS = 8;
const MAX_PHONE_DIGITS = 15;

export function formatInternationalPhone(raw: string) {
  const digits = raw.replace(/\D/g, "").slice(0, MAX_PHONE_DIGITS);
  if (!digits) return "";
  const parts: string[] = [];
  for (let index = 0; index < digits.length; index += 3) {
    parts.push(digits.slice(index, index + 3));
  }
  return `+${parts.join(" ")}`;
}

export function Field({ children }: { locale: Locale; children: ReactNode }) {
  return <span className="form-field">{children}</span>;
}

type PhoneFieldProps = Omit<
  InputHTMLAttributes<HTMLInputElement>,
  "value" | "onChange" | "type"
> & {
  locale: Locale;
};

export function PhoneField({
  locale: _locale,
  required = true,
  placeholder: _placeholder,
  ...props
}: PhoneFieldProps) {
  const [value, setValue] = useState("");

  return (
    <span className="form-field phone-field">
      <input
        {...props}
        type="tel"
        inputMode="tel"
        autoComplete="tel"
        required={required}
        placeholder={PHONE_PLACEHOLDER}
        value={value}
        onChange={(event) => {
          const next = formatInternationalPhone(event.target.value);
          const digits = next.replace(/\D/g, "").length;
          event.target.setCustomValidity(digits > 0 && digits < MIN_PHONE_DIGITS ? " " : "");
          setValue(next);
        }}
      />
      {required ? <span aria-hidden="true">*</span> : null}
    </span>
  );
}
