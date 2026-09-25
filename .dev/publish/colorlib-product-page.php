<?php
/**
 * Build the Horseclub product page on colorlib.com/wp, as a child of the themes
 * listing (5091).
 *
 * Same shape as Unioncorp's page (what the theme is, what it looks like, what
 * you get, the questions people ask), with two differences: a "Two versions of
 * Horseclub" section near the top, because the Elementor edition has its own
 * demo and repository, and no Documentation button, because there is no
 * documentation page yet.
 *
 * Every factual claim below was checked against this theme's code rather than
 * carried over from Unioncorp's page:
 *  - forms: one shortcode draws booking, contact and newsletter (inc/booking.php);
 *    nonce + honeypot, plain POST that works without JavaScript; mail goes to
 *    the admin address through wp_mail(); `horseclub_form_handlers` hands a
 *    submission elsewhere and skips the email. Newsletter sign-ups are emails
 *    too: there is no mailing-list integration.
 *  - form plugins styled (assets/css/forms.css): Contact Form 7, WPForms,
 *    Gravity Forms, Fluent Forms, Forminator, Ninja Forms. Six.
 *  - update check (inc/updates.php): theme, version, WordPress and PHP versions,
 *    locale, multisite flag, and a 32-character HMAC of the home URL keyed with
 *    the site's own AUTH salt. Cached 12 hours. `horseclub_check_for_updates`.
 *  - 32 patterns: 21 in the inserter (12 sections, 7 pages, header, footer),
 *    11 used by templates only. 14 templates, 3 parts. 8 palettes (Rosette,
 *    Saddle, Hunter, Navy, Burgundy, Meadow, Midnight, Stable) x 5 type
 *    pairings. Poppins and Playfair Display, self-hosted. Tabler Icons.
 *  - WooCommerce: styled when it is active, nothing loaded when it is not
 *    (inc/woocommerce.php checks class_exists( 'WooCommerce' )).
 *  - Motion: reveals off for reduced motion and with
 *    `horseclub_enable_scroll_animations`; slider and video popup stay.
 *    The video plays from youtube-nocookie.com.
 *  - Elementor edition (ColorlibHQ/horseclub, read-only clone): its companion
 *    lives inside the theme (inc/horseclub-companion) and its widgets need the
 *    Elementor plugin. Nothing else is claimed about it.
 *
 * Idempotent: creates the page the first time, rewrites it after that, and
 * leaves it a DRAFT. colorlib.com's convention is draft first, publish
 * separately; a page that is already published keeps its status.
 *
 * Images are found by file name, not by attachment ID, and the script refuses
 * to save while any is missing — a product page with a broken hero is worse
 * than no page. Import .dev/publish/images/*.jpg first.
 *
 *   Copy this file to the server, then run it with WP-CLI from the WordPress
 *   root, as the user PHP runs as:
 *     wp --url=https://colorlib.com/wp/ eval "require '/tmp/colorlib-product-page.php';"
 *
 * Use `wp eval "require …"`, not `wp eval-file`, which runs in a function scope.
 */

defined( 'ABSPATH' ) || exit;

$slug   = 'horseclub';
$parent = 5091;

$download = 'https://updates.colorlib.com/download/theme/horseclub.zip';
$demo     = 'https://colorlibhub.com/horseclub-blocks/';
$el_demo  = 'https://colorlibhub.com/horseclub/';
$el_repo  = 'https://github.com/ColorlibHQ/horseclub';

// ---------------------------------------------------------------------------
// Images, by file name
// ---------------------------------------------------------------------------

/*
 * colorlib.com's uploads have no year/month folder, so `_wp_attached_file` is
 * the bare file name and `LIKE '%/name'` alone never matches. A file over the
 * size threshold is stored as `name-scaled.jpg`, so accept that too.
 */
$find_image = static function ( $file ) {
	global $wpdb;
	$scaled = preg_replace( '/\.(jpe?g|png)$/i', '-scaled.$1', $file );
	return (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta}
			 WHERE meta_key = '_wp_attached_file'
			   AND ( meta_value = %s OR meta_value = %s OR meta_value LIKE %s OR meta_value LIKE %s )
			 ORDER BY post_id DESC LIMIT 1",
			$file,
			$scaled,
			'%/' . $wpdb->esc_like( $file ),
			'%/' . $wpdb->esc_like( $scaled )
		)
	);
};

