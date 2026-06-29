/* =====================================================================
   SerensWeb engagement switcher: lets a visitor pick how they want to
   work together (full-time / part-time / project). The chosen mode drives
   the reactive hint, the contact-form prefill, and the WhatsApp deep link.
   The number is localized as swEngage.whatsapp.

   Cross-page: the strengths cards live on the home page and link to /contact,
   carrying a data-topic. They stash that topic in sessionStorage on click
   (this runs even though the contact band is not on the home page), and the
   /contact page reads it on load to prefill the message.
   ===================================================================== */
(function () {
  'use strict';
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];

  // Always: strengths cards stash their topic before navigating to /contact.
  $$('.fcard[data-topic]').forEach(card =>
    card.addEventListener('click', () => {
      try { sessionStorage.setItem('sw-topic', card.dataset.topic); } catch (e) {}
    })
  );

  // The rest is the contact band, only present on the /contact page.
  const sw = $('.engage-switch');
  const grid = $('.contact__grid');
  if (!sw || !grid) return;

  const number = (typeof swEngage !== 'undefined' && swEngage.whatsapp) ? swEngage.whatsapp : '';
  const waLink = $('#waLink');
  const messageField = $('#contactForm [name="message"]');

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

  function apply(mode, prefill) {
    grid.setAttribute('data-engage', mode);
    $$('.engage-opt', sw).forEach(b => b.setAttribute('aria-pressed', String(b.dataset.mode === mode)));

    const msg = messageFor(mode);

    if (waLink) {
      waLink.href = number
        ? 'https://wa.me/' + number + '?text=' + encodeURIComponent(msg)
        : 'https://wa.me/';
    }

    if (prefill && messageField && (messageField.value === '' || messageField.value === lastAutoFill)) {
      messageField.value = msg;
      lastAutoFill = msg;
    }
  }

  $$('.engage-opt', sw).forEach(b =>
    b.addEventListener('click', () => apply(b.dataset.mode, true))
  );

  // On load: if the visitor arrived from a strengths card (topic stashed),
  // prefill with that topic; otherwise just set the default state.
  let hasTopic = false;
  try { hasTopic = !!sessionStorage.getItem('sw-topic'); } catch (e) {}
  apply(grid.getAttribute('data-engage') || 'project', hasTopic);
})();
