<?php
/**
 * Title: Blog banner
 * Slug: horseclub/hidden-blog-banner
 * Description: The heading for the posts page.
 * Inserter: no
 *
 * @package Horseclub
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/hero.webp' ) ); ?>","dimRatio":70,"overlayColor":"dark","isUserOverlayColor":true,"focalPoint":{"x":1,"y":0},"minHeight":346,"align":"full","className":"horseclub-banner","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull horseclub-banner" style="min-height:346px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/hero.webp' ) ); ?>" style="object-position:100% 0%" data-object-fit="cover" data-object-position="100% 0%"/><span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-70 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"}},"textColor":"overlay"} -->
<h1 class="wp-block-heading has-text-align-center has-overlay-color has-text-color">Blog</h1>
<!-- /wp:heading -->

<!-- wp:group {"className":"horseclub-breadcrumb","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center","verticalAlignment":"center"}} -->
<div class="wp-block-group horseclub-breadcrumb"><!-- wp:paragraph {"className":"horseclub-breadcrumb__home","textColor":"overlay"} -->
<p class="horseclub-breadcrumb__home has-overlay-color has-text-color"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"textColor":"overlay"} -->
<p class="has-overlay-color has-text-color">Blog</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->
