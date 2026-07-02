<?php
/**
 * Title: Project - Single
 * Slug: serensweb-child/project-single
 * Categories: serensweb-child
 * Description: One dark band for a single project: type label, title, featured image, body copy, tags, and footer links. Uses the current project post.
 */
?>
<!-- wp:group {"tagName":"section","className":"section section--dark project-hero","layout":{"type":"default"}} -->
<section class="wp-block-group section section--dark project-hero">
	<!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
	<div class="wp-block-group wrap">
		<!-- wp:post-terms {"term":"project_type","className":"project-hero__cat"} /-->
		<!-- wp:post-title {"level":1,"className":"project-hero__title h2"} /-->
		<!-- wp:post-featured-image {"className":"project-hero__viz"} /-->

		<!-- wp:group {"className":"project-body","layout":{"type":"default"}} -->
		<div class="wp-block-group project-body">
			<!-- wp:post-content {"layout":{"type":"default"}} /-->

			<!-- wp:post-terms {"term":"post_tag","prefix":"","className":"project-tags"} /-->

			<!-- wp:group {"className":"project-foot","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
			<div class="wp-block-group project-foot">
				<!-- wp:html -->
				<a class="btn btn--ghost" href="/projects">Back to all projects</a>
				<a class="btn btn--primary" href="/contact">Start a project
					<svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
				</a>
				<!-- /wp:html -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
