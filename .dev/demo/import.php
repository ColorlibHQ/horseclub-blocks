<?php
/**
 * The Horseclub demo: everything colorlibhub.com/horseclub-blocks/ shows that
 * the theme itself does not build.
 *
 * The theme's own activation builds the eight starter pages and the menu
 * (inc/front-page-setup.php). This adds what a real club's site would have on
 * top: the site title and tagline, six blog posts with categories, tags,
 * featured photographs and a comment, and it removes WordPress's "Hello world!"
 * and "Sample Page".
 *
 * Run it with the theme active, from WP-CLI, as the user PHP runs as:
 *
 *     sudo -u www-data wp --path=/var/www/colorlibhub.com/public \
 *       --url=https://colorlibhub.com/horseclub-blocks/ \
 *       eval "require '/path/to/demo/import.php';"
 *
 * `wp eval` + `require`, not `wp eval-file`: eval-file runs the file inside a
 * function, where a top-level variable is not a global. Everything below lives
 * in functions anyway, so either works, but require is the tested path.
 *
 * Safe to run twice. Posts are found by slug with get_posts() — never
 * get_page_by_path(), which also matches attachments — and a photograph is
 * uploaded only once, found again by the `_horseclub_demo_file` meta it is
 * given. The photographs sit next to this file and are sideloaded from there;
 * none of them ships in the theme zip.
 *
 * The Playground blueprint (.dev/blueprint.json) runs this same file.
 *
 * @package Horseclub
 */

defined( 'ABSPATH' ) || exit;

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

