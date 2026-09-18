type CaseArrowProps = { className?: string };

export function CaseArrow({ className }: CaseArrowProps) {
  return (
    <svg
      className={className}
      width="28"
      height="28"
      viewBox="0 0 28 28"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      aria-hidden="true"
      focusable="false"
    >
      <path
        d="M19.2498 0H0V8.75025H17.1412C13.9526 14.9773 7.46693 19.2498 0 19.2498V28C7.45508 28 14.231 25.0859 19.2498 20.3317V28H28V0H19.2498Z"
        fill="#000080"
        fillOpacity="0.7"
      />
    </svg>
  );
}
