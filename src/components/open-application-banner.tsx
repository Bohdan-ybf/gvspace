import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";

export function OpenApplicationBanner({ locale }: { locale: Locale }) {
  const uk = locale === "uk";

  return (
    <section className="open-application container">
      <Image
        src="/images/careers/open-application.webp"
        alt=""
        fill
        sizes="(max-width: 1320px) 100vw, 1280px"
      />
      <div>
        <h2>
          {uk ? (
            <>
              Не знайшли свою позицію?
              <br />
              Надішліть відкриту заявку
            </>
          ) : (
            <>
              Did not find your position?
              <br />
              Send an open application
            </>
          )}
        </h2>
        <p>
          {uk
            ? "Якщо ви фахівець і хочете бути частиною GVSPACE — напишіть нам. Ми тримаємо список людей, з якими хочемо працювати."
            : "If you are an expert who wants to become part of GVSPACE, write to us. We keep a list of people we want to work with."}
        </p>
      </div>
      <Link className="btn btn-primary" href={`/${locale}/contacts`}>
        {uk ? "Надіслати заявку" : "Send application"}
      </Link>
    </section>
  );
}