// `wp media import` never overwrites: an existing name is saved as `-1.jpg`,
// which this lookup would not find. New screenshots need new names.
$wanted = array(
	'card'     => 'horseclub-free-equestrian-wordpress-theme.jpg',
	'home'     => 'horseclub-block-theme-home.jpg',
	'booking'  => 'horseclub-block-theme-booking-form.jpg',
	'training' => 'horseclub-block-theme-training.jpg',
	'events'   => 'horseclub-block-theme-events.jpg',
	'palettes' => 'horseclub-block-theme-colour-palettes.jpg',
	'dark'     => 'horseclub-block-theme-dark-mode.jpg',
	'blog'     => 'horseclub-block-theme-blog.jpg',
);

$img     = array();
$missing = array();
foreach ( $wanted as $key => $file ) {
	$img[ $key ] = $find_image( $file );
	if ( ! $img[ $key ] ) {
		$missing[] = $file;
	}
}

if ( $missing ) {
	echo "ERROR: not in the media library yet, refusing to build a page with gaps:\n  " . implode( "\n  ", $missing ) . "\n";
	return;
}

// vc_btn's `link` attribute is WPBakery's own "url:…|title:…|target:…" encoding.
// It splits on "|" then on the first ":", so a raw URL is cut off at "https:"
// and the button renders href="http://https". Percent-encode anything in a link.
$enc = 'rawurlencode';

$btn_css = 'display:inline-block !important;vertical-align:middle !important;margin-right:12px !important;margin-bottom:10px !important;';
$tint    = '#fdf6f6';
$accent  = '#d90f3a';

// Look up by slug AND parent: get_page_by_path( 'horseclub' ) finds only a
// top-level page (and matches attachments), and this one is a child of 5091.
$found = get_posts(
	array(
		'post_type'   => 'page',
		'name'        => $slug,
		'post_parent' => $parent,
		// An explicit list, not 'any': in WP_Query 'any' leaves drafts out, so an
		// idempotent script would keep re-creating its own draft.
		'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ),
		'numberposts' => 1,
	)
);

$existing = $found ? $found[0] : null;
$page_id  = $existing ? $existing->ID : 0;

// ---------------------------------------------------------------------------
// Buttons
// ---------------------------------------------------------------------------

/**
 * One WPBakery button. `$n` makes its css= class unique on the page.
 */
$button = static function ( $n, $title, $url, $color, $icon, $blank = true ) use ( $btn_css, $enc ) {
	return '[vc_btn title="' . esc_attr( $title ) . '" style="flat" color="' . $color . '"'
		. ' link="url:' . $enc( $url ) . '|title:' . $enc( $title ) . ( $blank ? '|target:_blank' : '' ) . '"'
		. ' css=".vc_custom_hc' . $n . '{' . $btn_css . '}" i_icon_fontawesome="fa fa-' . $icon . '" add_icon="true"]';
};

// The Download button's title is what the email gate keys on, with its href.
$pair = static function ( $n ) use ( $button, $download, $demo ) {
	return $button( $n . 'a', 'Download Horseclub', $download, 'green', 'download' )
		. $button( $n . 'b', 'Live demo', $demo, 'grey', 'eye' );
};

// ---------------------------------------------------------------------------
// Content
// ---------------------------------------------------------------------------

$features = array(
	array( 'envelope', 'Booking, contact and newsletter forms', 'Built in, no plugin. Validated on the server, protected by a nonce and a honeypot, and they work with JavaScript turned off. One filter hands each submission to a booking system or a mailing list instead.' ),
	array( 'th-large', 'Sections a riding school needs', 'Services, training courses with prices, an events list, four numbered membership plans, a riders’ reviews slider, an edge-to-edge gallery and a video introduction. Each is a pattern you insert and edit like any other block.' ),
	array( 'paint-brush', 'Eight palettes, five type pairings', 'Every palette is measured against WCAG AA before the theme is built: text, links and button labels, in light mode and in dark.' ),
	array( 'moon-o', 'Dark mode', 'A switch in the header for the visitor, separate from the palette you chose. It follows the reader’s system setting until they choose, and it lifts your palette rather than replacing it.' ),
	array( 'plug', 'Your form plugin, styled', 'Contact Form 7, WPForms, Gravity Forms, Fluent Forms, Forminator and Ninja Forms take on the theme’s colours and spacing instead of looking like another website.' ),
	array( 'shopping-cart', 'WooCommerce ready', 'Styled if you install WooCommerce to sell lessons, vouchers or tack, and it loads nothing at all if you do not.' ),
	array( 'magic', 'Motion that stays out of the way', 'Sections rise into view as you scroll, reviews slide, the video plays in a popup and the gallery opens in a lightbox. Nothing on the first screen waits for it, and visitors who prefer reduced motion see no reveals.' ),
	array( 'files-o', 'A site on activation', 'Activate it on a new site and it builds Home, About, Services, Training, Events, Pricing, Blog and Contact, with a menu to match. On a site that already has pages it leaves them alone.' ),
	array( 'map-marker', 'A contact page with a map', 'Address, phone, email and opening hours with a contact form and an OpenStreetMap embed you point at your own yard.' ),
);

