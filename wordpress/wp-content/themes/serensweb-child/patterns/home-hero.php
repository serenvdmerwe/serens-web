<?php
/**
 * Title: Home - Hero
 * Slug: serensweb-child/home-hero
 * Categories: serensweb-child, banner
 * Description: Dark hero with the headline, sub, two CTAs, a three-stat row, and a decorative code card.
 */
?>
<!-- wp:html -->
<section class="hero section--dark" id="hero" data-screen-label="Hero">
  <div class="hero__mesh" aria-hidden="true"></div>
  <div class="hero__grid" aria-hidden="true"></div>
  <div class="wrap hero__inner">
    <div class="hero__copy reveal">
      <h1>Websites that <em>perform</em> as well as they look.</h1>
      <p class="hero__sub">I design and build fast, modern, conversion-focused sites and web apps, from headless commerce to AI-powered tools. Independent, end to end, shipped with care.</p>
      <div class="hero__cta">
        <a href="/contact" class="btn btn--primary">
          Start a project
          <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
        <a href="#work" class="btn btn--ghost">View selected work</a>
      </div>
      <div class="hero__stats">
        <div class="hero__stat"><div class="n"><em>8</em>+ yrs</div><div class="l">Shipping for clients</div></div>
        <div class="hero__stat"><div class="n"><em>40</em>+</div><div class="l">Projects delivered</div></div>
        <div class="hero__stat"><div class="n"><em>98</em></div><div class="l">Avg. Lighthouse</div></div>
      </div>
    </div>

    <div class="codecard reveal" aria-hidden="true">
      <div class="codecard__bar">
        <div class="codecard__dots"><i></i><i></i><i></i></div>
        <div class="codecard__url">serensweb.dev</div>
      </div>
      <div class="codecard__body">
<span class="ln"><span class="dim">// ship.config.ts</span></span>
<span class="ln"><span class="key">export const</span> stack = {</span>
<span class="ln">  framework: <span class="str">"headless"</span>,</span>
<span class="ln">  perf: <span class="tag">"&lt;1s LCP"</span>,</span>
<span class="ln">  a11y: <span class="str">"WCAG AA"</span>,</span>
<span class="ln">  deploy: <span class="str">"edge / global"</span>,</span>
<span class="ln">}</span>
<span class="ln"><span class="dim">// status:</span> <span class="str">shipped</span> <span class="codecard__caret"></span></span>
      </div>
    </div>
  </div>
</section>
<!-- /wp:html -->
