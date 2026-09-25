<?php
/**
 * The theme's own forms: lesson booking, contact and newsletter.
 *
 * A riding school's site exists to get someone onto a horse, so Horseclub ships
 * the forms rather than requiring a plugin for the one thing a visitor came to
 * do. One shortcode draws all three:
 *
 *     [horseclub_form type="booking" layout="compact" button="Request a booking"]
 *     [horseclub_form type="contact" layout="split"]
 *     [horseclub_form type="newsletter"]
 *
 * It is a **shortcode**, not inline PHP in a pattern. That is not a style
 * preference: inc/front-page-setup.php expands patterns into real post content
 * so the copy stays editable, and PHP inside stored post content never runs.
 * A pattern that rendered the form inline would freeze whatever it produced at
 * activation into the page forever.
 *
 * A theme must not create database tables or register a post type, so Horseclub
 * stores nothing. It validates, then hands the submission to whoever wants it:
 *
 *   - `horseclub_form_handlers` — return true from any handler to say the
 *     submission has been dealt with, and the built-in email is skipped. This
 *     is where a booking system, a mailing list or a webhook hooks in.
 *   - `horseclub_form_email_to` / `_subject` / `_body` — adjust the email the
 *     theme sends when nothing else claims the submission.
 *   - `horseclub_form_fields` — add, remove or relabel fields, per form type.
 *
 * The forms work with JavaScript off: each is a plain POST to the same URL,
 * answered with a redirect carrying the result.
 *
 * @package Horseclub
 */

defined( 'ABSPATH' ) || exit;

const HORSECLUB_FORM_ACTION = 'horseclub_form';

/**
 * The form types and their fields.
 *
 * @param string $type booking, contact or newsletter.
 * @return array<string, array<string, mixed>>
 */
function horseclub_form_fields( $type ) {
	$name  = array(
		'label'        => __( 'Your name', 'horseclub' ),
		'type'         => 'text',
		'autocomplete' => 'name',
		'required'     => true,
	);
	$email = array(
		'label'        => __( 'Email address', 'horseclub' ),
		'type'         => 'email',
		'autocomplete' => 'email',
		'required'     => true,
	);

	$sets = array(
		'booking'    => array(
			'name'    => $name,
			'email'   => $email,
			'phone'   => array(
				'label'        => __( 'Phone number', 'horseclub' ),
				'type'         => 'tel',
				'autocomplete' => 'tel',
				'required'     => false,
			),
			'date'    => array(
				'label'    => __( 'Preferred date', 'horseclub' ),
				'type'     => 'date',
				'required' => false,
				'min'      => gmdate( 'Y-m-d' ),
			),
			'message' => array(
				'label'    => __( 'Riding experience, and anything we should know', 'horseclub' ),
				'type'     => 'textarea',
				'required' => false,
			),
		),
		'contact'    => array(
			'name'    => $name,
			'email'   => $email,
			'subject' => array(
				'label'    => __( 'Subject', 'horseclub' ),
				'type'     => 'text',
				'required' => false,
			),
			'message' => array(
				'label'    => __( 'Message', 'horseclub' ),
				'type'     => 'textarea',
				'required' => true,
			),
		),
		'newsletter' => array(
			'email' => $email,
		),
	);

	$fields = isset( $sets[ $type ] ) ? $sets[ $type ] : array();

	/**
	 * Filters the fields of one of the theme's forms.
	 *
	 * @param array  $fields Field definitions keyed by name.
	 * @param string $type   booking, contact or newsletter.
	 */
	return apply_filters( 'horseclub_form_fields', $fields, $type );
}

/**
 * The known form types.
 *
 * @return string[]
 */
function horseclub_form_types() {
	return array( 'booking', 'contact', 'newsletter' );
}

/**
 * Render one field, label included.
 *
 * Every field has a real <label for>. The compact and inline layouts hide it
 * visually and show the same words as the placeholder, as the design does —
 * the label is still what a screen reader announces.
 *
 * @param string $form  Form type.
 * @param string $name  Field name.
 * @param array  $field Field definition.
 * @param bool   $quiet Whether the label is visually hidden.
 * @return string
 */
