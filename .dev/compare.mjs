/**
 * Side-by-side full-page renders: the HTML template on the left, the theme on
 * the right, at the same width, written to .dev/compare/.
 *
 * Comparing properties one at a time is not comparing designs: every value can
 * be defensible and the whole still read as a quieter relative. These put the
 * whole page next to the page it was drawn from.
 *
 *   WP_URL=http://127.0.0.1:9494 node .dev/compare.mjs            # all pages, 1440 and 390
 *   PAGES=home,services WIDTHS=1440 node .dev/compare.mjs
 *
 * The admin bar is hidden (Playground logs every visitor in), and lazy images
 * are loaded before the capture so nothing is an empty box.
 */

import { chromium } from 'playwright';
import sharp from 'sharp';
import { mkdirSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const out = join( resolve( dirname( fileURLToPath( import.meta.url ) ) ), 'compare' );
const site = ( process.env.WP_URL || 'http://127.0.0.1:9494' ).replace( /\/$/, '' );
const template = 'https://preview.colorlib.com/theme/horseclub/';

const MAP = {
	home: [ 'index.html', '/' ],
	about: [ 'about.html', '/about/' ],
	services: [ 'service.html', '/services/' ],
	training: [ 'training.html', '/training/' ],
	events: [ 'events.html', '/events/' ],
	pricing: [ 'pricing.html', '/pricing/' ],
	contact: [ 'contact.html', '/contact/' ],
	blog: [ 'blog-home.html', '/blog/' ],
	single: [ 'blog-single.html', '/spring-league-round-one/' ],
};

const pages = ( process.env.PAGES || Object.keys( MAP ).join( ',' ) ).split( ',' );
const widths = ( process.env.WIDTHS || '1440,390' ).split( ',' ).map( Number );

mkdirSync( out, { recursive: true } );
const browser = await chromium.launch();

async function capture( url, width ) {
	const context = await browser.newContext( {
		viewport: { width, height: 900 },
		deviceScaleFactor: 1,
		isMobile: width < 500,
		hasTouch: width < 500,
		reducedMotion: 'reduce',
	} );
	const page = await context.newPage();
	await page.goto( url, { waitUntil: 'networkidle', timeout: 90000 } ).catch( () => {} );
	await page.addStyleTag( { content: '#wpadminbar{display:none!important} html{margin-top:0!important} :root{--wp-admin--admin-bar--height:0px!important}' } );
	await page.evaluate( async () => {
		document.querySelectorAll( 'img[loading="lazy"]' ).forEach( ( img ) => {
			img.loading = 'eager';
		} );
		for ( let y = 0; y < document.body.scrollHeight; y += 700 ) {
			window.scrollTo( 0, y );
			await new Promise( ( r ) => setTimeout( r, 50 ) );
		}
		window.scrollTo( 0, 0 );
		await Promise.allSettled( [ ...document.images ].map( ( img ) => img.decode().catch( () => {} ) ) );
	} );
	await page.waitForTimeout( 1200 );
	const png = await page.screenshot( { fullPage: true } );
	await context.close();
	return png;
}

function label( text, width ) {
	return Buffer.from( `<svg xmlns="http://www.w3.org/2000/svg" width="${ width }" height="44"><rect width="100%" height="100%" fill="#111"/><text x="16" y="29" font-family="Helvetica, Arial" font-size="20" fill="#fff">${ text }</text></svg>` );
}

for ( const name of pages ) {
	const [ tpl, path ] = MAP[ name ];
	for ( const width of widths ) {
		const left = await capture( template + tpl, width );
		const right = await capture( site + path, width );
		const lm = await sharp( left ).metadata();
		const rm = await sharp( right ).metadata();
		const gap = 24;
		const height = Math.max( lm.height, rm.height ) + 44;
		const file = join( out, `${ name }-${ width }.jpg` );
		await sharp( { create: { width: width * 2 + gap, height, channels: 3, background: '#d0d0d0' } } )
			.composite( [
				{ input: label( 'Template: ' + tpl, width ), left: 0, top: 0 },
				{ input: label( 'Theme: ' + path, width ), left: width + gap, top: 0 },
				{ input: left, left: 0, top: 44 },
				{ input: right, left: width + gap, top: 44 },
			] )
			.jpeg( { quality: 78, mozjpeg: true } )
			.toFile( file );
		console.log( `  ${ file }  template ${ lm.height }px, theme ${ rm.height }px` );
	}
}

await browser.close();
