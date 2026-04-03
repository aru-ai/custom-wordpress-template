<?php require locate_template('template-parts/testimonials-showcase.php'); return; ?>
<?php
$items                = mbt_get_posts('mbt_testimonial', -1);
$testimonials_title   = mbt_get_theme_mod_string('mbt_testimonials_title', __('Client Experiences With Lumi Cabinets', 'my-business-theme'));
$testimonials_graphic = mbt_theme_mod_uses_default('mbt_testimonials_title') ? mbt_get_section_graphic_url('testimonials-title') : '';
$testimonials_full    = (!$items->have_posts() && mbt_theme_mod_uses_default('mbt_testimonials_title')) ? mbt_get_section_graphic_url('testimonials-full') : '';
?>
<section id="testimonials" class="mbt-section mbt-section--dark">
    <div class="mbt-container">
        <?php if ($testimonials_full) : ?>
            <div class="mbt-composite-section">
                <img src="<?php echo esc_url($testimonials_full); ?>" alt="<?php echo esc_attr($testimonials_title); ?>">
            </div>
        <?php else : ?>
            <div class="mbt-section-heading mbt-section-heading--center mbt-section-heading--light">
                <p class="mbt-kicker"><?php esc_html_e('Testimonials', 'my-business-theme'); ?></p>
                <?php if ($testimonials_graphic) : ?>
                    <h2 class="screen-reader-text"><?php echo esc_html($testimonials_title); ?></h2>
                    <img class="mbt-heading-graphic mbt-heading-graphic--section mbt-heading-graphic--light" src="<?php echo esc_url($testimonials_graphic); ?>" alt="<?php echo esc_attr($testimonials_title); ?>">
                <?php else : ?>
                    <h2><?php echo esc_html($testimonials_title); ?></h2>
                <?php endif; ?>
            </div>
            <div class="mbt-testimonial-shell">
                <span class="mbt-carousel-arrow" aria-hidden="true">‹</span>
                <div class="mbt-card-grid mbt-card-grid--testimonials">
                    <?php while ($items->have_posts()) : $items->the_post(); ?>
                        <?php
                        $role   = mbt_get_meta(get_the_ID(), '_mbt_client_role');
                        $rating = (int) mbt_get_meta(get_the_ID(), '_mbt_rating', '5');
                        ?>
                        <article <?php post_class('mbt-card mbt-card--testimonial'); ?>>
                            <div class="mbt-card__body">
                                <div class="mbt-prose"><?php the_content(); ?></div>
                                <div class="mbt-testimonial-meta">
                                    <div>
                                        <strong><?php the_title(); ?></strong>
                                        <?php if ($role) : ?><div><?php echo esc_html($role); ?></div><?php endif; ?>
                                    </div>
                                    <div class="mbt-rating" aria-label="<?php echo esc_attr(sprintf(__('%d out of 5 stars', 'my-business-theme'), $rating)); ?>">
                                        <?php echo wp_kses_post(mbt_render_stars_html($rating)); ?>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
                <span class="mbt-carousel-arrow" aria-hidden="true">›</span>
            </div>
        <?php endif; ?>
    </div>
</section>
