function paginationItems(current: number, total: number) {
  if (total <= 6) return Array.from({ length: total }, (_, index) => index + 1);

  const pages = new Set<number>([1, total]);
  if (current <= 4) {
    for (let page = 1; page <= 4; page += 1) pages.add(page);
  } else if (current >= total - 3) {
    for (let page = total - 3; page <= total; page += 1) pages.add(page);
  } else {
    pages.add(current - 1);
    pages.add(current);
    pages.add(current + 1);
  }

  const sorted = [...pages].sort((left, right) => left - right);
  const items: Array<number | "ellipsis"> = [];
  sorted.forEach((page, index) => {
    if (index > 0 && page - sorted[index - 1] > 1) items.push("ellipsis");
    items.push(page);
  });
  return items;
}

function PaginationArrow({
  direction,
  disabled,
}: {
  direction: "prev" | "next";
  disabled: boolean;
}) {
  const pointsLeft = direction === "prev";

  return (
    <svg width="12" height="20" viewBox="0 0 12 20" fill="none" aria-hidden="true">
      <path
        d={
          pointsLeft
            ? "M10.6064 1.06062L2.12109 9.5459L10.6064 18.0312"
            : "M1.06159 1.06062L9.54688 9.5459L1.06159 18.0312"
        }
        stroke="#5500FF"
        strokeOpacity={disabled ? 0.5 : 1}
        strokeWidth="3"
      />
    </svg>
  );
}

export function scrollToBlockTop(element: HTMLElement | null) {
  if (!element) return;
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const header = document.querySelector("header");
  const offset = header instanceof HTMLElement ? header.getBoundingClientRect().height : 0;
  const top = element.getBoundingClientRect().top + window.scrollY - offset;
  window.scrollTo({ top: Math.max(0, top), behavior: reduceMotion ? "auto" : "smooth" });
}

export function Pagination({
  page,
  pageCount,
  onPageChange,
  label,
  previousLabel,
  nextLabel,
  className,
}: {
  page: number;
  pageCount: number;
  onPageChange: (page: number) => void;
  label: string;
  previousLabel: string;
  nextLabel: string;
  className?: string;
}) {
  if (pageCount <= 1) return null;

  return (
    <nav className={className ? `pagination ${className}` : "pagination"} aria-label={label}>
      <button
        className="pagination-arrow"
        type="button"
        aria-label={previousLabel}
        disabled={page <= 1}
        onClick={() => onPageChange(page - 1)}
      >
        <PaginationArrow direction="prev" disabled={page <= 1} />
      </button>
      {paginationItems(page, pageCount).map((item, index) =>
        item === "ellipsis" ? (
          <span className="pagination-ellipsis" key={`ellipsis-${index}`}>
            ...
          </span>
        ) : (
          <button
            key={item}
            type="button"
            className={item === page ? "is-active" : undefined}
            aria-current={item === page ? "page" : undefined}
            onClick={() => onPageChange(item)}
          >
            {item}
          </button>
        ),
      )}
      <button
        className="pagination-arrow"
        type="button"
        aria-label={nextLabel}
        disabled={page >= pageCount}
        onClick={() => onPageChange(page + 1)}
      >
        <PaginationArrow direction="next" disabled={page >= pageCount} />
      </button>
    </nav>
  );
}
