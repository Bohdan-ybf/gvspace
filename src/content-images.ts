import { isValidElement, type ReactNode } from "react";

function nodeToText(node: ReactNode): string {
  if (node == null || typeof node === "boolean") return "";
  if (typeof node === "string" || typeof node === "number") return String(node);
  if (Array.isArray(node)) return node.map(nodeToText).join(" ");
  if (isValidElement(node)) {
    if (node.type === "br") return " ";
    const children = (node.props as { children?: ReactNode }).children;
    return nodeToText(children);
  }
  return "";
}

export function contentImageLabel(...parts: Array<ReactNode>) {
  return parts
    .map(nodeToText)
    .join(" ")
    .replace(/<[^>]*>/g, " ")
    .replace(/&nbsp;/gi, " ")
    .replace(/&amp;/gi, "&")
    .replace(/&quot;/gi, '"')
    .replace(/&#0?39;|&apos;/gi, "'")
    .replace(/&lt;/gi, "<")
    .replace(/&gt;/gi, ">")
    .replace(/\s+/g, " ")
    .trim();
}

function escapeAttribute(value: string) {
  return value
    .replace(/&/g, "&amp;")
    .replace(/"/g, "&quot;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");
}

export function stampContentImages(html: string, heading: string) {
  const label = contentImageLabel(heading);
  if (!label || !html) return html;
  const escaped = escapeAttribute(label);
  return html.replace(/<img\b([^>]*?)(\s*\/?)>/gi, (_match, attrs: string, slash: string) => {
    const withoutLabel = attrs.replace(/\s(?:alt|title)\s*=\s*(?:"[^"]*"|'[^']*'|[^\s>]+)/gi, "");
    return `<img${withoutLabel} alt="${escaped}" title="${escaped}"${slash}>`;
  });
}
