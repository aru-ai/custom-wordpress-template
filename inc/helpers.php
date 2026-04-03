<?php
if (!defined('ABSPATH')) {
    exit;
}

function mbt_get_theme_mod_string(string $key, string $default = ''): string
{
    return trim((string) get_theme_mod($key, $default));
}

function mbt_get_theme_mod_bool(string $key, bool $default = false): bool
{
    $value = get_theme_mod($key, $default);

    if (is_bool($value)) {
        return $value;
    }

    if (is_numeric($value)) {
        return ((int) $value) === 1;
    }

    return in_array(strtolower(trim((string) $value)), ['1', 'true', 'yes', 'on'], true);
}

function mbt_get_raw_theme_mod(string $key)
{
    return get_theme_mod($key, null);
}

function mbt_theme_mod_uses_default(string $key): bool
{
    $value = mbt_get_raw_theme_mod($key);

    return $value === null || trim((string) $value) === '';
}

function mbt_get_theme_mod_image_url(string $key): string
{
    $value = get_theme_mod($key, '');
    if (is_numeric($value)) {
        $url = wp_get_attachment_image_url((int) $value, 'full');
        return $url ?: '';
    }

    return is_string($value) ? $value : '';
}

function mbt_css_font_stack(string $font): string
{
    $map = [
        'Manrope'             => '"Manrope", Arial, sans-serif',
        'Inter'               => 'Inter, Arial, sans-serif',
        'Cormorant Garamond'  => '"Cormorant Garamond", Georgia, serif',
        'Montserrat'          => 'Montserrat, Arial, sans-serif',
        'Playfair Display'    => '"Playfair Display", Georgia, serif',
        'Open Sans'           => '"Open Sans", Arial, sans-serif',
        'Lora'                => 'Lora, Georgia, serif',
        'Poppins'             => 'Poppins, Arial, sans-serif',
        'DM Sans'             => '"DM Sans", Arial, sans-serif',
    ];

    return $map[$font] ?? 'Inter, Arial, sans-serif';
}

function mbt_sanitize_unit_interval($value, float $default = 1.0): float
{
    if (!is_numeric($value)) {
        return $default;
    }

    $value = (float) $value;

    if ($value < 0) {
        return 0.0;
    }

    if ($value > 1) {
        return 1.0;
    }

    return round($value, 2);
}

function mbt_sanitize_header_background_opacity($value): float
{
    return mbt_sanitize_unit_interval($value, 0.58);
}

function mbt_sanitize_checkbox($value): bool
{
    return !empty($value);
}

function mbt_sanitize_absint_range($value, int $default, int $min, int $max): int
{
    if (!is_numeric($value)) {
        return $default;
    }

    return max($min, min($max, absint($value)));
}

function mbt_sanitize_header_width($value): int
{
    return mbt_sanitize_absint_range($value, 1200, 720, 1600);
}

function mbt_sanitize_header_height($value): int
{
    return mbt_sanitize_absint_range($value, 68, 58, 180);
}

function mbt_sanitize_root_font_size($value): float
{
    if (!is_numeric($value)) {
        return 14.0;
    }

    return max(10.0, min(25.0, round((float) $value, 1)));
}

function mbt_sanitize_logo_strip_speed($value): int
{
    return mbt_sanitize_absint_range($value, 28, 12, 80);
}

function mbt_sanitize_service_feature_index($value): int
{
    return mbt_sanitize_absint_range($value, 0, 0, 20);
}

function mbt_sanitize_header_button_type($value): string
{
    return in_array($value, ['popup', 'link'], true) ? $value : 'popup';
}

