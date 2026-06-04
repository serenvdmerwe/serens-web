/* =====================================================================
   SerensWeb scroll reveal. Rect-based and robust across environments.
   The CSS only applies reveal transitions under prefers-reduced-motion:
   no-preference, so reduced-motion users see everything immediately.
   A transition feature-probe catches throttled/offscreen environments
   that freeze transitions and falls back to everything-visible.
   ===================================================================== */
(function () {
  'use strict';
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];

  const revealEls = $$('.reveal, .reveal-stagger');

  function revealCheck() {
    const vh = window.innerHeight || document.documentElement.clientHeight;
    revealEls.forEach(el => {
      if (el.classList.contains('is-visible')) return;
      const r = el.getBoundingClientRect();
      if (r.top < vh * 0.92 && r.bottom > 0) el.classList.add('is-visible');
    });
  }

  /* transition feature-probe: if transitions are frozen, show everything */
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
        revealCheck();
      }, 120);
    } catch (e) { /* ignore */ }
  })();

  revealCheck();
  window.addEventListener('scroll', revealCheck, { passive: true });
  window.addEventListener('resize', revealCheck, { passive: true });
  window.addEventListener('load', revealCheck);
})();
