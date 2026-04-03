<?php
$about_kicker       = mbt_get_theme_mod_string('mbt_about_kicker', __('About the Business', 'my-business-theme'));
$about_title_main   = mbt_get_theme_mod_string('mbt_about_title', __('Where Space Inspire', 'my-business-theme'));
$about_title_accent = mbt_get_theme_mod_string('mbt_about_title_accent', __('and Design Come Alive', 'my-business-theme'));
$about_text         = mbt_get_theme_mod_string('mbt_about_text', __("Lumi Melbourne Cabinets delivers premium bespoke cabinetry across Melbourne's southern suburbs.\n\nFrom kitchens and bathrooms to wardrobes, home offices and garage storage, each space is thoughtfully designed for refined aesthetics, practical function and lasting quality.\n\nLed by Martin with over fifteen years of hands-on experience, Lumi offers a transparent, collaborative process with clear communication and precise workmanship.", 'my-business-theme'));
$about_button_text  = mbt_get_theme_mod_string('mbt_about_button_text', __('GET A QUOTE', 'my-business-theme'));
$about_button_type  = mbt_sanitize_header_button_type(get_theme_mod('mbt_about_button_type', 'popup'));
$about_button_url   = mbt_sanitize_header_link_target(get_theme_mod('mbt_about_button_url', '#contact'));
$about_show_phone   = mbt_get_theme_mod_bool('mbt_about_show_phone', true);
$about_image        = mbt_get_image_url_or_fallback(absint(get_theme_mod('mbt_about_image', 0)), 'full', 'space');
$about_image_alt    = mbt_get_theme_mod_string('mbt_about_image_alt', __('Custom cabinetry interior', 'my-business-theme'));
$phone_number       = mbt_get_theme_mod_string('mbt_contact_phone', __('0435 777 797', 'my-business-theme'));
?>
<section id="about" class="mbt-section mbt-about-business">
    <div class="mbt-container">
        <div class="mbt-about-business__grid">
            <div class="mbt-about-business__content">
                <?php if ($about_kicker !== '') : ?>
                    <div class="mbt-about-business__kicker-wrap">
                        <p class="mbt-about-business__kicker">
                            <span class="mbt-about-business__kicker-dot" aria-hidden="true"></span>
                            <?php echo esc_html($about_kicker); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if ($about_title_main !== '' || $about_title_accent !== '') : ?>
                    <h2 class="mbt-about-business__title">
                        <?php if ($about_title_main !== '') : ?>
                            <span class="mbt-about-business__title-main"><?php echo esc_html($about_title_main); ?></span>
                        <?php endif; ?>
                        <?php if ($about_title_accent !== '') : ?>
                            <span class="mbt-about-business__title-accent"><?php echo esc_html($about_title_accent); ?></span>
                        <?php endif; ?>
                    </h2>
                <?php endif; ?>

                <?php if ($about_text !== '') : ?>
                    <div class="mbt-about-business__text"><?php echo wp_kses_post(wpautop($about_text)); ?></div>
                <?php endif; ?>

                <?php if ($about_button_text !== '' || ($about_show_phone && $phone_number !== '')) : ?>
                    <div class="mbt-about-business__actions">
                        <?php if ($about_button_text !== '') : ?>
                            <?php
                            echo mbt_button([
                                'label' => $about_button_text,
                                'url'   => $about_button_url,
                                'type'  => $about_button_type,
                                'class' => 'mbt-button mbt-about-business__button',
                                'modal' => 'mbt-contact-modal',
                            ]);
                            ?>
                        <?php endif; ?>

                        <?php if ($about_show_phone && $phone_number !== '') : ?>
                            <a class="mbt-about-business__phone" href="<?php echo esc_url(mbt_get_phone_link($phone_number)); ?>">
                                <span class="mbt-about-business__phone-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.45 19.45 0 0 1-6-6A19.8 19.8 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.79.62 2.64a2 2 0 0 1-.45 2.11L8 9.91a16 16 0 0 0 6.09 6.09l1.44-1.23a2 2 0 0 1 2.11-.45c.85.29 1.74.5 2.64.62A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                </span>
                                <span><?php echo esc_html($phone_number); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mbt-about-business__media">
                <div class="mbt-about-business__image-frame">
                    <img src="<?php echo esc_url($about_image); ?>" alt="<?php echo esc_attr($about_image_alt); ?>">
                </div>
            </div>
        </div>
    </div>
</section>
