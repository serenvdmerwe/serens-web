# Handoff: SerensWeb — Freelance Web Developer marketing site

## Overview
SerensWeb is a single-page marketing site for a one-person freelance web-development studio. It is a dark, modern, high-craft portfolio/landing page with one continuous scroll: header → hero → about → strengths (4 service areas) → selected work → process → contact form → footer. A signature feature is a **brand-accent switcher** (orange / blue / purple / green) that re-themes the entire site live, and each of the four service areas links out to its **own subdomain** of `serensweb.dev`.

The intended production platform is **WordPress** (the design was authored token-first specifically to map onto a block theme — see "WordPress notes" at the end).

## About the design files
The files in `design-reference/` are **design references created in HTML/CSS/JS** — a working prototype that shows the intended look, layout, copy, and behavior. They are **not meant to be dropped into production as-is.** Your task is to **recreate this design in the target environment** (a WordPress block theme, or whatever framework the project settles on) using that environment's established patterns — `theme.json` tokens, block templates/patterns, and properly enqueued assets.

That said, this prototype was written to be unusually close to shippable:
- `styles.css` is **token-first** — every color and size is a CSS custom property. It maps almost 1:1 onto a WordPress `theme.json` palette + custom properties. You can reuse most of it verbatim.
- `app.js` is **vanilla, framework-free JavaScript** — no build step. It can be enqueued directly in a theme.
- The markup is **semantic and block-friendly** (`header`/`main`/`section`/`footer`, real heading order, real `<a>` links).

What you should NOT ship: everything in `design-reference/_prototype-only/`. Those are the React + Babel "Tweaks" panel files used only as a **design-review control surface** while prototyping. See "What is prototype-only" below.

## Fidelity
**High-fidelity.** Final colors, typography, spacing, motion, and copy are all decided. Recreate the UI to match. Exact tokens are listed under "Design tokens" and the CSS in `design-reference/styles.css` is the source of truth for any value not spelled out here.

---

## What is production vs. prototype-only

### Ship these (recreate in WordPress)
| File | Role |
|---|---|
| `design-reference/SerensWeb.html` | Page structure & copy. Becomes your block templates / patterns. |
| `design-reference/styles.css` | Token-based stylesheet. Becomes `theme.json` + theme stylesheet. |
| `design-reference/app.js` | Vanilla interactions. Enqueue in the theme. |

### Do NOT ship these (prototype tooling only)
| File | Why it exists |
|---|---|
| `design-reference/_prototype-only/tweaks-app.jsx` | Design-review panel that drives accent / viewport-preview / motion while prototyping. React + Babel from a CDN. |
| `design-reference/_prototype-only/tweaks-panel.jsx` | The generic panel scaffold the above is built on. |

Two things in the prototype are **design-time only** and must not be confused with site features:
- **The "Viewport" slider** in the Tweaks panel is a *device-preview* tool (it squeezes the page width to preview mobile). In production, responsiveness comes from the **container queries already in `styles.css`** — there is no slider on the real site.
- The Tweaks panel's accent control is the *designer's* way to flip themes. The **visitor-facing accent switcher** (the colored dots in the header/footer) is a real, separate feature implemented in `app.js` + the page markup. Keep that one.

---

## Page structure & screens

