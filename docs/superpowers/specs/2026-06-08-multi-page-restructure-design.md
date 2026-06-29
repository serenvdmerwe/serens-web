# Multi-page restructure (Projects, Playground, About)

Date: 2026-06-08
Branch: `feat/multi-page-restructure`

## Why

The site is a polished one-pager, but a freelance developer's portfolio needs real,
indexable depth: each project on its own URL (the biggest SEO win), a place for personal
experiments, and a proper About page. This spec turns the single page into a small
multi-page site while keeping the home page people already like as the landing.

## Dependencies and git

- This design builds on the work-with-me changes (the contact band with the engagement
  switcher, WhatsApp, CV; the real footer social links). Those live in **PR #6**.
- Implementation must happen **after PR #6 is merged to `main`**. This branch was cut from
  `main`, so before building, merge #6 and rebase this branch onto the updated `main` (or
  recut it). The spec/plan docs themselves do not depend on #6.

## Decisions (locked)

- Design the whole restructure as one spec; phase the implementation.
- Projects are a custom post type; the system ships **empty**, content added later in WP admin.
- Florida Risk Explorer on Playground is a **static preview image linking out** to the live
  map (image and URL are placeholders this round).
- Home stays the rich landing; its Work section becomes a featured-projects teaser linking
  to `/projects`, and its About section shrinks to an intro linking to `/about`.
- Contact gets **no separate page**: nav points to `/#contact` (the home contact band).
- Strengths and Process **drop out of the global nav** (they remain as home sections).
- Domain and shown email are **unchanged** (decided at deploy); keep serensweb.dev refs.
- Zero plugins; project fields come from the block editor, not custom meta.

## A. Information architecture and navigation

Routes:
- `/` home (rich landing; Work and About become teasers that link out)
- `/projects` project archive (lists all projects)
- `/projects/<slug>` single project
- `/playground` Lab page
- `/about` About page
- Contact: no route; `/#contact` reaches the home contact band from any page.

Navigation lives in the shared `parts/header.html` and `parts/footer.html`, so editing it
updates every page.
- Header nav: **Work** (`/projects`), **Playground** (`/playground`), **About** (`/about`),
  then the accent switcher (kept) and the "Let's talk" CTA (`/#contact`). The mobile drawer
  mirrors these.
- All cross-page links are root-relative (`/projects`, `/#contact`) so they resolve from any
  page. The current-page link gets an `aria-current="page"` affordance.
- Footer: the Sitemap column becomes the page nav (Work, Playground, About, Contact). The
  Services column is repurposed to short service labels that link to `/projects`. The
  Contact column keeps the mailto plus `/#contact`.

## B. Projects system (the SEO core)

**Custom post type** `project`, registered in a new `includes/cpt-projects.php` (required
from `functions.php`, matching the thin-loader pattern):
- `public`, `has_archive: true`, `show_in_rest: true` (block editor), `menu_icon`
  `dashicons-portfolio`, `supports`: title, editor, excerpt, thumbnail, and `post_tag` (for
  tech-stack chips).
- `rewrite: [ 'slug' => 'projects', 'with_front' => false ]` so URLs are `/projects/<slug>`
  and the archive is `/projects`.
- A hierarchical taxonomy `project_type` (terms like "Headless Commerce", "AI Web App")
  for the category label shown on cards; `show_in_rest: true`, rewrite slug `project-type`.
- Rewrite rules are flushed on `after_switch_theme` (register the CPT/taxonomy first, then
  `flush_rewrite_rules()`); re-saving Permalinks in wp-admin is the documented fallback.

**Fields come from the editor, not custom meta** (keeps it plugin-free and fully editable):
- Story = post content (composed with blocks). Card visual = Featured Image. Blurb =
  Excerpt. Category = a `project_type` term. Tech chips = `post_tag` terms.
- Structured extras are block patterns the author drops into content:
  - `project-metrics`: a three-up stat row (value + label), echoing the prototype's lightbox
    metrics.
- No ACF, no custom meta boxes, no meta fields to maintain.

**Templates** (FSE block templates in `templates/`, standard hierarchy, no customTemplates
entry needed):
- `archive-project.html`: header part, a page-header pattern (heading "Selected work" plus a
  lede), a `core/query` block (`postType: project`, not inherited) whose post template is the
  shared project card, pagination, and a no-results block as the empty state, then footer part.
