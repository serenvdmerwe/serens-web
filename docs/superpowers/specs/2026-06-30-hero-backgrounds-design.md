# Accent-driven hero backgrounds (Work, Playground, About, Home)

Date: 2026-06-30
Branch: `feat/hero-backgrounds`

## Why

The three multi-page headers (Work/projects archive, Playground, About) and the home hero
currently share one identical decorative treatment: `.hero__mesh` (accent blur blobs) plus
`.hero__grid` (a masked dot-grid). It works but it is generic and undifferentiated. The
client supplied three reference images for a more distinctive, modern, digital feel:

- egworkhero: dense glowing circuit-board / PCB traces receding in perspective.
- egplaygroundhero: flowing light-ribbon waves.
- egabouthero: a zoomed-in cloudy night sky where diffuse glow leaks through the thinner
  patches of cloud cover (no moon, no ring of focus); the heavy cloud stays dark.

Each page should get its own reference-inspired hero background, and every background must
follow the visitor accent switcher (orange / blue / purple / green) live, the way the rest
of the site already does.

## The core constraint (locked)

The accent switcher sets `--color-primary` and `--color-primary-strong` as inline custom
properties on `<html>` and persists the choice in localStorage
(`assets/js/site-chrome.js`). Anything painted with `var(--color-primary)` re-themes
instantly and for free.

Therefore the references are **recreated as accent-driven CSS + inline SVG**, not used as
images. A static `.webp/.jpg/.avif` is locked to its exported colour and cannot follow the
switcher. A `background-image: url(data:…svg…)` also cannot: CSS custom properties do not
cross into a data-URI'd image. **Inline SVG in the markup does inherit CSS custom
properties**, so `stroke="var(--color-primary)"` / `fill="var(--color-primary)"` inside the
pattern re-themes live. That is the technique for all four backgrounds.

## Decisions (locked)

- Technique: CSS + inline SVG, accent-bound. No canvas, no images, no new JS, no plugins,
  no new dependencies.
- Motion: none. Match the home hero's current static decorative layers. Reduced-motion-safe
  by construction (nothing animates).
- Scope: all four heroes get the new system (the three named pages plus a home refresh).
  Home keeps its code-card; only its background changes.
- Distinct per page: four variants, one shared engine.
- The reference images are inspiration only; they are not added to the repo.

## Architecture: one engine, four skins

Each hero section carries a single decorative wrapper:

```html
<div class="hero-bg hero-bg--<variant>" aria-hidden="true">
  <svg class="hero-bg__svg" viewBox="0 0 1200 600" preserveAspectRatio="..." focusable="false">
    ...per-variant geometry, painted with var(--color-primary)...
  </svg>
</div>
```

- `aria-hidden="true"` and `focusable="false"`: purely decorative, invisible to assistive
  tech and keyboard order.
- Shared layers (dark base, dot-grid, perspective-fade mask, accent glow) are defined once
  in `assets/css/theme.css` under a `.hero-bg` block, with per-variant overrides under
  `.hero-bg--traces`, `.hero-bg--ribbons`, `.hero-bg--cloud`, `.hero-bg--signature`.
- Per-variant SVG path geometry lives in the pattern files (geometry differs per page, so
  it belongs with the markup, not in CSS).

This replaces the two empty `.hero__mesh` + `.hero__grid` divs that the four sections use
today. The grid and mesh effects are folded into `.hero-bg` so the visual base is preserved
where wanted (home, work) and dropped where not (about).

### CSS layer model (in `theme.css`, shared)

`.hero-bg` is `position: absolute; inset: 0; z-index: 0; pointer-events: none; overflow:
hidden;` on a `position: relative` section. Layers, back to front:

1. Dark base: the section's existing `--color-dark` background (unchanged).
2. Accent glow: a `radial-gradient` in `var(--color-primary)` (and a secondary
   `var(--color-primary-strong)` blob for the signature variant), via `.hero-bg::before` /
   `::after`. This is the live-re-theming light source.
3. Dot/line grid: `linear-gradient` hairlines via a pseudo-element where the variant wants
   it (work, home), keyed to the existing `--hairline-dark`.
