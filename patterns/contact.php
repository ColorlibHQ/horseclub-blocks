<?php
/**
 * Title: Contact: map, details and form
 * Slug: horseclub/contact
 * Categories: horseclub-sections
 * Keywords: contact, form, map
 * Description: A map across the width, then the address, phone and email beside the message form.
 *
 * @package Horseclub
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"horseclub-contact","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"backgroundColor":"surface","layout":{"type":"constrained"},"anchor":"contact"} -->
<div class="wp-block-group alignfull horseclub-contact has-surface-background-color has-background" id="contact" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:html -->
<iframe class="horseclub-map" title="Map of the countryside around the yard" loading="lazy" src="https://www.openstreetmap.org/export/embed.html?bbox=-1.7100%2C51.7800%2C-1.5900%2C51.8300&amp;layer=mapnik" style="width:100%;height:445px;border:0"></iframe>
<!-- /wp:html -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d70)"} -->
<div style="height:var(--wp--preset--spacing--70)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns"><!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"horseclub-contact-item horseclub-icon\u002d\u002dhome"} -->
<p class="horseclub-contact-item horseclub-icon--home"><strong>Linden Farm, Burford Road</strong><br>Oxfordshire, United Kingdom</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"horseclub-contact-item horseclub-icon\u002d\u002dphone"} -->
<p class="horseclub-contact-item horseclub-icon--phone"><strong><a href="tel:+441632960482">01632 960 482</a></strong><br>Tuesday to Sunday, 8am to 7pm</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"horseclub-contact-item horseclub-icon\u002d\u002dmail"} -->
<p class="horseclub-contact-item horseclub-icon--mail"><strong><a href="mailto:info@horseclub.com">info@horseclub.com</a></strong><br>Send us your questions any time</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"66.66%"} -->
<div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:shortcode -->
[horseclub_form type="contact" layout="split" button="Send message"]
<!-- /wp:shortcode --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
