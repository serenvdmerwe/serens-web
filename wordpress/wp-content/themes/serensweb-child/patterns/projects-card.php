<?php
/**
 * Title: Projects - Card
 * Slug: serensweb-child/projects-card
 * Categories: serensweb-child
 * Description: Project card (featured image, type, title, excerpt). Renders the current post inside a query loop; used by the archive and the home teaser.
 */
?>
<!-- wp:group {"tagName":"div","className":"proj-card","layout":{"type":"default"}} -->
<div class="wp-block-group proj-card">
	<!-- wp:post-featured-image {"isLink":true,"className":"proj-card__viz"} /-->
	<!-- wp:group {"className":"proj-card__body","layout":{"type":"default"}} -->
	<div class="wp-block-group proj-card__body">
		<!-- wp:post-terms {"term":"project_type","className":"proj-card__cat"} /-->
		<!-- wp:post-title {"isLink":true,"level":3,"className":"proj-card__title"} /-->
		<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":26,"className":"proj-card__excerpt"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
