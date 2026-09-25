<?php
/**
 * Title: About the club: two photographs
 * Slug: horseclub/about-club
 * Categories: horseclub-sections
 * Keywords: about, story
 * Description: Two photographs set at different heights beside the club's story and a button.
 *
 * @package Horseclub
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"horseclub-about","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull horseclub-about" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:columns {"isStackedOnMobile":false,"className":"horseclub-about-photos","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns is-not-stacked-on-mobile horseclub-about-photos"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"aspectRatio":"263/400","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/about-1.webp' ) ); ?>" alt="A rider in a helmet on a chestnut horse in a sand arena, her instructor walking behind" style="aspect-ratio:263/400;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"className":"horseclub-about-photos__low"} -->
<div class="wp-block-column horseclub-about-photos__low"><!-- wp:image {"aspectRatio":"263/400","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/about-2.webp' ) ); ?>" alt="A woman in a blue dress with a garland of flowers, holding the bridle of a grey horse" style="aspect-ratio:263/400;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"50%","className":"horseclub-about-text"} -->
<div class="wp-block-column is-vertically-aligned-center horseclub-about-text" style="flex-basis:50%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"is-style-horseclub-eyebrow","textColor":"primary"} -->
<p class="is-style-horseclub-eyebrow has-primary-color has-text-color">About the club</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Riding for every age <br>and every ability</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"horseclub-lede"} -->
<p class="horseclub-lede">Founded by a family of riders in 1987, and still run by one.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Horse Club began with six ponies and a borrowed field. Today it is an approved riding school and livery yard on three hundred acres, with an indoor school, a cross-country course and a clubhouse where parents can watch lessons over a coffee. What has not changed is the order of things: the horses come first, the riders a close second, and everyone goes home knowing a little more than when they arrived.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-horseclub-dark"} -->
<div class="wp-block-button is-style-horseclub-dark"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">Meet the team</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
