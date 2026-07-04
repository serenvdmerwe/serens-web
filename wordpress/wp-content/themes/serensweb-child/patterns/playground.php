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
          <h3>Risk Explorer: Florida and Cape Town</h3>
          <p>Two cities, one explorer. Tap any Florida county for its hazard report card built on FEMA and NOAA data, or switch to Cape Town and read the Mother City through its own open data: fire station cover, wetlands, and property valuations for 777 suburbs.</p>
          <ul class="play-card__tech"><li>Vanilla JS</li><li>Interactive Map</li><li>Open Data</li></ul>
          <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/florida-risk-explorer.html' ); ?>" target="_blank" rel="noopener">
            Open the live map
            <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
          </a>
        </div>
      </article>

      <article class="play-card">
        <div class="play-card__viz play-card__viz--blue" aria-hidden="true"><span>Live demo</span></div>
        <div class="play-card__body">
          <h3>Hurricane Tracks Time Machine</h3>
          <p>Fifty seasons of Atlantic hurricanes from NOAA's best-track archive, animated over a coastline map. Scrub the years, ghost past seasons, and tap any track for the storm behind it.</p>
          <ul class="play-card__tech"><li>SVG</li><li>Open Data</li><li>Animation</li></ul>
          <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/hurricane-tracks.html' ); ?>" target="_blank" rel="noopener">
            Open the map
            <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
          </a>
        </div>
      </article>

      <article class="play-card">
        <div class="play-card__viz play-card__viz--gold" aria-hidden="true"><span>Live demo</span></div>
        <div class="play-card__body">
          <h3>How a WordPress Page Loads</h3>
          <p>A scrollytelling tour from keystroke to paint. Scroll and watch the request travel through DNS, caches, PHP, and the database, including the shortcut most visitors take.</p>
          <ul class="play-card__tech"><li>SVG</li><li>Scrollytelling</li><li>WordPress</li></ul>
          <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/wp-page-load.html' ); ?>" target="_blank" rel="noopener">
            Take the tour
            <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
          </a>
        </div>
      </article>

      <article class="play-card">
        <div class="play-card__viz play-card__viz--violet" aria-hidden="true"><span>Live demo</span></div>
        <div class="play-card__body">
          <h3>Contrast: the Game</h3>
          <p>Guess whether text passes WCAG AA before the fuse burns out. Ten rounds that start friendly and end hugging the 4.5 to 1 boundary, with the real math revealed after every guess.</p>
          <ul class="play-card__tech"><li>Vanilla JS</li><li>Accessibility</li><li>Game</li></ul>
          <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/contrast-game.html' ); ?>" target="_blank" rel="noopener">
            Play the game
            <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
          </a>
        </div>
      </article>

      <article class="play-card">
        <div class="play-card__viz play-card__viz--green" aria-hidden="true"><span>Live demo</span></div>
        <div class="play-card__body">
          <h3>Dev Typing Test</h3>
          <p>How fast do you type real code? PHP, JavaScript, and CSS snippets with per-character feedback, live WPM and accuracy, and indentation typed for you.</p>
          <ul class="play-card__tech"><li>Vanilla JS</li><li>Geist Mono</li><li>Game</li></ul>
          <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/dev-typing-test.html' ); ?>" target="_blank" rel="noopener">
            Start typing
            <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
          </a>
        </div>
      </article>

      <article class="play-card">
        <div class="play-card__viz play-card__viz--cable" aria-hidden="true"><span>Live demo</span></div>
        <div class="play-card__body">
          <h3>How South Africa Reaches the Internet</h3>
          <p>Six submarine cables connect this country to the web. Tap each one for its story, then relive the day in March 2024 when the west coast went dark and the east coast quietly carried the load.</p>
          <ul class="play-card__tech"><li>SVG</li><li>Storytelling</li><li>Infrastructure</li></ul>
          <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/submarine-cables.html' ); ?>" target="_blank" rel="noopener">
            Follow the cables
            <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
          </a>
        </div>
      </article>

      <article class="play-card">
        <div class="play-card__viz play-card__viz--teal" aria-hidden="true"><span>Installable app</span></div>
        <div class="play-card__body">
          <h3>Aptitude Trainer</h3>
          <p>An installable app that generates endless matrix logic, number sequence, and mental rotation puzzles on your device, with difficulty that adapts to you. Works fully offline once installed. It trains test patterns; it does not measure intelligence.</p>
          <ul class="play-card__tech"><li>PWA</li><li>Service Worker</li><li>Offline</li></ul>
          <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/aptitude-trainer/index.html' ); ?>" target="_blank" rel="noopener">
            Open the trainer
            <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
          </a>
        </div>
      </article>

      <article class="play-card">
        <div class="play-card__viz play-card__viz--clay" aria-hidden="true"><span>Live demo</span></div>
        <div class="play-card__body">
          <h3>AI Feature Cost Estimator</h3>
          <p>Pick a use case, drag the sliders, and see what a Claude-powered feature would cost per month across four model tiers. Real July 2026 list prices, honest arithmetic.</p>
          <ul class="play-card__tech"><li>Vanilla JS</li><li>AI</li><li>Calculator</li></ul>
          <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/ai-cost-estimator.html' ); ?>" target="_blank" rel="noopener">
            Open the estimator
            <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
          </a>
        </div>
      </article>
      <article class="play-card">
        <div class="play-card__viz play-card__viz--ember" aria-hidden="true"><span>Live data</span></div>
        <div class="play-card__body">
          <h3>Live Earthquake Map</h3>
          <p>Every earthquake the USGS has catalogued in the past day or week, fetched by your browser the moment you open the page. Circle size is magnitude, color is depth, and the three strongest pulse.</p>
          <ul class="play-card__tech"><li>Live API</li><li>SVG</li><li>Open Data</li></ul>
          <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/earthquakes-live.html' ); ?>" target="_blank" rel="noopener">
            Watch the planet
            <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
          </a>
        </div>
      </article>
    </div>
  </div>
</section>
<!-- /wp:html -->
