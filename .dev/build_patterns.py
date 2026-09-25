#!/usr/bin/env python3
"""Generate Horseclub's patterns.

Run from the theme root:

    python3 .dev/build_patterns.py
    node .dev/normalize-blocks.mjs      # then let the editor re-serialise them
    node .dev/validate-blocks.mjs       # and refuse anything it calls invalid

Every pattern file is committed as generated. Edit this file, never
patterns/*.php.

The sections follow the Horse Club HTML template section by section: the
two-row header with the gradient squares, the full-screen hero, the video
introduction, the feature band, the two-photograph about block, four numbered
price cards, the booking band (testimonials slider beside the booking form),
the news pair, the edge-to-edge gallery and the three-column white footer.
The copy is one riding school's, start to finish.
"""

import json
import os
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from patternlib import (  # noqa: E402
    attrs, button, buttons, column, columns, cover, group, heading, image,
    paragraph, separator, shortcode, sp, spacer, theme_image,
)

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PATTERNS = os.path.join(ROOT, "patterns")
WRITTEN = []

SECTIONS = ["horseclub-sections"]
PAGES = ["horseclub-pages"]

PHONE = "01632 960 482"
PHONE_2 = "01632 960 517"
PHONE_HREF = "tel:+441632960482"
EMAIL = "info@horseclub.com"
VIDEO = "https://www.youtube.com/watch?v=zXRwZotweis"


def write(slug, title, content, categories=None, keywords=None,
          description=None, inserter=True, block_types=None):
    header = ["Title: " + title, "Slug: horseclub/" + slug]
    if categories:
        header.append("Categories: " + ", ".join(categories))
    if keywords:
        header.append("Keywords: " + ", ".join(keywords))
    if block_types:
        header.append("Block Types: " + ", ".join(block_types))
    if description:
        header.append("Description: " + description)
    if not inserter:
        header.append("Inserter: no")

    body = (
        "<?php\n/**\n * " + "\n * ".join(header) + "\n *\n * @package Horseclub\n */\n\n"
        "defined( 'ABSPATH' ) || exit;\n?>\n" + content.strip() + "\n"
    )
    with open(os.path.join(PATTERNS, slug + ".php"), "w") as fh:
        fh.write(body)
    WRITTEN.append(slug)


def home(path="/"):
    """A link to one of the starter pages, resolved when the pattern loads.

    A literal `/contact/` breaks on a site installed in a subdirectory; `#`
    goes nowhere. The pattern is PHP, so it can ask WordPress, and the link is
    correct in the page activation builds from it.
    """
    return "<?php echo esc_url( home_url( '%s' ) ); ?>" % path


def icon(name):
    """The class that draws a Tabler icon on the block that carries it.

    style.css draws the icon from the class with a mask filled in the text
    colour, so it follows the palette and dark mode, shows in the editor (an
    empty inline element would not), and changing it is an edit to Advanced →
    Additional CSS class(es). Every name needs a `.horseclub-icon--<name>` rule;
    .dev/dead-selectors.py checks it."""
    return "horseclub-icon--%s" % name


def flex_row(inner, justify=None, gap=None, wrap="wrap", vertical="center", extra_class=None):
    """A horizontal group, written directly; normalize-blocks.mjs canonicalises it."""
    layout = {"type": "flex", "flexWrap": wrap}
    if justify:
        layout["justifyContent"] = justify
    if vertical:
        layout["verticalAlignment"] = vertical
    data = {}
    if extra_class:
        data["className"] = extra_class
    if gap is not None:
        data["style"] = {"spacing": {"blockGap": sp(gap) if gap != "0" else "0"}}
    data["layout"] = layout
    cls = "wp-block-group" + (" " + extra_class if extra_class else "")
    return '<!-- wp:group %s -->\n<div class="%s">\n%s\n</div>\n<!-- /wp:group -->' % (
        json.dumps(data, separators=(",", ":")), cls, inner
    )


def eyebrow(text, align=None, color="primary"):
    """The small spaced capitals above a heading, as the template sets them."""
    return paragraph(text, align=align, color=color, style="horseclub-eyebrow")


def lede(text, color=None):
    """The template's bold one-liner between a heading and its copy."""
    return paragraph(text, color=color, extra_class="horseclub-lede")


def section_head(title, blurb=None, color=None, blurb_color=None):
    """A centred section title and one line beneath it, 70px above the content."""
    parts = [heading(title, level=2, align="center", color=color)]
    if blurb:
        parts.append(paragraph(blurb, align="center", color=blurb_color))
    return group("\n".join(parts), layout="constrained", content_size="730px", gap="20",
                 extra_class="horseclub-section-head")


def social_links(extra_style="horseclub-squares", justify="right"):
    services = ["facebook", "x", "instagram", "youtube"]
    data = {"size": "has-small-icon-size", "className": "is-style-" + extra_style,
            "layout": {"type": "flex", "justifyContent": justify, "flexWrap": "wrap"}}
    return (
        '<!-- wp:social-links %s -->\n'
        '<ul class="wp-block-social-links has-small-icon-size is-style-%s">%s</ul>\n'
        '<!-- /wp:social-links -->' % (
            json.dumps(data, separators=(",", ":")), extra_style,
            "".join('<!-- wp:social-link {"url":"#","service":"%s"} /-->' % s for s in services))
    )


