# Work-with-me rebuild (subdomains parked)

Date: 2026-06-08
Branch: `feat/work-with-me`

## Why

The four `*.serensweb.dev` subdomains are parked indefinitely, so the strengths cards
and the footer Services column currently link to pages that do not exist. At the same
time the site needs to state, on this one page, the ways a visitor can engage the studio:
hire full-time, part-time, or project-by-project, plus download a CV and reach out over
WhatsApp. The home page itself is finished and liked; every change here is a rewired
link or a new element placed inside the existing dark contact band. No section is
redesigned.

## Decisions (locked)

- Engagement switcher: full reactive version (live re-framing + prefill).
- Download CV: lives in the contact band, surfaced most when Full-time / Part-time is chosen.
- Strengths cards: anchor to `#contact`, carrying their service area as intent.
- Footer Services column: in-page anchors instead of subdomain links.
- Form delivery (mailto vs WhatsApp vs SMTP), the real WhatsApp number, the real CV PDF,
  and the destination email are deferred to a later "fiancée round". This build leaves
  clearly-marked placeholders for each. The existing REST endpoint in
  `includes/ajax-contact.php` is left untouched and dormant.

## Scope

### 1. Strengths cards anchor to contact
File: `patterns/home-strengths.php`

- Each `a.fcard` `href` changes from its subdomain URL to `#contact`.
- Drop `target="_blank" rel="noopener"` (now an in-page anchor).
- Add `data-topic="<service area>"` to each card (e.g. `data-topic="AI-Integrated Web Apps"`).
- The mono `.fcard__url` line keeps its visual slot but its text changes from
  `commerce.serensweb.dev` to a short action label, e.g. `Discuss this` followed by the
  existing hover arrow. The `.fcard__sub` accent span is no longer meaningful; the label
  can keep a single accent word for visual continuity.
- Lede reworded to drop the "dedicated home on its own subdomain" promise.

### 2. Engagement switcher (reactive)
File: `patterns/home-contact.php` (+ new JS, + child CSS)

Markup: a segmented control placed in the contact lead column, above or beside the
meta list. It mirrors the accent-switcher semantics so it reads as a sibling control.

- Container `.engage-switch`, group `.engage-opts`, three buttons `.engage-opt` with
  `type="button"`, `aria-pressed`, and `data-mode="fulltime|parttime|project"`.
- Default selected mode: `project`.
- The contact grid carries the current mode as `data-engage="project"` (updated by JS).
- No persistence across visits (per-visit intent only; keeps it simple).

Reactive behavior (driven by `data-engage` on the contact grid, styled in child CSS):

- `project`: the project-details form is the emphasized element; CV button is present
  but quiet.
- `fulltime` / `parttime`: the Download CV button is emphasized; the form is still
  available but de-emphasized.

The chosen mode is the single source of truth feeding the WhatsApp and email prefill.

### 3. WhatsApp click-to-chat
File: `patterns/home-contact.php` (+ JS)

- A WhatsApp action button in the contact band (reuse `.btn`; ghost or a `.btn--whatsapp`
  variant in child CSS).
- Links to `https://wa.me/<SW_WHATSAPP>?text=<encoded message>`.
- `SW_WHATSAPP` is a single placeholder constant in the JS module
  (value `__WHATSAPP_NUMBER__` until the fiancée round).
- The `text` is built from the engagement mode, plus the service topic when the visitor
  arrived from a strengths card (see prefill below). With JS off, the button falls back
  to a plain `wa.me/<number>` link (generic chat).

### 4. Download CV
File: `patterns/home-contact.php` (+ placeholder asset)

- A `Download CV (PDF)` button (reuse `.btn--ghost`) inside a `.contact__cv` wrapper in
  the contact band, next to the engagement switcher.
- `href` points at a self-hosted file: `assets/docs/seren-cv.pdf`, with the `download`
  attribute. A placeholder PDF (or a committed `.gitkeep` plus a TODO) stands in until the
  real CV is dropped in. No plugin.

### 5. Footer Services column
File: `parts/footer.html`

- The four subdomain links become in-page anchors. Recommended mapping: all four point to
  `#contact` (or `#strengths`), labels unchanged (Headless commerce, AI web apps, PWAs,
  Marketing sites). Drop `target="_blank" rel="noopener"`.

## New units

- `assets/js/engage.js`: guarded module (runs only when `.engage-switch` is present),
  responsible for: tracking the selected mode, toggling `data-engage` on the contact grid,
  building the WhatsApp/email prefill strings, and reading `data-topic` from a clicked
  strengths card (stashed on click, consumed on arrival at `#contact`). Enqueued in
  `includes/enqueue.php` alongside the other guarded scripts.
- Child `style.css` additions: `.engage-switch` / `.engage-opts` / `.engage-opt`, the
  `[data-engage="..."]` emphasis rules, `.contact__cv`, and any `.btn--whatsapp` variant.
  New component CSS goes in the child `style.css` (end of the
  tokens.css -> theme.css -> child pipeline). `theme.css` stays verbatim prototype CSS.

## Prefill model

One mode value (`project` default) maps to one message template, e.g.:

- project: "Hi Seren, I have a project in mind: ..."
- fulltime: "Hi Seren, I'd like to discuss a full-time role."
- parttime: "Hi Seren, I'd like to discuss a part-time engagement."

When the visitor arrived via a strengths card, the stashed `data-topic` is appended:
"... (interested in AI-Integrated Web Apps)". The same string feeds both the WhatsApp
`text` param and (later) the email body.

## Graceful degradation

- JS off: form still posts to the dormant REST endpoint, WhatsApp link opens a generic
  chat, CV still downloads, strengths cards still scroll to `#contact`. The switcher
  shows its default (`project`) state with no reactive re-framing.
- `prefers-reduced-motion`: no new animation is introduced beyond what theme.css already
  honours.

## Out of scope (this round)

- Choosing and wiring the real form delivery mechanism.
- The real WhatsApp number, CV PDF, and destination email address (placeholders only).
- Any change to `includes/ajax-contact.php`.
- Reviving or rebuilding the subdomains.

## Content rules

All copy follows the project rules: no em/en dashes, no double hyphens, no emoji, no
eyebrows, no decorative numerals.

## Files touched

- `patterns/home-strengths.php`
- `patterns/home-contact.php`
- `parts/footer.html`
- `assets/js/engage.js` (new)
- `includes/enqueue.php` (enqueue the new module)
- `assets/css/.../style.css` (child theme style.css: new component CSS)
- `assets/docs/` (placeholder CV location)
