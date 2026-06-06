<?php
/**
 * Title: Home - Work
 * Slug: serensweb-child/home-work
 * Categories: serensweb-child, grid
 * Description: Selected work grid (project cards injected by work.js) plus the project lightbox modal.
 */
?>
<!-- wp:html -->
<section class="section section--light" id="work" data-screen-label="Work">
  <div class="wrap">
    <div class="section-head reveal">
      <h2 class="h2">A few recent builds.</h2>
      <p class="lede">Placeholder case studies. Tap any project to preview the detail view.</p>
    </div>
    <div class="work__grid reveal-stagger" id="workGrid">
      <!-- project cards injected by work.js -->
    </div>
  </div>
</section>

<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-labelledby="lbTitle">
  <div class="lightbox__scrim" data-close></div>
  <div class="lightbox__panel">
    <button class="lightbox__close" data-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    <div class="lightbox__viz" id="lbViz"><div class="ovl"></div></div>
    <div class="lightbox__body">
      <span class="cat" id="lbCat"></span>
      <h3 id="lbTitle"></h3>
      <p id="lbDesc"></p>
      <div class="lightbox__tags" id="lbTags"></div>
      <div class="lightbox__metrics" id="lbMetrics"></div>
    </div>
  </div>
</div>
<!-- /wp:html -->
