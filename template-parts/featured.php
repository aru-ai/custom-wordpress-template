<?php
$featured_background = mbt_get_image_url_or_fallback(absint(get_theme_mod('mbt_featured_background', 0)), 'full', 'featured-overview');
$featured_kicker     = mbt_get_theme_mod_string('mbt_featured_kicker', __('Our Service Overview', 'my-business-theme'));
$featured_title      = mbt_get_theme_mod_string('mbt_featured_title', __('Bespoke Cabinetry', 'my-business-theme'));
$featured_accent     = mbt_get_theme_mod_string('mbt_featured_accent', __('Designed for Everyday Living', 'my-business-theme'));
$featured_text       = mbt_get_theme_mod_string('mbt_featured_text', __('Lumi Melbourne Cabinets delivers premium bespoke cabinetry across Melbourne\'s southern suburbs. From kitchens and bathrooms to wardrobes, home offices and garage storage, each space is thoughtfully designed for refined aesthetics, practical function and lasting quality.', 'my-business-theme'));
$featured_link_text  = mbt_get_theme_mod_string('mbt_featured_link_text', __('Expand', 'my-business-theme'));
$featured_link_url   = mbt_sanitize_header_link_target(get_theme_mod('mbt_featured_link_url', '#services'));
$featured_button_text = mbt_get_theme_mod_string('mbt_featured_button_text', __('Get a Quote', 'my-business-theme'));
$featured_button_type = mbt_sanitize_header_button_type(get_theme_mod('mbt_featured_button_type', 'popup'));
$featured_button_url  = mbt_sanitize_header_link_target(get_theme_mod('mbt_featured_button_url', '#contact'));
$show_phone_button    = mbt_get_theme_mod_bool('mbt_featured_show_phone', true);
$phone_number         = mbt_get_theme_mod_string('mbt_contact_phone', __('0435 777 797', 'my-business-theme'));
?>
<section class="mbt-section mbt-section--compact mbt-featured-overview">
    <div class="mbt-container">
        <div class="mbt-featured-overview__shell" style="background-image: url('<?php echo esc_url($featured_background); ?>');">
            <div class="mbt-featured-overview__panel">
                <?php if ($featured_kicker !== '') : ?>
                    <p class="mbt-featured-overview__kicker">
                        <span class="mbt-featured-overview__kicker-dot" aria-hidden="true"></span>
                        <?php echo esc_html($featured_kicker); ?>
                    </p>
                <?php endif; ?>

                <?php if ($featured_title !== '') : ?>
                    <h2 class="mbt-featured-overview__title"><?php echo esc_html($featured_title); ?></h2>
                <?php endif; ?>

                <?php if ($featured_accent !== '') : ?>
                    <p class="mbt-featured-overview__accent"><?php echo esc_html($featured_accent); ?></p>
                <?php endif; ?>

                <?php if ($featured_text !== '') : ?>
                    <div class="mbt-featured-overview__text"><?php echo wp_kses_post(wpautop($featured_text)); ?></div>
                <?php endif; ?>

                <?php if ($featured_link_text !== '' && $featured_link_url !== '') : ?>
                    <a class="mbt-featured-overview__link" href="<?php echo esc_url($featured_link_url); ?>">
                        <?php echo esc_html($featured_link_text); ?>
                    </a>
                <?php endif; ?>

                <div class="mbt-featured-overview__actions">
                    <?php
                    echo mbt_button([
                        'label' => $featured_button_text,
                        'url'   => $featured_button_url,
                        'type'  => $featured_button_type,
                        'class' => 'mbt-button mbt-button--small',
                        'modal' => 'mbt-contact-modal',
                    ]);
                    ?>
                    <?php if ($show_phone_button && $phone_number !== '') : ?>
                        <a class="mbt-featured-overview__phone" href="<?php echo esc_url(mbt_get_phone_link($phone_number)); ?>">
                            <span class="mbt-featured-overview__phone-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" focusable="false">
                                    <path fill="currentColor" d="M6.6 10.8a15.5 15.5 0 0 0 6.6 6.6l2.2-2.2c.3-.3.8-.4 1.2-.3 1 .3 2.1.4 3.2.4.7 0 1.2.5 1.2 1.2V20c0 .7-.5 1.2-1.2 1.2C10.4 21.2 2.8 13.6 2.8 4.2 2.8 3.5 3.3 3 4 3h3.5c.7 0 1.2.5 1.2 1.2 0 1.1.1 2.1.4 3.2.1.4 0 .9-.3 1.2l-2.2 2.2Z"/>
                                </svg>
                            </span>
                            <span><?php echo esc_html($phone_number); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
