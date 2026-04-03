<?php
if (!defined('ABSPATH')) {
    exit;
}

function mbt_add_meta_boxes(): void
{
    add_meta_box('mbt_service_meta', __('Service Details', 'my-business-theme'), 'mbt_render_service_meta_box', 'mbt_service', 'normal', 'default');
    add_meta_box('mbt_portfolio_meta', __('Portfolio Details', 'my-business-theme'), 'mbt_render_portfolio_meta_box', 'mbt_portfolio', 'normal', 'default');
    add_meta_box('mbt_testimonial_meta', __('Testimonial Details', 'my-business-theme'), 'mbt_render_testimonial_meta_box', 'mbt_testimonial', 'normal', 'default');
    add_meta_box('mbt_partner_meta', __('Partner Logo Details', 'my-business-theme'), 'mbt_render_partner_meta_box', 'mbt_partner', 'normal', 'default');
}
add_action('add_meta_boxes', 'mbt_add_meta_boxes');

function mbt_render_service_meta_box(WP_Post $post): void
{
    wp_nonce_field('mbt_save_meta', 'mbt_meta_nonce');
    $button_text = mbt_get_meta($post->ID, '_mbt_button_text');
    $button_url  = mbt_get_meta($post->ID, '_mbt_button_url');
    echo '<p><label>' . esc_html__('Button Text', 'my-business-theme') . '<br><input class="widefat" name="mbt_button_text" value="' . esc_attr($button_text) . '"></label></p>';
    echo '<p><label>' . esc_html__('Button URL', 'my-business-theme') . '<br><input class="widefat" name="mbt_button_url" value="' . esc_attr($button_url) . '"></label></p>';
    echo '<p>' . esc_html__('Use featured image for the card image and excerpt for the summary text.', 'my-business-theme') . '</p>';
}

function mbt_render_portfolio_meta_box(WP_Post $post): void
{
    wp_nonce_field('mbt_save_meta', 'mbt_meta_nonce');
    $project_url = mbt_get_meta($post->ID, '_mbt_project_url');
    echo '<p><label>' . esc_html__('Project URL', 'my-business-theme') . '<br><input class="widefat" name="mbt_project_url" value="' . esc_attr($project_url) . '"></label></p>';
    echo '<p>' . esc_html__('Use featured image for the portfolio thumbnail.', 'my-business-theme') . '</p>';
}

function mbt_render_testimonial_meta_box(WP_Post $post): void
{
    wp_nonce_field('mbt_save_meta', 'mbt_meta_nonce');
    $role   = mbt_get_meta($post->ID, '_mbt_client_role');
    $rating = mbt_get_meta($post->ID, '_mbt_rating', '5');
    echo '<p><label>' . esc_html__('Client Role / Company', 'my-business-theme') . '<br><input class="widefat" name="mbt_client_role" value="' . esc_attr($role) . '"></label></p>';
    echo '<p><label>' . esc_html__('Rating (1-5)', 'my-business-theme') . '<br><input class="small-text" type="number" min="1" max="5" name="mbt_rating" value="' . esc_attr($rating) . '"></label></p>';
}

function mbt_render_partner_meta_box(WP_Post $post): void
{
    wp_nonce_field('mbt_save_meta', 'mbt_meta_nonce');
    $url = mbt_get_meta($post->ID, '_mbt_partner_url');
    echo '<p><label>' . esc_html__('Partner URL', 'my-business-theme') . '<br><input class="widefat" name="mbt_partner_url" value="' . esc_attr($url) . '"></label></p>';
    echo '<p>' . esc_html__('Use featured image for the logo image.', 'my-business-theme') . '</p>';
}

function mbt_save_meta_boxes(int $post_id): void
{
    if (!isset($_POST['mbt_meta_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['mbt_meta_nonce'])), 'mbt_save_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = [
        'mbt_button_text'  => '_mbt_button_text',
        'mbt_button_url'   => '_mbt_button_url',
        'mbt_project_url'  => '_mbt_project_url',
        'mbt_client_role'  => '_mbt_client_role',
        'mbt_rating'       => '_mbt_rating',
        'mbt_partner_url'  => '_mbt_partner_url',
    ];

    foreach ($fields as $form_key => $meta_key) {
        if (isset($_POST[$form_key])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field(wp_unslash($_POST[$form_key])));
        }
    }
}
add_action('save_post', 'mbt_save_meta_boxes');
