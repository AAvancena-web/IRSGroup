# IRS Group redesign: WordPress implementation

Applies the approved static redesign to the live `siteorigin-corp-child` theme.

## The approach, and why

| Decision | Choice | Reason |
|---|---|---|
| Where the code lives | Child theme overlay | The work is presentational and the site already keeps its customisations here. A plugin would survive a theme switch, but this child theme *is* the site. |
| Homepage | Assignable page template, `template-home.php` | Shows as "IRS Homepage (Redesign)" under Page Attributes. No new page needed, and the old WPBakery homepage stays intact until you switch templates. |
| Fields | ACF registered in PHP (`acf_add_local_field_group`) | Deploys with the code. No JSON sync step, no import, and definitions cannot drift between staging and live. Values stay fully editable in the admin. |
| Seeding | One time, on `admin_init`, guarded by an option | No seeder page to build or remove. It runs once, reports what it did, then stays quiet. |
| Header / footer | Override `header.php` and `footer.php` | They are global by definition, so every inner page picks up the redesign with no per page work. |
| Inner banner form | One shared partial | `template-parts/banner-form.php` renders in the homepage hero *and* the inner page banner, so there is a single source of truth. |

### Two constraints that shaped the build

1. **`style.css` sets a fluid root font size**
   `html { font-size: calc(10px + (16 - 10) * ((100vw - 1200px) / (1920 - 1200))) }`
   Every `rem` on the site is therefore viewport dependent. The redesign used 69 rem
   values, which would have been silently rescaled. **All redesign CSS is emitted in
   px**, so it renders identically regardless of that rule, and the rule is left alone
   so existing pages keep their current sizing.

2. **Broad element selectors and class collisions**
   `style.css` styles `body h1/h2/h3`, `body p`, `body ul li`, `*`, and defines
   `.site-header`, `.site-footer`, `.contact-form`, `.form-grid`, `.btn`, `.stats`,
   `.eyebrow` and more. **Every redesign class is `irs-` prefixed** so the two
   stylesheets cannot reach into each other.

### What is deliberately preserved

The existing inner pages are WPBakery layouts that depend on things the old
`footer.php` provided. These are kept verbatim in the new `footer.php`:

* Slick initialisation for `.review-slider2` (the `our_services` and
  `skiptracing_services` shortcodes render into it)
* `.read-more-btn` toggles
* Numeric input filtering
* The `wpcf7mailsent` redirect to `/thank-you/`

`.corp-container` is still opened inside `#content` for every template **except**
the homepage, which lays out its own full bleed sections. Without this, existing
inner page content would lose its max width.

## Install

1. Copy everything except `README.md`, `functions-add-this.php` and
   `cf7-form-templates.txt` into `wp-content/themes/siteorigin-corp-child/`,
   keeping the folder structure. **Back up the current `header.php` and
   `footer.php` first.**
2. Paste the block from `functions-add-this.php` into the existing
   `functions.php`. Do not replace that file.
3. Confirm Advanced Custom Fields **Pro** is active (repeaters and the options
   page need Pro).
4. Load any admin page. The seeder runs once and shows a notice saying how many
   fields it wrote.
5. Create or open the page you want as the homepage, set
   **Page Attributes > Template > IRS Homepage (Redesign)**, and set it under
   **Settings > Reading** if it should be the front page.
6. Build the two Contact Form 7 forms from `cf7-form-templates.txt`, then paste
   their shortcodes into **IRS Global > Banner form** and **IRS Global > Footer**.
7. Assign menus: the header uses the existing `menu-1` location. The footer
   columns look for menus named "Quick Links" and "Footer Services".

Re-run the seeder at any time with `/wp-admin/?irs_reseed=1` (administrators
only). That pass overwrites existing values; the automatic first run does not.

### If you seeded before 2026-09-14

An earlier build named repeater sub fields after their unique key rather than
the short name the seeder and templates use, so repeater rows were created with
the right row count but no values. Six repeaters were affected: trust points,
stats, service cards (image and category chip), social links, and both button
groups.

Fixed. To pick up the values, visit **`/wp-admin/?irs_reseed=1`** once. The
empty rows are replaced. Any leftover meta from the old sub field names is
unused and harmless.

`inc/irs-acf-fields.php` now documents the trap on `irs_acf_field()`: the key is
derived from the slug so it stays unique, so every repeater sub field must pass
an explicit short `'name'`.

## Field reference

* **IRS Global** (options page): phone, email, logos, top bar, header button,
  banner form shortcode and copy, footer text, social links, map URL.
* **IRS Homepage** (only on pages using the template): banner, stats, service
  cards, core service tabs, process steps, skiptrace explainer, FAQ, CTA band
  and contact section.
* **IRS Banner** (every page): per page banner heading and intro overrides, plus
  a switch to hide the enquiry form on that page.

## Verification performed

Rendered through a WordPress function harness and a headless browser:

* Homepage and an inner page both render with **no PHP notices, warnings or errors**
* **Balanced HTML** across the header / template / footer split on both
* **No JavaScript errors**, and **no horizontal overflow** at 390, 1440 and 1920
* Service grid resolves to **5 columns on desktop, 1 on mobile**, 10 cards
* `wp_nav_menu` output picks up the design, dropdowns and injected drawer toggles work
* Inner page banner renders the shared enquiry form; `.corp-container` still wraps
  inner page content but not the homepage

## Known limitations

* The seeder points images at the media library by **filename match**. If an
  image is missing, that field is left empty rather than guessed. Check the
  banner, service card and tab images after seeding.
* ACF field *definitions* are code owned, so they are read only in the ACF admin
  UI. Edit them in `inc/irs-acf-fields.php`.
* The stat figures and the copy generally came from the approved static build.
  Confirm the claims ("8 states and territories", "25+ years") before launch.
