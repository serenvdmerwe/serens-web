<?php
/**
 * FAQ copy: single source of truth for the visible FAQ section on the
 * contact page (patterns/contact-faq.php) and the FAQPage node in the
 * JSON-LD graph (schema.php). One array, two consumers, so the structured
 * data can never drift from what a visitor actually reads.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Question => answer, in display order. Plain text only: the pattern
 * escapes it and the schema JSON-encodes it.
 */
function serensweb_faq_items(): array {
	return [
		'What does AI-augmented actually mean?' =>
			'I use AI tooling through the whole build: scaffolding, refactoring, tests, content passes. It makes me faster, not hands-off. Architecture, design taste, and the final call on what ships stay human, and I review everything the tools produce.',

		'What do you build?' =>
			'Four things, on purpose: custom WordPress block themes, AI workflows and LLM features, progressive web apps and mobile-first sites, and interactive portfolios and corporate sites. This site is the live sample of that work.',

		'How can we work together?' =>
			'Three ways: full-time, part-time, or per project. The switcher above tunes the form to whichever you pick, and nothing is locked in until we have actually talked.',

		'Do you work remotely?' =>
			'Yes, fully remote from South Africa (UTC+2), which sits within an hour of most of Europe\'s working day.',

		'How fast do you reply?' =>
			'Every serious enquiry gets a personal reply, promptly. WhatsApp is the quickest channel; the form and the email address land in the same inbox.',
	];
}
