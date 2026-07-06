# SerensWeb - Claude Instructions

## Project

SerensWeb (serensweb.com) is the personal portfolio site of Seren van der Merwe, an
AI-augmented freelance web developer. (The separate studio brand is 33FIGs, 33figs.com.)
Dark, modern, high-craft, multi-page: home, projects, playground, about, contact. The home
page scrolls through hero, about, strengths (four service areas: WordPress custom themes,
AI workflows, PWAs / mobile-first, portfolios / corporate), a projects teaser, process, and
contact. Primary audience: founders, marketers, and product teams evaluating an independent
developer. Voice is first person ("I"), AI-augmented, honest, not "senior".

Theme: `serensweb-child` (Twenty Twenty-Five child, FSE block theme)
Local URL: `https://serensweb.test`
Admin: `https://serensweb.test/wp-admin` (user `admin`, password `admin`, local only)

Design source of truth: `docs/handoff/`. Read `docs/handoff/brief.md` (the build contract
and decisions) and `docs/handoff/prototype/` (`page-home.html`, `styles.css`, `app.js`).
Build each page with the `wp-page-from-handoff` skill. The prototype CSS is preserved
verbatim in `assets/css/theme.css`, so patterns only need to output markup with the same
class hooks.

## Workflow

### Issues and Pull Requests
When working on a GitHub issue:
1. Create a feature branch first: `git checkout -b feat/<slug>` (or `fix/<slug>`) before making any changes.
2. Make the change on that branch.
3. Commit with a message that references the issue (e.g. `Closes #5`).
4. Push the branch and open a PR with `gh pr create`, in the same session as the change.

Never commit directly to `main` for issue work. The only direct-to-main commit was the initial bootstrap.

### Local dev
Bring the stack up with `docker compose up -d` from the repo root. The stack is isolated so
it runs alongside other local WordPress projects on this machine:
- Containers are `serens-web`-prefixed: `serens-web-caddy-service`, `serens-web-php-service`, `serens-web-mariadb-service`, `serens-web-redis-service`. (The Docker project is `serens-web`; the theme is `serensweb-child`.)
- Host ports: 80 (http), 443 (https), 3311 (MariaDB, bound to 127.0.0.1). Caddy holds 80/443, so only one local `.test` stack runs at a time. tcg-forensics and wc-pumps also use 80/443, so stop them (`docker compose stop` in their repo) before starting SerensWeb. Data stays isolated either way (prefixed containers, network, and named volumes).
- Run wp-cli inside the PHP container: `docker compose exec serens-web-php-service wp ...`.
- `wp-config.php` reads DB credentials via `getenv()` from the container environment, and sets `FS_METHOD` to `direct`.
- The Caddy local CA must be trusted once per machine (see wp-local-stack docs). The site then validates at `https://serensweb.test`.

## Writing and Copy Rules

These rules apply to all content, code comments, commit messages, and markdown.

**Banned characters:**

| Character           | Unicode | Notes                                              |
|---------------------|---------|----------------------------------------------------|
| em-dash             | U+2014  | Reads as AI text. Rewrite.                         |
| en-dash             | U+2013  | Same.                                              |
| two ASCII hyphens   | n/a     | The pair authors reach for instead of an em-dash.  |
| emoji               | various | Anywhere, including commit messages.               |

Rewrite the sentence using commas, parentheses, colons, or a period and a new sentence.

**No design eyebrows.** An eyebrow is the small uppercase label that sits above a headline
(`.eyebrow`, a theme.json eyebrow font-size slug). The handoff has already removed them;
keep them out. If a section needs that text, fold it into the lede or a real sub-heading.

**No decorative numerals.** The prototype's `01` through `04` numerals on the process steps
and service cards have been removed. If a sequence matters, express it in words.

## Playground and content boundaries

The playground and all site copy show **what** Seren builds, never **how**. His
competitive edge is his method and stack, and the site must not give it away.

- **Show output, not method.** A playground demo may showcase a finished thing (a live
  map, a game, a calculator, an installable app, this site itself). It must never walk a
  visitor through how it was built.
- **No stack or infrastructure reveals.** Do not name the server, cache, or hosting choices
  that make the work fast: no Caddy, Nginx, LiteSpeed, Redis, object caching, or "the
  shortcut most visitors take" style explainers. These are the secret weapons; keep them off
  the public site (page copy, chips, alt text, and JSON-LD schema alike).
- **No build- or workflow-replays.** No reconstructed agentic sessions, real terminal
  commands, prompts, tool calls, branch names, or step-by-step "how an AI-augmented build
  runs" content. The fact that the work is AI-augmented is fine; the playbook is not.
