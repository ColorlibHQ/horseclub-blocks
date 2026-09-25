<?php
/**
 * Title: Posts list
 * Slug: horseclub/hidden-posts-list
 * Description: The post list used by the blog and every archive: photograph, categories, title, excerpt.
 * Inserter: no
 *
 * @package Horseclub
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:query {"queryId":0,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":true},"className":"horseclub-posts","layout":{"type":"default"}} -->
<div class="wp-block-query horseclub-posts"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|70"}}} -->
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"750/350"} /-->

<!-- wp:post-terms {"term":"category","textColor":"contrast","fontSize":"small"} /-->

<!-- wp:post-title {"isLink":true} /-->

<!-- wp:post-excerpt {"excerptLength":45} /-->

<!-- wp:group {"className":"horseclub-post__meta","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group horseclub-post__meta"><!-- wp:post-date {"metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"fontSize":"small"} /-->

<!-- wp:post-comments-count {"className":"horseclub-comments-count","fontSize":"small"} /--></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p>Nothing here yet. Try a search, or start again from the home page.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results -->

<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"left"}} -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination --></div>
<!-- /wp:query -->
