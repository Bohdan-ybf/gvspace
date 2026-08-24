import Image from "next/image";
import Link from "next/link";
import type { Locale } from "@/i18n";
import { ArrowRight } from "./icons/arrow-right";

export function CareersBanner({ locale }: { locale: Locale }) {
  const uk = locale === "uk";
  return (
    <section className="careers-banner container">
      <Image
        src="/images/team/careers.webp"
        alt=""
        fill
        sizes="(max-width: 1320px) 100vw, 1280px"
      />
      <div>
        <h2>
          {uk ? "Шукаємо людей, які мислять системно" : "We are looking for systematic thinkers"}
        </h2>
        <p>
          {uk
            ? "Якщо ви фахівець у своїй зоні і хочете працювати в середовищі, де результат важливіший за процес — напишіть нам."
            : "If you are an expert who wants to work where results matter more than process, get in touch."}
        </p>
      </div>
      <Link className="btn btn-primary" href={`/${locale}/careers`}>
        {uk ? "Переглянути вакансії" : "View vacancies"}
        <ArrowRight />
      </Link>
    </section>
  );
}
