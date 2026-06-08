# Work-with-me Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Park the dead subdomain links and add three ways to engage the studio (an engagement switcher, WhatsApp click-to-chat, and a CV download) into the existing contact band, without redesigning any section.

**Architecture:** Markup lives in block patterns (`patterns/*.php`) and template parts (`parts/*.html`); new component CSS goes in the child `style.css` (last layer of the tokens.css -> theme.css -> child pipeline); behavior is a single guarded vanilla-JS module `engage.js` enqueued in the footer. One state value (the chosen engagement mode) is the single source of truth that drives a reactive hint, the WhatsApp deep link, and the contact-form prefill. The real WhatsApp number, CV PDF, and email delivery are deferred placeholders.

**Tech Stack:** WordPress FSE child theme (Twenty Twenty-Five), PHP block patterns, vanilla ES (no build step), CSS custom properties. No unit-test harness exists; verification is `php -l` syntax linting, `node --check` for JS syntax, and explicit browser checks at `https://serensweb.test`.

**Conventions to honour (from CLAUDE.md):** no em/en dashes, no double hyphens, no emoji, no eyebrows, no decorative numerals. Do not edit `assets/css/theme.css` (verbatim prototype). New CSS goes in the child `style.css`. JS modules are IIFE, `'use strict'`, guarded so they no-op when their markup is absent.

**Verification preconditions:** the Docker stack must be up (`docker compose up -d` from repo root) and the conflicting local stacks stopped first (`docker compose stop` in tcg-forensics and wc-pumps), because Caddy holds 80/443. wp-cli / php run inside the container: `docker compose exec serens-web-php-service ...`.

---

### Task 1: Footer Services links point in-page

**Files:**
- Modify: `wordpress/wp-content/themes/serensweb-child/parts/footer.html:14-17`

- [ ] **Step 1: Replace the Services column links**

Find this block (lines 14-17):

```html
        <div class="footer__col">
          <h4>Services</h4>
          <a href="https://commerce.serensweb.dev" target="_blank" rel="noopener">Headless commerce</a><a href="https://ai.serensweb.dev" target="_blank" rel="noopener">AI web apps</a><a href="https://apps.serensweb.dev" target="_blank" rel="noopener">PWAs</a><a href="https://studio.serensweb.dev" target="_blank" rel="noopener">Marketing sites</a>
        </div>
```

Replace with:

```html
        <div class="footer__col">
          <h4>Services</h4>
          <a href="#contact">Headless commerce</a><a href="#contact">AI web apps</a><a href="#contact">PWAs</a><a href="#contact">Marketing sites</a>
        </div>
```

- [ ] **Step 2: Verify no subdomain links remain in the footer**

Run: `git grep -n "serensweb.dev" wordpress/wp-content/themes/serensweb-child/parts/footer.html`
Expected: only `hello@serensweb.dev` (the mailto) remains; no `https://*.serensweb.dev` lines.

- [ ] **Step 3: Commit**

```bash
git add wordpress/wp-content/themes/serensweb-child/parts/footer.html
git commit -m "fix(footer): point Services links to in-page contact while subdomains are parked"
```

---

### Task 2: Strengths cards anchor to contact

**Files:**
- Modify: `wordpress/wp-content/themes/serensweb-child/patterns/home-strengths.php`

- [ ] **Step 1: Reword the section lede**

Find (line 14):

```html
      <p class="lede">Four areas of focused expertise, each with a dedicated home on its own subdomain.</p>
```

Replace with:

```html
      <p class="lede">Four areas of focused expertise. Tell me which one fits what you are building.</p>
```

- [ ] **Step 2: Rewire each of the four cards**

For every `a.fcard`, make three changes: (a) `href` becomes `#contact`, (b) remove `target="_blank" rel="noopener"`, (c) add `data-topic="<service area, no ampersand entity>"`, and (d) change the `.fcard__url` text from the subdomain to a `Discuss this` label (keep the `.fcard__sub` accent span and the existing arrow svg so the line keeps its look).

Card 1 (commerce) opening tag and url line. Find:

```html
      <a class="fcard" href="https://commerce.serensweb.dev" target="_blank" rel="noopener">
```

Replace with:

```html
      <a class="fcard" href="#contact" data-topic="Custom E-Commerce and Headless Commerce">
```

Find its url line:

```html
        <span class="fcard__url"><span class="fcard__sub">commerce</span>.serensweb.dev<svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
```

Replace with:

```html
        <span class="fcard__url"><span class="fcard__sub">Discuss</span> this<svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
```

Card 2 (ai). Find:

