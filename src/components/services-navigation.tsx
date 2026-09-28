"use client";

import { useEffect, useRef, useState, type MouseEvent, type PointerEvent } from "react";
import { ChevronDown } from "./icons/chevron-down";

type NavigationItem = {
  slug: string;
  title: string;
};

export function ServicesNavigation({ items }: { items: NavigationItem[] }) {
  const [activeSlug, setActiveSlug] = useState(items[0]?.slug);
  const [canPrev, setCanPrev] = useState(false);
  const [canNext, setCanNext] = useState(false);
  const scrollerRef = useRef<HTMLDivElement>(null);
  const dragRef = useRef({ active: false, startX: 0, scrollLeft: 0, moved: false });

  useEffect(() => {
    const sections = items
      .map(({ slug }) => document.getElementById(slug))
      .filter((section): section is HTMLElement => section !== null);

    const observer = new IntersectionObserver(
      (entries) => {
        const visibleSection = entries
          .filter((entry) => entry.isIntersecting)
          .sort((first, second) => second.intersectionRatio - first.intersectionRatio)[0];

        if (visibleSection) {
          setActiveSlug(visibleSection.target.id);
        }
      },
      {
        rootMargin: "-140px 0px -55% 0px",
        threshold: [0, 0.2, 0.5, 0.8],
      },
    );

    sections.forEach((section) => observer.observe(section));
    return () => observer.disconnect();
  }, [items]);

  useEffect(() => {
    const scroller = scrollerRef.current;
    if (!scroller) return;

    const update = () => {
      const max = scroller.scrollWidth - scroller.clientWidth;
      setCanPrev(scroller.scrollLeft > 4);
      setCanNext(max > 4 && scroller.scrollLeft < max - 4);
    };

    update();
    scroller.addEventListener("scroll", update, { passive: true });
    const resizeObserver = new ResizeObserver(update);
    resizeObserver.observe(scroller);
    return () => {
      scroller.removeEventListener("scroll", update);
      resizeObserver.disconnect();
    };
  }, [items]);

  const scrollByDirection = (direction: -1 | 1) => {
    const scroller = scrollerRef.current;
    if (!scroller) return;
    scroller.scrollBy({
      left: direction * Math.round(scroller.clientWidth * 0.65),
      behavior: "smooth",
    });
  };

  const onPointerDown = (event: PointerEvent<HTMLDivElement>) => {
    const scroller = scrollerRef.current;
    if (!scroller || event.pointerType === "touch") return;
    dragRef.current = {
      active: true,
      startX: event.clientX,
      scrollLeft: scroller.scrollLeft,
      moved: false,
    };
  };

  const onPointerMove = (event: PointerEvent<HTMLDivElement>) => {
    const scroller = scrollerRef.current;
    const drag = dragRef.current;
    if (!scroller || !drag.active) return;
    const delta = event.clientX - drag.startX;
    if (Math.abs(delta) <= 4) return;
    drag.moved = true;
    if (!scroller.hasPointerCapture(event.pointerId)) scroller.setPointerCapture(event.pointerId);
    scroller.scrollLeft = drag.scrollLeft - delta;
  };

  const onPointerUp = (event: PointerEvent<HTMLDivElement>) => {
    dragRef.current.active = false;
    scrollerRef.current?.releasePointerCapture(event.pointerId);
  };

  const onClickCapture = (event: MouseEvent<HTMLDivElement>) => {
    if (!dragRef.current.moved) return;
    event.preventDefault();
    event.stopPropagation();
    dragRef.current.moved = false;
  };

  return (
    <nav
      className={`services-tabs${canPrev || canNext ? " is-overflowing" : ""}`}
      aria-label="Service directions"
    >
      <button
        type="button"
        className="services-tabs-arrow is-prev"
        aria-label="Попередні напрями"
        disabled={!canPrev}
        onClick={() => scrollByDirection(-1)}
      >
        <ChevronDown />
      </button>
      <div
        className="container"
        ref={scrollerRef}
        onPointerDown={onPointerDown}
        onPointerMove={onPointerMove}
        onPointerUp={onPointerUp}
        onPointerCancel={onPointerUp}
        onClickCapture={onClickCapture}
      >
        {items.map((item) => (
          <a
            className={activeSlug === item.slug ? "is-active" : undefined}
            href={`#${item.slug}`}
            aria-current={activeSlug === item.slug ? "location" : undefined}
            key={item.slug}
            onClick={() => setActiveSlug(item.slug)}
          >
            {item.title}
          </a>
        ))}
      </div>
      <button
        type="button"
        className="services-tabs-arrow is-next"
        aria-label="Наступні напрями"
        disabled={!canNext}
        onClick={() => scrollByDirection(1)}
      >
        <ChevronDown />
      </button>
    </nav>
  );
}