def lines_list(items):
    return ('<!-- wp:list {"className":"is-style-horseclub-lines"} -->\n'
            '<ul class="wp-block-list is-style-horseclub-lines">%s</ul>\n<!-- /wp:list -->'
            % "".join("<!-- wp:list-item -->\n<li>%s</li>\n<!-- /wp:list-item -->" % i for i in items))


def section(inner, background=None, padding="80", anchor=None, extra_class=None):
    return group(inner, align="full", background=background, padding_y=padding,
                 layout="constrained", anchor=anchor, extra_class=extra_class)


# ---------------------------------------------------------------------------
# Parts
# ---------------------------------------------------------------------------
def build_header():
    # The switch needs text inside it: an empty core/button renders nothing at
    # all. The label is for screen readers; inc/scheme.php adds the pressed state.
    toggle = buttons([button('<span class="screen-reader-text">Switch between light and dark mode</span>', "#",
                             extra_class="horseclub-scheme-toggle")])

    # The two gradient squares are empty paragraphs: the icon is drawn from the
    # class and the gradient from style.css, so both follow the palette.
    square = lambda name: paragraph("", extra_class="horseclub-header__square " + icon(name), placeholder=" ")
    start = flex_row("\n".join([
        square("send"),
        paragraph('<a href="mailto:%s">%s</a>' % (EMAIL, EMAIL), color="contrast", size="small",
                  extra_class="horseclub-header__contact"),
    ]), gap="30", wrap="nowrap", extra_class="horseclub-header__side")
    brand = flex_row('<!-- wp:site-logo {"width":176} /-->\n<!-- wp:site-title {"level":0} /-->',
                     gap="30", wrap="nowrap", justify="center", extra_class="horseclub-header__brand")
    end = flex_row("\n".join([
        paragraph('<a href="%s">%s</a>' % (PHONE_HREF, PHONE), color="contrast", size="small",
                  extra_class="horseclub-header__contact"),
        square("phone"),
    ]), gap="30", wrap="nowrap", justify="right", extra_class="horseclub-header__side horseclub-header__side--end")

    top = flex_row("\n".join([start, brand, end]), justify="space-between", gap="30", wrap="nowrap",
                   extra_class="horseclub-header__top")
    rule = ('<!-- wp:separator {"align":"full","className":"horseclub-header__rule"} -->\n'
            '<hr class="wp-block-separator alignfull has-alpha-channel-opacity horseclub-header__rule"/>\n'
            '<!-- /wp:separator -->')
    bar = flex_row("\n".join([
        '<!-- wp:navigation {"overlayMenu":"mobile","layout":{"type":"flex","justifyContent":"center","flexWrap":"wrap"}} /-->',
        toggle,
    ]), justify="center", gap="30", wrap="nowrap", extra_class="horseclub-header__bar")

    write("header", "Header",
          group("\n".join([top, rule, bar]), align="full", background="base", layout="constrained",
                extra_class="horseclub-header"),
          keywords=["header", "navigation"],
          description="The template's two-row header: email and phone in gradient squares either side of the logo, "
                      "then the navigation centred beneath a rule.",
          block_types=["core/template-part/header"])


def build_footer():
    col_about = column("\n".join([
        heading("About us", level=2, size="large", extra_class="horseclub-footer__title"),
        paragraph("Horse Club is a family-run riding school and livery yard in the Oxfordshire "
                  "countryside, teaching riders of every age since 1987."),
    ]), width="25%")
    col_contact = column("\n".join([
        heading("Contact us", level=2, size="large", extra_class="horseclub-footer__title"),
        paragraph("Linden Farm, Burford Road, Oxfordshire. The yard is open Tuesday to Sunday, "
                  "from 8am until dusk."),
        paragraph('<a href="%s">%s</a><br><a href="tel:+441632960517">%s</a>' % (PHONE_HREF, PHONE, PHONE_2),
                  extra_class="horseclub-footer__numbers"),
    ]), width="33.33%")
    col_news = column("\n".join([
        heading("Newsletter", level=2, size="large", extra_class="horseclub-footer__title"),
        paragraph("Show dates, clinic places and news from the yard, once a month. Never anything else."),
        shortcode('[horseclub_form type="newsletter"]'),
    ]), width="41.66%")
    body = columns([col_about, col_contact, col_news], gap="60")
    legal = flex_row("\n".join([
        paragraph('&copy; Horse Club. All rights reserved. Theme by <a href="https://colorlib.com/" rel="nofollow">Colorlib</a>.',
                  size="small", extra_class="horseclub-footer__legal"),
        social_links(),
    ]), justify="space-between", gap="40", extra_class="horseclub-footer__bottom")
    write("footer", "Footer",
          group(body + "\n" + legal, align="full", background="base", padding_y="80", layout="constrained",
                extra_class="horseclub-footer"),
          keywords=["footer", "newsletter"],
          description="Three columns on white — about, contact numbers and a newsletter sign-up — with the "
                      "copyright line and social squares beneath.",
          block_types=["core/template-part/footer"])