```html
      <a class="fcard" href="https://ai.serensweb.dev" target="_blank" rel="noopener">
```

Replace with:

```html
      <a class="fcard" href="#contact" data-topic="AI-Integrated Web Apps">
```

Find its url line:

```html
        <span class="fcard__url"><span class="fcard__sub">ai</span>.serensweb.dev<svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
```

Replace with:

```html
        <span class="fcard__url"><span class="fcard__sub">Discuss</span> this<svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
```

Card 3 (apps). Find:

```html
      <a class="fcard" href="https://apps.serensweb.dev" target="_blank" rel="noopener">
```

Replace with:

```html
      <a class="fcard" href="#contact" data-topic="Progressive Web Apps and Mobile-First Sites">
```

Find its url line:

```html
        <span class="fcard__url"><span class="fcard__sub">apps</span>.serensweb.dev<svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
```

Replace with:

```html
        <span class="fcard__url"><span class="fcard__sub">Discuss</span> this<svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
```

Card 4 (studio). Find:

```html
      <a class="fcard" href="https://studio.serensweb.dev" target="_blank" rel="noopener">
```

Replace with:

```html
      <a class="fcard" href="#contact" data-topic="Interactive Portfolios and Corporate Sites">
```

Find its url line:

```html
        <span class="fcard__url"><span class="fcard__sub">studio</span>.serensweb.dev<svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
```

Replace with:

```html
        <span class="fcard__url"><span class="fcard__sub">Discuss</span> this<svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
```

- [ ] **Step 3: Lint the pattern**

Run: `docker compose exec serens-web-php-service php -l wp-content/themes/serensweb-child/patterns/home-strengths.php`
Expected: `No syntax errors detected`.

- [ ] **Step 4: Verify no subdomain links remain**

Run: `git grep -n "serensweb.dev" wordpress/wp-content/themes/serensweb-child/patterns/home-strengths.php`
Expected: no matches.

- [ ] **Step 5: Commit**

```bash
git add wordpress/wp-content/themes/serensweb-child/patterns/home-strengths.php
git commit -m "feat(strengths): cards anchor to contact and carry their service topic"
```

---

### Task 3: CV download placeholder asset

**Files:**
- Create: `wordpress/wp-content/themes/serensweb-child/assets/docs/.gitkeep`
- Create: `wordpress/wp-content/themes/serensweb-child/assets/docs/README.md`

- [ ] **Step 1: Create the docs directory keepfile**

Create `wordpress/wp-content/themes/serensweb-child/assets/docs/.gitkeep` with empty content.

- [ ] **Step 2: Create the README note**

Create `wordpress/wp-content/themes/serensweb-child/assets/docs/README.md`:

```markdown
# CV asset

The Download CV button in the contact section links to `seren-cv.pdf` in this folder.

Drop the real CV here as `seren-cv.pdf` (exact filename). Until then the download
link returns 404; that is expected during the design round and is wired with the
real file in the fiancee round. Self-hosted on purpose: no plugin, no CDN.
```

- [ ] **Step 3: Commit**

```bash
git add wordpress/wp-content/themes/serensweb-child/assets/docs/.gitkeep wordpress/wp-content/themes/serensweb-child/assets/docs/README.md
git commit -m "chore(assets): add docs/ placeholder for the self-hosted CV"
```

---

### Task 4: Contact band markup (switcher, hints, CV + WhatsApp)

**Files:**
- Modify: `wordpress/wp-content/themes/serensweb-child/patterns/home-contact.php`

- [ ] **Step 1: Add the engagement state to the grid wrapper**

Find (line 12):

```html
  <div class="wrap contact__grid" style="position:relative;z-index:1;">
```

Replace with:

```html
  <div class="wrap contact__grid" data-engage="project" style="position:relative;z-index:1;">
```

- [ ] **Step 2: Replace the lead column with switcher, reactive hints, and actions**

Find the whole `.contact__lead` block (lines 13-21):

```html
    <div class="contact__lead reveal">
      <h2>Have a project in <em>mind?</em></h2>
      <p class="lede" style="margin-top:22px;max-width:42ch;">Tell me a little about what you're building. I reply to every serious enquiry within one business day.</p>
      <div class="contact__meta">
        <a href="mailto:hello@serensweb.dev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m4 7 8 6 8-6"/></svg> hello@serensweb.dev</a>
        <div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="11" r="3"/><path d="M12 2a8 8 0 0 0-8 8c0 5.5 8 12 8 12s8-6.5 8-12a8 8 0 0 0-8-8Z"/></svg> Remote, working worldwide</div>
        <div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg> Currently booking for Q3</div>
      </div>
    </div>
```