// Three to a row, each row its own [vc_row]. WPBakery columns are floats: nine
// thirds in one row snag on the tallest box above them.
$feature_rows = '';
$rows         = array_chunk( $features, 3 );
foreach ( $rows as $r => $row ) {
	$last          = ( count( $rows ) - 1 === $r );
	$feature_rows .= '[vc_row css=".vc_custom_hc05' . ( $r + 1 ) . '{padding-bottom:' . ( $last ? '40' : '0' ) . 'px !important;}"]';
	foreach ( $row as $f ) {
		list( $icon, $heading, $body ) = $f;
		$feature_rows .= '[vc_column width="1/3"][vcex_icon_box style="two" heading="' . esc_attr( $heading ) . '" heading_type="h3"'
			. ' icon="fa fa-' . $icon . '" icon_color="' . $accent . '" icon_size="28px" heading_size="20px"'
			. ' content_font_size="15px" css=".vc_custom_hc_f_' . sanitize_key( $icon ) . '{margin-bottom:26px !important;}"]'
			. $body . '[/vcex_icon_box][/vc_column]';
	}
	$feature_rows .= '[/vc_row]';
}

$faqs = array(
	array( 'Do I need a plugin?', 'No. The booking, contact and newsletter forms and dark mode are part of the theme. WooCommerce is styled if you add it, and is not required.' ),
	array( 'Where do booking requests go?', 'To the site’s admin email address, sent with WordPress’s own mail function. If your host cannot send mail, any SMTP plugin fixes the forms along with everything else. To send requests to a booking system instead, hook the <code>horseclub_form_handlers</code> filter and return true; Horseclub then sends nothing of its own. <code>horseclub_form_email_to</code> changes the address.' ),
	array( 'Does the newsletter form connect to Mailchimp?', 'Not by itself. Each sign-up arrives as an email to the admin address. The same <code>horseclub_form_handlers</code> filter is where a mailing-list provider hooks in.' ),
	array( 'Which form plugins does it style?', 'Contact Form 7, WPForms, Gravity Forms, Fluent Forms, Forminator and Ninja Forms are mapped onto the theme’s own colours and spacing.' ),
	array( 'Can I change the colours?', 'Eight palettes and five type pairings ship with the theme, each a one-click choice in the Site Editor under Styles. Every colour is a palette entry you can edit there too.' ),
	array( 'How do I change the map?', 'The map on the contact page is a Custom HTML block holding an OpenStreetMap embed. On openstreetmap.org, find your yard, choose Share → HTML, and paste the new address into the block.' ),
	array( 'Can I turn dark mode off?', 'Yes. Add <code>add_filter( \'horseclub_enable_dark_mode\', \'__return_false\' );</code> to a child theme or a small plugin, and the switch in the header goes with it.' ),
	array( 'Can I turn the animations off?', 'Yes. Add <code>add_filter( \'horseclub_enable_scroll_animations\', \'__return_false\' );</code> and sections stop fading in; the reviews slider and the video popup keep working. Visitors whose system asks for reduced motion never see the reveals either way.' ),
	array( 'Block theme or Elementor edition?', 'For a new site, the block theme: everything is edited in WordPress’s own Site Editor and nothing else needs installing. The Elementor edition is the same design for sites already built with Elementor; it needs the free Elementor plugin.' ),
	array( 'Is it translation ready?', 'Yes. Every string is translatable and <code>languages/horseclub.pot</code> is included.' ),
	array( 'Does it check for updates?', 'Yes, at most twice a day, because it is distributed outside the WordPress.org theme directory. It sends the theme’s version, the WordPress and PHP versions, the locale, whether the site is a multisite, and an identifier derived from the site’s address with the site’s own secret key, so it cannot be turned back into the address. The <code>horseclub_check_for_updates</code> filter switches it off.' ),
);