def box(title, inner):
    """One of the sidebar's bordered boxes."""
    parts = []
    if title:
        parts.append(heading(title, level=2, size="large"))
    parts.append(inner)
    return group("\n".join(parts), layout="constrained", gap="40", style="horseclub-box")


def build_sidebar():
    inner = "\n".join([
        box(None, '<!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search posts","buttonText":"Search","buttonPosition":"button-inside","buttonUseIcon":true} /-->'),
        box("Post categories", '<!-- wp:categories {"showPostCounts":true,"className":"horseclub-counts"} /-->'),
        box("Recent posts", '<!-- wp:latest-posts {"postsToShow":4,"displayPostDate":true,"displayFeaturedImage":true,"featuredImageSizeSlug":"thumbnail","featuredImageAlign":"left","featuredImageSizeWidth":75,"featuredImageSizeHeight":75,"className":"horseclub-recent"} /-->'),
        box("Post archive", '<!-- wp:archives {"showPostCounts":true,"className":"horseclub-counts"} /-->'),
        box("Tag cloud", '<!-- wp:tag-cloud {"smallestFontSize":"0.875rem","largestFontSize":"0.875rem","className":"horseclub-tags"} /-->'),
    ])
    write("sidebar", "Sidebar", group(inner, layout="constrained", gap="50", extra_class="horseclub-sidebar"),
          keywords=["sidebar"], inserter=False,
          description="Search, categories, recent posts, archive and tags, each in a bordered box.")


# ---------------------------------------------------------------------------
# Sections
# ---------------------------------------------------------------------------
# hero.webp is 3:2 (1920x1280), not the template's 1920x780 strip, and the
# rider and her horse stand in its top-right corner. The cover pins that corner:
# on a desktop the photograph is scaled to the width, so the two faces sit in
# the right third beside the centred headline; on a phone it is scaled to the
# height and the right-hand edge is kept, which puts both faces above the
# eyebrow. Checked at 1440, 1024 and 390.
HERO_FOCAL = (1, 0)


def build_hero():
    inner = "\n".join([
        eyebrow("Introducing Horse Club", align="center", color="overlay"),
        separator("horseclub-bar"),
        heading("The Bond Between <br>Horse &amp; Rider", level=1, align="center", color="overlay",
                size="colossal", extra_class="horseclub-hero__title"),
        buttons([button("Book a lesson", home("/contact/"))], align="center"),
    ])
    # Dimmed 70, the template's own rgba(0,0,0,.7). The height is the screen
    # less the header, set in style.css: the template's hero fills the window.
    write("hero", "Hero",
          cover(group(inner, layout="constrained", gap="30"), "hero", dim=70, min_height=88,
                min_height_unit="vh", extra_class="horseclub-hero horseclub-hero--fill", focal=HERO_FOCAL),
          categories=SECTIONS, keywords=["hero", "banner"],
          description="Full-screen photograph with a spaced eyebrow, an accent rule, the headline and a button.")


def build_intro_video():
    text = "\n".join([
        eyebrow("Riding school &amp; livery yard"),
        heading("A yard built <br>around the horse", level=2),
        lede("Forty horses, eleven instructors and one standard of care."),
        paragraph("We teach complete beginners and affiliated competitors on horses that are fit, happy "
                  "and schooled for the job. Visitors are welcome at the yard on any day we are open."),
        buttons([button("Plan a visit", home("/contact/"), style="horseclub-dark")]),
    ])
    # horseclub-video: assets/js/interactions.js plays the film in a popup.
    # Without JavaScript it is an ordinary link to the video.
    play = buttons([button('<span class="screen-reader-text">Play the film: a riding school at work</span>', VIDEO,
                           extra_class="horseclub-video horseclub-play")], align="center")
    film = cover(play, "beach", dim=30, min_height=330, align=None, extra_class="horseclub-film")
    write("intro-video", "Introduction with video",
          section(columns([
              column(group(text, layout="constrained", gap="30"), vertical="center"),
              column(film, vertical="center"),
          ], gap="60", vertical="center"), extra_class="horseclub-intro"),
          categories=SECTIONS, keywords=["about", "video", "intro"],
          description="An introduction beside a photograph with a play button that opens the film.")


def build_features():
    items = [
        ("Qualified instructors", "Every lesson is taught by a BHS-accredited coach, and every coach still rides here each week."),
        ("A horse for every rider", "Forty school horses and ponies, from steady first-lesson cobs to seasoned jumpers."),
        ("Two all-weather arenas", "An indoor school for winter evenings and a floodlit outdoor arena with a full course of fences."),
        ("Full &amp; part livery", "Your horse stabled, turned out and exercised by people who notice when something is off."),
        ("Three hundred acres to hack", "Bridleways, open stubble in the autumn and a long gallop along the top of the valley."),
        ("Pony Club &amp; juniors", "Saturday rallies, holiday camps and badges for young riders from six years old."),
    ]

    def item(title, text):
        return column(group("\n".join([
            heading(title, level=3, color="overlay", size="large"),
            paragraph(text, color="on-dark"),
        ]), layout="constrained", gap="30", extra_class="horseclub-feature"))

    rows = "\n".join(columns([item(*i) for i in items[n:n + 3]], gap="60") for n in (0, 3))
    write("features", "Features on a photograph",
          cover(group(rows, layout="constrained", gap="60"), "stable", dim=75, min_height=None,
                extra_class="horseclub-features", padding_y="80"),
          categories=SECTIONS, keywords=["features", "why us"],
          description="Six reasons to ride here, three to a row, over a darkened stable photograph.")


