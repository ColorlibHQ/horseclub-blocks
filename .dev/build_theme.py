#!/usr/bin/env python3
"""
Generates theme.json and every style variation from one table of colours.

Horseclub's brand pink-red, #f6214b, is 4.01:1 on white and the orange that
starts its gradient, #f45622, is 3.39:1. Both are fine for a 1px rule, a
decorative square behind an icon or a 36px numeral, and both fail AA for body
text, links and a 14px button label. So the palette keeps them as `accent` and
`accent-warm`, for decoration only, and derives `primary` and `primary-warm`
(deeper shades of the same two hues) for everything a reader has to read. The
button gradient runs between those two, so it keeps the template's orange-to-red
sweep and still carries a white label at AA.

Nothing here is eyeballed: audit() computes every pair the design actually
produces and refuses to write a palette that fails, including the dark-mode
palette that assets/css/scheme.css derives at runtime.

Usage:  python3 .dev/build_theme.py
"""

import collections
import json
import os
import re

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
os.chdir(ROOT)

# ---------------------------------------------------------------------------
# Palette
# ---------------------------------------------------------------------------
# Fourteen slugs, the same in every variation, so a pattern written against them
# works under all of them. `overlay` is separate from `base` on purpose: text on
# a dimmed photograph must stay near-white even when the palette is dark, and
# writing it as `base` is what turns a dark palette's covers black-on-black.
PALETTE = [
    ("Base",          "base",          "#ffffff"),
    ("Surface",       "surface",       "#f9f9ff"),   # the template's own panel ground
    ("Contrast",      "contrast",      "#222222"),   # the template's heading colour
    ("Muted",         "muted",         "#6b6b6b"),   # body copy; the template's #777 is 4.48:1
    ("Primary",       "primary",       "#d90f3a"),   # readable brand red: links, buttons
    ("Primary deep",  "primary-deep",  "#b00c2f"),   # hover
    ("Primary warm",  "primary-warm",  "#c9431b"),   # readable orange: where the button gradient starts
    ("Accent",        "accent",        "#f6214b"),   # the brand red, decorative
    ("Accent warm",   "accent-warm",   "#f45622"),   # the brand orange, decorative
    ("Dark",          "dark",          "#111111"),   # the ground under dimmed photographs
    ("Divider",       "divider",       "#e8e8ef"),
    ("Overlay",       "overlay",       "#ffffff"),   # text on photographs and on `dark`
    # Body copy on a dimmed photograph. The template sets #777 there, which is
    # about 3:1 against its own darkened hero; a pattern needs one slug that
    # reads on the dark ground in every palette.
    ("On dark",       "on-dark",       "#cfcfcf"),
    # The label on a primary fill. White in the light palettes; in the dark ones
    # `primary` is a light colour and the label has to be the dark one.
    ("On primary",    "on-primary",    "#ffffff"),
]

