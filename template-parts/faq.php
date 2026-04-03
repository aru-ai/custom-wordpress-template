<?php
$items             = mbt_get_posts('mbt_faq', -1);
$faq_kicker        = mbt_get_theme_mod_string('mbt_faq_kicker', __("FAQ'S", 'my-business-theme'));
$faq_title_main    = mbt_get_theme_mod_string('mbt_faq_title_main', __('Your Questions,', 'my-business-theme'));
$faq_title_accent  = mbt_get_theme_mod_string('mbt_faq_title_accent', __('Answered', 'my-business-theme'));
$faq_intro         = mbt_get_theme_mod_string('mbt_faq_intro', __('Choose a proven team that delivers dependable results, clear communication, and work done right the first time.', 'my-business-theme'));
$faq_button_text   = mbt_get_theme_mod_string('mbt_faq_button_text', __('GET A QUOTE', 'my-business-theme'));
$faq_button_type   = mbt_sanitize_header_button_type(get_theme_mod('mbt_faq_button_type', 'popup'));
$faq_button_url    = mbt_sanitize_header_link_target(get_theme_mod('mbt_faq_button_url', '#contact'));
$faq_show_phone    = mbt_get_theme_mod_bool('mbt_faq_show_phone', true);
$phone_number      = mbt_get_theme_mod_string('mbt_contact_phone', __('0435 777 797', 'my-business-theme'));
?>
<section id="faq" class="mbt-section mbt-faq-section">
    <div class="mbt-container mbt-faq-layout">
        <div class="mbt-faq-section__intro">
            <?php if ($faq_kicker !== '') : ?>
                <div class="mbt-faq-section__kicker-wrap">
                    <p class="mbt-faq-section__kicker">
                        <span class="mbt-faq-section__kicker-dot" aria-hidden="true"></span>
                        <?php echo esc_html($faq_kicker); ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($faq_title_main !== '' || $faq_title_accent !== '') : ?>
                <h2 class="mbt-faq-section__title">
                    <?php if ($faq_title_main !== '') : ?>
                        <span class="mbt-faq-section__title-main"><?php echo esc_html($faq_title_main); ?></span>
                    <?php endif; ?>
                    <?php if ($faq_title_accent !== '') : ?>
                        <span class="mbt-faq-section__title-accent"><?php echo esc_html($faq_title_accent); ?></span>
                    <?php endif; ?>
                </h2>
            <?php endif; ?>

            <?php if ($faq_intro !== '') : ?>
                <div class="mbt-faq-section__text"><?php echo wp_kses_post(wpautop($faq_intro)); ?></div>
            <?php endif; ?>

            <?php if ($faq_button_text !== '' || ($faq_show_phone && $phone_number !== '')) : ?>
                <div class="mbt-about-business__actions mbt-faq-section__actions">
                    <?php if ($faq_button_text !== '') : ?>
                        <?php
                        echo mbt_button([
                            'label' => $faq_button_text,
                            'url'   => $faq_button_url,
                            'type'  => $faq_button_type,
                            'class' => 'mbt-button mbt-about-business__button',
                            'modal' => 'mbt-contact-modal',
                        ]);
                        ?>
                    <?php endif; ?>

                    <?php if ($faq_show_phone && $phone_number !== '') : ?>
                        <a class="mbt-about-business__phone" href="<?php echo esc_url(mbt_get_phone_link($phone_number)); ?>">
                            <span class="mbt-about-business__phone-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.45 19.45 0 0 1-6-6A19.8 19.8 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.79.62 2.64a2 2 0 0 1-.45 2.11L8 9.91a16 16 0 0 0 6.09 6.09l1.44-1.23a2 2 0 0 1 2.11-.45c.85.29 1.74.5 2.64.62A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                            </span>
                            <span><?php echo esc_html($phone_number); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mbt-faq-list">
            <?php if ($items->have_posts()) : ?>
                <?php $faq_index = 0; ?>
                <?php while ($items->have_posts()) : $items->the_post(); ?>
                    <details class="mbt-faq-item"<?php echo $faq_index === 0 ? ' open' : ''; ?>>
                        <summary><?php the_title(); ?></summary>
                        <div class="mbt-prose"><?php echo apply_filters('the_content', get_the_content()); ?></div>
                    </details>
                    <?php $faq_index++; ?>
                <?php endwhile; wp_reset_postdata(); ?>
            <?php else : ?>
                <?php foreach (mbt_get_demo_faqs() as $faq_index => $item) : ?>
                    <details class="mbt-faq-item"<?php echo $faq_index === 0 ? ' open' : ''; ?>>
                        <summary><?php echo esc_html($item['question']); ?></summary>
                        <div class="mbt-prose"><p><?php echo esc_html($item['answer']); ?></p></div>
                    </details>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