function mbt_sanitize_testimonials_repeater($value): string
{
    if (!is_string($value) || trim($value) === '') {
        return '';
    }

    $decoded = json_decode(wp_unslash($value), true);
    if (!is_array($decoded)) {
        return '';
    }

    $items = [];

    foreach ($decoded as $item) {
        if (!is_array($item)) {
            continue;
        }

        $name    = sanitize_text_field($item['name'] ?? '');
        $source  = sanitize_text_field($item['source'] ?? '');
        $content = trim(wp_kses_post($item['content'] ?? ''));
        $rating  = mbt_sanitize_absint_range($item['rating'] ?? 5, 5, 1, 5);

        if ($name === '' && $content === '') {
            continue;
        }

        $items[] = [
            'name'    => $name,
            'source'  => $source,
            'content' => $content,
            'rating'  => $rating,
        ];

        if (count($items) >= 100) {
            break;
        }
    }

    return $items ? wp_json_encode($items) : '';
}

function mbt_sanitize_header_link_target($value): string
{
    $value = trim((string) $value);

    if ($value === '') {
        return '';
    }

    if (strpos($value, '#') === 0) {
        return '#' . sanitize_html_class(ltrim($value, '#'));
    }

    return esc_url_raw($value);
}

function mbt_hex_to_rgba(string $hex, float $opacity = 1.0): string
{
    $hex = sanitize_hex_color($hex);

    if (!$hex) {
        $hex = '#000000';
    }

    $hex = ltrim($hex, '#');

    if (strlen($hex) === 3) {
        $hex = preg_replace('/(.)/', '$1$1', $hex);
    }

    $red   = hexdec(substr($hex, 0, 2));
    $green = hexdec(substr($hex, 2, 2));
    $blue  = hexdec(substr($hex, 4, 2));

    return sprintf('rgba(%d, %d, %d, %.2F)', $red, $green, $blue, mbt_sanitize_unit_interval($opacity));
}

function mbt_button(array $args = []): string
{
    $defaults = [
        'label'      => __('Get in touch', 'my-business-theme'),
        'url'        => '',
        'type'       => 'popup',
        'class'      => 'mbt-button',
        'modal'      => 'mbt-contact-modal',
        'target'     => '',
        'rel'        => '',
        'attributes' => '',
    ];

    $args = wp_parse_args($args, $defaults);
    $label = esc_html($args['label']);
    $class = esc_attr($args['class']);

    if ($args['type'] === 'link' && !empty($args['url'])) {
        $target = $args['target'] ? ' target="' . esc_attr($args['target']) . '"' : '';
        $rel    = $args['rel'] ? ' rel="' . esc_attr($args['rel']) . '"' : '';
        return '<a class="' . $class . '" href="' . esc_url($args['url']) . '"' . $target . $rel . ' ' . $args['attributes'] . '>' . $label . '</a>';
    }

    return '<button type="button" class="' . $class . '" data-modal-target="' . esc_attr($args['modal']) . '" ' . $args['attributes'] . '>' . $label . '</button>';
}

function mbt_get_meta(int $post_id, string $key, string $default = ''): string
{
    $value = get_post_meta($post_id, $key, true);
    return is_string($value) && $value !== '' ? $value : $default;
}

function mbt_render_image(int $attachment_id = 0, string $size = 'full', string $class = '', string $alt = ''): string
{
    if (!$attachment_id) {
        return '';
    }

    $attrs = ['class' => trim($class)];
    if ($alt !== '') {
        $attrs['alt'] = $alt;
    }

    $image = wp_get_attachment_image($attachment_id, $size, false, $attrs);
    return $image ?: '';
}

function mbt_get_posts(string $post_type, int $count = -1): WP_Query
{
    return new WP_Query([
        'post_type'      => $post_type,
        'posts_per_page' => $count,
        'post_status'    => 'publish',
        'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC'],
    ]);
}

function mbt_get_phone_link(string $phone): string
{
    $normalized = preg_replace('/(?!^\+)[^\d]/', '', $phone);

    if (!$normalized) {
        return '#contact';
    }

    return 'tel:' . $normalized;
}