def build_about_club():
    photos = columns([
        column(image("about-1", "A rider in a helmet on a chestnut horse in a sand arena, her instructor walking behind",
                     ratio="263/400")),
        column(image("about-2", "A woman in a blue dress with a garland of flowers, holding the bridle of a grey horse",
                     ratio="263/400"), extra_class="horseclub-about-photos__low"),
    ], gap="40", stack_on_mobile=False, extra_class="horseclub-about-photos")
    text = "\n".join([
        eyebrow("About the club"),
        heading("Riding for every age <br>and every ability", level=2),
        lede("Founded by a family of riders in 1987, and still run by one."),
        paragraph("Horse Club began with six ponies and a borrowed field. Today it is an approved riding "
                  "school and livery yard on three hundred acres, with an indoor school, a cross-country "
                  "course and a clubhouse where parents can watch lessons over a coffee. What has not "
                  "changed is the order of things: the horses come first, the riders a close second, and "
                  "everyone goes home knowing a little more than when they arrived."),
        buttons([button("Meet the team", home("/about/"), style="horseclub-dark")]),
    ])
    write("about-club", "About the club: two photographs",
          section(columns([
              column(photos, width="50%"),
              column(group(text, layout="constrained", gap="30"), width="50%", vertical="center",
                     extra_class="horseclub-about-text"),
          ], gap="40", vertical="center"), extra_class="horseclub-about"),
          categories=SECTIONS, keywords=["about", "story"],
          description="Two photographs set at different heights beside the club's story and a button.")


def price_card(number, name, who, items, amount, link_text="Choose plan"):
    inner = "\n".join([
        paragraph(number, align="center", extra_class="horseclub-price__no"),
        heading(name, level=3, align="center", size="large"),
        paragraph(who, align="center", extra_class="horseclub-price__who"),
        lines_list(items),
        paragraph(amount, align="center", extra_class="horseclub-price__amount"),
        paragraph('<a href="%s">%s</a>' % (home("/contact/"), link_text), align="center",
                  extra_class="horseclub-price__link"),
    ])
    return column(group(inner, layout="constrained", gap="20", style="horseclub-price"))


def build_pricing():
    cards = [
        price_card("01", "Taster", "For first-time riders",
                   ["A private half-hour lesson", "A tour of the yard", "Hat and boots on loan"], "&pound;45.00"),
        price_card("02", "Rider", "For regular riders",
                   ["Four group lessons a month", "Priority on clinic places", "Members' hacks at weekends"], "&pound;149.00"),
        price_card("03", "Family", "For two riders or more",
                   ["Eight group lessons a month", "Pony Club rallies included", "10% off holiday camps"], "&pound;259.00"),
        price_card("04", "Livery", "For horse owners",
                   ["Stable, turnout and hay", "Daily checks and rugs", "Arena and hacking access"], "&pound;495.00"),
    ]
    inner = section_head("Choose the plan that suits you",
                         "Every plan includes a hat and boots on loan, arena time and the clubhouse.")
    write("pricing", "Pricing: four numbered plans",
          section(inner + "\n" + columns(cards, gap="40", extra_class="horseclub-prices"),
                  background="surface", anchor="pricing"),
          categories=SECTIONS, keywords=["pricing", "plans", "membership"],
          description="Four plans, each with a numbered gradient badge, a ruled feature list and a price.")


QUOTES = [
    ("My daughter was nervous of horses a year ago. Now she tacks up her favourite pony on her own "
     "and talks about nothing else on the drive home.", "Fannie Rowe", "Parent of a Pony Club member", 5),
    ("I came back to riding at forty-six after twenty years off. Nobody made me feel slow, and my "
     "instructor knew exactly when to push.", "Hulda Sutton", "Returning rider", 5),
    ("Clean stables, sensible turnout and a team that rings you before the vet does. I have kept horses "
     "at five yards, and this is the one I stayed at.", "Marcus Hale", "Livery client", 5),
    ("The jumping clinics are the best value in the county: small groups, proper grids, and real time "
     "on the course.", "Priya Nair", "Amateur show jumper", 4),
    ("We booked a taster lesson for my birthday and signed up for the Rider plan the same afternoon.",
     "Tom Reilly", "Adult beginner", 5),
    ("Four days outdoors, a rosette and more confidence than a year of after-school clubs.",
     "Grace Okafor", "Parent, summer camp", 5),
]


def quote(text, name, role, stars):
    return group("\n".join([
        flex_row("\n".join([
            paragraph(name, color="overlay", size="large", extra_class="horseclub-quote__name"),
            paragraph('<span class="screen-reader-text">Rated %d out of 5</span>' % stars,
                      extra_class="horseclub-stars horseclub-stars--%d" % stars),
        ]), gap="40", wrap="wrap"),
        paragraph(text, color="on-dark"),
        paragraph(role, color="on-dark", size="small", extra_class="horseclub-quote__role"),
    ]), layout="constrained", gap="20", extra_class="horseclub-quote")