Replace with:

```html
    <div class="contact__lead reveal">
      <h2>Have a project in <em>mind?</em></h2>

      <div class="engage-switch" role="group" aria-label="How would you like to work together?">
        <span class="engage-switch__label">Work with me</span>
        <div class="engage-opts">
          <button type="button" class="engage-opt" data-mode="fulltime" aria-pressed="false">Full-time</button>
          <button type="button" class="engage-opt" data-mode="parttime" aria-pressed="false">Part-time</button>
          <button type="button" class="engage-opt" data-mode="project" aria-pressed="true">Project</button>
        </div>
      </div>

      <p class="lede contact__hint" data-when="project" style="margin-top:22px;max-width:44ch;">Tell me a little about what you're building. I reply to every serious enquiry within one business day.</p>
      <p class="lede contact__hint" data-when="role" style="margin-top:22px;max-width:44ch;">Considering me for a role? Download my CV, then send a note below or message me on WhatsApp.</p>

      <div class="contact__actions">
        <a class="btn btn--ghost contact__cv" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/docs/seren-cv.pdf' ); ?>" download>
          Download CV (PDF)
          <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12m0 0 4-4m-4 4-4-4"/><path d="M5 21h14"/></svg>
        </a>
        <a class="btn btn--whatsapp" id="waLink" href="https://wa.me/" target="_blank" rel="noopener">
          Message on WhatsApp
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.5 8.5 0 0 1-12.6 7.4L3 20l1.1-5.4A8.5 8.5 0 1 1 21 11.5Z"/></svg>
        </a>
      </div>

      <div class="contact__meta">
        <a href="mailto:hello@serensweb.dev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m4 7 8 6 8-6"/></svg> hello@serensweb.dev</a>
        <div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="11" r="3"/><path d="M12 2a8 8 0 0 0-8 8c0 5.5 8 12 8 12s8-6.5 8-12a8 8 0 0 0-8-8Z"/></svg> Remote, working worldwide</div>
        <div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg> Currently booking for Q3</div>
      </div>
    </div>
```

- [ ] **Step 3: Lint the pattern**

Run: `docker compose exec serens-web-php-service php -l wp-content/themes/serensweb-child/patterns/home-contact.php`
Expected: `No syntax errors detected`.

- [ ] **Step 4: Commit**

```bash
git add wordpress/wp-content/themes/serensweb-child/patterns/home-contact.php
git commit -m "feat(contact): add engagement switcher, CV download, and WhatsApp button"
```

---

### Task 5: Component CSS in the child stylesheet

**Files:**
- Modify: `wordpress/wp-content/themes/serensweb-child/style.css` (append after the theme header comment)

- [ ] **Step 1: Append the component styles**

Add to the end of `wordpress/wp-content/themes/serensweb-child/style.css`:

```css

/* =====================================================================
   Work-with-me additions (child overrides, after the theme header).
   Engagement switcher, reactive contact hints, CV + WhatsApp actions.
   Token names come from assets/css/tokens.css.
   ===================================================================== */

/* ---- engagement switcher ---- */
.engage-switch { display: flex; align-items: center; flex-wrap: wrap; gap: 14px; margin-top: 24px; }
.engage-switch__label { font-family: var(--font-mono); font-size: 11.5px; letter-spacing: 0.1em; text-transform: uppercase; color: var(--color-text-muted); }
.engage-opts { display: inline-flex; gap: 4px; padding: 4px; background: var(--color-dark-3); border: 1px solid var(--hairline-dark); border-radius: var(--r-pill); }
.engage-opt { appearance: none; border: 0; cursor: pointer; font: inherit; font-size: 0.92rem; padding: 8px 16px; border-radius: var(--r-pill); background: transparent; color: var(--color-text-muted); transition: color 0.2s var(--ease), background 0.2s var(--ease); }
.engage-opt:hover { color: var(--color-text-light); }
.engage-opt[aria-pressed="true"] { background: var(--color-primary); color: #1a1206; }

/* ---- reactive hints: exactly one shows per mode ---- */
.contact__hint { display: none; }
.contact__grid[data-engage="project"] .contact__hint[data-when="project"],
.contact__grid[data-engage="fulltime"] .contact__hint[data-when="role"],
.contact__grid[data-engage="parttime"] .contact__hint[data-when="role"] { display: block; }

/* ---- CV + WhatsApp actions ---- */
.contact__actions { display: flex; flex-wrap: wrap; gap: 14px; margin-top: 26px; }
.btn--whatsapp { background: #25D366; color: #07251a; }
.btn--whatsapp:hover { transform: translateY(-2px); box-shadow: 0 14px 30px -12px rgba(37, 211, 102, 0.5); }

/* CV button is a quiet ghost by default, emphasized when the visitor is here about a role */
.contact__grid[data-engage="fulltime"] .contact__cv,
.contact__grid[data-engage="parttime"] .contact__cv { background: var(--color-primary); color: #1a1206; border-color: transparent; box-shadow: var(--shadow-pop); }
```

