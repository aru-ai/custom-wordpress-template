<?php
if (!defined('ABSPATH')) {
    exit;
}

$business_name = mbt_get_theme_mod_string('mbt_business_name', __('Lumi Melbourne Cabinets', 'my-business-theme'));
$phone         = mbt_get_theme_mod_string('mbt_contact_phone', __('0435 777 797', 'my-business-theme'));
$default_logo  = get_theme_file_uri('/assets/img/Lumi-White.png');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <header class="mbt-header">
        <div class="mbt-container">
            <div class="mbt-header__shell">
                <div class="mbt-branding">
                    <?php if (has_custom_logo()) : ?>
                        <div class="mbt-logo"><?php the_custom_logo(); ?></div>
                    <?php else : ?>
                        <a class="mbt-brand-link" href="<?php echo esc_url(home_url('/')); ?>">
                            <img class="mbt-brand-logo" src="<?php echo esc_url($default_logo); ?>" alt="<?php echo esc_attr($business_name); ?>">
                        </a>
                    <?php endif; ?>
                </div>

                <button class="mbt-menu-toggle" type="button" aria-expanded="false" aria-controls="mbt-primary-menu">
                    <span class="screen-reader-text"><?php esc_html_e('Toggle menu', 'my-business-theme'); ?></span>
                    <span></span><span></span><span></span>
                </button>

                <nav class="mbt-nav" aria-label="<?php esc_attr_e('Primary Menu', 'my-business-theme'); ?>">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'menu_id'        => 'mbt-primary-menu',
                        'menu_class'     => 'mbt-menu',
                        'container'      => false,
                        'fallback_cb'    => 'mbt_primary_menu_fallback',
                    ]);
                    ?>
                </nav>

                <div class="mbt-header__cta">
                    <a class="mbt-header__phone" href="<?php echo esc_url(mbt_get_phone_link($phone)); ?>">
                        <span class="mbt-header__phone-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                                <path d="M6.7 3.9a1.9 1.9 0 0 1 2 .4l1.8 1.8a1.9 1.9 0 0 1 .4 2l-.8 2a1.7 1.7 0 0 0 .4 1.8l1.7 1.7a1.7 1.7 0 0 0 1.8.4l2-.8a1.9 1.9 0 0 1 2 .4l1.8 1.8a1.9 1.9 0 0 1 .4 2l-.5 1.7a2.6 2.6 0 0 1-2.5 1.8A16.9 16.9 0 0 1 3 6.9a2.6 2.6 0 0 1 1.8-2.5Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" />
                            </svg>
                        </span>
                        <span class="mbt-header__phone-copy">
                            <small><?php esc_html_e('Call Us', 'my-business-theme'); ?></small>
                            <strong><?php echo esc_html($phone); ?></strong>
                        </span>
                    </a>
                    <?php
                    echo mbt_button([
                        'label' => mbt_get_theme_mod_string('mbt_contact_button_text', __('GET A QUOTE', 'my-business-theme')),
                        'class' => 'mbt-button mbt-button--small',
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </header>
    <main id="primary" class="site-main">
        <?php
        function mbt_primary_menu_fallback(): void
        {
            echo '<ul id="mbt-primary-menu" class="mbt-menu">';
            echo '<li><a href="#top">' . esc_html__('HOME', 'my-business-theme') . '</a></li>';
            echo '<li class="menu-item-has-children"><a href="#services">' . esc_html__('SERVICES', 'my-business-theme') . '</a><ul class="sub-menu"><li><a href="#services">' . esc_html__('Cabinetry', 'my-business-theme') . '</a></li><li><a href="#portfolio">' . esc_html__('Portfolio', 'my-business-theme') . '</a></li><li><a href="#process">' . esc_html__('Process', 'my-business-theme') . '</a></li></ul></li>';
            echo '<li class="menu-item-has-children"><a href="#service-areas">' . esc_html__('AREAS WE SERVE', 'my-business-theme') . '</a><ul class="sub-menu"><li><a href="#contact">' . esc_html__('About Melbourne', 'my-business-theme') . '</a></li><li><a href="#service-areas">' . esc_html__('Service Areas', 'my-business-theme') . '</a></li></ul></li>';
            echo '</ul>';
        }
