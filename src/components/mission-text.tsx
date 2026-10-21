"use client";

import { useCallback, useEffect, useRef, useState, type ReactNode } from "react";

export function MissionText({ title, children }: { title?: string; children?: ReactNode }) {
  const scrollerRef = useRef<HTMLDivElement>(null);
  const trackRef = useRef<HTMLSpanElement>(null);
  const [thumb, setThumb] = useState({ height: 0, top: 0, visible: false });

  const update = useCallback(() => {
    const scroller = scrollerRef.current;
    if (!scroller) return;
    const { scrollHeight, clientHeight, scrollTop } = scroller;
    const canScroll = scrollHeight > clientHeight + 1;
    if (!canScroll) {
      setThumb({ height: 0, top: 0, visible: false });
      return;
    }
    const height = Math.min(65, clientHeight);
    const maxTop = clientHeight - height;
    const top = maxTop * (scrollTop / (scrollHeight - clientHeight));
    setThumb({ height, top, visible: true });
  }, []);

  useEffect(() => {
    const scroller = scrollerRef.current;
    if (!scroller) return;
    update();
    scroller.addEventListener("scroll", update, { passive: true });
    const observer = new ResizeObserver(update);
    observer.observe(scroller);
    return () => {
      scroller.removeEventListener("scroll", update);
      observer.disconnect();
    };
  }, [update, title, children]);

  const scrollFromPointer = (clientY: number) => {
    const scroller = scrollerRef.current;
    const track = trackRef.current;
    if (!scroller || !track) return;
    const rect = track.getBoundingClientRect();
    const maxTop = rect.height - thumb.height;
    const nextTop = Math.min(Math.max(clientY - rect.top - thumb.height / 2, 0), maxTop);
    scroller.scrollTop =
      (maxTop === 0 ? 0 : nextTop / maxTop) * (scroller.scrollHeight - scroller.clientHeight);
  };

  return (
    <section className={`mission container${thumb.visible ? " is-scrollable" : ""}`}>
      <div className="mission-scroll" ref={scrollerRef}>
        {title ? <b>{title}</b> : null}
        {children}
      </div>
      {thumb.visible ? (
        <span
          className="mission-scrollbar"
          ref={trackRef}
          aria-hidden="true"
          onPointerDown={(event) => {
            if ((event.target as HTMLElement).dataset.thumb === "true") return;
            scrollFromPointer(event.clientY);
          }}
        >
          <span
            className="mission-scrollbar-thumb"
            data-thumb="true"
            style={{ height: thumb.height, transform: `translateY(${thumb.top}px)` }}
            onPointerDown={(event) => {
              event.preventDefault();
              event.stopPropagation();
              const scroller = scrollerRef.current;
              const track = trackRef.current;
              if (!scroller || !track) return;
              const startY = event.clientY;
              const startScroll = scroller.scrollTop;
              const maxTop = track.getBoundingClientRect().height - thumb.height;
              const scrollable = scroller.scrollHeight - scroller.clientHeight;
              const move = (moveEvent: PointerEvent) => {
                const delta = moveEvent.clientY - startY;
                scroller.scrollTop = startScroll + (maxTop === 0 ? 0 : delta / maxTop) * scrollable;
              };
              const stop = () => {
                window.removeEventListener("pointermove", move);
                window.removeEventListener("pointerup", stop);
              };
              window.addEventListener("pointermove", move);
              window.addEventListener("pointerup", stop);
            }}
          />
        </span>
      ) : null}
    </section>
  );
}
