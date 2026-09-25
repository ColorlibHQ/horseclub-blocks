/**
 * Horseclub: scroll reveals, the reviews slider, the video popup and the
 * header's shadow once the page has scrolled.
 *
 * All four are enhancements. Without this file every section is visible, the
 * review slides scroll sideways on their own, and the play button is a link to
 * the film, so nothing on the page depends on it running.
 *
 * Reveals are skipped for a visitor who asks the system for reduced motion,
 * and a site owner can switch them off with the
 * `horseclub_enable_scroll_animations` filter, which sets
 * `window.horseclubMotion.enabled` to false.
 */
( function () {
	'use strict';

	var settings = window.horseclubMotion || {};
	var reduced = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var motion = settings.enabled !== false && ! reduced && 'IntersectionObserver' in window;

	/*
	 * Only what is below the first screen is ever hidden. Anything a visitor can
	 * already see stays where it is: hiding the opening screen is what makes
	 * PageSpeed report no first contentful paint, and it flashes on load besides.
	 */
	function belowTheFold( el ) {
		return el.getBoundingClientRect().top > window.innerHeight * 0.92;
	}

	function reveals() {
		if ( ! motion ) {
			return;
		}

		var candidates = Array.prototype.slice.call( document.querySelectorAll( [
			'main .horseclub-section-head',
			'main .wp-block-columns > .wp-block-column',
			'main .wp-block-post-template > .wp-block-post'
		].join( ',' ) ) );

		// Animate the innermost element, so a row of cards arrives one card at a
		// time rather than as one block. Nothing inside the slider: its slides
		// sit side by side off screen and would never be seen to arrive.
		var targets = candidates.filter( function ( el ) {
			return ! el.closest( '.horseclub-slider' ) && ! candidates.some( function ( other ) {
				return other !== el && el.contains( other );
			} );
		} ).filter( belowTheFold );

		if ( ! targets.length ) {
			return;
		}

		var rows = new Map();
		targets.forEach( function ( el ) {
			var row = rows.get( el.parentElement ) || [];
			row.push( el );
			rows.set( el.parentElement, row );
		} );
		rows.forEach( function ( row ) {
			row.forEach( function ( el, i ) {
				el.style.setProperty( '--horseclub-reveal-delay', Math.min( i, 5 ) * 110 + 'ms' );
				el.classList.add( 'horseclub-reveal' );
			} );
		} );

		document.documentElement.classList.add( 'horseclub-motion' );

		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-revealed' );
					observer.unobserve( entry.target );
				}
			} );
		}, { rootMargin: '0px 0px -8% 0px' } );

		targets.forEach( function ( el ) {
			observer.observe( el );
		} );
	}

	/*
	 * The reviews slider. The slides are a horizontal scroll-snap strip already
	 * (style.css), so a finger or a trackpad moves them with or without this.
	 * The script adds the template's square dots, one per slide, as real
	 * buttons, and keeps the current one marked as the strip scrolls.
	 */
	function sliders() {
		Array.prototype.forEach.call( document.querySelectorAll( '.horseclub-slider' ), function ( slider ) {
			var slides = Array.prototype.filter.call( slider.children, function ( el ) {
				return el.classList.contains( 'horseclub-slide' );
			} );
			if ( slides.length < 2 ) {
				return;
			}

			slider.setAttribute( 'role', 'region' );
			slider.setAttribute( 'aria-label', settings.sliderLabel || 'Reviews' );

			var dots = document.createElement( 'div' );
			dots.className = 'horseclub-slider__dots';

			var buttons = slides.map( function ( slide, i ) {
				var dot = document.createElement( 'button' );
				dot.type = 'button';
				dot.className = 'horseclub-slider__dot';
				dot.setAttribute( 'aria-label', ( settings.slideLabel || 'Show reviews, page %d' ).replace( '%d', i + 1 ) );
				dot.addEventListener( 'click', function () {
					slider.scrollTo( { left: slide.offsetLeft - slider.offsetLeft, behavior: reduced ? 'auto' : 'smooth' } );
				} );
				dots.appendChild( dot );
				return dot;
			} );

			slider.insertAdjacentElement( 'afterend', dots );

			function mark() {
				var index = Math.round( slider.scrollLeft / Math.max( 1, slider.clientWidth ) );
				index = Math.max( 0, Math.min( slides.length - 1, index ) );
				buttons.forEach( function ( dot, i ) {
					dot.setAttribute( 'aria-current', i === index ? 'true' : 'false' );
				} );
				// Slides out of view are hidden from assistive technology too.
				slides.forEach( function ( slide, i ) {
					if ( i === index ) {
						slide.removeAttribute( 'aria-hidden' );
					} else {
						slide.setAttribute( 'aria-hidden', 'true' );
					}
				} );
			}

			var pending = null;
			slider.addEventListener( 'scroll', function () {
				window.cancelAnimationFrame( pending );
				pending = window.requestAnimationFrame( mark );
			}, { passive: true } );
			mark();
		} );
	}

	function youtubeId( url ) {
		var match = url.match( /(?:youtube\.com\/(?:watch\?(?:[^#]*&)?v=|embed\/|shorts\/)|youtu\.be\/)([\w-]{11})/ );
		return match ? match[ 1 ] : null;
	}

	function video() {
		var links = document.querySelectorAll( '.horseclub-video a[href]' );
		if ( ! links.length || 'undefined' === typeof window.HTMLDialogElement ) {
			return;
		}

		var dialog = null;
		var frame = null;

		function build() {
			dialog = document.createElement( 'dialog' );
			dialog.className = 'horseclub-video-dialog';
			dialog.setAttribute( 'aria-label', settings.videoLabel || 'Video' );

			var close = document.createElement( 'button' );
			close.type = 'button';
			close.className = 'horseclub-video-dialog__close';
			close.setAttribute( 'aria-label', settings.closeLabel || 'Close video' );
			close.textContent = '×';
			close.addEventListener( 'click', function () {
				dialog.close();
			} );

			frame = document.createElement( 'iframe' );
			frame.title = settings.videoLabel || 'Video';
			frame.setAttribute( 'allow', 'autoplay; encrypted-media; picture-in-picture; fullscreen' );
			frame.setAttribute( 'allowfullscreen', '' );

			dialog.appendChild( close );
			dialog.appendChild( frame );

			// A click on the backdrop lands on the dialog itself, never on its contents.
			dialog.addEventListener( 'click', function ( event ) {
				if ( event.target === dialog ) {
					dialog.close();
				}
			} );

			// However it closes — button, backdrop or Escape — the video stops.
			dialog.addEventListener( 'close', function () {
				frame.src = 'about:blank';
			} );

			document.body.appendChild( dialog );
		}

		Array.prototype.forEach.call( links, function ( link ) {
			var id = youtubeId( link.href );
			if ( ! id ) {
				return;
			}
			link.addEventListener( 'click', function ( event ) {
				event.preventDefault();
				if ( ! dialog ) {
					build();
				}
				frame.src = 'https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0';
				dialog.showModal();
			} );
		} );
	}

	/* The sticky header gains its shadow once the page has moved. */
	function header() {
		var bar = document.querySelector( '.horseclub-header' );
		if ( ! bar ) {
			return;
		}
		var pending = null;
		function update() {
			bar.classList.toggle( 'is-scrolled', window.scrollY > 8 );
		}
		window.addEventListener( 'scroll', function () {
			window.cancelAnimationFrame( pending );
			pending = window.requestAnimationFrame( update );
		}, { passive: true } );
		update();
	}

	function init() {
		header();
		reveals();
		sliders();
		video();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
