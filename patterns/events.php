<?php
/**
 * Title: Events: four alternating rows
 * Slug: horseclub/events
 * Categories: horseclub-sections
 * Keywords: events, shows, calendar
 * Description: Four events, photograph and details alternating sides, each with a date and a button.
 *
 * @package Horseclub
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"},"anchor":"events"} -->
<div class="wp-block-group alignfull" id="events" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:group {"className":"horseclub-section-head","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"730px"}} -->
<div class="wp-block-group horseclub-section-head"><!-- wp:heading {"style":{"typography":{"textAlign":"center"}}} -->
<h2 class="wp-block-heading has-text-align-center">Upcoming events at the club</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center">Everyone is welcome to watch. Entries close a week before each event.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:columns {"verticalAlignment":"center","className":"horseclub-event","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center horseclub-event"><!-- wp:column {"verticalAlignment":"center","className":"horseclub-event__photo"} -->
<div class="wp-block-column is-vertically-aligned-center horseclub-event__photo"><!-- wp:image {"aspectRatio":"555/180","scale":"cover","sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"8px"}}} -->
<figure class="wp-block-image size-large has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/event-1.webp' ) ); ?>" alt="A chestnut horse with a white blaze, cattle blurred in the field behind" style="border-radius:8px;aspect-ratio:555/180;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size">Spring show jumping league</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"horseclub-event__meta"} -->
<p class="horseclub-event__meta"><strong>12th April</strong> at the outdoor arena</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Four rounds across the spring, from 60cm clear-round to the 1m open, with a league table and rosettes to sixth.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"horseclub-caps"} -->
<div class="wp-block-button horseclub-caps"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">View details</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:columns {"verticalAlignment":"center","className":"horseclub-event","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center horseclub-event"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size">Open day at the yard</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"horseclub-event__meta"} -->
<p class="horseclub-event__meta"><strong>3rd May</strong> at Linden Farm</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Meet the horses, try a free ten-minute pony ride and watch a demonstration from our instructors.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"horseclub-caps"} -->
<div class="wp-block-button horseclub-caps"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">View details</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","className":"horseclub-event__photo"} -->
<div class="wp-block-column is-vertically-aligned-center horseclub-event__photo"><!-- wp:image {"aspectRatio":"555/180","scale":"cover","sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"8px"}}} -->
<figure class="wp-block-image size-large has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/event-2.webp' ) ); ?>" alt="A smiling woman holding out a carrot to a chestnut horse at the edge of a wood" style="border-radius:8px;aspect-ratio:555/180;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:columns {"verticalAlignment":"center","className":"horseclub-event","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center horseclub-event"><!-- wp:column {"verticalAlignment":"center","className":"horseclub-event__photo"} -->
<div class="wp-block-column is-vertically-aligned-center horseclub-event__photo"><!-- wp:image {"aspectRatio":"555/180","scale":"cover","sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"8px"}}} -->
<figure class="wp-block-image size-large has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/event-3.webp' ) ); ?>" alt="A chestnut horse in a halter against the weathered boards of a stable" style="border-radius:8px;aspect-ratio:555/180;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size">Dressage clinic</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"horseclub-event__meta"} -->
<p class="horseclub-event__meta"><strong>24th May</strong> in the indoor school</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>A day of private sessions with a visiting judge, with written feedback for every rider who takes part.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"horseclub-caps"} -->
<div class="wp-block-button horseclub-caps"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">View details</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:columns {"verticalAlignment":"center","className":"horseclub-event","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center horseclub-event"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size">Summer Pony Club rally</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"horseclub-event__meta"} -->
<p class="horseclub-event__meta"><strong>21st June</strong> on the lower fields</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Gymkhana games, a fancy dress class and the handy pony competition. The tea tent is open all afternoon.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"horseclub-caps"} -->
<div class="wp-block-button horseclub-caps"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">View details</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","className":"horseclub-event__photo"} -->
<div class="wp-block-column is-vertically-aligned-center horseclub-event__photo"><!-- wp:image {"aspectRatio":"555/180","scale":"cover","sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"8px"}}} -->
<figure class="wp-block-image size-large has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/event-4.webp' ) ); ?>" alt="A woman in a blue dress beside a grey horse on a woodland path" style="border-radius:8px;aspect-ratio:555/180;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
