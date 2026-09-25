/**
 * The colorlib.com product page's screenshots, from a Playground with the demo
 * content imported (the blueprint runs .dev/demo/import.php).
 *
 * Viewport captures at 1400px wide, scaled to 1140 (the card to 1200x800),
 * JPEG q82, into .dev/publish/images/. Never full-page shots.
 *
 *   node .dev/publish/shoot.mjs            # WP_URL defaults to the 9494 Playground
 *
 * @package Horseclub
 */

import { chromium } from 'playwright';
import sharp from 'sharp';
import { mkdirSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { paletteCss } from '../photo-ground.mjs';

const site = ( process.env.WP_URL || 'http://127.0.0.1:9494' ).replace( /\/$/, '' );
const out = join( dirname( fileURLToPath( import.meta.url ) ), 'images' );
mkdirSync( out, { recursive: true } );

const W = 1400;
const H = 900;

const browser = await chromium.launch();
const context = await browser.newContext( {
	viewport: { width: W, height: H },
	deviceScaleFactor: 1,
	reducedMotion: 'reduce',
} );
const page = await context.newPage();

async function open( path, { dark = false, palette = '' } = {} ) {
	await page.goto( site + path, { waitUntil: 'load', timeout: 90000 } );
	// Playground logs every visitor in; the admin bar is not part of the theme.
	await page.addStyleTag( { content: '#wpadminbar{display:none!important} html{margin-top:0!important} :root{--wp-admin--admin-bar--height:0px!important}' } );
	if ( palette ) {
		await page.addStyleTag( { content: await paletteCss( palette ) } );
	}
	await page.evaluate( ( on ) => {
		document.documentElement.classList.toggle( 'horseclub-dark', on );
		document.documentElement.style.colorScheme = on ? 'dark' : 'light';
	}, dark );
	await page.evaluate( async () => {
		document.querySelectorAll( 'img[loading="lazy"]' ).forEach( ( img ) => {
			img.loading = 'eager';
		} );
		await Promise.allSettled( [ ...document.images ].map( ( img ) => img.decode().catch( () => {} ) ) );
	} );
	await page.waitForTimeout( 1200 );
}

// Scroll so the element's top sits just under the sticky header.
async function scrollTo( selector, offset = 0 ) {
	await page.evaluate( ( [ sel, off ] ) => {
		const el = document.querySelector( sel );
		const header = document.querySelector( '.wp-site-blocks > header' );
		const h = header ? header.getBoundingClientRect().height : 0;
		window.scrollTo( 0, el.getBoundingClientRect().top + window.scrollY - h + off );
	}, [ selector, offset ] );
	await page.waitForTimeout( 900 );
}

async function save( name, buffer, width = 1140, height = null ) {
	await sharp( buffer )
		.resize( width, height, { fit: 'cover', position: 'top', kernel: 'lanczos3' } )
		.jpeg( { quality: 82, progressive: true, mozjpeg: true } )
		.toFile( join( out, name ) );
	console.log( name );
}

// Home: the hero, as a visitor lands.
await open( '/' );
await save( 'horseclub-block-theme-home.jpg', await page.screenshot() );

// The card: 3:2, the same first screen.
await page.setViewportSize( { width: 1500, height: 1000 } );
await open( '/' );
await save( 'horseclub-free-equestrian-wordpress-theme.jpg', await page.screenshot(), 1200, 800 );
await page.setViewportSize( { width: W, height: H } );

// The booking band: reviews slider beside the lesson booking form.
await open( '/' );
await scrollTo( '.horseclub-booking' );
await save( 'horseclub-block-theme-booking-form.jpg', await page.screenshot() );

// Dark mode: the about block and the price cards.
await open( '/', { dark: true } );
await scrollTo( '.horseclub-about' );
await save( 'horseclub-block-theme-dark-mode.jpg', await page.screenshot() );

// Blog.
await open( '/blog/' );
await scrollTo( 'main', 0 );
await save( 'horseclub-block-theme-blog.jpg', await page.screenshot() );

// Theme-specific: training cards and the events page.
await open( '/training/' );
await scrollTo( 'main .is-style-horseclub-card', -250 );
await save( 'horseclub-block-theme-training.jpg', await page.screenshot() );

await open( '/events/' );
await scrollTo( 'main .horseclub-event', -250 );
await save( 'horseclub-block-theme-events.jpg', await page.screenshot() );

// Palettes: the same price cards under four of the eight palettes, 2 x 2.
const palettes = [
	[ 'colors-1-rosette', 'Rosette' ],
	[ 'colors-3-hunter', 'Hunter' ],
	[ 'colors-4-navy', 'Navy' ],
	[ 'colors-7-midnight', 'Midnight' ],
];
const tileW = 570;
const tileH = 380;
const tiles = [];
for ( const [ slug, name ] of palettes ) {
	await open( '/', { palette: slug } );
	await scrollTo( '.horseclub-prices', -40 );
	// Header plus the price cards, down to the section's own bottom edge, so no
	// sliver of the next section shows under the tile.
	const bottom = await page.evaluate( () => Math.min( window.innerHeight, Math.round( document.querySelector( '.horseclub-prices' ).closest( '.wp-block-group.alignfull, section, .wp-block-group' ).getBoundingClientRect().bottom ) ) );
	const shot = await sharp( await page.screenshot( { clip: { x: 0, y: 0, width: W, height: bottom } } ) ).resize( tileW, tileH, { fit: 'cover', position: 'top' } ).toBuffer();
	const label = Buffer.from(
		`<svg width="${ tileW }" height="${ tileH }"><rect x="12" y="${ tileH - 44 }" rx="6" width="${ 24 + name.length * 11 }" height="32" fill="rgba(0,0,0,.72)"/>` +
		`<text x="24" y="${ tileH - 22 }" font-family="Helvetica, Arial, sans-serif" font-size="17" font-weight="600" fill="#fff">${ name }</text></svg>`
	);
	tiles.push( await sharp( shot ).composite( [ { input: label } ] ).toBuffer() );
}
const gap = 0;
const grid = await sharp( {
	create: { width: tileW * 2 + gap, height: tileH * 2 + gap, channels: 3, background: '#ffffff' },
} ).composite( tiles.map( ( input, i ) => ( { input, left: ( i % 2 ) * ( tileW + gap ), top: Math.floor( i / 2 ) * ( tileH + gap ) } ) ) ).png().toBuffer();
await save( 'horseclub-block-theme-colour-palettes.jpg', grid );

await browser.close();
