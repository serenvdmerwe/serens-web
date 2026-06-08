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
