=== Horseclub ===

Contributors: colorlib
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.1
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: blog, entertainment, full-site-editing, block-patterns, block-styles, template-editing, wide-blocks, accessibility-ready, translation-ready, custom-colors, custom-menu, custom-logo, featured-images, threaded-comments, one-column, two-columns, right-sidebar, rtl-language-support, sticky-post, theme-options

A block theme for riding schools, equestrian clubs and livery yards.

== Description ==

Horseclub is a full site editing theme for riding schools, equestrian clubs and
livery yards: services, training courses, upcoming events, numbered membership
plans, a riders' reviews slider beside a lesson booking form, a contact page
with a map, a newsletter sign-up and a blog. The booking, contact and
newsletter forms need no plugin.

It is built from the Horse Club HTML template: the two-row header with the
email and phone in gradient squares, the full-screen photograph, the feature
band, the numbered price cards and the edge-to-edge gallery are all there, as
blocks you can edit.

Activate it on a new site and it builds the pages for you — Home, About,
Services, Training, Events, Pricing, Blog and Contact — with a menu to match.
On a site that already has pages it leaves them alone.

Eight colour palettes and five type pairings, each checked for contrast before
release rather than by eye. Visitor-facing dark mode that follows the reader's
system setting until they choose for themselves. WooCommerce is styled if you
install it and loads nothing if you do not.

== Installation ==

1. In WordPress, go to Appearance → Themes → Add New Theme → Upload Theme.
2. Choose horseclub.zip and click Install Now, then Activate.
3. Appearance → Editor is where the header, footer, colours and templates live.

== Frequently Asked Questions ==

= Do I need a plugin for the booking form? =

No. The booking, contact and newsletter forms are part of the theme and send
with WordPress's own wp_mail() to the site's admin address. If your host cannot
send mail, install any SMTP plugin — whatever fixes a lost password-reset email
fixes the forms too. To send submissions somewhere else (a booking system, a
mailing list), hook the `horseclub_form_handlers` filter and return true.

= How do I change the colours? =