COLOR_SETS = {
    "colors-1-rosette": ("Rosette", {
        "base": "#ffffff", "surface": "#f9f9ff", "contrast": "#222222", "muted": "#6b6b6b",
        "primary": "#d90f3a", "primary-deep": "#b00c2f", "primary-warm": "#c9431b",
        "accent": "#f6214b", "accent-warm": "#f45622", "dark": "#111111", "divider": "#e8e8ef",
        "overlay": "#ffffff", "on-dark": "#cfcfcf", "on-primary": "#ffffff",
    }),
    "colors-2-saddle": ("Saddle", {
        "base": "#ffffff", "surface": "#faf6f1", "contrast": "#2a1d14", "muted": "#6b5a4d",
        "primary": "#9a4812", "primary-deep": "#76370d", "primary-warm": "#8f5a10",
        "accent": "#c8763a", "accent-warm": "#b07d24", "dark": "#1a120c", "divider": "#ebe0d4",
        "overlay": "#ffffff", "on-dark": "#dccdbf", "on-primary": "#ffffff",
    }),
    "colors-3-hunter": ("Hunter", {
        "base": "#ffffff", "surface": "#f4f8f5", "contrast": "#13261c", "muted": "#546559",
        "primary": "#1d6a44", "primary-deep": "#144e32", "primary-warm": "#4d6a1a",
        "accent": "#2e9e62", "accent-warm": "#6f9a2a", "dark": "#0b1811", "divider": "#dce8e0",
        "overlay": "#ffffff", "on-dark": "#c6d8cc", "on-primary": "#ffffff",
    }),
    "colors-4-navy": ("Navy", {
        "base": "#ffffff", "surface": "#f5f7fc", "contrast": "#111b2e", "muted": "#55617a",
        "primary": "#1d4282", "primary-deep": "#142f5e", "primary-warm": "#1f5b9a",
        "accent": "#3a6fd0", "accent-warm": "#2f8fc4", "dark": "#0a111e", "divider": "#dde3ee",
        "overlay": "#ffffff", "on-dark": "#c9d3e3", "on-primary": "#ffffff",
    }),
    "colors-5-burgundy": ("Burgundy", {
        "base": "#ffffff", "surface": "#fbf6f7", "contrast": "#2a1218", "muted": "#6d5960",
        "primary": "#8e1b3a", "primary-deep": "#6e132c", "primary-warm": "#a2322a",
        "accent": "#c2375e", "accent-warm": "#e0703a", "dark": "#1a0a0f", "divider": "#eedde2",
        "overlay": "#ffffff", "on-dark": "#dcc8ce", "on-primary": "#ffffff",
    }),
    "colors-6-meadow": ("Meadow", {
        "base": "#ffffff", "surface": "#f7f8f1", "contrast": "#1f2414", "muted": "#5d6450",
        "primary": "#4b6a16", "primary-deep": "#374f0f", "primary-warm": "#7a5a10",
        "accent": "#7d9b2e", "accent-warm": "#b07d24", "dark": "#12160a", "divider": "#e4e8d6",
        "overlay": "#ffffff", "on-dark": "#d3d9c2", "on-primary": "#ffffff",
    }),
    # Dark palettes: `base` is the page, so it is dark here. A button is
    # bright-on-dark, which means its label is the DARK colour — the opposite of
    # the usual rule, and the reason the audit measures instead of assuming.
    "colors-7-midnight": ("Midnight", {
        "base": "#0f131a", "surface": "#161c26", "contrast": "#eef1f6", "muted": "#a4adbb",
        "primary": "#ff7d8e", "primary-deep": "#ffa6b1", "primary-warm": "#ff9468",
        "accent": "#f6214b", "accent-warm": "#f45622", "dark": "#080b10", "divider": "#263041",
        "overlay": "#ffffff", "on-dark": "#b8c0cc", "on-primary": "#0f131a",
    }),
    "colors-8-stable": ("Stable", {
        "base": "#17120e", "surface": "#211a14", "contrast": "#f3ece4", "muted": "#b4a698",
        "primary": "#e8a86a", "primary-deep": "#f3c79a", "primary-warm": "#ecb65e",
        "accent": "#c8763a", "accent-warm": "#b8862b", "dark": "#0d0906", "divider": "#352a20",
        "overlay": "#ffffff", "on-dark": "#c9bcae", "on-primary": "#17120e",
    }),
}
DEFAULT_COLORS = "colors-1-rosette"

# Typography. Poppins is the template's only face. Playfair Display is the
# serif an equestrian club reaches for (rosettes, show programmes), and the
# system stack is the zero-download option. Two families, five pairings.
TYPE_SETS = {
    "type-1-poppins": ("Poppins throughout", "poppins", "poppins", None),
    "type-2-playfair-poppins": ("Playfair Display headings, Poppins text", "playfair", "poppins", None),
    "type-3-poppins-system": ("Poppins headings, system text", "poppins", "system", "400"),
    "type-4-playfair-system": ("Playfair Display headings, system text", "playfair", "system", "400"),
    "type-5-system": ("System fonts", "system", "system", "400"),
}

LATIN = ("U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, "
         "U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD")
LATIN_EXT = ("U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, "
             "U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, "
             "U+2C60-2C7F, U+A720-A7FF")

