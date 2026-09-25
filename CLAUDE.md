# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

**Horseclub 1.0.0** is a Colorlib **WordPress block theme** (Full Site Editing)
for riding schools, equestrian clubs and livery yards. 32 patterns, 14
templates, 3 parts, 8 colour palettes × 5 type pairings, 8 starter pages built
on activation, visitor dark mode, WooCommerce styling, and booking / contact /
newsletter forms that need no plugin. Text domain `horseclub`.

It was rebuilt from the **Horse Club HTML template**
(`preview.colorlib.com/theme/horseclub/`, Bootstrap 4 + jQuery). The design was
reproduced section by section — header, hero, feature band, price cards,
booking band, gallery, footer, inner-page banner — and judged with side-by-side
full-page renders (`.dev/compare.mjs`), not property by property. The WordPress
demo of the old Elementor version is `colorlibhub.com/horseclub/`.

**Block theme only, no companion plugin, no page builder, no jQuery.**
Distribution is **outside WordPress.org**: `Update URI` points at
`updates.colorlib.com`, like Unioncorp, Pato, Academia and Unapp. It is **not**
a static HTML template — the HTML-template upgrade phases and R2 preview flow in
the global instructions do not apply.

Toolchain and `inc/` architecture were ported from Unioncorp
(`~/Fresh Projects/unioncorp-blocks`). Requires WP 6.6+, tested to 7.1, PHP 7.4+.

## Commands

```bash
python3 .dev/build_theme.py        # theme.json + styles/**; audits every palette, refuses a failing one
python3 .dev/build_patterns.py     # patterns/*.php (deletes and rewrites them all)
node    .dev/build-fonts.mjs       # assets/fonts: Poppins 300–700 + Playfair variable, latin + latin-ext

# A throwaway WordPress on this theme's port (9494). Restart it after any
# pattern change: starter pages are copies made at activation.
npx -y @wp-playground/cli@3.1.54 server --port=9494 --php=8.3 --wp=latest \
  --mount-before-install="$PWD:/wordpress/wp-content/themes/horseclub" \
  --blueprint=.dev/blueprint.json --login

node .dev/normalize-blocks.mjs     # ALWAYS after build_patterns.py
node .dev/validate-blocks.mjs      # 0 invalid across patterns, templates, parts, stored pages, menus
node .dev/editor-check.mjs         # every pattern opened in the editor
node .dev/contrast-rendered.mjs && HORSECLUB_DARK=1 node .dev/contrast-rendered.mjs
node .dev/button-boundary.mjs && HORSECLUB_DARK=1 node .dev/button-boundary.mjs
HORSECLUB_PALETTE=colors-8-stable node .dev/contrast-rendered.mjs   # any palette
node .dev/overflow-check.mjs && node .dev/alignment-check.mjs
python3 .dev/dead-selectors.py
node .dev/compare.mjs              # .dev/compare/<page>-<width>.png, template left, theme right
bash .dev/build-zip.sh /tmp/horseclub-build
```

Playwright and sharp come from `node_modules`, a symlink (gitignored) to
`~/Fresh Projects/tailwind-templates/node_modules`.

**Theme Check** runs in a separate Playground with no server (so no port):
`npx @wp-playground/cli run-blueprint` with the built theme mounted, a step
installing `theme-check`, and a runPHP step that calls
`run_themechecks_against_theme()` and writes the findings to a mounted folder.
**`languages/horseclub.pot`**: `wp i18n make-pot <built theme> languages/horseclub.pot
--domain=horseclub` with `~/.local/bin/wp-cli.phar` and any PHP (the Playground
`wp-cli` step fails on a CA bundle).

## Where things live

| Concern | File(s) |
| --- | --- |
| Palettes, type, spacing, gradients, element styles | `.dev/build_theme.py` → `theme.json`, `styles/colors/*`, `styles/typography/*` |
| Every section, part body, hidden partial and page | `.dev/build_patterns.py` (+ `.dev/patternlib.py`) → `patterns/*.php` |
| Header / footer / sidebar parts | `parts/*.html`, each one `wp:pattern` reference |
| Templates (14) | `templates/*.html` — hand-written, validated by validate-blocks |
| Component CSS, block style variations, header, icons | `style.css` (also the editor stylesheet) |
| Booking, contact and newsletter forms | `inc/booking.php` (`[horseclub_form type= layout= button=]`) + `assets/css/forms.css` |
| Starter pages + menu on activation | `inc/front-page-setup.php` |
| Dark mode | `inc/scheme.php`, `assets/css/scheme.css`, `assets/js/scheme-toggle.js` |
| Reveals, reviews slider, video popup, header shadow | `assets/js/interactions.js` |
| Self-hosted updates | `inc/updates.php` |
| WooCommerce | `inc/woocommerce.php`, `assets/css/woocommerce.css` |
| Demo content: colorlibhub demo + Playground (not shipped) | `.dev/demo/import.php`, `.dev/demo/*.jpg` |