Appearance → Editor → Styles → Browse styles. Eight palettes are included —
Rosette (the template's own red and orange), Saddle, Hunter, Navy, Burgundy,
Meadow, and the dark Midnight and Stable — and each restyles every section.

= Why is the brand red not used for links? =

The template's red, #f6214b, is 4.0:1 against white and its orange, #f45622,
3.4:1; both fail WCAG AA for text and for a button label. They ship as the
decorative accents (rules, the header squares, the price numerals), and deeper
shades of the same two colours carry links, buttons and anything else you have
to read. The button keeps the template's orange-to-red sweep, in those shades.

= How do I change the map on the contact page? =

The map is a Custom HTML block holding an OpenStreetMap embed. Open
openstreetmap.org, find your yard, choose Share → HTML, and paste the new
address into the block's src.

= Can I turn dark mode off? =

Yes: add add_filter( 'horseclub_enable_dark_mode', '__return_false' ); to a
child theme or a small plugin. The switch in the header disappears with it.

= Can I turn the scroll animations off? =

Yes: add add_filter( 'horseclub_enable_scroll_animations', '__return_false' );
to a child theme or a small plugin. Visitors who have asked their system for
reduced motion never see them either way; the reviews slider and the video
popup keep working.

= What does the update check send? =

Horseclub is distributed from colorlib.com, not the WordPress.org directory, so
it asks updates.colorlib.com for new versions, twice a day at most. It sends
the theme's version, the WordPress and PHP versions, the locale, whether the
site is a multisite, and a one-way hash of the site address — no site name, no
email address, nothing personal. add_filter( 'horseclub_check_for_updates',
'__return_false' ); stops it entirely.

== Theme Check ==

Theme Check reports three REQUIRED findings and no warnings. All three are
deliberate, and each is the price of something the theme does on purpose.

1. **add_shortcode() in inc/booking.php.** The forms have to keep working after
   a pattern is expanded into a page's content, where PHP never runs. A
   shortcode is the only mechanism WordPress offers for that. Moving it to a
   plugin would mean the booking form stops working the moment the plugin is
   disabled, on a page the theme built.
2. **Pixabay photographs.** Most photographs are from Pexels; three files
   (card-5, event-3, gallery-1) are one Pixabay photograph, and Theme Check
   reports the Pixabay credit. Neither licence is GPL-compatible. Replace the
   photographs with your own and the finding goes with them.
3. **Update URI in style.css.** This theme is distributed outside the
   WordPress.org directory and checks colorlib.com for its own updates. A theme
   inside the directory must not carry this header.

== Copyright ==

Horseclub WordPress Theme, (C) 2026 Colorlib.
Horseclub is distributed under the terms of the GNU GPL v2 or later.

Poppins and Playfair Display
License: SIL Open Font License 1.1
Source: https://fontsource.org/

Tabler Icons
License: MIT, https://github.com/tabler/tabler-icons/blob/main/LICENSE
Source: https://tabler.io/icons

Photographs
From Pexels (Pexels License, https://www.pexels.com/license/) and Pixabay
(Pixabay Content License, https://pixabay.com/service/license-summary/).

* hero.webp — Gustavo Fring, Pexels,
  https://www.pexels.com/photo/a-young-woman-walking-the-horse-near-the-stable-doors-4894941/
* riders.webp — Barbara Olsen, Pexels,
  https://www.pexels.com/photo/jockey-women-training-with-horse-7882544/
* card-2.webp — Barbara Olsen, Pexels,
  https://www.pexels.com/photo/woman-riding-horse-in-park-7882529/
* event-2.webp — Barbara Olsen, Pexels,
  https://www.pexels.com/photo/woman-with-horses-7883129/
* about-1.webp — Barbara Olsen, Pexels,
  https://www.pexels.com/photo/horseback-riding-lesson-7882524/
* gallery-2.webp — Barbara Olsen, Pexels,
  https://www.pexels.com/photo/woman-during-horseback-riding-lesson-7882520/
* gallery-5.webp — Barbara Olsen, Pexels,
  https://www.pexels.com/photo/woman-in-blue-jacket-standing-beside-two-brown-horses-7883323/
* gallery-6.webp — Barbara Olsen, Pexels,
  https://www.pexels.com/photo/photo-of-two-horses-in-the-paddock-7882835/
* price-hover.webp — Barbara Olsen, Pexels,
  https://www.pexels.com/photo/woman-cuddling-a-brown-horse-7883326/
* beach.webp — Agung Pandit Wiguna, Pexels,
  https://www.pexels.com/photo/two-horses-on-the-beach-881233/
* card-1.webp, event-1.webp — Jacque B., Pexels,
  https://www.pexels.com/photo/brown-horse-632316/
* card-6.webp, stable.webp — Pixabay, Pexels,
  https://www.pexels.com/photo/brown-horse-208866/
* about-2.webp, card-4.webp, event-4.webp — Pixabay, Pexels,
  https://www.pexels.com/photo/smiling-woman-in-blue-dress-standing-beside-white-horse-220039/
* card-3.webp, gallery-3.webp — Pixabay, Pexels,
  https://www.pexels.com/photo/animal-meadow-leaves-autumn-33096/
* gallery-4.webp — Karolina Grabowska (kaboompics.com), Pexels,
  https://www.pexels.com/photo/brown-horse-on-field-6468/
* card-5.webp, event-3.webp, gallery-1.webp — Alexas_Fotos, Pixabay,
  https://pixabay.com/photos/shire-horse-horse-big-horse-1751800/

== Changelog ==

= 1.0.1 =
* The sample email address in the patterns is now hello@yourdomain.com. The 1.0.0 address used a domain that belongs, or could belong, to someone else.

= 1.0.0 =
* Initial release.
