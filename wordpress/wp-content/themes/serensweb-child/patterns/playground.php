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
