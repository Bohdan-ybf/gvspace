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
        d="M3.35935 3.6044L8.89818e-06 -6.32798e-07L6.71868 -4.54329e-08L3.35935 3.6044Z"
        fill="currentColor"
      />
    </svg>
  );
}
