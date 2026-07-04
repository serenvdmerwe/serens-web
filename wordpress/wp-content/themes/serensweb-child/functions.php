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
 *   ajax-contact.php  REST handler for the contact form (wp_mail)
 *   ajax-planes.php   REST proxy for the planes-overhead playground map
 *   smtp.php          Gmail SMTP transport for wp_mail (env-driven, no plugin)
 *   cpt-projects.php  Projects custom post type + project_type taxonomy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/includes/schema.php';
require_once __DIR__ . '/includes/enqueue.php';
require_once __DIR__ . '/includes/patterns.php';
require_once __DIR__ . '/includes/ajax-contact.php';
require_once __DIR__ . '/includes/ajax-planes.php';
require_once __DIR__ . '/includes/smtp.php';
require_once __DIR__ . '/includes/cpt-projects.php';
