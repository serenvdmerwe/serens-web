# Wind map legibility: brighter continents, country borders, location pin, wind/map toggle

Date: 2026-07-06
Scope: `assets/playground/wind-particles.html` only. The Florida and Cape Town
zoom (a separate SVG demo, `florida-risk-explorer.html`) is a follow-up PR.

## Problem

Visitors who are not map hobbyists cannot orient on the wind demo. Two real
testers reported the same thing: "the countries here are too dark to see, can
you brighten the landmass?" The root cause is twofold: the continents were
filled near-black (`#1b2836` on an `#080c11` sea), and `drawCoast()` ran once
while the animation loop painted a translucent dark rectangle every frame to
make the trails linger, slowly dissolving the coastline toward the sea color.

## Decisions

- Brighten the land and add real country borders, not just a lighter fill.
  Borders come from Natural Earth 110m public-domain data (`ne_110m_land` for
  the filled continents plus coastline, `ne_110m_admin_0_boundary_lines_land`
  for internal borders), projected offline into the file's own equirectangular
  pixel space and baked in as two `Path2D` strings (`LAND`, `BORDERS`),
  replacing the old `COAST` constant. This matches how the coastline was already
  produced: data in, no runtime dependency.
- Give the visitor control. A "Wind + map / Wind only" toggle lets anyone drop
  back to today's pure-particle view. Default is "Wind + map" (the fix); the
  choice persists per visitor in `localStorage` under `sw-wind-map`.
- A "Show my location" button drops a warm accent pin at the visitor's
  coordinates, asking permission only on tap (consistent with the ISS, planes,
  and golden-hour demos). Denial or an absent geolocation API writes an honest
  line into the note box and leaves the map untouched.

## Architecture

The trail-fade cannot coexist with a crisp map on one canvas, so the render
splits into two stacked canvases:

- `#base`: the static map layer (brighter land fill, coastline stroke, country
  borders, location pin). Redrawn only on toggle, resize is not handled because
  the canvas is a fixed 1440x540 scaled by CSS, matching the prior behaviour.
- `#fx`: the moving particles. The fade switched from an opaque dark fill to
  `globalCompositeOperation = "destination-out"`, which lowers the alpha of old
  trail pixels instead of laying down dark ones, so the map below stays visible.

Reduced-motion still renders one calm frame of streamlines, now on `#fx`, with
the map drawn separately on `#base`.

## Content boundaries

Data sources only (Open-Meteo, Natural Earth), no method or stack reveals. The
footer credit becomes "Coastline and country borders from Natural Earth
public-domain data, drawn straight into the canvas." No new claims or metrics.

## Verification

Node syntax check of the extracted script, served page returns 200 with the new
markup and constants, and a browser render confirmed legible continents, visible
borders, working toggle both directions, and no console errors.
