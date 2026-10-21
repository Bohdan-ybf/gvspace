"use client";

import Image, { type ImageProps } from "next/image";
import { createContext, useContext, type ReactNode } from "react";

const ContentImageLabelContext = createContext("");

export function ContentImageLabel({ label, children }: { label: string; children: ReactNode }) {
  return (
    <ContentImageLabelContext.Provider value={label}>{children}</ContentImageLabelContext.Provider>
  );
}

function useContentImageLabel() {
  return useContext(ContentImageLabelContext);
}

type ContentPhotoProps = Omit<ImageProps, "alt" | "title">;

export function ContentPhoto(props: ContentPhotoProps) {
  const label = useContentImageLabel();
  return <Image {...props} alt={label} title={label || undefined} />;
}

export function ContentPhotoImg({
  src,
  className = "content-photo",
}: {
  src: string;
  className?: string;
}) {
  const label = useContentImageLabel();
  return (
    // eslint-disable-next-line @next/next/no-img-element -- content photos keep alt/title on the element, including WordPress URLs the image optimizer does not fetch
    <img alt={label} className={className} src={src} title={label || undefined} />
  );
}
