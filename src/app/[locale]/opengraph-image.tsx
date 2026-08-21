import { ImageResponse } from "next/og";

export const alt = "GVSPACE — scalable business systems";
export const size = { width: 1200, height: 630 };
export const contentType = "image/png";

export default function OpenGraphImage() {
  return new ImageResponse(
    <div
      style={{
        width: "100%",
        height: "100%",
        display: "flex",
        flexDirection: "column",
        justifyContent: "space-between",
        padding: "72px",
        color: "white",
        background: "linear-gradient(135deg, #00003d 0%, #000080 58%, #5500ff 100%)",
        fontFamily: "sans-serif",
      }}
    >
      <div style={{ display: "flex", fontSize: 52, fontWeight: 700 }}>GVSPACE</div>
      <div
        style={{
          display: "flex",
          maxWidth: 900,
          fontSize: 76,
          lineHeight: 1.05,
          fontWeight: 700,
        }}
      >
        Scalable business systems
      </div>
    </div>,
    size,
  );
}