## Conventions that matter

- **Never hand-write pattern markup.** Generate it, then `normalize-blocks.mjs`
  re-serialises it through the real `wp.blocks.serialize`. Markup that is not
  valid on parse is written back unchanged (the serializer keeps invalid blocks
  raw), so the generator must be close; `explain-invalid.mjs` shows the diff.
  Cover `dimRatio` 50 writes **no** `has-background-dim-50` class (core's default).
- **Colours are palette slugs.** Anything on a non-`base` ground has its own
  slug: `overlay` (text on photographs and `dark`), `on-dark` (body copy there),
  `on-primary` (labels on primary fills). `accent`/`accent-warm` are the
  template's colours and are decorative only; `primary`/`primary-warm` are the
  readable shades and form the button gradient (`brand-deep`). The bright
  `brand` gradient is only for the header squares, price numerals and gallery
  hover. Every pair is in `CONTRAST_CHECKS`.
- **Preset classes carry `!important`.** A paragraph with `has-x-small-font-size`
  cannot be resized from CSS; `eyebrow()` therefore sets no size and the
  `is-style-horseclub-eyebrow` rule does. Same for colours.
- **Sizes without a fluid range are `fluid: false`.** Otherwise WordPress's
  default fluid rule shrinks 15px body copy to 14px and the 18px site title to
  14px on a phone.
- **A space before every `<br>` in a heading.** Below 992px the hero hides its
  `<br>`s, and "Between<br>Horse" became "BetweenHorse".
- **Separators need `:not(.is-style-wide):not(.is-style-dots)`** to set a width:
  core's `wp-block-styles` fixes a default separator at 100px with that selector.
- **A group with a background gets core's 1.25em padding**; the header resets it.
- **The header is sticky** on `.wp-site-blocks > header` with
  `top: var(--wp-admin--admin-bar--height)`; below 960px the side contact blocks
  and the rule hide and the navigation row becomes the top-right menu button.
  Core's own collapse is 600px; style.css moves it to 959px.
- **Starter content is inserted with `wp_slash()` and with kses lifted.** The
  contact map is an `<iframe>`; kses strips it for anyone without
  `unfiltered_html` — including *no user*, which is how WP-CLI and Playground
  blueprints activate a theme. `horseclub_create_front_page()` removes and
  restores the kses filters around its own inserts only.
- **`get_page_by_path()` also matches attachments**; `horseclub_page_exists()`
  queries pages by name instead.
- **Forms are one shortcode** (`type` = booking | contact | newsletter). The
  redirect target travels in a hidden field and is checked with
  `wp_validate_redirect()`; the notice shows only on the form that was sent
  (`horseclub-form-type`). `core/shortcode` needs `do_shortcode` on
  `render_block_core/shortcode` for the footer's newsletter (a template part).
- **Dark mode lifts the palette** (`accent` 72% / 56% + white, `accent-warm`
  72%) and `build_theme.py` reads those numbers from scheme.css and audits them.
  The switch's own styles are in style.css so the editor draws it; it renders
  nothing when `horseclub_enable_dark_mode` is false.
- **Buttons on photographs carry a pale hairline** (`overlay` 60% into
  `accent`): the red fill on a darkened photo is 2.2–2.6:1 by luminance, and the
  hairline is what makes the boundary 3:1.
- **Reveals skip the slider** (its slides sit off screen), and never hide
  anything above the fold.
- **Porting trap:** every word from the source theme is a silent bug. `grep -ri
  "unioncorp\|pato" .` must return nothing outside credits.

## Theme Check

Three REQUIRED findings, no warnings, all deliberate: `Update URI` (self-hosted
distribution), `add_shortcode()` (forms must survive pattern expansion into
content), and the Pixabay reference in readme.txt's photo credits (one
photograph: card-5, event-3, gallery-1; Pexels is not flagged). No Freepik or
Creative Market photograph may ship, in the theme or the demo content.
readme.txt says what each finding would take to remove.

## Release checklist

1. `php -l` every PHP file, `node --check` every JS file, JSON parses.
2. Regenerate; `build_theme.py` must print no contrast failures.
3. Fresh Playground; `normalize` → `validate` → `editor-check` → contrast (light,
   dark, all eight palettes) → `button-boundary` (light, dark) → `overflow` →
   `alignment` → `dead-selectors`.
4. `compare.mjs` and look at the renders.
5. Bump `Version:` (style.css), `HORSECLUB_VERSION` (functions.php), `Stable tag:`
   (readme.txt) and add a changelog entry.
6. `build-zip.sh`, Theme Check the built theme, regenerate the .pot.
