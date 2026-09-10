import type { Locale } from "@/i18n";

import { componentCopy } from "@/i18n/component-copy";
export function VacancyApplicationForm({ locale }: { locale: Locale }) {
  const copy = componentCopy[locale]["vacancy-application-form"];

  return (
    <form className="vacancy-application-form">
      <span className="mono">{copy.copy1}</span>
      <h2>{copy.copy2}</h2>
      <div>
        <input aria-label={copy.copy3} placeholder={copy.copy4} required />
        <input aria-label={copy.copy5} placeholder="+38 0__" inputMode="tel" />
      </div>
      <input aria-label="Email" placeholder="Email" type="email" required />
      <input aria-label="Telegram" placeholder="Telegram" />
      <input aria-label="LinkedIn or portfolio" placeholder="LinkedIn або портфоліо" />
      <textarea aria-label={copy.copy6} placeholder={copy.copy7} required />
      <label className="resume-upload mono">
        <input type="file" accept=".pdf" />
        {copy.copy8}
      </label>
      <button className="btn btn-primary" type="submit">
        {copy.copy9}
      </button>
      <p className="mono">{copy.copy10}</p>
    </form>
  );
}
