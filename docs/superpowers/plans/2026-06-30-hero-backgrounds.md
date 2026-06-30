# Accent-driven hero backgrounds Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Give the four dark heroes (Work, Playground, About, Home) distinct, reference-inspired background textures that re-theme live with the visitor accent switcher.

**Architecture:** One shared `.hero-bg` engine in `theme.css` (dark base, accent glow via pseudo-elements, optional dot-grid, perspective-fade mask) plus four variant modifiers. Per-page SVG geometry lives inline in each pattern and is painted with `currentColor` (set to `var(--color-primary)` on the SVG), so it follows the accent switcher with zero JS.

**Tech Stack:** WordPress FSE block patterns (PHP), hand-written CSS in `assets/css/theme.css`, inline SVG (paths + one `feTurbulence` filter). No build step, no new JS, no plugins, no new assets.

---

## Conventions for every task

- Branch is already `feat/hero-backgrounds` (cut from `main`). Stay on it.
- The local stack is up at `https://serensweb.test` (self-signed cert, use `curl -sk`).
- Pages reference patterns by slug, so editing a pattern PHP file updates the live page on next request. No DB resync, no wp-cli needed.
- **Banned characters (project rule):** no em-dash (U+2014), en-dash (U+2013), two consecutive ASCII hyphens used as a dash, or emoji. This includes CSS/code comments and commit messages. BEM modifier syntax like `hero-bg--traces` is code, not a dash, and is fine.
- Commit after each task. Commit messages reference the work, no emoji, end with the Co-Authored-By trailer:
  ```
  Co-Authored-By: Claude Opus 4.8 <noreply@anthropic.com>
  ```
- Visual confirmation: the implementing session cannot see the browser. After each page task, ask the user to open the page at `https://serensweb.test`, flip the four accent dots in the header, and confirm the texture looks right and re-themes. Treat that as the acceptance gate before moving on.

---

## File structure

- Modify: `wordpress/wp-content/themes/serensweb-child/assets/css/theme.css` (append one new section; do not touch the existing `.hero__mesh` / `.hero__grid` rules, which `contact.php` still uses).
- Modify: `wordpress/wp-content/themes/serensweb-child/patterns/home-hero.php` (swap the two decorative divs for the `--signature` wrapper).
- Modify: `wordpress/wp-content/themes/serensweb-child/patterns/projects-archive-header.php` (`--traces`).
- Modify: `wordpress/wp-content/themes/serensweb-child/patterns/playground.php` (`--ribbons`, header section only).
- Modify: `wordpress/wp-content/themes/serensweb-child/patterns/about.php` (`--cloud`, header section only).

---

### Task 1: Add the shared `.hero-bg` CSS engine and four variants

**Files:**
- Modify: `wordpress/wp-content/themes/serensweb-child/assets/css/theme.css` (append at end of file)

- [ ] **Step 1: Append the new CSS section to `theme.css`**

Append this block to the very end of `assets/css/theme.css`. It does not modify or remove any existing rule.

