import { NextResponse } from "next/server";
import { clientAddress, deliverForm, isRateLimited, prepareForm } from "@/form-delivery";
import { isFormId, type FormErrorCode } from "@/form-types";

export const runtime = "nodejs";
export const dynamic = "force-dynamic";

function failure(error: FormErrorCode, status: number) {
  return NextResponse.json({ error }, { status });
}

export async function POST(request: Request) {
  if (isRateLimited(clientAddress(request.headers))) {
    return failure("send_failed", 429);
  }

  let formData: FormData;
  try {
    formData = await request.formData();
  } catch {
    return failure("invalid", 400);
  }

  const honeypot = formData.get("company_url_hp");
  if (typeof honeypot === "string" && honeypot.trim()) {
    return NextResponse.json({ ok: true });
  }

  const formId = formData.get("form");
  if (typeof formId !== "string" || !isFormId(formId)) return failure("invalid", 400);

  const prepared = await prepareForm(formData, formId);
  if (!prepared.ok) {
    return failure(prepared.error, prepared.error === "file_too_large" ? 413 : 400);
  }

  const delivered = await deliverForm(formId, prepared.fields, prepared.attachment);
  if (!delivered.ok) {
    const status = delivered.error === "unavailable" ? 503 : 502;
    return failure(delivered.error, status);
  }

  return NextResponse.json({ ok: true });
}
