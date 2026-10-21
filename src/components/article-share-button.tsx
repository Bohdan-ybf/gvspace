"use client";

import { useEffect, useId, useRef, useState } from "react";
import { FacebookIcon, TelegramIcon } from "./icons/social-icons";

function pageUrl() {
  return window.location.href;
}

export function ArticleShareButton({
  label,
  copyLabel,
  copiedLabel,
  title,
}: {
  label: string;
  copyLabel: string;
  copiedLabel: string;
  title: string;
}) {
  const [open, setOpen] = useState(false);
  const [copied, setCopied] = useState(false);
  const rootRef = useRef<HTMLDivElement>(null);
  const menuId = useId();

  useEffect(() => {
    if (!open) return;
    const onPointerDown = (event: PointerEvent) => {
      if (!rootRef.current?.contains(event.target as Node)) setOpen(false);
    };
    const onKeyDown = (event: KeyboardEvent) => {
      if (event.key === "Escape") setOpen(false);
    };
    document.addEventListener("pointerdown", onPointerDown);
    document.addEventListener("keydown", onKeyDown);
    return () => {
      document.removeEventListener("pointerdown", onPointerDown);
      document.removeEventListener("keydown", onKeyDown);
    };
  }, [open]);

  useEffect(() => {
    if (!copied) return;
    const timer = window.setTimeout(() => setCopied(false), 1600);
    return () => window.clearTimeout(timer);
  }, [copied]);

  const copyLink = () => {
    const url = pageUrl();
    setCopied(true);
    const fallback = () => {
      const input = document.createElement("textarea");
      input.value = url;
      input.setAttribute("readonly", "");
      input.style.position = "fixed";
      input.style.left = "-9999px";
      document.body.appendChild(input);
      input.select();
      document.execCommand("copy");
      input.remove();
    };
    if (navigator.clipboard?.writeText) {
      navigator.clipboard.writeText(url).catch(fallback);
      return;
    }
    fallback();
  };

  const openShare = (network: "facebook" | "telegram") => {
    const url = encodeURIComponent(pageUrl());
    const text = encodeURIComponent(title);
    const href =
      network === "facebook"
        ? `https://www.facebook.com/sharer/sharer.php?u=${url}`
        : `https://t.me/share/url?url=${url}&text=${text}`;
    window.open(href, "_blank", "noopener,noreferrer");
    setOpen(false);
  };

  return (
    <div className={`article-share${open ? " is-open" : ""}`} ref={rootRef}>
      <button
        type="button"
        aria-expanded={open}
        aria-controls={menuId}
        onClick={() => setOpen((value) => !value)}
      >
        <svg width="18" height="20" viewBox="0 0 18 20" fill="none" aria-hidden="true">
          <path
            d="M15 20C14.1667 20 13.4583 19.7083 12.875 19.125C12.2917 18.5417 12 17.8333 12 17C12 16.9 12.025 16.6667 12.075 16.3L5.05 12.2C4.78333 12.45 4.475 12.646 4.125 12.788C3.775 12.93 3.4 13.0007 3 13C2.16667 13 1.45833 12.7083 0.875 12.125C0.291667 11.5417 0 10.8333 0 10C0 9.16667 0.291667 8.45833 0.875 7.875C1.45833 7.29167 2.16667 7 3 7C3.4 7 3.775 7.071 4.125 7.213C4.475 7.355 4.78333 7.55067 5.05 7.8L12.075 3.7C12.0417 3.58333 12.021 3.471 12.013 3.363C12.005 3.255 12.0007 3.134 12 3C12 2.16667 12.2917 1.45833 12.875 0.875C13.4583 0.291667 14.1667 0 15 0C15.8333 0 16.5417 0.291667 17.125 0.875C17.7083 1.45833 18 2.16667 18 3C18 3.83333 17.7083 4.54167 17.125 5.125C16.5417 5.70833 15.8333 6 15 6C14.6 6 14.225 5.929 13.875 5.787C13.525 5.645 13.2167 5.44933 12.95 5.2L5.925 9.3C5.95833 9.41667 5.97933 9.52933 5.988 9.638C5.99667 9.74667 6.00067 9.86733 6 10C5.99933 10.1327 5.99533 10.2537 5.988 10.363C5.98067 10.4723 5.95967 10.5847 5.925 10.7L12.95 14.8C13.2167 14.55 13.525 14.3543 13.875 14.213C14.225 14.0717 14.6 14.0007 15 14C15.8333 14 16.5417 14.2917 17.125 14.875C17.7083 15.4583 18 16.1667 18 17C18 17.8333 17.7083 18.5417 17.125 19.125C16.5417 19.7083 15.8333 20 15 20Z"
            fill="currentColor"
          />
        </svg>
        {label}
      </button>
      <div id={menuId} className="article-share-popover" role="group" aria-hidden={!open}>
        <button type="button" aria-label={copied ? copiedLabel : copyLabel} onClick={copyLink}>
          {copied ? (
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path
                d="M5 12.5 9.2 17 19 7"
                fill="none"
                stroke="currentColor"
                strokeWidth="1.8"
                strokeLinecap="round"
                strokeLinejoin="round"
              />
            </svg>
          ) : (
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <rect
                x="8.5"
                y="8.5"
                width="11"
                height="11"
                rx="2"
                fill="none"
                stroke="currentColor"
                strokeWidth="1.8"
              />
              <path
                d="M6.5 15.5h-1a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v1"
                fill="none"
                stroke="currentColor"
                strokeWidth="1.8"
                strokeLinecap="round"
              />
            </svg>
          )}
        </button>
        <button type="button" aria-label="Facebook" onClick={() => openShare("facebook")}>
          <FacebookIcon />
        </button>
        <button type="button" aria-label="Telegram" onClick={() => openShare("telegram")}>
          <TelegramIcon />
        </button>
      </div>
    </div>
  );
}
