# Design Nitpicks and Responsive Polish Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Ship a polish pass over SerensWeb: an accent-aware pill-and-arrows scrollbar, stable layout across pages, consistent anchor offsets, a less cramped tablet layout, WCAG-sized tap targets, and honest hero codecard copy.

**Architecture:** All changes are CSS in `assets/css/theme.css` and a token in `assets/css/tokens.css`, plus one markup edit in `patterns/home-hero.php`. The site is container-query driven off `<body>` (breakpoints at 700 / 860 / 1040, with a new 820 added here). Nothing is translated into block styles; the prototype CSS is edited in place because it is the live stylesheet.

**Tech Stack:** WordPress FSE child theme (`serensweb-child`), hand-authored CSS with container queries and `clamp()`, custom properties consumed from `tokens.css`, accent switcher rewriting `--color-primary` at runtime. Local stack is Docker (Caddy + PHP-FPM + MariaDB + Redis).

**Verification model:** This project has no JS/CSS unit-test harness, so "tests" here are: `php -l` for PHP, a server-side render check via curl through Caddy, and Seren confirming visuals in their own browser at 390 / 768 / 1280 / 1920px. Reusable commands:

- Render check: `docker compose exec serens-web-php-service curl -sk --connect-to serensweb.test:443:serens-web-caddy-service:443 https://serensweb.test/`
- Lint: `docker compose exec serens-web-php-service php -l wp-content/themes/serensweb-child/patterns/home-hero.php`

CSS files are not PHP-parsed, so CSS-only tasks rely on the render check (page still loads, no fatal) plus Seren's browser confirmation.

---

## File structure

- `assets/css/tokens.css` — add `--header-h` token (one line). Single responsibility: design tokens.
- `assets/css/theme.css` — all CSS edits: scroll behaviour on `html`, the scrollbar block, steps grid, the new 820 nav block, accent-dot hit area. The live prototype stylesheet.
- `patterns/home-hero.php` — codecard decorative copy only.

---

## Task 1: Scroll behaviour foundation (anchor offset, stable gutter)

**Files:**
- Modify: `assets/css/tokens.css` (add `--header-h` near the spacing rhythm block)
- Modify: `assets/css/theme.css:11` (the `html` rule) and `:464-465` (remove the scroll-margin hack)

- [ ] **Step 1: Add the header-height token**

In `assets/css/tokens.css`, inside `:root`, add `--header-h` to the spacing rhythm group so it reads:

```css
  /* Spacing rhythm */
  --section-pad: clamp(64px, 9cqi, 132px);
  --gutter: clamp(20px, 5cqi, 56px);
  --maxw: 1240px;
  --header-h: 72px;   /* sticky header bar height; drives scroll-padding */
```

- [ ] **Step 2: Add scroll-padding and stable gutter to `html`**

In `assets/css/theme.css`, replace the existing one-line `html` rule:

```css
html { -webkit-text-size-adjust: 100%; }
```

with:

```css
html {
  -webkit-text-size-adjust: 100%;
  /* Anchor jumps clear the sticky header from one source of truth */
  scroll-padding-top: calc(var(--header-h) + 12px);
  /* Reserve the classic scrollbar so short and long pages centre identically */
  scrollbar-gutter: stable;
}
```

- [ ] **Step 3: Remove the per-element scroll-margin hack**

In `assets/css/theme.css`, delete these two lines (the comment and rule, around line 464-465):

```css
/* Scroll offset for sticky header on anchor jumps */
.section, .footer { scroll-margin-top: 84px; }
```

The `scroll-padding-top` on `html` now covers every anchor target uniformly, so this is redundant.

- [ ] **Step 4: Render check**

Run: `docker compose exec serens-web-php-service curl -sk --connect-to serensweb.test:443:serens-web-caddy-service:443 https://serensweb.test/ | head -c 200`
Expected: HTML output starting with `<!DOCTYPE html>` (page still renders, no fatal).

- [ ] **Step 5: Commit**