function mbt_get_asset_image_url(string $filename): string
{
    $relative_path = 'assets/img/' . ltrim($filename, '/\\');
    $absolute_path = trailingslashit(MBT_DIR) . str_replace('/', DIRECTORY_SEPARATOR, $relative_path);

    if (!file_exists($absolute_path)) {
        return '';
    }

    return trailingslashit(MBT_URI) . str_replace('%2F', '/', rawurlencode($relative_path));
}

function mbt_has_custom_map_embed(): bool
{
    return mbt_get_theme_mod_string('mbt_contact_map_embed', '') !== '';
}

function mbt_get_map_embed_url(): string
{
    $url = mbt_get_theme_mod_string('mbt_contact_map_embed', '');

    if ($url !== '') {
        return $url;
    }

    return 'https://maps.google.com/maps?q=Melbourne%20VIC&t=&z=12&ie=UTF8&iwloc=&output=embed';
}

function mbt_get_demo_images(): array
{
    return [
        'hero'              => mbt_get_asset_image_url('Rectangle 34625213.png'),
        'owner'             => mbt_get_asset_image_url('Martin-Lumi 2 2.png'),
        'owner-section'     => mbt_get_asset_image_url('Martin-Lumi 2 2.png'),
        'owner-background'  => mbt_get_asset_image_url('Rectangle 34625280.png'),
        'owner-frame'       => mbt_get_asset_image_url('Frame 1707484306.png'),
        'owner-signature'   => mbt_get_asset_image_url('Group 1707485040.png'),
        'service-1'         => mbt_get_asset_image_url('image.png'),
        'service-2'         => mbt_get_asset_image_url('image-1.png'),
        'service-3'         => mbt_get_asset_image_url('image-2.png'),
        'capability-1'      => mbt_get_asset_image_url('service1.jpg'),
        'capability-2'      => mbt_get_asset_image_url('service2.jpg'),
        'capability-3'      => mbt_get_asset_image_url('service3.jpg'),
        'capability-4'      => mbt_get_asset_image_url('service4.jpg'),
        'capability-5'      => mbt_get_asset_image_url('service5.jpg'),
        'capability-6'      => mbt_get_asset_image_url('service6.jpg'),
        'featured-overview' => mbt_get_asset_image_url('Rectangle 34625480-1.png'),
        'service-areas-background' => mbt_get_asset_image_url('Rectangle 34625480.png'),
        'footer-background' => mbt_get_asset_image_url('Rectangle 34625466.png'),
        'space'             => mbt_get_asset_image_url('Rectangle 34625462-1.png'),
        'portfolio-1'       => mbt_get_asset_image_url('portfolio1.jpg'),
        'portfolio-2'       => mbt_get_asset_image_url('portfolio2.jpg'),
        'portfolio-3'       => mbt_get_asset_image_url('portfolio3.jpg'),
        'portfolio-4'       => mbt_get_asset_image_url('portfolio4.jpg'),
        'portfolio-5'       => mbt_get_asset_image_url('portfolio5.jpg'),
        'portfolio-6'       => mbt_get_asset_image_url('portfolio6.jpg'),
        'portfolio-7'       => mbt_get_asset_image_url('portfolio7.jpg'),
        'logo-strip'        => mbt_get_asset_image_url('Group 1707484990.png'),
        'map'               => mbt_get_asset_image_url('Rectangle 34625462.png'),
    ];
}

function mbt_get_fallback_image_url(string $key): string
{
    $images = mbt_get_demo_images();

    return $images[$key] ?? $images['hero'];
}

function mbt_get_image_url_or_fallback(int $attachment_id = 0, string $size = 'full', string $fallback_key = 'hero'): string
{
    if ($attachment_id) {
        $url = wp_get_attachment_image_url($attachment_id, $size);

        if ($url) {
            return $url;
        }
    }

    return mbt_get_fallback_image_url($fallback_key);
}

