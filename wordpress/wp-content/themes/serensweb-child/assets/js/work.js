/* =====================================================================
   SerensWeb selected work: renders placeholder project cards into #workGrid
   and wires the lightbox. Guarded, so it no-ops until the work section and
   its lightbox markup exist on the page. Replace PROJECTS with real case
   studies (a CPT or ACF) later.
   ===================================================================== */
(function () {
  'use strict';
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];

  const grid = $('#workGrid');
  const lb = $('#lightbox');
  if (!grid && !lb) return;

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

  if (lb) {
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
  }
})();
