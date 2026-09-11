export function StructuredData({ data }: { data: Record<string, unknown> | Record<string, unknown>[] }) {
  return (
    <script
      type="application/ld+json"
      dangerouslySetInnerHTML={{ __html: JSON.stringify(data).replace(/</g, "\\u003c") }}
    />
  );
}

export function ItemListStructuredData({
  name,
  items,
}: {
  name: string;
  items: Array<{ name: string; url: string }>;
}) {
  return (
    <StructuredData
      data={{
        "@context": "https://schema.org",
        "@type": "ItemList",
        name,
        itemListElement: items.map((item, index) => ({
          "@type": "ListItem",
          position: index + 1,
          name: item.name,
          url: item.url,
        })),
      }}
    />
  );
}
