<?php
if (!defined('ABSPATH')) {
    exit;
}

if (class_exists('WP_Customize_Control') && !class_exists('MBT_Testimonial_Repeater_Control')) {
    class MBT_Testimonial_Repeater_Control extends WP_Customize_Control
    {
        public $type = 'mbt-testimonial-repeater';

        public function render_content(): void
        {
            $value = $this->value();
            $items = [];

            if (is_string($value) && trim($value) !== '') {
                $decoded = json_decode($value, true);
                if (is_array($decoded)) {
                    $items = $decoded;
                }
            }
            ?>
            <?php if (!empty($this->label)) : ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>

            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>

            <div class="mbt-repeater-control" data-default-source="<?php echo esc_attr(get_theme_mod('mbt_testimonials_review_source', 'Based on Google Review')); ?>">
                <input type="hidden" class="mbt-repeater-control__value" <?php $this->link(); ?> value="<?php echo esc_attr(is_string($value) ? $value : ''); ?>">
                <div class="mbt-repeater-control__list" data-items="<?php echo esc_attr(wp_json_encode($items)); ?>"></div>
                <button type="button" class="button button-secondary mbt-repeater-control__add"><?php esc_html_e('Add Review', 'my-business-theme'); ?></button>
            </div>
            <?php
        }
    }
}

