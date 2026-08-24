import type { Locale } from "@/i18n";

export function VacancyApplicationForm({ locale }: { locale: Locale }) {
  const uk = locale === "uk";

  return (
    <form className="vacancy-application-form">
      <span className="mono">{uk ? "ВІДГУКНУТИСЬ" : "APPLY"}</span>
      <h2>{uk ? "Залишити заявку" : "Submit your application"}</h2>
      <div>
        <input aria-label={uk ? "Ім’я" : "Name"} placeholder={uk ? "Ім’я" : "Name"} required />
        <input aria-label={uk ? "Телефон" : "Phone"} placeholder="+38 0__" inputMode="tel" />
      </div>
      <input aria-label="Email" placeholder="Email" type="email" required />
      <input aria-label="Telegram" placeholder="Telegram" />
      <input aria-label="LinkedIn or portfolio" placeholder="LinkedIn або портфоліо" />
      <textarea
        aria-label={uk ? "Про себе" : "About you"}
        placeholder={
          uk ? "Розкажіть про себе і свій підхід" : "Tell us about yourself and your approach"
        }
        required
      />
      <label className="resume-upload mono">
        <input type="file" accept=".pdf" />
        {uk ? "Прикріпити резюме (PDF, до 5 МБ)" : "Attach your CV (PDF, up to 5 MB)"}
      </label>
      <button className="btn btn-primary" type="submit">
        {uk ? "Відправити заявку" : "Send application"}
      </button>
      <p className="mono">
        {uk
          ? "Натискаючи кнопку, ви погоджуєтесь з обробкою персональних даних"
          : "By clicking the button, you consent to the processing of personal data"}
      </p>
    </form>
  );
}
