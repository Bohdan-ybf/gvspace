import type { MetadataRoute } from "next";

export default function manifest(): MetadataRoute.Manifest {
  return {
    name: "GVSPACE",
    short_name: "GVSPACE",
    description: "Керовані системи маркетингу, IT та стратегії для масштабування бізнесу.",
    start_url: "/uk",
    display: "standalone",
    background_color: "#ffffff",
    theme_color: "#00003d",
    icons: [{ src: "/icon.svg", sizes: "any", type: "image/svg+xml" }],
  };
}
