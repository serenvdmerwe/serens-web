# SerensWeb build brief (Claude Design handoff)

This folder is a TurboPress design handoff. Drop it at the repo root as `docs/handoff/`, then run the `turbopress-wp` plugin's `wp-handoff-bootstrap` skill. The prototype CSS in `prototype/styles.css` is the source of truth for all values and gets copied verbatim into the child theme's `assets/css/theme.css`; the class hooks in `prototype/page-home.html` must survive into the block patterns.

This handoff has already been put through the TurboPress content rules: no em-dashes, en-dashes, or double-hyphens; no emoji; no eyebrows; and the decorative section numerals have been removed (see "Decisions" at the end).

## 1. Client and theme identity

- **Client name:** SerensWeb
- **Theme slug:** `serensweb-child` (Template: `twentytwentyfive`, Author: TurboPress, Author URI https://www.turbopress.pro)
- **Brand summary:** SerensWeb is a one-person freelance web-development studio building fast, modern, conversion-focused sites and web apps, from headless commerce to AI-powered tools. Independent, end to end, shipped with care.

## 2. Design tokens

`prototype/styles.css` `:root` is the source of truth. Lift these into `theme.json` (`settings.color.palette`, `typography`, `spacing`, `custom`) and mirror them in `assets/css/tokens.css`.

**Colors**

| Token | Value | Role |
|---|---|---|
| primary (accent, default) | `#F6821F` | brand accent, retheme knob |
| primary alt: blue | `#2F73E8` | accent option |
| primary alt: purple | `#8B5CF6` | accent option |
| primary alt: green | `#1FA463` | accent option |
| primary-strong | `#E5751A` | hover / pressed |
| primary-soft | accent at 16% | soft fills |
| primary-faint | accent at 8% | faint glows |
| dark | `#1A1A1A` | primary dark surface |
| dark-2 | `#2A2A2A` | secondary dark surface |
| dark-3 | `#232323` | card on dark |
| smoke | `#F5F5F5` | light background |
| smoke-2 | `#E8E8E8` | light borders / cards |
| white | `#FCFCFB` | white |
| text-light | `#EDEBE7` | text on dark |
| text-muted | `#A8A6A2` | muted on dark |
| text-dark | `#1A1A1A` | text on light |
| text-dark-2 | `#5A5A58` | muted on light |
| hairline-dark | `rgba(255,255,255,.10)` | lines on dark |
| hairline-light | `rgba(0,0,0,.09)` | lines on light |

Set `defaultPalette: false` and `defaultGradients: false` so the brand palette is the only option.

**Typography:** Geist (400/500/600/700) for sans, Geist Mono (400/500/700) for mono and metadata. Headings weight 600 with tight negative tracking. Do not add an `eyebrow` font-size slug. Fluid sizes are defined inline in the prototype CSS via `clamp()`; build a matching `fontSizes` scale (body 16px fixed, plus fluid h1 / h2 / display).

**Radii:** sm 8, md 14, lg 22, pill 999. **Spacing:** `--section-pad: clamp(64px,9cqi,132px)`, `--gutter: clamp(20px,5cqi,56px)`, `--maxw: 1240px`. **Motion:** `--ease: cubic-bezier(0.22,1,0.36,1)`. Plus the two `--shadow-card` / `--shadow-pop` values in `:root`. These belong in `settings.custom` (`radius`, `shadow`, `motion`, `maxWidth`, `gutter`).

**Layout for theme.json:** `contentSize` 768px, `wideSize` 1240px.

## 3. Fonts

Self-host Geist and Geist Mono in `assets/fonts/` and declare them via `theme.json` `fontFace` (or `@font-face` at the top of `tokens.css`). Do not load from the Google Fonts CDN. The prototype `page-home.html` has had the CDN `<link>` removed; the font binaries are not bundled in this handoff, so during bootstrap drop the Geist + Geist Mono files (variable `.ttf` or static weights 400/500/600/700) into `assets/fonts/` and wire the `fontFace` entries.

## 4. Page list

This site is a single page: **home** (a one-pager; all primary nav is in-page anchors). Add one `customTemplates` entry to `theme.json`: `{ "name": "page-home", "title": "Home", "postTypes": ["page"] }`. Header and footer are template parts.

## 5. Home page sections (in order)

The prototype is `prototype/page-home.html`. Each top-level `<section>` has a stable `id` and class so it maps cleanly to one block pattern. Header and footer become `parts/header.html` and `parts/footer.html`.

1. **header** (part) `header.header`: sticky bar; wordmark (`.brand`); desktop nav (Work, Strengths, Process, About); the brand **accent switcher** (`.accent-switch--header`, four `.accent-dot` buttons) which is a real visitor feature, keep it; primary CTA "Let's talk"; mobile burger + `.drawer`.
2. **hero** `section.hero#... ` (`.hero.section--dark`): H1 "Websites that perform as well as they look" (the word "perform" is accent-colored via `<em>`); sub-paragraph; two CTAs ("Start a project", "View selected work"); three-stat row (8+ yrs, 40+ projects, 98 avg Lighthouse); decorative code-card on the right (`aria-hidden`).
3. **about** `section#about` (`.section--light`): large statement paragraph; supporting paragraph; "Core stack" sub-heading plus the chip row (`.stack`): TypeScript, React, Next.js, Astro, Node, WordPress, Tailwind, Shopify Hydrogen, Postgres, Edge / Workers.
4. **strengths** `section#strengths` (`.section--dark`): heading "Specialised where it matters." plus lede; FOUR cards (`a.fcard`), each linking to its own subdomain (new tab), each showing title, one-line description, and a mono subdomain URL with the subdomain segment accent-colored (`.fcard__url` / `.fcard__sub`) and a hover arrow:
   - Custom E-Commerce and Headless Commerce (`https://commerce.serensweb.dev`)
   - AI-Integrated Web Apps (`https://ai.serensweb.dev`)
   - Progressive Web Apps and Mobile-First Sites (`https://apps.serensweb.dev`)
   - Interactive Portfolios and Corporate Sites (`https://studio.serensweb.dev`)
5. **work** `section#work` (`.section--light`): heading "A few recent builds." plus lede; a grid of placeholder project cards injected by `app.js` from a `PROJECTS` array, opening a lightbox modal. The `PROJECTS` data is placeholder; convert to real case studies later (a CPT or ACF). Keep the card + lightbox behavior.
6. **process** `section#process` (`.section--dark2`): heading "A clear path, every time." plus lede; four steps (Discover, Design, Build, Launch and support) each with body copy.
7. **contact** `section#contact` (`.section--dark`): heading "Have a project in mind?"; meta list (hello@serensweb.dev, "Remote, working worldwide", "Currently booking for Q3"); a contact FORM (name / email / project details) with client-side validation and a success state. Needs a server-side handler, so bootstrap should add `includes/ajax-contact.php`.
8. **footer** (part) `footer.footer`: brand block plus tagline; Sitemap column (Work / Strengths / Process / About anchors); Services column linking to the four subdomains; Contact column (mailto plus anchor); copyright with current year (set in JS); social icon links (GitHub / LinkedIn / X, currently placeholder `#`, replace with real profile URLs).

## 6. Behaviors to preserve (all in `prototype/app.js`, vanilla, no build step)

Bootstrap should move these into `assets/js/` modules and enqueue them:

- header shrink on scroll (`.is-scrolled`)
- mobile burger drawer (open / close, Escape)
- accent switcher: writes `--color-primary` and `--color-primary-strong` on `:root` and persists to `localStorage`; restores on load
- smooth-scroll for in-page anchors with sticky-header offset
- scroll reveal (rect-based) respecting `prefers-reduced-motion`
- project lightbox (open from card, close on scrim / button / Escape)
- contact-form validation plus success swap
- current-year injection

## 7. Optional includes implied by features

- Contact form present, so create `includes/ajax-contact.php`.
- No custom post types, no search scope filter, and no legacy redirects at launch.

## 8. SEO / schema hints (`includes/schema.php`)

Organization plus WebSite JSON-LD. Organization name SerensWeb; a freelance web developer; service area "remote, worldwide"; contact email hello@serensweb.dev. Fill the real social profile URLs into `sameAs` when available.

## 9. Subdomain architecture (open decision, not a built feature)

The four service areas (`commerce` / `ai` / `apps` / `studio` `.serensweb.dev`) are an information-architecture intent, not yet built. A real subdomain means one of: a WordPress multisite subdomain network, four separate WordPress installs, or routed landing sites. Until the model is chosen, the four service cards and the footer Services column are plain outbound links to the intended subdomain URLs (`target="_blank" rel="noopener"`). Each subdomain would get its own handoff later. Confirm the model with the client before wiring these live; if the subdomains do not exist yet, consider pointing them at on-page anchors or "coming soon" routes.

## 10. Decisions already applied to this handoff

- **Eyebrows removed.** The original prototype had a small uppercase label above every section heading (Hero "Freelance Web Developer", About "About" and "Core stack", Strengths "What I build", Work "Selected work", Process "How I work", Contact "Contact"). Per the TurboPress style guide these were dropped, except "Core stack" which was promoted to a normal sub-heading (`.about__stack-title`). The `.eyebrow` CSS has been removed.
- **Section numerals removed.** The service cards' 01 to 04 numerals (`.fcard__n`) and the process steps' "0X /" prefixes (`.step__n`) were removed, along with the small index numerals in the mobile drawer. If you want to re-emphasise the process as an ordered sequence, that is a deliberate choice to make with the client rather than restoring decorative numerals by default.
- **Design-time tooling stripped.** The React/Babel Tweaks panel, the viewport-preview slider, and the `.vp-host` preview wrapper are not in this handoff. Container-query responsiveness is driven by `container-type: inline-size` on `body` (see `styles.css`). The redundant footer and hero accent switchers were dropped; the single header switcher is the one visitor-facing control.