```css
/* ===================================================================
   HERO BACKGROUNDS (accent-driven, static) - .hero-bg engine.
   Inline SVG paints with currentColor (set to --color-primary here),
   so the accent switcher re-themes every hero live. No animation.
   The legacy .hero__mesh / .hero__grid rules are left intact for the
   contact section, which still uses them.
   =================================================================== */
.page-header { position: relative; isolation: isolate; }
.page-header > .wrap { position: relative; z-index: 1; }

.hero-bg {
  position: absolute; inset: 0; z-index: 0;
  pointer-events: none; overflow: hidden;
}
.hero-bg__svg {
  position: absolute; inset: 0; width: 100%; height: 100%;
  display: block; color: var(--color-primary);
}

.hero-bg__grid {
  position: absolute; inset: 0; pointer-events: none; opacity: 0.55;
  background-image:
    linear-gradient(var(--hairline-dark) 1px, transparent 1px),
    linear-gradient(90deg, var(--hairline-dark) 1px, transparent 1px);
  background-size: 56px 56px;
  -webkit-mask-image: radial-gradient(ellipse 90% 70% at 30% 30%, #000 30%, transparent 78%);
          mask-image: radial-gradient(ellipse 90% 70% at 30% 30%, #000 30%, transparent 78%);
}

.hero-bg::before, .hero-bg::after {
  content: ""; position: absolute; pointer-events: none;
  border-radius: 50%; filter: blur(72px);
}
.hero-bg::before {
  width: 62cqi; height: 62cqi; top: -24cqi; right: -12cqi;
  background: radial-gradient(circle, var(--color-primary), transparent 68%);
  opacity: 0.5;
}
.hero-bg::after {
  width: 46cqi; height: 46cqi; bottom: -22cqi; left: -10cqi;
  background: radial-gradient(circle, var(--color-primary-strong), transparent 70%);
  opacity: 0.26;
}

.hero-bg--traces .hero-bg__svg,
.hero-bg--signature .hero-bg__svg {
  -webkit-mask-image: radial-gradient(130% 130% at 70% 15%, #000 38%, transparent 86%);
          mask-image: radial-gradient(130% 130% at 70% 15%, #000 38%, transparent 86%);
}

.hero-bg--ribbons::after { display: none; }
.hero-bg--ribbons::before {
  width: 120cqi; height: 80cqi; top: -10cqi; right: -10cqi; left: auto;
  background: radial-gradient(ellipse at 60% 40%, var(--color-primary), transparent 60%);
  opacity: 0.32; filter: blur(60px);
}
.hero-bg--ribbons .hero-bg__svg {
  -webkit-mask-image: radial-gradient(140% 140% at 50% 50%, #000 45%, transparent 92%);
          mask-image: radial-gradient(140% 140% at 50% 50%, #000 45%, transparent 92%);
}

.hero-bg--cloud::after { display: none; }
.hero-bg--cloud::before {
  width: 90cqi; height: 120cqi; top: -10cqi; left: 50cqi; right: auto;
  background: radial-gradient(ellipse at center, var(--color-primary), transparent 62%);
  opacity: 0.62; filter: blur(50px);
}

@media (prefers-reduced-motion: reduce) {
  .hero-bg, .hero-bg__svg, .hero-bg__grid { animation: none; transition: none; }
}
```

- [ ] **Step 2: Verify the CSS file is served and contains the new engine**

Run:
```bash
curl -sk "https://serensweb.test/wp-content/themes/serensweb-child/assets/css/theme.css?$(date +%s)" | grep -c "hero-bg__svg"
```
Expected: prints `1` or higher (the new rules are live). If `0`, the file did not save or a cache is serving an old copy.

- [ ] **Step 3: Confirm no banned characters were introduced**

Run (uses the Grep tool in practice; this is the shell equivalent):
```bash
grep -nP $'—|–' wordpress/wp-content/themes/serensweb-child/assets/css/theme.css && echo "FOUND BANNED" || echo "clean"
```
Expected: `clean`.

- [ ] **Step 4: Confirm pages still return 200 (CSS is additive, nothing should break)**

Run:
```bash
curl -sk -o /dev/null -w "%{http_code}\n" https://serensweb.test/
```
Expected: `200`.

- [ ] **Step 5: Commit**

```bash
git add wordpress/wp-content/themes/serensweb-child/assets/css/theme.css
git commit -m "feat(hero): add accent-driven .hero-bg engine and four variants

Co-Authored-By: Claude Opus 4.8 <noreply@anthropic.com>"
```

---

### Task 2: Home hero `--signature`

**Files:**
- Modify: `wordpress/wp-content/themes/serensweb-child/patterns/home-hero.php`

- [ ] **Step 1: Replace the two decorative divs**

In `patterns/home-hero.php`, replace exactly these two lines:
```html
  <div class="hero__mesh" aria-hidden="true"></div>
  <div class="hero__grid" aria-hidden="true"></div>
```
with:
```html
  <div class="hero-bg hero-bg--signature" aria-hidden="true">
    <span class="hero-bg__grid"></span>
    <svg class="hero-bg__svg" viewBox="0 0 1200 600" preserveAspectRatio="xMidYMid slice" focusable="false" aria-hidden="true">
      <g fill="none" stroke="currentColor" stroke-width="2" stroke-opacity="0.20" stroke-linejoin="round">
        <path d="M-10 470 H250 a14 14 0 0 0 14-14 V250 H560"/>
        <path d="M1210 150 H960 a14 14 0 0 1-14 14 V360 H700"/>
      </g>
      <g fill="currentColor" fill-opacity="0.5">
        <circle cx="560" cy="250" r="4"/><circle cx="700" cy="360" r="4"/>
      </g>
    </svg>
  </div>
```
Leave the rest of the file (the `.hero__inner`, copy, CTAs, stats, codecard) unchanged.

