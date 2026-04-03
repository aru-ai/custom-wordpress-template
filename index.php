<?php get_header(); ?>
<section class="mbt-section">
    <div class="mbt-container">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('mbt-entry'); ?>>
                    <h1 class="mbt-entry__title"><?php the_title(); ?></h1>
                    <div class="mbt-entry__content"><?php the_content(); ?></div>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p><?php esc_html_e('No content found.', 'my-business-theme'); ?></p>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
