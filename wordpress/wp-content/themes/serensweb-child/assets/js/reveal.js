/* =====================================================================
   SerensWeb scroll reveal: rect-based, robust across environments, and
   honours prefers-reduced-motion through the CSS (this only toggles the
   .is-visible class; the animation itself is gated in theme.css).
   ===================================================================== */
(function () {
  'use strict';
  const revealEls = [...document.querySelectorAll('.reveal, .reveal-stagger')];
  if (!revealEls.length) return;

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
})();
