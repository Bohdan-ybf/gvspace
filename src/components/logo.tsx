import Image from "next/image";

type LogoVariant = "header" | "footer";

const dimensions: Record<LogoVariant, { width: number; height: number }> = {
  header: { width: 217, height: 40 },
  footer: { width: 519, height: 96 },
};

type LogoProps = {
  variant?: LogoVariant;
  priority?: boolean;
  className?: string;
};

export function Logo({ variant = "header", priority = false, className }: LogoProps) {
  const { width, height } = dimensions[variant];

  return (
    <Image
      src="/images/figma/logo-white.svg"
      alt="GVSPACE"
      width={width}
      height={height}
      priority={priority}
      className={className}
    />
  );
}
