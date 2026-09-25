<?php
/**
 * Title: Footer
 * Slug: horseclub/footer
 * Keywords: footer, newsletter
 * Block Types: core/template-part/footer
 * Description: Three columns on white — about, contact numbers and a newsletter sign-up — with the copyright line and social squares beneath.
 *
 * @package Horseclub
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"horseclub-footer","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull horseclub-footer has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns"><!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"><!-- wp:heading {"className":"horseclub-footer__title","fontSize":"large"} -->
<h2 class="wp-block-heading horseclub-footer__title has-large-font-size">About us</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Horse Club is a family-run riding school and livery yard in the Oxfordshire countryside, teaching riders of every age since 1987.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:heading {"className":"horseclub-footer__title","fontSize":"large"} -->
<h2 class="wp-block-heading horseclub-footer__title has-large-font-size">Contact us</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Linden Farm, Burford Road, Oxfordshire. The yard is open Tuesday to Sunday, from 8am until dusk.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"horseclub-footer__numbers"} -->
<p class="horseclub-footer__numbers"><a href="tel:+441632960482">01632 960 482</a><br><a href="tel:+441632960517">01632 960 517</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"41.66%"} -->
<div class="wp-block-column" style="flex-basis:41.66%"><!-- wp:heading {"className":"horseclub-footer__title","fontSize":"large"} -->
<h2 class="wp-block-heading horseclub-footer__title has-large-font-size">Newsletter</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Show dates, clinic places and news from the yard, once a month. Never anything else.</p>
<!-- /wp:paragraph -->

<!-- wp:shortcode -->
[horseclub_form type="newsletter"]
<!-- /wp:shortcode --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"className":"horseclub-footer__bottom","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group horseclub-footer__bottom"><!-- wp:paragraph {"className":"horseclub-footer__legal","fontSize":"small"} -->
<p class="horseclub-footer__legal has-small-font-size">© Horse Club. All rights reserved. Theme by <a href="https://colorlib.com/" rel="nofollow">Colorlib</a>.</p>
<!-- /wp:paragraph -->

<!-- wp:social-links {"size":"has-small-icon-size","className":"is-style-horseclub-squares","layout":{"type":"flex","justifyContent":"right","flexWrap":"wrap"}} -->
<ul class="wp-block-social-links has-small-icon-size is-style-horseclub-squares"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

<!-- wp:social-link {"url":"#","service":"x"} /-->

<!-- wp:social-link {"url":"#","service":"instagram"} /-->

<!-- wp:social-link {"url":"#","service":"youtube"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