# name, CSS stack, [(weight, file stem)] — each stem ships as -latin and -latin-ext.
FAMILIES = collections.OrderedDict([
    ("poppins", ("Poppins", "Poppins, system-ui, -apple-system, 'Segoe UI', sans-serif",
                 [(w, "poppins-%s-" + w + "-normal") for w in ("300", "400", "500", "600", "700")])),
    ("playfair", ("Playfair Display", "'Playfair Display', Georgia, 'Times New Roman', serif",
                  [("400 900", "playfair-display-%s-wght-normal")])),
    ("system", ("System", "system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif", [])),
])


def fluid(minimum, maximum):
    return collections.OrderedDict([("min", minimum), ("max", maximum)])


# The template's scale, restated: 12px navigation and eyebrows, 14px copy and
# buttons, 18px card titles, 24px footer figures, 36px section titles, 48px
# page banners and 60px on the hero. Body copy is 15px rather than 14: the
# template's 14px light weight is a demo's size, and a club's blog is read.
FONT_SIZES = [
    ("X Small",  "x-small",  "0.75rem",   None),
    ("Small",    "small",    "0.875rem",  None),
    ("Medium",   "medium",   "0.9375rem", None),
    ("Large",    "large",    "1.125rem",  None),
    ("X Large",  "x-large",  "1.5rem",    fluid("1.25rem", "1.5rem")),
    ("Heading",  "heading",  "2.25rem",   fluid("1.75rem", "2.25rem")),
    ("Display",  "display",  "3rem",      fluid("2.125rem", "3rem")),
    ("Colossal", "colossal", "3.75rem",   fluid("2.25rem", "3.75rem")),
]

# The template's section gap is 100px and its section-title gap 70px; 80 and 60
# land on those at desktop and step down on a phone.
SPACING = [
    ("20", "0.5rem"),
    ("30", "1rem"),
    ("40", "1.5rem"),
    ("50", "clamp(1.75rem, 3vw, 2.5rem)"),
    ("60", "clamp(2.5rem, 5vw, 4.375rem)"),
    ("70", "clamp(3rem, 6vw, 5rem)"),
    ("80", "clamp(4rem, 8vw, 6.25rem)"),
]

# Every foreground/background pair the design actually puts together, at the
# ratio it needs: 4.5 for text, 3 for large numerals and graphics.
CONTRAST_CHECKS = [
    ("contrast", "base", 4.5), ("contrast", "surface", 4.5),
    ("muted", "base", 4.5), ("muted", "surface", 4.5),
    ("primary", "base", 4.5), ("primary", "surface", 4.5),
    ("primary-deep", "base", 4.5),
    ("overlay", "dark", 4.5),
    ("on-dark", "dark", 4.5),
    # Every button label on every fill it can have: resting, hovered, and the
    # warm end of the gradient.
    ("on-primary", "primary", 4.5),
    ("on-primary", "primary-deep", 4.5),
    ("on-primary", "primary-warm", 4.5),
    # The dark "Get started" button is `contrast` with a `base` label.
    ("base", "contrast", 4.5),
    # White numerals (36px, large text) on the brand-gradient circles of the
    # pricing cards, and white icons on the header's gradient squares.
    ("overlay", "accent", 3.0),
    ("overlay", "accent-warm", 3.0),
]


# ---------------------------------------------------------------------------
# Contrast
# ---------------------------------------------------------------------------
def _channel(value):
    value = value / 255
    return value / 12.92 if value <= 0.04045 else ((value + 0.055) / 1.055) ** 2.4


def luminance(hex_colour):
    r, g, b = (int(hex_colour[i:i + 2], 16) for i in (1, 3, 5))
    return 0.2126 * _channel(r) + 0.7152 * _channel(g) + 0.0722 * _channel(b)


def contrast_ratio(a, b):
    la, lb = luminance(a), luminance(b)
    lighter, darker = max(la, lb), min(la, lb)
    return (lighter + 0.05) / (darker + 0.05)


# ---------------------------------------------------------------------------
# theme.json
# ---------------------------------------------------------------------------
def od(*pairs):
    return collections.OrderedDict(pairs)


