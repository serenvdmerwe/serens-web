# Design nitpicks and responsive polish

Date: 2026-06-30
Status: design approved, pending spec review
Scope: a polish pass over SerensWeb, focused on scrollbars, section spacing,
responsive fit (phone, tablet, laptop, desktop), and small bells-and-whistles
fixes. No new sections or features.

## Context

SerensWeb is container-query driven: `<body>` is the query container
(`container-type: inline-size`, `container-name: site`), so layout flips at
`@container site (min-width: 700px / 860px / 1040px)` rather than viewport media
queries. Spacing and type are fluid via `clamp(min, Ncqi, max)`, with the rhythm
tokens living in `assets/css/tokens.css` (`--section-pad`, `--gutter`, `--maxw`).

The prototype CSS in `assets/css/theme.css` is preserved verbatim; this pass edits
it in place (it is the live stylesheet, not a generated artefact). Token changes
go in `tokens.css`. Markup changes for the codecard go in
`patterns/home-hero.php`.

Verification is code-level plus Seren's own browser: per the project's
verification preference, no headless browser is launched. Seren confirms the
visual and responsive items at phone (about 390px), tablet (768 to 834px),
laptop (1280 to 1440px), and desktop (1920px and up) in a browser that already
trusts the Caddy local CA at https://serensweb.test.

## Goals

1. A calm, accent-aware custom scrollbar that re-themes with the accent switcher.
2. No content shift when navigating between a short page and a long page.
3. Consistent anchor-jump offset under the sticky header.
4. Tablet (768px) layout that does not feel cramped or under-built.
5. Touch targets that meet the WCAG 2.5.8 minimum.
6. Honest decorative copy in the hero codecard.

## Non-goals

- No new sections, pages, or features.
- No translation of the prototype CSS into block styles.
- No change to the accent switcher behaviour or the four accent colours.
- No redesign of any section; spacing and breakpoints are tuned, not replaced.

## Work items

### Batch 1: scrollbar and scroll behaviour (global, low risk)

**1.1 Accent-aware custom scrollbar.**
Add a global scrollbar treatment that reads `--color-primary`, so it re-themes
live with the accent switcher. Calm by default, accent on hover.

- Firefox: `html { scrollbar-width: thin; scrollbar-color: <thumb> <track>; }`
  where thumb is `color-mix(in oklab, var(--color-primary) 55%, var(--color-dark-2))`
  and track is `var(--color-dark)`. Firefox has no scrollbar hover state, so the
  resting thumb is the muted accent mix.
- WebKit (Chrome, Edge, Safari): `::-webkit-scrollbar { width: 12px; height: 12px; }`,
  track `var(--color-dark)`, thumb the same muted accent mix with
  `border-radius: 999px` and a `3px solid var(--color-dark)` border to inset it,
  and `::-webkit-scrollbar-thumb:hover { background: var(--color-primary); }`.
- The WebKit pseudo-elements are global, so inner scrollers (the lightbox panel,
  the message textarea) inherit the same look with no extra rules. Firefox
  inherits `scrollbar-color` from `html` down the tree.
- Mobile keeps native overlay scrollbars; this treatment only shows where the OS
  draws a classic scrollbar (most desktop and laptop setups).

**1.2 Stable scrollbar gutter.**
`html { scrollbar-gutter: stable; }` reserves the scrollbar track so a short page
(`/contact`) and a long page (`/home`) centre their content at the same width.
No effect on overlay-scrollbar systems, which already do not shift.

**1.3 Anchor offset token.**
Add `--header-h: 72px;` to `tokens.css`. Set `html { scroll-padding-top: calc(var(--header-h) + 12px); }`
and remove the per-element `.section, .footer { scroll-margin-top: 84px; }` rule.
This makes every anchor target clear the sticky header consistently, including the
shrunk 64px scrolled state, from one source of truth.

### Batch 2: responsive fit and section rhythm

**2.1 Process steps grid.**
Change the steps grid so it is 1 column on phone, 2 columns in the 700 to 860
band, and 4 columns at 860 and up. Concretely: keep `.steps { grid-template-columns: 1fr; }`,
set `repeat(2, 1fr)` at `@container site (min-width: 700px)`, and move the
`repeat(4, 1fr)` to `@container site (min-width: 860px)` (currently it sits in the
700px block). This removes the roughly 160px-wide cramped columns on a 768px iPad.

