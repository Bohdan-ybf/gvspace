"use client";

import { useId, useState } from "react";
import type { ChangeEvent } from "react";
import type { Locale } from "@/i18n";

const MAX_FILE_SIZE = 25 * 1024 * 1024;

type FileUploadFieldProps = {
  className: string;
  label: string;
  locale: Locale;
  accept?: string;
  name?: string;
};

export function FileUploadField({
  className,
  label,
  locale,
  accept,
  name = "file",
}: FileUploadFieldProps) {
  const inputId = useId();
  const [fileName, setFileName] = useState("");
  const [error, setError] = useState("");

  const handleChange = (event: ChangeEvent<HTMLInputElement>) => {
    const input = event.currentTarget;
    const file = input.files?.[0];

    if (file && file.size > MAX_FILE_SIZE) {
      const message =
        locale === "uk" ? "Максимальний розмір файлу — 25 МБ" : "Maximum file size is 25 MB";
      input.value = "";
      input.setCustomValidity(message);
      setFileName("");
      setError(message);
      return;
    }

    input.setCustomValidity("");
    setError("");
    setFileName(file?.name ?? "");
  };

  return (
    <label
      className={className}
      htmlFor={inputId}
      style={{ display: "flex", alignItems: "center", cursor: "pointer" }}
    >
      <input
        id={inputId}
        type="file"
        name={name}
        accept={accept}
        onChange={handleChange}
        style={{ pointerEvents: "none" }}
      />
      <FileAddIcon />
      <span aria-live="polite">
        {error || label}
        {!error && fileName ? ` — ${fileName}` : ""}
      </span>
    </label>
  );
}

function FileAddIcon() {
  return (
    <svg
      width="24"
      height="24"
      viewBox="0 0 24 24"
      fill="none"
      aria-hidden="true"
      style={{ cursor: "pointer" }}
    >
      <path
        d="M4 12V14.545C4 17.79 4 19.412 4.886 20.511C5.06494 20.7329 5.26709 20.9351 5.489 21.114C6.59 22 8.211 22 11.456 22C12.161 22 12.514 22 12.837 21.887C12.9037 21.8623 12.9693 21.835 13.034 21.805C13.344 21.657 13.593 21.407 14.092 20.909L18.828 16.172C19.407 15.594 19.695 15.305 19.848 14.937C20 14.57 20 14.161 20 13.344V10C20 6.228 20 4.343 18.828 3.171C17.769 2.111 16.127 2.011 13.035 2.001M13 21.5V21C13 18.171 13 16.757 13.879 15.878C14.757 15 16.172 15 19 15H19.5M12 6H4M8 2V10"
        stroke="currentColor"
        strokeWidth="1.5"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
}
