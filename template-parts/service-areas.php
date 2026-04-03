<?php
$service_areas_kicker       = mbt_get_theme_mod_string('mbt_service_areas_kicker', __('Our Service Area', 'my-business-theme'));
$service_areas_title_main   = mbt_get_theme_mod_string('mbt_service_areas_title_main', __('Proudly Servicing', 'my-business-theme'));
$service_areas_title_accent = mbt_get_theme_mod_string('mbt_service_areas_title_accent', __('Melbourne Areas', 'my-business-theme'));
$service_areas_background   = mbt_get_image_url_or_fallback(absint(get_theme_mod('mbt_service_areas_background_image', 0)), 'full', 'service-areas-background');
$service_area_items         = mbt_get_service_areas_from_theme_mod();
$service_area_map_url       = mbt_get_map_embed_url();
$area_grid_class            = count($service_area_items) > 12 ? ' mbt-area-grid--three-columns' : '';
?>
<section id="service-areas" class="mbt-section mbt-section--dark mbt-service-areas-section" style="--mbt-service-areas-background-image: url('<?php echo esc_url($service_areas_background); ?>');">
    <div class="mbt-container">
        <div class="mbt-service-areas">
            <div class="mbt-service-areas__content">
                <?php if ($service_areas_kicker !== '') : ?>
                    <div class="mbt-service-areas__kicker-wrap">
                        <p class="mbt-service-areas__kicker">
                            <span class="mbt-service-areas__kicker-dot" aria-hidden="true"></span>
                            <?php echo esc_html($service_areas_kicker); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if ($service_areas_title_main !== '' || $service_areas_title_accent !== '') : ?>
                    <h2 class="mbt-service-areas__title">
                        <?php if ($service_areas_title_main !== '') : ?>
                            <span class="mbt-service-areas__title-main"><?php echo esc_html($service_areas_title_main); ?></span>
                        <?php endif; ?>
                        <?php if ($service_areas_title_accent !== '') : ?>
                            <span class="mbt-service-areas__title-accent"><?php echo esc_html($service_areas_title_accent); ?></span>
                        <?php endif; ?>
                    </h2>
                <?php endif; ?>

                <?php if ($service_area_items) : ?>
                    <ul class="mbt-area-grid<?php echo esc_attr($area_grid_class); ?>">
                        <?php foreach ($service_area_items as $area) : ?>
                            <li>
                                <span class="mbt-area-chip">
                                    <span class="mbt-area-chip__label"><?php echo esc_html($area); ?></span>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="mbt-service-areas__map">
                <div class="mbt-service-areas__map-frame mbt-map-frame">
                    <iframe src="<?php echo esc_url($service_area_map_url); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="<?php esc_attr_e('Service area map', 'my-business-theme'); ?>"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>
