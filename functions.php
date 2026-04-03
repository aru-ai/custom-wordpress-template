<?php
if (!defined('ABSPATH')) {
    exit;
}

define('MBT_VERSION', '1.1.71');
define('MBT_DIR', get_template_directory());
define('MBT_URI', get_template_directory_uri());

require_once MBT_DIR . '/inc/helpers.php';
require_once MBT_DIR . '/inc/customizer.php';
require_once MBT_DIR . '/inc/meta-boxes.php';

function mbt_setup(): void
{
    load_theme_textdomain('my-business-theme', MBT_DIR . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);
    add_theme_support('custom-logo', [
        'height'      => 120,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor.css');

    register_nav_menus([
        'primary' => __('Primary Menu', 'my-business-theme'),
        'footer'  => __('Footer Menu', 'my-business-theme'),
    ]);

    add_image_size('mbt-service', 900, 700, true);
    add_image_size('mbt-portfolio', 1200, 900, true);
    add_image_size('mbt-testimonial', 240, 240, true);
    add_image_size('mbt-logo', 320, 180, false);
}
add_action('after_setup_theme', 'mbt_setup');

function mbt_enqueue_assets(): void
{
    wp_enqueue_style(
        'mbt-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&family=Manrope:wght@400;500;600;700;800&display=swap',
        [],
        null
    );
    wp_enqueue_style('mbt-main', MBT_URI . '/assets/css/main.css', [], MBT_VERSION);
    wp_enqueue_script('mbt-main', MBT_URI . '/assets/js/main.js', [], MBT_VERSION, true);

    wp_localize_script('mbt-main', 'mbtTheme', [
        'openLabel'  => __('Open menu', 'my-business-theme'),
        'closeLabel' => __('Close menu', 'my-business-theme'),
    ]);
}
add_action('wp_enqueue_scripts', 'mbt_enqueue_assets');

function mbt_register_post_types(): void
{
    $supports = ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'];

    register_post_type('mbt_service', [
        'labels' => [
            'name'          => __('Services', 'my-business-theme'),
            'singular_name' => __('Service', 'my-business-theme'),
        ],
        'public'       => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-screenoptions',
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'services'],
        'supports'     => $supports,
    ]);

    register_post_type('mbt_portfolio', [
        'labels' => [
            'name'          => __('Portfolio', 'my-business-theme'),
            'singular_name' => __('Portfolio Item', 'my-business-theme'),
        ],
        'public'       => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-format-gallery',
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'portfolio'],
        'supports'     => $supports,
    ]);

    register_post_type('mbt_testimonial', [
        'labels' => [
            'name'          => __('Testimonials', 'my-business-theme'),
            'singular_name' => __('Testimonial', 'my-business-theme'),
        ],
        'public'       => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-format-quote',
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'testimonial'],
        'supports'     => ['title', 'editor', 'thumbnail', 'page-attributes'],
    ]);

    register_post_type('mbt_faq', [
        'labels' => [
            'name'          => __('FAQs', 'my-business-theme'),
            'singular_name' => __('FAQ', 'my-business-theme'),
        ],
        'public'       => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-editor-help',
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'faq'],
        'supports'     => ['title', 'editor', 'page-attributes'],
    ]);

    register_post_type('mbt_partner', [
        'labels' => [
            'name'          => __('Partner Logos', 'my-business-theme'),
            'singular_name' => __('Partner Logo', 'my-business-theme'),
        ],
        'public'       => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-images-alt2',
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'partner-logo'],
        'supports'     => ['title', 'thumbnail', 'page-attributes'],
    ]);
}
add_action('init', 'mbt_register_post_types');

function mbt_body_classes(array $classes): array
{
    if (is_front_page()) {
        $classes[] = 'is-front-page';
    }

    return $classes;
}
add_filter('body_class', 'mbt_body_classes');