def build_booking():
    # Three slides of two quotes, as the template's carousel. The slider is
    # assets/js/interactions.js; without it the slides scroll sideways.
    slides = "\n".join(
        group("\n".join(quote(*q) for q in QUOTES[i:i + 2]), layout="constrained", gap="50",
              extra_class="horseclub-slide")
        for i in (0, 2, 4))
    slider = group(slides, layout="default", extra_class="horseclub-slider")
    form = group("\n".join([
        heading("Book a lesson", level=2, size="large", color="overlay"),
        shortcode('[horseclub_form type="booking" layout="compact" button="Request a booking"]'),
    ]), layout="constrained", gap="30", extra_class="horseclub-booking__box")
    inner = columns([
        column(slider, width="55%", vertical="center"),
        column(form, width="35%", vertical="center"),
    ], gap="70", vertical="center", extra_class="horseclub-booking__row")
    write("booking", "Booking band: testimonials and form",
          cover(inner, "riders", dim=80, min_height=None, extra_class="horseclub-booking",
                padding_y="80", anchor="book"),
          categories=SECTIONS, keywords=["booking", "testimonials", "form", "reviews"],
          description="Riders' reviews in a slider beside the lesson booking form, over a darkened photograph.")


def build_news():
    query = (
        '<!-- wp:query {"queryId":2,"query":{"perPage":2,"pages":0,"offset":0,"postType":"post",'
        '"order":"desc","orderBy":"date","inherit":false},"className":"horseclub-news","layout":{"type":"default"}} -->\n'
        '<div class="wp-block-query horseclub-news"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"grid","columnCount":2}} -->\n'
        '<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"555/320","style":{"border":{"radius":"5px"}}} /-->\n'
        '<!-- wp:post-terms {"term":"category","className":"horseclub-chips"} /-->\n'
        '<!-- wp:post-title {"isLink":true,"level":3,"fontSize":"large"} /-->\n'
        '<!-- wp:post-excerpt {"excerptLength":24} /-->\n'
        '<!-- wp:post-date {"textColor":"contrast"} /-->\n'
        '<!-- /wp:post-template -->\n'
        '<!-- wp:query-no-results -->\n'
        + paragraph("News from the yard will appear here as soon as the first post is published.",
                    align="center") + '\n'
        '<!-- /wp:query-no-results --></div>\n'
        '<!-- /wp:query -->'
    )
    inner = section_head("Latest news from the yard",
                         "Show results, new arrivals and what is happening at the club this month.")
    write("latest-news", "Latest news: two posts",
          section(inner + "\n" + query),
          categories=SECTIONS, keywords=["blog", "news", "posts"],
          description="The two most recent posts, with category chips, excerpt and date.")


def build_gallery():
    shots = [
        ("gallery-1", "A chestnut horse with a white blaze and a leather headcollar, looking out of a wooden stable"),
        ("gallery-2", "A rider in a helmet swinging into the saddle of a chestnut horse while an instructor holds it"),
        ("gallery-3", "A bridled chestnut horse grazing among fallen autumn leaves"),
        ("gallery-4", "A bay horse standing in a sand arena, head turned towards the camera"),
        ("gallery-5", "A woman in a blue jacket coiling a lead rope beside two chestnut horses with white blazes"),
        ("gallery-6", "A chestnut horse with a flaxen mane resting its head over a white paddock fence"),
    ]
    items = "\n".join(
        '<!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"large","linkDestination":"none"} -->\n'
        '<figure class="wp-block-image size-large"><img src="%s" alt="%s"/></figure>\n'
        '<!-- /wp:image -->' % (theme_image(s), alt) for s, alt in shots)
    data = {"columns": 6, "imageCrop": True, "linkTo": "none", "sizeSlug": "large", "align": "full",
            "className": "horseclub-gallery",
            "style": {"spacing": {"blockGap": {"top": "0", "left": "0"}}}}
    gallery = ('<!-- wp:gallery %s -->\n'
               '<figure class="wp-block-gallery alignfull has-nested-images columns-6 is-cropped horseclub-gallery">\n'
               '%s\n</figure>\n<!-- /wp:gallery -->' % (json.dumps(data, separators=(",", ":")), items))
    write("gallery", "Gallery: six photographs edge to edge", gallery,
          categories=SECTIONS, keywords=["gallery", "photos"],
          description="Six photographs across the full width, no gaps, enlarging on click.")


def card(slug, alt, title, text, badge=None, price=None):
    """A photograph over a pale panel: the template's service and training card."""
    media = image(slug, alt, ratio="360/212")
    if badge:
        media = group(media + "\n" + paragraph(badge, extra_class="horseclub-badge"),
                      layout="default", extra_class="horseclub-card__media")
    if price:
        head = flex_row("\n".join([
            heading(title, level=3, size="large"),
            paragraph(price, extra_class="horseclub-card__price"),
        ]), justify="space-between", gap="30", wrap="nowrap", vertical="top", extra_class="horseclub-card__head")
    else:
        head = heading(title, level=3, size="large")
    body = group(head + "\n" + paragraph(text), layout="constrained", gap="30", background="surface",
                 extra_class="horseclub-card__body" + (" horseclub-card__body--tight" if price else ""))
    return column(group(media + "\n" + body, layout="default", style="horseclub-card"))