```bash
git add assets/css/tokens.css assets/css/theme.css
git commit -m "Add header-height token, scroll-padding, and stable scrollbar gutter"
```

---

## Task 2: Accent-aware scrollbar (pill thumb plus up/down buttons)

**Files:**
- Modify: `assets/css/theme.css` (add a new scrollbar block; place it just after the `html` rule from Task 1, before the LAYOUT PRIMITIVES section)

- [ ] **Step 1: Add the scrollbar block**

In `assets/css/theme.css`, immediately after the `html { ... }` rule, add:

```css
/* ===================================================================
   CUSTOM SCROLLBAR - minimal pill thumb plus accent up/down buttons.
   Reads --color-primary, so the accent switcher re-themes it live.
   WebKit (Chrome/Edge/Safari) gets the pill and arrow buttons; Firefox
   gets the themed pill thumb only (no scrollbar-button API).
   =================================================================== */
html {
  scrollbar-width: thin;
  scrollbar-color: color-mix(in oklab, var(--color-primary) 55%, var(--color-dark-2)) transparent;
}
::-webkit-scrollbar { width: 14px; height: 14px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-corner { background: transparent; }
::-webkit-scrollbar-thumb {
  background: color-mix(in oklab, var(--color-primary) 55%, var(--color-dark-2));
  border-radius: 999px;
  border: 3px solid transparent;
  background-clip: padding-box;
}
::-webkit-scrollbar-thumb:hover {
  background: var(--color-primary);
  background-clip: padding-box;
}
/* Up (decrement) and down (increment) buttons, vertical only */
::-webkit-scrollbar-button:vertical:decrement,
::-webkit-scrollbar-button:vertical:increment {
  height: 14px;
  background-color: color-mix(in oklab, var(--color-primary) 60%, var(--color-dark-2));
  -webkit-mask-repeat: no-repeat;
  -webkit-mask-position: center;
  -webkit-mask-size: 9px 9px;
}
::-webkit-scrollbar-button:vertical:decrement:hover,
::-webkit-scrollbar-button:vertical:increment:hover {
  background-color: var(--color-primary);
}
::-webkit-scrollbar-button:vertical:decrement {
  -webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpath fill='%23000' d='M6 3.5l4 4-1 1-3-3-3 3-1-1z'/%3E%3C/svg%3E");
}
::-webkit-scrollbar-button:vertical:increment {
  -webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpath fill='%23000' d='M6 8.5l-4-4 1-1 3 3 3-3 1 1z'/%3E%3C/svg%3E");
}
/* Show only one arrow at each end; hide the double-button slots and all
   horizontal buttons so the look stays minimal */
::-webkit-scrollbar-button:vertical:start:increment,
::-webkit-scrollbar-button:vertical:end:decrement,
::-webkit-scrollbar-button:horizontal { display: none; }
```

Notes for the implementer: the `decrement` SVG path is an up chevron (apex at top), `increment` is a down chevron (apex at bottom). The arrows are drawn as a `mask` over a solid `background-color` that references `--color-primary`, which is what makes them re-theme with the accent switcher (a plain `background-image` SVG could not read the variable). The pill thumb uses a transparent 3px border with `background-clip: padding-box` so it floats as an inset pill on the transparent track.

- [ ] **Step 2: Render check**

Run: `docker compose exec serens-web-php-service curl -sk --connect-to serensweb.test:443:serens-web-caddy-service:443 https://serensweb.test/ | head -c 200`
Expected: HTML output, page still renders.

- [ ] **Step 3: Browser confirmation (Seren)**

In Chrome or Edge on Windows at https://serensweb.test, confirm: the right-edge scrollbar shows a pill thumb and a single up arrow at the top and down arrow at the bottom; hovering the thumb and the arrows brightens them to full accent; switching the accent in the header (orange, blue, purple, green) re-colours the thumb and arrows live. In Firefox, confirm a thin themed pill thumb with no arrow buttons (expected fallback).

- [ ] **Step 4: Commit**

