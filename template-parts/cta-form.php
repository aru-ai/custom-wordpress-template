<?php
$cta_image               = mbt_get_image_url_or_fallback(absint(get_theme_mod('mbt_cta_background_image', 0)), 'full', 'hero');
$cta_title               = mbt_get_theme_mod_string('mbt_cta_title', __('Get In Touch With The Team Today.', 'my-business-theme'));
$cta_status_text         = mbt_get_theme_mod_string('mbt_cta_status_text', __("We're available now!", 'my-business-theme'));
$cta_name_placeholder    = mbt_get_theme_mod_string('mbt_cta_name_placeholder', __('Enter name', 'my-business-theme'));
$cta_phone_placeholder   = mbt_get_theme_mod_string('mbt_cta_phone_placeholder', __('Enter phone', 'my-business-theme'));
$cta_email_placeholder   = mbt_get_theme_mod_string('mbt_cta_email_placeholder', __('Enter email', 'my-business-theme'));
$cta_message_placeholder = mbt_get_theme_mod_string('mbt_cta_message_placeholder', __('How can we help?', 'my-business-theme'));
$cta_button_text         = mbt_get_theme_mod_string('mbt_cta_button_text', __('GET A QUOTE', 'my-business-theme'));
$cta_button_type         = mbt_sanitize_header_button_type(get_theme_mod('mbt_cta_button_type', 'popup'));
$cta_button_url          = mbt_sanitize_header_link_target(get_theme_mod('mbt_cta_button_url', '#contact'));
?>
<section class="mbt-section mbt-section--compact mbt-cta-band-section">
    <div class="mbt-container">
        <div class="mbt-cta-band" style="background-image: url('<?php echo esc_url($cta_image); ?>');">
            <div class="mbt-cta-band__content">
                <div class="mbt-cta-band__header">
                    <?php if ($cta_title !== '') : ?>
                        <h2><?php echo esc_html($cta_title); ?></h2>
                    <?php endif; ?>

                    <?php if ($cta_status_text !== '') : ?>
                        <p class="mbt-cta-band__status">
                            <span><?php echo esc_html($cta_status_text); ?></span>
                            <span class="mbt-cta-band__status-dot" aria-hidden="true"></span>
                        </p>
                    <?php endif; ?>
                </div>

                <form class="mbt-inline-form mbt-inline-form--cta" action="#" method="post" onsubmit="return false;">
                    <label>
                        <span class="screen-reader-text"><?php esc_html_e('Name', 'my-business-theme'); ?></span>
                        <input type="text" placeholder="<?php echo esc_attr($cta_name_placeholder); ?>">
                    </label>
                    <label>
                        <span class="screen-reader-text"><?php esc_html_e('Phone', 'my-business-theme'); ?></span>
                        <input type="tel" placeholder="<?php echo esc_attr($cta_phone_placeholder); ?>">
                    </label>
                    <label>
                        <span class="screen-reader-text"><?php esc_html_e('Email', 'my-business-theme'); ?></span>
                        <input type="email" placeholder="<?php echo esc_attr($cta_email_placeholder); ?>">
                    </label>
                    <label class="mbt-inline-form__message">
                        <span class="screen-reader-text"><?php esc_html_e('Message', 'my-business-theme'); ?></span>
                        <textarea rows="1" placeholder="<?php echo esc_attr($cta_message_placeholder); ?>"></textarea>
                    </label>
                    <div class="mbt-cta-band__action">
                        <?php
                        echo mbt_button([
                            'label' => $cta_button_text,
                            'url'   => $cta_button_url,
                            'type'  => $cta_button_type,
                            'class' => 'mbt-button mbt-button--hero mbt-cta-band__button',
                            'modal' => 'mbt-contact-modal',
                        ]);
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