- [ ] **Step 2: Confirm the file still starts with the theme header**

Run: `git grep -n "Theme Name" wordpress/wp-content/themes/serensweb-child/style.css`
Expected: the `Theme Name:` header line is still present at the top (the CSS was appended, not overwritten).

- [ ] **Step 3: Commit**

```bash
git add wordpress/wp-content/themes/serensweb-child/style.css
git commit -m "style(contact): component CSS for engagement switcher, hints, CV and WhatsApp"
```

---

### Task 6: Engagement behavior module

**Files:**
- Create: `wordpress/wp-content/themes/serensweb-child/assets/js/engage.js`
- Modify: `wordpress/wp-content/themes/serensweb-child/includes/enqueue.php:36-42`

- [ ] **Step 1: Create the module**

Create `wordpress/wp-content/themes/serensweb-child/assets/js/engage.js`:

```js
/* =====================================================================
   SerensWeb engagement switcher: lets a visitor pick how they want to
   work together (full-time / part-time / project). The chosen mode drives
   the reactive hint, the contact-form prefill, and the WhatsApp deep link.
   Guarded on .engage-switch. The number is localized as swEngage.whatsapp;
   strengths cards stash their topic so the prefill can mention it.
   ===================================================================== */
(function () {
  'use strict';
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];

  const sw = $('.engage-switch');
  const grid = $('.contact__grid');
  if (!sw || !grid) return;

  const number = (typeof swEngage !== 'undefined' && swEngage.whatsapp) ? swEngage.whatsapp : '';
  const waLink = $('#waLink');
  const messageField = $('#contactForm [name="message"]');

  // Per-mode message templates. {topic} is filled when the visitor arrived
  // from a strengths card (the card click stores it in sessionStorage).
  const templates = {
    project:  'Hi Seren, I have a project in mind.{topic}',
    fulltime: 'Hi Seren, I would like to discuss a full-time role.{topic}',
    parttime: 'Hi Seren, I would like to discuss a part-time engagement.{topic}',
  };

  let lastAutoFill = '';

  function topicSuffix() {
    let topic = '';
    try { topic = sessionStorage.getItem('sw-topic') || ''; } catch (e) {}
    return topic ? ' I am interested in ' + topic + '.' : '';
  }

  function messageFor(mode) {
    return (templates[mode] || templates.project).replace('{topic}', topicSuffix());
  }

  function apply(mode) {
    grid.setAttribute('data-engage', mode);
    $$('.engage-opt', sw).forEach(b => b.setAttribute('aria-pressed', String(b.dataset.mode === mode)));

    const msg = messageFor(mode);

    if (waLink) {
      waLink.href = number
        ? 'https://wa.me/' + number + '?text=' + encodeURIComponent(msg)
        : 'https://wa.me/';
    }

    // Prefill the form message, but never clobber what the visitor typed:
    // only overwrite when the field is empty or still holds our last auto-fill.
    if (messageField && (messageField.value === '' || messageField.value === lastAutoFill)) {
      messageField.value = msg;
      lastAutoFill = msg;
    }
  }

  $$('.engage-opt', sw).forEach(b =>
    b.addEventListener('click', () => apply(b.dataset.mode))
  );

  // Strengths cards carry a topic; stash it and refresh the prefill on click.
  $$('.fcard[data-topic]').forEach(card =>
    card.addEventListener('click', () => {
      try { sessionStorage.setItem('sw-topic', card.dataset.topic); } catch (e) {}
      apply(grid.getAttribute('data-engage') || 'project');
    })
  );

  // Apply the default (or whatever mode the markup ships with) on load.
  apply(grid.getAttribute('data-engage') || 'project');
})();
```

- [ ] **Step 2: Enqueue and localize it**

In `wordpress/wp-content/themes/serensweb-child/includes/enqueue.php`, find:

```php
	wp_enqueue_script( 'sw-contact-form', $theme_uri . '/assets/js/contact-form.js', [], $asset_version( 'assets/js/contact-form.js' ), true );

	// Hand the contact form its REST endpoint and a nonce.
	wp_localize_script( 'sw-contact-form', 'swContact', [
		'url'   => esc_url_raw( rest_url( 'serensweb/v1/contact' ) ),
		'nonce' => wp_create_nonce( 'wp_rest' ),
	] );
```

