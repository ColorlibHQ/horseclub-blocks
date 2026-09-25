<?php
/**
 * Title: Sidebar
 * Slug: horseclub/sidebar
 * Keywords: sidebar
 * Description: Search, categories, recent posts, archive and tags, each in a bordered box.
 * Inserter: no
 *
 * @package Horseclub
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"className":"horseclub-sidebar","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group horseclub-sidebar"><!-- wp:group {"className":"is-style-horseclub-box","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-horseclub-box"><!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search posts","buttonText":"Search","buttonPosition":"button-inside","buttonUseIcon":true} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-horseclub-box","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-horseclub-box"><!-- wp:heading {"fontSize":"large"} -->
<h2 class="wp-block-heading has-large-font-size">Post categories</h2>
<!-- /wp:heading -->

<!-- wp:categories {"showPostCounts":true,"className":"horseclub-counts"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-horseclub-box","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-horseclub-box"><!-- wp:heading {"fontSize":"large"} -->
<h2 class="wp-block-heading has-large-font-size">Recent posts</h2>
<!-- /wp:heading -->

<!-- wp:latest-posts {"postsToShow":4,"displayPostDate":true,"displayFeaturedImage":true,"featuredImageAlign":"left","featuredImageSizeWidth":75,"featuredImageSizeHeight":75,"className":"horseclub-recent"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-horseclub-box","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-horseclub-box"><!-- wp:heading {"fontSize":"large"} -->
<h2 class="wp-block-heading has-large-font-size">Post archive</h2>
<!-- /wp:heading -->

<!-- wp:archives {"showPostCounts":true,"className":"horseclub-counts"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-horseclub-box","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-horseclub-box"><!-- wp:heading {"fontSize":"large"} -->
<h2 class="wp-block-heading has-large-font-size">Tag cloud</h2>
<!-- /wp:heading -->

<!-- wp:tag-cloud {"smallestFontSize":"0.875rem","largestFontSize":"0.875rem","className":"horseclub-tags"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