def var(slug):
    return "var(--wp--preset--color--%s)" % slug


def fs(slug):
    return "var(--wp--preset--font-size--%s)" % slug


def ff(slug):
    return "var(--wp--preset--font-family--%s)" % slug


def sp(slug):
    valid = {s for s, _ in SPACING}
    if slug not in valid:
        raise SystemExit("spacing %r is not on the scale %s" % (slug, sorted(valid)))
    return "var(--wp--preset--spacing--%s)" % slug


def palette(colors):
    missing = [slug for _, slug, _ in PALETTE if slug not in colors]
    if missing:
        raise SystemExit("palette is missing %s" % ", ".join(missing))
    return [od(("name", name), ("slug", slug), ("color", colors[slug])) for name, slug, _ in PALETTE]


def font_families():
    out = []
    for key, (name, stack, faces) in FAMILIES.items():
        entry = od(("name", name), ("slug", key), ("fontFamily", stack))
        if faces:
            entry["fontFace"] = []
            for weight, stem in faces:
                for subset, rng in (("latin", LATIN), ("latin-ext", LATIN_EXT)):
                    entry["fontFace"].append(od(
                        ("fontFamily", name), ("fontStyle", "normal"), ("fontWeight", weight),
                        ("fontDisplay", "swap"),
                        ("src", ["file:./assets/fonts/%s.woff2" % (stem % subset)]),
                        ("unicodeRange", rng),
                    ))
        out.append(entry)
    return out


def font_files():
    files = []
    for _, (_, _, faces) in FAMILIES.items():
        for _, stem in faces:
            files += ["%s.woff2" % (stem % s) for s in ("latin", "latin-ext")]
    return files


GRADIENTS = [
    # The template's orange-to-red sweep, for decoration: the header's icon
    # squares, the pricing numerals, the gallery hover.
    ("Brand", "brand", "linear-gradient(90deg, %s 0%%, %s 100%%)" % (var("accent-warm"), var("accent"))),
    # The same sweep in the readable shades, for anything carrying a label.
    ("Brand deep", "brand-deep", "linear-gradient(90deg, %s 0%%, %s 100%%)" % (var("primary-warm"), var("primary"))),
]


def build_settings():
    return od(
        ("appearanceTools", True),
        ("useRootPaddingAwareAlignments", True),
        # The template is a Bootstrap 4 layout: a 1140px container with 15px
        # gutters, so 1110px of content. Wide is the blog's article + sidebar.
        ("layout", od(("contentSize", "1110px"), ("wideSize", "1290px"))),
        ("color", od(("custom", True), ("defaultPalette", False), ("defaultGradients", False),
                     ("defaultDuotone", False),
                     ("palette", palette(COLOR_SETS[DEFAULT_COLORS][1])),
                     ("gradients", [od(("name", n), ("slug", s), ("gradient", g)) for n, s, g in GRADIENTS]))),
        ("typography", od(
            ("fluid", True), ("customFontSize", True), ("defaultFontSizes", False),
            ("fontFamilies", font_families()),
            # A size with no fluid range is fixed: left to WordPress's default
            # fluid rule, 15px body copy shrinks to 14px on a phone and the 18px
            # site title to 14px.
            ("fontSizes", [od(("name", name), ("slug", slug), ("size", size), ("fluid", f if f else False))
                           for name, slug, size, f in FONT_SIZES]),
        )),
        ("spacing", od(("units", ["px", "em", "rem", "vh", "vw", "%"]),
                       ("padding", True), ("margin", True), ("blockGap", True),
                       ("defaultSpacingSizes", False),
                       ("spacingSizes", [od(("name", name), ("slug", name), ("size", size))
                                         for name, size in SPACING]))),
        ("border", od(("color", True), ("radius", True), ("style", True), ("width", True))),
        ("shadow", od(("defaultPresets", False), ("presets", [
            # The template's own card hover, a long soft shadow down and left.
            od(("name", "Card"), ("slug", "card"), ("shadow", "-14px 14px 20px 0 rgba(157, 157, 157, 0.3)")),
            od(("name", "Header"), ("slug", "header"), ("shadow", "0 10px 30px 0 rgba(158, 158, 158, 0.3)")),
        ]))),
    )