function mbt_inline_css_variables(): void
{
    $header_color           = sanitize_hex_color(get_theme_mod('mbt_header_background_color', '#100e0d')) ?: '#100e0d';
    $header_opacity         = mbt_sanitize_unit_interval(get_theme_mod('mbt_header_background_opacity', 0.58), 0.58);
    $header_overlay_opacity = round($header_opacity * 0.41, 2);
    $header_width           = mbt_sanitize_header_width(get_theme_mod('mbt_header_width', 1200));
    $header_height          = mbt_sanitize_header_height(get_theme_mod('mbt_header_height', 80));
    $header_height_tablet   = min($header_height, max(74, (int) round($header_height * 0.92)));
    $header_height_mobile   = min($header_height_tablet, max(58, (int) round($header_height * 0.72)));
    $logo_strip_duration    = mbt_sanitize_logo_strip_speed(get_theme_mod('mbt_logo_strip_speed', 28));
    $services_highlight     = sanitize_hex_color(get_theme_mod('mbt_services_highlight_color', '#c8ab6e')) ?: '#c8ab6e';
    $cta_status_color       = sanitize_hex_color(get_theme_mod('mbt_cta_status_color', '#7df33b')) ?: '#7df33b';

    $vars = [
        '--mbt-color-primary'            => get_theme_mod('mbt_color_primary', '#c8ab6e'),
        '--mbt-color-secondary'          => get_theme_mod('mbt_color_secondary', '#212121'),
        '--mbt-color-accent'             => get_theme_mod('mbt_color_accent', '#f6f3ee'),
        '--mbt-color-text'               => get_theme_mod('mbt_color_text', '#1f1f1f'),
        '--mbt-color-surface'            => get_theme_mod('mbt_color_surface', '#ffffff'),
        '--mbt-header-width'             => $header_width . 'px',
        '--mbt-header-height'            => $header_height . 'px',
        '--mbt-header-height-tablet'     => $header_height_tablet . 'px',
        '--mbt-header-height-mobile'     => $header_height_mobile . 'px',
        '--mbt-header-shell-background'  => mbt_hex_to_rgba($header_color, $header_opacity),
        '--mbt-header-overlay-background'=> mbt_hex_to_rgba($header_color, $header_overlay_opacity),
        '--mbt-header-overlay-fade'      => mbt_hex_to_rgba($header_color, 0),
        '--mbt-font-heading'             => mbt_css_font_stack(get_theme_mod('mbt_font_heading', 'Cormorant Garamond')),
        '--mbt-font-body'                => mbt_css_font_stack(get_theme_mod('mbt_font_body', 'Manrope')),
        '--mbt-container-width'          => absint(get_theme_mod('mbt_container_width', 1200)) . 'px',
        '--mbt-section-space'            => absint(get_theme_mod('mbt_section_spacing', 88)) . 'px',
        '--mbt-services-highlight'       => $services_highlight,
        '--mbt-services-highlight-soft'  => mbt_hex_to_rgba($services_highlight, 0.12),
        '--mbt-services-highlight-glow'  => mbt_hex_to_rgba($services_highlight, 0.18),
        '--mbt-cta-status-color'         => $cta_status_color,
        '--mbt-cta-status-color-soft'    => mbt_hex_to_rgba($cta_status_color, 0.46),
        '--mbt-hero-position-x'          => esc_attr(get_theme_mod('mbt_hero_position_x', '50%')),
        '--mbt-hero-position-y'          => esc_attr(get_theme_mod('mbt_hero_position_y', '0%')),
        '--mbt-featured-position-x'      => esc_attr(get_theme_mod('mbt_featured_position_x', '50%')),
        '--mbt-featured-position-y'      => esc_attr(get_theme_mod('mbt_featured_position_y', '50%')),
        '--mbt-owner-position-x'         => esc_attr(get_theme_mod('mbt_owner_position_x', '50%')),
        '--mbt-owner-position-y'         => esc_attr(get_theme_mod('mbt_owner_position_y', '20%')),
        '--mbt-owner-section-position-x' => esc_attr(get_theme_mod('mbt_owner_section_position_x', '50%')),
        '--mbt-owner-section-position-y' => esc_attr(get_theme_mod('mbt_owner_section_position_y', '20%')),
        '--mbt-logo-strip-duration'      => $logo_strip_duration . 's',
    ];

    $style = ':root{';
    foreach ($vars as $name => $value) {
        $style .= $name . ':' . $value . ';';
    }
    $style .= '}';

    echo '<style id="mbt-inline-vars">' . wp_strip_all_tags($style) . '</style>';
}
add_action('wp_head', 'mbt_inline_css_variables', 20);