function mbt_customize_register(WP_Customize_Manager $wp_customize): void
{
    $wp_customize->add_panel('mbt_theme_options', [
        'title'    => __('My Business Theme Options', 'my-business-theme'),
        'priority' => 30,
    ]);

    $wp_customize->add_section('mbt_global_styles', [
        'title' => __('Global Styles', 'my-business-theme'),
        'panel' => 'mbt_theme_options',
    ]);

    $wp_customize->add_section('mbt_header_options', [
        'title'       => __('Header', 'my-business-theme'),
        'description' => __('Adjust the header size, appearance, and CTA content here. Navigation links are managed under Appearance > Menus.', 'my-business-theme'),
        'panel'       => 'mbt_theme_options',
    ]);

    $color_settings = [
        'mbt_color_primary'   => ['Primary Color', '#c8ab6e'],
        'mbt_color_secondary' => ['Secondary Color', '#212121'],
        'mbt_color_accent'    => ['Accent Color', '#f6f3ee'],
        'mbt_color_text'      => ['Text Color', '#1f1f1f'],
        'mbt_color_surface'   => ['Surface Color', '#ffffff'],
    ];

    foreach ($color_settings as $key => [$label, $default]) {
        $wp_customize->add_setting($key, [
            'default'           => $default,
            'sanitize_callback' => 'sanitize_hex_color',
        ]);
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $key, [
            'label'   => __($label, 'my-business-theme'),
            'section' => 'mbt_global_styles',
        ]));
    }

    $wp_customize->add_setting('mbt_header_background_color', [
        'default'           => '#100e0d',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mbt_header_background_color', [
        'label'       => __('Header Background Color', 'my-business-theme'),
        'description' => __('Used for the translucent header shell and top overlay.', 'my-business-theme'),
        'section'     => 'mbt_header_options',
    ]));

    $wp_customize->add_setting('mbt_header_background_opacity', [
        'default'           => 0.58,
        'sanitize_callback' => 'mbt_sanitize_header_background_opacity',
    ]);
    $wp_customize->add_control('mbt_header_background_opacity', [
        'label'       => __('Header Background Opacity', 'my-business-theme'),
        'description' => __('Set between 0 and 1. Lower values are more transparent.', 'my-business-theme'),
        'section'     => 'mbt_header_options',
        'type'        => 'number',
        'input_attrs' => ['min' => 0, 'max' => 1, 'step' => 0.01],
    ]);

    $wp_customize->add_setting('mbt_header_width', [
        'default'           => 1200,
        'sanitize_callback' => 'mbt_sanitize_header_width',
    ]);
    $wp_customize->add_control('mbt_header_width', [
        'label'       => __('Header Width (px)', 'my-business-theme'),
        'description' => __('Maximum width of the floating header shell.', 'my-business-theme'),
        'section'     => 'mbt_header_options',
        'type'        => 'number',
        'input_attrs' => ['min' => 720, 'max' => 1600, 'step' => 10],
    ]);

    $wp_customize->add_setting('mbt_header_height', [
        'default'           => 80,
        'sanitize_callback' => 'mbt_sanitize_header_height',
    ]);
    $wp_customize->add_control('mbt_header_height', [
        'label'       => __('Header Height (px)', 'my-business-theme'),
        'description' => __('Controls the minimum height of the header across breakpoints.', 'my-business-theme'),
        'section'     => 'mbt_header_options',
        'type'        => 'number',
        'input_attrs' => ['min' => 58, 'max' => 180, 'step' => 2],
    ]);

    $wp_customize->add_setting('mbt_header_show_phone', [
        'default'           => true,
        'sanitize_callback' => 'mbt_sanitize_checkbox',
    ]);
    $wp_customize->add_control('mbt_header_show_phone', [
        'label'   => __('Show Phone CTA', 'my-business-theme'),
        'section' => 'mbt_header_options',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting('mbt_header_phone_label', [
        'default'           => 'Call Us',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_header_phone_label', [
        'label'       => __('Phone CTA Label', 'my-business-theme'),
        'description' => __('Small text shown above the phone number.', 'my-business-theme'),
        'section'     => 'mbt_header_options',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_header_show_button', [
        'default'           => true,
        'sanitize_callback' => 'mbt_sanitize_checkbox',
    ]);
    $wp_customize->add_control('mbt_header_show_button', [
        'label'   => __('Show Header Button', 'my-business-theme'),
        'section' => 'mbt_header_options',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting('mbt_header_button_text', [
        'default'           => 'Get a Quote',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_header_button_text', [
        'label'   => __('Header Button Text', 'my-business-theme'),
        'section' => 'mbt_header_options',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_header_button_type', [
        'default'           => 'popup',
        'sanitize_callback' => 'mbt_sanitize_header_button_type',
    ]);
    $wp_customize->add_control('mbt_header_button_type', [
        'label'   => __('Header Button Behavior', 'my-business-theme'),
        'section' => 'mbt_header_options',
        'type'    => 'select',
        'choices' => [
            'popup' => __('Open popup form', 'my-business-theme'),
            'link'  => __('Open link', 'my-business-theme'),
        ],
    ]);

    $wp_customize->add_setting('mbt_header_button_url', [
        'default'           => '#contact',
        'sanitize_callback' => 'mbt_sanitize_header_link_target',
    ]);
    $wp_customize->add_control('mbt_header_button_url', [
        'label'       => __('Header Button URL', 'my-business-theme'),
        'description' => __('Used only when the header button behavior is set to Open link.', 'my-business-theme'),
        'section'     => 'mbt_header_options',
        'type'        => 'url',
    ]);

    $wp_customize->add_setting('mbt_font_heading', [
        'default'           => 'Cormorant Garamond',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_font_heading', [
        'label'   => __('Heading Font', 'my-business-theme'),
        'section' => 'mbt_global_styles',
        'type'    => 'select',
        'choices' => mbt_font_choices(),
    ]);

    $wp_customize->add_setting('mbt_font_body', [
        'default'           => 'Manrope',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_font_body', [
        'label'   => __('Body Font', 'my-business-theme'),
        'section' => 'mbt_global_styles',
        'type'    => 'select',
        'choices' => mbt_font_choices(),
    ]);

    $wp_customize->add_setting('mbt_root_font_size', [
        'default'           => 15,
        'sanitize_callback' => 'mbt_sanitize_root_font_size',
    ]);
    $wp_customize->add_control('mbt_root_font_size', [
        'label'       => __('Base Font Size (px)', 'my-business-theme'),
        'description' => __('Scales most front-end text because the theme uses rem-based typography. Default: 15px.', 'my-business-theme'),
        'section'     => 'mbt_global_styles',
        'type'        => 'number',
        'input_attrs' => [
            'min'  => 10,
            'max'  => 25,
            'step' => 0.5,
        ],
    ]);

    $wp_customize->add_setting('mbt_container_width', [
        'default'           => 1200,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('mbt_container_width', [
        'label'       => __('Container Width (px)', 'my-business-theme'),
        'section'     => 'mbt_global_styles',
        'type'        => 'number',
        'input_attrs' => ['min' => 960, 'max' => 1440, 'step' => 10],
    ]);

    $wp_customize->add_setting('mbt_section_spacing', [
        'default'           => 88,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('mbt_section_spacing', [
        'label'       => __('Section Spacing (px)', 'my-business-theme'),
        'section'     => 'mbt_global_styles',
        'type'        => 'number',
        'input_attrs' => ['min' => 40, 'max' => 160, 'step' => 4],
    ]);

    $wp_customize->add_section('mbt_branding', [
        'title' => __('Branding & Contact', 'my-business-theme'),
        'panel' => 'mbt_theme_options',
    ]);

    $brand_fields = [
        'mbt_business_name'    => ['Business Name', 'Lumi Melbourne Cabinets'],
        'mbt_contact_phone'    => ['Phone Number', '0435 777 797'],
        'mbt_contact_email'    => ['Email Address', 'hello@lumicabinets.com.au'],
        'mbt_contact_address'  => ['Address', 'Melbourne, Victoria, Australia'],
        'mbt_modal_title'      => ['Popup Form Title', 'Get a quote'],
        'mbt_contact_form_shortcode' => ['Contact Form Shortcode', '[contact-form-7 id="123" title="Contact form"]'],
    ];

    foreach ($brand_fields as $key => [$label, $default]) {
        $wp_customize->add_setting($key, [
            'default'           => $default,
            'sanitize_callback' => 'wp_kses_post',
        ]);
        $wp_customize->add_control($key, [
            'label'   => __($label, 'my-business-theme'),
            'section' => 'mbt_branding',
            'type'    => 'text',
        ]);
    }

    $wp_customize->add_section('mbt_hero_options', [
        'title'       => __('Hero', 'my-business-theme'),
        'description' => __('Manage the hero background, text, review pill, shared portrait image, and enquiry CTA shown on the first screen.', 'my-business-theme'),
        'panel'       => 'mbt_theme_options',
    ]);

    $wp_customize->add_setting('mbt_hero_background', ['sanitize_callback' => 'absint']);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'mbt_hero_background', [
        'label'      => __('Hero Background Image', 'my-business-theme'),
        'section'    => 'mbt_hero_options',
        'mime_type'  => 'image',
    ]));

    $wp_customize->add_setting('mbt_hero_position_x', [
        'default'           => '50%',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_hero_position_x', [
        'label'       => __('Hero Image Position X', 'my-business-theme'),
        'description' => __('Example: 50%, center, left', 'my-business-theme'),
        'section'     => 'mbt_hero_options',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_hero_position_y', [
        'default'           => '0%',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_hero_position_y', [
        'label'       => __('Hero Image Position Y', 'my-business-theme'),
        'description' => __('Example: 0%, top, center', 'my-business-theme'),
        'section'     => 'mbt_hero_options',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_hero_kicker', [
        'default'           => 'Based on Google Reviews',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('mbt_hero_kicker', [
        'label'       => __('Hero Review Text', 'my-business-theme'),
        'description' => __('Small text shown under the review score.', 'my-business-theme'),
        'section'     => 'mbt_hero_options',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_hero_review_score', [
        'default'           => '4.9',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_hero_review_score', [
        'label'       => __('Hero Review Score', 'my-business-theme'),
        'description' => __('Example: 4.9', 'my-business-theme'),
        'section'     => 'mbt_hero_options',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_hero_title', [
        'default'           => 'Timeless Cabinetry.',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('mbt_hero_title', [
        'label'   => __('Hero Title', 'my-business-theme'),
        'section' => 'mbt_hero_options',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_business_tagline', [
        'default'           => 'Modern Melbourne elegance.',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('mbt_business_tagline', [
        'label'       => __('Hero Accent Text', 'my-business-theme'),
        'description' => __('Second line shown after the main hero title.', 'my-business-theme'),
        'section'     => 'mbt_hero_options',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_hero_text', [
        'default'           => 'Bespoke cabinetry and joinery, crafted with precision to bring light, function and timeless beauty to your home.',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('mbt_hero_text', [
        'label'   => __('Hero Text', 'my-business-theme'),
        'section' => 'mbt_hero_options',
        'type'    => 'textarea',
    ]);

    $wp_customize->add_setting('mbt_hero_form_title', [
        'default'           => 'Got a project in mind?',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_hero_form_title', [
        'label'   => __('Hero Form Title', 'my-business-theme'),
        'section' => 'mbt_hero_options',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_hero_form_name_placeholder', [
        'default'           => 'Enter name',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_hero_form_name_placeholder', [
        'label'   => __('Name Field Placeholder', 'my-business-theme'),
        'section' => 'mbt_hero_options',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_hero_form_phone_placeholder', [
        'default'           => 'Enter phone',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_hero_form_phone_placeholder', [
        'label'   => __('Phone Field Placeholder', 'my-business-theme'),
        'section' => 'mbt_hero_options',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_hero_form_email_placeholder', [
        'default'           => 'Enter email',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_hero_form_email_placeholder', [
        'label'   => __('Email Field Placeholder', 'my-business-theme'),
        'section' => 'mbt_hero_options',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_hero_form_message_placeholder', [
        'default'           => 'How can we help?',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_hero_form_message_placeholder', [
        'label'   => __('Message Field Placeholder', 'my-business-theme'),
        'section' => 'mbt_hero_options',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_hero_button_text', [
        'default'           => 'Get a quote',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_hero_button_text', [
        'label'   => __('Hero Button Text', 'my-business-theme'),
        'section' => 'mbt_hero_options',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_hero_button_type', [
        'default'           => 'popup',
        'sanitize_callback' => 'mbt_sanitize_header_button_type',
    ]);
    $wp_customize->add_control('mbt_hero_button_type', [
        'label'   => __('Hero Button Behavior', 'my-business-theme'),
        'section' => 'mbt_hero_options',
        'type'    => 'select',
        'choices' => [
            'popup' => __('Open popup form', 'my-business-theme'),
            'link'  => __('Open link', 'my-business-theme'),
        ],
    ]);

    $wp_customize->add_setting('mbt_hero_button_url', [
        'default'           => '#contact',
        'sanitize_callback' => 'mbt_sanitize_header_link_target',
    ]);
    $wp_customize->add_control('mbt_hero_button_url', [
        'label'       => __('Hero Button URL', 'my-business-theme'),
        'description' => __('Used only when the hero button behavior is set to Open link.', 'my-business-theme'),
        'section'     => 'mbt_hero_options',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_owner_photo', ['sanitize_callback' => 'absint']);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'mbt_owner_photo', [
        'label'      => __('Hero Portrait Image', 'my-business-theme'),
        'section'    => 'mbt_hero_options',
        'mime_type'  => 'image',
    ]));

    $wp_customize->add_setting('mbt_owner_position_x', [
        'default'           => '50%',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_owner_position_x', [
        'label'       => __('Hero Portrait Position X', 'my-business-theme'),
        'description' => __('Example: 50%, center, left', 'my-business-theme'),
        'section'     => 'mbt_hero_options',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_owner_position_y', [
        'default'           => '20%',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_owner_position_y', [
        'label'       => __('Hero Portrait Position Y', 'my-business-theme'),
        'description' => __('Example: 20%, center, top', 'my-business-theme'),
        'section'     => 'mbt_hero_options',
        'type'        => 'text',
    ]);

    $wp_customize->add_section('mbt_logo_strip', [
        'title'       => __('Logo Strip', 'my-business-theme'),
        'description' => __('Manage the scrolling logo carousel shown below the hero. Partner Logos posts from the admin take priority, these custom slots are used only when there are no Partner Logos yet.', 'my-business-theme'),
        'panel'       => 'mbt_theme_options',
    ]);

    $wp_customize->add_setting('mbt_logo_strip_speed', [
        'default'           => 28,
        'sanitize_callback' => 'mbt_sanitize_logo_strip_speed',
    ]);
    $wp_customize->add_control('mbt_logo_strip_speed', [
        'label'       => __('Scroll Speed (seconds)', 'my-business-theme'),
        'description' => __('Lower values move faster. Recommended range: 20 to 36 seconds.', 'my-business-theme'),
        'section'     => 'mbt_logo_strip',
        'type'        => 'number',
        'input_attrs' => ['min' => 12, 'max' => 80, 'step' => 1],
    ]);

    for ($index = 1; $index <= 8; $index++) {
        $wp_customize->add_setting('mbt_logo_strip_label_' . $index, [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control('mbt_logo_strip_label_' . $index, [
            'label'       => sprintf(__('Logo %d Label', 'my-business-theme'), $index),
            'description' => __('Used as the image alt text for accessibility.', 'my-business-theme'),
            'section'     => 'mbt_logo_strip',
            'type'        => 'text',
        ]);

        $wp_customize->add_setting('mbt_logo_strip_logo_' . $index, [
            'default'           => '',
            'sanitize_callback' => 'absint',
        ]);
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'mbt_logo_strip_logo_' . $index, [
            'label'      => sprintf(__('Logo %d Image', 'my-business-theme'), $index),
            'section'    => 'mbt_logo_strip',
            'mime_type'  => 'image',
        ]));
    }

    $wp_customize->add_section('mbt_featured_overview', [
        'title'       => __('Service Overview', 'my-business-theme'),
        'description' => __('Manage the full-width service overview block shown below the logo strip.', 'my-business-theme'),
        'panel'       => 'mbt_theme_options',
    ]);

    $wp_customize->add_setting('mbt_featured_background', [
        'default'           => '',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'mbt_featured_background', [
        'label'      => __('Background Image', 'my-business-theme'),
        'section'    => 'mbt_featured_overview',
        'mime_type'  => 'image',
    ]));

    $wp_customize->add_setting('mbt_featured_position_x', [
        'default'           => '50%',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_featured_position_x', [
        'label'       => __('Background Position X', 'my-business-theme'),
        'description' => __('Example: 50%, center, left', 'my-business-theme'),
        'section'     => 'mbt_featured_overview',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_featured_position_y', [
        'default'           => '50%',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_featured_position_y', [
        'label'       => __('Background Position Y', 'my-business-theme'),
        'description' => __('Example: 50%, center, top', 'my-business-theme'),
        'section'     => 'mbt_featured_overview',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_featured_kicker', [
        'default'           => 'Our Service Overview',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_featured_kicker', [
        'label'   => __('Kicker', 'my-business-theme'),
        'section' => 'mbt_featured_overview',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_featured_title', [
        'default'           => 'Bespoke Cabinetry',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_featured_title', [
        'label'   => __('Title', 'my-business-theme'),
        'section' => 'mbt_featured_overview',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_featured_accent', [
        'default'           => 'Designed for Everyday Living',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_featured_accent', [
        'label'   => __('Accent Line', 'my-business-theme'),
        'section' => 'mbt_featured_overview',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_featured_text', [
        'default'           => 'Lumi Melbourne Cabinets delivers premium bespoke cabinetry across Melbourne\'s southern suburbs. From kitchens and bathrooms to wardrobes, home offices and garage storage, each space is thoughtfully designed for refined aesthetics, practical function and lasting quality.',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('mbt_featured_text', [
        'label'   => __('Body Text', 'my-business-theme'),
        'section' => 'mbt_featured_overview',
        'type'    => 'textarea',
    ]);

    $wp_customize->add_setting('mbt_featured_link_text', [
        'default'           => 'Expand',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_featured_link_text', [
        'label'   => __('Secondary Text Link Label', 'my-business-theme'),
        'section' => 'mbt_featured_overview',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_featured_link_url', [
        'default'           => '#services',
        'sanitize_callback' => 'mbt_sanitize_header_link_target',
    ]);
    $wp_customize->add_control('mbt_featured_link_url', [
        'label'   => __('Secondary Text Link URL', 'my-business-theme'),
        'section' => 'mbt_featured_overview',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_featured_button_text', [
        'default'           => 'Get a Quote',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_featured_button_text', [
        'label'   => __('Primary Button Text', 'my-business-theme'),
        'section' => 'mbt_featured_overview',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_featured_button_type', [
        'default'           => 'popup',
        'sanitize_callback' => 'mbt_sanitize_header_button_type',
    ]);
    $wp_customize->add_control('mbt_featured_button_type', [
        'label'   => __('Primary Button Behavior', 'my-business-theme'),
        'section' => 'mbt_featured_overview',
        'type'    => 'select',
        'choices' => [
            'popup' => __('Open popup form', 'my-business-theme'),
            'link'  => __('Open link', 'my-business-theme'),
        ],
    ]);

    $wp_customize->add_setting('mbt_featured_button_url', [
        'default'           => '#contact',
        'sanitize_callback' => 'mbt_sanitize_header_link_target',
    ]);
    $wp_customize->add_control('mbt_featured_button_url', [
        'label'       => __('Primary Button URL', 'my-business-theme'),
        'description' => __('Used only when the primary button behavior is set to Open link.', 'my-business-theme'),
        'section'     => 'mbt_featured_overview',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_featured_show_phone', [
        'default'           => true,
        'sanitize_callback' => 'mbt_sanitize_checkbox',
    ]);
    $wp_customize->add_control('mbt_featured_show_phone', [
        'label'   => __('Show Phone Button', 'my-business-theme'),
        'section' => 'mbt_featured_overview',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_section('mbt_services_features', [
        'title'       => __('Service Features', 'my-business-theme'),
        'description' => __('Manage the feature-card section shown below the Service Overview block. Add up to 6 custom cards here, or leave them empty to keep the bundled default cards.', 'my-business-theme'),
        'panel'       => 'mbt_theme_options',
    ]);

    $wp_customize->add_setting('mbt_services_kicker', [
        'default'           => 'Our Service Features',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_services_kicker', [
        'label'   => __('Kicker', 'my-business-theme'),
        'section' => 'mbt_services_features',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_services_title_main', [
        'default'           => 'End to End Custom',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_services_title_main', [
        'label'   => __('Title Main', 'my-business-theme'),
        'section' => 'mbt_services_features',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_services_title_accent', [
        'default'           => 'Cabinetry Solutions',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_services_title_accent', [
        'label'   => __('Title Accent', 'my-business-theme'),
        'section' => 'mbt_services_features',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_services_intro', [
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('mbt_services_intro', [
        'label'   => __('Intro Text (optional)', 'my-business-theme'),
        'section' => 'mbt_services_features',
        'type'    => 'textarea',
    ]);

    $wp_customize->add_setting('mbt_services_highlight_color', [
        'default'           => '#c8ab6e',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mbt_services_highlight_color', [
        'label'       => __('Highlight Color', 'my-business-theme'),
        'description' => __('Used for the active and hover card border, glow, and bottom pointer.', 'my-business-theme'),
        'section'     => 'mbt_services_features',
    ]));

    $wp_customize->add_setting('mbt_services_featured_item', [
        'default'           => 0,
        'sanitize_callback' => 'mbt_sanitize_service_feature_index',
    ]);
    $wp_customize->add_control('mbt_services_featured_item', [
        'label'       => __('Default Highlighted Card', 'my-business-theme'),
        'description' => __('Set to 0 for none, or choose which card is highlighted by default.', 'my-business-theme'),
        'section'     => 'mbt_services_features',
        'type'        => 'number',
        'input_attrs' => ['min' => 0, 'max' => 20, 'step' => 1],
    ]);

    $service_defaults = [
        1 => [
            'title' => 'Fast & Reliable Local Service',
            'text'  => 'Melbourne-based electricians delivering quick response times, clear communication, and on-time arrivals you can count on.',
        ],
        2 => [
            'title' => 'Clear Pricing. Proven Professionalism',
            'text'  => 'Up-front fixed pricing with no surprises. We follow strict safety standards to ensure every job is completed correctly and safely.',
        ],
        3 => [
            'title' => 'Licensed Experts You Can Trust',
            'text'  => 'Fully licensed and experienced professionals providing compliant, high-quality workmanship with long-lasting results.',
        ],
    ];

    for ($index = 1; $index <= 6; $index++) {
        $defaults = $service_defaults[$index] ?? ['title' => '', 'text' => ''];

        $wp_customize->add_setting('mbt_services_item_' . $index . '_title', [
            'default'           => $defaults['title'],
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control('mbt_services_item_' . $index . '_title', [
            'label'   => sprintf(__('Card %d Title', 'my-business-theme'), $index),
            'section' => 'mbt_services_features',
            'type'    => 'text',
        ]);

        $wp_customize->add_setting('mbt_services_item_' . $index . '_text', [
            'default'           => $defaults['text'],
            'sanitize_callback' => 'wp_kses_post',
        ]);
        $wp_customize->add_control('mbt_services_item_' . $index . '_text', [
            'label'   => sprintf(__('Card %d Text', 'my-business-theme'), $index),
            'section' => 'mbt_services_features',
            'type'    => 'textarea',
        ]);

        $wp_customize->add_setting('mbt_services_item_' . $index . '_url', [
            'default'           => '',
            'sanitize_callback' => 'mbt_sanitize_header_link_target',
        ]);
        $wp_customize->add_control('mbt_services_item_' . $index . '_url', [
            'label'       => sprintf(__('Card %d Link (optional)', 'my-business-theme'), $index),
            'description' => __('If set, the whole card becomes clickable.', 'my-business-theme'),
            'section'     => 'mbt_services_features',
            'type'        => 'text',
        ]);

        $wp_customize->add_setting('mbt_services_item_' . $index . '_image', [
            'default'           => '',
            'sanitize_callback' => 'absint',
        ]);
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'mbt_services_item_' . $index . '_image', [
            'label'      => sprintf(__('Card %d Image', 'my-business-theme'), $index),
            'section'    => 'mbt_services_features',
            'mime_type'  => 'image',
        ]));
    }

    $wp_customize->add_section('mbt_capabilities_carousel', [
        'title'       => __('Our Services Carousel', 'my-business-theme'),
        'description' => __('Manage the full-width service card carousel shown below Service Features. The carousel automatically pulls all Service posts, so the card count is not capped here. If there are no Service posts, bundled demo cards with service1.jpg to service6.jpg are shown instead.', 'my-business-theme'),
        'panel'       => 'mbt_theme_options',
    ]);

    $wp_customize->add_setting('mbt_capabilities_kicker', [
        'default'           => 'Our Services',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_capabilities_kicker', [
        'label'   => __('Kicker', 'my-business-theme'),
        'section' => 'mbt_capabilities_carousel',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_capabilities_title_main', [
        'default'           => 'Explore Our Luxury',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_capabilities_title_main', [
        'label'   => __('Title Main', 'my-business-theme'),
        'section' => 'mbt_capabilities_carousel',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_capabilities_title_accent', [
        'default'           => 'Cabinetry Solution',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_capabilities_title_accent', [
        'label'   => __('Title Accent', 'my-business-theme'),
        'section' => 'mbt_capabilities_carousel',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_capabilities_primary_text', [
        'default'           => 'Get a Quote',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_capabilities_primary_text', [
        'label'   => __('Primary Button Text', 'my-business-theme'),
        'section' => 'mbt_capabilities_carousel',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_capabilities_primary_type', [
        'default'           => 'popup',
        'sanitize_callback' => 'mbt_sanitize_header_button_type',
    ]);
    $wp_customize->add_control('mbt_capabilities_primary_type', [
        'label'   => __('Primary Button Behavior', 'my-business-theme'),
        'section' => 'mbt_capabilities_carousel',
        'type'    => 'select',
        'choices' => [
            'popup' => __('Open popup form', 'my-business-theme'),
            'link'  => __('Open link', 'my-business-theme'),
        ],
    ]);

    $wp_customize->add_setting('mbt_capabilities_primary_url', [
        'default'           => '#contact',
        'sanitize_callback' => 'mbt_sanitize_header_link_target',
    ]);
    $wp_customize->add_control('mbt_capabilities_primary_url', [
        'label'       => __('Primary Button URL', 'my-business-theme'),
        'description' => __('Used only when the primary button behavior is set to Open link.', 'my-business-theme'),
        'section'     => 'mbt_capabilities_carousel',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_capabilities_show_phone', [
        'default'           => true,
        'sanitize_callback' => 'mbt_sanitize_checkbox',
    ]);
    $wp_customize->add_control('mbt_capabilities_show_phone', [
        'label'   => __('Show Phone Button', 'my-business-theme'),
        'section' => 'mbt_capabilities_carousel',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting('mbt_capabilities_card_button_text', [
        'default'           => 'Learn More',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_capabilities_card_button_text', [
        'label'       => __('Card Button Text', 'my-business-theme'),
        'description' => __('Used on every service card unless a Service post has its own Button Text filled in.', 'my-business-theme'),
        'section'     => 'mbt_capabilities_carousel',
        'type'        => 'text',
    ]);

    $wp_customize->add_section('mbt_about_business', [
        'title'       => __('About the Business', 'my-business-theme'),
        'description' => __('Manage the split About section with the badge, heading, image, body copy, and CTA buttons.', 'my-business-theme'),
        'panel'       => 'mbt_theme_options',
    ]);

    $wp_customize->add_setting('mbt_about_kicker', [
        'default'           => 'About the Business',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_about_kicker', [
        'label'   => __('Kicker', 'my-business-theme'),
        'section' => 'mbt_about_business',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_about_title', [
        'default'           => 'Where Space Inspire',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_about_title', [
        'label'   => __('Title Main', 'my-business-theme'),
        'section' => 'mbt_about_business',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_about_title_accent', [
        'default'           => 'and Design Come Alive',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_about_title_accent', [
        'label'   => __('Title Accent', 'my-business-theme'),
        'section' => 'mbt_about_business',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_about_text', [
        'default'           => "Lumi Melbourne Cabinets delivers premium bespoke cabinetry across Melbourne's southern suburbs.\n\nFrom kitchens and bathrooms to wardrobes, home offices and garage storage, each space is thoughtfully designed for refined aesthetics, practical function and lasting quality.\n\nLed by Martin with over fifteen years of hands-on experience, Lumi offers a transparent, collaborative process with clear communication and precise workmanship.",
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('mbt_about_text', [
        'label'       => __('Body Text', 'my-business-theme'),
        'description' => __('Use blank lines to create separate paragraphs.', 'my-business-theme'),
        'section'     => 'mbt_about_business',
        'type'        => 'textarea',
    ]);

    $wp_customize->add_setting('mbt_about_button_text', [
        'default'           => 'GET A QUOTE',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_about_button_text', [
        'label'   => __('Primary Button Text', 'my-business-theme'),
        'section' => 'mbt_about_business',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_about_button_type', [
        'default'           => 'popup',
        'sanitize_callback' => 'mbt_sanitize_header_button_type',
    ]);
    $wp_customize->add_control('mbt_about_button_type', [
        'label'   => __('Primary Button Behavior', 'my-business-theme'),
        'section' => 'mbt_about_business',
        'type'    => 'select',
        'choices' => [
            'popup' => __('Open popup form', 'my-business-theme'),
            'link'  => __('Open link', 'my-business-theme'),
        ],
    ]);

    $wp_customize->add_setting('mbt_about_button_url', [
        'default'           => '#contact',
        'sanitize_callback' => 'mbt_sanitize_header_link_target',
    ]);
    $wp_customize->add_control('mbt_about_button_url', [
        'label'       => __('Primary Button URL', 'my-business-theme'),
        'description' => __('Used only when the primary button behavior is set to Open link.', 'my-business-theme'),
        'section'     => 'mbt_about_business',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_about_show_phone', [
        'default'           => true,
        'sanitize_callback' => 'mbt_sanitize_checkbox',
    ]);
    $wp_customize->add_control('mbt_about_show_phone', [
        'label'   => __('Show Phone Button', 'my-business-theme'),
        'section' => 'mbt_about_business',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting('mbt_about_image', [
        'default'           => '',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'mbt_about_image', [
        'label'      => __('About Image', 'my-business-theme'),
        'section'    => 'mbt_about_business',
        'mime_type'  => 'image',
    ]));

    $wp_customize->add_setting('mbt_about_image_alt', [
        'default'           => 'Custom cabinetry interior',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_about_image_alt', [
        'label'   => __('About Image Alt Text', 'my-business-theme'),
        'section' => 'mbt_about_business',
        'type'    => 'text',
    ]);

    $wp_customize->add_section('mbt_portfolio_showcase', [
        'title'       => __('Portfolio', 'my-business-theme'),
        'description' => __('Manage the portfolio section heading and intro here. The gallery itself pulls from Portfolio posts and falls back to bundled demo images when there are no portfolio items yet.', 'my-business-theme'),
        'panel'       => 'mbt_theme_options',
    ]);

    $wp_customize->add_setting('mbt_portfolio_kicker', [
        'default'           => 'Our Recent Projects',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_portfolio_kicker', [
        'label'   => __('Kicker', 'my-business-theme'),
        'section' => 'mbt_portfolio_showcase',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_portfolio_title_main', [
        'default'           => 'Our Portfolio Of Work',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_portfolio_title_main', [
        'label'   => __('Title Main', 'my-business-theme'),
        'section' => 'mbt_portfolio_showcase',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_portfolio_title_accent', [
        'default'           => 'To Boast About',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_portfolio_title_accent', [
        'label'   => __('Title Accent', 'my-business-theme'),
        'section' => 'mbt_portfolio_showcase',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_portfolio_intro', [
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('mbt_portfolio_intro', [
        'label'       => __('Intro Text (optional)', 'my-business-theme'),
        'description' => __('Leave blank to match the cleaner mosaic reference layout.', 'my-business-theme'),
        'section'     => 'mbt_portfolio_showcase',
        'type'        => 'textarea',
    ]);

    $wp_customize->add_section('mbt_testimonials_showcase', [
        'title'       => __('Testimonials', 'my-business-theme'),
        'description' => __('Manage the testimonials section heading and review label here. The testimonial cards pull from Testimonials posts and fall back to bundled demo reviews when there are no posts yet.', 'my-business-theme'),
        'panel'       => 'mbt_theme_options',
    ]);

    $wp_customize->add_setting('mbt_testimonials_kicker', [
        'default'           => 'Our Testimonials',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_testimonials_kicker', [
        'label'   => __('Kicker', 'my-business-theme'),
        'section' => 'mbt_testimonials_showcase',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_testimonials_title_main', [
        'default'           => 'Client Experiences With',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_testimonials_title_main', [
        'label'   => __('Title Main', 'my-business-theme'),
        'section' => 'mbt_testimonials_showcase',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_testimonials_title_accent', [
        'default'           => 'Lumi Cabinets',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_testimonials_title_accent', [
        'label'   => __('Title Accent', 'my-business-theme'),
        'section' => 'mbt_testimonials_showcase',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_testimonials_review_source', [
        'default'           => 'Based on Google Review',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_testimonials_review_source', [
        'label'   => __('Review Source Label', 'my-business-theme'),
        'section' => 'mbt_testimonials_showcase',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_testimonials_reviews', [
        'default'           => '',
        'sanitize_callback' => 'mbt_sanitize_testimonials_repeater',
    ]);
    $wp_customize->add_control(new MBT_Testimonial_Repeater_Control($wp_customize, 'mbt_testimonials_reviews', [
        'label'       => __('Reviews', 'my-business-theme'),
        'description' => __('Add as many reviews as you need, with name, source label, review text, and star rating.', 'my-business-theme'),
        'section'     => 'mbt_testimonials_showcase',
    ]));

    $wp_customize->add_section('mbt_about_owner', [
        'title'       => __('About Owner', 'my-business-theme'),
        'description' => __('Manage the owner section content, portrait, bottom frame image, and signature here.', 'my-business-theme'),
        'panel'       => 'mbt_theme_options',
    ]);

    $wp_customize->add_setting('mbt_owner_kicker', [
        'default'           => 'About Owner',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_owner_kicker', [
        'label'   => __('Kicker', 'my-business-theme'),
        'section' => 'mbt_about_owner',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_owner_title_main', [
        'default'           => 'The Experience and',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_owner_title_main', [
        'label'   => __('Title Main', 'my-business-theme'),
        'section' => 'mbt_about_owner',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_owner_title_accent', [
        'default'           => 'Values Behind Lumi',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_owner_title_accent', [
        'label'   => __('Title Accent', 'my-business-theme'),
        'section' => 'mbt_about_owner',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_owner_name', [
        'default'           => 'Martin',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_owner_name', [
        'label'   => __('Owner Name', 'my-business-theme'),
        'section' => 'mbt_about_owner',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_owner_text', [
        'default'           => "Martin, owner and cabinet maker at Lumi Melbourne Cabinets, brings over fifteen years of hands-on experience to every project.\n\nHe takes a personal, owner-led approach from initial consultation to final installation, focused on clear communication, honest advice and reliable timelines.\n\nWorking closely with homeowners, Martin delivers thoughtfully designed cabinetry that balances form, function and durability.",
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('mbt_owner_text', [
        'label'       => __('Body Text', 'my-business-theme'),
        'description' => __('Use blank lines to create separate paragraphs.', 'my-business-theme'),
        'section'     => 'mbt_about_owner',
        'type'        => 'textarea',
    ]);

    $wp_customize->add_setting('mbt_owner_signature_caption', [
        'default'           => 'LUMI MELBOURNE CABINETS',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_owner_signature_caption', [
        'label'   => __('Signature Caption', 'my-business-theme'),
        'section' => 'mbt_about_owner',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_owner_section_photo', [
        'default'           => '',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'mbt_owner_section_photo', [
        'label'      => __('Owner Portrait Image', 'my-business-theme'),
        'section'    => 'mbt_about_owner',
        'mime_type'  => 'image',
    ]));

    $wp_customize->add_setting('mbt_owner_image_alt', [
        'default'           => 'Martin from Lumi Melbourne Cabinets',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_owner_image_alt', [
        'label'   => __('Portrait Alt Text', 'my-business-theme'),
        'section' => 'mbt_about_owner',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_owner_section_position_x', [
        'default'           => '50%',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_owner_section_position_x', [
        'label'       => __('Portrait Position X', 'my-business-theme'),
        'description' => __('Example: 50%, center, left', 'my-business-theme'),
        'section'     => 'mbt_about_owner',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_owner_section_position_y', [
        'default'           => '20%',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_owner_section_position_y', [
        'label'       => __('Portrait Position Y', 'my-business-theme'),
        'description' => __('Example: 20%, center, top', 'my-business-theme'),
        'section'     => 'mbt_about_owner',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_owner_section_background_image', [
        'default'           => '',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'mbt_owner_section_background_image', [
        'label'      => __('Background Image', 'my-business-theme'),
        'section'    => 'mbt_about_owner',
        'mime_type'  => 'image',
    ]));

    $wp_customize->add_setting('mbt_owner_signature_image', [
        'default'           => '',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'mbt_owner_signature_image', [
        'label'      => __('Signature Image', 'my-business-theme'),
        'section'    => 'mbt_about_owner',
        'mime_type'  => 'image',
    ]));

    $wp_customize->add_section('mbt_contact_section', [
        'title'       => __('Contact & Map', 'my-business-theme'),
        'description' => __('Manage the text and Google Map section shown after About Owner. Use blank lines in the body text to create longer paragraph groups like the other editorial sections.', 'my-business-theme'),
        'panel'       => 'mbt_theme_options',
    ]);

    $wp_customize->add_setting('mbt_contact_kicker', [
        'default'           => 'About Area',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_contact_kicker', [
        'label'   => __('Kicker', 'my-business-theme'),
        'section' => 'mbt_contact_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_contact_title', [
        'default'           => 'About',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_contact_title', [
        'label'   => __('Title Main', 'my-business-theme'),
        'section' => 'mbt_contact_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_contact_title_accent', [
        'default'           => 'Melbourne',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_contact_title_accent', [
        'label'   => __('Title Accent', 'my-business-theme'),
        'section' => 'mbt_contact_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_contact_text', [
        'default'           => "Lumi Melbourne Cabinets proudly services the southern suburbs of Melbourne, delivering premium custom cabinetry for residential homes. We work across areas including the Mornington Peninsula, Bayside and surrounding suburbs, partnering with homeowners who value quality craftsmanship, clear communication and considered design.",
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('mbt_contact_text', [
        'label'       => __('Body Text', 'my-business-theme'),
        'description' => __('Use blank lines to create separate paragraphs.', 'my-business-theme'),
        'section'     => 'mbt_contact_section',
        'type'        => 'textarea',
    ]);

    $wp_customize->add_setting('mbt_contact_link_text', [
        'default'           => 'Expand',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_contact_link_text', [
        'label'   => __('Expand Link Text', 'my-business-theme'),
        'section' => 'mbt_contact_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_contact_link_url', [
        'default'           => '#service-areas',
        'sanitize_callback' => 'mbt_sanitize_header_link_target',
    ]);
    $wp_customize->add_control('mbt_contact_link_url', [
        'label'       => __('Expand Link URL', 'my-business-theme'),
        'description' => __('Used for the small text link below the body copy.', 'my-business-theme'),
        'section'     => 'mbt_contact_section',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_contact_button_text', [
        'default'           => 'Get a quote',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_contact_button_text', [
        'label'   => __('Primary Button Text', 'my-business-theme'),
        'section' => 'mbt_contact_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_contact_button_type', [
        'default'           => 'popup',
        'sanitize_callback' => 'mbt_sanitize_header_button_type',
    ]);
    $wp_customize->add_control('mbt_contact_button_type', [
        'label'   => __('Primary Button Behavior', 'my-business-theme'),
        'section' => 'mbt_contact_section',
        'type'    => 'select',
        'choices' => [
            'popup' => __('Open popup form', 'my-business-theme'),
            'link'  => __('Open link', 'my-business-theme'),
        ],
    ]);

    $wp_customize->add_setting('mbt_contact_button_url', [
        'default'           => '#contact',
        'sanitize_callback' => 'mbt_sanitize_header_link_target',
    ]);
    $wp_customize->add_control('mbt_contact_button_url', [
        'label'       => __('Primary Button URL', 'my-business-theme'),
        'description' => __('Used only when the primary button behavior is set to Open link.', 'my-business-theme'),
        'section'     => 'mbt_contact_section',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_contact_map_embed', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('mbt_contact_map_embed', [
        'label'       => __('Google Map Embed URL', 'my-business-theme'),
        'description' => __('Paste the Google Maps embed URL or iframe src. Leave blank to use the default Melbourne map.', 'my-business-theme'),
        'section'     => 'mbt_contact_section',
        'type'        => 'url',
    ]);

    $wp_customize->add_section('mbt_process_section', [
        'title'       => __('Process', 'my-business-theme'),
        'description' => __('Manage the process heading, CTA buttons, and up to six process cards shown on the homepage.', 'my-business-theme'),
        'panel'       => 'mbt_theme_options',
    ]);

    $wp_customize->add_setting('mbt_process_kicker', [
        'default'           => 'Our Process',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_process_kicker', [
        'label'   => __('Kicker', 'my-business-theme'),
        'section' => 'mbt_process_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_process_title_main', [
        'default'           => 'How We Bring Your',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_process_title_main', [
        'label'   => __('Title Main', 'my-business-theme'),
        'section' => 'mbt_process_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_process_title_accent', [
        'default'           => 'Cabinetry to Life',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_process_title_accent', [
        'label'   => __('Title Accent', 'my-business-theme'),
        'section' => 'mbt_process_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_process_button_text', [
        'default'           => 'GET A QUOTE',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_process_button_text', [
        'label'   => __('Primary Button Text', 'my-business-theme'),
        'section' => 'mbt_process_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_process_button_type', [
        'default'           => 'popup',
        'sanitize_callback' => 'mbt_sanitize_header_button_type',
    ]);
    $wp_customize->add_control('mbt_process_button_type', [
        'label'   => __('Primary Button Behavior', 'my-business-theme'),
        'section' => 'mbt_process_section',
        'type'    => 'select',
        'choices' => [
            'popup' => __('Open popup form', 'my-business-theme'),
            'link'  => __('Open link', 'my-business-theme'),
        ],
    ]);

    $wp_customize->add_setting('mbt_process_button_url', [
        'default'           => '#contact',
        'sanitize_callback' => 'mbt_sanitize_header_link_target',
    ]);
    $wp_customize->add_control('mbt_process_button_url', [
        'label'       => __('Primary Button URL', 'my-business-theme'),
        'description' => __('Used only when the primary button behavior is set to Open link.', 'my-business-theme'),
        'section'     => 'mbt_process_section',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_process_show_phone', [
        'default'           => true,
        'sanitize_callback' => 'mbt_sanitize_checkbox',
    ]);
    $wp_customize->add_control('mbt_process_show_phone', [
        'label'   => __('Show Phone Button', 'my-business-theme'),
        'section' => 'mbt_process_section',
        'type'    => 'checkbox',
    ]);

    $process_defaults = [
        1 => [
            'title' => 'Consultation & Site Measure',
            'text'  => 'We take time to understand your home, lifestyle and vision, developing a tailored cabinetry concept with clear guidance and transparent pricing.',
        ],
        2 => [
            'title' => 'Custom Build & Installation',
            'text'  => 'Your cabinetry is precision-crafted and expertly installed to ensure a seamless fit, refined finish and flawless day-to-day functionality.',
        ],
        3 => [
            'title' => 'Final Detailing & Handover',
            'text'  => 'We complete meticulous final checks, walk you through the finished space and remain available for ongoing support when required.',
        ],
        4 => [
            'title' => '',
            'text'  => '',
        ],
        5 => [
            'title' => '',
            'text'  => '',
        ],
        6 => [
            'title' => '',
            'text'  => '',
        ],
    ];

    foreach ($process_defaults as $index => $process_default) {
        $wp_customize->add_setting('mbt_process_step_' . $index . '_title', [
            'default'           => $process_default['title'],
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control('mbt_process_step_' . $index . '_title', [
            'label'   => sprintf(__('Step %d Title', 'my-business-theme'), $index),
            'section' => 'mbt_process_section',
            'type'    => 'text',
        ]);

        $wp_customize->add_setting('mbt_process_step_' . $index . '_text', [
            'default'           => $process_default['text'],
            'sanitize_callback' => 'wp_kses_post',
        ]);
        $wp_customize->add_control('mbt_process_step_' . $index . '_text', [
            'label'   => sprintf(__('Step %d Text', 'my-business-theme'), $index),
            'section' => 'mbt_process_section',
            'type'    => 'textarea',
        ]);
    }

    $wp_customize->add_section('mbt_cta_form_section', [
        'title'       => __('Get In Touch CTA', 'my-business-theme'),
        'description' => __('Manage the large contact CTA band, including its title, availability text, blinking status dot color, background image, and form placeholders.', 'my-business-theme'),
        'panel'       => 'mbt_theme_options',
    ]);

    $wp_customize->add_setting('mbt_cta_title', [
        'default'           => 'Get In Touch With The Team Today.',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_cta_title', [
        'label'   => __('Title', 'my-business-theme'),
        'section' => 'mbt_cta_form_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_cta_status_text', [
        'default'           => "We're available now!",
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_cta_status_text', [
        'label'   => __('Status Text', 'my-business-theme'),
        'section' => 'mbt_cta_form_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_cta_status_color', [
        'default'           => '#7df33b',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mbt_cta_status_color', [
        'label'       => __('Status Dot Color', 'my-business-theme'),
        'description' => __('Used for the blinking availability dot.', 'my-business-theme'),
        'section'     => 'mbt_cta_form_section',
    ]));

    $wp_customize->add_setting('mbt_cta_background_image', [
        'default'           => '',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'mbt_cta_background_image', [
        'label'      => __('Background Image', 'my-business-theme'),
        'section'    => 'mbt_cta_form_section',
        'mime_type'  => 'image',
    ]));

    $wp_customize->add_setting('mbt_cta_name_placeholder', [
        'default'           => 'Enter name',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_cta_name_placeholder', [
        'label'   => __('Name Field Placeholder', 'my-business-theme'),
        'section' => 'mbt_cta_form_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_cta_phone_placeholder', [
        'default'           => 'Enter phone',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_cta_phone_placeholder', [
        'label'   => __('Phone Field Placeholder', 'my-business-theme'),
        'section' => 'mbt_cta_form_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_cta_email_placeholder', [
        'default'           => 'Enter email',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_cta_email_placeholder', [
        'label'   => __('Email Field Placeholder', 'my-business-theme'),
        'section' => 'mbt_cta_form_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_cta_message_placeholder', [
        'default'           => 'How can we help?',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_cta_message_placeholder', [
        'label'   => __('Message Field Placeholder', 'my-business-theme'),
        'section' => 'mbt_cta_form_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_cta_button_text', [
        'default'           => 'GET A QUOTE',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_cta_button_text', [
        'label'   => __('Button Text', 'my-business-theme'),
        'section' => 'mbt_cta_form_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_cta_button_type', [
        'default'           => 'popup',
        'sanitize_callback' => 'mbt_sanitize_header_button_type',
    ]);
    $wp_customize->add_control('mbt_cta_button_type', [
        'label'   => __('Button Behavior', 'my-business-theme'),
        'section' => 'mbt_cta_form_section',
        'type'    => 'select',
        'choices' => [
            'popup' => __('Open popup form', 'my-business-theme'),
            'link'  => __('Open link', 'my-business-theme'),
        ],
    ]);

    $wp_customize->add_setting('mbt_cta_button_url', [
        'default'           => '#contact',
        'sanitize_callback' => 'mbt_sanitize_header_link_target',
    ]);
    $wp_customize->add_control('mbt_cta_button_url', [
        'label'       => __('Button URL', 'my-business-theme'),
        'description' => __('Used only when the CTA button behavior is set to Open link.', 'my-business-theme'),
        'section'     => 'mbt_cta_form_section',
        'type'        => 'text',
    ]);

    $wp_customize->add_section('mbt_faq_section', [
        'title'       => __('FAQ', 'my-business-theme'),
        'description' => __('Manage the FAQ intro content here. The accordion items still pull from FAQ posts and fall back to bundled demo questions when there are no FAQ entries yet.', 'my-business-theme'),
        'panel'       => 'mbt_theme_options',
    ]);

    $wp_customize->add_setting('mbt_faq_kicker', [
        'default'           => "FAQ'S",
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_faq_kicker', [
        'label'   => __('Kicker', 'my-business-theme'),
        'section' => 'mbt_faq_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_faq_title_main', [
        'default'           => 'Your Questions,',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_faq_title_main', [
        'label'   => __('Title Main', 'my-business-theme'),
        'section' => 'mbt_faq_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_faq_title_accent', [
        'default'           => 'Answered',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_faq_title_accent', [
        'label'   => __('Title Accent', 'my-business-theme'),
        'section' => 'mbt_faq_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_faq_intro', [
        'default'           => 'Choose a proven team that delivers dependable results, clear communication, and work done right the first time.',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('mbt_faq_intro', [
        'label'   => __('Intro Text', 'my-business-theme'),
        'section' => 'mbt_faq_section',
        'type'    => 'textarea',
    ]);

    $wp_customize->add_setting('mbt_faq_button_text', [
        'default'           => 'GET A QUOTE',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_faq_button_text', [
        'label'   => __('Primary Button Text', 'my-business-theme'),
        'section' => 'mbt_faq_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_faq_button_type', [
        'default'           => 'popup',
        'sanitize_callback' => 'mbt_sanitize_header_button_type',
    ]);
    $wp_customize->add_control('mbt_faq_button_type', [
        'label'   => __('Primary Button Behavior', 'my-business-theme'),
        'section' => 'mbt_faq_section',
        'type'    => 'select',
        'choices' => [
            'popup' => __('Open popup form', 'my-business-theme'),
            'link'  => __('Open link', 'my-business-theme'),
        ],
    ]);

    $wp_customize->add_setting('mbt_faq_button_url', [
        'default'           => '#contact',
        'sanitize_callback' => 'mbt_sanitize_header_link_target',
    ]);
    $wp_customize->add_control('mbt_faq_button_url', [
        'label'       => __('Primary Button URL', 'my-business-theme'),
        'description' => __('Used only when the primary button behavior is set to Open link.', 'my-business-theme'),
        'section'     => 'mbt_faq_section',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_faq_show_phone', [
        'default'           => true,
        'sanitize_callback' => 'mbt_sanitize_checkbox',
    ]);
    $wp_customize->add_control('mbt_faq_show_phone', [
        'label'   => __('Show Phone Button', 'my-business-theme'),
        'section' => 'mbt_faq_section',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_section('mbt_service_areas_section', [
        'title'       => __('Service Areas', 'my-business-theme'),
        'description' => __('Manage the dark service area section heading and area chip list. Use one area per line.', 'my-business-theme'),
        'panel'       => 'mbt_theme_options',
    ]);

    $wp_customize->add_setting('mbt_service_areas_kicker', [
        'default'           => 'Our Service Area',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_service_areas_kicker', [
        'label'   => __('Kicker', 'my-business-theme'),
        'section' => 'mbt_service_areas_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_service_areas_title_main', [
        'default'           => 'Proudly Servicing',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_service_areas_title_main', [
        'label'   => __('Title Main', 'my-business-theme'),
        'section' => 'mbt_service_areas_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_service_areas_title_accent', [
        'default'           => 'Melbourne Areas',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_service_areas_title_accent', [
        'label'   => __('Title Accent', 'my-business-theme'),
        'section' => 'mbt_service_areas_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_service_areas_background_image', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'mbt_service_areas_background_image', [
        'label'       => __('Background Image', 'my-business-theme'),
        'description' => __('Used behind the dark service areas section. Defaults to Rectangle 34625480.png.', 'my-business-theme'),
        'section'     => 'mbt_service_areas_section',
        'mime_type'   => 'image',
    ]));

    $wp_customize->add_setting('mbt_service_areas_list', [
        'default'           => "Southern Melbourne\nChelsea Heights\nEdithvale\nHampton\nAspendale\nChelsea\nMentone\nMoorabbin\nParkdale\nBrighton\nCheltenham\nBraeside",
        'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('mbt_service_areas_list', [
        'label'       => __('Area List', 'my-business-theme'),
        'description' => __('Enter one service area per line.', 'my-business-theme'),
        'section'     => 'mbt_service_areas_section',
        'type'        => 'textarea',
    ]);

    $wp_customize->add_section('mbt_footer_section', [
        'title'       => __('Footer', 'my-business-theme'),
        'description' => __('Manage the footer background, brand text, services column, and bottom credit line.', 'my-business-theme'),
        'panel'       => 'mbt_theme_options',
    ]);

    $wp_customize->add_setting('mbt_footer_background_image', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'mbt_footer_background_image', [
        'label'       => __('Background Image', 'my-business-theme'),
        'description' => __('Used behind the dark footer. Defaults to Rectangle 34625466.png.', 'my-business-theme'),
        'section'     => 'mbt_footer_section',
        'mime_type'   => 'image',
    ]));

    $wp_customize->add_setting('mbt_footer_text', [
        'default'           => 'Premium custom cabinetry and joinery for kitchens, laundries, wardrobes, vanities, and whole-home interiors across Melbourne.',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('mbt_footer_text', [
        'label'   => __('Intro Text', 'my-business-theme'),
        'section' => 'mbt_footer_section',
        'type'    => 'textarea',
    ]);

    $wp_customize->add_setting('mbt_footer_services_title', [
        'default'           => 'Services',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_footer_services_title', [
        'label'   => __('Services Column Title', 'my-business-theme'),
        'section' => 'mbt_footer_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_footer_services_list', [
        'default'           => "Cabinet Maker\nKitchen Cabinet Maker\nBathroom Cabinetry\nCustom Wardrobes\nHome Office Cabinetry\nGarage Storage Cabinets",
        'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('mbt_footer_services_list', [
        'label'       => __('Services List', 'my-business-theme'),
        'description' => __('Fallback only. Used when there are no published Services entries yet. Enter one service per line.', 'my-business-theme'),
        'section'     => 'mbt_footer_section',
        'type'        => 'textarea',
    ]);

    $wp_customize->add_setting('mbt_footer_contact_title', [
        'default'           => 'Contact Info',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_footer_contact_title', [
        'label'   => __('Contact Column Title', 'my-business-theme'),
        'section' => 'mbt_footer_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_footer_copyright_text', [
        'default'           => 'Copyright © {year} - {business_name}',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_footer_copyright_text', [
        'label'       => __('Bottom Copyright Text', 'my-business-theme'),
        'description' => __('You can use {year} and {business_name} as placeholders.', 'my-business-theme'),
        'section'     => 'mbt_footer_section',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('mbt_footer_credit_text', [
        'default'           => 'Made with 💪 by UpRank',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mbt_footer_credit_text', [
        'label'   => __('Bottom Credit Text', 'my-business-theme'),
        'section' => 'mbt_footer_section',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('mbt_footer_credit_url', [
        'default'           => '',
        'sanitize_callback' => 'mbt_sanitize_header_link_target',
    ]);
    $wp_customize->add_control('mbt_footer_credit_url', [
        'label'       => __('Bottom Credit URL', 'my-business-theme'),
        'description' => __('Optional. Leave empty to show the credit as plain text.', 'my-business-theme'),
        'section'     => 'mbt_footer_section',
        'type'        => 'text',
    ]);

    if ($wp_customize->get_setting('mbt_footer_copyright_text')) {
        $wp_customize->get_setting('mbt_footer_copyright_text')->default = 'Copyright {year} - {business_name}';
    }

    if ($wp_customize->get_setting('mbt_footer_credit_text')) {
        $wp_customize->get_setting('mbt_footer_credit_text')->default = 'Made with care by UpRank';
    }

    $wp_customize->add_section('mbt_homepage_content', [
        'title' => __('Homepage Content', 'my-business-theme'),
        'panel' => 'mbt_theme_options',
    ]);

    $home_fields = [];

    foreach ($home_fields as $key => [$label, $default]) {
        $wp_customize->add_setting($key, [
            'default'           => $default,
            'sanitize_callback' => 'wp_kses_post',
        ]);
        $wp_customize->add_control($key, [
            'label'   => __($label, 'my-business-theme'),
            'section' => 'mbt_homepage_content',
            'type'    => ($key === 'mbt_owner_text' || $key === 'mbt_contact_text' || $key === 'mbt_footer_text' || $key === 'mbt_services_text') ? 'textarea' : 'text',
        ]);
    }
}
add_action('customize_register', 'mbt_customize_register');

function mbt_customize_controls_assets(): void
{
    wp_enqueue_style('mbt-customizer-controls', MBT_URI . '/assets/css/customizer-controls.css', [], MBT_VERSION);
    wp_enqueue_script('mbt-customizer-controls', MBT_URI . '/assets/js/customizer-controls.js', ['customize-controls'], MBT_VERSION, true);
}
add_action('customize_controls_enqueue_scripts', 'mbt_customize_controls_assets');

function mbt_font_choices(): array
{
    return [
        'Cormorant Garamond' => 'Cormorant Garamond',
        'Manrope'            => 'Manrope',
        'Inter'              => 'Inter',
        'Montserrat'         => 'Montserrat',
        'Playfair Display'   => 'Playfair Display',
        'Open Sans'          => 'Open Sans',
        'Lora'               => 'Lora',
        'Poppins'            => 'Poppins',
        'DM Sans'            => 'DM Sans',
    ];
}