def build_styles():
    return od(
        ("color", od(("background", var("base")), ("text", var("muted")))),
        ("typography", od(("fontFamily", ff("poppins")), ("fontSize", fs("medium")),
                          ("fontWeight", "300"), ("lineHeight", "1.7"))),
        ("spacing", od(("blockGap", sp("40")),
                       ("padding", od(("top", "0px"), ("bottom", "0px"),
                                      ("left", sp("40")), ("right", sp("40")))))),
        ("elements", od(
            ("heading", od(("typography", od(("fontFamily", ff("poppins")), ("fontWeight", "600"),
                                             ("lineHeight", "1.2"))),
                           ("color", od(("text", var("contrast")))))),
            ("h1", od(("typography", od(("fontSize", fs("display")))))),
            ("h2", od(("typography", od(("fontSize", fs("heading")))))),
            ("h3", od(("typography", od(("fontSize", fs("x-large")))))),
            ("h4", od(("typography", od(("fontSize", fs("large")))))),
            ("h5", od(("typography", od(("fontSize", "1rem"))))),
            ("h6", od(("typography", od(("fontSize", fs("small")))))),
            ("link", od(("color", od(("text", var("primary")))),
                        (":hover", od(("color", od(("text", var("primary-deep")))))))),
            ("button", od(
                # The template's gradient button, in the readable shades. The
                # label is `on-primary`, which the audit holds against both ends
                # of the gradient and the hover fill, in every palette and in
                # dark mode.
                ("color", od(("gradient", "var(--wp--preset--gradient--brand-deep)"),
                             ("text", var("on-primary")))),
                ("typography", od(("fontFamily", "inherit"), ("fontWeight", "600"),
                                  ("fontSize", fs("small")), ("lineHeight", "1.5"))),
                ("border", od(("radius", "0px"), ("width", "1px"), ("style", "solid"),
                              ("color", "transparent"))),
                ("spacing", od(("padding", od(("top", "0.6rem"), ("bottom", "0.6rem"),
                                              ("left", "1.875rem"), ("right", "1.875rem"))))),
            )),
            ("caption", od(("typography", od(("fontSize", fs("small")))))),
        )),
        ("blocks", od(
            ("core/separator", od(("color", od(("text", var("divider")))))),
            ("core/site-title", od(("typography", od(("fontWeight", "600"), ("fontSize", fs("large")),
                                                     ("textTransform", "uppercase"),
                                                     ("letterSpacing", "0.02em"))),
                                   ("elements", od(("link", od(("color", od(("text", var("contrast")))),
                                                               ("typography", od(("textDecoration", "none"))))))))),
            ("core/navigation", od(("typography", od(("fontSize", fs("x-small")), ("fontWeight", "500"),
                                                     ("textTransform", "uppercase"))))),
            ("core/post-title", od(("elements", od(("link", od(("color", od(("text", var("contrast")))),
                                                               ("typography", od(("textDecoration", "none"))))))))),
            ("core/quote", od(("border", od(("left", od(("color", var("accent")), ("style", "solid"),
                                                        ("width", "2px"))))),
                              ("color", od(("background", var("surface")))),
                              ("spacing", od(("padding", od(("top", sp("40")), ("bottom", sp("40")),
                                                            ("left", sp("40")), ("right", sp("40")))))),
                              ("typography", od(("fontStyle", "normal")))),
             ),
            ("core/pullquote", od(("border", od(("top", od(("color", var("accent")), ("style", "solid"), ("width", "2px"))),
                                                ("bottom", od(("color", var("accent")), ("style", "solid"), ("width", "2px"))))))),
        )),
    )