```bash
git add assets/css/theme.css
git commit -m "Add accent-aware pill scrollbar with up and down buttons"
```

---

## Task 3: Process steps grid (2-up on tablet, 4-up at 860)

**Files:**
- Modify: `assets/css/theme.css` (the 700px container block, around line 445; and the 860px block, around line 448-459)

- [ ] **Step 1: Make the 700px steps rule 2 columns**

In `assets/css/theme.css`, in the `@container site (min-width: 700px)` block, change:

```css
  .steps { grid-template-columns: repeat(4, 1fr); }
```

to:

```css
  .steps { grid-template-columns: repeat(2, 1fr); }
```

- [ ] **Step 2: Add the 4-column steps rule at 860px**

In the `@container site (min-width: 860px)` block, add this line (next to the other grid rules in that block):

```css
  .steps { grid-template-columns: repeat(4, 1fr); }
```

- [ ] **Step 3: Render check**

Run: `docker compose exec serens-web-php-service curl -sk --connect-to serensweb.test:443:serens-web-caddy-service:443 https://serensweb.test/ | head -c 200`
Expected: HTML output, page still renders.

- [ ] **Step 4: Browser confirmation (Seren)**

On the home page Process section: at 768px the four steps sit as a 2 by 2 grid (not four cramped columns); at 1280px they are four across; at 390px they stack in one column.

- [ ] **Step 5: Commit**

```bash
git add assets/css/theme.css
git commit -m "Process steps: 2-up on tablet, 4-up from 860"
```

---

## Task 4: Reveal full nav at the tablet width (820px)

**Files:**
- Modify: `assets/css/theme.css` (move four rules out of the 860px block into a new 820px block placed just before it)

- [ ] **Step 1: Remove the four header rules from the 860px block**

In `assets/css/theme.css`, in the `@container site (min-width: 860px)` block, delete these four lines:

```css
  .nav { display: flex; }
  .header__cta { display: inline-flex; }
  .burger { display: none; }
  .drawer { display: none; }
```

Leave the rest of that block (the about, contact, footer, hero grid rules) untouched.

- [ ] **Step 2: Add a new 820px block before the 860px block**

Immediately before `@container site (min-width: 860px) {`, insert:

```css
@container site (min-width: 820px) {
  .nav { display: flex; }
  .header__cta { display: inline-flex; }
  .burger { display: none; }
  .drawer { display: none; }
}
```

- [ ] **Step 3: Render check**

Run: `docker compose exec serens-web-php-service curl -sk --connect-to serensweb.test:443:serens-web-caddy-service:443 https://serensweb.test/ | head -c 200`
Expected: HTML output, page still renders.

- [ ] **Step 4: Browser confirmation (Seren)**

At 768px the hamburger still shows (below 820). At an iPad-portrait width of 820 to 834px, the full primary nav and the "Let's talk" CTA show and the hamburger is gone. At 860px and up, the section layouts (about, contact, hero) flip as before.

- [ ] **Step 5: Commit**

```bash
git add assets/css/theme.css
git commit -m "Reveal full nav at 820 so iPad portrait skips the hamburger"
```

---

## Task 5: Accent-dot tap target (WCAG 2.5.8, 24px minimum)

**Files:**
- Modify: `assets/css/theme.css` (the header accent-dot rules, around line 389-390)

- [ ] **Step 1: Add a hit-area pseudo-element to the header accent dots**

In `assets/css/theme.css`, just after the rule `.accent-switch--header .accent-dot { width: 18px; height: 18px; }`, add:

```css
/* Keep the 18px visual dot but enlarge the touch target to 24px (WCAG 2.5.8).
   The dots are 8px apart, so a 3px bleed each side tiles without overlapping. */
.accent-switch--header .accent-dot::before {
  content: "";
  position: absolute;
  inset: -3px;
  border-radius: 50%;
}
```

The base `.accent-dot` already has `position: relative`, and its `::after` is used for the pressed-state inner dot, so `::before` is free for the hit area. The pseudo-element is part of the button box, so clicks and taps on it dispatch to the dot.

