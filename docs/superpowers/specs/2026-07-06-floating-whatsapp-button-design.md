# Floating WhatsApp button, design

Date: 2026-07-06
Status: approved

## Goal

Add a persistent floating WhatsApp control to every page of serensweb.com, so a visitor
can reach out from anywhere, not only from the contact band. It reuses the existing
click-to-chat deep link and the engagement-switcher message logic.

## Appearance

- A round WhatsApp-green (`#25D366`) icon button, roughly 52px, with a dark-green
  (`#07251a`) WhatsApp glyph, matching the existing inline `.btn--whatsapp`.
- Directly below the icon, a small caption pill reading "Chat on WhatsApp": small type
  (about 11px), tight padding, a subtle green-tinted background. The caption is
  deliberately small, it must not read as a large button. The icon is the tap target
  and visual anchor; the caption sits beneath it, centred.
- Fixed to the bottom-right corner on every page.

## Placement in the stack

- `z-index: 50`, chosen from the existing stacking contract so overlays still cover it:
  drawer is 55, header 60, lightbox 90. The float sits above page content but beneath all
  of those.
- Hidden while the mobile menu drawer is open (`body.menu-open .wa-float`), since the
  drawer is only 96% opaque and the green would otherwise bleed through.

## Behaviour (message)

The button's `href` is a WhatsApp deep link, `https://wa.me/<number>?text=<message>`.
The message is remembered from the visitor's last choice:

- When a visitor picks an engagement mode on `/contact`, persist that mode to
  `localStorage['sw-engage']`. Topic already stashes to `sessionStorage['sw-topic']`
  when a strengths card is clicked on the home page.
- On load, on every page, build the float's message from the remembered mode + topic via
  the existing `messageFor()` helper.
- If the visitor has touched neither the switcher nor a strengths card, fall back to a
  generic greeting: "Hi Seren, I saw your site and wanted to get in touch."
- On `/contact`, the existing `apply()` already rewrites `#waLink`; it also rewrites the
  float (`#waFloat`) so both stay in sync live as the visitor flips modes.

## Implementation surfaces

- `parts/footer.html`: the anchor markup, appended after `</footer>` so it renders on
  every template. The number is hardcoded in the `href` as a no-JS fallback (the link
  still opens the chat if JS fails; JS only upgrades it with a prefilled message). This
  matches how the number already appears literally elsewhere in the theme.
- `assets/css/theme.css` is verbatim prototype CSS and is not touched. New styles for
  `.wa-float` go in the child `style.css`, alongside the existing `.btn--whatsapp` rules.
- `assets/js/engage.js`: extend the always-runs section (before the `/contact`-only early
  return) to set the float href on load everywhere, and persist the chosen mode.

## Accessibility

- The control is an `<a>` with `target="_blank" rel="noopener"` and an `aria-label`
  ("Chat with Seren on WhatsApp"). The glyph is `aria-hidden`.
- Icon tap target is at least 44px. A `:focus-visible` ring is provided, and the hover
  lift is guarded by `prefers-reduced-motion`.

## Out of scope

- No scroll-collapse animation (the label stays put).
- No accent theming: the control keeps WhatsApp brand green regardless of the visitor
  accent, for instant recognisability and parity with the inline button.
- No new plugin, no new script file, no new build step.

## Delivery

Feature branch `feat/floating-whatsapp-button`, commit, PR. No GitHub issue exists for
this, so the PR is opened directly. Verified locally against `https://serensweb.test`.