- [ ] **Step 2: Verify the home page renders the new wrapper**

Run:
```bash
curl -sk "https://serensweb.test/?$(date +%s)" | grep -o "hero-bg hero-bg--signature"
```
Expected: prints `hero-bg hero-bg--signature`.

- [ ] **Step 3: Verify the old classes are gone from the home hero**

Run:
```bash
curl -sk "https://serensweb.test/?$(date +%s)" | grep -c 'class="hero__mesh"'
```
Expected: `0`.

- [ ] **Step 4: Visual acceptance**

Ask the user to open `https://serensweb.test/`, confirm the hero shows the refined mesh plus a faint grid and two quiet accent traces, the headline and code card are fully legible, and clicking the four accent dots re-themes the glow and traces with no reload. Do not proceed until confirmed.

- [ ] **Step 5: Commit**

```bash
git add wordpress/wp-content/themes/serensweb-child/patterns/home-hero.php
git commit -m "feat(hero): home signature background

Co-Authored-By: Claude Opus 4.8 <noreply@anthropic.com>"
```

---

### Task 3: Work archive header `--traces`

**Files:**
- Modify: `wordpress/wp-content/themes/serensweb-child/patterns/projects-archive-header.php`

- [ ] **Step 1: Replace the two decorative divs**

In `patterns/projects-archive-header.php`, replace exactly these two lines:
```html
  <div class="hero__mesh" aria-hidden="true"></div>
  <div class="hero__grid" aria-hidden="true"></div>
```
with:
```html
  <div class="hero-bg hero-bg--traces" aria-hidden="true">
    <span class="hero-bg__grid"></span>
    <svg class="hero-bg__svg" viewBox="0 0 1200 600" preserveAspectRatio="xMidYMid slice" focusable="false" aria-hidden="true">
      <g fill="none" stroke="currentColor" stroke-width="2" stroke-opacity="0.30" stroke-linejoin="round">
        <path d="M-10 120 H210 a14 14 0 0 0 14-14 V40 H540"/>
        <path d="M60 620 V470 a14 14 0 0 1 14-14 H380 a14 14 0 0 0 14-14 V250 H840"/>
        <path d="M1210 250 H1000 a14 14 0 0 1-14-14 V120 H640"/>
        <path d="M430 -10 V70 a14 14 0 0 0 14 14 H930 a14 14 0 0 1 14 14 V300"/>
        <path d="M1210 470 H980 a14 14 0 0 1-14-14 V360 H760"/>
      </g>
      <g fill="none" stroke="currentColor" stroke-width="2.6" stroke-opacity="0.95" stroke-linecap="round">
        <path d="M224 106 V40 H540"/>
        <path d="M394 442 V250 H840"/>
      </g>
      <g fill="currentColor">
        <circle cx="540" cy="40" r="6"/><circle cx="840" cy="250" r="6"/>
        <circle cx="224" cy="106" r="4"/><circle cx="394" cy="442" r="4"/>
        <circle cx="640" cy="120" r="4"/><circle cx="944" cy="112" r="4"/>
        <circle cx="760" cy="360" r="4"/>
      </g>
    </svg>
  </div>
```

- [ ] **Step 2: Verify the projects page renders the new wrapper**

Run:
```bash
curl -sk "https://serensweb.test/projects/?$(date +%s)" | grep -o "hero-bg hero-bg--traces"
```
Expected: prints `hero-bg hero-bg--traces`.

- [ ] **Step 3: Visual acceptance**

Ask the user to open `https://serensweb.test/projects/`, confirm the circuit-trace texture with accent nodes fading into the dark, the "Selected work" heading and lede are legible, and the accent switcher re-themes the traces and glow. Do not proceed until confirmed.

- [ ] **Step 4: Commit**

```bash
git add wordpress/wp-content/themes/serensweb-child/patterns/projects-archive-header.php
git commit -m "feat(hero): work archive traces background

Co-Authored-By: Claude Opus 4.8 <noreply@anthropic.com>"
```

---

### Task 4: Playground header `--ribbons`