function horseclub_form_field( $form, $name, $field, $quiet ) {
	$id       = 'horseclub-' . $form . '-' . $name;
	$required = ! empty( $field['required'] );

	$attributes = array(
		'id'    => $id,
		'name'  => $name,
		'class' => 'horseclub-field__control',
	);

	if ( $required ) {
		$attributes['required'] = 'required';
	}
	if ( ! empty( $field['autocomplete'] ) ) {
		$attributes['autocomplete'] = $field['autocomplete'];
	}
	if ( isset( $field['min'] ) ) {
		$attributes['min'] = $field['min'];
	}
	if ( $quiet && 'date' !== $field['type'] ) {
		$attributes['placeholder'] = $field['label'] . ( $required ? ' *' : '' );
	}

	$label_class = 'horseclub-field__label' . ( $quiet && 'date' !== $field['type'] ? ' screen-reader-text' : '' );

	$out  = '<p class="horseclub-field horseclub-field--' . esc_attr( $name ) . '">';
	$out .= '<label class="' . esc_attr( $label_class ) . '" for="' . esc_attr( $id ) . '">' . esc_html( $field['label'] );
	if ( $required ) {
		$out .= ' <span class="horseclub-field__required" aria-hidden="true">*</span>';
	}
	$out .= '</label>';

	if ( 'textarea' === $field['type'] ) {
		$attributes['rows'] = 4;
		$out               .= '<textarea' . horseclub_attributes( $attributes ) . '></textarea>';
	} else {
		$attributes['type'] = $field['type'];
		$out               .= '<input' . horseclub_attributes( $attributes ) . '>';
	}

	return $out . '</p>';
}

/**
 * Build an attribute string from a map, escaping every value.
 *
 * @param array $attributes Attribute map.
 * @return string
 */
function horseclub_attributes( $attributes ) {
	$out = '';
	foreach ( $attributes as $key => $value ) {
		if ( '' === $value ) {
			continue;
		}
		$out .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
	}
	return $out;
}

/**
 * The form shortcode.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function horseclub_form( $atts = array() ) {
	$atts = shortcode_atts(
		array(
			'type'   => 'booking',
			'layout' => '',
			'button' => '',
		),
		$atts,
		'horseclub_form'
	);

	$type = in_array( $atts['type'], horseclub_form_types(), true ) ? $atts['type'] : 'booking';

	$defaults = array(
		'booking'    => array( 'compact', __( 'Request a booking', 'horseclub' ) ),
		'contact'    => array( 'split', __( 'Send message', 'horseclub' ) ),
		'newsletter' => array( 'inline', __( 'Subscribe', 'horseclub' ) ),
	);

	$layout = in_array( $atts['layout'], array( 'compact', 'split', 'inline', 'stacked' ), true ) ? $atts['layout'] : $defaults[ $type ][0];
	$label  = '' !== $atts['button'] ? $atts['button'] : $defaults[ $type ][1];
	$quiet  = 'stacked' !== $layout;
	$anchor = 'horseclub-form-' . $type;

	$out  = '<form class="horseclub-form horseclub-form--' . esc_attr( $layout ) . ' horseclub-form--' . esc_attr( $type ) . '" method="post" action="' . esc_url( horseclub_current_url() ) . '#' . esc_attr( $anchor ) . '">';
	$out .= '<span id="' . esc_attr( $anchor ) . '" class="horseclub-form__anchor"></span>';
	$out .= horseclub_form_notice( $type );
	$out .= wp_nonce_field( HORSECLUB_FORM_ACTION, 'horseclub_form_nonce', true, false );
	$out .= '<input type="hidden" name="action" value="' . esc_attr( HORSECLUB_FORM_ACTION ) . '">';
	$out .= '<input type="hidden" name="horseclub_form_type" value="' . esc_attr( $type ) . '">';

	// The page to come back to, carried explicitly.
	//
	// wp_get_referer() cannot do this job: it returns false whenever the
	// referer matches the current request URI, which is always the case for a
	// form that posts to its own page, and the visitor would land on the front
	// page with no form in sight. Validated with wp_validate_redirect() on the
	// way back out, so a crafted value cannot send anyone off-site.
	$out .= '<input type="hidden" name="horseclub_redirect" value="' . esc_url( horseclub_current_url() ) . '">';

	// A field no visitor sees and no visitor fills in. Bots fill everything.
	$out .= '<p class="horseclub-form__trap" aria-hidden="true">';
	$out .= '<label for="horseclub-' . esc_attr( $type ) . '-website">' . esc_html__( 'Leave this field empty', 'horseclub' ) . '</label>';
	$out .= '<input id="horseclub-' . esc_attr( $type ) . '-website" type="text" name="horseclub_website" tabindex="-1" autocomplete="off">';
	$out .= '</p>';

	$out .= '<div class="horseclub-form__grid">';
	foreach ( horseclub_form_fields( $type ) as $name => $field ) {
		$out .= horseclub_form_field( $type, $name, $field, $quiet );
	}

	if ( 'inline' === $layout ) {
		// A round button with an arrow, as the template's newsletter has it: the
		// words are for screen readers.
		$out .= '<button type="submit" class="horseclub-form__round wp-element-button"><span class="screen-reader-text">' . esc_html( $label ) . '</span></button>';
		$out .= '</div>';
	} else {
		$out .= '</div>';
		$out .= '<p class="horseclub-form__actions">';
		$out .= '<button type="submit" class="wp-block-button__link wp-element-button">' . esc_html( $label ) . '</button>';
		$out .= '</p>';
	}

	$out .= '</form>';

	return $out;
}
add_shortcode( 'horseclub_form', 'horseclub_form' );

/**
 * The current URL, without any previous result parameters.
 *
 * @return string
 */
