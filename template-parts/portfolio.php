<?php
$items                  = mbt_get_posts('mbt_portfolio', -1);
$portfolio_kicker       = mbt_get_theme_mod_string('mbt_portfolio_kicker', __('Our Recent Projects', 'my-business-theme'));
$portfolio_title_main   = mbt_get_theme_mod_string('mbt_portfolio_title_main', __('Our Portfolio Of Work', 'my-business-theme'));
$portfolio_title_accent = mbt_get_theme_mod_string('mbt_portfolio_title_accent', __('To Boast About', 'my-business-theme'));
$portfolio_intro        = mbt_get_theme_mod_string('mbt_portfolio_intro', '');
$portfolio_items        = [];
$demo_items             = mbt_get_demo_portfolio_items();

if ($items->have_posts()) {
    $demo_total = max(1, count($demo_items));
    $index      = 0;

    while ($items->have_posts()) {
        $items->the_post();

        $fallback       = $demo_items[$index % $demo_total] ?? [];
        $thumbnail_id   = get_post_thumbnail_id();
        $thumbnail_url  = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'mbt-portfolio') : '';
        $full_image_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : '';
        $fallback_image = $fallback['image'] ?? mbt_get_fallback_image_url('portfolio-1');
        $title          = get_the_title();

        $portfolio_items[] = [
            'title'      => $title !== '' ? $title : sprintf(__('Project %d', 'my-business-theme'), $index + 1),
            'image'      => $thumbnail_url ?: $fallback_image,
            'full_image' => $full_image_url ?: ($thumbnail_url ?: $fallback_image),
        ];

        $index++;
    }

    wp_reset_postdata();
}

if (!$portfolio_items) {
    foreach ($demo_items as $item) {
        $portfolio_items[] = [
            'title'      => $item['title'],
            'image'      => $item['image'],
            'full_image' => $item['image'],
        ];
    }
}

$mosaic_classes = 'mbt-portfolio-mosaic';
if (count($portfolio_items) <= 3) {
    $mosaic_classes .= ' mbt-portfolio-mosaic--simple';
}
?>
<section id="portfolio" class="mbt-section mbt-portfolio-showcase">
    <div class="mbt-container">
        <div class="mbt-portfolio-showcase__heading">
            <?php if ($portfolio_kicker !== '') : ?>
                <p class="mbt-portfolio-showcase__kicker">
                    <span class="mbt-portfolio-showcase__kicker-dot" aria-hidden="true"></span>
                    <?php echo esc_html($portfolio_kicker); ?>
                </p>
            <?php endif; ?>

            <h2 class="mbt-portfolio-showcase__title">
                <?php if ($portfolio_title_main !== '') : ?>
                    <span><?php echo esc_html($portfolio_title_main); ?></span>
                <?php endif; ?>
                <?php if ($portfolio_title_accent !== '') : ?>
                    <span class="mbt-portfolio-showcase__title-accent"><?php echo esc_html($portfolio_title_accent); ?></span>
                <?php endif; ?>
            </h2>

            <?php if ($portfolio_intro !== '') : ?>
                <p class="mbt-portfolio-showcase__intro"><?php echo esc_html($portfolio_intro); ?></p>
            <?php endif; ?>
        </div>

        <div class="<?php echo esc_attr($mosaic_classes); ?>">
            <?php foreach ($portfolio_items as $item) : ?>
                <article class="mbt-portfolio-card">
                    <button
                        type="button"
                        class="mbt-portfolio-card__trigger"
                        data-mbt-lightbox-trigger
                        data-mbt-lightbox-image="<?php echo esc_url($item['full_image']); ?>"
                        data-mbt-lightbox-title="<?php echo esc_attr($item['title']); ?>"
                        aria-label="<?php echo esc_attr(sprintf(__('Open %s in lightbox', 'my-business-theme'), $item['title'])); ?>"
                    >
                        <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title']); ?>">
                        <span class="screen-reader-text"><?php echo esc_html($item['title']); ?></span>
                    </button>
                </article>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="mbt-portfolio-lightbox" class="mbt-lightbox" aria-hidden="true">
        <button type="button" class="mbt-lightbox__overlay" data-mbt-lightbox-close aria-label="<?php esc_attr_e('Close portfolio lightbox', 'my-business-theme'); ?>"></button>
        <div class="mbt-lightbox__dialog" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Portfolio image preview', 'my-business-theme'); ?>">
            <button type="button" class="mbt-lightbox__close" data-mbt-lightbox-close aria-label="<?php esc_attr_e('Close lightbox', 'my-business-theme'); ?>">&times;</button>
            <figure class="mbt-lightbox__figure">
                <img src="" alt="" data-mbt-lightbox-image-target>
                <figcaption class="mbt-lightbox__caption" data-mbt-lightbox-caption></figcaption>
            </figure>
        </div>
    </div>
</section>