function mbt_get_demo_logos(): array
{
    return [
        'FUJITSU',
        'AUSTARON',
        'elica',
        'Hafele',
        'TOSHIBA',
        'beko',
        'ADP',
    ];
}

function mbt_get_demo_logo_strip_items(): array
{
    $items = [
        [
            'label' => 'ActronAir',
            'image' => mbt_get_asset_image_url('actron_logo_strip.png'),
        ],
        [
            'label' => 'Airtouch',
            'image' => mbt_get_asset_image_url('airtouch_logo_strip.png'),
        ],
        [
            'label' => 'Braemar',
            'image' => mbt_get_asset_image_url('braemar-image_logo_strip.png'),
        ],
        [
            'label' => 'Brivis',
            'image' => mbt_get_asset_image_url('brivis_logo_strip.png'),
        ],
        [
            'label' => 'Daikin',
            'image' => mbt_get_asset_image_url('daikin_logo_strip.png'),
        ],
        [
            'label' => 'Fujitsu',
            'image' => mbt_get_asset_image_url('fujitsu_logo_strip.png'),
        ],
        [
            'label' => 'Mitsubishi Electric',
            'image' => mbt_get_asset_image_url('mitsubishi_logo_strip.png'),
        ],
        [
            'label' => 'Toshiba',
            'image' => mbt_get_asset_image_url('toshiba-image_logo_strip.png'),
        ],
    ];

    return array_values(array_filter($items, static function (array $item): bool {
        return $item['image'] !== '';
    }));
}

function mbt_get_customizer_logo_strip_items(): array
{
    $defaults     = mbt_get_demo_logo_strip_items();
    $items        = [];
    $has_override = false;

    for ($index = 1; $index <= 8; $index++) {
        if (mbt_get_raw_theme_mod('mbt_logo_strip_logo_' . $index) !== null || mbt_get_raw_theme_mod('mbt_logo_strip_label_' . $index) !== null) {
            $has_override = true;
        }
    }

    if (!$has_override) {
        return [];
    }

    for ($index = 1; $index <= 8; $index++) {
        $default_item = $defaults[$index - 1] ?? [
            'label' => sprintf(__('Partner logo %d', 'my-business-theme'), $index),
            'image' => '',
        ];

        $image = mbt_get_theme_mod_image_url('mbt_logo_strip_logo_' . $index);

        if ($image === '') {
            $image = $default_item['image'] ?? '';
        }

        if ($image === '') {
            continue;
        }

        $label = mbt_get_theme_mod_string('mbt_logo_strip_label_' . $index, '');

        $items[] = [
            'label' => $label !== '' ? $label : ($default_item['label'] ?? sprintf(__('Partner logo %d', 'my-business-theme'), $index)),
            'image' => $image,
        ];
    }

    return $items;
}

function mbt_get_demo_logo_strip_image_url(): string
{
    return mbt_get_fallback_image_url('logo-strip');
}

function mbt_get_demo_map_image_url(): string
{
    return mbt_get_fallback_image_url('map');
}

function mbt_get_service_areas_from_theme_mod(): array
{
    $raw = mbt_get_theme_mod_string('mbt_service_areas_list', '');

    if ($raw === '') {
        return mbt_get_demo_service_areas();
    }

    $areas      = [];
    $seen_areas = [];
    $lines      = preg_split('/\r\n|\r|\n/', $raw) ?: [];

    foreach ($lines as $line) {
        $area = sanitize_text_field($line);

        if ($area === '') {
            continue;
        }

        $area_key = strtolower($area);

        if (isset($seen_areas[$area_key])) {
            continue;
        }

        $seen_areas[$area_key] = true;
        $areas[]               = $area;

        if (count($areas) >= 24) {
            break;
        }
    }

    return $areas ?: mbt_get_demo_service_areas();
}

