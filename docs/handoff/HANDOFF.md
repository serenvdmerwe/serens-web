# SerensWeb — Build Handoff Note

**Project:** SerensWeb — single-page marketing site for a one-person freelance web-dev studio
**Platform:** WordPress, FSE **block child theme** of Twenty Twenty-Five
**Fidelity:** High — colours, type, spacing, motion, and copy are all decided
**Date:** 2026-06-04
**Status:** Ready to bootstrap

---

## 0. How to read this note

This note is the **build contract**. It does not re-document the whole design — it
layers the build decisions on top of the design package and maps that design onto the
TurboPress structure.

**Canonical source of truth (in priority order):**

1. `docs/handoff/design_handoff_serensweb_site/design-reference/styles.css` — every
   colour/size as a CSS custom property. Final say on any numeric value.
2. `docs/handoff/design_handoff_serensweb_site/design-reference/SerensWeb.html` — page
   structure, section order, and copy.
3. `docs/handoff/design_handoff_serensweb_site/design-reference/app.js` — all
   interaction behaviour (vanilla, no build step).
4. `docs/handoff/design_handoff_serensweb_site/README.md` — the design author's full
   spec. Read it; it is excellent.

If this note and the design package ever disagree on a *value*, the design package wins.
If they disagree on a *decision* (form handling, subdomains, etc.), this note wins —
those are the calls made for this build.

> **Do NOT ship** anything in
> `design_handoff_serensweb_site/design-reference/_prototype-only/` — those `.jsx` files
> are a design-review "Tweaks" panel, not a site feature.

---

## 1. Project at a glance

A dark, modern, high-craft single-page scroll:

`header → hero → about → strengths (4 service areas) → selected work → process → contact → footer`

Signature feature: a **live brand-accent switcher** (orange / blue / purple / green) that
re-themes the entire page and persists per visitor. The four service areas are each
intended to live on their own subdomain of `serensweb.dev` (see §6 — deferred).

---

## 2. Build constraints (locked)

| Constraint | Decision |
|---|---|
| Theme model | FSE **block child theme** of Twenty Twenty-Five |
| Plugins | **Target zero.** Nothing about a static marketing page needs a plugin until the contact backend is wired (deferred — see §5). |
| Pages | **One** scrolling home page. In TurboPress: one home template + one block pattern per section; header & footer as template parts. |
| Fonts | **Bundle Geist + Geist Mono locally** (`@font-face` via `theme.json`), not the Google CDN — better performance + GDPR. |
| Assets | No raster/vector files. All imagery is CSS (gradients, glows, faux code card) or inline SVG icons. |
| Responsiveness | **Container queries** (`@container site (...)`), not media queries. The page wrapper must carry `container-type: inline-size; container-name: site;`. |

---

## 3. Local environment & Docker isolation

The local stack must be **fully self-contained** and must not collide with any other
Docker project on this machine.

| Isolation lever | Value for this project |
|---|---|
| Compose project name | `serens-web` (namespaces every container/network/volume) |
| Domain | `https://serensweb.test` (unique, via Caddy local CA) |
| Network | dedicated project bridge network (`serens-web_default`) |
| Volumes | **named, project-prefixed** volumes for DB + wp-content; no bind mounts into any other project directory |
| Containers | project-prefixed names (`serens-web-*`): Caddy, PHP-FPM 8.4, MariaDB 12, Redis |
| Host ports | Caddy binds 80/443. **Only one HTTPS `*.test` stack can hold 80/443 at a time** — bring other local web stacks down before `up`, or remap host ports if they must run concurrently. |

Everything lives under `C:\dev\serens-web`. Tearing the project down (`docker compose
down -v`) removes only this project's containers, network, and named volumes.

---

## 4. Decisions made this session

### 4.1 Accent switcher — **KEEP, all four, default orange**
Ship the full visitor-facing switcher exactly as designed:
- Accents: orange `#F6821F` (**default**), blue `#2F73E8`, purple `#8B5CF6`, green `#1FA463`.
- Clicking a dot sets `--color-primary` (+ derived `--color-primary-strong`) on `:root`,
  updates `aria-pressed` on all dot groups, and **persists to `localStorage`**.
- Placement: **header** (the `data-switcher="header"` default). The hero/footer switcher
  markup may be deleted; hard-code header placement.

### 4.2 Service subdomains — **DEFERRED (documented TODO)**
The four "subdomains" (`commerce.` / `ai.` / `apps.` / `studio.serensweb.dev`) do not
exist yet. **For this build, the four cards link to on-page anchors** (a safe default so
nothing 404s). Real subdomain architecture (separate installs vs. multisite vs.
coming-soon routes) is a later decision — see §6.

### 4.3 Contact form — **PLACEHOLDER (no backend yet)**
Render the form exactly as designed, including its **client-side** validation (name
required, email pattern, message ≥ 10 chars) and the "Message sent. Thank you." success
card with "Send another". **There is no server-side handler** — submissions are not
delivered yet. Wiring a real backend is deferred (see §6) and, per the minimal-plugin
goal, will likely be a theme-only REST endpoint calling `wp_mail()` rather than a plugin.

