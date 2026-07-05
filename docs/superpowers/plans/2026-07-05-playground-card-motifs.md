# Playground Card Motifs Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace the flat gradient blobs on the 8 playground map cards with bespoke inline-SVG motifs sharing a glowing lat/long grid, motion gated to hover/focus.

**Architecture:** One hidden `<symbol id="play-grid">` plus a glow `<filter>` are added once at the top of the maps section in `patterns/playground.php`. Each map card's `.play-card__viz` gains an inline `<svg class="play-motif play-motif--<hue>">` that pulls the grid in with `<use href="#play-grid"/>` and adds a small per-map motif. All positioning, per-hue grid tint, and animation live in `assets/css/theme.css`, found by class. Grid strokes use `currentColor` so a per-hue class tints them.

**Tech Stack:** WordPress FSE block pattern (PHP-emitted HTML), inline SVG, CSS (`transform`/`opacity` animations, `@media (prefers-reduced-motion)`).

**Verification model:** No unit tests. Each task verifies with `curl` render-grep against `https://serensweb.test`, `php -l` on the pattern, and a final browser pass. The stack must be up (`docker compose up -d`).

---

## File Structure

- `wordpress/wp-content/themes/serensweb-child/patterns/playground.php` — add the hidden grid defs `<svg>` once in the maps section; add one motif `<svg>` inside each of the 8 map cards' `.play-card__viz`, before the existing `<span>` label.
- `wordpress/wp-content/themes/serensweb-child/assets/css/theme.css` — add the `.play-motif` block: base positioning, per-hue grid tint, motif animation classes, `:hover`/`:focus-within` gating, `prefers-reduced-motion` reset.

No new files. PHP-helper fallback for `<use>` is only introduced if Task 2 shows cross-`<svg>` `<use>` fails in the browser.

---

## Task 1: CSS foundation + shared grid defs

**Files:**
- Modify: `assets/css/theme.css` (after the `.play-card__viz--inkwell` rule, ~line 386)
- Modify: `patterns/playground.php` (top of the `#maps` section, after `<div class="play-grid">` opens... see step)

- [ ] **Step 1: Add the `.play-motif` CSS block** after the last `.play-card__viz--*` rule in `theme.css`:

```css
/* Playground card motifs: a shared glowing grid (via <use>) plus a per-card
   motif. Motion is gated to hover/focus and off for reduced-motion. */
.play-motif { position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; }
.play-card__viz span { position: relative; z-index: 2; }
.play-grid-glow { color: rgba(255,255,255,0.5); }   /* glow layer, tinted per hue */
.play-grid-line { color: rgba(255,255,255,0.16); }  /* crisp layer, tinted per hue */
/* Per-hue grid tint (glow, then line) */
.play-motif--ember  .play-grid-glow { color:#ffd9bf; } .play-motif--ember  .play-grid-line { color:rgba(255,225,205,0.18); }
.play-motif--radar  .play-grid-glow { color:#9af0bf; } .play-motif--radar  .play-grid-line { color:rgba(180,255,210,0.16); }
.play-motif--night  .play-grid-glow { color:#a9c8ff; } .play-motif--night  .play-grid-line { color:rgba(180,205,255,0.16); }
.play-motif--dawn   .play-grid-glow { color:#ffe0a8; } .play-motif--dawn   .play-grid-line { color:rgba(255,224,138,0.18); }
.play-motif--storm  .play-grid-glow { color:#b8efe0; } .play-motif--storm  .play-grid-line { color:rgba(159,232,216,0.16); }
.play-motif--blue   .play-grid-glow { color:#a9d4ff; } .play-motif--blue   .play-grid-line { color:rgba(160,205,255,0.16); }
.play-motif--cable  .play-grid-glow { color:#a6e6ff; } .play-motif--cable  .play-grid-line { color:rgba(150,225,255,0.16); }
.play-motif--primary .play-grid-glow { color:#ffd0a8; } .play-motif--primary .play-grid-line { color:rgba(255,215,180,0.18); }
/* Motion, hover/focus only */
@keyframes play-breathe { 50% { opacity: 0.85; } }
@keyframes play-spin { to { transform: rotate(360deg); } }
@keyframes play-orbit { to { offset-distance: 100%; } }
@keyframes play-drift { to { transform: translateX(22px); } }
.play-motif .anim { animation-play-state: paused; }
.play-card:hover .play-motif .anim,
.play-card:focus-within .play-motif .anim { animation-play-state: running; }
@media (prefers-reduced-motion: reduce) { .play-motif .anim { animation: none !important; } }
```

- [ ] **Step 2: Add the hidden grid defs** as the first child inside the `#maps` `.play-grid` container in `playground.php`, immediately after `<div class="play-grid">`:

