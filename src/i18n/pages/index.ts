import careersUK from "./careers/uk";
import careersEN from "./careers/en";
import blogUK from "./blog/uk";
import blogEN from "./blog/en";
import aboutUK from "./about/uk";
import aboutEN from "./about/en";
import casesUK from "./cases/uk";
import casesEN from "./cases/en";
import reviewsUK from "./reviews/uk";
import reviewsEN from "./reviews/en";
import servicesUK from "./services/uk";
import servicesEN from "./services/en";
import commonUK from "./common/uk";
import commonEN from "./common/en";
import teamUK from "./team/uk";
import teamEN from "./team/en";
import technologiesUK from "./technologies/uk";
import technologiesEN from "./technologies/en";
import homeUK from "./home/uk";
import homeEN from "./home/en";
import vacanciesUK from "./vacancies/uk";
import vacanciesEN from "./vacancies/en";
import contactsUK from "./contacts/uk";
import contactsEN from "./contacts/en";
import legalUK from "./legal/uk";
import legalEN from "./legal/en";
import globalUK from "./global/uk";
import globalEN from "./global/en";
import type { Locale } from "../index";

const translations = {
  careers: { uk: careersUK, en: careersEN },
  blog: { uk: blogUK, en: blogEN },
  about: { uk: aboutUK, en: aboutEN },
  cases: { uk: casesUK, en: casesEN },
  reviews: { uk: reviewsUK, en: reviewsEN },
  services: { uk: servicesUK, en: servicesEN },
  common: { uk: commonUK, en: commonEN },
  team: { uk: teamUK, en: teamEN },
  technologies: { uk: technologiesUK, en: technologiesEN },
  home: { uk: homeUK, en: homeEN },
  vacancies: { uk: vacanciesUK, en: vacanciesEN },
  contacts: { uk: contactsUK, en: contactsEN },
  legal: { uk: legalUK, en: legalEN },
  global: { uk: globalUK, en: globalEN },
} as const;

export type TranslationPage = keyof typeof translations;

export function getTranslations<P extends TranslationPage>(
  page: P,
  locale: Locale,
): (typeof translations)[P][Locale] {
  return translations[page][locale] as (typeof translations)[P][Locale];
}
