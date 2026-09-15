import Image from "next/image";
import type { Partner } from "./wordpress-partners";

export function PartnersSlider({
  title,
  description,
  partners,
}: {
  title: string;
  description: string;
  partners: Partner[];
}) {
  if (!partners.length) return null;

  const repeatedPartners = [...partners, ...partners];

  return (
    <section className="partners-section" aria-labelledby="partners-title">
      <div className="container">
        <h2 id="partners-title">{title}</h2>
        <p>{description}</p>
      </div>
      <div className="partners-slider">
        <div className="partners-track">
          {repeatedPartners.map((partner, index) => (
            <article className="partner-slide" key={`${partner.id}-${index}`}>
              <div className="partner-logo">
                {partner.image && (
                  <Image src={partner.image} alt={partner.imageAlt} fill sizes="72px" unoptimized />
                )}
              </div>
              <div>
                <span className="mono">{partner.direction}</span>
                <h3>{partner.name}</h3>
              </div>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