- `single-project.html`: header part, a page header (project title, `project_type` term,
  featured image), `core/post-content`, a `post_tag` chip row via `core/post-terms`, a
  "Back to all projects" link plus a contact CTA to `/#contact`, then footer part.

**Patterns:**
- `projects-card`: the card markup (featured-image link, type label, title link, excerpt),
  reusing the existing `.proj` styling from theme.css where it fits. The same markup is used
  as the post template inside both the archive query and the home teaser query.
- `projects-archive-header` and `project-single-header`: small page-header patterns.
- `project-metrics`: the author-inserted stat row described above.

**Home "Work" section** (`patterns/home-work.php`): replace the work.js placeholder grid and
lightbox with a `core/query` (`postType: project`, latest 3, newest first) rendering the
`projects-card`, plus a "View all projects" link to `/projects`. When there are no projects
yet, show a short empty state. Retire `assets/js/work.js` (dequeue in `enqueue.php`) and
remove the lightbox markup.

## C. Playground / Lab page

- A Page titled "Playground" (slug `playground`) assigned a `page-playground` template
  (added to `theme.json` `customTemplates`), which renders a `playground` pattern.
- Pattern content: a page header framed "Things I build and explore for fun"; a hero block
  for the Florida Risk Explorer (a large **preview image placeholder**, a caption, and an
  "Open the live map" button linking to a **placeholder URL**, `target="_blank"`); then an
  extensible experiments grid (cards with title, blurb, outbound link) seeded with the map
  card plus two editable placeholder slots.

## D. About page

- A Page titled "About" (slug `about`) assigned a `page-about` template (added to
  `theme.json` `customTemplates`), rendering an `about` pattern.
- Pattern content: a page header; the studio statement and supporting copy promoted from the
  home About section; a **photo placeholder** image slot; a short personality paragraph; the
  Core stack chip row (moved here from home); a clear link to `/playground`; and a contact
  CTA to `/#contact`.
- The home About section (`patterns/home-about.php`) shrinks to a 2-3 line intro plus a
  "More about me" link to `/about`; the longer copy and the stack chips move to the About page.

## E. Contact

No new page or pattern. The home contact band (engagement switcher, WhatsApp, CV, form from
PR #6) stays the single contact hub. Nav and CTAs point to `/#contact`, which loads the home
page and scrolls to the contact section from anywhere.

## New and changed units

- New: `includes/cpt-projects.php` (CPT + taxonomy + rewrite flush); require it in
  `functions.php`.
- New templates: `templates/archive-project.html`, `templates/single-project.html`,
  `templates/page-playground.html`, `templates/page-about.html`.
- New patterns: `patterns/projects-card.php`, `patterns/projects-archive-header.php`,
  `patterns/project-single-header.php`, `patterns/project-metrics.php`,
  `patterns/playground.php`, `patterns/about.php`.
- New CSS: project card/archive/single layout, page-header, playground hero and grid, about
  layout and photo, all appended to the child `style.css` (theme.css stays verbatim).
- Edited: `theme.json` (customTemplates for page-playground and page-about),
  `parts/header.html` and `parts/footer.html` (nav), `patterns/home-work.php` (teaser query),
  `patterns/home-about.php` (shrink plus link), `includes/enqueue.php` (dequeue work.js).
- Retired: `assets/js/work.js` and the lightbox markup.
- WP content created at build time via wp-cli: the Playground and About pages with their
  templates assigned, and the `project_type` terms; no project posts (added later).

## Content rules

All copy follows the project rules: no em or en dashes, no double hyphens, no emoji, no
eyebrows, no decorative numerals.

## Out of scope (this round)

- Real project content, real images, the live map embed (link-out only).
- Domain or shown-email changes (deferred to deploy).
- Project filtering UI on the archive, custom meta boxes or ACF, comments on projects.
- Any SEO plugin; rely on per-page title/excerpt and the existing JSON-LD schema.

## Open follow-ups (you provide later)

- Project posts (title, excerpt, featured image, content, type, tags) added in WP admin.
- The Florida Risk Explorer preview image and its live URL.
- The About photo and final personality copy.
- The canonical domain and shown email, settled at deploy.
