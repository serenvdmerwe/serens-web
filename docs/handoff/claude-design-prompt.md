# Claude Design prompt for a TurboPress ready handoff

Paste the prompt block below into a Claude Design session. It makes the exported
design bundle contain exactly the files and conventions the TurboPress `turbopress-wp`
plugin consumes downstream (skills: `wp-handoff-bootstrap`, `wp-page-from-handoff`).

## How the bundle maps onto the plugin

| Handoff file | What the plugin does with it |
|---|---|
| `design-reference/styles.css` (token first) | tokens lifted into `theme.json` (palette, typography, spacing, custom) and mirrored in `assets/css/tokens.css`; the rest is copied verbatim into `assets/css/theme.css` |
| `design-reference/page-<slug>.html` | each section becomes one block pattern; the page becomes `templates/page-<slug>.html` |
| `design-reference/app.js` | enqueued in the child theme as behaviour modules |
| `design-reference/assets/fonts/*` | self hosted fonts referenced from `theme.json` `fontFace` |
| `README.md` | drives the page list, token table, and the project `CLAUDE.md` |

## Why these rules exist

The plugin builds a WordPress Full Site Editing block child theme. It does not
translate your CSS into block styles; it copies the CSS and emits markup the CSS can
find. So your class names are the interface. It also enforces a house style: no
em dash (U+2014), no en dash (U+2013), no two ASCII hyphens together, no emojis, no
eyebrows (the small uppercase label above a heading), and self hosted fonts only.

---

## The prompt (copy everything below this line)

You are producing a design handoff that will be rebuilt as a WordPress Full Site
Editing block child theme by the TurboPress plugin. Author the design token first and
block friendly so it maps onto `theme.json` plus block patterns with no rework. Follow
every rule below exactly.

### Deliverable: a single zip named `<project>.zip` containing

```
<project>/
  README.md
  design-reference/
    page-<slug>.html        one file per page, semantic HTML, e.g. page-home.html
    styles.css              token first CSS: tokens as :root custom properties, then section rules
    app.js                  vanilla JS, no build step
    assets/
      fonts/                self hosted variable font files (.woff2 or .ttf), NO web font CDN
      images/               SVG motifs, logos, any raster art
    _prototype-only/        any React or preview tooling used only while designing, label DO NOT SHIP
```

### CSS rules (this is the contract the plugin relies on)

1. Token first. Put every color, font, spacing step, radius, shadow, motion timing,
   max width, and gutter as a CSS custom property on `:root`. Group the names cleanly,
   for example `--color-*`, `--font-*`, a numbered spacing scale `--space-1` through
   `--space-10`, `--radius-*`, `--shadow-*`, `--motion-*`, `--max-width`, `--gutter`.
2. Keep all section styling in one `styles.css`. The plugin copies this file verbatim
   into `assets/css/theme.css`, so every class name you use is the interface. Do not
   rename or minify it.
3. Use stable, descriptive class hooks on every element, in BEM style. Example hero
   hooks: `.hero`, `.hero__inner`, `.hero__title`, `.hero__lede`, `.hero__ctas`. Card
   grids: `.cards`, `.card`, `.card__title`. Give each section a wrapper class so it can
   become exactly one block pattern.
4. Prefer container queries on a single site container over viewport media queries. Put
   `container-type: inline-size` on the page wrapper.

### HTML rules

5. Semantic and block friendly: real `header`, `main`, `section`, `footer`, correct
   heading order, real `<a>` links. No div soup.
6. Compose each page from clearly delimited sections in source order. Each section is
   self contained so it becomes exactly one block pattern. Name files per page:
   `page-home.html`, `page-about.html`, and so on.
7. No build step in the production reference. No React, no JSX, no bundler output. If
   you use a live controls panel while designing, isolate it under
   `design-reference/_prototype-only/` and mark it DO NOT SHIP.

### JavaScript rules

8. Vanilla, framework free, enqueue ready. Split behaviours by concern where practical:
   navigation, header shrink, reveal on scroll, lightbox, contact form, theme switcher.
   Respect `prefers-reduced-motion`.

### Fonts and assets

9. Self host fonts. Ship the actual variable font files in `assets/fonts/` and load them
   with `@font-face` in the CSS. Do not link Google Fonts or any web font CDN. This is
   for performance and GDPR.
10. Imagery as CSS or inline SVG where possible. Put real image files in `assets/images/`.

### TurboPress house style (hard bans, enforce in copy and markup)

11. No em dash (U+2014), no en dash (U+2013), no two ASCII hyphens together in any
    visible copy. Rewrite with commas, colons, parentheses, or a new sentence.
12. No emojis anywhere: copy, code, comments, file names.
13. No eyebrows. Do not place a small uppercase label above a heading, and do not add an
    eyebrow class or eyebrow font size. Fold that text into the lede paragraph, turn it
    into a real sub headline, or drop it.
14. No numbered section or step prefixes such as `01.` or `02.`. If sequence matters,
    express it in words inside the heading or copy, not as a decorative numeral.
15. Target Full Site Editing block themes only. Do not assume Kadence, Elementor, or any
    page builder.

### README.md (the written handoff) must include

16. Project overview and intended platform (WordPress FSE block child theme of Twenty
    Twenty-Five).
17. Fidelity statement: which values are final.
18. Page list, and for each page an ordered section list. This drives the `theme.json`
    `customTemplates` entries and the one pattern per section mapping.
19. A complete design token table, matching the `:root` custom properties.
20. A behaviour spec for `app.js`: what each interaction does.
21. An asset inventory: fonts, images, icons.
22. Open decisions or TODOs (form backend, external links, real content) called out
    explicitly.
23. A clear production versus prototype only list so nothing from `_prototype-only/`
    ships.

---

## Note on the current SerensWeb handoff

The existing `design_handoff_serensweb_site` bundle is strong, but it predates these
rules and deviates in three ways that the bootstrap step will resolve:

1. It uses eyebrows on every section. These will be folded into the lede or dropped.
2. It uses `01` through `04` numerals on process steps and service cards. These will be
   reworded or dropped.
3. It loads Geist and Geist Mono from Google Fonts. These will be self hosted in
   `assets/fonts/` and declared in `theme.json`.

Use the prompt above for any future Claude Design work so these do not recur.