SERVICES = [
    ("card-1", "A chestnut horse with a white blaze and a plaited bridle, cattle blurred behind it",
     "Group lessons", "Four riders to an instructor, grouped by ability, in the indoor school or out on the arena."),
    ("card-2", "An instructor steadying a chestnut horse as a rider in a helmet settles into the saddle, among pine trees",
     "Private lessons", "Thirty or sixty minutes of one-to-one coaching, for nerves, a new horse or a competition goal."),
    ("card-3", "A bridled chestnut horse grazing among fallen autumn leaves",
     "Hacking out", "Escorted rides over three hundred acres of farmland and bridleway, from a gentle walk to a canter."),
    ("card-4", "A woman in a blue dress holding the bridle of a grey horse on a woodland path",
     "Horse care courses", "Grooming, tacking up, feeding and first aid: the half of horsemanship that happens on the ground."),
    ("card-5", "A chestnut horse in a halter against the weathered boards of a stable",
     "Full &amp; part livery", "Stabling, turnout and exercise, with the same team checking your horse morning and night."),
    ("card-6", "A bay horse with a white blaze in a halter, in a dark wooden stable",
     "Schooling &amp; starting", "Young and green horses brought on patiently by our senior rider, from first saddle to first show."),
]


def build_services():
    rows = "\n".join(columns([card(*s) for s in SERVICES[i:i + 3]], gap="40") for i in (0, 3))
    inner = section_head("Everything for horse and rider",
                         "Six ways to ride, learn and keep a horse at Horse Club.")
    write("services", "Services: six photograph cards",
          section(inner + "\n" + group(rows, layout="constrained", gap="40"), anchor="services"),
          categories=SECTIONS, keywords=["services", "cards", "lessons"],
          description="Six services, each a photograph over a pale panel with a title and a line of copy.")


TRAINING = [
    ("card-3", SERVICES[2][1], "Beginner rider course", "&pound;240",
     "Six weekly lessons from first mount to rising trot, with a horse care session in week four."),
    ("card-2", SERVICES[1][1], "Jumping clinic", "&pound;85",
     "Three hours of grids and courses in groups of four, for riders jumping 70cm and up."),
    ("card-5", SERVICES[4][1], "Dressage foundations", "&pound;260",
     "Eight lessons on rhythm, contact and accuracy, finishing with a ridden test and a scoresheet."),
    ("card-6", SERVICES[5][1], "Stable management", "&pound;150",
     "Five evening sessions on feeding, rugs, shoeing and spotting lameness, with a certificate."),
    ("card-4", SERVICES[3][1], "Pony Club camp", "&pound;320",
     "Four days of riding, games and horse care for ages six to fourteen, every school holiday."),
    ("card-1", SERVICES[0][1], "Instructor pathway", "&pound;650",
     "Coaching and exam preparation for the Stage 2 and 3 exams, with teaching practice in our school."),
]


def build_training():
    cards = [card(slug, alt, title, text, badge="Enrolling now", price=price)
             for slug, alt, title, price, text in TRAINING]
    rows = "\n".join(columns(cards[i:i + 3], gap="40") for i in (0, 3))
    inner = section_head("Courses and training programmes",
                         "Structured training with a start date, a plan and a goal at the end of it.")
    write("training", "Training: six programme cards",
          section(inner + "\n" + group(rows, layout="constrained", gap="40"), anchor="training"),
          categories=SECTIONS, keywords=["training", "courses", "programmes"],
          description="Six courses, each with an enrolment badge on the photograph, a title, a price and a line of copy.")


EVENTS = [
    ("event-1", "A chestnut horse with a white blaze, cattle blurred in the field behind",
     "Spring show jumping league", "12th April", "at the outdoor arena",
     "Four rounds across the spring, from 60cm clear-round to the 1m open, with a league table and rosettes to sixth."),
    ("event-2", "A smiling woman holding out a carrot to a chestnut horse at the edge of a wood",
     "Open day at the yard", "3rd May", "at Linden Farm",
     "Meet the horses, try a free ten-minute pony ride and watch a demonstration from our instructors."),
    ("event-3", "A chestnut horse in a halter against the weathered boards of a stable",
     "Dressage clinic", "24th May", "in the indoor school",
     "A day of private sessions with a visiting judge, with written feedback for every rider who takes part."),
    ("event-4", "A woman in a blue dress beside a grey horse on a woodland path",
     "Summer Pony Club rally", "21st June", "on the lower fields",
     "Gymkhana games, a fancy dress class and the handy pony competition. The tea tent is open all afternoon."),
]


