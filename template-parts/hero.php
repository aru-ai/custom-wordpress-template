<?php
$bg_id              = absint(get_theme_mod('mbt_hero_background', 0));
$owner_id           = absint(get_theme_mod('mbt_owner_photo', 0));
$bg_url             = mbt_get_image_url_or_fallback($bg_id, 'full', 'hero');
$owner_url          = mbt_get_image_url_or_fallback($owner_id, 'large', 'owner');
$hero_title         = mbt_get_theme_mod_string('mbt_hero_title', __('Timeless Cabinetry.', 'my-business-theme'));
$hero_accent        = mbt_get_theme_mod_string('mbt_business_tagline', __('Modern Melbourne elegance.', 'my-business-theme'));
$hero_text          = mbt_get_theme_mod_string('mbt_hero_text', __('Bespoke cabinetry and joinery, crafted with precision to bring light, function and timeless beauty to your home.', 'my-business-theme'));
$hero_kicker        = mbt_get_theme_mod_string('mbt_hero_kicker', __('Based on Google Reviews', 'my-business-theme'));
$hero_review_score  = mbt_get_theme_mod_string('mbt_hero_review_score', '4.9');
$hero_form_title    = mbt_get_theme_mod_string('mbt_hero_form_title', __('Got a project in mind?', 'my-business-theme'));
$hero_name_ph       = mbt_get_theme_mod_string('mbt_hero_form_name_placeholder', __('Enter name', 'my-business-theme'));
$hero_phone_ph      = mbt_get_theme_mod_string('mbt_hero_form_phone_placeholder', __('Enter phone', 'my-business-theme'));
$hero_email_ph      = mbt_get_theme_mod_string('mbt_hero_form_email_placeholder', __('Enter email', 'my-business-theme'));
$hero_message_ph    = mbt_get_theme_mod_string('mbt_hero_form_message_placeholder', __('How can we help?', 'my-business-theme'));
$hero_button_text   = mbt_get_theme_mod_string('mbt_hero_button_text', __('Get a quote', 'my-business-theme'));
$hero_button_type   = mbt_sanitize_header_button_type(get_theme_mod('mbt_hero_button_type', 'popup'));
$hero_button_url    = mbt_sanitize_header_link_target(get_theme_mod('mbt_hero_button_url', '#contact'));
$hero_accent_parts  = preg_split('/\s+/', trim($hero_accent), 2);
$hero_accent_lead   = $hero_accent_parts[0] ?? '';
$hero_accent_rest   = $hero_accent_parts[1] ?? '';
$hero_title_graphic = (mbt_theme_mod_uses_default('mbt_hero_title') && mbt_theme_mod_uses_default('mbt_business_tagline'))
    ? mbt_get_section_graphic_url('hero-title')
    : '';
?>
<section id="top" class="mbt-hero">
    <div class="mbt-hero__media">
        <img src="<?php echo esc_url($bg_url); ?>" alt="<?php esc_attr_e('Hero background', 'my-business-theme'); ?>">
    </div>
    <div class="mbt-hero__overlay"></div>
    <div class="mbt-container">
        <div class="mbt-hero__content">
            <div class="mbt-review-pill">
                <span class="mbt-review-pill__badge" aria-hidden="true">
                    <svg viewBox="0 0 533.5 544.3" focusable="false" aria-hidden="true">
                        <path fill="#4285F4" d="M533.5 278.4c0-18.5-1.5-37-4.7-55.1H272v104.4h146.9c-6.1 33.7-25.2 62.4-53.7 81.6v67.7h86.9c50.9-46.9 80.4-116.1 80.4-198.6Z"/>
                        <path fill="#34A853" d="M272 544.3c72.6 0 133.5-23.8 178-64.6l-86.9-67.7c-24.1 16.4-55 25.8-91.1 25.8-70 0-129.4-47.3-150.6-110.9H31.1v69.8C75.8 486.4 167.5 544.3 272 544.3Z"/>
                        <path fill="#FBBC04" d="M121.4 326.9c-10.6-31.4-10.6-65.4 0-96.8v-69.8H31.1c-41.4 82.6-41.4 180.8 0 263.4l90.3-69.8Z"/>
                        <path fill="#EA4335" d="M272 107.7c39.5 0 75 13.6 103 40.3l77.2-77.2C405 24.6 343.4 0 272 0 167.5 0 75.8 57.9 31.1 147.6l90.3 69.8C142.6 155 202 107.7 272 107.7Z"/>
                    </svg>
                </span>
                <div class="mbt-review-pill__copy">
                    <div class="mbt-review-pill__stars"><span aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</span> <strong><?php echo esc_html($hero_review_score); ?></strong></div>
                    <small><?php echo esc_html($hero_kicker); ?></small>
                </div>
            </div>
            <?php if ($hero_title_graphic) : ?>
                <h1 class="screen-reader-text"><?php echo esc_html($hero_title . ' ' . $hero_accent); ?></h1>
                <img class="mbt-heading-graphic mbt-heading-graphic--hero" src="<?php echo esc_url($hero_title_graphic); ?>" alt="<?php echo esc_attr($hero_title . ' ' . $hero_accent); ?>">
            <?php else : ?>
                <h1 class="mbt-hero__title">
                    <span><?php echo esc_html($hero_title); ?></span>
                    <span class="mbt-hero__accent-line">
                        <span class="mbt-hero__accent-prefix"><?php echo esc_html($hero_accent_lead); ?></span><?php if ($hero_accent_rest !== '') : ?>
                            <span class="mbt-hero__accent-emphasis"><?php echo esc_html($hero_accent_rest); ?></span>
                        <?php endif; ?>
                    </span>
                </h1>
            <?php endif; ?>
            <p class="mbt-hero__text"><?php echo esc_html($hero_text); ?></p>
        </div>
        <div class="mbt-hero__enquiry">
            <div class="mbt-hero__enquiry-main">
                <h2><?php echo esc_html($hero_form_title); ?></h2>
                <form class="mbt-inline-form mbt-inline-form--hero" action="#" method="post">
                    <label>
                        <span class="screen-reader-text"><?php esc_html_e('Name', 'my-business-theme'); ?></span>
                        <input type="text" placeholder="<?php echo esc_attr($hero_name_ph); ?>">
                    </label>
                    <label>
                        <span class="screen-reader-text"><?php esc_html_e('Phone', 'my-business-theme'); ?></span>
                        <input type="tel" placeholder="<?php echo esc_attr($hero_phone_ph); ?>">
                    </label>
                    <label>
                        <span class="screen-reader-text"><?php esc_html_e('Email', 'my-business-theme'); ?></span>
                        <input type="email" placeholder="<?php echo esc_attr($hero_email_ph); ?>">
                    </label>
                    <label class="mbt-inline-form__message">
                        <span class="screen-reader-text"><?php esc_html_e('How can we help?', 'my-business-theme'); ?></span>
                        <input type="text" placeholder="<?php echo esc_attr($hero_message_ph); ?>">
                    </label>
                    <?php
                    echo mbt_button([
                        'label' => $hero_button_text,
                        'url'   => $hero_button_url,
                        'type'  => $hero_button_type,
                        'class' => 'mbt-button mbt-button--hero',
                        'modal' => 'mbt-contact-modal',
                    ]);
                    ?>
                </form>
            </div>
            <div class="mbt-hero__portrait">
                <img src="<?php echo esc_url($owner_url); ?>" alt="<?php echo esc_attr(mbt_get_theme_mod_string('mbt_owner_name', __('Martin', 'my-business-theme'))); ?>">
            </div>
        </div>
    </div>
</section>