function mbt_get_footer_services_items(): array
{
    $defaults = [
        __('Cabinet Maker', 'my-business-theme'),
        __('Kitchen Cabinet Maker', 'my-business-theme'),
        __('Bathroom Cabinetry', 'my-business-theme'),
        __('Custom Wardrobes', 'my-business-theme'),
        __('Home Office Cabinetry', 'my-business-theme'),
        __('Garage Storage Cabinets', 'my-business-theme'),
    ];

    $raw = mbt_get_theme_mod_string('mbt_footer_services_list', implode("\n", $defaults));

    if ($raw === '') {
        return $defaults;
    }

    $items      = [];
    $seen_items = [];
    $lines      = preg_split('/\r\n|\r|\n/', $raw) ?: [];

    foreach ($lines as $line) {
        $item = sanitize_text_field($line);

        if ($item === '') {
            continue;
        }

        $item_key = strtolower($item);

        if (isset($seen_items[$item_key])) {
            continue;
        }

        $seen_items[$item_key] = true;
        $items[]               = $item;

        if (count($items) >= 12) {
            break;
        }
    }

    return $items ?: $defaults;
}

function mbt_get_footer_service_links(): array
{
    $services = get_posts([
        'post_type'      => 'mbt_service',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => [
            'menu_order' => 'ASC',
            'date'       => 'DESC',
        ],
    ]);

    if (!$services) {
        return [];
    }

    $items = [];

    foreach ($services as $service) {
        $title = get_the_title($service);
        $url   = get_permalink($service);

        if ($title === '' || !$url) {
            continue;
        }

        $items[] = [
            'title' => $title,
            'url'   => $url,
        ];
    }

    return $items;
}

function mbt_get_section_graphic_url(string $key): string
{
    $graphics = [
        'hero-title'         => 'Timeless Cabinetry. Modern Melbourne elegance..png',
        'services-title'     => 'Group 1707485085.png',
        'capabilities-title' => 'Explore Our Luxury Cabinetry Solution.png',
        'about-title'        => 'Where Space Inspire and Design Come Alive.png',
        'portfolio-title'    => 'Group 1707485097.png',
        'testimonials-title' => 'Client Experiences With Lumi Cabinets.png',
        'owner-title'        => 'The Experience and Values Behind Lumi.png',
        'process-title'      => 'How We Bring Your Cabinetry to Life.png',
        'faq-title'          => 'Group 1707485076.png',
        'areas-title'        => 'Proudly Servicing Melbourne Areas.png',
        'owner-signature'    => 'Group 1707485040.png',
        'featured-bespoke'   => 'Group 1707485065.png',
        'testimonials-full'  => 'Group 1707485083.png',
        'about-area-full'    => 'Group 1707485096.png',
        'cta-full'           => 'Group 1707485091.png',
        'footer-full'        => 'Frame 1707485080.png',
    ];

    if (!isset($graphics[$key])) {
        return '';
    }

    return mbt_get_asset_image_url($graphics[$key]);
}

function mbt_get_demo_services(): array
{
    return [
        [
            'title'       => __('Fast & Reliable Local Service', 'my-business-theme'),
            'excerpt'     => __('Melbourne-based electricians delivering quick response times, clear communication, and on-time arrivals you can count on.', 'my-business-theme'),
            'button_text' => '',
            'button_url'  => '',
            'image'       => mbt_get_fallback_image_url('service-1'),
        ],
        [
            'title'       => __('Clear Pricing. Proven Professionalism', 'my-business-theme'),
            'excerpt'     => __('Up-front fixed pricing with no surprises. We follow strict safety standards to ensure every job is completed correctly and safely.', 'my-business-theme'),
            'button_text' => '',
            'button_url'  => '',
            'image'       => mbt_get_fallback_image_url('service-2'),
        ],
        [
            'title'       => __('Licensed Experts You Can Trust', 'my-business-theme'),
            'excerpt'     => __('Fully licensed and experienced professionals providing compliant, high-quality workmanship with long-lasting results.', 'my-business-theme'),
            'button_text' => '',
            'button_url'  => '',
            'image'       => mbt_get_fallback_image_url('service-3'),
        ],
    ];
}