function horseclub_current_url() {
	$url = is_singular() ? get_permalink() : '';
	if ( ! $url ) {
		$url = home_url( add_query_arg( array() ) );
	}
	return remove_query_arg( array( 'horseclub-form', 'horseclub-form-type' ), $url );
}

/**
 * The message shown after a submission, on the form that was submitted.
 *
 * @param string $type Form type.
 * @return string
 */
function horseclub_form_notice( $type ) {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- display only.
	$result = isset( $_GET['horseclub-form'] ) ? sanitize_key( wp_unslash( $_GET['horseclub-form'] ) ) : '';
	$which  = isset( $_GET['horseclub-form-type'] ) ? sanitize_key( wp_unslash( $_GET['horseclub-form-type'] ) ) : '';
	// phpcs:enable

	if ( $which !== $type ) {
		return '';
	}

	$sent = array(
		'booking'    => __( 'Thank you — your booking request is with us. We will confirm a time by email or phone.', 'horseclub' ),
		'contact'    => __( 'Thank you — your message is with us. We will reply by email shortly.', 'horseclub' ),
		'newsletter' => __( 'Thank you — you are on the list for the next newsletter.', 'horseclub' ),
	);

	$messages = array(
		'sent'    => array( 'ok', $sent[ $type ] ),
		'invalid' => array( 'error', __( 'Please check the form: every field marked * needs an answer.', 'horseclub' ) ),
		'email'   => array( 'error', __( 'That email address does not look right.', 'horseclub' ) ),
		'failed'  => array( 'error', __( 'Sorry, that could not be sent. Please call or email the yard instead.', 'horseclub' ) ),
		'expired' => array( 'error', __( 'That form had been open a while and expired. Please send it again.', 'horseclub' ) ),
	);

	if ( ! isset( $messages[ $result ] ) ) {
		return '';
	}

	list( $kind, $text ) = $messages[ $result ];

	return '<p class="horseclub-form__notice is-' . esc_attr( $kind ) . '" role="status">' . esc_html( $text ) . '</p>';
}

/**
 * Handle a submitted form.
 *
 * Runs on `template_redirect` so it can redirect before anything is sent —
 * the POST/redirect/GET that stops a refresh from sending it twice.
 */
