<?php
/**
 * Title: Playground
 * Slug: serensweb-child/playground
 * Categories: serensweb-child
 * Description: Lab page. Experiment cards (brief, tech chips, link-out button), seeded with the Florida Risk Explorer.
 */
?>
<!-- wp:html -->
<section class="section section--dark page-header">
  <div class="hero-bg hero-bg--ribbons" aria-hidden="true">
    <svg class="hero-bg__svg" viewBox="0 0 1200 600" preserveAspectRatio="xMidYMid slice" focusable="false" aria-hidden="true">
      <defs><filter id="rbGlow" x="-10%" y="-40%" width="120%" height="180%"><feGaussianBlur stdDeviation="10"/></filter></defs>
      <g fill="none" stroke="currentColor" filter="url(#rbGlow)" stroke-opacity="0.45">
        <path d="M-20 380 C 240 180, 460 520, 700 340 S 1080 180, 1220 300" stroke-width="5"/>
        <path d="M-20 300 C 260 460, 440 180, 720 360 S 1040 460, 1220 250" stroke-width="5"/>
      </g>
      <g fill="none" stroke="currentColor" stroke-linecap="round">
        <path d="M-20 380 C 240 180, 460 520, 700 340 S 1080 180, 1220 300" stroke-width="2.4" stroke-opacity="0.9"/>
        <path d="M-20 300 C 260 460, 440 180, 720 360 S 1040 460, 1220 250" stroke-width="2.4" stroke-opacity="0.8"/>
        <path d="M-20 440 C 260 300, 440 560, 720 420 S 1040 340, 1220 410" stroke-width="1.6" stroke-opacity="0.55"/>
        <path d="M-20 220 C 240 360, 480 140, 720 280 S 1040 360, 1220 180" stroke-width="1.6" stroke-opacity="0.5"/>
      </g>
      <g fill="#fff">
        <circle cx="700" cy="340" r="2.6" fill-opacity="0.9"/>
        <circle cx="360" cy="470" r="2" fill-opacity="0.7"/>
        <circle cx="940" cy="300" r="2" fill-opacity="0.7"/>
      </g>
    </svg>
  </div>
  <div class="wrap">
    <div class="section-head">
      <h1 class="h2">Playground</h1>
      <p class="lede">Things I build and explore for fun. Small experiments, data toys, and interactive pieces.</p>
    </div>
  </div>
</section>

<section class="section section--light">
  <div class="wrap">
    <div class="play-grid">
      <article class="play-card">
        <div class="play-card__viz" aria-hidden="true"><span>Live demo</span></div>
        <div class="play-card__body">
          <h3>Florida Risk Explorer</h3>
          <p>Every hazard, every county. An interactive map for exploring natural and built risk across Florida, with layered legends and a per-county readout.</p>
          <ul class="play-card__tech"><li>Vanilla JS</li><li>Interactive Map</li><li>Open Data</li></ul>
          <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/florida-risk-explorer.html' ); ?>" target="_blank" rel="noopener">
            Open the live map
            <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
          </a>
        </div>
      </article>

      <article class="play-card play-card--soon">
        <div class="play-card__viz" aria-hidden="true"><span>Coming soon</span></div>
        <div class="play-card__body">
          <h3>Next experiment</h3>
          <p>Another small build is on the way. Swap this placeholder for a real experiment when it ships.</p>
          <ul class="play-card__tech"><li>TBC</li></ul>
        </div>
      </article>

      <article class="play-card play-card--soon">
        <div class="play-card__viz" aria-hidden="true"><span>Coming soon</span></div>
        <div class="play-card__body">
          <h3>Next experiment</h3>
          <p>Another small build is on the way. Swap this placeholder for a real experiment when it ships.</p>
          <ul class="play-card__tech"><li>TBC</li></ul>
        </div>
      </article>
    </div>
  </div>
</section>
<!-- /wp:html -->
