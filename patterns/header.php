<?php
/**
 * Title: Header
 * Slug: horseclub/header
 * Keywords: header, navigation
 * Block Types: core/template-part/header
 * Description: The template's two-row header: email and phone in gradient squares either side of the logo, then the navigation centred beneath a rule.
 *
 * @package Horseclub
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"horseclub-header","backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull horseclub-header has-base-background-color has-background"><!-- wp:group {"className":"horseclub-header__top","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group horseclub-header__top"><!-- wp:group {"className":"horseclub-header__side","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group horseclub-header__side"><!-- wp:paragraph {"placeholder":" ","className":"horseclub-header__square horseclub-icon\u002d\u002dsend"} -->
<p class="horseclub-header__square horseclub-icon--send"></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"horseclub-header__contact","textColor":"contrast","fontSize":"small"} -->
<p class="horseclub-header__contact has-contrast-color has-text-color has-small-font-size"><a href="mailto:hello@yourdomain.com">hello@yourdomain.com</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"horseclub-header__brand","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center","verticalAlignment":"center"}} -->
<div class="wp-block-group horseclub-header__brand"><!-- wp:site-logo {"width":176} /-->

<!-- wp:site-title {"level":0} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"horseclub-header__side horseclub-header__side\u002d\u002dend","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right","verticalAlignment":"center"}} -->
<div class="wp-block-group horseclub-header__side horseclub-header__side--end"><!-- wp:paragraph {"className":"horseclub-header__contact","textColor":"contrast","fontSize":"small"} -->
<p class="horseclub-header__contact has-contrast-color has-text-color has-small-font-size"><a href="tel:+441632960482">01632 960 482</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"placeholder":" ","className":"horseclub-header__square horseclub-icon\u002d\u002dphone"} -->
<p class="horseclub-header__square horseclub-icon--phone"></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:separator {"align":"full","className":"horseclub-header__rule"} -->
<hr class="wp-block-separator alignfull has-alpha-channel-opacity horseclub-header__rule"/>
<!-- /wp:separator -->

<!-- wp:group {"className":"horseclub-header__bar","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center","verticalAlignment":"center"}} -->
<div class="wp-block-group horseclub-header__bar"><!-- wp:navigation {"layout":{"type":"flex","justifyContent":"center","flexWrap":"wrap"}} /-->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"horseclub-scheme-toggle"} -->
<div class="wp-block-button horseclub-scheme-toggle"><a class="wp-block-button__link wp-element-button" href="#"><span class="screen-reader-text">Switch between light and dark mode</span></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
