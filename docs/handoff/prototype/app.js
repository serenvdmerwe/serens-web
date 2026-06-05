/* =====================================================================
   SerensWeb - interactions (vanilla JS, framework-free, WP-friendly)
   Bootstrap should split these concerns into assets/js/ modules and
   enqueue them from includes/enqueue.php.
   ===================================================================== */
(function () {
  'use strict';
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];

  /* ---- current year ---- */
  const y = $('#year'); if (y) y.textContent = new Date().getFullYear();

  /* ---- transition feature-probe ----
     Some environments (offscreen / throttled tabs) freeze CSS transitions,
     which would leave opacity:0 reveal content stuck hidden. Detect that and
     fall back to body.frozen (everything visible, no transition) so the page
     is never blank. Real foreground browsers pass the probe and keep motion. */
  (function probeTransitions() {
    try {
      const p = document.createElement('div');
      p.style.cssText = 'position:fixed;top:-20px;left:-20px;width:4px;height:4px;opacity:0;transition:opacity .2s linear;pointer-events:none;';
      document.body.appendChild(p);
      void p.offsetHeight;
      p.style.opacity = '1';
      setTimeout(() => {
        const moved = parseFloat(getComputedStyle(p).opacity) > 0.05;
        if (!moved) document.body.classList.add('frozen');
        p.remove();
      }, 120);
    } catch (e) { /* ignore */ }
  })();

  /* ---- visitor-facing accent switcher (persists across visits) ---- */
  const ACCENTS = {
    orange: ['#F6821F', '#E5751A'],
    blue:   ['#2F73E8', '#2861C9'],
    purple: ['#8B5CF6', '#7A48E0'],
    green:  ['#1FA463', '#198A53'],
  };
  const accentDots = $$('.accent-dot');
  function applyAccent(key, persist) {
    const a = ACCENTS[key] || ACCENTS.orange;
    document.documentElement.style.setProperty('--color-primary', a[0]);
    document.documentElement.style.setProperty('--color-primary-strong', a[1]);
    accentDots.forEach(d => d.setAttribute('aria-pressed', String(d.dataset.accent === key)));
    if (persist) { try { localStorage.setItem('serensweb-accent', key); } catch (e) {} }
  }
  let savedAccent = 'orange';
  try { savedAccent = localStorage.getItem('serensweb-accent') || 'orange'; } catch (e) {}
  applyAccent(savedAccent, false);
  accentDots.forEach(d => d.addEventListener('click', () => applyAccent(d.dataset.accent, true)));

  /* ---- header elevation on scroll ---- */
  const header = $('#header');
  const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 12);
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  /* ---- mobile drawer ---- */
  const burger = $('#burger');
  const closeMenu = () => { document.body.classList.remove('menu-open'); burger.setAttribute('aria-expanded', 'false'); burger.setAttribute('aria-label', 'Open menu'); };
  const openMenu  = () => { document.body.classList.add('menu-open');    burger.setAttribute('aria-expanded', 'true');  burger.setAttribute('aria-label', 'Close menu'); };
  burger.addEventListener('click', () => document.body.classList.contains('menu-open') ? closeMenu() : openMenu());
  $$('#drawer a').forEach(a => a.addEventListener('click', closeMenu));
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMenu(); });

  /* ---- smooth scroll for in-page anchors (accounts for sticky header) ---- */
  $$('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const id = a.getAttribute('href');
      if (id === '#' || id.length < 2) return;
      const target = document.querySelector(id);
      if (!target) return;
      e.preventDefault();
      const top = target.getBoundingClientRect().top + window.scrollY - 70;
      window.scrollTo({ top, behavior: 'smooth' });
    });
  });

  /* ---- scroll reveal (rect-based, robust across environments) ---- */
  const revealEls = $$('.reveal, .reveal-stagger');
  function revealCheck() {
    const vh = window.innerHeight || document.documentElement.clientHeight;
    revealEls.forEach(el => {
      if (el.classList.contains('is-visible')) return;
      const r = el.getBoundingClientRect();
      if (r.top < vh * 0.92 && r.bottom > 0) el.classList.add('is-visible');
    });
  }
  revealCheck();
  window.addEventListener('scroll', revealCheck, { passive: true });
  window.addEventListener('resize', revealCheck, { passive: true });
  window.addEventListener('load', revealCheck);

  /* ---- projects data + render ---- */
  const PROJECTS = [
    { viz: 'viz-a', cat: 'Headless Commerce', title: 'Atlas Supply Co.', blurb: 'A headless Shopify storefront rebuilt for speed. Sub-second loads and a 30% lift in checkout completion.',
      tags: ['Shopify Hydrogen', 'React', 'Edge', 'Stripe'],
      metrics: [['0.8s', 'LCP'], ['+30%', 'Checkout'], ['100', 'Perf score']],
      desc: 'Atlas came to me with a slow, theme-locked store losing customers at checkout. I rebuilt the front-end as a headless Hydrogen storefront with edge-rendered product pages, a streamlined cart, and a custom checkout flow. The result is a store that feels instant and converts.' },
    { viz: 'viz-b', cat: 'AI Web App', title: 'Brief: AI writing copilot', blurb: 'A focused writing tool with an LLM assistant that drafts, rewrites and structures copy in real time.',
      tags: ['Next.js', 'TypeScript', 'LLM', 'Postgres'],
      metrics: [['12k', 'Users'], ['1.4s', 'TTFB'], ['4.8 stars', 'Rating']],
      desc: 'Brief is a distraction-free editor with an AI copilot built in. I designed the streaming assistant UX, the prompt orchestration layer, and a usage-metered billing system. The interface keeps AI helpful and out of the way until you ask for it.' },
    { viz: 'viz-c', cat: 'Progressive Web App', title: 'Tempo: habit tracker PWA', blurb: 'An installable, offline-first habit tracker with native-feeling gestures and instant sync.',
      tags: ['PWA', 'Service Worker', 'IndexedDB', 'Astro'],
      metrics: [['Offline', 'Ready'], ['<50ms', 'Interaction'], ['A+', 'a11y']],
      desc: 'Tempo works anywhere, online or off. I built a service-worker sync layer over IndexedDB, smooth gesture interactions, and an installable PWA shell that feels like a native app, without an app store in sight.' },
    { viz: 'viz-d', cat: 'Corporate Site', title: 'Northwind Studio', blurb: 'A motion-rich brand site for a design studio. Scroll-driven storytelling that signals craft.',
      tags: ['Astro', 'GSAP', 'WordPress', 'CMS'],
      metrics: [['+62%', 'Time on site'], ['98', 'Lighthouse'], ['2wk', 'Delivery']],
      desc: 'Northwind needed a site that proved their craft in the first three seconds. I built a scroll-driven narrative with restrained motion on top of an editor-friendly WordPress block backend, so the team can keep it fresh without touching code.' },
  ];

  const grid = $('#workGrid');
  if (grid) {
    grid.innerHTML = PROJECTS.map((p, i) => `
      <button class="proj" data-i="${i}" aria-label="View ${p.title}">
        <span class="proj__viz ${p.viz}">
          <span class="ovl"></span>
          <span class="chip">${p.cat}</span>
          <span class="view"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
        </span>
        <span class="proj__body">
          <span class="cat">${p.cat}</span>
          <h3>${p.title}</h3>
          <p>${p.blurb}</p>
        </span>
      </button>`).join('');
  }

  /* ---- lightbox ---- */
  const lb = $('#lightbox');
  const lbViz = $('#lbViz'), lbCat = $('#lbCat'), lbTitle = $('#lbTitle'), lbDesc = $('#lbDesc'), lbTags = $('#lbTags'), lbMetrics = $('#lbMetrics');
  let lastFocus = null;

  function openLightbox(i) {
    const p = PROJECTS[i];
    lbViz.className = 'lightbox__viz ' + p.viz;
    lbViz.innerHTML = '<div class="ovl"></div><span class="chip" style="position:absolute;left:20px;bottom:20px;font-family:var(--font-mono);font-size:11px;padding:6px 12px;border-radius:999px;background:rgba(10,10,10,0.5);color:#fff;border:1px solid rgba(255,255,255,0.18);backdrop-filter:blur(6px);">' + p.cat + '</span>';
    lbCat.textContent = p.cat;
    lbTitle.textContent = p.title;
    lbDesc.textContent = p.desc;
    lbTags.innerHTML = p.tags.map(t => `<span>${t}</span>`).join('');
    lbMetrics.innerHTML = p.metrics.map(m => `<div class="m"><div class="n">${m[0]}</div><div class="l">${m[1]}</div></div>`).join('');
    lastFocus = document.activeElement;
    lb.classList.add('show');
    document.body.style.overflow = 'hidden';
    $('.lightbox__close', lb).focus();
  }
  function closeLightbox() {
    lb.classList.remove('show');
    document.body.style.overflow = '';
    if (lastFocus) lastFocus.focus();
  }
  if (grid) grid.addEventListener('click', e => { const b = e.target.closest('.proj'); if (b) openLightbox(+b.dataset.i); });
  $$('[data-close]', lb).forEach(el => el.addEventListener('click', closeLightbox));
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && lb.classList.contains('show')) closeLightbox(); });

  /* ---- contact form validation ----
     Client-side only. Wire the submit to a server-side handler
     (TurboPress: includes/ajax-contact.php) in the WordPress build. */
  const form = $('#contactForm');
  const success = $('#formSuccess');
  const validators = {
    name: v => v.trim().length >= 2,
    email: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()),
    message: v => v.trim().length >= 10,
  };
  function validateField(field) {
    const name = field.dataset.field;
    const input = $('input, textarea', field);
    const ok = validators[name](input.value);
    field.classList.toggle('invalid', !ok);
    return ok;
  }
  if (form) {
    $$('.field', form).forEach(field => {
      const input = $('input, textarea', field);
      input.addEventListener('blur', () => { if (input.value) validateField(field); });
      input.addEventListener('input', () => { if (field.classList.contains('invalid')) validateField(field); });
    });
    form.addEventListener('submit', e => {
      e.preventDefault();
      let allOk = true;
      $$('.field', form).forEach(field => { if (!validateField(field)) allOk = false; });
      if (!allOk) { $('.field.invalid input, .field.invalid textarea', form)?.focus(); return; }
      form.style.display = 'none';
      success.classList.add('show');
    });
    $('#resetForm')?.addEventListener('click', () => {
      form.reset();
      $$('.field', form).forEach(f => f.classList.remove('invalid'));
      success.classList.remove('show');
      form.style.display = 'grid';
    });
  }
})();
