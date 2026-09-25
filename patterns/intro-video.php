<?php
/**
 * Title: Introduction with video
 * Slug: horseclub/intro-video
 * Categories: horseclub-sections
 * Keywords: about, video, intro
 * Description: An introduction beside a photograph with a play button that opens the film.
 *
 * @package Horseclub
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"horseclub-intro","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull horseclub-intro" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"is-style-horseclub-eyebrow","textColor":"primary"} -->
<p class="is-style-horseclub-eyebrow has-primary-color has-text-color">Riding school &amp; livery yard</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">A yard built <br>around the horse</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"horseclub-lede"} -->
<p class="horseclub-lede">Forty horses, eleven instructors and one standard of care.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>We teach complete beginners and affiliated competitors on horses that are fit, happy and schooled for the job. Visitors are welcome at the yard on any day we are open.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-horseclub-dark"} -->
<div class="wp-block-button is-style-horseclub-dark"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Plan a visit</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/beach.webp' ) ); ?>","dimRatio":30,"overlayColor":"dark","isUserOverlayColor":true,"minHeight":330,"className":"horseclub-film","layout":{"type":"constrained"}} -->
<div class="wp-block-cover horseclub-film" style="min-height:330px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/beach.webp' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-30 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"horseclub-video horseclub-play"} -->
<div class="wp-block-button horseclub-video horseclub-play"><a class="wp-block-button__link wp-element-button" href="https://www.youtube.com/watch?v=zXRwZotweis"><span class="screen-reader-text">Play the film: a riding school at work</span></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
