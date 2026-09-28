"use client";

import { useLayoutEffect, useRef } from "react";

const fieldHeight = 60;

export function ContactMessageField({
  label,
  placeholder,
}: {
  label: string;
  placeholder: string;
}) {
  const fieldRef = useRef<HTMLTextAreaElement>(null);

  const resize = () => {
    const field = fieldRef.current;
    if (!field) return;
    field.style.height = `${fieldHeight}px`;
    field.style.height = `${Math.max(fieldHeight, field.scrollHeight)}px`;
  };

  useLayoutEffect(() => {
    resize();
  }, []);

  return (
    <textarea
      ref={fieldRef}
      aria-label={label}
      placeholder={placeholder}
      required
      rows={1}
      onInput={resize}
    />
  );
}
