<?php
/**
 * Title: Pricing: four numbered plans
 * Slug: horseclub/pricing
 * Categories: horseclub-sections
 * Keywords: pricing, plans, membership
 * Description: Four plans, each with a numbered gradient badge, a ruled feature list and a price.
 *
 * @package Horseclub
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"backgroundColor":"surface","layout":{"type":"constrained"},"anchor":"pricing"} -->
<div class="wp-block-group alignfull has-surface-background-color has-background" id="pricing" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:group {"className":"horseclub-section-head","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"730px"}} -->
<div class="wp-block-group horseclub-section-head"><!-- wp:heading {"style":{"typography":{"textAlign":"center"}}} -->
<h2 class="wp-block-heading has-text-align-center">Choose the plan that suits you</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center">Every plan includes a hat and boots on loan, arena time and the clubhouse.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:columns {"className":"horseclub-prices","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns horseclub-prices"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"is-style-horseclub-price","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-horseclub-price"><!-- wp:paragraph {"className":"horseclub-price__no","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center horseclub-price__no">01</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"style":{"typography":{"textAlign":"center"}},"fontSize":"large"} -->
<h3 class="wp-block-heading has-text-align-center has-large-font-size">Taster</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"horseclub-price__who","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center horseclub-price__who">For first-time riders</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-horseclub-lines"} -->
<ul class="wp-block-list is-style-horseclub-lines"><!-- wp:list-item -->
<li>A private half-hour lesson</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>A tour of the yard</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Hat and boots on loan</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph {"className":"horseclub-price__amount","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center horseclub-price__amount">£45.00</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"horseclub-price__link","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center horseclub-price__link"><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Choose plan</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"is-style-horseclub-price","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-horseclub-price"><!-- wp:paragraph {"className":"horseclub-price__no","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center horseclub-price__no">02</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"style":{"typography":{"textAlign":"center"}},"fontSize":"large"} -->
<h3 class="wp-block-heading has-text-align-center has-large-font-size">Rider</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"horseclub-price__who","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center horseclub-price__who">For regular riders</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-horseclub-lines"} -->
<ul class="wp-block-list is-style-horseclub-lines"><!-- wp:list-item -->
<li>Four group lessons a month</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Priority on clinic places</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Members' hacks at weekends</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph {"className":"horseclub-price__amount","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center horseclub-price__amount">£149.00</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"horseclub-price__link","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center horseclub-price__link"><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Choose plan</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"is-style-horseclub-price","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-horseclub-price"><!-- wp:paragraph {"className":"horseclub-price__no","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center horseclub-price__no">03</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"style":{"typography":{"textAlign":"center"}},"fontSize":"large"} -->
<h3 class="wp-block-heading has-text-align-center has-large-font-size">Family</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"horseclub-price__who","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center horseclub-price__who">For two riders or more</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-horseclub-lines"} -->
<ul class="wp-block-list is-style-horseclub-lines"><!-- wp:list-item -->
<li>Eight group lessons a month</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Pony Club rallies included</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>10% off holiday camps</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph {"className":"horseclub-price__amount","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center horseclub-price__amount">£259.00</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"horseclub-price__link","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center horseclub-price__link"><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Choose plan</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"is-style-horseclub-price","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-horseclub-price"><!-- wp:paragraph {"className":"horseclub-price__no","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center horseclub-price__no">04</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"style":{"typography":{"textAlign":"center"}},"fontSize":"large"} -->
<h3 class="wp-block-heading has-text-align-center has-large-font-size">Livery</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"horseclub-price__who","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center horseclub-price__who">For horse owners</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-horseclub-lines"} -->
<ul class="wp-block-list is-style-horseclub-lines"><!-- wp:list-item -->
<li>Stable, turnout and hay</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Daily checks and rugs</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Arena and hacking access</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph {"className":"horseclub-price__amount","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center horseclub-price__amount">£495.00</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"horseclub-price__link","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center horseclub-price__link"><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Choose plan</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