function mbt_get_customizer_service_features(): array
{
    $defaults     = mbt_get_demo_services();
    $items        = [];
    $has_override = false;

    for ($index = 1; $index <= 6; $index++) {
        foreach (['title', 'text', 'image', 'url'] as $field) {
            if (mbt_get_raw_theme_mod('mbt_services_item_' . $index . '_' . $field) !== null) {
                $has_override = true;
                break 2;
            }
        }
    }

    if (!$has_override) {
        return [];
    }

    for ($index = 1; $index <= 6; $index++) {
        $default = $defaults[$index - 1] ?? [
            'title'      => '',
            'excerpt'    => '',
            'image'      => '',
            'button_url' => '',
        ];

        $title = mbt_get_theme_mod_string('mbt_services_item_' . $index . '_title', $default['title'] ?? '');
        $text  = mbt_get_theme_mod_string('mbt_services_item_' . $index . '_text', $default['excerpt'] ?? '');
        $url   = mbt_get_theme_mod_string('mbt_services_item_' . $index . '_url', $default['button_url'] ?? '');
        $image = mbt_get_theme_mod_image_url('mbt_services_item_' . $index . '_image');

        if ($image === '') {
            $image = $default['image'] ?? '';
        }

        if ($title === '' && $text === '' && $image === '') {
            continue;
        }

        $items[] = [
            'title' => $title,
            'text'  => $text,
            'url'   => $url !== '' ? $url : '',
            'image' => $image,
        ];
    }

    return $items;
}

function mbt_get_demo_capability_cards(): array
{
    return [
        [
            'title' => __('Kitchen Cabinetry', 'my-business-theme'),
            'url'   => '#portfolio',
            'image' => mbt_get_fallback_image_url('capability-1'),
        ],
        [
            'title' => __('Home Office Cabinetry', 'my-business-theme'),
            'url'   => '#portfolio',
            'image' => mbt_get_fallback_image_url('capability-2'),
        ],
        [
            'title' => __('Garage Storage Cabinets', 'my-business-theme'),
            'url'   => '#portfolio',
            'image' => mbt_get_fallback_image_url('capability-3'),
        ],
        [
            'title' => __('Custom Wardrobes', 'my-business-theme'),
            'url'   => '#portfolio',
            'image' => mbt_get_fallback_image_url('capability-4'),
        ],
        [
            'title' => __('Bathroom Cabinetry', 'my-business-theme'),
            'url'   => '#portfolio',
            'image' => mbt_get_fallback_image_url('capability-5'),
        ],
        [
            'title' => __('Laundry Cabinetry', 'my-business-theme'),
            'url'   => '#portfolio',
            'image' => mbt_get_fallback_image_url('capability-6'),
        ],
    ];
}

function mbt_get_demo_service_tiles(): array
{
    return [
        [
            'title' => __('Kitchen Cabinetry', 'my-business-theme'),
            'link'  => '#portfolio',
            'image' => mbt_get_asset_image_url('Group 1707485024.png'),
            'composite' => true,
        ],
        [
            'title' => __('Laundry Cabinets', 'my-business-theme'),
            'link'  => '#portfolio',
            'image' => mbt_get_asset_image_url('Group 1707485026.png'),
            'composite' => true,
        ],
        [
            'title' => __('Vanities', 'my-business-theme'),
            'link'  => '#portfolio',
            'image' => mbt_get_asset_image_url('Group 1707485025.png'),
            'composite' => true,
        ],
        [
            'title' => __('Entertainment Units', 'my-business-theme'),
            'link'  => '#portfolio',
            'image' => mbt_get_asset_image_url('Group 1707485023.png'),
            'composite' => true,
        ],
    ];
}

