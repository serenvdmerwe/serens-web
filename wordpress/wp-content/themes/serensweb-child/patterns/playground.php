<?php
/**
 * Title: Playground
 * Slug: serensweb-child/playground
 * Categories: serensweb-child
 * Description: Lab page. One page, four category sections (maps, games, tools, apps) with a jump nav; each experiment is a card with brief, tech chips, and a link-out button.
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
      <p class="lede">Things I build and explore for fun: live maps, small games, honest calculators, and apps you can install. Everything runs on open data and browser APIs.</p>
      <nav class="play-nav" aria-label="Playground categories">
        <a href="#maps">Maps</a>
        <a href="#games">Games</a>
        <a href="#tools">Tools</a>
        <a href="#apps">Apps</a>
      </nav>
    </div>
  </div>
</section>

<section class="section section--light">
  <div class="wrap">

    <div class="play-section" id="maps">
      <h2>Interactive maps and data stories</h2>
      <p class="play-blurb">Live feeds, open government data, and browser APIs turned into maps you can poke: hurricanes, earthquakes, aircraft, the Space Station, the sun, and the wind.</p>
      <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
        <defs>
          <filter id="play-glow" x="-20%" y="-20%" width="140%" height="140%"><feGaussianBlur stdDeviation="1.8"/></filter>
          <g id="play-grid-lines" fill="none" stroke="currentColor" stroke-width="1">
            <path d="M53 6 Q44 100 53 194"/><path d="M107 6 Q101 100 107 194"/><path d="M160 6 L160 194"/>
            <path d="M213 6 Q219 100 213 194"/><path d="M267 6 Q276 100 267 194"/>
            <path d="M6 48 Q160 42 314 48"/><path d="M6 84 Q160 79 314 84"/><path d="M6 120 Q160 115 314 120"/><path d="M6 156 Q160 151 314 156"/>
          </g>
          <symbol id="play-grid" viewBox="0 0 320 200">
            <use href="#play-grid-lines" filter="url(#play-glow)" opacity="0.55"/>
            <use href="#play-grid-lines" opacity="0.32"/>
          </symbol>
        </defs>
      </svg>
      <div class="play-grid">
        <article class="play-card">
          <div class="play-card__viz play-card__viz--primary" aria-hidden="true">
            <svg class="play-motif play-motif--primary" viewBox="0 0 320 200" aria-hidden="true" focusable="false">
              <use href="#play-grid"/>
              <g fill="none" stroke="currentColor" stroke-width="1" opacity="0.25">
                <rect x="72" y="64" width="176" height="86" rx="4"/>
                <path d="M130 64 V150 M190 64 V150 M72 93 H248 M72 121 H248"/>
              </g>
              <circle class="ping anim" cx="118" cy="86" r="16" fill="none" stroke="currentColor" stroke-width="1.4" opacity="0.55"/>
              <g fill="currentColor">
                <path d="M118 70 c-8 0 -14 6 -14 14 c0 10 14 24 14 24 c0 0 14 -14 14 -24 c0 -8 -6 -14 -14 -14 z"/>
                <circle cx="118" cy="84" r="4.5" fill="#0b0b0e"/>
                <path d="M206 100 c-7 0 -12 5 -12 12 c0 9 12 21 12 21 c0 0 12 -12 12 -21 c0 -7 -5 -12 -12 -12 z" opacity="0.65"/>
                <circle cx="206" cy="112" r="3.6" fill="#0b0b0e"/>
              </g>
            </svg>
            <span>Live demo</span>
          </div>
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
          <div class="play-card__viz play-card__viz--blue" aria-hidden="true">
            <svg class="play-motif play-motif--blue" viewBox="0 0 320 200" aria-hidden="true" focusable="false">
              <use href="#play-grid"/>
              <g fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-dasharray="2 6" opacity="0.7">
                <path d="M20 150 C90 140 150 120 200 96"/>
                <path d="M30 176 C110 160 170 130 210 92" opacity="0.5"/>
              </g>
              <g class="eye anim" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                <path d="M214 78 a10 10 0 1 1 -8 4"/>
                <path d="M214 98 a10 10 0 1 1 8 -4"/>
                <circle cx="214" cy="88" r="2.4" fill="currentColor" stroke="none"/>
              </g>
            </svg>
            <span>Live demo</span>
          </div>
          <div class="play-card__body">
            <h3>Hurricane Tracks Time Machine</h3>
            <p>Fifty seasons of Atlantic hurricanes from NOAA's best-track archive, animated over a coastline map. Scrub the years, ghost past seasons, and tap any track for the storm behind it, with story lines on the monsters drafted by Claude from the data itself.</p>
            <ul class="play-card__tech"><li>SVG</li><li>Open Data</li><li>AI Workflow</li></ul>
            <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/hurricane-tracks.html' ); ?>" target="_blank" rel="noopener">
              Open the map
              <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
            </a>
          </div>
        </article>

        <article class="play-card">
          <div class="play-card__viz play-card__viz--cable" aria-hidden="true">
            <svg class="play-motif play-motif--cable" viewBox="0 0 320 200" aria-hidden="true" focusable="false">
              <use href="#play-grid"/>
              <g fill="none" stroke="currentColor" stroke-linecap="round">
                <path d="M64 150 C120 120 200 92 300 74" stroke-width="1.6" opacity="0.85"/>
                <path d="M64 150 C130 140 210 130 306 118" stroke-width="1.3" opacity="0.5"/>
                <path d="M64 150 C120 158 210 168 306 150" stroke-width="1.3" opacity="0.5"/>
                <path d="M64 150 C110 128 180 118 250 60" stroke-width="1.2" opacity="0.4"/>
              </g>
              <circle cx="64" cy="150" r="4.5" fill="currentColor"/>
              <g class="pulse anim"><circle cx="0" cy="0" r="3.2" fill="#eafaff"/></g>
            </svg>
            <span>Live demo</span>
          </div>
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
          <div class="play-card__viz play-card__viz--ember" aria-hidden="true">
            <svg class="play-motif play-motif--ember" viewBox="0 0 320 200" aria-hidden="true" focusable="false">
              <use href="#play-grid"/>
              <g>
                <circle cx="92" cy="126" r="4" fill="#ffc857"/><circle cx="122" cy="108" r="3" fill="#ff7043"/>
                <circle cx="176" cy="90" r="3.4" fill="#ffc857"/><circle cx="212" cy="120" r="4.6" fill="#9b8cff"/>
                <circle cx="246" cy="82" r="3" fill="#ff7043"/><circle cx="72" cy="150" r="2.6" fill="#9b8cff"/>
                <circle cx="134" cy="70" r="9" fill="#ff7043" stroke="#ffe0cf" stroke-width="1.4"/>
                <circle cx="230" cy="150" r="8" fill="#ffc857" stroke="#ffe0cf" stroke-width="1.4"/>
                <circle cx="104" cy="158" r="7" fill="#ff7043" stroke="#ffe0cf" stroke-width="1.4"/>
              </g>
            </svg>
            <span>Live data</span>
          </div>
          <div class="play-card__body">
            <h3>Live Earthquake Map</h3>
            <p>Every earthquake the USGS has catalogued in the past day or week, fetched by your browser the moment you open the page. Circle size is magnitude, color is depth, and the three strongest are outlined.</p>
            <ul class="play-card__tech"><li>Live API</li><li>SVG</li><li>Open Data</li></ul>
            <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/earthquakes-live.html' ); ?>" target="_blank" rel="noopener">
              Watch the planet
              <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
            </a>
          </div>
        </article>

        <article class="play-card">
          <div class="play-card__viz play-card__viz--radar" aria-hidden="true">
            <svg class="play-motif play-motif--radar" viewBox="0 0 320 200" aria-hidden="true" focusable="false">
              <use href="#play-grid"/>
              <g fill="none" stroke="currentColor" opacity="0.4">
                <circle cx="160" cy="100" r="30"/><circle cx="160" cy="100" r="58"/><circle cx="160" cy="100" r="86"/>
                <line x1="160" y1="12" x2="160" y2="188"/><line x1="72" y1="100" x2="248" y2="100"/>
              </g>
              <g class="sweep anim">
                <path d="M160 100 L160 12 A88 88 0 0 1 248 74 Z" fill="url(#radarFade)"/>
                <line x1="160" y1="100" x2="160" y2="14" stroke="currentColor" stroke-width="1.8"/>
              </g>
              <g fill="currentColor">
                <circle cx="198" cy="72" r="2.6"/><circle cx="126" cy="132" r="2.2"/><circle cx="212" cy="128" r="2"/>
              </g>
              <defs>
                <linearGradient id="radarFade" x1="0" y1="0" x2="1" y2="0">
                  <stop offset="0" stop-color="currentColor" stop-opacity="0.32"/>
                  <stop offset="1" stop-color="currentColor" stop-opacity="0"/>
                </linearGradient>
              </defs>
            </svg>
            <span>Live data</span>
          </div>
          <div class="play-card__body">
            <h3>Planes Overhead</h3>
            <p>A radar scope of every aircraft transmitting within 150 nautical miles of you. The flight data travels through this site's own WordPress REST endpoint, because the upstream sources refuse to talk to browsers directly.</p>
            <ul class="play-card__tech"><li>WordPress REST</li><li>Live Data</li><li>Geolocation</li></ul>
            <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/planes-overhead.html' ); ?>" target="_blank" rel="noopener">
              Sweep the sky
              <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
            </a>
          </div>
        </article>

        <article class="play-card">
          <div class="play-card__viz play-card__viz--night" aria-hidden="true">
            <svg class="play-motif play-motif--night" viewBox="0 0 320 200" aria-hidden="true" focusable="false">
              <use href="#play-grid"/>
              <ellipse cx="160" cy="100" rx="110" ry="46" fill="none" stroke="currentColor" stroke-width="1.4" stroke-dasharray="3 6" opacity="0.7"/>
              <path d="M50 100 A110 46 0 0 1 270 100" fill="none" stroke="currentColor" stroke-width="1" opacity="0.25"/>
              <g class="iss anim"><circle cx="0" cy="0" r="3.6" fill="#eaf1ff"/><circle cx="0" cy="0" r="7" fill="none" stroke="currentColor" stroke-width="1"/></g>
            </svg>
            <span>Live data</span>
          </div>
          <div class="play-card__body">
            <h3>ISS Live Tracker</h3>
            <p>The Space Station's live position, this lap's ground track, and the glowing patch of Earth that can see it, polled every few seconds. One tap tells you how far away it is right now.</p>
            <ul class="play-card__tech"><li>Live API</li><li>SVG</li><li>Geolocation</li></ul>
            <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/iss-tracker.html' ); ?>" target="_blank" rel="noopener">
              Find the station
              <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
            </a>
          </div>
        </article>

        <article class="play-card">
          <div class="play-card__viz play-card__viz--dawn" aria-hidden="true">
            <svg class="play-motif play-motif--dawn" viewBox="0 0 320 200" aria-hidden="true" focusable="false">
              <use href="#play-grid"/>
              <circle class="glow anim" cx="160" cy="126" r="34" fill="#ffd98a" opacity="0.26"/>
              <circle cx="160" cy="126" r="20" fill="#ffe08a"/>
              <line x1="24" y1="150" x2="296" y2="150" stroke="#ffe0a8" stroke-width="1.2" opacity="0.6"/>
              <path d="M236 0 L320 0 L320 200 L288 200 Z" fill="#0a0c16" opacity="0.4"/>
            </svg>
            <span>Live demo</span>
          </div>
          <div class="play-card__body">
            <h3>Golden Hour, Everywhere</h3>
            <p>A live day and night map with no API behind it: the browser computes the sun's position itself, then geolocation adds your sunrise, sunset, and next golden hour. Pure astronomy, zero data feeds.</p>
            <ul class="play-card__tech"><li>Astronomy</li><li>Geolocation</li><li>No API</li></ul>
            <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/golden-hour.html' ); ?>" target="_blank" rel="noopener">
              See the light
              <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
            </a>
          </div>
        </article>

        <article class="play-card">
          <div class="play-card__viz play-card__viz--storm" aria-hidden="true">
            <svg class="play-motif play-motif--storm" viewBox="0 0 320 200" aria-hidden="true" focusable="false">
              <use href="#play-grid"/>
              <g class="streaks anim" fill="none" stroke="currentColor" stroke-linecap="round">
                <path d="M40 60 q40 -10 80 0" stroke-width="1.6" opacity="0.8"/>
                <path d="M120 90 q50 -12 100 2" stroke-width="1.5" opacity="0.7"/>
                <path d="M30 120 q60 12 120 -2" stroke-width="1.4" opacity="0.6"/>
                <path d="M150 150 q40 -10 90 4" stroke-width="1.4" opacity="0.55"/>
                <path d="M60 170 q50 8 110 -4" stroke-width="1.2" opacity="0.45"/>
                <path d="M200 46 q30 -8 70 4" stroke-width="1.3" opacity="0.6"/>
              </g>
            </svg>
            <span>Live data</span>
          </div>
          <div class="play-card__body">
            <h3>The Wind, as Particles</h3>
            <p>Thousands of canvas particles riding real surface winds across a world map: trade winds, westerlies, and whatever storm is spinning today, all drawing themselves. One tap fetches the atmosphere as it is right now.</p>
            <ul class="play-card__tech"><li>Canvas</li><li>Live API</li><li>Animation</li></ul>
            <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/wind-particles.html' ); ?>" target="_blank" rel="noopener">
              Release the particles
              <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
            </a>
          </div>
        </article>
      </div>
    </div>

    <div class="play-section" id="games">
      <h2>Browser mini games</h2>
      <p class="play-blurb">Small games that run entirely in your browser and quietly teach a web skill on the side.</p>
      <div class="play-grid">
        <article class="play-card">
          <div class="play-card__viz play-card__viz--violet" aria-hidden="true">
            <svg class="play-motif play-motif--violet" viewBox="0 0 320 200" aria-hidden="true" focusable="false">
              <use href="#play-grid"/>
              <rect x="92" y="46" width="136" height="70" rx="12" fill="none" stroke="currentColor" stroke-width="1.4" opacity="0.7"/>
              <text x="160" y="88" text-anchor="middle" font-family="Geist, sans-serif" font-size="30" font-weight="700" fill="currentColor">Aa</text>
              <g class="choice anim">
                <rect x="98" y="132" width="54" height="24" rx="12" fill="none" stroke="#4ade80" stroke-width="1.4"/>
                <text x="125" y="148" text-anchor="middle" font-family="Geist Mono, monospace" font-size="11" font-weight="700" fill="#4ade80">PASS</text>
                <rect x="168" y="132" width="54" height="24" rx="12" fill="none" stroke="#fb7185" stroke-width="1.4"/>
                <text x="195" y="148" text-anchor="middle" font-family="Geist Mono, monospace" font-size="11" font-weight="700" fill="#fb7185">FAIL</text>
              </g>
            </svg>
            <span>Live demo</span>
          </div>
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
      </div>
    </div>

    <div class="play-section" id="tools">
      <h2>Developer tools and calculators</h2>
      <p class="play-blurb">Practical calculators built the way I build client work: honest numbers, real list prices, no signup.</p>
      <div class="play-grid">
        <article class="play-card">
          <div class="play-card__viz play-card__viz--clay" aria-hidden="true">
            <svg class="play-motif play-motif--clay" viewBox="0 0 320 200" aria-hidden="true" focusable="false">
              <use href="#play-grid"/>
              <g stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity="0.55">
                <line x1="62" y1="70" x2="220" y2="70"/>
                <line x1="62" y1="100" x2="220" y2="100"/>
              </g>
              <circle class="knob anim" cx="120" cy="70" r="7" fill="currentColor"/>
              <circle cx="170" cy="100" r="7" fill="currentColor" opacity="0.85"/>
              <g fill="currentColor" opacity="0.7">
                <rect x="62" y="150" width="16" height="20" rx="2"/>
                <rect x="88" y="138" width="16" height="32" rx="2"/>
                <rect x="114" y="126" width="16" height="44" rx="2"/>
                <rect x="140" y="146" width="16" height="24" rx="2"/>
              </g>
            </svg>
            <span>Live demo</span>
          </div>
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
          <div class="play-card__viz play-card__viz--iris" aria-hidden="true">
            <svg class="play-motif play-motif--iris" viewBox="0 0 320 200" aria-hidden="true" focusable="false">
              <use href="#play-grid"/>
              <g>
                <rect x="72" y="62" width="30" height="30" rx="6" fill="#ff9ec7"/>
                <rect x="110" y="62" width="30" height="30" rx="6" fill="#8ad2ff"/>
                <rect x="148" y="62" width="30" height="30" rx="6" fill="#ffd98a"/>
                <rect x="186" y="62" width="30" height="30" rx="6" fill="#a3e9c4"/>
              </g>
              <rect x="72" y="122" width="144" height="6" rx="3" fill="currentColor" opacity="0.5"/>
              <circle class="knob anim" cx="118" cy="125" r="8" fill="currentColor"/>
            </svg>
            <span>Live demo</span>
          </div>
          <div class="play-card__body">
            <h3>Design Token Re-themer</h3>
            <p>Eight design tokens drive an entire mock landing page: drag the hue, swap the fonts, loosen the spacing, and watch it become a different site. Export the result as CSS custom properties or a WordPress theme.json fragment, with a live WCAG check keeping the palette honest.</p>
            <ul class="play-card__tech"><li>Design Tokens</li><li>OKLCH</li><li>Accessibility</li></ul>
            <a class="btn btn--primary" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/playground/token-rethemer.html' ); ?>" target="_blank" rel="noopener">
              Open the re-themer
              <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
            </a>
          </div>
        </article>
      </div>
    </div>

    <div class="play-section" id="apps">
      <h2>Installable web apps</h2>
      <p class="play-blurb">Progressive web apps you can put on a home screen. Once installed they keep working with no connection at all.</p>
      <div class="play-grid">
        <article class="play-card">
          <div class="play-card__viz play-card__viz--teal" aria-hidden="true">
            <svg class="play-motif play-motif--teal" viewBox="0 0 320 200" aria-hidden="true" focusable="false">
              <use href="#play-grid"/>
              <g fill="none" stroke="currentColor" stroke-width="1.4" opacity="0.35">
                <rect x="112" y="52" width="96" height="96" rx="6"/>
                <path d="M144 52 V148 M176 52 V148 M112 84 H208 M112 116 H208"/>
              </g>
              <g fill="currentColor">
                <circle cx="128" cy="68" r="7"/><rect x="153" y="61" width="14" height="14" rx="2"/><circle cx="192" cy="68" r="7"/>
                <rect x="121" y="93" width="14" height="14" rx="2"/><circle cx="160" cy="100" r="7"/><rect x="185" y="93" width="14" height="14" rx="2"/>
                <circle cx="128" cy="132" r="7"/><rect x="153" y="125" width="14" height="14" rx="2"/>
              </g>
              <text class="q anim" x="192" y="139" text-anchor="middle" font-family="Geist, sans-serif" font-size="22" font-weight="700" fill="currentColor">?</text>
            </svg>
            <span>Installable app</span>
          </div>
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
      </div>
    </div>

  </div>
</section>
<!-- /wp:html -->