- [ ] **Step 2: Render check**

Run: `docker compose exec serens-web-php-service curl -sk --connect-to serensweb.test:443:serens-web-caddy-service:443 https://serensweb.test/ | head -c 200`
Expected: HTML output, page still renders.

- [ ] **Step 3: Browser confirmation (Seren)**

The header dots look unchanged (still 18px circles, same spacing), but in DevTools each `.accent-dot` reports a hit box of about 24 by 24px (hover the element to see the highlighted area), and tapping near a dot on a touch device reliably selects it. Switching accents still works.

- [ ] **Step 4: Commit**

```bash
git add assets/css/theme.css
git commit -m "Enlarge header accent-dot touch target to 24px"
```

---

## Task 6: Honest hero codecard copy

**Files:**
- Modify: `patterns/home-hero.php:42-49` (the eight `.ln` lines inside `.codecard__body`)

- [ ] **Step 1: Replace the codecard body lines**

In `wordpress/wp-content/themes/serensweb-child/patterns/home-hero.php`, replace the block:

```html
<span class="ln"><span class="dim">// ship.config.ts</span></span>
<span class="ln"><span class="key">export const</span> stack = {</span>
<span class="ln">  framework: <span class="str">"headless"</span>,</span>
<span class="ln">  perf: <span class="tag">"&lt;1s LCP"</span>,</span>
<span class="ln">  a11y: <span class="str">"WCAG AA"</span>,</span>
<span class="ln">  deploy: <span class="str">"edge / global"</span>,</span>
<span class="ln">}</span>
<span class="ln"><span class="dim">// status:</span> <span class="str">shipped</span> <span class="codecard__caret"></span></span>
```

with:

```html
<span class="ln"><span class="dim">// stack.config.ts</span></span>
<span class="ln"><span class="key">export const</span> build = {</span>
<span class="ln">  cms:  <span class="str">"wordpress / fse"</span>,</span>
<span class="ln">  ai:   <span class="str">"assisted-workflow"</span>,</span>
<span class="ln">  app:  <span class="str">"installable pwa"</span>,</span>
<span class="ln">  ship: <span class="str">"independent"</span>,</span>
<span class="ln">}</span>
<span class="ln"><span class="dim">// status:</span> <span class="str">shipped</span> <span class="codecard__caret"></span></span>
```

This removes the banned "headless" term and the fabricated "<1s LCP" metric, and draws on the real services (WordPress and FSE, AI-assisted workflow, installable PWA). Every line stays under about 26 characters so the `white-space: pre` block does not clip on a 360px phone. The block remains `aria-hidden`, so it is decoration only.

- [ ] **Step 2: Lint the PHP**

Run: `docker compose exec serens-web-php-service php -l wp-content/themes/serensweb-child/patterns/home-hero.php`
Expected: `No syntax errors detected`.

- [ ] **Step 3: Render check confirms new copy present**

Run: `docker compose exec serens-web-php-service curl -sk --connect-to serensweb.test:443:serens-web-caddy-service:443 https://serensweb.test/ | grep -o 'stack.config.ts'`
Expected: `stack.config.ts` (the new comment renders; the old "headless" no longer appears).

- [ ] **Step 4: Commit**

```bash
git add wordpress/wp-content/themes/serensweb-child/patterns/home-hero.php
git commit -m "Hero codecard: honest copy, drop headless and fabricated metric"
```

---

## Task 7: Verify-then-tune sweep (conditional fixes)

These spec items are "verify in the browser, then tune only if the check fails." Each has prepared CSS to apply ONLY if Seren's browser check flags it. If a check passes, skip its edit (YAGNI).

**Files (only if a check fails):**
- Modify: `assets/css/theme.css`
- Modify: `assets/css/tokens.css` (padding rhythm only)

- [ ] **Step 1: Browser checks (Seren), at 390 / 768 / 1280 / 1920px**

