<?php
$process_kicker       = mbt_get_theme_mod_string('mbt_process_kicker', __('Our Process', 'my-business-theme'));
$process_title_main   = mbt_get_theme_mod_string('mbt_process_title_main', __('How We Bring Your', 'my-business-theme'));
$process_title_accent = mbt_get_theme_mod_string('mbt_process_title_accent', __('Cabinetry to Life', 'my-business-theme'));
$process_button_text  = mbt_get_theme_mod_string('mbt_process_button_text', __('GET A QUOTE', 'my-business-theme'));
$process_button_type  = mbt_sanitize_header_button_type(get_theme_mod('mbt_process_button_type', 'popup'));
$process_button_url   = mbt_sanitize_header_link_target(get_theme_mod('mbt_process_button_url', '#contact'));
$process_show_phone   = mbt_get_theme_mod_bool('mbt_process_show_phone', true);
$phone_number         = mbt_get_theme_mod_string('mbt_contact_phone', __('0435 777 797', 'my-business-theme'));

$process_steps = [];

for ($index = 1; $index <= 6; $index++) {
    $default_title = '';
    $default_text  = '';

    if ($index === 1) {
        $default_title = __('Consultation & Site Measure', 'my-business-theme');
        $default_text  = __('We take time to understand your home, lifestyle and vision, developing a tailored cabinetry concept with clear guidance and transparent pricing.', 'my-business-theme');
    } elseif ($index === 2) {
        $default_title = __('Custom Build & Installation', 'my-business-theme');
        $default_text  = __('Your cabinetry is precision-crafted and expertly installed to ensure a seamless fit, refined finish and flawless day-to-day functionality.', 'my-business-theme');
    } elseif ($index === 3) {
        $default_title = __('Final Detailing & Handover', 'my-business-theme');
        $default_text  = __('We complete meticulous final checks, walk you through the finished space and remain available for ongoing support when required.', 'my-business-theme');
    }

    $title = mbt_get_theme_mod_string('mbt_process_step_' . $index . '_title', $default_title);
    $text  = mbt_get_theme_mod_string('mbt_process_step_' . $index . '_text', $default_text);

    if ($title === '' && $text === '') {
        continue;
    }

    $process_steps[] = [
        'index' => sprintf('%02d', $index),
        'title' => $title,
        'text'  => $text,
    ];
}
?>
<section id="process" class="mbt-section mbt-process-section">
    <div class="mbt-container">
        <div class="mbt-process-section__header">
            <div class="mbt-process-section__intro">
                <?php if ($process_kicker !== '') : ?>
                    <div class="mbt-process-section__kicker-wrap">
                        <p class="mbt-process-section__kicker">
                            <span class="mbt-process-section__kicker-dot" aria-hidden="true"></span>
                            <?php echo esc_html($process_kicker); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if ($process_title_main !== '' || $process_title_accent !== '') : ?>
                    <h2 class="mbt-process-section__title">
                        <?php if ($process_title_main !== '') : ?>
                            <span class="mbt-process-section__title-main"><?php echo esc_html($process_title_main); ?></span>
                        <?php endif; ?>
                        <?php if ($process_title_accent !== '') : ?>
                            <span class="mbt-process-section__title-accent"><?php echo esc_html($process_title_accent); ?></span>
                        <?php endif; ?>
                    </h2>
                <?php endif; ?>
            </div>

            <?php if ($process_button_text !== '' || ($process_show_phone && $phone_number !== '')) : ?>
                <div class="mbt-process-section__actions">
                    <?php if ($process_button_text !== '') : ?>
                        <?php
                        echo mbt_button([
                            'label' => $process_button_text,
                            'url'   => $process_button_url,
                            'type'  => $process_button_type,
                            'class' => 'mbt-button mbt-process-section__button',
                            'modal' => 'mbt-contact-modal',
                        ]);
                        ?>
                    <?php endif; ?>

                    <?php if ($process_show_phone && $phone_number !== '') : ?>
                        <a class="mbt-about-business__phone mbt-process-section__phone" href="<?php echo esc_url(mbt_get_phone_link($phone_number)); ?>">
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

        <?php if ($process_steps) : ?>
            <div class="mbt-process-grid">
                <?php foreach ($process_steps as $step) : ?>
                    <article class="mbt-process-card">
                        <span class="mbt-process-card__index" aria-hidden="true"><?php echo esc_html($step['index']); ?></span>
                        <h3><?php echo esc_html($step['title']); ?></h3>
                        <p><?php echo esc_html($step['text']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