function mbt_get_demo_portfolio_items(): array
{
    return [
        [
            'title' => __('Bathroom Vanity', 'my-business-theme'),
            'image' => mbt_get_fallback_image_url('portfolio-1'),
        ],
        [
            'title' => __('Kitchen Joinery', 'my-business-theme'),
            'image' => mbt_get_fallback_image_url('portfolio-2'),
        ],
        [
            'title' => __('Feature Kitchen', 'my-business-theme'),
            'image' => mbt_get_fallback_image_url('portfolio-3'),
        ],
        [
            'title' => __('Laundry Cabinetry', 'my-business-theme'),
            'image' => mbt_get_fallback_image_url('portfolio-4'),
        ],
        [
            'title' => __('Walk-In Storage', 'my-business-theme'),
            'image' => mbt_get_fallback_image_url('portfolio-5'),
        ],
        [
            'title' => __('Wardrobe Fitout', 'my-business-theme'),
            'image' => mbt_get_fallback_image_url('portfolio-6'),
        ],
        [
            'title' => __('Dark Toned Joinery', 'my-business-theme'),
            'image' => mbt_get_fallback_image_url('portfolio-7'),
        ],
    ];
}

function mbt_get_demo_testimonials(): array
{
    return [
        [
            'name'    => __('Antoinette Jackson', 'my-business-theme'),
            'source'  => __('Based on Google Review', 'my-business-theme'),
            'content' => __('Lumi delivered exactly what we were hoping for and more. The cabinetry is beautifully finished, functional and thoughtfully designed. Martin was clear, honest and easy to work with throughout the entire process.', 'my-business-theme'),
            'rating'  => 5,
        ],
        [
            'name'    => __('Jerome Bell', 'my-business-theme'),
            'source'  => __('Based on Google Review', 'my-business-theme'),
            'content' => __('Lumi delivered exactly what we were hoping for and more. The cabinetry is beautifully finished, functional and thoughtfully designed. Martin was clear, honest and easy to work with throughout the entire process.', 'my-business-theme'),
            'rating'  => 5,
        ],
        [
            'name'    => __('Theresa Webb', 'my-business-theme'),
            'source'  => __('Based on Google Review', 'my-business-theme'),
            'content' => __('Lumi delivered exactly what we were hoping for and more. The cabinetry is beautifully finished, functional and thoughtfully designed. Martin was clear, honest and easy to work with throughout the entire process.', 'my-business-theme'),
            'rating'  => 5,
        ],
        [
            'name'    => __('Savannah Nguyen', 'my-business-theme'),
            'source'  => __('Based on Google Review', 'my-business-theme'),
            'content' => __('From the first consultation to installation, everything felt considered and professional. The joinery looks refined, works beautifully every day, and genuinely lifted the whole room.', 'my-business-theme'),
            'rating'  => 5,
        ],
        [
            'name'    => __('Cameron Williamson', 'my-business-theme'),
            'source'  => __('Based on Google Review', 'my-business-theme'),
            'content' => __('The process was smooth, communication stayed clear, and the final cabinetry exceeded our expectations. Every detail feels premium, practical, and perfectly tailored to our home.', 'my-business-theme'),
            'rating'  => 5,
        ],
    ];
}

function mbt_get_customizer_testimonials(): array
{
    $value = get_theme_mod('mbt_testimonials_reviews', '');

    if (!is_string($value) || trim($value) === '') {
        return [];
    }

    $decoded = json_decode($value, true);
    if (!is_array($decoded)) {
        return [];
    }

    $items = [];

    foreach ($decoded as $item) {
        if (!is_array($item)) {
            continue;
        }

        $name    = sanitize_text_field($item['name'] ?? '');
        $source  = sanitize_text_field($item['source'] ?? '');
        $content = trim(wp_kses_post($item['content'] ?? ''));
        $rating  = mbt_sanitize_absint_range($item['rating'] ?? 5, 5, 1, 5);

        if ($name === '' && $content === '') {
            continue;
        }

        $items[] = [
            'name'    => $name,
            'source'  => $source,
            'content' => $content,
            'rating'  => $rating,
        ];
    }

    return $items;
}

