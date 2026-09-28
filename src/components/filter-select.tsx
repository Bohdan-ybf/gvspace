"use client";

import { useEffect, useId, useRef, useState } from "react";
import { ChevronDown } from "./icons/chevron-down";

function displayLabel(value: string) {
  if (!/[А-ЯІЇЄҐ]/.test(value) || value !== value.toUpperCase()) return value;
  const lower = value.toLocaleLowerCase("uk-UA");
  return lower.charAt(0).toLocaleUpperCase("uk-UA") + lower.slice(1);
}

export function FilterSelect({
  label,
  value,
  options,
  onChange,
}: {
  label: string;
  value: string;
  options: Array<{ value: string; label: string }>;
  onChange: (value: string) => void;
}) {
  const [open, setOpen] = useState(false);
  const rootRef = useRef<HTMLDivElement>(null);
  const listId = useId();
  const selected = options.find((option) => option.value === value);

  useEffect(() => {
    if (!open) return;

    const onPointerDown = (event: MouseEvent) => {
      if (!rootRef.current?.contains(event.target as Node)) setOpen(false);
    };
    const onKeyDown = (event: KeyboardEvent) => {
      if (event.key === "Escape") setOpen(false);
    };

    document.addEventListener("mousedown", onPointerDown);
    document.addEventListener("keydown", onKeyDown);
    return () => {
      document.removeEventListener("mousedown", onPointerDown);
      document.removeEventListener("keydown", onKeyDown);
    };
  }, [open]);

  return (
    <div className={`filter-select${open ? " is-open" : ""}`} ref={rootRef}>
      <button
        type="button"
        className="filter-select-trigger"
        aria-haspopup="listbox"
        aria-expanded={open}
        aria-controls={listId}
        onClick={() => setOpen((current) => !current)}
      >
        <span>{selected ? displayLabel(selected.label) : label}</span>
        <ChevronDown />
      </button>
      {open ? (
        <ul className="filter-select-menu" id={listId} role="listbox" aria-label={label}>
          {options.map((option) => (
            <li key={option.value} role="presentation">
              <button
                type="button"
                role="option"
                aria-selected={option.value === value}
                onClick={() => {
                  onChange(option.value);
                  setOpen(false);
                }}
              >
                {displayLabel(option.label)}
              </button>
            </li>
          ))}
        </ul>
      ) : null}
    </div>
  );
}
