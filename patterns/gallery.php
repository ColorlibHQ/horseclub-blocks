<?php
/**
 * Title: Gallery: six photographs edge to edge
 * Slug: horseclub/gallery
 * Categories: horseclub-sections
 * Keywords: gallery, photos
 * Description: Six photographs across the full width, no gaps, enlarging on click.
 *
 * @package Horseclub
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:gallery {"columns":6,"linkTo":"none","align":"full","className":"horseclub-gallery","style":{"spacing":{"blockGap":{"top":"0","left":"0"}}}} -->
<figure class="wp-block-gallery alignfull has-nested-images columns-6 is-cropped horseclub-gallery"><!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-1.webp' ) ); ?>" alt="A chestnut horse with a white blaze and a leather headcollar, looking out of a wooden stable"/></figure>
<!-- /wp:image -->

<!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-2.webp' ) ); ?>" alt="A rider in a helmet swinging into the saddle of a chestnut horse while an instructor holds it"/></figure>
<!-- /wp:image -->

<!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-3.webp' ) ); ?>" alt="A bridled chestnut horse grazing among fallen autumn leaves"/></figure>
<!-- /wp:image -->

<!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-4.webp' ) ); ?>" alt="A bay horse standing in a sand arena, head turned towards the camera"/></figure>
<!-- /wp:image -->

<!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-5.webp' ) ); ?>" alt="A woman in a blue jacket coiling a lead rope beside two chestnut horses with white blazes"/></figure>
<!-- /wp:image -->

<!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-6.webp' ) ); ?>" alt="A chestnut horse with a flaxen mane resting its head over a white paddock fence"/></figure>
<!-- /wp:image --></figure>
<!-- /wp:gallery -->
