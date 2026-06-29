/* =====================================================================
   SerensWeb site chrome: year, accent switcher, header shrink, mobile
   drawer, smooth-scroll. Split from the handoff app.js. Each block is
   guarded so it no-ops when its markup is absent (e.g. in the editor).
   ===================================================================== */
(function () {
  'use strict';
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];

  /* ---- current year ---- */
  const y = $('#year');
  if (y) y.textContent = new Date().getFullYear();

  /* ---- transition feature-probe ----
     Some environments (offscreen / throttled tabs) freeze CSS transitions,
     which would leave opacity:0 reveal content stuck hidden. Detect that and
     fall back to body.frozen (everything visible, no transition). */
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
  if (header) {
    const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 12);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* ---- mobile drawer ---- */
  const burger = $('#burger');
  if (burger) {
    const closeMenu = () => { document.body.classList.remove('menu-open'); burger.setAttribute('aria-expanded', 'false'); burger.setAttribute('aria-label', 'Open menu'); };
    const openMenu  = () => { document.body.classList.add('menu-open');    burger.setAttribute('aria-expanded', 'true');  burger.setAttribute('aria-label', 'Close menu'); };
    burger.addEventListener('click', () => document.body.classList.contains('menu-open') ? closeMenu() : openMenu());
    $$('#drawer a').forEach(a => a.addEventListener('click', closeMenu));
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMenu(); });
  }

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

  /* ---- mark the current page in the global nav ---- */
  (function markCurrent() {
    const path = location.pathname.replace(/\/+$/, '') || '/';
    $$('.nav a, .drawer a').forEach(a => {
      const href = (a.getAttribute('href') || '').replace(/\/+$/, '');
      if (href && href !== '/' && (path === href || path.startsWith(href + '/'))) {
        a.setAttribute('aria-current', 'page');
      }
    });
  })();
})();
