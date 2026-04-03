<?php
if (!defined('ABSPATH')) {
    exit;
}

$business_name    = mbt_get_theme_mod_string('mbt_business_name', __('Lumi Melbourne Cabinets', 'my-business-theme'));
$default_logo     = get_theme_file_uri('/assets/img/Lumi-White.png');
$footer_background = mbt_get_image_url_or_fallback(absint(get_theme_mod('mbt_footer_background_image', 0)), 'full', 'footer-background');
$footer_text       = mbt_get_theme_mod_string('mbt_footer_text', __('Premium custom cabinetry and joinery for kitchens, laundries, wardrobes, vanities, and whole-home interiors across Melbourne.', 'my-business-theme'));
$footer_services_title = mbt_get_theme_mod_string('mbt_footer_services_title', __('Services', 'my-business-theme'));
$footer_contact_title  = mbt_get_theme_mod_string('mbt_footer_contact_title', __('Contact Info', 'my-business-theme'));
$footer_credit_text    = mbt_get_theme_mod_string('mbt_footer_credit_text', __('Made with 💪 by UpRank', 'my-business-theme'));
$footer_credit_url     = mbt_sanitize_header_link_target(get_theme_mod('mbt_footer_credit_url', ''));
$footer_phone          = mbt_get_theme_mod_string('mbt_contact_phone', __('0435 777 797', 'my-business-theme'));
$footer_email          = mbt_get_theme_mod_string('mbt_contact_email', __('hello@lumicabinets.com.au', 'my-business-theme'));
$footer_address        = mbt_get_theme_mod_string('mbt_contact_address', __('Melbourne, Victoria, Australia', 'my-business-theme'));
$footer_service_links  = mbt_get_footer_service_links();
$footer_services       = mbt_get_footer_services_items();
$footer_credit_text    = mbt_get_theme_mod_string('mbt_footer_credit_text', __('Made with care by UpRank', 'my-business-theme'));
$footer_copyright_raw  = mbt_get_theme_mod_string('mbt_footer_copyright_text', __('Copyright {year} - {business_name}', 'my-business-theme'));
$footer_copyright_text = str_replace(
    ['{year}', '{business_name}'],
    [wp_date('Y'), $business_name],
    $footer_copyright_raw
);
?>
</main>
<footer class="mbt-footer" style="--mbt-footer-background-image: url('<?php echo esc_url($footer_background); ?>');">
    <div class="mbt-container mbt-footer__shell">
        <div class="mbt-footer__grid">
            <div class="mbt-footer__brand">
                <?php if (has_custom_logo()) : ?>
                    <div class="mbt-logo"><?php the_custom_logo(); ?></div>
                <?php else : ?>
                    <a class="mbt-brand-link" href="<?php echo esc_url(home_url('/')); ?>">
                        <img class="mbt-brand-logo" src="<?php echo esc_url($default_logo); ?>" alt="<?php echo esc_attr($business_name); ?>">
                    </a>
                <?php endif; ?>

                <?php if ($footer_text !== '') : ?>
                    <div class="mbt-footer__intro"><?php echo wp_kses_post(wpautop($footer_text)); ?></div>
                <?php endif; ?>
            </div>

            <div class="mbt-footer__column">
                <?php if ($footer_services_title !== '') : ?>
                    <h3 class="mbt-footer__title"><?php echo esc_html($footer_services_title); ?></h3>
                <?php endif; ?>

                <?php if ($footer_service_links) : ?>
                    <ul class="mbt-footer__services">
                        <?php foreach ($footer_service_links as $service_link) : ?>
                            <li>
                                <a href="<?php echo esc_url($service_link['url']); ?>">
                                    <?php echo esc_html($service_link['title']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php elseif ($footer_services) : ?>
                    <ul class="mbt-footer__services">
                        <?php foreach ($footer_services as $service_item) : ?>
                            <li><?php echo esc_html($service_item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="mbt-footer__column">
                <?php if ($footer_contact_title !== '') : ?>
                    <h3 class="mbt-footer__title"><?php echo esc_html($footer_contact_title); ?></h3>
                <?php endif; ?>

                <ul class="mbt-footer__contact-list">
                    <?php if ($footer_phone !== '') : ?>
                        <li><a href="<?php echo esc_url(mbt_get_phone_link($footer_phone)); ?>"><?php echo esc_html($footer_phone); ?></a></li>
                    <?php endif; ?>
                    <?php if ($footer_email !== '') : ?>
                        <li><a href="mailto:<?php echo esc_attr($footer_email); ?>"><?php echo esc_html($footer_email); ?></a></li>
                    <?php endif; ?>
                    <?php if ($footer_address !== '') : ?>
                        <li><strong><?php echo esc_html($footer_address); ?></strong></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="mbt-footer__bottom">
            <?php if ($footer_copyright_text !== '') : ?>
                <p class="mbt-footer__copyright"><?php echo esc_html($footer_copyright_text); ?></p>
            <?php endif; ?>

            <?php if ($footer_credit_text !== '') : ?>
                <?php if ($footer_credit_url !== '') : ?>
                    <a class="mbt-footer__credit" href="<?php echo esc_url($footer_credit_url); ?>"><?php echo esc_html($footer_credit_text); ?></a>
                <?php else : ?>
                    <p class="mbt-footer__credit"><?php echo esc_html($footer_credit_text); ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</footer>
<?php get_template_part('template-parts/modal-contact'); ?>
<?php wp_footer(); ?>
</body>
</html>
