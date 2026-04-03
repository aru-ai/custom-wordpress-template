<?php
$services_kicker        = mbt_get_theme_mod_string('mbt_services_kicker', __('Our Service Features', 'my-business-theme'));
$services_title_main    = mbt_get_theme_mod_string('mbt_services_title_main', __('End to End Custom', 'my-business-theme'));
$services_title_accent  = mbt_get_theme_mod_string('mbt_services_title_accent', __('Cabinetry Solutions', 'my-business-theme'));
$services_intro         = mbt_get_theme_mod_string('mbt_services_intro', '');
$featured_item          = mbt_sanitize_service_feature_index(get_theme_mod('mbt_services_featured_item', 0));
$service_items          = mbt_get_customizer_service_features();

if (!$service_items) {
    foreach (mbt_get_demo_services() as $service) {
        $service_items[] = [
            'title' => $service['title'],
            'text'  => $service['excerpt'],
            'url'   => $service['button_url'],
            'image' => $service['image'],
        ];
    }
}
?>
<section id="services" class="mbt-section mbt-section--services mbt-services-showcase">
    <div class="mbt-container">
        <div class="mbt-section-heading mbt-section-heading--center mbt-services-showcase__heading">
            <?php if ($services_kicker !== '') : ?>
                <p class="mbt-services-showcase__kicker">
                    <span class="mbt-services-showcase__kicker-dot" aria-hidden="true"></span>
                    <?php echo esc_html($services_kicker); ?>
                </p>
            <?php endif; ?>

            <h2 class="mbt-services-showcase__title">
                <span><?php echo esc_html($services_title_main); ?></span>
                <?php if ($services_title_accent !== '') : ?>
                    <span class="mbt-services-showcase__title-accent"><?php echo esc_html($services_title_accent); ?></span>
                <?php endif; ?>
            </h2>

            <?php if ($services_intro !== '') : ?>
                <div class="mbt-services-showcase__intro"><?php echo wp_kses_post(wpautop($services_intro)); ?></div>
            <?php endif; ?>
        </div>

        <div class="mbt-card-grid mbt-card-grid--services">
            <?php foreach ($service_items as $index => $service) : ?>
                <?php
                $card_classes = 'mbt-service-feature-card' . ($featured_item > 0 && ($index + 1) === $featured_item ? ' is-featured' : '');
                $title        = $service['title'] ?? '';
                $text         = $service['text'] ?? '';
                $url          = $service['url'] ?? '';
                $image        = $service['image'] ?? '';
                ?>
                <article class="<?php echo esc_attr($card_classes); ?>">
                    <?php if ($url) : ?>
                        <a class="mbt-service-feature-card__inner" href="<?php echo esc_url($url); ?>">
                    <?php else : ?>
                        <div class="mbt-service-feature-card__inner">
                    <?php endif; ?>

                    <?php if ($title !== '') : ?>
                        <h3 class="mbt-service-feature-card__title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>

                    <?php if ($image !== '') : ?>
                        <div class="mbt-service-feature-card__image">
                            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
                        </div>
                    <?php endif; ?>

                    <?php if ($text !== '') : ?>
                        <p class="mbt-service-feature-card__text"><?php echo esc_html($text); ?></p>
                    <?php endif; ?>

                    <?php if ($url) : ?>
                        </a>
                    <?php else : ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
