import type { Locale } from "@/i18n";

export type BlogPost = {
  slug: string;
  title: string;
  excerpt: string;
  content: string;
  category: string;
  publishedAt: string;
  readingTime: number;
  author: { slug: string; name: string; role: string; avatar?: string };
  tags: string[];
};

export type BlogPostSummary = {
  slug: string;
  title: string;
  excerpt: string;
  category: string;
  publishedAt: string;
  readingTime: number;
  image?: string;
};

const endpoint = process.env.WORDPRESS_GRAPHQL_URL;

export const fallbackPost: BlogPost = {
  slug: "marketing-lottery",
  title: "Чому ваш маркетинг виглядає як лотерея — і як це виправити за 30 днів",
  excerpt:
    "Розбираємо три системні помилки, які перетворюють рекламний бюджет на непередбачувані результати.",
  category: "СТРАТЕГІЯ",
  publishedAt: "14 травня 2026",
  readingTime: 8,
  author: { slug: "vasyl-hordiichuk", name: "Василь Гордійчук", role: "CEO, GVSPACE" },
  tags: ["СТРАТЕГІЯ", "АНАЛІТИКА", "PERFORMANCE", "GA4"],
  content: `
    <p>Більшість власників бізнесу, з якими ми спілкуємося на Clarity Session, описують свій маркетинг однаково: «щось працює, але не розуміємо що». Це не проблема бюджету. Це проблема системи.</p>
    <h2 id="lottery">Три ознаки того, що ваш маркетинг — це лотерея</h2>
    <p>Перша і найочевидніша ознака — ви не можете передбачити, скільки лідів отримаєте наступного місяця. Навіть приблизно. Якщо відповідь «не знаємо, дивимося по ситуації» — це і є лотерея.</p>
    <ol><li>Бюджет витрачається, але незрозуміло, який канал приносить гроші.</li><li>Кожен місяць — нова гіпотеза замість оптимізації системи, що вже працює.</li><li>Звіти від агенції є, але рішення на їх основі не ухвалюються.</li></ol>
    <blockquote>«Ріст можливий лише тоді, коли є простір, ясність і система.»<cite>GVSPACE, Brand Strategy 2026</cite></blockquote>
    <h2 id="managed-marketing">Що таке керований маркетинг і як він виглядає на практиці</h2>
    <p>Керований маркетинг — це не про те, щоб запускати більше реклами. Це про те, щоб розуміти причинно-наслідковий зв’язок між кожною дією та результатом.</p>
    <aside><small>КЛЮЧОВИЙ ІНСАЙТ</small>Наскрізна аналітика — єдиний інструмент, який перетворює маркетинг з витрати на інвестицію. Без неї ви не знаєте, що саме повернуло гроші.</aside>
    <h3>Крок 1: Clarity before growth</h3><p>Перш ніж масштабувати, потрібно зрозуміти, що саме працює. Це звучить очевидно, але більшість агенцій пропускають цей крок і одразу переходять до запуску реклами.</p>
    <h3>Крок 2: Побудова системи вимірювання</h3><p>GA4 + GTM + CRM-інтеграція — це мінімальний стек, який дозволяє бачити повний шлях клієнта. Без цього будь-яка оптимізація — це стрілянина в темряві.</p>
    <h2 id="thirty-days">Що можна зробити за 30 днів</h2>
    <p>30 днів — це реальний горизонт, щоб перейти від хаосу до перших вимірюваних результатів. Але тільки якщо почати з правильного кроку.</p>
    <ul><li><b>Тиждень 1:</b> Clarity Session + аудит поточного стану аналітики.</li><li><b>Тиждень 2:</b> Налаштування наскрізного трекінгу та дашборду.</li><li><b>Тиждень 3:</b> Перша оптимізація на основі даних, а не відчуттів.</li><li><b>Тиждень 4:</b> Перший звіт з реальними цифрами по кожному каналу.</li></ul>
    <div class="article-conclusion" id="article-conclusion"><small>ВИСНОВОК</small><p>Маркетинг стає системною не тоді, коли ви витрачаєте більше бюджету. А тоді, коли кожна дія має вимірюваний наслідок. Саме з цього починається простір для росту.</p></div>
  `,
};

type WordPressPost = {
  slug: string;
  title: string;
  excerpt: string;
  content: string;
  date: string;
  author?: {
    node?: {
      slug?: string;
      name?: string;
      avatar?: { url?: string };
      gvspaceAuthorProfile?: { role?: string };
    };
  };
  categories?: { nodes?: Array<{ name: string }> };
  tags?: { nodes?: Array<{ name: string }> };
  featuredImage?: { node?: { sourceUrl?: string } };
};

