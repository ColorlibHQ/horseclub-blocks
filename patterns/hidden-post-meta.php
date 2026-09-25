<?php
/**
 * Title: Post meta
 * Slug: horseclub/hidden-post-meta
 * Description: Categories, date and author for a single post.
 * Inserter: no
 *
 * @package Horseclub
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"className":"horseclub-post__meta","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group horseclub-post__meta"><!-- wp:post-terms {"term":"category","textColor":"contrast","fontSize":"small"} /-->

<!-- wp:post-date {"metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"fontSize":"small"} /-->

<!-- wp:post-author-name {"className":"horseclub-author","fontSize":"small"} /--></div>
<!-- /wp:group -->
