<?php
/**
 * SerensWeb, child of Twenty Twenty-Five.
 *
 * FSE block-theme child. Sections live in /patterns, layout in /templates and
 * /parts, design tokens in theme.json, and the prototype CSS is preserved in
 * /assets/css so the class hooks from the handoff keep working verbatim.
 *
 * functions.php is a thin loader. Real work lives in /includes:
 *   schema.php        JSON-LD structured data
 *   enqueue.php       front-end and editor asset registration
 *   patterns.php      block pattern registration
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/includes/schema.php';
require_once __DIR__ . '/includes/enqueue.php';
require_once __DIR__ . '/includes/patterns.php';
// Add optional includes here once the corresponding feature lands.
