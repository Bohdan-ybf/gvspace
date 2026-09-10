import { NextRequest, NextResponse } from "next/server";
import { getLocaleOrigin, getMarket } from "@/markets";
import { isLocale } from "@/i18n";

export function proxy(request: NextRequest) {
  const market = getMarket(request.headers.get("host") ?? "");

  // Local development and preview hosts keep the existing locale routing.
  if (!market) return NextResponse.next();

  const requestedLocale = request.nextUrl.pathname.split("/")[1];

  if (isLocale(requestedLocale)) {
    const cleanPathname = request.nextUrl.pathname.replace(/^\/(uk|en)(?=\/|$)/, "") || "/";
    const target = new URL(
      cleanPathname + request.nextUrl.search,
      getLocaleOrigin(requestedLocale),
    );
    return NextResponse.redirect(target, 307);
  }

  const internalUrl = request.nextUrl.clone();
  internalUrl.pathname = `/${market.locale}${request.nextUrl.pathname === "/" ? "" : request.nextUrl.pathname}`;
  return NextResponse.rewrite(internalUrl);
}

export const config = {
  matcher: ["/((?!api|_next/static|_next/image|favicon.ico|icon.svg|robots.txt|sitemap.xml).*)"],
};
