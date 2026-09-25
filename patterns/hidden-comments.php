<?php
/**
 * Title: Comments
 * Slug: horseclub/hidden-comments
 * Description: The comments and the reply form for a single post.
 * Inserter: no
 *
 * @package Horseclub
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:comments {"className":"horseclub-comments"} -->
<div class="wp-block-comments horseclub-comments"><!-- wp:comments-title {"fontSize":"medium"} /-->

<!-- wp:comment-template -->
<!-- wp:columns {"isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns is-not-stacked-on-mobile"><!-- wp:column {"width":"60px"} -->
<div class="wp-block-column" style="flex-basis:60px"><!-- wp:avatar {"size":60} /--></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:comment-author-name {"fontSize":"medium"} /-->

<!-- wp:comment-date {"fontSize":"small"} /-->

<!-- wp:comment-content /-->

<!-- wp:comment-reply-link {"className":"horseclub-reply","fontSize":"x-small"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
<!-- /wp:comment-template -->

<!-- wp:comments-pagination {"layout":{"type":"flex","justifyContent":"left"}} -->
<!-- wp:comments-pagination-previous /-->

<!-- wp:comments-pagination-numbers /-->

<!-- wp:comments-pagination-next /-->
<!-- /wp:comments-pagination -->

<!-- wp:post-comments-form /--></div>
<!-- /wp:comments -->