**Files:**
- Modify: `wordpress/wp-content/themes/serensweb-child/patterns/playground.php`

- [ ] **Step 1: Replace the two decorative divs in the header section**

In `patterns/playground.php`, the header section is the first `<section class="section section--dark page-header">`. Replace exactly these two lines inside it:
```html
  <div class="hero__mesh" aria-hidden="true"></div>
  <div class="hero__grid" aria-hidden="true"></div>
```
with:
```html
  <div class="hero-bg hero-bg--ribbons" aria-hidden="true">
    <svg class="hero-bg__svg" viewBox="0 0 1200 600" preserveAspectRatio="xMidYMid slice" focusable="false" aria-hidden="true">
      <defs><filter id="rbGlow" x="-10%" y="-40%" width="120%" height="180%"><feGaussianBlur stdDeviation="10"/></filter></defs>
      <g fill="none" stroke="currentColor" filter="url(#rbGlow)" stroke-opacity="0.45">
        <path d="M-20 380 C 240 180, 460 520, 700 340 S 1080 180, 1220 300" stroke-width="5"/>
        <path d="M-20 300 C 260 460, 440 180, 720 360 S 1040 460, 1220 250" stroke-width="5"/>
      </g>
      <g fill="none" stroke="currentColor" stroke-linecap="round">
        <path d="M-20 380 C 240 180, 460 520, 700 340 S 1080 180, 1220 300" stroke-width="2.4" stroke-opacity="0.9"/>
        <path d="M-20 300 C 260 460, 440 180, 720 360 S 1040 460, 1220 250" stroke-width="2.4" stroke-opacity="0.8"/>
        <path d="M-20 440 C 260 300, 440 560, 720 420 S 1040 340, 1220 410" stroke-width="1.6" stroke-opacity="0.55"/>
        <path d="M-20 220 C 240 360, 480 140, 720 280 S 1040 360, 1220 180" stroke-width="1.6" stroke-opacity="0.5"/>
      </g>
      <g fill="#fff">
        <circle cx="700" cy="340" r="2.6" fill-opacity="0.9"/>
        <circle cx="360" cy="470" r="2" fill-opacity="0.7"/>
        <circle cx="940" cy="300" r="2" fill-opacity="0.7"/>
      </g>
    </svg>
  </div>
```
Leave the second section (the `.play-grid`) unchanged.

- [ ] **Step 2: Verify the playground page renders the new wrapper**

Run:
```bash
curl -sk "https://serensweb.test/playground/?$(date +%s)" | grep -o "hero-bg hero-bg--ribbons"
```
Expected: prints `hero-bg hero-bg--ribbons`.

- [ ] **Step 3: Visual acceptance**

Ask the user to open `https://serensweb.test/playground/`, confirm the flowing light-ribbon waves with a soft accent glow, the "Playground" heading and lede are legible, and the accent switcher re-themes the ribbons. Do not proceed until confirmed.

- [ ] **Step 4: Commit**

```bash
git add wordpress/wp-content/themes/serensweb-child/patterns/playground.php
git commit -m "feat(hero): playground ribbons background

Co-Authored-By: Claude Opus 4.8 <noreply@anthropic.com>"
```

---

### Task 5: About header `--cloud`

**Files:**
- Modify: `wordpress/wp-content/themes/serensweb-child/patterns/about.php`

- [ ] **Step 1: Replace the two decorative divs in the header section**

In `patterns/about.php`, the header section is the first `<section class="section section--dark page-header">`. Replace exactly these two lines inside it:
```html
  <div class="hero__mesh" aria-hidden="true"></div>
  <div class="hero__grid" aria-hidden="true"></div>
```
with:
```html
  <div class="hero-bg hero-bg--cloud" aria-hidden="true">
    <svg class="hero-bg__svg" viewBox="0 0 1200 600" preserveAspectRatio="none" focusable="false" aria-hidden="true">
      <defs>
        <filter id="abCloud" x="0" y="0" width="100%" height="100%">
          <feTurbulence type="fractalNoise" baseFrequency="0.011 0.016" numOctaves="4" seed="11" stitchTiles="stitch"/>
          <feColorMatrix type="matrix" values="0 0 0 0 0.043 0 0 0 0 0.043 0 0 0 0 0.055 2.4 0 0 0 -0.35"/>
        </filter>
      </defs>
      <rect width="1200" height="600" filter="url(#abCloud)"/>
    </svg>
  </div>
```
Leave the second section (the about-page grid) unchanged.

