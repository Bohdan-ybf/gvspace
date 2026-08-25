type CaseCardProps = {
  index: number;
  title: string;
  result: string;
  badge: string;
  image?: string;
};

export function CaseCard({ index, title, result, badge, image }: CaseCardProps) {
  return (
    <article className="catalog-case-card">
      <div
        className="catalog-case-image"
        style={
          image
            ? {
                backgroundImage: `url(${image})`,
                backgroundPosition: "center",
                backgroundSize: "cover",
              }
            : undefined
        }
      >
        <span>{badge}</span>
      </div>
      <div className="catalog-case-copy">
        <div className="catalog-case-meta mono">
          <span>{String(index).padStart(2, "0")}</span>
          <span>[ E-COMMERCE / STRATEGY + IT ]</span>
        </div>
        <h2>{title}</h2>
        <p>[{result}]</p>
      </div>
    </article>
  );
}
