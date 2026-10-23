export const formIds = [
  "contact",
  "contacts",
  "lead",
  "partner",
  "question",
  "review",
  "vacancy",
  "newsletter",
  "comment",
] as const;

export type FormId = (typeof formIds)[number];

export type FormErrorCode = "invalid" | "file_too_large" | "unavailable" | "send_failed";

export function isFormId(value: string): value is FormId {
  return (formIds as readonly string[]).includes(value);
}
