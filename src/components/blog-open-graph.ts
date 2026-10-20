const openGraphLabel =
  /^(?:\*\*)?(Open Graph Title|Open Graph Description)(?:\*\*)?\s*:\s*([\s\S]*)$/i;

export function stripOpenGraphLabel(value: string) {
  const trimmed = value.trim();
  const match = trimmed.match(openGraphLabel);
  const text = match ? match[2] : trimmed;
  return text
    .replace(/^\*\*\s*/, "")
    .replace(/\s*\*\*$/, "")
    .replace(/\s+/g, " ")
    .trim();
}

export function presentBlogCopy(input: { title: string; excerpt: string; content: string }) {
  let ogTitle = "";
  let ogDescription = "";
  const content = input.content.replace(/<(p|h[1-6])\b[^>]*>[\s\S]*?<\/\1>/gi, (block) => {
    const text = block
      .replace(/<[^>]+>/g, " ")
      .replace(/&nbsp;/gi, " ")
      .replace(/\s+/g, " ")
      .trim();
    const match = text.match(openGraphLabel);
    if (!match) return block;
    const value = match[2].trim();
    if (/title/i.test(match[1])) ogTitle = value;
    else ogDescription = value;
    return "";
  });

  const titleHadLabel = openGraphLabel.test(input.title.trim());
  const excerptHadLabel = openGraphLabel.test(input.excerpt.trim());
  const title = stripOpenGraphLabel(input.title) || ogTitle;
  const excerpt = excerptHadLabel ? stripOpenGraphLabel(input.excerpt) : input.excerpt.trim();

  return {
    title,
    excerpt: excerpt || (excerptHadLabel ? ogDescription : ""),
    content,
    ogTitle: ogTitle || (titleHadLabel ? title : ""),
    ogDescription: ogDescription || (excerptHadLabel ? excerpt : ""),
  };
}
