const letters: Record<string, string> = {
  а: "a",
  б: "b",
  в: "v",
  г: "h",
  ґ: "g",
  д: "d",
  е: "e",
  є: "ie",
  ж: "zh",
  з: "z",
  и: "y",
  і: "i",
  ї: "i",
  й: "i",
  к: "k",
  л: "l",
  м: "m",
  н: "n",
  о: "o",
  п: "p",
  р: "r",
  с: "s",
  т: "t",
  у: "u",
  ф: "f",
  х: "kh",
  ц: "ts",
  ч: "ch",
  ш: "sh",
  щ: "shch",
  ь: "",
  ю: "iu",
  я: "ia",
  "'": "",
  "’": "",
  ʼ: "",
};

const wordStart: Record<string, string> = {
  є: "ye",
  ї: "yi",
  й: "y",
  ю: "yu",
  я: "ya",
};

function decodeSlug(value: string) {
  try {
    return decodeURIComponent(value);
  } catch {
    return value;
  }
}

export function toLatinSlug(value: string) {
  const source = decodeSlug(value).normalize("NFC").toLowerCase();
  let result = "";
  let atWordStart = true;

  for (const char of source) {
    if (char === "-" || char === " " || char === "_") {
      result += "-";
      atWordStart = true;
      continue;
    }
    if (atWordStart && wordStart[char]) result += wordStart[char];
    else if (letters[char] !== undefined) result += letters[char];
    else if (/[a-z0-9]/.test(char)) result += char;
    atWordStart = false;
  }

  return result.replace(/-+/g, "-").replace(/^-|-$/g, "");
}
