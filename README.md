# My Business Theme

Custom WordPress theme for modular business landing pages and service websites.

The homepage is built from reusable sections, with most section copy managed in the Customizer and structured content managed through custom post types where that makes more sense.

## Homepage section order

The front page currently renders sections in this order:

1. Hero
2. Logo Strip
3. Service Overview
4. Service Features
5. Our Services Carousel
6. About the Business
7. Portfolio
8. Testimonials
9. About Owner
10. Contact & Map
11. Process
12. Get In Touch CTA
13. FAQ
14. Service Areas
15. Footer

## Install

You can install the theme either manually or by uploading a zip in WordPress.

### Option 1: Upload as a theme zip

1. Download the codebase as a `.zip`
2. Extract it on your computer
3. Open the extracted folder and find the actual theme root
4. The correct folder is the one that directly contains:
   - `style.css`
   - `functions.php`
   - `assets/`
   - `inc/`
   - `template-parts/`
5. Zip that folder itself
6. In WordPress go to **Appearance > Themes > Add New > Upload Theme**
7. Upload that zipped theme folder and activate it

Important:

- If your extracted folder is named `custom-wordpress-template-main` and it already contains `style.css`, `functions.php`, `assets/`, `inc/`, and `template-parts/`, then that folder is the theme and can be zipped directly
- You may rename that folder to `my-business-theme` before zipping if you want a cleaner folder name in WordPress, but it is not required
- Do not upload an outer zip that only contains another nested project folder

### Option 2: Manual install

1. Copy the `my-business-theme` folder into `wp-content/themes/`
2. Activate the theme in **Appearance > Themes**

### After activation

1. Set a static homepage in **Settings > Reading**
2. Assign a menu to **Primary Menu** in **Appearance > Menus**
3. Open **Appearance > Customize > My Business Theme Options** and configure the sections

## Custom post types

The theme registers these post types:

- `Services`
- `Portfolio`
- `Testimonials`
- `FAQs`
- `Partner Logos`

## Where content is managed

### Global theme settings

Manage these in:

`Appearance > Customize > My Business Theme Options`

Main sections currently available:

- Global Styles
- Header
- Branding & Contact
- Hero
- Logo Strip
- Service Overview
- Service Features
- Our Services Carousel
- About the Business
- Portfolio
- Testimonials
- About Owner
- Contact & Map
- Process
- Get In Touch CTA
- FAQ
- Service Areas
- Footer

### Content source by homepage section

| Section | Primary source |
|---|---|
| Hero | Customizer |
| Logo Strip | `Partner Logos` posts first, then Logo Strip Customizer fallbacks |
| Service Overview | Customizer |
| Service Features | Customizer |
| Our Services Carousel | `Services` posts, with built-in fallback cards |
| About the Business | Customizer |
| Portfolio | `Portfolio` posts, with built-in fallback images |
| Testimonials | Customizer repeater reviews, with built-in fallback reviews |
| About Owner | Customizer |
| Contact & Map | Customizer |
| Process | Customizer |
| Get In Touch CTA | Customizer |
| FAQ intro block | Customizer |
| FAQ accordion items | `FAQs` posts, with built-in fallback FAQs |
| Service Areas | Customizer |
| Footer intro / contact / credits | Customizer |
| Footer services column | `Services` posts first, then Footer fallback list |

## Navigation behavior

- The theme currently uses a single menu location: `Primary Menu`
- Desktop header supports dropdown items
- Mobile header uses a collapsible menu
- Footer no longer depends on a separate footer menu location

## Map embed requirements

The map URL fields expect an embeddable Google Maps URL, not a share link.

Use:

- `https://www.google.com/maps/embed?...`

Do not use:

- `https://maps.app.goo.gl/...`
- the full `<iframe ...></iframe>` tag

Paste only the `src` URL into the map field.

## Images and fallbacks

The theme is built so replacement images keep the intended layout as much as possible.

Notes:

- Most section images use fixed frames or controlled aspect ratios
- Hero and owner images use positioning controls via Customizer X/Y values
- Several sections include bundled fallback images if no content is uploaded yet
- Partner logos and portfolio items fall back to bundled defaults if no admin content exists

## Forms

- Popup CTA buttons can open the theme modal or link to a URL, depending on section settings
- The popup form shortcode is managed in `Branding & Contact`
- Contact Form 7 is recommended if you want a real shortcode-powered popup form

## Developer notes

- No ACF dependency
- Theme assets are versioned with `MBT_VERSION` in `functions.php`
- Main front-page assembly happens in `front-page.php`
- Section templates live in `template-parts/`
- Customizer configuration lives in `inc/customizer.php`
- Shared helpers and fallback data live in `inc/helpers.php`
- Front-end behavior lives in `assets/js/main.js`
- Main styling lives in `assets/css/main.css`