---

## 5. Design → TurboPress structure

| Design artifact | Becomes in the child theme |
|---|---|
| `:root` custom properties in `styles.css` | `theme.json` palette + `settings.custom`, surfaced through a `tokens.css` layer |
| Rules in `styles.css` (`.section--dark`, `.hero`, card classes, etc.) | copied **verbatim** into the theme stylesheet so every class hook keeps working |
| The 8 `SerensWeb.html` sections | one **block pattern** each (`patterns/home-<section>.php`) |
| Page shell / section order | a home **template** (`templates/...`) assembling the patterns |
| Header & footer | **template parts** |
| `app.js` | enqueued via the child theme (`functions.php` / `wp_enqueue_scripts`); switcher persistence + reveal observer work unchanged |
| `PROJECTS` array (Selected work) | placeholder data for now; later a custom post type or repeater (see §6) |
| `_prototype-only/*.jsx` | **not shipped** |

### Section inventory (build order within the page)
1. **Header** (sticky, shrinks on scroll; wordmark + nav + accent switcher + "Let's talk" CTA; mobile burger → drawer)
2. **Hero** (`.section--dark`; eyebrow, H1 with accent `<em>perform</em>`, sub, two buttons, stats row, decorative code card)
3. **About** (`.section--light`; statement + core-stack pill chips)
4. **Strengths / "What I build"** (`.section--dark`; 4 service cards → on-page anchors per §4.2)
5. **Selected work** (`.section--light`; cards injected by `app.js`; click → lightbox modal)
6. **Process** (`.section--dark2`; 4 steps)
7. **Contact** (`.section--dark`; meta list + the placeholder form per §4.3)
8. **Footer** (`.section--dark`; brand + 3 link columns + social icons + injected year)

### Key tokens (full set in `styles.css`)
- **Accent (the one re-theming knob):** see §4.1.
- **Surfaces:** dark `#1A1A1A`, dark-2 `#2A2A2A`, dark-3 `#232323`, smoke `#F5F5F5`, smoke-2 `#E8E8E8`, white `#FCFCFB`.
- **Text:** light `#EDEBE7`, muted `#A8A6A2`, dark `#1A1A1A`, dark-muted `#5A5A58`.
- **Type:** Geist (400/500/600/700) + Geist Mono (400/500/700). Headings 600, tight negative tracking; eyebrows/labels mono uppercase wide tracking.
- **Radii:** 8 / 14 / 22 / 999px. **Motion:** `--ease: cubic-bezier(0.22,1,0.36,1)`, `--dur: 0.55s`.
- **Copy rule:** no em/en/long dashes in visible copy (recast as periods/commas/colons). Ordinary hyphens in compounds are fine. Interpunct `·` used in some meta lines.

---

## 6. Open TODOs (deferred, not blocking the first build)

- [ ] **Contact backend** — theme-only REST endpoint + `wp_mail()` (preferred, keeps zero-plugin), or decide on a form plugin.
- [ ] **Subdomain architecture** — confirm separate installs vs. multisite vs. coming-soon routes; then repoint the four service cards from anchors to real URLs.
- [ ] **Selected work** — replace the placeholder `PROJECTS` array with real case studies (custom post type or ACF when ready).
- [ ] **Social/profile links** — footer GitHub / LinkedIn / X currently `#`. Provide real URLs.
- [ ] **Contact email** — confirm `hello@serensweb.dev` is the address to display.

---

## 7. Build sequence

1. **`wp-local-stack`** — bring up isolated Docker WordPress at `https://serensweb.test` (project `serens-web`, per §3).
2. **`wp-handoff-bootstrap`** — expand the Twenty Twenty-Five child theme from this handoff: `theme.json` tokens, the `tokens.css → theme.css → child style.css` pipeline, copy prototype CSS verbatim, enqueue `app.js`, write the project `CLAUDE.md`, init the GitHub repo + issue workflow.
3. **`wp-page-from-handoff`** — build the home page section by section (template + one pattern per §5 section).
4. **`wp-issue-workflow`** — every change after bootstrap goes issue → branch → commit (referencing the issue) → PR.

## 8. "First build done" acceptance

- [ ] `https://serensweb.test` loads over HTTPS with no plugin active.
- [ ] All 8 sections render in order, matching the prototype's look at mobile / ≥700c / ≥860c / ≥1040c container widths.
- [ ] Accent switcher works and persists across reload; default is orange.
- [ ] Mobile burger → drawer opens/closes (click, link, Escape).
- [ ] Selected-work cards open the lightbox; Escape/scrim close it.
- [ ] Contact form shows validation errors and the success card (no delivery expected).
- [ ] `prefers-reduced-motion` disables reveal animation.
- [ ] Footer year is the current year.
