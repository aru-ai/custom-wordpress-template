<?php
$testimonials_kicker       = mbt_get_theme_mod_string('mbt_testimonials_kicker', __('Our Testimonials', 'my-business-theme'));
$testimonials_title_main   = mbt_get_theme_mod_string('mbt_testimonials_title_main', __('Client Experiences With', 'my-business-theme'));
$testimonials_title_accent = mbt_get_theme_mod_string('mbt_testimonials_title_accent', __('Lumi Cabinets', 'my-business-theme'));
$review_source_label       = mbt_get_theme_mod_string('mbt_testimonials_review_source', __('Based on Google Review', 'my-business-theme'));
$testimonials              = mbt_get_customizer_testimonials();

if (!$testimonials) {
    $testimonials = mbt_get_demo_testimonials();
}

$track_classes = 'mbt-testimonials-showcase__track' . (count($testimonials) === 1 ? ' mbt-testimonials-showcase__track--single' : '');
?>
<section id="testimonials" class="mbt-section mbt-testimonials-showcase">
    <div class="mbt-testimonials-showcase__shell" data-mbt-carousel data-mbt-carousel-dots-count="<?php echo esc_attr(min(6, max(1, count($testimonials)))); ?>">
        <div class="mbt-container">
            <div class="mbt-testimonials-showcase__heading">
                <?php if ($testimonials_kicker !== '') : ?>
                    <p class="mbt-testimonials-showcase__kicker">
                        <span class="mbt-testimonials-showcase__kicker-dot" aria-hidden="true"></span>
                        <?php echo esc_html($testimonials_kicker); ?>
                    </p>
                <?php endif; ?>

                <h2 class="mbt-testimonials-showcase__title">
                    <?php if ($testimonials_title_main !== '') : ?>
                        <span><?php echo esc_html($testimonials_title_main); ?></span>
                    <?php endif; ?>
                    <?php if ($testimonials_title_accent !== '') : ?>
                        <span class="mbt-testimonials-showcase__title-accent"><?php echo esc_html($testimonials_title_accent); ?></span>
                    <?php endif; ?>
                </h2>
            </div>

            <div class="mbt-testimonials-showcase__ornament mbt-testimonials-showcase__ornament--left" aria-hidden="true"></div>

            <div class="mbt-testimonials-showcase__carousel">
                <button type="button" class="mbt-testimonials-showcase__arrow mbt-testimonials-showcase__arrow--side" data-mbt-carousel-prev aria-label="<?php esc_attr_e('Show previous testimonial', 'my-business-theme'); ?>">
                    <span class="mbt-carousel-arrow__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" focusable="false">
                            <path d="M15 5 8 12l7 7"></path>
                            <path d="M8 12h8"></path>
                        </svg>
                    </span>
                </button>

                <div class="mbt-testimonials-showcase__viewport">
                    <div class="<?php echo esc_attr($track_classes); ?>" data-mbt-carousel-track aria-label="<?php esc_attr_e('Client testimonials', 'my-business-theme'); ?>">
                        <?php foreach ($testimonials as $testimonial) : ?>
                            <?php
                            $name    = $testimonial['name'] ?? '';
                            $source  = $testimonial['source'] ?? '';
                            $content = $testimonial['content'] ?? '';
                            $rating  = max(1, min(5, (int) ($testimonial['rating'] ?? 5)));
                            ?>
                            <article class="mbt-testimonial-review" data-mbt-carousel-item>
                                <div class="mbt-testimonial-review__body">
                                    <p class="mbt-testimonial-review__content"><?php echo esc_html($content); ?></p>
                                    <div class="mbt-testimonial-review__footer">
                                        <div class="mbt-testimonial-review__identity">
                                            <strong><?php echo esc_html($name); ?></strong>
                                            <span><?php echo esc_html($source !== '' ? $source : $review_source_label); ?></span>
                                        </div>
                                        <div class="mbt-testimonial-review__rating-group">
                                            <div class="mbt-testimonial-review__stars" aria-label="<?php echo esc_attr(sprintf(__('%d out of 5 stars', 'my-business-theme'), $rating)); ?>">
                                                <?php echo wp_kses_post(mbt_render_stars_html($rating)); ?>
                                            </div>
                                            <span class="mbt-testimonial-review__brand" aria-hidden="true">Google</span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="button" class="mbt-testimonials-showcase__arrow mbt-testimonials-showcase__arrow--side" data-mbt-carousel-next aria-label="<?php esc_attr_e('Show next testimonial', 'my-business-theme'); ?>">
                    <span class="mbt-carousel-arrow__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" focusable="false">
                            <path d="M9 5l7 7-7 7"></path>
                            <path d="M16 12H8"></path>
                        </svg>
                    </span>
                </button>
            </div>

            <div class="mbt-testimonials-showcase__footer">
                <div class="mbt-testimonials-showcase__controls">
                    <button type="button" class="mbt-testimonials-showcase__arrow mbt-testimonials-showcase__arrow--footer" data-mbt-carousel-prev aria-label="<?php esc_attr_e('Show previous testimonial', 'my-business-theme'); ?>">
                        <span class="mbt-carousel-arrow__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <path d="M15 5 8 12l7 7"></path>
                                <path d="M8 12h8"></path>
                            </svg>
                        </span>
                    </button>

                    <div class="mbt-capabilities-carousel__dots" data-mbt-carousel-dots aria-label="<?php esc_attr_e('Testimonials pagination', 'my-business-theme'); ?>"></div>

                    <button type="button" class="mbt-testimonials-showcase__arrow mbt-testimonials-showcase__arrow--footer" data-mbt-carousel-next aria-label="<?php esc_attr_e('Show next testimonial', 'my-business-theme'); ?>">
                        <span class="mbt-carousel-arrow__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <path d="M9 5l7 7-7 7"></path>
                                <path d="M16 12H8"></path>
                            </svg>
                        </span>
                    </button>
                </div>

                <div class="mbt-testimonials-showcase__ornament mbt-testimonials-showcase__ornament--right" aria-hidden="true"></div>
            </div>
        </div>
    </div>
</section>
