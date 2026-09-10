import { NextRequest, NextResponse } from "next/server";
import { getLocaleOrigin, getMarket } from "@/markets";
import { isLocale } from "@/i18n";

export function proxy(request: NextRequest) {
  const market = getMarket(request.headers.get("host") ?? "");

  // Local development and preview hosts keep the existing locale routing.
  if (!market) return NextResponse.next();

  const requestedLocale = request.nextUrl.pathname.split("/")[1];

  if (isLocale(requestedLocale) && requestedLocale !== market.locale) {
    const target = new URL(
      request.nextUrl.pathname + request.nextUrl.search,
      getLocaleOrigin(requestedLocale),
    );
    return NextResponse.redirect(target, 307);
  }

  if (request.nextUrl.pathname === "/") {
    return NextResponse.redirect(new URL(`/${market.locale}`, market.origin), 307);
  }

  return NextResponse.next();
}

export const config = {
  matcher: ["/((?!api|_next/static|_next/image|favicon.ico|icon.svg|robots.txt|sitemap.xml).*)"],
};
