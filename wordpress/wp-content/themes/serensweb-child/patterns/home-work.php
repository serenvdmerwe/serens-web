<?php
/**
 * Title: Home - Work
 * Slug: serensweb-child/home-work
 * Categories: serensweb-child
 * Description: Featured projects teaser (latest three) linking to the full /projects archive. Replaces the old placeholder grid and lightbox.
 */
?>
<!-- wp:group {"tagName":"section","className":"section section--light","layout":{"type":"default"},"metadata":{"name":"Home - Work teaser"}} -->
<section class="wp-block-group section section--light" id="work">
	<!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
	<div class="wp-block-group wrap">
		<!-- wp:html -->
		<div class="section-head reveal">
			<h2 class="h2">A few recent builds.</h2>
			<p class="lede">A growing portfolio. Here is what I have been building.</p>
		</div>
		<!-- /wp:html -->

		<!-- wp:query {"queryId":2,"query":{"perPage":3,"pages":0,"offset":0,"postType":"project","order":"desc","orderBy":"date","inherit":false}} -->
		<div class="wp-block-query">
			<!-- wp:post-template {"className":"projects-grid"} -->
				<!-- wp:pattern {"slug":"serensweb-child/projects-card"} /-->
			<!-- /wp:post-template -->
			<!-- wp:query-no-results -->
				<!-- wp:html -->
				<p class="projects-empty">Case studies are on the way. In the meantime, see what I build in the strengths above.</p>
				<!-- /wp:html -->
			<!-- /wp:query-no-results -->
		</div>
		<!-- /wp:query -->

		<!-- wp:html -->
		<div class="work-more">
			<a class="btn btn--ghost" href="/projects">View all projects
				<svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
			</a>
		</div>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
