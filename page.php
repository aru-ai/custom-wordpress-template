<?php get_header(); ?>
<section class="mbt-section">
    <div class="mbt-container">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('mbt-entry'); ?>>
                <h1 class="mbt-entry__title"><?php the_title(); ?></h1>
                <?php if (has_post_thumbnail()) : ?>
                    <div class="mbt-media-frame mbt-media-frame--landscape mbt-entry__image"><?php the_post_thumbnail('large'); ?></div>
                <?php endif; ?>
                <div class="mbt-entry__content"><?php the_content(); ?></div>
            </article>
        <?php endwhile; ?>
    </div>
</section>
<?php get_footer(); ?>
