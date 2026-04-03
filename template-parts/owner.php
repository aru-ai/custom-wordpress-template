<?php
$owner_name              = mbt_get_theme_mod_string('mbt_owner_name', __('Martin', 'my-business-theme'));
$owner_kicker            = mbt_get_theme_mod_string('mbt_owner_kicker', __('About Owner', 'my-business-theme'));
$owner_title_main        = mbt_get_theme_mod_string('mbt_owner_title_main', __('The Experience and', 'my-business-theme'));
$owner_title_accent      = mbt_get_theme_mod_string('mbt_owner_title_accent', __('Values Behind Lumi', 'my-business-theme'));
$owner_text              = mbt_get_theme_mod_string('mbt_owner_text', '');
$owner_signature_caption = mbt_get_theme_mod_string('mbt_owner_signature_caption', __('LUMI MELBOURNE CABINETS', 'my-business-theme'));
$business_name           = mbt_get_theme_mod_string('mbt_business_name', __('Lumi Melbourne Cabinets', 'my-business-theme'));
$owner_image_alt         = mbt_get_theme_mod_string('mbt_owner_image_alt', $owner_name !== '' ? $owner_name : __('Martin from Lumi Melbourne Cabinets', 'my-business-theme'));
$owner_portrait_url      = mbt_get_image_url_or_fallback(absint(get_theme_mod('mbt_owner_section_photo', 0)), 'large', 'owner-section');
$owner_section_bg_url    = mbt_get_image_url_or_fallback(absint(get_theme_mod('mbt_owner_section_background_image', 0)), 'full', 'owner-background');
$owner_signature_url     = mbt_get_image_url_or_fallback(absint(get_theme_mod('mbt_owner_signature_image', 0)), 'full', 'owner-signature');
$owner_logo_url          = '';
$custom_logo_id          = absint(get_theme_mod('custom_logo', 0));

if ($custom_logo_id) {
    $owner_logo_url = wp_get_attachment_image_url($custom_logo_id, 'full') ?: '';
}

if ($owner_logo_url === '') {
    $owner_logo_url = get_theme_file_uri('/assets/img/Lumi-White.png');
}
?>
<section class="mbt-section mbt-about-owner">
    <div class="mbt-about-owner__shell">
        <img class="mbt-about-owner__background" src="<?php echo esc_url($owner_section_bg_url); ?>" alt="" aria-hidden="true">

        <div class="mbt-container mbt-owner-section">
            <div class="mbt-owner-section__media">
                <div class="mbt-media-frame mbt-media-frame--owner-portrait">
                    <img src="<?php echo esc_url($owner_portrait_url); ?>" alt="<?php echo esc_attr($owner_image_alt); ?>">
                </div>
            </div>

            <div class="mbt-owner-section__content">
                <div class="mbt-owner-section__kicker-wrap">
                    <?php if ($owner_kicker !== '') : ?>
                        <p class="mbt-owner-section__kicker">
                            <span class="mbt-owner-section__kicker-dot" aria-hidden="true"></span>
                            <?php echo esc_html($owner_kicker); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <h2 class="mbt-owner-section__title">
                    <?php if ($owner_title_main !== '') : ?>
                        <span class="mbt-owner-section__title-main"><?php echo esc_html($owner_title_main); ?></span>
                    <?php endif; ?>
                    <?php if ($owner_title_accent !== '') : ?>
                        <span class="mbt-owner-section__title-accent"><?php echo esc_html($owner_title_accent); ?></span>
                    <?php endif; ?>
                </h2>

                <div class="mbt-owner-section__text"><?php echo wp_kses_post(wpautop($owner_text)); ?></div>

                <div class="mbt-owner-signature">
                    <img class="mbt-owner-signature__image" src="<?php echo esc_url($owner_signature_url); ?>" alt="<?php echo esc_attr(sprintf(__('%s signature', 'my-business-theme'), $owner_name !== '' ? $owner_name : __('Owner', 'my-business-theme'))); ?>">
                    <?php if ($owner_signature_caption !== '') : ?>
                        <p class="mbt-owner-signature__caption"><?php echo esc_html($owner_signature_caption); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="mbt-owner-brand-strip" aria-hidden="true">
            <div class="mbt-owner-brand-strip__viewport">
                <div class="mbt-owner-brand-strip__track">
                    <?php for ($copy = 0; $copy < 2; $copy++) : ?>
                        <div class="mbt-owner-brand-strip__group"<?php echo $copy === 1 ? ' aria-hidden="true"' : ''; ?>>
                            <?php for ($index = 0; $index < 6; $index++) : ?>
                                <div class="mbt-owner-brand-strip__item">
                                    <span class="mbt-owner-brand-strip__dot"></span>
                                    <span class="mbt-owner-brand-strip__logo">
                                        <img src="<?php echo esc_url($owner_logo_url); ?>" alt="<?php echo esc_attr($business_name); ?>">
                                    </span>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</section>
