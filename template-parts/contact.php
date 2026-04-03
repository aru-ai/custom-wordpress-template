<?php
$contact_kicker       = mbt_get_theme_mod_string('mbt_contact_kicker', __('About Area', 'my-business-theme'));
$contact_title_main   = mbt_get_theme_mod_string('mbt_contact_title', __('About', 'my-business-theme'));
$contact_title_accent = mbt_get_theme_mod_string('mbt_contact_title_accent', __('Melbourne', 'my-business-theme'));
$contact_text         = mbt_get_theme_mod_string('mbt_contact_text', __('Lumi Melbourne Cabinets proudly services the southern suburbs of Melbourne, delivering premium custom cabinetry for residential homes. We work across areas including the Mornington Peninsula, Bayside and surrounding suburbs, partnering with homeowners who value quality craftsmanship, clear communication and considered design.', 'my-business-theme'));
$contact_link_text    = mbt_get_theme_mod_string('mbt_contact_link_text', __('Expand', 'my-business-theme'));
$contact_link_url     = mbt_sanitize_header_link_target(get_theme_mod('mbt_contact_link_url', '#service-areas'));
$contact_button_text  = mbt_get_theme_mod_string('mbt_contact_button_text', __('Get a quote', 'my-business-theme'));
$contact_button_type  = mbt_sanitize_header_button_type(get_theme_mod('mbt_contact_button_type', 'popup'));
$contact_button_url   = mbt_sanitize_header_link_target(get_theme_mod('mbt_contact_button_url', '#contact'));
$contact_phone        = mbt_get_theme_mod_string('mbt_contact_phone', __('0435 777 797', 'my-business-theme'));
$contact_map_url      = mbt_get_map_embed_url();
?>
<section id="contact" class="mbt-section mbt-contact-section">
    <div class="mbt-container">
        <div class="mbt-contact-section__grid">
            <div class="mbt-contact-section__content">
                <?php if ($contact_kicker !== '') : ?>
                    <div class="mbt-contact-section__kicker-wrap">
                        <p class="mbt-contact-section__kicker">
                            <span class="mbt-contact-section__kicker-dot" aria-hidden="true"></span>
                            <?php echo esc_html($contact_kicker); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if ($contact_title_main !== '' || $contact_title_accent !== '') : ?>
                    <h2 class="mbt-contact-section__title">
                        <?php if ($contact_title_main !== '') : ?>
                            <span class="mbt-contact-section__title-main"><?php echo esc_html($contact_title_main); ?></span>
                        <?php endif; ?>
                        <?php if ($contact_title_accent !== '') : ?>
                            <span class="mbt-contact-section__title-accent"><?php echo esc_html($contact_title_accent); ?></span>
                        <?php endif; ?>
                    </h2>
                <?php endif; ?>

                <?php if ($contact_text !== '') : ?>
                    <div class="mbt-contact-section__text"><?php echo wp_kses_post(wpautop($contact_text)); ?></div>
                <?php endif; ?>

                <?php if ($contact_link_text !== '' && $contact_link_url !== '') : ?>
                    <a class="mbt-contact-section__link" href="<?php echo esc_url($contact_link_url); ?>">
                        <?php echo esc_html($contact_link_text); ?>
                    </a>
                <?php endif; ?>

                <?php if ($contact_button_text !== '' || $contact_phone !== '') : ?>
                    <div class="mbt-contact-section__actions">
                        <?php if ($contact_button_text !== '') : ?>
                            <?php
                            echo mbt_button([
                                'label' => $contact_button_text,
                                'url'   => $contact_button_url,
                                'type'  => $contact_button_type,
                                'class' => 'mbt-button mbt-about-business__button',
                                'modal' => 'mbt-contact-modal',
                            ]);
                            ?>
                        <?php endif; ?>

                        <?php if ($contact_phone !== '') : ?>
                            <a class="mbt-about-business__phone" href="<?php echo esc_url(mbt_get_phone_link($contact_phone)); ?>">
                                <span class="mbt-about-business__phone-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.45 19.45 0 0 1-6-6A19.8 19.8 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.79.62 2.64a2 2 0 0 1-.45 2.11L8 9.91a16 16 0 0 0 6.09 6.09l1.44-1.23a2 2 0 0 1 2.11-.45c.85.29 1.74.5 2.64.62A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                </span>
                                <span><?php echo esc_html($contact_phone); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mbt-contact-section__map">
                <div class="mbt-contact-section__map-frame mbt-map-frame">
                    <iframe src="<?php echo esc_url($contact_map_url); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="<?php esc_attr_e('Google Map', 'my-business-theme'); ?>"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>