```html
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
  <defs>
    <filter id="play-glow" x="-20%" y="-20%" width="140%" height="140%"><feGaussianBlur stdDeviation="1.8"/></filter>
    <g id="play-grid-lines" fill="none" stroke="currentColor" stroke-width="1">
      <path d="M53 6 Q44 100 53 194"/><path d="M107 6 Q101 100 107 194"/><path d="M160 6 L160 194"/>
      <path d="M213 6 Q219 100 213 194"/><path d="M267 6 Q276 100 267 194"/>
      <path d="M6 48 Q160 42 314 48"/><path d="M6 84 Q160 79 314 84"/><path d="M6 120 Q160 115 314 120"/><path d="M6 156 Q160 151 314 156"/>
    </g>
    <symbol id="play-grid" viewBox="0 0 320 200">
      <use href="#play-grid-lines" class="play-grid-glow anim" style="color:inherit" filter="url(#play-glow)" opacity="0.5" stroke="currentColor"/>
      <use href="#play-grid-lines" class="play-grid-line" stroke="currentColor"/>
    </symbol>
  </defs>
</svg>
```

Note: the `play-grid-glow` breathe animation is attached in the motif SVG (Task 2), not here; the `anim` class on the glow `<use>` is the hook. Set its animation in CSS by extending the block: add `.play-motif .play-grid-glow.anim { animation: play-breathe 3s ease-in-out infinite; }` to the Step 1 block.

- [ ] **Step 3: Verify the pattern parses and the defs render**

Run: `docker compose exec -T serens-web-php-service php -l wp-content/themes/serensweb-child/patterns/playground.php`
Expected: `No syntax errors detected`

Run: `curl -sk https://serensweb.test/playground | grep -c 'id="play-grid"'`
Expected: `1`

- [ ] **Step 4: Commit**

```bash
git add wordpress/wp-content/themes/serensweb-child/assets/css/theme.css wordpress/wp-content/themes/serensweb-child/patterns/playground.php
git commit -m "feat(playground): shared card-motif grid and CSS foundation"
```

---

## Task 2: Earthquake pilot motif (validates the `<use>` technique)