$toggles = '';
foreach ( $faqs as $faq ) {
	// vcex_toggle takes `heading` (with `title` every toggle renders the
	// shortcode's placeholder). Styled by its own attributes, not a css= class.
	$toggles .= '[vcex_toggle heading="' . esc_attr( $faq[0] ) . '" heading_type="h3" heading_font_size="17px" style="boxed" padding_y="16px" padding_x="20px" bottom_margin="12px"]'
		. $faq[1] . '[/vcex_toggle]';
}

$specs = array(
	'Requires'     => 'WordPress 6.6 or newer',
	'PHP'          => '7.4 or newer',
	'Tested up to' => 'WordPress 7.1',
	'Licence'      => 'GNU General Public License v2 or later',
	'Patterns'     => '21 to insert, plus 11 the templates use',
	'Templates'    => '14, plus 3 template parts',
	'Styles'       => '8 colour palettes × 5 type pairings',
	'Fonts'        => 'Poppins and Playfair Display, self-hosted',
	'Icons'        => 'Tabler Icons (MIT), drawn in the palette’s colours',
	'Form plugins' => 'Contact Form 7, WPForms, Gravity Forms, Fluent Forms, Forminator, Ninja Forms',
	'Build step'   => 'None — no npm, no SCSS',
);

$spec_rows = '';
foreach ( $specs as $label => $value ) {
	$spec_rows .= '<tr><th style="text-align:left;padding:10px 18px 10px 0;border-bottom:1px solid #eee3e3;font-weight:600;white-space:nowrap;vertical-align:top;">'
		. esc_html( $label ) . '</th><td style="padding:10px 0;border-bottom:1px solid #eee3e3;">' . $value . '</td></tr>';
}

$section = static function ( $n, $bg, $heading, $text, $image ) use ( $tint ) {
	$background = $bg ? "background-color:{$tint} !important;" : '';
	return "[vc_row css=\".vc_custom_hc{$n}0{padding-top:56px !important;padding-bottom:20px !important;{$background}}\"][vc_column width=\"1/1\"]"
		. '[vcex_heading text="' . esc_attr( $heading ) . '" tag="h2" font_size="34px" text_align="center" bottom_margin="14px" font_weight="700"]'
		. "[vc_column_text css=\".vc_custom_hc{$n}1{text-align:center !important;max-width:790px !important;margin-left:auto !important;margin-right:auto !important;}\"]{$text}[/vc_column_text]"
		. "[/vc_column][/vc_row][vc_row css=\".vc_custom_hc{$n}2{padding-bottom:56px !important;{$background}}\"][vc_column width=\"1/1\"]"
		. "[vcex_image image_id=\"{$image}\" align=\"center\" border_radius=\"12px\" bottom_margin=\"0px\"][/vc_column][/vc_row]";
};

// Two versions: one row, two halves.
$versions = "[vc_row css=\".vc_custom_hc0v0{padding-top:56px !important;padding-bottom:8px !important;}\"][vc_column width=\"1/1\"]"
	. '[vcex_heading text="Two versions of Horseclub" tag="h2" font_size="34px" text_align="center" bottom_margin="30px" font_weight="700"][/vc_column][/vc_row]'
	. "[vc_row css=\".vc_custom_hc0v1{padding-bottom:48px !important;}\"]"
	. '[vc_column width="1/2"][vcex_heading text="Block theme" tag="h3" font_size="24px" bottom_margin="12px" font_weight="700"]'
	. '[vc_column_text css=".vc_custom_hc0v2{margin-bottom:18px !important;}"]<p>This download. Built for the Site Editor, with no plugins: the sections are patterns, the colours are eight palettes you switch in Styles, and the visitor gets a dark-mode switch. The booking, contact and newsletter forms are part of the theme. <strong>Recommended for new sites.</strong></p>[/vc_column_text]'
	. '[vc_column_text]' . $pair( '0v3' ) . '[/vc_column_text][/vc_column]'
	. '[vc_column width="1/2"][vcex_heading text="Elementor edition" tag="h3" font_size="24px" bottom_margin="12px" font_weight="700"]'
	. '[vc_column_text css=".vc_custom_hc0v4{margin-bottom:18px !important;}"]<p>The same design built for Elementor, for sites that already use it. It needs the free Elementor plugin; its companion, with the Horseclub widgets, is built into the theme. The source is free on GitHub.</p>[/vc_column_text]'
	. '[vc_column_text]' . $button( '0v5a', 'Elementor demo', $el_demo, 'grey', 'eye' ) . $button( '0v5b', 'Source on GitHub', $el_repo, 'grey', 'github' ) . '[/vc_column_text][/vc_column]'
	. '[/vc_row]';

