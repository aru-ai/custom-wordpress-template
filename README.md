# My Business Theme

A reusable WordPress theme built for modular business websites with:

- variable-driven homepage sections
- dropdown desktop navigation
- image-safe containers for replaced images
- special owner-photo handling with object-fit cover
- popup contact form trigger support
- custom post types for services, portfolio, testimonials, FAQs, and partner logos

## Install

1. Copy the `my-business-theme` folder into `wp-content/themes/`
2. Activate the theme in **Appearance > Themes**
3. Go to **Appearance > Customize** to edit global colors, homepage content, contact info, and popup shortcode
4. Create menu items under **Appearance > Menus** and assign the primary menu
5. Add content using these post types:
   - Services
   - Portfolio
   - Testimonials
   - FAQs
   - Partner Logos

## Recommended setup

- Install Contact Form 7 if you want a real popup form
- Add the form shortcode in **Customizer > My Business Theme Options > Branding & Contact**
- Set a static homepage in **Settings > Reading** and choose a page using `front-page.php`

## Image handling

This theme is designed so swapped images keep their original visual space:

- Service and portfolio images use fixed aspect-ratio wrappers
- Owner photo uses a portrait frame with `object-fit: cover`
- Owner image position can be adjusted in the Customizer using X/Y values

## Important notes

- This theme does not require ACF
- For more advanced field mapping, ACF or a custom platform mapper can be added later
- Menu dropdowns are styled for desktop and collapsible on mobile
