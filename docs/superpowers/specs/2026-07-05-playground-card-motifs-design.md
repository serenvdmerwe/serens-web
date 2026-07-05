# Playground card motifs (direction B) — design

Date: 2026-07-05
Status: approved for implementation planning

## Problem

The playground page cards use a flat per-hue gradient blob plus a label pill
(`.play-card__viz` in `patterns/playground.php`, styled in
`assets/css/theme.css`). It is tasteful and loads instantly, but a colour blob
tells a visitor nothing about what each experiment does. The playground exists
to show off interactive work, so this is the one page that undersells itself.

## Goal

Replace the blobs with bespoke, hand-built SVG motifs that hint at what each
demo does, adding meaning and life without images or a performance cost.

## Principles and constraints

- Pure inline SVG and CSS. No images, no screenshots (they add weight, go stale
  on live-data demos), no JavaScript, no plugins. This is the site's whole
  zero-asset, hand-built pitch, so the previews should embody it.
- Keep each card's existing hue. The spread of colours across the shelf is the
  "reads as range" identity and stays.
- Two-level visual language: a shared category "family" foundation plus a
  per-item motif on top. Cohesion and distinction at once.
- Motion is enhancement only. Motifs are static at rest; animation runs on
  `:hover` and `:focus-within`; `prefers-reduced-motion` disables all of it.
  Result: zero idle cost even with many cards.
- Follow the project convention: markup (the SVG) lives in the pattern; all
  positioning and animation CSS lives in `theme.css`, found by class.
- Accessibility: motifs are decorative (`aria-hidden="true"`); labels and links
  are unchanged; keyboard focus gets the same motion as hover.

## Scope

- **Phase 1 (this spec):** the 8 cards in the "Interactive maps and data
  stories" section.
- **Phase 2 (separate spec, only if Phase 1 lands):** games (2), tools (2),
  apps (1), explainers (2), each with a gameplay- or tool-hint motif.

The demos themselves are not touched. Only the card preview markup and CSS.

## Visual system

### Shared map grid (the family language)

A glowing lat/long graticule that every map card carries:

- 5 meridians (gently bowed) and 4 parallels, on a `0 0 320 200` viewBox.
- Drawn twice for a neon-line look with no raster: a blurred copy
  (`feGaussianBlur`) for the glow, and a crisp thin copy over it for definition.
- Stroke colour is themed per card via CSS `color` + `currentColor` so the grid
  reads against each hue (warm on ember/gold, cool on blue/teal/green).
- Sits on the hue gradient, behind the item motif and the label pill.
- Hover/focus: the glow layer "breathes" (opacity oscillation, ~3s). Static for
  reduced-motion.

Single-sourced so the geometry is not copy-pasted 8 times: one hidden `<svg>`
at the top of the maps section holds `<symbol id="play-grid">` and the glow
`<filter>`. Each card's motif SVG pulls it in with `<use href="#play-grid"/>`.
The grid strokes use `currentColor`, so a per-card class tints it. If a target
browser mishandles cross-`<svg>` `<use>` or filter references, fall back to a
small PHP helper that emits the grid group inline per card.

### Per-map motifs (over the grid)

| Card (viz modifier) | Motif | Hover motion |
|---|---|---|
| Risk Explorer (default) | two location pins on the grid | subtle pin pulse |
| Hurricane Tracks (blue) | 2-3 curved storm-track paths to an eye | slow track drift |
| Submarine Cables (cable) | cable arcs fanning from a coast to sea | pulse travels a cable |
| Live Earthquake (ember) | magnitude-sized dots, 3 outlined | **none** (stays calm, matches the shipped ring removal) |
| Planes Overhead (radar) | concentric rings + sweep + blips | sweep rotates |
| ISS Tracker (night) | orbit ellipse, station dot, ground-track arc | station travels the orbit |
| Golden Hour (dawn) | sun on a day/night terminator + horizon | glow breathes |
| The Wind (storm) | drifting particle streaks | streaks drift |

The earthquake card deliberately has no hover motion; the contrast between still
and moving cards is part of the composition and keeps faith with the recent
"calm the earthquake map" change.

## Files

- `patterns/playground.php`: add one hidden defs `<svg>` at the top of the maps
  section (`#play-grid` symbol + glow filter); inside each of the 8 map cards'
  `.play-card__viz`, add `<svg class="play-motif play-motif--<hue>"
  aria-hidden="true" viewBox="0 0 320 200"><use href="#play-grid"/> ...motif...
  </svg>` before the existing `<span>` label.
- `assets/css/theme.css`: add `.play-motif` (absolute fill, `pointer-events:
  none`, behind the pill via source order); per-hue tint classes; motif
  animation classes; and the `:hover`/`:focus-within` gating with a
  `prefers-reduced-motion` reset.

No new template, no new pattern file. A PHP helper include is added only if the
`<use>` fallback is needed.

## Accessibility and performance

- Motifs `aria-hidden`, purely decorative; the label pill and CTA are unchanged.
- Animations run only on `:hover`/`:focus-within`, so idle CPU is zero; they use
  compositor-friendly `transform`/`opacity` only.
- `@media (prefers-reduced-motion: reduce)` disables every motif animation.
- `:focus-within` gives keyboard users the same motion as pointer users.

## Verification

- Visual review at `https://serensweb.test/playground` for each of the 8 map
  cards: motif renders behind the label; hover and keyboard focus trigger the
  intended motion; the grid `<use>` resolves (grid visible on every map card);
  reduced-motion (emulated) shows a static motif.
- No browser console errors; valid SVG/HTML; `php -l` clean on the pattern.
- Confirm non-map sections are visually unchanged.

## Out of scope (YAGNI)

- Screenshots, iframes, and any JavaScript.
- Phase 2 (games/tools/apps/explainers) motifs.
- Any change to the demos, copy, or card layout beyond the viz block.

## Risks

- Cross-`<svg>` `<use>` and `filter` id references: fine in evergreen browsers;
  documented inline fallback (PHP helper) if not.
- Eight motifs is craft-heavy and can tip into busy. Mitigation: the shared grid
  plus a restrained, consistent motif vocabulary; motion off at rest.
