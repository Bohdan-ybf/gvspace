import nodemailer from "nodemailer";
import type { FormErrorCode, FormId } from "@/form-types";

const MAX_FILE_BYTES = 20 * 1024 * 1024;
const MAX_FIELD_LENGTH = 8000;
const RATE_LIMIT = 8;
const RATE_WINDOW_MS = 10 * 60 * 1000;

const requiredFields: Record<FormId, string[]> = {
  contact: ["name", "company", "email", "message"],
  contacts: ["name", "email", "topic", "message"],
  lead: ["name", "company", "email", "phone"],
  partner: ["name", "email", "phone"],
  question: ["name", "email", "phone", "question"],
  review: ["name", "email", "phone", "text"],
  vacancy: ["name", "email", "phone", "about"],
  newsletter: ["email"],
  comment: ["name", "email", "text"],
};

const subjects: Record<FormId, string> = {
  contact: "Заявка з сайту",
  contacts: "Заявка зі сторінки контактів",
  lead: "Заявка з форми",
  partner: "Партнерська заявка",
  question: "Питання автору",
  review: "Новий відгук",
  vacancy: "Відгук на вакансію",
  newsletter: "Підписка на розсилку",
  comment: "Коментар до статті",
};

const fieldLabels: Record<string, string> = {
  name: "Ім'я",
  company: "Компанія",
  email: "Email",
  phone: "Телефон",
  source: "Як дізналися",
  message: "Повідомлення",
  interest: "Інтерес",
  website: "Сайт",
  question: "Питання",
  text: "Текст",
  telegram: "Telegram",
  portfolio: "Портфоліо",
  about: "Про себе",
  topic: "Тема",
  vacancy: "Вакансія",
  article: "Стаття",
  page: "Сторінка",
  locale: "Мова",
};

const interestLabels: Record<string, string> = {
  strategy: "Стратегія",
  marketing: "Маркетинг",
  development: "IT-розробка",
  content: "Контент",
  other: "Інше",
};

const fieldOrder = [
  "vacancy",
  "article",
  "interest",
  "name",
  "company",
  "email",
  "phone",
  "telegram",
  "website",
  "portfolio",
  "source",
  "topic",
  "question",
  "message",
  "about",
  "text",
  "locale",
  "page",
];

const hits = new Map<string, number[]>();

export function clientAddress(headers: Headers): string {
  const forwarded = headers.get("x-forwarded-for")?.split(",")[0]?.trim();
  return forwarded || headers.get("x-real-ip") || "local";
}

export function isRateLimited(ip: string): boolean {
  const now = Date.now();
  const recent = (hits.get(ip) ?? []).filter((time) => now - time < RATE_WINDOW_MS);
  if (recent.length >= RATE_LIMIT) {
    hits.set(ip, recent);
    return true;
  }
  recent.push(now);
  hits.set(ip, recent);
  return false;
}

type Attachment = {
  filename: string;
  content: Buffer;
  contentType: string;
};

type PreparedForm =
  | { ok: true; fields: Record<string, string>; attachment: Attachment | null }
  | { ok: false; error: FormErrorCode };

export async function prepareForm(formData: FormData, formId: FormId): Promise<PreparedForm> {
  const fields: Record<string, string> = {};
  let attachment: Attachment | null = null;

  for (const [key, value] of formData.entries()) {
    if (key === "form" || key === "company_url_hp") continue;
    if (value instanceof File) {
      if (value.size === 0) continue;
      if (value.size > MAX_FILE_BYTES) return { ok: false, error: "file_too_large" };
      const filename = value.name.replace(/[^\w.\-() ]+/g, "_").slice(0, 120) || "attachment";
      if (/\.(exe|bat|cmd|com|js|mjs|sh|php|html?|svg|scr|msi)$/i.test(filename)) {
        return { ok: false, error: "invalid" };
      }
      attachment = {
        filename,
        content: Buffer.from(await value.arrayBuffer()),
        contentType: value.type || "application/octet-stream",
      };
      continue;
    }
    if (Object.keys(fields).length > 24) return { ok: false, error: "invalid" };
    const text = value
      .replace(/\u0000/g, "")
      .trim()
      .slice(0, MAX_FIELD_LENGTH);
    if (text) fields[key] = text;
  }

  for (const key of requiredFields[formId]) {
    if (!fields[key]) return { ok: false, error: "invalid" };
  }
  if (fields.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(fields.email)) {
    return { ok: false, error: "invalid" };
  }

  return { ok: true, fields, attachment };
}

function mailConfig() {
  const host = process.env.SMTP_HOST?.trim();
  const user = process.env.SMTP_USER?.trim();
  const pass = process.env.SMTP_PASS?.trim();
  const port = Number(process.env.SMTP_PORT || 465);
  const from = process.env.SMTP_FROM?.trim() || user;
  const to = process.env.FORM_TO_EMAIL?.trim() || "bohdan.info.work@gmail.com";
  if (!host || !user || !pass || !from || !to || !Number.isFinite(port)) return null;
  return { host, user, pass, port, from, to };
}

function oneLine(value: string) {
  return value.replace(/[\r\n]+/g, " ").trim();
}

export async function deliverForm(
  formId: FormId,
  fields: Record<string, string>,
  attachment: Attachment | null,
) {
  const config = mailConfig();
  if (!config) return { ok: false as const, error: "unavailable" as const };

  const lines = [`Форма: ${subjects[formId]}`];
  const used = new Set<string>();
  for (const key of fieldOrder) {
    const value = fields[key];
    if (!value) continue;
    used.add(key);
    const printed = key === "interest" ? interestLabels[value] || value : value;
    lines.push(`${fieldLabels[key] || key}: ${printed}`);
  }
  for (const [key, value] of Object.entries(fields)) {
    if (used.has(key)) continue;
    lines.push(`${fieldLabels[key] || key}: ${value}`);
  }
  if (attachment) lines.push(`Файл: ${attachment.filename}`);

  const who = fields.name || fields.email || "";
  const subject = oneLine(
    who ? `GVSPACE: ${subjects[formId]} — ${who}` : `GVSPACE: ${subjects[formId]}`,
  );

  try {
    const transporter = nodemailer.createTransport({
      host: config.host,
      port: config.port,
      secure: config.port === 465,
      connectionTimeout: 10_000,
      greetingTimeout: 10_000,
      socketTimeout: 20_000,
      auth: { user: config.user, pass: config.pass },
    });
    await transporter.sendMail({
      from: { name: "GVSPACE", address: config.from },
      to: config.to,
      replyTo: fields.email ? oneLine(fields.email) : undefined,
      subject,
      text: lines.join("\n"),
      attachments: attachment
        ? [
            {
              filename: attachment.filename,
              content: attachment.content,
              contentType: attachment.contentType,
            },
          ]
        : undefined,
    });
  } catch (error) {
    console.error("Form mail delivery failed", error);
    return { ok: false as const, error: "send_failed" as const };
  }

  return { ok: true as const };
}
