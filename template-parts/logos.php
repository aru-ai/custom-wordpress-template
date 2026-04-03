<?php
$logo_items = [];
$logos      = mbt_get_posts('mbt_partner', -1);

if ($logos->have_posts()) {
    while ($logos->have_posts()) {
        $logos->the_post();

        $logo_items[] = [
            'label' => get_the_title(),
            'url'   => mbt_get_meta(get_the_ID(), '_mbt_partner_url'),
            'image' => get_the_post_thumbnail_url(get_the_ID(), 'mbt-logo') ?: '',
        ];
    }

    wp_reset_postdata();
}

if (!$logo_items) {
    $logo_items = mbt_get_customizer_logo_strip_items();
}

if (!$logo_items) {
    $logo_items = mbt_get_demo_logo_strip_items();
}
?>
<section class="mbt-logo-strip">
    <?php if ($logo_items) : ?>
        <div class="mbt-logo-strip__viewport">
            <div class="mbt-logo-strip__track">
                <?php for ($copy = 0; $copy < 2; $copy++) : ?>
                    <div class="mbt-logo-strip__group"<?php echo $copy === 1 ? ' aria-hidden="true"' : ''; ?>>
                        <?php foreach ($logo_items as $logo_item) : ?>
                            <?php
                            $label = $logo_item['label'] ?? '';
                            $url   = $logo_item['url'] ?? '';
                            $image = $logo_item['image'] ?? '';
                            ?>
                            <div class="mbt-logo-chip">
                                <?php if ($url) : ?><a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer"><?php endif; ?>
                                <?php if ($image) : ?>
                                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($label); ?>">
                                <?php else : ?>
                                    <span><?php echo esc_html($label); ?></span>
                                <?php endif; ?>
                                <?php if ($url) : ?></a><?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    <?php else : ?>
        <div class="mbt-logo-strip__viewport">
            <div class="mbt-logo-strip__track">
                <?php for ($copy = 0; $copy < 2; $copy++) : ?>
                    <div class="mbt-logo-strip__group"<?php echo $copy === 1 ? ' aria-hidden="true"' : ''; ?>>
                        <?php foreach (mbt_get_demo_logos() as $logo) : ?>
                            <div class="mbt-logo-chip">
                                <span><?php echo esc_html($logo); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
