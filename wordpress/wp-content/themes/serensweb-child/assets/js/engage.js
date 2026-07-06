/* =====================================================================
   SerensWeb engagement switcher: lets a visitor pick how they want to
   work together (full-time / part-time / project). The chosen mode drives
   the reactive hint, the contact-form prefill, and the WhatsApp deep link.
   The number is localized as swEngage.whatsapp.

   Cross-page: the strengths cards live on the home page and link to /contact,
   carrying a data-topic. They stash that topic in sessionStorage on click
   (this runs even though the contact band is not on the home page), and the
   /contact page reads it on load to prefill the message.

   Global: the floating WhatsApp button (#waFloat, in the footer part) is on
   every page. It carries the visitor's remembered choice: the engagement mode
   persisted to localStorage plus any stashed topic, falling back to a plain
   greeting when the visitor has expressed no intent yet. On /contact it tracks
   the switcher live, alongside the inline #waLink button.
   ===================================================================== */
(function () {
  'use strict';
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];

  const number = (typeof swEngage !== 'undefined' && swEngage.whatsapp) ? swEngage.whatsapp : '';

  const templates = {
    project:  'Hi Seren, I have a project in mind.{topic}',
    fulltime: 'Hi Seren, I would like to discuss a full-time role.{topic}',
    parttime: 'Hi Seren, I would like to discuss a part-time engagement.{topic}',
  };
  // Shown when the visitor reaches for WhatsApp without having picked a mode or topic.
  const GREETING = 'Hi Seren, I saw your site and wanted to get in touch.';

  // Storage access, wrapped so a locked-down browser (private mode) never throws.
  const store = {
    getTopic() { try { return sessionStorage.getItem('sw-topic') || ''; } catch (e) { return ''; } },
    getMode()  { try { return localStorage.getItem('sw-engage') || ''; } catch (e) { return ''; } },
    setMode(m) { try { localStorage.setItem('sw-engage', m); } catch (e) {} },
  };

  function topicSuffix() {
    const topic = store.getTopic();
    return topic ? ' I am interested in ' + topic + '.' : '';
  }

  function messageFor(mode) {
    return (templates[mode] || templates.project).replace('{topic}', topicSuffix());
  }

  function waHref(msg) {
    return number ? 'https://wa.me/' + number + '?text=' + encodeURIComponent(msg) : 'https://wa.me/';
  }

  // The message the floating button carries off /contact: the remembered choice,
  // or a plain greeting when the visitor has touched neither switcher nor a card.
  function rememberedMessage() {
    const mode = store.getMode();
    if (!mode && !store.getTopic()) return GREETING;
    return messageFor(mode || 'project');
  }

  const waFloat = $('#waFloat');

  // Always: strengths cards stash their topic before navigating to /contact.
  $$('.fcard[data-topic]').forEach(card =>
    card.addEventListener('click', () => {
      try { sessionStorage.setItem('sw-topic', card.dataset.topic); } catch (e) {}
    })
  );

  // Always: point the floating button at the remembered message (every page).
  if (waFloat) waFloat.href = waHref(rememberedMessage());

  // The rest is the contact band, only present on the /contact page.
  const sw = $('.engage-switch');
  const grid = $('.contact__grid');
  if (!sw || !grid) return;

  const waLink = $('#waLink');
  const messageField = $('#contactForm [name="message"]');

  let lastAutoFill = '';

  function apply(mode, prefill) {
    grid.setAttribute('data-engage', mode);
    $$('.engage-opt', sw).forEach(b => b.setAttribute('aria-pressed', String(b.dataset.mode === mode)));

    const msg = messageFor(mode);
    const href = waHref(msg);
    if (waLink) waLink.href = href;
    if (waFloat) waFloat.href = href; // keep the floating button in sync with the switcher

    if (prefill && messageField && (messageField.value === '' || messageField.value === lastAutoFill)) {
      messageField.value = msg;
      lastAutoFill = msg;
    }
  }

  // Clicking an option persists the choice so the floating button remembers it site-wide.
  $$('.engage-opt', sw).forEach(b =>
    b.addEventListener('click', () => { store.setMode(b.dataset.mode); apply(b.dataset.mode, true); })
  );

  // On load: if the visitor arrived from a strengths card (topic stashed),
  // prefill with that topic; otherwise just set the default state.
  let hasTopic = false;
  try { hasTopic = !!sessionStorage.getItem('sw-topic'); } catch (e) {}
  apply(grid.getAttribute('data-engage') || 'project', hasTopic);
})();
