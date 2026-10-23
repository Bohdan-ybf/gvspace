let locks = 0;
let previousOverflow = "";
let previousPaddingRight = "";

export function lockPageScroll() {
  if (locks === 0) {
    const scrollbarWidth = Math.max(0, window.innerWidth - document.documentElement.clientWidth);
    previousOverflow = document.body.style.overflow;
    previousPaddingRight = document.body.style.paddingRight;
    document.body.style.overflow = "hidden";
    document.documentElement.style.setProperty("--scrollbar-compensation", `${scrollbarWidth}px`);
    if (scrollbarWidth > 0) document.body.style.paddingRight = `${scrollbarWidth}px`;
    document.documentElement.classList.add("scroll-locked");
  }

  locks += 1;

  return () => {
    locks = Math.max(0, locks - 1);
    if (locks > 0) return;
    document.body.style.overflow = previousOverflow;
    document.body.style.paddingRight = previousPaddingRight;
    document.documentElement.style.removeProperty("--scrollbar-compensation");
    document.documentElement.classList.remove("scroll-locked");
  };
}
