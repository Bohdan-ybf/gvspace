type ChevronDownProps = {
  className?: string;
};

export function ChevronDown({ className }: ChevronDownProps) {
  return (
    <svg
      className={className}
      width="7"
      height="4"
      viewBox="0 0 7 4"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      aria-hidden="true"
      focusable="false"
    >
      <path
        d="M3.35925 3.60443L-8.26546e-05 2.98848e-05L6.71859 3.04721e-05L3.35925 3.60443Z"
        fill="currentColor"
      />
    </svg>
  );
}
