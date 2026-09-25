<?php
/**
 * Title: Hero
 * Slug: horseclub/hero
 * Categories: horseclub-sections
 * Keywords: hero, banner
 * Description: Full-screen photograph with a spaced eyebrow, an accent rule, the headline and a button.
 *
 * @package Horseclub
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/hero.webp' ) ); ?>","dimRatio":70,"overlayColor":"dark","isUserOverlayColor":true,"focalPoint":{"x":1,"y":0},"minHeight":88,"minHeightUnit":"vh","align":"full","className":"horseclub-hero horseclub-hero\u002d\u002dfill","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull horseclub-hero horseclub-hero--fill" style="min-height:88vh"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/hero.webp' ) ); ?>" style="object-position:100% 0%" data-object-fit="cover" data-object-position="100% 0%"/><span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-70 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"is-style-horseclub-eyebrow","style":{"typography":{"textAlign":"center"}},"textColor":"overlay"} -->
<p class="has-text-align-center is-style-horseclub-eyebrow has-overlay-color has-text-color">Introducing Horse Club</p>
<!-- /wp:paragraph -->

<!-- wp:separator {"className":"is-style-horseclub-bar"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-horseclub-bar"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":1,"className":"horseclub-hero__title","style":{"typography":{"textAlign":"center"}},"textColor":"overlay","fontSize":"colossal"} -->
<h1 class="wp-block-heading has-text-align-center horseclub-hero__title has-overlay-color has-text-color has-colossal-font-size">The Bond Between <br>Horse &amp; Rider</h1>
<!-- /wp:heading -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Book a lesson</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->