function stripHtml(value: string) {
  return value
    .replace(/<[^>]*>/g, " ")
    .replace(/\s+/g, " ")
    .trim();
}

export async function getBlogPosts(locale: Locale): Promise<BlogPostSummary[]> {
  if (!endpoint) return [];
  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query Posts { posts(first: 100, where: { status: PUBLISH }) { nodes { slug title excerpt content date featuredImage { node { sourceUrl } } categories { nodes { name } } } } }`,
      }),
      next: { revalidate: 60 },
    });
    if (!response.ok) return [];
    const result = (await response.json()) as { data?: { posts?: { nodes?: WordPressPost[] } } };
    return (
      result.data?.posts?.nodes?.map((post) => ({
        slug: post.slug,
        title: stripHtml(post.title),
        excerpt: stripHtml(post.excerpt),
        category: post.categories?.nodes?.[0]?.name ?? "БЛОГ",
        publishedAt: new Intl.DateTimeFormat(locale === "uk" ? "uk-UA" : "en-GB", {
          day: "2-digit",
          month: "short",
          year: "numeric",
        }).format(new Date(post.date)),
        readingTime: Math.max(
          1,
          Math.ceil(stripHtml(post.content || post.excerpt).split(" ").length / 200),
        ),
        image: post.featuredImage?.node?.sourceUrl,
      })) ?? []
    );
  } catch {
    return [];
  }
}

export async function getBlogPostsByAuthor(
  slug: string,
  locale: Locale,
): Promise<BlogPostSummary[]> {
  if (!endpoint) return [];
  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `query AuthorPosts($slug: ID!) { user(id: $slug, idType: SLUG) { posts(first: 100) { nodes { slug title excerpt content date featuredImage { node { sourceUrl } } categories { nodes { name } } } } } }`,
        variables: { slug },
      }),
      next: { revalidate: 60 },
    });
    if (!response.ok) return [];
    const result = (await response.json()) as {
      data?: { user?: { posts?: { nodes?: WordPressPost[] } } };
    };
    return (
      result.data?.user?.posts?.nodes?.map((post) => ({
        slug: post.slug,
        title: stripHtml(post.title),
        excerpt: stripHtml(post.excerpt),
        category: post.categories?.nodes?.[0]?.name ?? "БЛОГ",
        publishedAt: new Intl.DateTimeFormat(locale === "uk" ? "uk-UA" : "en-GB", {
          day: "2-digit",
          month: "short",
          year: "numeric",
        }).format(new Date(post.date)),
        readingTime: Math.max(
          1,
          Math.ceil(stripHtml(post.content || post.excerpt).split(" ").length / 200),
        ),
        image: post.featuredImage?.node?.sourceUrl,
      })) ?? []
    );
  } catch {
    return [];
  }
}

export async function getBlogPost(slug: string, locale: Locale): Promise<BlogPost | undefined> {
  if (endpoint) {
    try {
      const response = await fetch(endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          query: `query Post($slug: ID!) { post(id: $slug, idType: SLUG) { slug title excerpt content date author { node { slug name avatar { url } gvspaceAuthorProfile { role } } } categories { nodes { name } } tags { nodes { name } } featuredImage { node { sourceUrl } } } }`,
          variables: { slug },
        }),
        next: { revalidate: 60 },
      });
      const result = (await response.json()) as { data?: { post?: WordPressPost } };
      const post = result.data?.post;
      if (post) {
        const words = stripHtml(post.content).split(" ").length;
        return {
          slug: post.slug,
          title: stripHtml(post.title),
          excerpt: stripHtml(post.excerpt),
          content: post.content,
          category: post.categories?.nodes?.[0]?.name ?? "БЛОГ",
          publishedAt: new Intl.DateTimeFormat(locale === "uk" ? "uk-UA" : "en-GB", {
            dateStyle: "long",
          }).format(new Date(post.date)),
          readingTime: Math.max(1, Math.ceil(words / 200)),
          author: {
            slug: post.author?.node?.slug ?? "vasyl-hordiichuk",
            name: post.author?.node?.name ?? "GVSPACE",
            role: post.author?.node?.gvspaceAuthorProfile?.role || "GVSPACE",
            avatar: post.author?.node?.avatar?.url,
          },
          tags: post.tags?.nodes?.map(({ name }) => name) ?? [],
        };
      }
    } catch {
      // Local fallback keeps previews available while WordPress is offline.
    }
  }
  return undefined;
}