def build_theme():
    return od(
        ("$schema", "https://schemas.wp.org/trunk/theme.json"),
        ("version", 3),
        ("settings", build_settings()),
        ("styles", build_styles()),
        ("customTemplates", [
            od(("name", "page-no-title"), ("title", "Page without banner"), ("postTypes", ["page"])),
            od(("name", "page-with-sidebar"), ("title", "Page with sidebar"), ("postTypes", ["page"])),
            od(("name", "single-no-sidebar"), ("title", "Post without sidebar"), ("postTypes", ["post"])),
        ]),
        ("templateParts", [
            od(("name", "header"), ("title", "Header"), ("area", "header")),
            od(("name", "footer"), ("title", "Footer"), ("area", "footer")),
            od(("name", "sidebar"), ("title", "Sidebar"), ("area", "uncategorized")),
        ]),
    )


def build_color_variation(slug, name, colors):
    return od(
        ("$schema", "https://schemas.wp.org/trunk/theme.json"),
        ("version", 3), ("title", name),
        ("settings", od(("color", od(("palette", palette(colors)))))),
    )


def build_type_variation(slug, name, heading, body, weight):
    typography = od(("fontFamily", ff(body)))
    if weight:
        # A light weight is Poppins' character; in a system face it just reads thin.
        typography["fontWeight"] = weight
    return od(
        ("$schema", "https://schemas.wp.org/trunk/theme.json"),
        ("version", 3), ("title", name),
        ("styles", od(
            ("typography", typography),
            ("elements", od(
                ("heading", od(("typography", od(("fontFamily", ff(heading)))))),
                ("button", od(("typography", od(("fontFamily", ff(body)))))),
            )),
            ("blocks", od(("core/site-title", od(("typography", od(("fontFamily", ff(heading)))))))),
        )),
    )


def write(path, data):
    os.makedirs(os.path.dirname(path) or ".", exist_ok=True)
    with open(path, "w", encoding="utf-8") as handle:
        json.dump(data, handle, indent="\t", ensure_ascii=False)
        handle.write("\n")
    return path


def _mix_with_white(hex_colour, percent):
    """CSS `color-mix(in srgb, <colour> <percent>%, white)`, per channel."""
    channels = [int(hex_colour[i:i + 2], 16) for i in (1, 3, 5)]
    share = percent / 100
    return "#" + "".join("%02x" % round(c * share + 255 * (1 - share)) for c in channels)


def _dark_scheme():
    """What assets/css/scheme.css does to the palette, read from the file itself.

    Read rather than restated: the percentages live in the CSS, and a second
    copy here would drift from it. It also refuses any colour slug the palette
    does not define — a colour-mix on an undefined variable makes the whole
    declaration invalid, `primary` stops resolving and every button renders as
    bare text, while every text check still passes.
    """
    css = open("assets/css/scheme.css", encoding="utf-8").read()
    defined = {slug for _, slug, _ in PALETTE}
    referenced = set(re.findall(r"var\(--wp--preset--color--([a-z0-9-]+)\)", css))
    unknown = sorted(referenced - defined)
    if unknown:
        raise SystemExit("scheme.css reads colour slugs the palette does not define: %s"
                         % ", ".join(unknown))

    def mix(slug):
        m = re.search(r"--wp--preset--color--%s:\s*color-mix\(in srgb,\s*"
                      r"var\(--wp--preset--color--([a-z0-9-]+)\)\s*(\d+)%%,\s*white\)"
                      % re.escape(slug), css)
        if not m:
            raise SystemExit("scheme.css: cannot read the dark-mode `%s` mix" % slug)
        return m.group(1), int(m.group(2))

    def fixed(slug):
        m = re.search(r"--wp--preset--color--%s:\s*(#[0-9a-fA-F]{6})\s*;" % re.escape(slug), css)
        if not m:
            raise SystemExit("scheme.css: cannot read the dark-mode `%s`" % slug)
        return m.group(1).lower()

    return {
        "primary": mix("primary"), "primary-deep": mix("primary-deep"), "primary-warm": mix("primary-warm"),
        "on-primary": fixed("on-primary"), "base": fixed("base"), "surface": fixed("surface"),
        "contrast": fixed("contrast"), "muted": fixed("muted"),
    }