**Files:**
- Modify: `patterns/playground.php` (the Live Earthquake Map card's `.play-card__viz play-card__viz--ember`)

- [ ] **Step 1: Add the motif SVG** inside the earthquake card's viz `<div>`, before its `<span>Live data</span>`:

```html
<svg class="play-motif play-motif--ember" viewBox="0 0 320 200" aria-hidden="true" focusable="false">
  <use href="#play-grid"/>
  <g>
    <circle cx="92" cy="126" r="4" fill="#ffc857"/><circle cx="122" cy="108" r="3" fill="#ff7043"/>
    <circle cx="176" cy="90" r="3.4" fill="#ffc857"/><circle cx="212" cy="120" r="4.6" fill="#9b8cff"/>
    <circle cx="246" cy="82" r="3" fill="#ff7043"/><circle cx="72" cy="150" r="2.6" fill="#9b8cff"/>
    <circle cx="134" cy="70" r="9" fill="#ff7043" stroke="#ffe0cf" stroke-width="1.4"/>
    <circle cx="230" cy="150" r="8" fill="#ffc857" stroke="#ffe0cf" stroke-width="1.4"/>
    <circle cx="104" cy="158" r="7" fill="#ff7043" stroke="#ffe0cf" stroke-width="1.4"/>
  </g>
</svg>
```

- [ ] **Step 2: Verify it renders**

Run: `curl -sk https://serensweb.test/playground | grep -c 'play-motif play-motif--ember'`
Expected: `1`

- [ ] **Step 3: Browser check (manual gate)** — open `https://serensweb.test/playground`, confirm on the earthquake card: the glowing grid shows (proves `<use href="#play-grid">` resolves), the quake dots sit on top, the "Live data" pill is still readable above the motif, and the card has NO motion on hover (earthquake stays calm by design).

If the grid does NOT show (cross-`<svg>` `<use>` unsupported), switch to the fallback: add a PHP helper `serensweb_play_grid()` in a new `includes/playground-motifs.php` that returns the two grid `<g>` groups inline, and call it in place of `<use href="#play-grid"/>` in every motif. Re-verify before continuing.

- [ ] **Step 4: Commit**

```bash
git add wordpress/wp-content/themes/serensweb-child/patterns/playground.php
git commit -m "feat(playground): earthquake card motif on the shared grid"
```

---

## Tasks 3-9: Remaining 7 map motifs (one task each)

Each task follows the same shape: add the motif `<svg class="play-motif play-motif--<hue>">` with `<use href="#play-grid"/>` before the card's `<span>`, add any motif-specific animation CSS to the Step 1 block in `theme.css`, then verify with `curl ... | grep -c 'play-motif--<hue>'` (expect `1`), a browser hover check, and a commit. Motion classes carry the `anim` class so hover/focus gating and reduced-motion apply automatically.

Concrete motif element list per card (author the SVG to these; keep the `0 0 320 200` viewBox):

- [ ] **Task 3 — Planes Overhead (`--radar`)**: three concentric rings centred (160,100) r=30/58/86 stroke `rgba(180,255,210,0.22)`, crosshair lines, a `<g class="sweep anim">` (a faded wedge `path` filled with a left-to-right green linear-gradient + a bright radius line) with `animation: play-spin 4.2s linear infinite; transform-origin:160px 100px;`, and 3 blip dots `#8affc0`. CSS: `.play-motif--radar .sweep { transform-origin:160px 100px; animation: play-spin 4.2s linear infinite; }` (with `.anim` pausing at rest).

- [ ] **Task 4 — ISS Tracker (`--night`)**: an orbit ellipse (cx160 cy100 rx110 ry46) stroke `rgba(170,200,255,0.35)` dashed; a faint ground-track arc; a station dot that travels the ellipse via CSS `offset-path: path('M50 100 A110 46 0 1 1 270 100 A110 46 0 1 1 50 100'); animation: play-orbit 6s linear infinite;` on a `<circle class="iss anim">`. CSS adds the `.play-motif--night .iss` offset-path + `play-orbit` animation.

- [ ] **Task 5 — Golden Hour (`--dawn`)**: two faint white land humps (opacity 0.16), a `<circle class="sun">` r=20 `#ffe08a`, a `<circle class="glow anim">` r=34 `#ffd98a` opacity 0.28 with `animation: play-breathe 3.4s ease-in-out infinite;`, a horizon line `rgba(255,224,138,0.45)`, and a night wedge `path` `#0c0f1e` opacity 0.34 on the right. CSS adds `.play-motif--dawn .glow { animation: play-breathe 3.4s ease-in-out infinite; }`.

- [ ] **Task 6 — The Wind (`--storm`)**: a `<g class="streaks anim">` of ~7 short curved `path` strokes (`#9fe8d8`/`#7dd3c0`, stroke-width 1.4, stroke-linecap round, varying opacity) spread across the field, with `animation: play-drift 3.5s ease-in-out infinite alternate;`. CSS adds `.play-motif--storm .streaks { animation: play-drift 3.5s ease-in-out infinite alternate; }`.

- [ ] **Task 7 — Hurricane Tracks (`--blue`)**: 2 curved storm-track `path` strokes (`#7cc7ff`, dashed, stroke-width 1.6) sweeping toward a small spiral "eye" near (210,90); a `<g class="eye anim">` (two tight arcs) with `animation: play-spin 8s linear infinite; transform-origin:210px 90px;`. CSS adds `.play-motif--blue .eye { transform-origin:210px 90px; animation: play-spin 8s linear infinite; }`.

- [ ] **Task 8 — Submarine Cables (`--cable`)**: a coast anchor point near (70,150); 4 cable `path` arcs fanning out to the right edge (`#8ee3ff`, stroke-width 1.4, varying opacity); a `<circle class="pulse anim">` r=3 `#d8f6ff` riding one cable via `offset-path`/`play-orbit` (6s). CSS adds the `.play-motif--cable .pulse` offset-path + animation.

- [ ] **Task 9 — Risk Explorer (`--primary`)**: a faint 4x3 block grid (small `rect`s, low white opacity) as "counties"; two location-pin `path`s (teardrop + inner dot) in `#ffd0a8` and `#7cc7ff` at (120,96) and (205,120); a `<g class="ping anim">` ring on one pin with `animation: play-breathe 2.6s ease-in-out infinite;`. This card currently uses the default `.play-card__viz` (no modifier); add `play-motif--primary`. CSS adds `.play-motif--primary .ping { animation: play-breathe 2.6s ease-in-out infinite; }`.

For each: `curl -sk https://serensweb.test/playground | grep -c 'play-motif--<hue>'` → `1`, browser hover check, then commit `feat(playground): <card> motif`.

---

## Task 10: Full verification and PR

- [ ] **Step 1: All 8 motifs present**

Run: `curl -sk https://serensweb.test/playground | grep -oc 'class="play-motif'`
Expected: `8`

- [ ] **Step 2: Pattern lint**

Run: `docker compose exec -T serens-web-php-service php -l wp-content/themes/serensweb-child/patterns/playground.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Writing-rules + console check** — grep the changed files for banned dashes/emoji (expect none); open the page and confirm no console errors, motifs sit behind labels, hover/keyboard-focus animate, non-map sections unchanged, and reduced-motion (emulated) freezes all motion.

- [ ] **Step 4: Push and open PR**

```bash
git push -u origin feat/playground-card-motifs
gh pr create --title "feat(playground): bespoke map-card motifs on a shared grid" --body "<summary of the 8 motifs, motion model, a11y, links the spec>"
```

---

## Self-Review notes

- Spec coverage: shared grid family (Task 1), 8 per-map motifs (Tasks 2-9), hover/focus gating + reduced-motion (Task 1 CSS, applied throughout), earthquake stays static (Task 2), accessibility aria-hidden (every motif SVG), verification (Task 10). All spec sections mapped.
- The `<use>` risk has an explicit fallback path in Task 2.
- Phase 2 (games/tools/apps/explainers) is intentionally excluded.