The whole page lives inside one wrapper `<div class="vp-host">` (in production this is just the page body — the wrapper only exists so the prototype's viewport-preview slider can constrain width; you can drop the wrapper and apply `container-type: inline-size` to the body or main element instead, since the layout uses **container queries**, not media queries).

Layout breakpoints are **container queries** on the site container, mobile-first:
- `< 700c`: single column everywhere (mobile).
- `≥ 700c`: service cards 2-up, work grid 2-up, process steps 4-up, footer columns 3-up.
- `≥ 860c`: desktop nav + header CTA appear, burger/drawer hidden; hero, about, contact, footer go two-column.
- `≥ 1040c`: service cards 4-up.

### 1. Header (sticky)
- Sticky top bar, height 72px (shrinks to 64px once the page is scrolled — toggled by `.is-scrolled` added in `app.js` past 10px scroll). Background is a translucent dark with `backdrop-filter: blur(14px)`; a hairline bottom border fades in on scroll.
- **Left:** wordmark — a 30×30px rounded-square "S" mark in the accent color (mono font, weight 700) + "Serens**Web**" where "Web" is accent-colored.
- **Center (desktop ≥860c):** nav links — Work, Strengths, Process, About. Muted text, pill hover with faint background.
- **Right (desktop):** the **accent switcher** (4 small 18px dots, divider line to its right) followed by the primary CTA button "Let's talk" with an arrow icon.
- **Mobile (<860c):** nav, CTA, and the header switcher are hidden; a bordered burger button shows. Tapping it opens a full-screen drawer (links with mono index numbers + a full-width CTA). The burger animates into an X via `body.menu-open`.

> **Switcher placement is configurable** via `body[data-switcher]` (`header` / `hero` / `both`). **The chosen default is `header`** — the switcher lives in the top bar. `app.js` reads it from a `?place=` URL param and defaults to `header`. The hero and footer variants exist in the markup but are hidden by CSS for the header setting. For a real build you can hard-code the header placement and delete the hero/footer switcher markup + the `[data-switcher]` rules.

### 2. Hero (`.section--dark`)
- Full-width dark section, generous vertical padding (`clamp(72px,12cqi,150px)`).
- **Background decoration** (all pure CSS, `aria-hidden`): a blurred radial "mesh" glow in the accent color top-right + a secondary pink-tinted glow bottom-left; plus a faint 56px grid masked by a radial fade. These are decorative — recreate with CSS, no images.
- **Left column:**
  - Eyebrow: "Freelance Web Developer" (mono, uppercase, accent, with a short leading rule).
  - H1: "Websites that *perform* as well as they look." — "perform" is accent-colored (`<em>`, not italic). Size `clamp(2.6rem,8.4cqi,5.4rem)`, weight 600, line-height 0.98, tight tracking.
  - Sub-paragraph (muted, max 50ch): "I design and build fast, modern, conversion-focused sites and web apps, from headless commerce to AI-powered tools. Independent, end-to-end, shipped with care."
  - Two buttons: primary "Start a project" (arrow) + ghost "View selected work".
  - **Hero accent switcher** (hidden in the chosen header config — present for the `hero`/`both` options).
  - Three stats row: **8+ yrs** Shipping for clients · **40+** Projects delivered · **98** Avg. Lighthouse. The number portion uses accent color on the leading digits.
- **Right column (≥860c):** a decorative "code card" — a faux browser window (three dots, first dot accent-colored; URL pill "serensweb.dev") over a monospace code block with syntax-style coloring and a blinking accent caret. Purely decorative, `aria-hidden`.

### 3. About (`.section--light`)
- Light smoke background, dark text. Two columns at ≥860c (1.4fr / 1fr).
- Left: eyebrow "About" + large statement (`clamp(1.5rem,3.4cqi,2.45rem)`, weight 500): "A one-person studio for teams who want *senior engineering* without the agency overhead. Strategy, design and code from a single, accountable partner." ("senior engineering" accent-colored.)
- Right: paragraph "I work directly with founders, marketers and product teams to turn ambitious ideas into polished, maintainable software. No handoffs, no bloat, just clean work that ships." + eyebrow "Core stack" + a wrapped row of mono pill chips: TypeScript, React, Next.js, Astro, Node, WordPress, Tailwind, Shopify Hydrogen, Postgres, Edge / Workers. Chips lift + accent-border on hover.

### 4. Strengths / "What I build" (`.section--dark`) — the subdomain section
- Eyebrow "What I build", H2 "Specialised where it matters.", lede "Four areas of focused expertise, each with a dedicated home on its own subdomain."
- A grid of **four cards** (1-up mobile → 2-up ≥700c → 4-up ≥1040c). **Each card is an `<a>`** linking to its own subdomain, opening in a new tab (`target="_blank" rel="noopener"`):

  | # | Title | Subdomain link | Body copy |
  |---|---|---|---|
  | 01 | Custom E-Commerce & Headless Commerce | `https://commerce.serensweb.dev` | Storefronts on Shopify Hydrogen and headless stacks. Fast, flexible, and built to convert. |
  | 02 | AI-Integrated Web Apps | `https://ai.serensweb.dev` | LLM features, assistants and automation woven into real products. Useful, not gimmicky. |
  | 03 | Progressive Web Apps & Mobile-First Sites | `https://apps.serensweb.dev` | Installable, offline-ready PWAs and responsive sites that feel native on every device. |
  | 04 | Interactive Portfolios & Corporate Sites | `https://studio.serensweb.dev` | Brand-defining marketing sites with motion and craft that signal quality at first glance. |

- Card anatomy: mono index number (top-right), a 50px rounded icon tile (accent-soft background, accent stroke icon), title, body, and at the bottom a **mono subdomain URL** where the subdomain segment (e.g. `commerce`) is accent-colored, with an up-right arrow that slides on hover. Card lifts and reveals a faint accent radial glow on hover.

> **Architecture note for the developer:** these four "subdomains" are a content/IA decision, not yet built. A real subdomain (`commerce.serensweb.dev`) means either a separate WordPress install, a WordPress **multisite** subdomain network, or a routing/landing setup. Confirm with the client which model they want before wiring the links — for now they're plain outbound links to the intended URLs. If the subdomains don't exist yet, you may want to point them at on-page anchors or "coming soon" routes instead.

### 5. Selected work (`.section--light`)
- Eyebrow "Selected work", H2 "A few recent builds.", lede "Placeholder case studies. Tap any project to preview the detail view."
- A grid (1→2-up) of **project cards injected by `app.js`** from a `PROJECTS` array (4 placeholder case studies). Each card: a 16:10 gradient "viz" block (one of four vivid gradient themes `viz-a`..`viz-d`) with a faint grid overlay, a category chip (bottom-left), and a circular "view" affordance (top-right, appears on hover). Body: mono category, title, short blurb.
- Clicking a card opens a **lightbox modal** (see Interactions) with a larger viz, full description, tag pills, and a 3-metric row.
- The placeholder projects (title / category / blurb / tags / metrics) live in `app.js` — replace with real case studies. Copy has already had em/en dashes removed.

### 6. Process (`.section--dark2`, slightly lighter dark)
- Eyebrow "How I work", H2 "A clear path, every time.", lede about a lightweight transparent process.
- Four steps (1-up → 4-up ≥700c), each: a top hairline with an accent dot, mono "0X / Label", H3, and body:
  - 01 Discover — "We align on goals, audience and scope, then I map the work into clear, costed milestones."
  - 02 Design — "Wireframes to high-fidelity UI, reviewed in the browser so you see exactly what ships."
  - 03 Build — "Clean, tested, accessible code with previews at every step. No black boxes."
  - 04 Launch & support — "Performance pass, deploy to the edge, and ongoing support to keep things sharp."

### 7. Contact (`.section--dark`)
- Two columns (≥860c). Left: eyebrow "Contact", H2 "Have a project in *mind?*" ("mind?" accent), lede, and a meta list (email `hello@serensweb.dev`, "Remote · working worldwide", "Currently booking for Q3") each with an accent icon.
- Right: a **contact form** (name / email / project details) with mono uppercase labels, dark translucent inputs, accent focus border. **Client-side validation** in `app.js`: name required, email must match a basic pattern, message ≥10 chars; invalid fields get a red border + error message. On valid submit the form is replaced by a success card ("Message sent. Thank you.") with a "Send another" reset button. **No backend** — wire this to the real form handler (WP Forms, a REST endpoint, etc.).

### 8. Footer (`.section--dark`)
- Brand block (wordmark + tagline), three link columns:
  - **Sitemap:** Work / Strengths / Process / About (on-page anchors).
  - **Services:** Headless commerce / AI web apps / PWAs / Marketing sites — these link to the **four subdomains** (new tab).
  - **Contact:** `hello@serensweb.dev` (mailto) / Start a project (anchor).
- A footer accent switcher exists in markup but is **hidden** in the chosen `header` placement.
- Bottom row: "© <current year> SerensWeb. All rights reserved." (year injected by `app.js`) + GitHub / LinkedIn / X social icon buttons (placeholder `#` hrefs — replace with real profiles).

---

## Interactions & behavior (all in `app.js`, vanilla)
- **Sticky header shrink:** adds `.is-scrolled` to the header past 10px scroll (height 72→64px, border + stronger bg).
- **Mobile drawer:** burger toggles `body.menu-open`; drawer fades/slides in, burger morphs to X; closing on link click and on Escape.
- **Accent switcher (visitor feature):** clicking a `.accent-dot` sets `--color-primary` (and derived `--color-primary-strong`) on `:root`, updates `aria-pressed` on all dot groups, and **persists to `localStorage`**. On load the saved accent is restored. Four accents: orange `#F6821F` (default), blue `#2F73E8`, purple `#8B5CF6`, green `#1FA463`. (Note: the prototype's React Tweaks panel uses a parallel accent map with slightly different hexes for its swatches — the **canonical visitor values are the ones in `app.js` / the dot markup** listed here.)
- **Project cards + lightbox:** cards are generated from a `PROJECTS` array and injected into `#workGrid`; clicking opens `#lightbox` (populates title/category/desc/tags/metrics, locks body scroll); closes on scrim, close button, or Escape.
- **Scroll reveal:** an IntersectionObserver adds `.is-visible` to `.reveal` / `.reveal-stagger` elements as they enter view (fade + 22px rise; stagger delays children). Respects `prefers-reduced-motion`. A `body.no-anim` class (set by the Tweaks motion toggle in the prototype) disables it — in production, just keep the reduced-motion handling.
- **Form validation & success:** as described in the Contact section.
- **Year:** `#year` set to the current year.
- **Switcher placement:** `app.js` reads `?place=` and sets `body[data-switcher]`, default `header`.

---

## Design tokens
From `:root` in `styles.css` (source of truth). 

**Brand / accent (the one knob that re-themes everything):**
- `--color-primary: #F6821F` (orange; default) — also blue `#2F73E8`, purple `#8B5CF6`, green `#1FA463`
- `--color-primary-strong: #E5751A` (hover/pressed)
- `--color-primary-soft` = primary @ 16% over transparent; `--color-primary-faint` = @ 8%

**Surfaces:** dark `#1A1A1A`, dark-2 `#2A2A2A`, dark-3 (card on dark) `#232323`, smoke `#F5F5F5`, smoke-2 `#E8E8E8`, white `#FCFCFB`

**Text:** light `#EDEBE7`, muted `#A8A6A2`, dark `#1A1A1A`, dark-muted `#5A5A58`

**Lines:** hairline-dark `rgba(255,255,255,0.10)`, hairline-light `rgba(0,0,0,0.09)`

**Type:** sans = **Geist** (400/500/600/700), mono = **Geist Mono** (400/500/700), loaded from Google Fonts. Headings 600, tight negative tracking; eyebrows/labels/metadata use the mono font, uppercase, wide tracking.

**Radii:** sm 8 · md 14 · lg 22 · pill 999px

**Spacing rhythm:** `--section-pad: clamp(64px,9cqi,132px)`, `--gutter: clamp(20px,5cqi,56px)`, `--maxw: 1240px`

**Motion:** `--ease: cubic-bezier(0.22,1,0.36,1)`, `--dur: 0.55s`

**Shadows:** card `0 1px 2px rgba(0,0,0,.04), 0 12px 32px -12px rgba(0,0,0,.16)`; pop `0 24px 70px -24px rgba(0,0,0,.45)`

> Copywriting rule applied throughout: **no em dashes, en dashes, or long hyphens** in visible copy (recast as periods/commas/colons). Keep this convention if you edit copy. Ordinary hyphens in compound words ("end-to-end", "Mobile-First") are fine. The interpunct "·" is used as a separator in a couple of meta lines.

## Assets
- **No raster/vector image files.** All imagery is CSS (gradient hero glows, gradient project "viz" blocks, faux code card) or **inline SVG** icons (service icons, contact icons, social logos, UI arrows/close). Recreate as inline SVG or your icon system.
- **Fonts:** Geist + Geist Mono via Google Fonts. In WordPress, bundle them locally (e.g. via `theme.json` `settings.typography.fontFamilies` with `@font-face`) for performance/GDPR rather than the CDN link.
- **Social/profile links** and the **contact form backend** are placeholders to wire up.

## Files in this bundle
```
design_handoff_serensweb_site/
├── README.md                         ← this file
└── design-reference/
    ├── SerensWeb.html                ← page structure & copy (PRODUCTION reference)
    ├── styles.css                    ← token-based stylesheet (PRODUCTION reference)
    ├── app.js                        ← vanilla interactions (PRODUCTION reference)
    └── _prototype-only/              ← design-review tooling — DO NOT SHIP
        ├── tweaks-app.jsx
        └── tweaks-panel.jsx
```
To preview the prototype, open `SerensWeb.html` in a browser (it loads the fonts, CSS, JS, and the Tweaks panel from CDNs).

---

## WordPress notes (intended platform)
The CSS was authored to translate to a **block theme**:
- Map the `:root` custom properties onto `theme.json` — accents/surfaces/text into `settings.color.palette`, radii/spacing/type into `settings.custom`. Because everything references `--color-primary`, theme variants (the 4 accents) can be exposed as palette options or style variations.
- Rebuild the page as **block templates + patterns** (header & footer as template parts), preserving the section order and the `section--dark` / `section--light` / `section--dark2` alternation.
- Enqueue `app.js` (or split its concerns into smaller scripts) via `functions.php` / `wp_enqueue_scripts`. The accent switcher's `localStorage` persistence and the reveal observer work unchanged.
- The layout uses **container queries** (`@container site (...)`) rather than viewport media queries — make sure the page wrapper carries `container-type: inline-size; container-name: site;`.
- Replace the placeholder `PROJECTS` data with real case studies (custom post type or ACF), wire the contact form to a real handler, and decide the subdomain architecture for the four service areas.
