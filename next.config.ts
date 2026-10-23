import type { NextConfig } from "next";

const securityHeaders = [
  { key: "X-Content-Type-Options", value: "nosniff" },
  { key: "X-Frame-Options", value: "SAMEORIGIN" },
  { key: "Referrer-Policy", value: "strict-origin-when-cross-origin" },
  { key: "Permissions-Policy", value: "camera=(), microphone=(), geolocation=()" },
];

const wordpressUrl = new URL(process.env.WORDPRESS_URL ?? "http://localhost:8080");

const nextConfig: NextConfig = {
  output: "standalone",
  agentRules: false,
  serverExternalPackages: ["nodemailer"],
  allowedDevOrigins: ["gvspace.test", "ua.gvspace.test"],
  poweredByHeader: false,
  reactStrictMode: true,
  images: {
    formats: ["image/webp"],
    remotePatterns: [
      {
        protocol: wordpressUrl.protocol.replace(":", "") as "http" | "https",
        hostname: wordpressUrl.hostname,
        port: wordpressUrl.port,
        pathname: "/wp-content/uploads/**",
      },
    ],
  },
  async headers() {
    const staticAssetHeaders = [
      { key: "Cache-Control", value: "public, max-age=86400, stale-while-revalidate=604800" },
    ];

    return [
      { source: "/(.*)", headers: securityHeaders },
      { source: "/images/:path*", headers: staticAssetHeaders },
      { source: "/icon.svg", headers: staticAssetHeaders },
      { source: "/favicon.ico", headers: staticAssetHeaders },
      { source: "/favicon-96x96.png", headers: staticAssetHeaders },
      { source: "/apple-icon.png", headers: staticAssetHeaders },
      { source: "/web-app-manifest-192x192.png", headers: staticAssetHeaders },
      { source: "/web-app-manifest-512x512.png", headers: staticAssetHeaders },
    ];
  },
};

export default nextConfig;