- **No method reveals in FAQ or marketing copy.** Sell the client's outcome (fast, lean,
  easy to maintain, owned code), not the internal technique ("hand-built into the theme",
  "zero-plugin", plugin-free rationale). Phrase benefits, not blueprints.
- **Check the schema too.** When removing method content from a page, remove the matching
  JSON-LD in `includes/schema.php` and `includes/faq.php` in the same change, or search
  engines keep indexing what the page no longer shows.

When a new playground idea or section would explain the method, cut it or reduce it to the
outcome. If unsure whether something is proprietary, treat it as proprietary and ask.

## Theme structure

```
wordpress/wp-content/themes/serensweb-child/
├── style.css           # Theme header only (container query context sits on <body> in theme.css)
├── theme.json          # Tokens (color, typography, spacing, custom), customTemplates, fontFace
├── functions.php       # Thin loader - only requires includes/*.php
├── includes/           # enqueue.php, patterns.php, schema.php, ajax-contact.php (one concern per file)
├── parts/              # header.html, footer.html
├── templates/          # index, page, single, search (page-<slug> added per page)
├── patterns/           # <page>-<section>.php - one section per file (added per page)
└── assets/
    ├── css/            # tokens.css -> theme.css -> child style.css
    ├── js/             # site-chrome.js, reveal.js (global); work.js, contact-form.js, engage.js (guarded)
    ├── fonts/          # Geist-Variable.woff2, GeistMono-Variable.woff2 (self-hosted, OFL bundled)
    └── images/         # logo.svg and other motifs
```

## Client facts

- Personal portfolio of Seren van der Merwe, an AI-augmented freelance web developer (the studio brand 33FIGs, 33figs.com, is separate). Voice: first person ("I"), confident, precise, low-hype, AI-augmented. Do not call him "senior" (real timeline is roughly 2-3 years).
- Four service areas: WordPress Custom Themes, AI Workflows, PWAs / Mobile-First, Portfolios / Corporate. Headless commerce / Shopify Hydrogen was dropped as unprovable. The old serensweb.dev subdomain idea is parked: the strength cards link to `/contact`, each carrying a `data-topic` (e.g. `WordPress Custom Themes`, `AI Workflows`) that the contact prefill reads.
- Signature feature: a visitor accent switcher (orange default, plus blue, purple, green) that re-themes the whole site live and persists per visitor in localStorage. Single control, in the header.
- Engagement switcher in the contact section: a Full-time / Part-time / Project control that echoes the accent switcher. The chosen mode drives a reactive hint, the contact-form message prefill, and the WhatsApp deep-link text. Logic in `assets/js/engage.js`, guarded on `.engage-switch`.
- Contact form: theme-only REST endpoint `serensweb/v1/contact` in `includes/ajax-contact.php` validates server-side and sends via `wp_mail()`, zero plugins. No SMTP is configured (local or production), so the form shows its success state but does NOT deliver email yet. Wiring delivery (a mailto handoff, or Gmail SMTP via WordPress's built-in PHPMailer, both plugin-free) is a tracked TODO. Until then the working contact channels are the WhatsApp button (number `27769420144`, set in `enqueue.php` as `swEngage.whatsapp`) and the `vandermerweseren@gmail.com` mailto link.
- Contact band has a Download CV button linking to `assets/docs/seren-cv.pdf` (self-hosted, no plugin). The real CV is in place; to update it, replace the file keeping the same name.
- Target zero plugins. Redis is wired (`WP_REDIS_HOST` is set) but the object-cache drop-in is not installed, so the active-plugin count stays at zero.
- Contact email shown: vandermerweseren@gmail.com. Footer social links and the schema `sameAs` point at the real profiles: `github.com/serenvdmerwe` and `linkedin.com/in/serenvdmerwe`. The X/Twitter icon was removed (not used).

## What NOT to do

- Don't commit to `main` directly (except the initial bootstrap commit).
- Don't install Kadence or any non-FSE theme.
- Don't translate the prototype CSS into block styles. It lives in `assets/css/theme.css` verbatim; patterns output markup that the CSS finds by class.
- Don't add an eyebrow token to theme.json or an eyebrow label above a heading in patterns.
- Don't reintroduce the `01` through `04` numerals on steps or cards.
- Don't load fonts from a CDN. Geist and Geist Mono are self-hosted in `assets/fonts/`.
- Don't reintroduce "studio", "senior", headless commerce, or fabricated metrics. This is Seren's personal AI-augmented portfolio; copy is first person and only makes provable claims.
- Don't reveal method or stack. No build/workflow replays, no server/cache/hosting names (Caddy, Nginx, Redis, object caching), no "how I build it" explainers or plugin-free/hand-built method reveals in copy, chips, or schema. See "Playground and content boundaries". Show what, never how.