- [ ] **Step 2: Verify the about page renders the new wrapper and the cloud filter**

Run:
```bash
curl -sk "https://serensweb.test/about/?$(date +%s)" | grep -o "hero-bg hero-bg--cloud"
curl -sk "https://serensweb.test/about/?$(date +%s)" | grep -o "feTurbulence"
```
Expected: prints `hero-bg hero-bg--cloud` then `feTurbulence`.

- [ ] **Step 3: Visual acceptance and feTurbulence paint-cost check**

Ask the user to open `https://serensweb.test/about/` and confirm:
1. A dark night-cloud texture with an accent glow leaking through the thin patches.
2. The dense cloud stays dark and identical when switching accents; only the glow colour changes.
3. The "About" heading and lede are legible.
4. Scrolling the page feels smooth (the `feTurbulence` filter has acceptable paint cost on their machine).

If scrolling stutters, reduce cost by changing `numOctaves="4"` to `numOctaves="3"` in the filter, re-verify, and note it. Do not proceed until confirmed.

- [ ] **Step 4: Commit**

```bash
git add wordpress/wp-content/themes/serensweb-child/patterns/about.php
git commit -m "feat(hero): about cloud-glow background

Co-Authored-By: Claude Opus 4.8 <noreply@anthropic.com>"
```

---

### Task 6: Cross-page verification and PR update

**Files:** none (verification only)

- [ ] **Step 1: Confirm all four pages return 200 and carry their variant**

Run:
```bash
for u in "/:signature" "/projects/:traces" "/playground/:ribbons" "/about/:cloud"; do
  path="${u%%:*}"; v="${u##*:}";
  code=$(curl -sk -o /dev/null -w "%{http_code}" "https://serensweb.test${path}");
  has=$(curl -sk "https://serensweb.test${path}?$(date +%s)" | grep -c "hero-bg--${v}");
  echo "${path} code=${code} hero-bg--${v}=${has}";
done
```
Expected: every line shows `code=200` and `hero-bg--<variant>=1`.

- [ ] **Step 2: Confirm the active plugin count is still zero**

Run:
```bash
docker compose exec serens-web-php-service wp plugin list --status=active --format=count
```
Expected: `0`.

- [ ] **Step 3: Confirm no banned characters across the changed files**

Run:
```bash
grep -rnP $'—|–' \
  wordpress/wp-content/themes/serensweb-child/assets/css/theme.css \
  wordpress/wp-content/themes/serensweb-child/patterns/home-hero.php \
  wordpress/wp-content/themes/serensweb-child/patterns/projects-archive-header.php \
  wordpress/wp-content/themes/serensweb-child/patterns/playground.php \
  wordpress/wp-content/themes/serensweb-child/patterns/about.php \
  && echo "FOUND BANNED" || echo "clean"
```
Expected: `clean`.

- [ ] **Step 4: Push and confirm the PR reflects the implementation**

Run:
```bash
git push
```
The existing PR (https://github.com/serenvdmerwe/serens-web/pull/14) updates automatically. Add a short comment noting the implementation has landed on top of the spec.

---

## Self-review notes (author check, completed)

- **Spec coverage:** technique (Task 1 CSS uses inline SVG + `currentColor`), motion none (no animation anywhere; reduced-motion guard added), all four heroes (Tasks 2 to 5), distinct per page (four variants), cloud constant-dark with accent glow (Task 5 fixed-seed `feTurbulence` over CSS glow), accessibility (`aria-hidden`, `focusable="false"`, content raised above bg via `.page-header > .wrap` z-index and home's existing `.hero__inner` z-index), perf and plugin-count (Task 6), banned characters (Tasks 1 and 6), old classes preserved for contact (not removed). All covered.
- **Placeholders:** none; every step has exact code and commands.
- **Naming consistency:** `.hero-bg`, `.hero-bg__svg`, `.hero-bg__grid`, and the four `hero-bg--<variant>` classes are used identically in CSS (Task 1) and markup (Tasks 2 to 5). The `feTurbulence`/`feColorMatrix` filter id `abCloud` and the ribbon glow filter id `rbGlow` are each defined and referenced within the same SVG.
