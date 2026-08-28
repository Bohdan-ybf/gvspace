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
    icons: [
      { src: "/icon.svg", sizes: "any", type: "image/svg+xml" },
      { src: "/favicon-96x96.png", sizes: "96x96", type: "image/png" },
      {
        src: "/web-app-manifest-192x192.png",
        sizes: "192x192",
        type: "image/png",
      },
      {
        src: "/web-app-manifest-512x512.png",
        sizes: "512x512",
        type: "image/png",
      },
    ],
  };
}
