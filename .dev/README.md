# Build tooling

Nothing in `patterns/`, `theme.json` or `styles/` is written by hand. Edit the
generator, run it, and commit what it produces.

```bash
python3 .dev/build_theme.py        # theme.json + styles/colors/* + styles/typography/*
python3 .dev/build_patterns.py     # patterns/*.php
node    .dev/build-fonts.mjs       # assets/fonts/*.woff2 (only when the faces change)
```

## The order that matters

Generated block markup is a guess until the editor has seen it. Block comment
attributes must match what a block's `save()` writes, and when they do not, the
editor shows "this block contains unexpected or invalid content" — while the
front end looks perfect and nothing warns you at build time.

So, against a WordPress with this theme active:

```bash
node .dev/normalize-blocks.mjs     # re-serialise every pattern as the editor would
node .dev/validate-blocks.mjs      # then fail on anything still invalid (patterns, templates, stored pages)
node .dev/explain-invalid.mjs patterns/x.php   # what the editor expected, for a pattern that will not normalise
```

A throwaway WordPress to run them against (port 9494 is this theme's):

```bash
npx -y @wp-playground/cli@3.1.54 server --port=9494 --php=8.3 --wp=latest \
  --mount-before-install="$PWD:/wordpress/wp-content/themes/horseclub" \
  --blueprint=.dev/blueprint.json --login
```

The blueprint activates the theme (which builds the starter pages) and runs
`.dev/demo/import.php`, the same importer the colorlibhub demo uses: site title
and tagline, six posts with categories, tags, photographs from `.dev/demo/` and
a comment, and WordPress's "Hello world!" and "Sample Page" removed. It is safe
to run twice. None of it ships. On the demo server:

```bash
sudo -u www-data wp --path=/var/www/colorlibhub.com/public \
  --url=https://colorlibhub.com/horseclub-blocks/ eval "require '<dir>/import.php';"
```

**Starter pages are copies made at activation.** After changing a pattern,
restart the Playground before judging a page, or you are looking at the old one.

Every script defaults to `WP_URL=http://127.0.0.1:9494`, `admin`/`password`,
and reads the pages to visit from `HORSECLUB_PATHS` (comma-separated).

## The other checks

```bash
node .dev/contrast-rendered.mjs    # measured text contrast, photographs sampled by pixel
HORSECLUB_DARK=1 node .dev/contrast-rendered.mjs
node .dev/button-boundary.mjs      # every button visible as a button (fill/border 3:1, label 4.5:1)
HORSECLUB_DARK=1 node .dev/button-boundary.mjs
HORSECLUB_PALETTE=colors-7-midnight node .dev/contrast-rendered.mjs   # any of the eight palettes
node .dev/overflow-check.mjs       # elements wider than the box they live in, 1400/1024/768/390
node .dev/alignment-check.mjs      # constrained children that took the width cap but not the centring
python3 .dev/dead-selectors.py     # CSS nothing emits; registered block styles nothing styles
node .dev/editor-check.mjs         # every pattern opened in the editor: warnings, placeholders, icons
node .dev/compare.mjs              # side-by-side renders against the HTML template -> .dev/compare/
node .dev/screenshot.mjs <url>     # screenshot.png at 1200x900
bash .dev/build-zip.sh [outdir]    # the distributable, without .dev or node_modules
```

`build_theme.py` audits every palette before writing and **refuses to emit one
that fails WCAG AA** on any foreground/background pair the design produces,
including the dark-mode palette `assets/css/scheme.css` derives at runtime (it
reads that file, so the two cannot drift).

The template's own colours are decorative for that reason. `#f6214b` is 4.0:1
on white and `#f45622` 3.4:1; both ship as `accent`/`accent-warm`, and deeper
shades — `primary`, `primary-warm` — carry links, labels and the button
gradient.

## What the measuring scripts measure, and the two traps fixed in them

- **Text on a gradient fill is sampled with only its letters made transparent**
  (and its transition off). Hiding the element instead photographed the white
  page behind every gradient button and reported its label at 1:1; with the
  transition left on, the label was still half-drawn when the pixels were read.
- **A gradient fill is its colour stops.** The button check reads
  `background-image`, not only `background-color`, and holds the worst stop.
- Large text (24px, or 18.66px bold) is held to 3:1 and icon-only buttons to
  WCAG 1.4.11's 3:1; everything else to 4.5:1.
- Text scrolled out of a clipping ancestor (the reviews slider's other slides)
  is not on screen and is skipped.
- `dead-selectors.py` ignores a bare `horseclub-` prefix: an id built as
  `'horseclub-' . $form` made every selector count as live and the check pass
  without checking anything.

## Spacing and colour are vocabularies, not values

`sp()` refuses any spacing step that is not on the registered scale, because an
undefined preset variable makes WordPress drop the whole declaration and the
element silently falls back to its inherited gap. The same applies to colour
slugs: patterns name `primary` or `surface`, never a hex value, which is what
lets all eight palettes restyle every section.
