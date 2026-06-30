<?php
/**
 * Title: About
 * Slug: serensweb-child/about
 * Categories: serensweb-child
 * Description: About page. Studio statement, supporting copy, photo placeholder, core stack chips, and links to the playground and contact.
 */
?>
<!-- wp:html -->
<section class="section section--dark page-header">
  <div class="hero-bg hero-bg--cloud" aria-hidden="true">
    <svg class="hero-bg__svg" viewBox="0 0 1200 600" preserveAspectRatio="none" focusable="false" aria-hidden="true">
      <defs>
        <filter id="abCloud" x="0" y="0" width="100%" height="100%">
          <feTurbulence type="fractalNoise" baseFrequency="0.011 0.016" numOctaves="4" seed="11" stitchTiles="stitch"/>
          <feColorMatrix type="matrix" values="0 0 0 0 0.043 0 0 0 0 0.043 0 0 0 0 0.055 2.4 0 0 0 -0.35"/>
        </filter>
      </defs>
      <rect width="1200" height="600" filter="url(#abCloud)"/>
    </svg>
  </div>
  <div class="wrap">
    <div class="section-head">
      <h1 class="h2">About</h1>
      <p class="lede">I'm Seren, an AI-augmented web developer. I design, build, and ship production sites and web apps end to end, using agentic AI workflows to move fast without cutting corners.</p>
    </div>
  </div>
</section>

<section class="section section--light">
  <div class="wrap about-page__grid">
    <div class="about-page__photo" aria-hidden="true"><span>Photo</span></div>
    <div class="about-page__copy">
      <p class="about__statement">Strategy, design and code from one <em>accountable developer.</em></p>
      <p>I work directly with founders, marketers and product teams to turn ideas into polished, maintainable software. No handoffs, no bloat, just clean work that ships. I lean on agentic AI workflows (Claude Code, MCP, and CI/CD pipelines) to build and review faster, across custom WordPress themes, React and Next.js apps, and AI-driven features.</p>
      <h3 class="about__stack-title">Core stack</h3>
      <ul class="stack">
        <li>PHP</li><li>TypeScript</li><li>React</li><li>Next.js</li>
        <li>Node</li><li>WordPress</li><li>Tailwind</li><li>MySQL</li>
        <li>REST APIs</li><li>Docker</li><li>GitHub Actions</li><li>Claude Code</li>
      </ul>
      <div class="about-page__links">
        <a class="btn btn--ghost" href="/playground">See the playground
          <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
        </a>
        <a class="btn btn--primary" href="/contact">Start a project
          <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>
    </div>
  </div>
</section>
<!-- /wp:html -->
