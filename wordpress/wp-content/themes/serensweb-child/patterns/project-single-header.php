<?php
/**
 * Title: Project - Single Header
 * Slug: serensweb-child/project-single-header
 * Categories: serensweb-child
 * Description: Dark header for a single project: type label, title, and featured image. Uses the current project post.
 */
?>
<!-- wp:group {"tagName":"section","className":"section section--dark project-hero","layout":{"type":"default"}} -->
<section class="wp-block-group section section--dark project-hero">
	<!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
	<div class="wp-block-group wrap">
		<!-- wp:post-terms {"term":"project_type","className":"project-hero__cat"} /-->
		<!-- wp:post-title {"level":1,"className":"project-hero__title h2"} /-->
		<!-- wp:post-featured-image {"className":"project-hero__viz"} /-->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