function horseclub_handle_form() {
	if ( 'POST' !== ( isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '' ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- checked immediately below.
	if ( ! isset( $_POST['action'] ) || HORSECLUB_FORM_ACTION !== $_POST['action'] ) {
		return;
	}

	// phpcs:disable WordPress.Security.NonceVerification.Missing -- nonce checked below; these only select the form and where to go.
	$type     = isset( $_POST['horseclub_form_type'] ) ? sanitize_key( wp_unslash( $_POST['horseclub_form_type'] ) ) : '';
	$posted   = isset( $_POST['horseclub_redirect'] ) ? esc_url_raw( wp_unslash( $_POST['horseclub_redirect'] ) ) : '';
	// phpcs:enable
	$type     = in_array( $type, horseclub_form_types(), true ) ? $type : 'booking';
	$redirect = wp_validate_redirect( $posted, home_url( '/' ) );
	$redirect = remove_query_arg( array( 'horseclub-form', 'horseclub-form-type' ), $redirect );

	$nonce = isset( $_POST['horseclub_form_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['horseclub_form_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, HORSECLUB_FORM_ACTION ) ) {
		horseclub_form_redirect( $redirect, $type, 'expired' );
	}

	// Silently accept and discard anything that filled the honeypot: telling a
	// bot it failed only teaches it to try again differently.
	if ( ! empty( $_POST['horseclub_website'] ) ) {
		horseclub_form_redirect( $redirect, $type, 'sent' );
	}

	$submission = array();
	foreach ( horseclub_form_fields( $type ) as $name => $field ) {
		$raw = isset( $_POST[ $name ] ) ? wp_unslash( $_POST[ $name ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised by type below.
		$raw = is_string( $raw ) ? $raw : '';

		if ( 'textarea' === $field['type'] ) {
			$value = sanitize_textarea_field( $raw );
		} elseif ( 'email' === $field['type'] ) {
			$value = sanitize_email( $raw );
		} else {
			$value = sanitize_text_field( $raw );
		}

		// Something typed that is not an address gets the email message, not
		// "every field marked * needs an answer": sanitize_email() empties it.
		if ( 'email' === $field['type'] && '' !== trim( $raw ) && ! is_email( $value ) ) {
			horseclub_form_redirect( $redirect, $type, 'email' );
		}

		if ( ! empty( $field['required'] ) && '' === $value ) {
			horseclub_form_redirect( $redirect, $type, 'invalid' );
		}

		$submission[ $name ] = $value;
	}

	if ( ! empty( $submission['email'] ) && ! is_email( $submission['email'] ) ) {
		horseclub_form_redirect( $redirect, $type, 'email' );
	}

	/**
	 * Filters whether the submission has already been handled.
	 *
	 * Return true from any handler and Horseclub will not send its own email —
	 * which is how a booking system, a mailing list or a webhook takes over.
	 *
	 * @param bool   $handled    Whether something has dealt with it.
	 * @param array  $submission The sanitised submission.
	 * @param string $type       booking, contact or newsletter.
	 */
	$handled = apply_filters( 'horseclub_form_handlers', false, $submission, $type );

	if ( ! $handled ) {
		$handled = horseclub_form_email( $submission, $type );
	}

	horseclub_form_redirect( $redirect, $type, $handled ? 'sent' : 'failed' );
}
add_action( 'template_redirect', 'horseclub_handle_form' );

/**
 * Redirect back to the form with a result, and stop.
 *
 * @param string $url    Where to go.
 * @param string $type   Form type.
 * @param string $result Result key.
 */
function horseclub_form_redirect( $url, $type, $result ) {
	$url = add_query_arg(
		array(
			'horseclub-form'      => $result,
			'horseclub-form-type' => $type,
		),
		$url
	);
	wp_safe_redirect( $url . '#horseclub-form-' . $type, 303 );
	exit;
}

/**
 * Email the submission to the site's admin address.
 *
 * From: is the site's own address, never the visitor's. Putting the visitor
 * there fails SPF and DMARC — the mail is sent by this server, not by their
 * provider — and it is the classic route to header injection. Reply-To carries
 * them instead, and wp_mail() rejects a header containing a newline.
 *
 * @param array  $submission Sanitised submission.
 * @param string $type       Form type.
 * @return bool
 */
function horseclub_form_email( $submission, $type ) {
	$to = apply_filters( 'horseclub_form_email_to', get_option( 'admin_email' ), $type );

	if ( ! $to || ! is_email( $to ) ) {
		return false;
	}

	$site = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$what = array(
		/* translators: %s: site name. */
		'booking'    => __( '[%s] Lesson booking request', 'horseclub' ),
		/* translators: %s: site name. */
		'contact'    => __( '[%s] Website message', 'horseclub' ),
		/* translators: %s: site name. */
		'newsletter' => __( '[%s] Newsletter sign-up', 'horseclub' ),
	);
	$subject = sprintf( $what[ $type ], $site );
	$subject = apply_filters( 'horseclub_form_email_subject', $subject, $submission, $type );

	$lines  = array();
	$fields = horseclub_form_fields( $type );
	foreach ( $submission as $name => $value ) {
		if ( '' === $value ) {
			continue;
		}
		$label   = isset( $fields[ $name ]['label'] ) ? $fields[ $name ]['label'] : $name;
		$lines[] = $label . ': ' . $value;
	}

	$body = implode( "\n", $lines );
	$body = apply_filters( 'horseclub_form_email_body', $body, $submission, $type );

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( ! empty( $submission['email'] ) && is_email( $submission['email'] ) ) {
		$headers[] = 'Reply-To: ' . $submission['email'];
	}

	return (bool) wp_mail( $to, $subject, $body, $headers );
}
