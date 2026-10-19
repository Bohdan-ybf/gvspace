const stylesheet = `<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9">
  <xsl:output method="html" encoding="UTF-8" doctype-system="about:legacy-compat" />

  <xsl:template match="/">
    <html lang="en">
      <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>GVSPACE XML Sitemap</title>
        <style>
          * { box-sizing: border-box; }
          body { margin: 0; background: #f5f7f8; color: #142127; font: 14px/1.5 Arial, sans-serif; }
          header { padding: 28px clamp(20px, 4vw, 64px); background: #15282f; color: #fff; }
          h1 { margin: 0 0 6px; font-size: clamp(24px, 3vw, 36px); }
          header p { margin: 0; color: #bdc9cd; }
          main { padding: 24px clamp(12px, 4vw, 64px) 48px; }
          .table-wrap { overflow-x: auto; border: 1px solid #dce3e5; border-radius: 10px; background: #fff; }
          table { width: 100%; border-collapse: collapse; }
          th, td { padding: 12px 16px; border-bottom: 1px solid #e7ecee; text-align: left; }
          th { position: sticky; top: 0; background: #eaf0f2; color: #496069; font-size: 12px; text-transform: uppercase; letter-spacing: .06em; }
          tr:last-child td { border-bottom: 0; }
          tr:hover td { background: #f8fafb; }
          td:first-child { width: 64px; color: #789099; }
          td:last-child { width: 220px; white-space: nowrap; color: #5e737b; }
          a { color: #087b65; text-decoration: none; overflow-wrap: anywhere; }
          a:hover { text-decoration: underline; }
          @media (max-width: 640px) {
            header { padding: 22px 16px; }
            main { padding: 16px 8px 32px; }
            th, td { padding: 10px 12px; }
            td:last-child { width: auto; }
          }
        </style>
      </head>
      <body>
        <header>
          <h1>GVSPACE XML Sitemap</h1>
          <p>
            <xsl:value-of select="count(sitemap:urlset/sitemap:url)" /> URLs available for search engines
          </p>
        </header>
        <main>
          <div class="table-wrap">
            <table>
              <thead>
                <tr><th>#</th><th>URL</th><th>Last modified</th></tr>
              </thead>
              <tbody>
                <xsl:for-each select="sitemap:urlset/sitemap:url">
                  <tr>
                    <td><xsl:value-of select="position()" /></td>
                    <td>
                      <a href="{sitemap:loc}"><xsl:value-of select="sitemap:loc" /></a>
                    </td>
                    <td>
                      <xsl:choose>
                        <xsl:when test="sitemap:lastmod"><xsl:value-of select="sitemap:lastmod" /></xsl:when>
                        <xsl:otherwise>—</xsl:otherwise>
                      </xsl:choose>
                    </td>
                  </tr>
                </xsl:for-each>
              </tbody>
            </table>
          </div>
        </main>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
`;

export function GET() {
  return new Response(stylesheet, {
    headers: {
      "Content-Type": "application/xml; charset=utf-8",
      "Cache-Control": "public, max-age=86400",
    },
  });
}