if ( ! function_exists( 'horseclub_demo_log' ) ) {

	/**
	 * Print a line, through WP-CLI when there is one.
	 *
	 * @param string $line Message.
	 */
	function horseclub_demo_log( $line ) {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			WP_CLI::log( $line );
		} else {
			echo esc_html( $line ) . "\n";
		}
	}

	/**
	 * The posts. Plain lines: `## ` is a heading, `> words — who` a quote.
	 *
	 * @return array[]
	 */
	function horseclub_demo_posts() {
		return array(
			array(
				'slug'     => 'spring-league-round-one',
				'title'    => 'Spring league: round one results',
				'category' => 'Competitions',
				'tags'     => array( 'show jumping', 'results' ),
				'image'    => 'post-chestnut.jpg',
				'alt'      => 'A chestnut horse with a white blaze and a plaited bridle, cattle blurred behind it',
				'days'     => 3,
				'excerpt'  => 'Forty-two riders, three rings and one very muddy warm-up: how the first round of the spring league went, class by class.',
				'body'     => array(
					'Forty-two riders came out for the first round of the spring league on Sunday, which is the biggest entry we have had since the league began. The ground held up better than the forecast suggested, and the warm-up arena took the worst of it.',
					'## Clear rounds all morning',
					'The 60cm clear-round class ran from nine until almost noon. Twenty-three of the twenty-eight starters went clear, and every one of them took home a rosette, which is rather the point of the class.',
					'> The best thing about the league is that the ponies improve as much as the riders. By round four you can see it. — Sarah, head instructor',
					'## The open class',
					'The 1m open was won by Priya Nair on Dandelion, the only double clear of the day, with Marcus Hale second after a rail down in the jump-off. League tables are up in the clubhouse and round two is on 26th April.',
				),
				'comment'  => array( 'Grace Okafor', 'Well done to everyone in the clear-round class. My son has not taken his rosette off since Sunday.' ),
			),
			array(
				'slug'     => 'meet-pearl',
				'title'    => 'Meet Pearl, the newest grey on the yard',
				'category' => 'Club news',
				'tags'     => array( 'school horses', 'lessons' ),
				'image'    => 'post-pearl.jpg',
				'alt'      => 'A smiling woman in a blue dress and a flower crown holding the bridle of a grey horse on a woodland path',
				'days'     => 6,
				'excerpt'  => 'A twelve-year-old Connemara cross with the patience of a saint. Pearl joins the school string this month.',
				'body'     => array(
					'Pearl arrived from a family in the next county last week, and she has already worked out where the carrots are kept. She is a twelve-year-old Connemara cross, fifteen hands, and she spent the last six years teaching two children to ride.',
					'## Who she will teach',
					'Pearl is steady, forward without being quick, and happy in the arena or out on a hack, which makes her ideal for nervous adults and for riders coming back after a long break. She will start in the Taster and Rider lessons from next Monday.',
					'> She stood like a rock while a tractor went past on her first day. That told us everything. — Tom, yard manager',
					'If you would like to meet her before you book, come by on a Saturday morning. She is in the end box, and she will be the one looking over the door.',
				),
			),
			array(
				'slug'     => 'first-winter-at-livery',
				'title'    => 'Getting a horse through its first winter at livery',
				'category' => 'Horse care',
				'tags'     => array( 'livery', 'winter' ),
				'image'    => 'post-arena.jpg',
				'alt'      => 'A bay horse standing by the white rail of a sand paddock, looking towards the camera',
				'days'     => 9,
				'excerpt'  => 'Rugs, hay, turnout and the one thing new owners always forget: a practical checklist for October.',
				'body'     => array(
					'A horse that has lived out all summer notices the change to winter livery more than its owner does. Shorter days, more time in the stable and a new routine all arrive at once, and a little planning makes the difference between a settled horse and a stressed one.',
					'## Rugs: less than you think',
					'Most horses are over-rugged. Feel behind the withers rather than the ears, and only add a layer when the horse is actually cold. We check every livery horse morning and evening and adjust as the weather turns.',
					'## Turnout, even when it is wet',
					'The winter paddocks are smaller, but every horse still goes out for part of every day. A horse that moves stays sounder and calmer than one that stands in all week.',
					'## Hay before hard feed',
					'Forage keeps a horse warm from the inside. Before reaching for a bigger bucket feed, make sure the hay net never runs empty overnight.',
					'The one thing new owners forget: a spare headcollar and lead rope on the stable door, in case a horse has to be moved in a hurry.',
				),
			),
			array(
				'slug'     => 'summer-beach-ride',
				'title'    => 'The summer beach ride is back',
				'category' => 'Club news',
				'tags'     => array( 'hacking', 'events' ),
				'image'    => 'post-beach.jpg',
				'alt'      => 'Two horses on a wet beach at sunset, one led on a rope by a man walking through the shallows',
				'days'     => 16,
				'excerpt'  => 'Twelve places, one early start and a long gallop along the sand. Booking opens to members next week.',
				'body'     => array(
					'After two years away, the club beach ride returns this summer. We will box twelve horses to the coast for a morning ride along the sands, with a gallop for those who want one and a walk through the shallows for those who do not.',
					'Places go to members first, and riders need to be confident in open canter. If you are not sure, book a hacking lesson before the end of June and your instructor will tell you honestly.',
				),
			),
			array(
				'slug'     => 'saddle-fit-signs',
				'title'    => 'Five signs a saddle no longer fits',
				'category' => 'Horse care',
				'tags'     => array( 'tack', 'welfare' ),
				'image'    => 'post-stable.jpg',
				'alt'      => 'A bay horse with a white blaze in a halter, in a dark wooden stable',
				'days'     => 24,
				'excerpt'  => 'A horse changes shape through the year. Here is what to look for before the saddle fitter comes.',
				'body'     => array(
					'Horses change shape with the seasons, with work and with age, and a saddle that fitted in spring can pinch by autumn. These are the signs our yard team looks for.',
					'## What to watch',
					'White hairs under the saddle area, a horse that dips away from the girth, uneven sweat marks after work, a saddle that slides to one side, and a sudden reluctance to go forward in canter. Any one of them is worth a call to the fitter.',
				),
			),
			array(
				'slug'     => 'adults-make-brilliant-beginners',
				'title'    => 'Why adults make brilliant beginners',
				'category' => 'Lessons',
				'tags'     => array( 'beginners', 'lessons' ),
				'image'    => 'post-riders.jpg',
				'alt'      => 'A rider on a chestnut pony with one arm raised, on the lunge with her instructor in a sand arena',
				'days'     => 31,
				'excerpt'  => 'Nervous about starting at forty? Our instructors would rather teach you than almost anyone.',
				'body'     => array(
					'Every week someone rings to ask whether they are too old to learn to ride. The answer is always no, and our instructors will tell you that adult beginners are some of their favourite riders to teach.',
					'Adults listen, ask good questions and practise between lessons. They are more nervous than children, but they also understand why a horse does what it does, and that understanding turns into good riding surprisingly quickly.',
					'Our Taster lesson is thirty minutes on a steady cob with an instructor at your side. Most people book a second before they have left the yard.',
				),
			),
		);
	}

	/**
	 * Turn the plain lines into block markup.
	 *
	 * @param string[] $lines Paragraphs, `## ` headings and `> ` quotes.
	 * @return string
	 */
	function horseclub_demo_blocks( $lines ) {
		$out = array();
		foreach ( $lines as $line ) {
			if ( 0 === strpos( $line, '## ' ) ) {
				$out[] = "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html( substr( $line, 3 ) ) . "</h2>\n<!-- /wp:heading -->";
			} elseif ( 0 === strpos( $line, '> ' ) ) {
				list( $words, $who ) = array_map( 'trim', explode( '—', substr( $line, 2 ) ) );
				$out[]               = "<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>" . esc_html( $words ) . "</p>\n<!-- /wp:paragraph --><cite>" . esc_html( $who ) . "</cite></blockquote>\n<!-- /wp:quote -->";
			} else {
				$out[] = "<!-- wp:paragraph -->\n<p>" . esc_html( $line ) . "</p>\n<!-- /wp:paragraph -->";
			}
		}
		return implode( "\n\n", $out );
	}

	/**
	 * The ID of a post of this type with this slug, in any status, or 0.
	 *
	 * @param string $slug Slug.
	 * @param string $type Post type.
	 * @return int
	 */
	function horseclub_demo_find( $slug, $type ) {
		$ids = get_posts(
			array(
				'name'             => $slug,
				'post_type'        => $type,
				'post_status'      => array( 'publish', 'draft', 'pending', 'private', 'future' ),
				'posts_per_page'   => 1,
				'fields'           => 'ids',
				'suppress_filters' => true,
			)
		);
		return $ids ? (int) $ids[0] : 0;
	}

	/**
	 * An attachment for one of the photographs next to this file, uploaded once.
	 *
	 * @param string $file    File name in this directory.
	 * @param int    $post_id Post to attach a new upload to.
	 * @param string $title   Attachment title.
	 * @param string $alt     Alt text.
	 * @return int Attachment ID, or 0.
	 */
	function horseclub_demo_media( $file, $post_id, $title, $alt ) {
		$found = get_posts(
			array(
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'meta_key'       => '_horseclub_demo_file', // phpcs:ignore WordPress.DB.SlowDBQuery -- a one-off import.
				'meta_value'     => $file, // phpcs:ignore WordPress.DB.SlowDBQuery
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		if ( $found ) {
			update_post_meta( $found[0], '_wp_attachment_image_alt', $alt );
			return (int) $found[0];
		}

		$source = __DIR__ . '/' . $file;
		if ( ! is_readable( $source ) ) {
			horseclub_demo_log( "  missing photograph: $file" );
			return 0;
		}

		// media_handle_sideload() moves the file it is given, so hand it a copy.
		$tmp = wp_tempnam( $file );
		copy( $source, $tmp );
		$id = media_handle_sideload(
			array(
				'name'     => $file,
				'tmp_name' => $tmp,
			),
			$post_id,
			$title
		);
		if ( is_wp_error( $id ) ) {
			horseclub_demo_log( '  upload failed: ' . $file . ' — ' . $id->get_error_message() );
			if ( file_exists( $tmp ) ) {
				wp_delete_file( $tmp );
			}
			return 0;
		}

		update_post_meta( $id, '_wp_attachment_image_alt', $alt );
		update_post_meta( $id, '_horseclub_demo_file', $file );
		return (int) $id;
	}

	/**
	 * A category by name, created when missing.
	 *
	 * @param string $name Category name.
	 * @return int Term ID, or 0.
	 */
	function horseclub_demo_category( $name ) {
		$term = term_exists( $name, 'category' );
		if ( ! $term ) {
			$term = wp_insert_term( $name, 'category' );
		}
		return is_array( $term ) ? (int) $term['term_id'] : 0;
	}

	/**
	 * Run the import.
	 */
	function horseclub_demo_import() {
		if ( ! function_exists( 'horseclub_create_front_page' ) ) {
			horseclub_demo_log( 'Horseclub is not the active theme on ' . home_url( '/' ) . ' — activate it first. Nothing imported.' );
			return;
		}

		// Site identity. The header prints the site title beside the logo disc.
		update_option( 'blogname', 'Horse Club' );
		update_option( 'blogdescription', 'Riding school & livery yard' );

		// The starter pages and menu are the theme's own job. Its function is
		// one-shot and checks every slug, so calling it again only fills in what
		// activation did not get to (for example when it ran before patterns
		// were registered).
		horseclub_create_front_page();
		horseclub_demo_log( 'starter pages: ' . get_option( HORSECLUB_SETUP_FLAG ) );

		// WordPress's own sample content.
		foreach ( array( array( 'hello-world', 'post' ), array( 'sample-page', 'page' ) ) as $sample ) {
			$id = horseclub_demo_find( $sample[0], $sample[1] );
			if ( $id ) {
				wp_delete_post( $id, true );
				horseclub_demo_log( "removed {$sample[1]} {$sample[0]}" );
			}
		}

		// Written by the site's first administrator, not by user 0.
		$admins = get_users(
			array(
				'role'    => 'administrator',
				'number'  => 1,
				'orderby' => 'ID',
				'fields'  => 'ids',
			)
		);
		$author = $admins ? (int) $admins[0] : 1;

		// Activation from WP-CLI runs with no user, so the starter pages are
		// saved with author 0. Give them the same author as the posts — in the
		// table, not through wp_update_post(): that re-saves the content through
		// kses, which strips the contact page's map <iframe> when there is no user.
		global $wpdb;
		foreach ( array_keys( horseclub_starter_pages() ) as $slug ) {
			$page = horseclub_demo_find( $slug, 'page' );
			if ( $page && ! (int) get_post_field( 'post_author', $page ) ) {
				$wpdb->update( $wpdb->posts, array( 'post_author' => $author ), array( 'ID' => $page ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				clean_post_cache( $page );
			}
		}

		foreach ( horseclub_demo_posts() as $post ) {
			$id = horseclub_demo_find( $post['slug'], 'post' );

			if ( ! $id ) {
				$id = wp_insert_post(
					wp_slash(
						array(
							'post_title'    => $post['title'],
							'post_name'     => $post['slug'],
							'post_excerpt'  => $post['excerpt'],
							'post_content'  => horseclub_demo_blocks( $post['body'] ),
							'post_status'   => 'publish',
							'post_author'   => $author,
							'post_date'     => wp_date( 'Y-m-d H:i:s', time() - $post['days'] * DAY_IN_SECONDS ),
							'post_category' => array( horseclub_demo_category( $post['category'] ) ),
							'tags_input'    => $post['tags'],
						)
					),
					true
				);
				if ( is_wp_error( $id ) ) {
					horseclub_demo_log( 'post failed: ' . $post['slug'] . ' — ' . $id->get_error_message() );
					continue;
				}
				horseclub_demo_log( 'created post ' . $post['slug'] );
			} else {
				horseclub_demo_log( 'kept post ' . $post['slug'] );
			}

			if ( ! has_post_thumbnail( $id ) ) {
				$media = horseclub_demo_media( $post['image'], $id, $post['title'], $post['alt'] );
				if ( $media ) {
					set_post_thumbnail( $id, $media );
				}
			}

			if ( ! empty( $post['comment'] ) ) {
				list( $who, $words ) = $post['comment'];
				$has                 = get_comments(
					array(
						'post_id'      => $id,
						'search'       => $words,
						'count'        => true,
					)
				);
				if ( ! $has ) {
					wp_insert_comment(
						wp_slash(
							array(
								'comment_post_ID'  => $id,
								'comment_author'   => $who,
								'comment_content'  => $words,
								'comment_approved' => 1,
								'comment_date'     => wp_date( 'Y-m-d H:i:s', time() - ( $post['days'] - 1 ) * DAY_IN_SECONDS ),
							)
						)
					);
				}
			}
		}

		$count = wp_count_posts( 'post' );
		horseclub_demo_log( sprintf( 'done: %d published posts, %d pages', $count->publish, wp_count_posts( 'page' )->publish ) );
	}
}

horseclub_demo_import();