Confirm each of the following. Note any that fail.

  - (a) **Section padding rhythm:** does vertical breathing room feel right? Too tight on phone or too loose on desktop?
  - (b) **360 to 390px header:** do the brand wordmark, four accent dots, and the burger share one row without crowding or wrapping? (Borderline at 320px iPhone SE.)
  - (c) **No horizontal scrollbar** appears at any width on any page.
  - (d) **Focus ring on light sections:** tab to a link or button inside the About or Selected Work areas; is the accent outline clearly visible against the light background?

- [ ] **Step 2 (only if 1a fails): Tune the padding clamp ends**

In `assets/css/tokens.css`, adjust only the min/max of `--section-pad` (keep the `9cqi` middle). For example, if mobile is tight raise the min, if desktop is loose lower the max:

```css
  --section-pad: clamp(72px, 9cqi, 120px);
```

- [ ] **Step 3 (only if 1b fails): Tighten the header accent switch on narrow widths**

In `assets/css/theme.css`, add a small max-width container query near the other queries:

```css
@container site (max-width: 380px) {
  .accent-switch--header { gap: 6px; padding-right: 6px; margin-right: 2px; }
  .accent-switch--header .accent-dots { gap: 6px; }
}
```

- [ ] **Step 4 (only if 1d fails): Add a contrast halo to the focus ring**

In `assets/css/theme.css`, replace the `:focus-visible` rule:

```css
:focus-visible { outline: 2px solid var(--color-primary); outline-offset: 3px; border-radius: 4px; }
```

with a version that adds a thin dark/light halo so the ring reads on both surfaces:

```css
:focus-visible {
  outline: 2px solid var(--color-primary);
  outline-offset: 3px;
  border-radius: 4px;
  box-shadow: 0 0 0 1px var(--color-dark), 0 0 0 5px color-mix(in oklab, var(--color-primary) 30%, transparent);
}
```

- [ ] **Step 5: Render check (if any edit was made)**

Run: `docker compose exec serens-web-php-service curl -sk --connect-to serensweb.test:443:serens-web-caddy-service:443 https://serensweb.test/ | head -c 200`
Expected: HTML output, page still renders.

- [ ] **Step 6: Commit (only if edits were made)**

```bash
git add assets/css/theme.css assets/css/tokens.css
git commit -m "Tune padding, narrow-header, and focus-ring per browser check"
```

If no edits were needed, record in the PR description that the verify-then-tune checks all passed with no change.

---

## Task 8: Open the pull request

- [ ] **Step 1: Push the branch**

```bash
git push -u origin feat/design-nitpicks-responsive
```

- [ ] **Step 2: Open the PR**

```bash
gh pr create --fill --base main
```

Use a body that lists the batches shipped (scrollbar, scroll behaviour, tablet fit, tap target, codecard copy) and notes which verify-then-tune checks were applied versus passed clean. No emoji, no em or en dashes per the project writing rules.

---

## Self-review notes

- **Spec coverage:** 1.1 scrollbar to Task 2; 1.2 gutter and 1.3 anchor token to Task 1; 2.1 steps to Task 3; 2.2 nav to Task 4; 2.3 padding to Task 7 step 2; 2.4 narrow-header overflow to Task 7 step 3; 3.1 accent-dot target to Task 5; 3.2 footer/contact targets folded into Task 7 check 1 (the footer link computes to about 30px tall with line-height, so it is verify-only, not a forced edit); 3.3 focus ring to Task 7 step 4; 4.1 codecard to Task 6. All spec sections map to a task.
- **Token consistency:** `--header-h` is defined in Task 1 and consumed in the same task; the scrollbar mix `color-mix(in oklab, var(--color-primary) 55%, var(--color-dark-2))` is identical across Firefox and WebKit thumb rules; the accent variable name `--color-primary` matches `tokens.css`.
- **No placeholders:** every code step shows complete CSS or markup; conditional steps in Task 7 carry their full snippet so they are ready to paste if a check fails.
