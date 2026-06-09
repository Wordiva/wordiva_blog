<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main error-404-main" role="main" itemscope itemtype="https://schema.org/WebPage">
    <section class="error-404-hero">
        <div class="container">
            <div class="error-404-content">
                <div class="error-404-number" aria-hidden="true">404</div>
                <h1 class="error-404-title"><?php esc_html_e('Page not found', 'wordiva-blog-theme'); ?></h1>
                <p class="error-404-description">
                    <?php esc_html_e("Sorry, we couldn't find the page you're looking for.", 'wordiva-blog-theme'); ?>
                </p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="error-404-btn">
                    <?php esc_html_e('Go back home', 'wordiva-blog-theme'); ?>
                </a>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>