# SerensWeb - Claude Instructions

## Project

SerensWeb is a single-page marketing site for a one-person freelance web development
studio. Dark, modern, high-craft, one continuous scroll: header, hero, about, strengths
(four service areas), selected work, process, contact, footer. Primary audience: founders,
marketers, and product teams evaluating a senior independent developer.

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
    ├── js/             # site-chrome.js, reveal.js (global); work.js, contact-form.js (guarded)
    ├── fonts/          # Geist-Variable.ttf, GeistMono-Variable.ttf (self-hosted, OFL bundled)
    └── images/         # logo.svg and other motifs
```

## Client facts

- One-person freelance web development studio. Voice: confident, precise, low-hype, senior.
- Four service areas, intended to live on their own subdomains of serensweb.dev (commerce, ai, apps, studio). The subdomains do not exist yet. The cards and footer Services links currently point to the intended outbound URLs (new tab); confirm the subdomain model with the client before launch. Tracked TODO.
- Signature feature: a visitor accent switcher (orange default, plus blue, purple, green) that re-themes the whole site live and persists per visitor in localStorage. Single control, in the header.
- Contact form: theme-only REST endpoint `serensweb/v1/contact` in `includes/ajax-contact.php` validates server-side and sends via `wp_mail()`, keeping the plugin count at zero. Local dev has no SMTP, so mail does not deliver locally; configure SMTP or a transactional provider in production. Tracked TODO.
- Target zero plugins. Redis is wired (`WP_REDIS_HOST` is set) but the object-cache drop-in is not installed, so the active-plugin count stays at zero.
- Contact email shown: hello@serensweb.dev. Footer social links are placeholders to replace (also the schema `sameAs`).

## What NOT to do

- Don't commit to `main` directly (except the initial bootstrap commit).
- Don't install Kadence or any non-FSE theme.
- Don't translate the prototype CSS into block styles. It lives in `assets/css/theme.css` verbatim; patterns output markup that the CSS finds by class.
- Don't add an eyebrow token to theme.json or an eyebrow label above a heading in patterns.
- Don't reintroduce the `01` through `04` numerals on steps or cards.
- Don't load fonts from a CDN. Geist and Geist Mono are self-hosted in `assets/fonts/`.
