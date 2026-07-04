# Playground batch 5: token re-themer and build replay

Date: 2026-07-04. Issue: #36. Branch: `feat/playground-batch-5`.

## Goal

Build the last two experiments from the vetted shortlist. The re-themer proves
design-system thinking for the WordPress Custom Themes service area and scales up the
site's signature accent switcher. The build replay is the missing proof for the AI
Workflows service area: it shows, step by step, how a real feature on this site was
actually built with Claude.

## Delivery model (both)

Identical to batches 2 through 4:

- One self-contained HTML file per demo in
  `wordpress/wp-content/themes/serensweb-child/assets/playground/`.
  Inline CSS and JS, zero frameworks, zero plugins, zero build step.
- Linked from a `play-card` on the playground page (`patterns/playground.php`),
  opening in a new tab (`target="_blank" rel="noopener"`).
- Mobile friendly from the start.
- Writing rules apply: no em or en dashes, no double hyphens in prose, no emoji,
  first person voice where copy speaks as Seren, only provable claims.
- Fonts: system stacks, or the theme's self-hosted Geist and Geist Mono referenced
  by absolute path (`/wp-content/themes/serensweb-child/assets/fonts/`). No CDNs.
- Each demo has its own distinct aesthetic.
- Both added to the playground ItemList in `includes/schema.php` and given a new
  `play-card__viz` hue variant in `theme.css`.

## Demo 1: Design-token re-themer (`token-rethemer.html`)

One realistic mock landing page (a fictional, neutral brand: a field guide app called
Fernway, with a nav, hero, three feature cards, a stat row, a pricing card, and a
footer) rendered entirely from CSS custom properties, next to a token panel that edits
those properties live.

Tokens, grouped in the panel:

- Color: accent hue (0 to 360 slider), accent saturation (slider), and a
  light or dark mode toggle. Surfaces, borders, and text colors derive from the mode
  and accent via `oklch()` with a static `hsl()` fallback path chosen at load.
  If `CSS.supports('color', 'oklch(50% 0.1 200)')` fails, the demo derives colors in
  JS instead; either way the mock page only ever reads custom properties.
- Typography: font pairing (four presets: Grotesk pairs Geist with Geist,
  Editorial pairs Georgia display with system body, System uses system-ui throughout,
  Mono pairs Geist Mono display with Geist body) and type scale ratio
  (slider, 1.125 to 1.333; heading sizes are computed as powers of the ratio).
- Shape: corner radius (0 to 24 px) and border width (1 to 3 px).
- Rhythm: density (compact, comfortable, airy) as a spacing multiplier.

Above the panel, five named presets set every token at once (for example Midnight,
Paper, Terminal, Sorbet, Concrete), plus a shuffle button that randomizes tokens
within legible bounds. A live readout computes the contrast ratio of body text on the
page background and labels it with its WCAG verdict; shuffle rerolls until body copy
passes AA, and manual edits that fail are flagged rather than blocked, with a one line
note that the flag is the point: tokens are power tools.

An export drawer prints the current tokens two ways, with a copy button each: a CSS
`:root` block, and a WordPress `theme.json` fragment (palette entries plus fontSizes),
which is the tie back to how this very site is built. State persists in localStorage;
a reset link clears it.

Chrome aesthetic: a design-tool inspector look. Near-black chrome, hairline borders,
Geist Mono panel labels, the mock page sitting on a subtle checkerboard canvas like an
artboard. Desktop: panel left (fixed width), artboard right. Under 860 px: the panel
collapses to a bottom sheet with the artboard above it.

## Demo 2: Agentic build replay (`build-replay.html`)

A player that replays how the Hurricane Tracks Time Machine was built (issue #28,
PR #29, July 2026), reconstructed from the session that built it: the spec in this
repo, the commands actually used, and the shipped code. The intro says exactly that,
in first person, and notes that times are compressed and the log is condensed. No
fabricated metrics, no speed claims.

Layout: a session log pane (the star) plus a status rail.

- The log types out events in order: Seren's prompts, Claude's actions named by tool
  (reading the spec, searching the codebase, editing files, running commands), real
  command lines (docker compose exec, wp pattern checks, curl render checks, the PHP
  HURDAT2 processing script), their condensed output, a failing check, the fix, the
  passing rerun, commits, and the PR. Each event is a typed line or block with an
  actor tag (Seren, Claude, terminal). About fifty events across the whole run.
- The status rail shows the current phase (Brief, Spec, Data, Build, Verify, Ship)
  as a stepped progress list, a small file tree where touched files light up, and a
  commit strip that grows as commits land.
- Controls: play or pause, speed (1x, 2x, 4x), a scrubber bound to the event index,
  and phase chips that jump. With `prefers-reduced-motion`, autoplay and the typing
  effect are disabled and the same controls step through instantly rendered events.

The event script is a single JSON array embedded in the page: each event carries
phase, actor, kind (prompt, action, command, output, note, commit), text, optional
files, and a duration weight. The player is a small state machine over that array, so
the content is editable without touching the logic.

Aesthetic: editor dark but clearly distinct from the phosphor terminal of the typing
test. Deep slate blue chrome, one warm amber accent for phase and commit markers, Geist
Mono for the log, Geist for the rail and intro. A thin status bar footer like an IDE.

## Playground page

`patterns/playground.php` gains one card each: the re-themer under tools, the replay
under explainers. The explainers section blurb widens from page loading to also cover
how an AI-augmented build runs. `theme.css` gains two hue variants: an iris multi-hue
gradient for the re-themer card, an ink slate-and-amber gradient for the replay card.
`includes/schema.php` adds both to the ItemList.

## Testing

Per demo: banned-character lint over changed files, JS syntax check with node inside
the PHP container, curl render check on serensweb.test when the stack is up, grep for
key hooks (token panel ids, replay controls, localStorage keys), manual browser pass
by Seren on the PR. Production deploy happens after merge, per the usual scp plus
cache purge routine.

## Out of scope

Social card previewer and hype-checker (still on the shortlist), WordPress-pins map
and offline Cape Town guide (parked on content decisions), any server-side piece for
either demo, and any change to the site accent switcher itself.