4. SVG geometry: `.hero-bg__svg`, `position: absolute; inset: 0; width/height: 100%`,
   painted with `var(--color-primary)`.
5. Perspective fade: a `mask-image` / overlay `radial-gradient` to the dark base so the
   texture dissolves toward the edges instead of hard-cropping.

The hero content (`.hero__inner`, `.section-head`, etc.) stays at `z-index: 1` above the
background, exactly as today.

## The four variants

### Work: `--traces`
Routed circuit traces (orthogonal "Manhattan" runs with rounded corners) over the dot-grid,
a few brighter accent "live" runs, small accent node dots at junctions, fading in
perspective toward the lower-left into the dark. Echoes egworkhero without being locked to
blue. Grid + glow layers on.

### Playground: `--ribbons`
Several layered flowing wave strokes (cubic beziers) in accent tints, each backed by a soft
blurred copy (SVG `feGaussianBlur`) for glow, with a sparse scatter of light points. The
most energetic of the set, fitting the page. Glow layer on, grid off.

### About: `--cloud`
A CSS `radial-gradient` glow in `var(--color-primary)`, offset (not centered, no ring),
behind an SVG `feTurbulence` fractal-noise cloud layer. The turbulence uses a **fixed seed**
so the dense cloud shapes are constant and stay dark; an `feColorMatrix` maps the noise to a
near-black fill with variable alpha, so the accent glow only shows through the thin patches.
Switching accent changes only the glow; the cloud mass never moves. Grid off; this is the
calmest, most organic header. (`feTurbulence` is decorative and static; its cost is paid
once at paint. Verify paint cost is acceptable; if not, reduce `numOctaves` or rasteration
area.)

### Home: `--signature`
A refined version of today's treatment: the two accent mesh blobs (primary + strong) plus a
faint dot-grid plus a couple of quiet traces, tying the family together. The code-card and
all hero copy are unchanged.

## Files touched

- `assets/css/theme.css`: add the `.hero-bg` block and four `--variant` modifiers. The
  existing `.hero__mesh` / `.hero__grid` rules are superseded; remove them only after all
  four patterns no longer reference those classes (or keep them if any other template still
  uses them; verify with a grep first).
- `patterns/home-hero.php`: swap mesh/grid divs for `hero-bg hero-bg--signature` + SVG.
- `patterns/projects-archive-header.php`: `hero-bg hero-bg--traces` + SVG.
- `patterns/playground.php`: `hero-bg hero-bg--ribbons` + SVG (header section only).
- `patterns/about.php`: `hero-bg hero-bg--cloud` + SVG (header section only).

No JS, theme.json, template, or pattern-registration changes. No new assets.

## Accessibility and performance

- All decorative wrappers `aria-hidden="true"`, SVG `focusable="false"`; no change to
  heading order or focus order.
- Contrast: headline and lede already sit on `--color-dark` at `z-index: 1`; the texture
  stays low-opacity and edge-faded so text contrast is unaffected. Re-check the lede
  (muted) over the brightest part of each glow at every accent.
- No animation, so `prefers-reduced-motion` needs no special handling, but the build should
  not introduce any transitions or keyframes on these layers.
- Lightweight: a handful of SVG paths and one filter per page; no runtime JS, no network
  requests, no layout shift (backgrounds are absolutely positioned behind content).

## Acceptance criteria

1. Each of the four heroes shows its distinct texture as described.
2. Clicking each accent dot (orange/blue/purple/green) re-themes all four hero backgrounds
   live, with no reload, and the choice persists across pages and reloads (existing switcher
   behaviour, now reflected in the backgrounds).
3. About: the dense cloud stays dark and identical across accents; only the glow changes
   colour.
4. No animation on any hero background.
5. Headline and lede remain legible (WCAG AA) on every page at every accent.
6. Zero new plugins, zero new JS, zero new assets, zero new dependencies; active-plugin
   count stays zero.
7. No banned characters introduced (em/en dash, double hyphen, emoji) and no eyebrows or
   decorative numerals added.

## Out of scope

- Single project pages, contact, and other sections keep their current treatment.
- No new accent colours; the four existing accents are the full set.
- The reference images are not committed to the repo.