def build_events():
    rows = []
    for n, (slug, alt, title, date, place, text) in enumerate(EVENTS):
        photo = column(image(slug, alt, ratio="555/180", rounded="8px"), vertical="center",
                       extra_class="horseclub-event__photo")
        words = column(group("\n".join([
            heading(title, level=3, size="large"),
            paragraph("<strong>%s</strong> %s" % (date, place), extra_class="horseclub-event__meta"),
            paragraph(text),
            buttons([button("View details", home("/contact/"), extra_class="horseclub-caps")]),
        ]), layout="constrained", gap="20"), vertical="center")
        pair = [photo, words] if n % 2 == 0 else [words, photo]
        rows.append(columns(pair, gap="40", vertical="center", extra_class="horseclub-event"))
    inner = section_head("Upcoming events at the club",
                         "Everyone is welcome to watch. Entries close a week before each event.")
    write("events", "Events: four alternating rows",
          section(inner + "\n" + group("\n".join(rows), layout="constrained", gap="50"), anchor="events"),
          categories=SECTIONS, keywords=["events", "shows", "calendar"],
          description="Four events, photograph and details alternating sides, each with a date and a button.")


def build_contact():
    def item(name, first, second):
        return paragraph("<strong>%s</strong><br>%s" % (first, second), extra_class="horseclub-contact-item " + icon(name))

    details = group("\n".join([
        item("home", "Linden Farm, Burford Road", "Oxfordshire, United Kingdom"),
        item("phone", '<a href="%s">%s</a>' % (PHONE_HREF, PHONE), "Tuesday to Sunday, 8am to 7pm"),
        item("mail", '<a href="mailto:%s">%s</a>' % (EMAIL, EMAIL), "Send us your questions any time"),
    ]), layout="constrained", gap="40")
    form = shortcode('[horseclub_form type="contact" layout="split" button="Send message"]')
    map_block = (
        '<!-- wp:html -->\n'
        '<iframe class="horseclub-map" title="Map of the countryside around the yard" loading="lazy" '
        'src="https://www.openstreetmap.org/export/embed.html?bbox=-1.7100%2C51.7800%2C-1.5900%2C51.8300&amp;layer=mapnik" '
        'style="width:100%;height:445px;border:0"></iframe>\n'
        '<!-- /wp:html -->'
    )
    inner = map_block + "\n" + spacer("70") + "\n" + columns(
        [column(details, width="33.33%"), column(form, width="66.66%")], gap="60")
    write("contact", "Contact: map, details and form",
          section(inner, background="surface", anchor="contact", extra_class="horseclub-contact"),
          categories=SECTIONS, keywords=["contact", "form", "map"],
          description="A map across the width, then the address, phone and email beside the message form.")


# ---------------------------------------------------------------------------
# Hidden patterns: the pieces templates are built from.
# ---------------------------------------------------------------------------
def breadcrumb(current):
    """Home → current, as the template's banner has it."""
    return flex_row("\n".join([
        paragraph('<a href="%s">Home</a>' % home("/"), color="overlay", extra_class="horseclub-breadcrumb__home"),
        current,
    ]), justify="center", gap="30", wrap="wrap", extra_class="horseclub-breadcrumb")


def banner(slug, title, description, title_block, current):
    inner = "\n".join([title_block, breadcrumb(current)])
    write(slug, title, cover(group(inner, layout="constrained", gap="20"), "hero", dim=70, min_height=346,
                             extra_class="horseclub-banner", focal=HERO_FOCAL),
          inserter=False, description=description)