def audit():
    problems = []

    print("  light      label/primary  /deep  /warm")
    for slug, (name, colors) in sorted(COLOR_SETS.items()):
        for fg, bg, need in CONTRAST_CHECKS:
            ratio = contrast_ratio(colors[fg], colors[bg])
            if ratio < need:
                problems.append("%s: %s on %s is %.2f, needs %.1f" % (name, fg, bg, ratio, need))
        print("  %-10s %13.2f  %5.2f  %5.2f" % (
            name, contrast_ratio(colors["on-primary"], colors["primary"]),
            contrast_ratio(colors["on-primary"], colors["primary-deep"]),
            contrast_ratio(colors["on-primary"], colors["primary-warm"])))

    dark = _dark_scheme()
    print("\n  dark mode, as scheme.css applies it: primary = %s %d%%, deep = %s %d%%, warm = %s %d%% (+ white)"
          % (dark["primary"] + dark["primary-deep"] + dark["primary-warm"]))
    print("  dark       text/base  text/surf  link-hover  label  label-hover  label-warm  boundary")
    for slug, (name, colors) in sorted(COLOR_SETS.items()):
        fill = _mix_with_white(colors[dark["primary"][0]], dark["primary"][1])
        deep = _mix_with_white(colors[dark["primary-deep"][0]], dark["primary-deep"][1])
        warm = _mix_with_white(colors[dark["primary-warm"][0]], dark["primary-warm"][1])
        measured = (
            ("primary text on base", contrast_ratio(fill, dark["base"]), 4.5),
            ("primary text on surface", contrast_ratio(fill, dark["surface"]), 4.5),
            ("hovered link on base", contrast_ratio(deep, dark["base"]), 4.5),
            ("button label on its fill", contrast_ratio(dark["on-primary"], fill), 4.5),
            ("button label on the hover fill", contrast_ratio(dark["on-primary"], deep), 4.5),
            ("button label on the warm end", contrast_ratio(dark["on-primary"], warm), 4.5),
            # WCAG 1.4.11. A button has to be visible as a button, not merely
            # carry a readable label.
            ("button fill against the page",
             min(contrast_ratio(fill, dark["base"]), contrast_ratio(fill, dark["surface"]),
                 contrast_ratio(warm, dark["base"])), 3.0),
        )
        for label, value, need in measured:
            if value < need:
                problems.append("%s (dark): %s is %.2f, needs %.1f" % (name, label, value, need))
        print("  %-10s %9.2f  %9.2f  %10.2f  %5.2f  %11.2f  %10.2f  %8.2f"
              % ((name,) + tuple(v for _, v, _ in measured)))
    for fg, bg in (("contrast", "base"), ("contrast", "surface"), ("muted", "base"), ("muted", "surface"),
                   ("base", "contrast")):
        ratio = contrast_ratio(dark[fg], dark[bg])
        if ratio < 4.5:
            problems.append("dark: %s on %s is %.2f" % (fg, bg, ratio))

    print("\n  for the record: the template's #f6214b is %.2f:1 on white, #f45622 %.2f:1 and #777 %.2f:1 —"
          % (contrast_ratio("#f6214b", "#ffffff"), contrast_ratio("#f45622", "#ffffff"),
             contrast_ratio("#777777", "#ffffff")))
    print("  the two brand colours ship as `accent` and `accent-warm`, for decoration, never as text or a label.")
    if problems:
        raise SystemExit("\nContrast failures:\n  " + "\n  ".join(problems))


def check_fonts():
    missing = [f for f in font_files() if not os.path.exists(os.path.join("assets/fonts", f))]
    if missing:
        print("\n  warning: fonts not downloaded yet (node .dev/build-fonts.mjs): %s" % ", ".join(missing))


def main():
    audit()
    written = [write("theme.json", build_theme())]
    for slug, (name, colors) in sorted(COLOR_SETS.items()):
        written.append(write("styles/colors/%s.json" % slug, build_color_variation(slug, name, colors)))
    for slug, (name, heading, body, weight) in sorted(TYPE_SETS.items()):
        written.append(write("styles/typography/%s.json" % slug,
                             build_type_variation(slug, name, heading, body, weight)))
    check_fonts()
    print("\n  %d files written" % len(written))
    for path in written:
        print("    " + path)


if __name__ == "__main__":
    main()