$content = "[vc_row css=\".vc_custom_hc001{padding-top:64px !important;padding-bottom:40px !important;background-color:{$tint} !important;}\"][vc_column width=\"1/1\"]"
	. '[vcex_heading text="A riding school theme with lesson booking built in" tag="h2" font_size="46px" text_align="center" bottom_margin="20px" font_weight="700"]'
	. '[vc_column_text css=".vc_custom_hc002{text-align:center !important;font-size:18px !important;max-width:820px !important;margin-left:auto !important;margin-right:auto !important;}"]'
	. 'Horseclub is a free block theme for riding schools, equestrian clubs and livery yards. It ships the form a riding school’s site exists for — a lesson booking request that needs no plugin — with training courses, events, numbered membership plans, a reviews slider and a gallery, eight colour palettes and full site editing throughout.'
	. '[/vc_column_text][vc_column_text css=".vc_custom_hc003{text-align:center !important;margin-top:26px !important;}"]' . $pair( '004' ) . '[/vc_column_text]'
	. "[/vc_column][/vc_row][vc_row css=\".vc_custom_hc006{padding-top:0px !important;padding-bottom:64px !important;background-color:{$tint} !important;}\"][vc_column width=\"1/1\"]"
	. "[vcex_image image_id=\"{$img['home']}\" align=\"center\" border_radius=\"14px\" bottom_margin=\"0px\"][/vc_column][/vc_row]\n\n"

	. $versions . "\n\n"

	. $section( '01', true, 'Lesson booking without a plugin',
		'Riders’ reviews slide on the left, the booking form waits on the right: name, email, phone, a preferred date and a note about their riding. It validates on the server, carries a nonce and a honeypot, and works with JavaScript turned off. Requests go to the site’s admin address — or, with one filter, to your booking system.',
		$img['booking'] ) . "\n\n"

	. $section( '02', false, 'Courses and prices, as blocks',
		'Six training programmes with a photograph, a price and a line on what each course covers; six services in the same cards; four numbered membership plans. Each is a pattern: insert it, change the words and the photographs, and it stays exactly as editable as a paragraph.',
		$img['training'] ) . "\n\n"

	. $section( '03', true, 'Show days, clinics and open days',
		'The events page sets each date beside its photograph, alternating sides down the page, with its own button. On a phone every event reads photograph first.',
		$img['events'] ) . "\n\n"

	. $section( '04', false, 'Eight palettes, checked before release',
		'Rosette, the template’s own red and orange, then Saddle, Hunter, Navy, Burgundy and Meadow, and two dark palettes, Midnight and Stable. Every one is measured against WCAG AA before the theme is built, in light mode and in dark, and a palette that fails is not written.',
		$img['palettes'] ) . "\n\n"

	. $section( '05', true, 'Dark mode the visitor controls',
		'The switch in the header is the visitor’s, separate from the palette you chose. It follows the reader’s system setting until they decide for themselves, and it lifts your palette rather than replacing it.',
		$img['dark'] ) . "\n\n"

	. "[vc_row css=\".vc_custom_hc060{padding-top:56px !important;padding-bottom:16px !important;}\"][vc_column width=\"1/1\"]"
	. '[vcex_heading text="What you get" tag="h2" font_size="34px" text_align="center" bottom_margin="34px" font_weight="700"][/vc_column][/vc_row]'
	. $feature_rows . "\n\n"

	. $section( '07', true, 'It has a blog, too',
		'Competition results, horse-care advice, news from the yard. Archives, single posts, categories, tags, author pages, search and a 404 are all designed rather than inherited, with a sidebar of search, categories and recent posts.',
		$img['blog'] ) . "\n\n"

	. "[vc_row css=\".vc_custom_hc080{padding-top:56px !important;padding-bottom:18px !important;}\"][vc_column width=\"1/1\"]"
	. '[vcex_heading text="Questions" tag="h2" font_size="34px" text_align="center" bottom_margin="28px" font_weight="700"][/vc_column][/vc_row]'
	. "[vc_row css=\".vc_custom_hc081{padding-bottom:48px !important;}\"][vc_column width=\"1/1\"]{$toggles}[/vc_column][/vc_row]\n\n"

	. "[vc_row css=\".vc_custom_hc090{padding-top:48px !important;padding-bottom:56px !important;background-color:{$tint} !important;}\"][vc_column width=\"1/1\"]"
	. '[vcex_heading text="The details" tag="h2" font_size="34px" text_align="center" bottom_margin="26px" font_weight="700"]'
	. '[vc_column_text css=".vc_custom_hc091{max-width:680px !important;margin-left:auto !important;margin-right:auto !important;}"]<table style="width:100%;border-collapse:collapse;">' . $spec_rows . '</table>[/vc_column_text]'
	. '[vc_column_text css=".vc_custom_hc092{text-align:center !important;margin-top:34px !important;}"]' . $pair( '093' ) . '[/vc_column_text]'
	. '[/vc_column][/vc_row]';

