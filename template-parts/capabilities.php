<?php
$capabilities_kicker       = mbt_get_theme_mod_string('mbt_capabilities_kicker', __('Our Services', 'my-business-theme'));
$capabilities_title_main   = mbt_get_theme_mod_string('mbt_capabilities_title_main', __('Explore Our Luxury', 'my-business-theme'));
$capabilities_title_accent = mbt_get_theme_mod_string('mbt_capabilities_title_accent', __('Cabinetry Solution', 'my-business-theme'));
$primary_button_text       = mbt_get_theme_mod_string('mbt_capabilities_primary_text', __('Get a Quote', 'my-business-theme'));
$primary_button_type       = mbt_sanitize_header_button_type(get_theme_mod('mbt_capabilities_primary_type', 'popup'));
$primary_button_url        = mbt_sanitize_header_link_target(get_theme_mod('mbt_capabilities_primary_url', '#contact'));
$show_phone_button         = mbt_get_theme_mod_bool('mbt_capabilities_show_phone', true);
$card_button_text          = mbt_get_theme_mod_string('mbt_capabilities_card_button_text', __('Learn More', 'my-business-theme'));
$phone_number              = mbt_get_theme_mod_string('mbt_contact_phone', __('0435 777 797', 'my-business-theme'));
$capability_cards          = [];
$demo_cards                = mbt_get_demo_capability_cards();
$services_query            = mbt_get_posts('mbt_service', -1);

if ($services_query->have_posts()) {
    $demo_total = max(1, count($demo_cards));
    $index      = 0;

    while ($services_query->have_posts()) {
        $services_query->the_post();

        $fallback    = $demo_cards[$index % $demo_total] ?? [];
        $button_text = mbt_get_meta(get_the_ID(), '_mbt_button_text', $card_button_text);

        $capability_cards[] = [
            'title'       => get_the_title(),
            'url'         => mbt_get_meta(get_the_ID(), '_mbt_button_url', get_permalink()),
            'button_text' => $button_text !== '' ? $button_text : $card_button_text,
            'image'       => has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'mbt-service') : ($fallback['image'] ?? mbt_get_fallback_image_url('capability-1')),
        ];

        $index++;
    }

    wp_reset_postdata();
}

if (!$capability_cards) {
    foreach ($demo_cards as $card) {
        $capability_cards[] = [
            'title'       => $card['title'],
            'url'         => $card['url'],
            'button_text' => $card_button_text,
            'image'       => $card['image'],
        ];
    }
}

$track_classes = 'mbt-capabilities-carousel__track' . (count($capability_cards) === 1 ? ' mbt-capabilities-carousel__track--single' : '');
?>
<section id="capabilities" class="mbt-section mbt-capabilities-carousel">
    <div class="mbt-container">
        <div class="mbt-capabilities-carousel__header">
            <div class="mbt-capabilities-carousel__heading">
                <?php if ($capabilities_kicker !== '') : ?>
                    <p class="mbt-capabilities-carousel__kicker">
                        <span class="mbt-capabilities-carousel__kicker-dot" aria-hidden="true"></span>
                        <?php echo esc_html($capabilities_kicker); ?>
                    </p>
                <?php endif; ?>

                <h2 class="mbt-capabilities-carousel__title">
                    <span><?php echo esc_html($capabilities_title_main); ?></span>
                    <?php if ($capabilities_title_accent !== '') : ?>
                        <span class="mbt-capabilities-carousel__title-accent"><?php echo esc_html($capabilities_title_accent); ?></span>
                    <?php endif; ?>
                </h2>
            </div>

            <div class="mbt-capabilities-carousel__actions">
                <?php
                echo mbt_button([
                    'label' => $primary_button_text,
                    'url'   => $primary_button_url,
                    'type'  => $primary_button_type,
                    'class' => 'mbt-button mbt-button--small mbt-capabilities-carousel__action-button',
                ]);
                ?>

                <?php if ($show_phone_button && $phone_number !== '') : ?>
                    <a class="mbt-capabilities-carousel__phone" href="<?php echo esc_url(mbt_get_phone_link($phone_number)); ?>">
                        <span class="mbt-capabilities-carousel__phone-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.45 19.45 0 0 1-6-6A19.8 19.8 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.79.62 2.64a2 2 0 0 1-.45 2.11L8 9.91a16 16 0 0 0 6.09 6.09l1.44-1.23a2 2 0 0 1 2.11-.45c.85.29 1.74.5 2.64.62A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </span>
                        <span><?php echo esc_html($phone_number); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="mbt-capabilities-carousel__rail" data-mbt-carousel data-mbt-carousel-dots-count="3">
        <div class="mbt-capabilities-carousel__viewport">
            <div class="<?php echo esc_attr($track_classes); ?>" data-mbt-carousel-track aria-label="<?php esc_attr_e('Cabinetry service categories', 'my-business-theme'); ?>">
                <?php foreach ($capability_cards as $card) : ?>
                    <article class="mbt-capability-card">
                        <a class="mbt-capability-card__link" href="<?php echo esc_url($card['url']); ?>">
                            <img src="<?php echo esc_url($card['image']); ?>" alt="<?php echo esc_attr($card['title']); ?>">
                            <span class="mbt-capability-card__overlay" aria-hidden="true"></span>
                            <div class="mbt-capability-card__content">
                                <h3 class="mbt-capability-card__title"><?php echo esc_html($card['title']); ?></h3>
                                <span class="mbt-capability-card__button">
                                    <span><?php echo esc_html($card['button_text']); ?></span>
                                </span>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mbt-container">
            <div class="mbt-capabilities-carousel__controls">
                <button type="button" class="mbt-capabilities-carousel__arrow" data-mbt-carousel-prev aria-label="<?php esc_attr_e('Scroll services carousel backward', 'my-business-theme'); ?>">
                    <span class="mbt-carousel-arrow__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" focusable="false">
                            <path d="M15 5 8 12l7 7"></path>
                            <path d="M8 12h8"></path>
                        </svg>
                    </span>
                </button>
                <div class="mbt-capabilities-carousel__dots" data-mbt-carousel-dots aria-label="<?php esc_attr_e('Services carousel pagination', 'my-business-theme'); ?>"></div>
                <button type="button" class="mbt-capabilities-carousel__arrow" data-mbt-carousel-next aria-label="<?php esc_attr_e('Scroll services carousel forward', 'my-business-theme'); ?>">
                    <span class="mbt-carousel-arrow__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" focusable="false">
                            <path d="M9 5l7 7-7 7"></path>
                            <path d="M16 12H8"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    </div>
</section>
