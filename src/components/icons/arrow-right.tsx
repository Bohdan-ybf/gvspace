type ArrowRightProps = { className?: string };

export function ArrowRight({ className }: ArrowRightProps) {
  return (
    <svg
      className={className}
      width="13"
      height="12"
      viewBox="0 0 13 12"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      aria-hidden="true"
      focusable="false"
    >
      <path
        d="M11.6667 5.66634L1 5.66634M7 0.999674L11.6667 5.66634L7 10.333"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
}
