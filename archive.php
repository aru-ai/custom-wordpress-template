<?php get_header(); ?>
<section class="mbt-section">
    <div class="mbt-container">
        <header class="mbt-archive-header">
            <h1><?php the_archive_title(); ?></h1>
            <?php the_archive_description('<div class="mbt-archive-description">', '</div>'); ?>
        </header>
        <div class="mbt-card-grid mbt-card-grid--three">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article <?php post_class('mbt-card'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <a class="mbt-media-frame mbt-media-frame--landscape" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('mbt-portfolio'); ?></a>
                    <?php endif; ?>
                    <div class="mbt-card__body">
                        <h2 class="mbt-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php echo esc_html(get_the_excerpt()); ?></p>
                    </div>
                </article>
            <?php endwhile; else : ?>
                <p><?php esc_html_e('No items found.', 'my-business-theme'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>
