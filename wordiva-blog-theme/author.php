<?php
/**
 * Author archive template
 *
 * @package Wordiva_Theme
 */

get_header();

$author = get_queried_object();
$author_name = wordiva_get_author_display_name($author->ID);
?>

<main id="main" class="site-main archive-main author-archive" role="main">
    <div class="container">

        <header class="archive-header author-header">
            <div class="author-bio-avatar">
                <?php echo get_avatar($author->ID, 96, '', esc_attr($author_name)); ?>
            </div>
            <h1 class="archive-title"><?php echo esc_html($author_name); ?></h1>
            <?php if (!empty($author->description)) : ?>
                <div class="archive-description author-description">
                    <p><?php echo wp_kses_post($author->description); ?></p>
                </div>
            <?php endif; ?>
        </header>

        <?php if (have_posts()) : ?>
            <div class="post-grid index-post-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <div class="post-grid-item">
                        <?php get_template_part('template-parts/content', 'card'); ?>
                    </div>
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php esc_html_e('No posts by this author yet.', 'wordiva-blog-theme'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php
get_template_part('template-parts/sticky-cta');
get_footer();