function mbt_get_demo_faqs(): array
{
    return [
        [
            'question' => __('What areas do you service?', 'my-business-theme'),
            'answer'   => __('Lumi Melbourne Cabinets services the southern suburbs of Melbourne and surrounding areas. If you are unsure whether your location is covered, you are always welcome to get in touch to confirm.', 'my-business-theme'),
        ],
        [
            'question' => __('Do you design and build custom cabinetry?', 'my-business-theme'),
            'answer'   => __('Yes. We manage the cabinetry process from consultation and design guidance through to detailed manufacture and installation, creating tailored joinery that fits your home and the way you live.', 'my-business-theme'),
        ],
        [
            'question' => __('What types of cabinetry do you specialise in?', 'my-business-theme'),
            'answer'   => __('We create kitchens, wardrobes, laundries, bathroom vanities, entertainment units, storage fit-outs, and other bespoke cabinetry solutions for residential homes.', 'my-business-theme'),
        ],
        [
            'question' => __('Can you help if I’m not sure what I want yet?', 'my-business-theme'),
            'answer'   => __('Absolutely. We can guide you through layouts, storage ideas, finishes, and practical details so the final cabinetry feels clear, cohesive, and suited to your home.', 'my-business-theme'),
        ],
        [
            'question' => __('How long does a typical project take?', 'my-business-theme'),
            'answer'   => __('Timing depends on the project scope, materials, and site conditions, but we will always talk you through realistic lead times and key milestones before work begins.', 'my-business-theme'),
        ],
        [
            'question' => __('Who will be working on my project?', 'my-business-theme'),
            'answer'   => __('You will work directly with Lumi Melbourne Cabinets throughout the process, with clear communication from consultation through to installation and final handover.', 'my-business-theme'),
        ],
    ];
}

function mbt_get_demo_process_steps(): array
{
    return [
        [
            'title'   => __('Contact Us & Share Your Vision', 'my-business-theme'),
            'content' => __('Tell us about the space, your priorities, and any plans or inspiration you already have.', 'my-business-theme'),
        ],
        [
            'title'   => __('Customised Design & Estimate', 'my-business-theme'),
            'content' => __('We shape a tailored solution around layout, materiality, function, and a realistic project budget.', 'my-business-theme'),
        ],
        [
            'title'   => __('We Build and Deliver To Scope', 'my-business-theme'),
            'content' => __('Manufacture, installation, and finishing are coordinated carefully so the final result feels seamless.', 'my-business-theme'),
        ],
    ];
}

function mbt_get_demo_service_areas(): array
{
    return [
        __('Aspendale', 'my-business-theme'),
        __('Braeside', 'my-business-theme'),
        __('Brighton', 'my-business-theme'),
        __('Chelsea', 'my-business-theme'),
        __('Chelsea Heights', 'my-business-theme'),
        __('Cheltenham', 'my-business-theme'),
        __('Edithvale', 'my-business-theme'),
        __('Hampton', 'my-business-theme'),
        __('Mentone', 'my-business-theme'),
        __('Moorabbin', 'my-business-theme'),
        __('Parkdale', 'my-business-theme'),
        __('Southern Melbourne', 'my-business-theme'),
    ];
}

function mbt_render_stars(int $rating = 5): string
{
    $rating = max(1, min(5, $rating));
    $stars = '';

    for ($index = 1; $index <= 5; $index++) {
        $stars .= $index <= $rating ? '★' : '☆';
    }

    return $stars;
}

function mbt_render_stars_html(int $rating = 5): string
{
    $rating = max(1, min(5, $rating));
    $stars = '';

    for ($index = 1; $index <= 5; $index++) {
        $stars .= $index <= $rating ? '&#9733;' : '&#9734;';
    }

    return $stars;
}