**2.2 Reveal full nav at the tablet width.**
Move the nav reveal from 860px to 820px so iPad portrait (768 to 834) shows the
desktop nav and the header CTA instead of the hamburger. Move these four rules
from the 860px block to a new `@container site (min-width: 820px)` block:
`.nav { display: flex; }`, `.header__cta { display: inline-flex; }`,
`.burger { display: none; }`, `.drawer { display: none; }`. The remaining 860px
layout rules (about, contact, footer, hero grids) stay at 860.

**2.3 Section padding rhythm.**
Seren confirms `--section-pad` (`clamp(64px, 9cqi, 132px)`) and
`--gutter` (`clamp(20px, 5cqi, 56px)`) at the four widths in their browser. If
mobile reads tight or desktop reads loose, tune only the clamp ends (the min and
max), not the structure. Default position: leave as-is unless the browser check
flags it. This item is a verify-then-maybe-tune, not a guaranteed edit.

**2.4 Horizontal overflow sweep at 360 to 390px.**
Seren checks the header bar at narrow widths (brand wordmark, four accent dots,
and the burger share one row via `justify-content: space-between`). Reasoning
shows it fits at 360px with slack and is borderline at 320px (iPhone SE). If it
crowds: tighten the `.accent-switch--header` gaps and side margins first; only if
still tight, reduce the brand to the mark glyph below about 360px. Also confirm no
element produces a horizontal scrollbar at these widths. Fix only what the check
finds.

### Batch 3: tap targets and micro-polish

**3.1 Header accent-dot hit area.**
The header accent dots render at 18x18px, below the WCAG 2.5.8 minimum of 24px.
Keep the 18px visual dot but enlarge the interactive area to at least 24x24px
using padding or a pseudo-element overlay, without changing the visible size or
the row spacing. The dots stay a tight cluster visually but become reliably
tappable.

**3.2 Footer and small link targets.**
`.footer__col a` uses `padding: 6px 0` (about 18px tall). Raise small text links to
at least 24px effective touch height where it does not disturb the layout, the
footer columns first.

**3.3 Focus ring on light sections.**
The `:focus-visible` ring is accent-coloured. Seren confirms it stays visible on
the light sections (orange ring on a light background, for example the About and
Selected Work areas). If it washes out, add a thin contrasting halo (for example
a second outline or a small `outline-offset` plus a dark inset), accent-aware.
Verify-then-maybe-tune.

### Codecard copy

**4.1 Honest decorative codecard.**
The hero codecard (in `patterns/home-hero.php`) currently reads
`framework: "headless"` and `perf: "<1s LCP"`, which violates the project rules
against reintroducing "headless" and against fabricated metrics. Replace the
decorative lines with honest, on-brand content drawn from the real services
(WordPress and FSE, AI-assisted workflow, installable PWA). Proposed lines, which
Seren can reword:

```
// stack.config.ts
export const build = {
  cms:  "wordpress / fse",
  ai:   "assisted-workflow",
  app:  "installable pwa",
  ship: "independent",
}
// status: shipped
```

Keep each line under about 26 characters so the `white-space: pre` code block does
not get clipped inside the card on a 360px phone. The block stays `aria-hidden`,
so it is decoration, not a screen-reader claim.

## Risks and constraints

- `scrollbar-gutter: stable` reserves 12px on the side that holds the scrollbar.
  Confirm the sticky header and full-bleed hero backgrounds still look correct
  with that reserved gutter (they are full width inside `.header` and
  `.page-header`, so the inset is uniform).
- Custom scrollbars are cosmetic only; native keyboard and wheel scrolling are
  untouched. The thumb mix must stay legible on the dark track at every accent.
- Container-query breakpoints are shared across sections; moving the nav reveal to
  820 must not drag the section grid rules with it. Only the four header rules
  move.

## Verification plan

- `php -l` on `patterns/home-hero.php` after the codecard edit.
- Server-side render check via curl through Caddy on `/` and `/contact` to confirm
  no fatal output and the new markup is present.
- Seren confirms in their own browser at 390, 768, 1280, and 1920: scrollbar look
  and accent re-theming, no content shift between `/home` and `/contact`, anchor
  jumps clearing the header, the 2-up steps and tablet nav, accent-dot
  tappability, and the focus ring on light sections.

## Out of scope, noted

The codecard copy is included here by decision. No other copy or content changes
are in this pass.