def build_hidden():
    here = lambda text: paragraph(text, color="overlay")
    banner("hidden-page-banner", "Page banner", "The photograph banner a page title sits on.",
           '<!-- wp:post-title {"level":1,"textAlign":"center","textColor":"overlay"} /-->',
           '<!-- wp:post-title {"level":0,"textColor":"overlay"} /-->')
    banner("hidden-single-banner", "Post banner", "The photograph banner a post title sits on.",
           '<!-- wp:post-title {"level":1,"textAlign":"center","textColor":"overlay"} /-->',
           here("Blog"))
    banner("hidden-blog-banner", "Blog banner", "The heading for the posts page.",
           heading("Blog", level=1, align="center", color="overlay"), here("Blog"))
    banner("hidden-archive-banner", "Archive banner", "The banner an archive title sits on.",
           '<!-- wp:query-title {"type":"archive","textAlign":"center","textColor":"overlay"} /-->',
           here("Archive"))
    banner("hidden-search-banner", "Search banner", "The banner search results sit under.",
           '<!-- wp:query-title {"type":"search","textAlign":"center","textColor":"overlay"} /-->',
           here("Search"))
    banner("hidden-404-banner", "Not found banner", "The banner of the page that is not there.",
           heading("Page not found", level=1, align="center", color="overlay"), here("404"))

    posts = (
        '<!-- wp:query {"queryId":0,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post",'
        '"order":"desc","orderBy":"date","inherit":true},"className":"horseclub-posts","layout":{"type":"default"}} -->\n'
        '<div class="wp-block-query horseclub-posts"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|70"}}} -->\n'
        '<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"750/350"} /-->\n'
        '<!-- wp:post-terms {"term":"category","textColor":"contrast","fontSize":"small"} /-->\n'
        '<!-- wp:post-title {"isLink":true,"level":2} /-->\n'
        '<!-- wp:post-excerpt {"excerptLength":45} /-->\n'
        + flex_row("\n".join([
            '<!-- wp:post-date {"fontSize":"small"} /-->',
            '<!-- wp:post-comments-count {"fontSize":"small","className":"horseclub-comments-count"} /-->',
        ]), gap="40", extra_class="horseclub-post__meta") + '\n'
        '<!-- /wp:post-template -->\n'
        '<!-- wp:query-no-results -->\n'
        + paragraph("Nothing here yet. Try a search, or start again from the home page.") + '\n'
        '<!-- /wp:query-no-results -->\n'
        '<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"left"}} -->\n'
        '<!-- wp:query-pagination-previous /-->\n'
        '<!-- wp:query-pagination-numbers /-->\n'
        '<!-- wp:query-pagination-next /-->\n'
        '<!-- /wp:query-pagination --></div>\n'
        '<!-- /wp:query -->'
    )
    write("hidden-posts-list", "Posts list", posts, inserter=False,
          description="The post list used by the blog and every archive: photograph, categories, title, excerpt.")

    meta = flex_row("\n".join([
        '<!-- wp:post-terms {"term":"category","textColor":"contrast","fontSize":"small"} /-->',
        '<!-- wp:post-date {"fontSize":"small"} /-->',
        '<!-- wp:post-author-name {"fontSize":"small","className":"horseclub-author"} /-->',
    ]), gap="40", extra_class="horseclub-post__meta")
    write("hidden-post-meta", "Post meta", meta, inserter=False,
          description="Categories, date and author for a single post.")

    comments = (
        '<!-- wp:comments {"className":"horseclub-comments"} -->\n'
        '<div class="wp-block-comments horseclub-comments">\n'
        '<!-- wp:comments-title {"level":2,"fontSize":"medium"} /-->\n'
        '<!-- wp:comment-template -->\n'
        '<!-- wp:columns {"isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"}}}} -->\n'
        '<div class="wp-block-columns is-not-stacked-on-mobile">'
        '<!-- wp:column {"width":"60px"} -->\n<div class="wp-block-column" style="flex-basis:60px">'
        '<!-- wp:avatar {"size":60} /--></div>\n<!-- /wp:column -->\n'
        '<!-- wp:column -->\n<div class="wp-block-column">'
        '<!-- wp:comment-author-name {"fontSize":"medium"} /-->\n'
        '<!-- wp:comment-date {"fontSize":"small"} /-->\n'
        '<!-- wp:comment-content /-->\n'
        '<!-- wp:comment-reply-link {"className":"horseclub-reply","fontSize":"x-small"} /-->'
        '</div>\n<!-- /wp:column --></div>\n'
        '<!-- /wp:columns -->\n'
        '<!-- /wp:comment-template -->\n'
        '<!-- wp:comments-pagination {"layout":{"type":"flex","justifyContent":"left"}} -->\n'
        '<!-- wp:comments-pagination-previous /-->\n'
        '<!-- wp:comments-pagination-numbers /-->\n'
        '<!-- wp:comments-pagination-next /-->\n'
        '<!-- /wp:comments-pagination -->\n'
        '<!-- wp:post-comments-form /-->\n'
        '</div>\n'
        '<!-- /wp:comments -->'
    )
    write("hidden-comments", "Comments", comments, inserter=False,
          description="The comments and the reply form for a single post.")

    notfound = "\n".join([
        heading("That page has wandered off", level=2, align="center"),
        paragraph("The address may be old, or the page may have been renamed. "
                  "Try a search, or start again from the home page.", align="center"),
        '<!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search the site","buttonText":"Search","align":"center"} /-->',
        buttons([button("Back to the home page", home("/"))], align="center"),
    ])
    write("hidden-404", "404 content",
          section(group(notfound, layout="constrained", content_size="620px", gap="40")),
          inserter=False, description="What a visitor sees when nothing is there.")


# ---------------------------------------------------------------------------
# Whole pages
# ---------------------------------------------------------------------------
def ref(slug):
    return '<!-- wp:pattern {"slug":"horseclub/%s"} /-->' % slug


def build_pages():
    pages = {
        "page-home": ("Page: home", ["hero", "intro-video", "features", "about-club", "pricing",
                                     "booking", "latest-news", "gallery"]),
        "page-about": ("Page: about", ["about-club", "features", "intro-video", "booking"]),
        "page-services": ("Page: services", ["services", "booking"]),
        "page-training": ("Page: training", ["training", "booking"]),
        "page-events": ("Page: events", ["events", "booking"]),
        "page-pricing": ("Page: pricing", ["pricing", "booking"]),
        "page-contact": ("Page: contact", ["contact"]),
    }
    for slug, (title, refs) in pages.items():
        write(slug, title, "\n".join(ref(r) for r in refs), categories=PAGES,
              description="A complete %s page, built from the theme's sections, as the template lays it out."
              % title.split(": ")[1])


def main():
    os.makedirs(PATTERNS, exist_ok=True)
    for name in os.listdir(PATTERNS):
        if name.endswith(".php"):
            os.remove(os.path.join(PATTERNS, name))
    build_header()
    build_footer()
    build_sidebar()
    build_hidden()
    build_hero()
    build_intro_video()
    build_features()
    build_about_club()
    build_pricing()
    build_booking()
    build_news()
    build_gallery()
    build_services()
    build_training()
    build_events()
    build_contact()
    build_pages()

    for slug in sorted(WRITTEN):
        print("  patterns/%s.php" % slug)
    print("\n%d patterns" % len(WRITTEN))


if __name__ == "__main__":
    main()
