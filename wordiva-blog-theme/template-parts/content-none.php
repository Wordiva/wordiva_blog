<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */
?>

<section class="no-results not-found" role="alert">
    <header class="page-header">
        <h2 class="page-title"><?php esc_html_e('Nothing here', 'wordiva-blog-theme'); ?></h2>
    </header>

    <div class="page-content">
        <?php if (is_home() && current_user_can('publish_posts')) : ?>
            <p>
                <?php
                printf(
                    wp_kses(
                        /* translators: 1: link to WP admin new post page. */
                        __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'wordiva-blog-theme'),
                        array(
                            'a' => array(
                                'href' => array(),
                            ),
                        )
                    ),
                    esc_url(admin_url('post-new.php'))
                );
                ?>
            </p>
        <?php elseif (is_search()) : ?>
            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'wordiva-blog-theme'); ?></p>
            <div aria-label="<?php esc_attr_e('Search again', 'wordiva-blog-theme'); ?>">
                <?php get_search_form(); ?>
            </div>
        <?php else : ?>
            <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'wordiva-blog-theme'); ?></p>
            <div aria-label="<?php esc_attr_e('Search the site', 'wordiva-blog-theme'); ?>">
                <?php get_search_form(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>