Replace with:

```php
	wp_enqueue_script( 'sw-contact-form', $theme_uri . '/assets/js/contact-form.js', [], $asset_version( 'assets/js/contact-form.js' ), true );
	wp_enqueue_script( 'sw-engage',       $theme_uri . '/assets/js/engage.js',       [], $asset_version( 'assets/js/engage.js' ), true );

	// Hand the contact form its REST endpoint and a nonce.
	wp_localize_script( 'sw-contact-form', 'swContact', [
		'url'   => esc_url_raw( rest_url( 'serensweb/v1/contact' ) ),
		'nonce' => wp_create_nonce( 'wp_rest' ),
	] );

	// Hand the engagement switcher the WhatsApp number.
	// TODO(fiancee round): set the number in international format, digits only,
	// no plus or spaces, e.g. '27821234567'. Empty string disables the deep link.
	wp_localize_script( 'sw-engage', 'swEngage', [
		'whatsapp' => '',
	] );
```

- [ ] **Step 3: Syntax-check both files**

Run: `node --check wordpress/wp-content/themes/serensweb-child/assets/js/engage.js`
Expected: no output (exit 0).

Run: `docker compose exec serens-web-php-service php -l wp-content/themes/serensweb-child/includes/enqueue.php`
Expected: `No syntax errors detected`.

- [ ] **Step 4: Commit**

```bash
git add wordpress/wp-content/themes/serensweb-child/assets/js/engage.js wordpress/wp-content/themes/serensweb-child/includes/enqueue.php
git commit -m "feat(contact): engagement switcher behavior, WhatsApp prefill, topic carry"
```

---

### Task 7: Browser verification and PR

**Files:** none (verification + integration)

- [ ] **Step 1: Bring the stack up**

Run (stop the conflicting stacks first if needed):
```
docker compose up -d
```
Confirm `https://serensweb.test` loads the home page.

- [ ] **Step 2: Verify the switcher and reactive emphasis**

Open `https://serensweb.test`, scroll to the contact section. Expected:
- Three pills show, `Project` selected by default.
- The "Tell me a little about what you're building" hint shows; the role hint is hidden.
- Click `Full-time`: the role hint replaces the project hint, and the `Download CV (PDF)` button fills with the accent color (emphasized). Click `Part-time`: same emphasis. Click `Project`: emphasis returns to the form-focused hint and the CV button goes back to ghost.

- [ ] **Step 3: Verify the prefill and topic carry**

- With `Project` selected, confirm the message textarea is prefilled with "Hi Seren, I have a project in mind." Type over it, switch modes, and confirm your typed text is NOT clobbered.
- Reload, scroll to Strengths, click the AI card. Expected: the page scrolls to contact and (after the click) the prefill / WhatsApp text includes "I am interested in AI-Integrated Web Apps."

- [ ] **Step 4: Verify links degrade gracefully**

- Hover the WhatsApp button: with `swEngage.whatsapp` empty it points to `https://wa.me/` (no broken `__PLACEHOLDER__`). It will deep-link properly once the number is set.
- Click `Download CV (PDF)`: it requests `/assets/docs/seren-cv.pdf` (404 until the real file is added, expected this round).
- Footer Services links and all four strengths cards scroll to the contact section.

- [ ] **Step 5: Open the PR**

```bash
git push -u origin feat/work-with-me
gh pr create --fill --base main
```

Use a PR body that lists what shipped (switcher, WhatsApp, CV, footer/strengths rewire) and the deferred placeholders (WhatsApp number, CV PDF, form delivery, destination email).

---

## Self-review notes

- **Spec coverage:** strengths rewire (Task 2), engagement switcher reactive + prefill (Tasks 4, 5, 6), WhatsApp (Tasks 4, 6), CV in contact band (Tasks 3, 4, 5), footer (Task 1), graceful degradation (Task 7 Step 4). Deferred items remain placeholders (empty WhatsApp number, missing PDF, untouched REST endpoint).
- **No placeholders in the plan:** every code step shows full content; `__PLACEHOLDER__` text was deliberately removed in favor of an empty configured value plus a TODO comment.
- **Name consistency:** `.engage-switch` / `.engage-opt` / `data-mode` / `data-engage` / `data-topic` / `#waLink` / `swEngage.whatsapp` / `sw-topic` (sessionStorage key) are used identically in the markup (Task 4), CSS (Task 5), and JS (Task 6).