// ---------------------------------------------------------------------------
// Save
// ---------------------------------------------------------------------------

// kses strips the shortcode attributes this page is made of.
$kses = has_filter( 'content_save_pre', 'wp_filter_post_kses' );
if ( $kses ) {
	kses_remove_filters();
}

$args = array(
	'post_title'   => 'Horseclub',
	'post_name'    => $slug,
	'post_content' => $content,
	// Draft on creation, but never demote a page that is already published.
	'post_status'  => $existing ? $existing->post_status : 'draft',
	'post_type'    => 'page',
	'post_parent'  => $parent,
);

if ( $page_id ) {
	$args['ID'] = $page_id;
	$result     = wp_update_post( wp_slash( $args ), true );
} else {
	$result  = wp_insert_post( wp_slash( $args ), true );
	$page_id = is_wp_error( $result ) ? 0 : $result;
}

if ( $kses ) {
	kses_init_filters();
}

if ( is_wp_error( $result ) ) {
	echo 'ERROR: ' . $result->get_error_message() . "\n";
	return;
}

set_post_thumbnail( $page_id, $img['card'] );

// The full-width template and WPBakery's own flag, as the other theme pages have.
update_post_meta( $page_id, '_wp_page_template', 'templates/no-sidebar.php' );
update_post_meta( $page_id, '_wpb_vc_js_status', 'true' );
update_post_meta( $page_id, '_yoast_wpseo_title', 'Horseclub – Free Equestrian WordPress Theme - %%sitename%%' );
update_post_meta( $page_id, '_yoast_wpseo_metadesc', 'A free WordPress block theme for riding schools, equestrian clubs and livery yards, with lesson booking built in, eight colour palettes and dark mode.' );

// WPBakery keeps every css="…" rule in _wpb_shortcodes_custom_css and only
// regenerates it when the page is saved through the builder UI.
if ( function_exists( 'visual_composer' ) && method_exists( visual_composer(), 'buildShortcodesCss' ) ) {
	visual_composer()->buildShortcodesCss( $page_id, 'custom' );
	visual_composer()->buildShortcodesCss( $page_id, 'default' );
	echo "custom css rebuilt\n";
} else {
	echo "WARNING: could not rebuild the WPBakery custom css\n";
}

$saved = get_post_field( 'post_content', $page_id );

echo 'page: ' . $page_id . ' (' . get_post_status( $page_id ) . '), parent ' . wp_get_post_parent_id( $page_id ) . "\n";
echo 'images: ' . wp_json_encode( $img ) . "\n";
echo 'length: ' . strlen( $saved ) . "\n";
echo 'icon boxes: ' . substr_count( $saved, '[vcex_icon_box' ) . " (must be 9)\n";
echo 'toggles: ' . substr_count( $saved, '[vcex_toggle' ) . ' (with heading=: ' . substr_count( $saved, '[vcex_toggle heading=' ) . ', boxed: ' . substr_count( $saved, 'style="boxed"' ) . ")\n";
echo 'template: ' . get_post_meta( $page_id, '_wp_page_template', true ) . ', vc_js: ' . get_post_meta( $page_id, '_wpb_vc_js_status', true ) . "\n";
echo 'section images: ' . substr_count( $saved, '[vcex_image' ) . " (must be 7)\n";
echo 'download buttons: ' . substr_count( $saved, 'title="Download Horseclub"' ) . " (must be 3: top, Two versions, details)\n";
echo 'elementor buttons: ' . substr_count( $saved, 'title="Elementor demo"' ) . ' + ' . substr_count( $saved, 'title="Source on GitHub"' ) . " (must be 1 + 1)\n";
echo 'documentation buttons: ' . substr_count( $saved, 'Documentation' ) . " (must be 0)\n";
echo 'two-column rows: ' . substr_count( $saved, 'width="1/2"' ) . " (must be 2)\n";
echo 'unbalanced rows: ' . ( substr_count( $saved, '[vc_row' ) - substr_count( $saved, '[/vc_row]' ) ) . "\n";
