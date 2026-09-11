import { NextRequest, NextResponse } from "next/server";
import { getFallbackMarket, getLocaleOrigin, getMarket } from "@/markets";
import { isLocale } from "@/i18n";

export function proxy(request: NextRequest) {
  const pathname = request.nextUrl.pathname;

  // Public assets must keep their original paths. Rewriting an image such as
  // /images/logo.svg to /uk/images/logo.svg makes Next.js return HTML instead.
  if (
    pathname.startsWith("/_next/") ||
    pathname.startsWith("/api/") ||
    pathname.startsWith("/images/") ||
    /\.[^/]+$/.test(pathname)
  ) {
    return NextResponse.next();
  }

  const market = getMarket(request.headers.get("host") ?? "");

  // Local development and preview hosts keep the existing locale routing.
  if (!market) return NextResponse.next();

  // Domains can be connected before their dictionaries and content are ready.
  // Disabled markets always redirect to their explicitly configured fallback.
  if (!market.enabled || !market.routeLocale) {
    const fallback = getFallbackMarket(market);
    return NextResponse.redirect(
      new URL(request.nextUrl.pathname + request.nextUrl.search, fallback.origin),
      307,
    );
  }

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
  internalUrl.pathname = `/${market.routeLocale}${request.nextUrl.pathname === "/" ? "" : request.nextUrl.pathname}`;
  return NextResponse.rewrite(internalUrl);
}

export const config = {
  matcher: ["/:path*"],
};
