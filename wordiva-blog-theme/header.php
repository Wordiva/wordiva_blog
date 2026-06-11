<?php
/**
 * The header for our theme - Landing Page Navigation
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

$is_blog_context = is_home() || is_singular('post') || is_archive() || is_search();
?>
<!doctype html>
<html <?php language_attributes(); ?> itemscope itemtype="https://schema.org/WebPage">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="https://schema.org/WebPage">
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to content', 'wordiva-blog-theme'); ?></a>

<div class="site-wrapper" itemscope itemtype="https://schema.org/WebSite">
    
    <nav class="wordiva-nav-fixed" id="wordiva-nav" role="navigation" aria-label="<?php esc_attr_e('Main Navigation', 'wordiva-blog-theme'); ?>">
        <div class="wordiva-nav-inner">
            <div class="wordiva-nav-content">
                
                <div class="wordiva-nav-logo">
                    <?php wordiva_render_logo(); ?>
                </div>
                
                <div class="wordiva-nav-desktop">
                    <a href="<?php echo esc_url(wordiva_get_main_site_anchor('features')); ?>" class="wordiva-nav-link">
                        <?php esc_html_e('Features', 'wordiva-blog-theme'); ?>
                    </a>
                    <a href="<?php echo esc_url(wordiva_get_main_site_anchor('workflow')); ?>" class="wordiva-nav-link">
                        <?php esc_html_e('How it works', 'wordiva-blog-theme'); ?>
                    </a>
                    <a href="<?php echo esc_url(wordiva_get_main_site_anchor('pricing')); ?>" class="wordiva-nav-link">
                        <?php esc_html_e('Pricing', 'wordiva-blog-theme'); ?>
                    </a>
                    <a href="<?php echo esc_url(wordiva_get_blog_url()); ?>" class="wordiva-nav-link <?php echo $is_blog_context ? 'active' : ''; ?>">
                        <?php esc_html_e('Blog', 'wordiva-blog-theme'); ?>
                    </a>
                </div>

                <div class="wordiva-nav-actions wordiva-nav-desktop">
                    <a href="<?php echo esc_url(wordiva_get_sign_in_url()); ?>" class="wordiva-sign-in-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="m10 17 5-5-5-5"></path>
                            <path d="M15 12H3"></path>
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        </svg>
                        <?php esc_html_e('Sign In', 'wordiva-blog-theme'); ?>
                    </a>
                    <a href="<?php echo esc_url(wordiva_get_cta_url()); ?>" class="wordiva-cta-button">
                        <?php esc_html_e('Get Started', 'wordiva-blog-theme'); ?>
                    </a>
                </div>
                
                <div class="wordiva-nav-mobile-toggle">
                    <button id="wordiva-mobile-btn" 
                            class="wordiva-mobile-button" 
                            aria-label="<?php esc_attr_e('Toggle menu', 'wordiva-blog-theme'); ?>"
                            aria-expanded="false">
                        <svg class="wordiva-hamburger-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path class="wordiva-hamburger-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path class="wordiva-hamburger-close" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
            </div>
        </div>
        
        <div class="wordiva-nav-mobile-menu" id="wordiva-mobile-menu">
            <div class="wordiva-nav-mobile-inner">
                <a href="<?php echo esc_url(wordiva_get_main_site_anchor('features')); ?>" class="wordiva-nav-mobile-link">
                    <?php esc_html_e('Features', 'wordiva-blog-theme'); ?>
                </a>
                <a href="<?php echo esc_url(wordiva_get_main_site_anchor('workflow')); ?>" class="wordiva-nav-mobile-link">
                    <?php esc_html_e('How it works', 'wordiva-blog-theme'); ?>
                </a>
                <a href="<?php echo esc_url(wordiva_get_main_site_anchor('pricing')); ?>" class="wordiva-nav-mobile-link">
                    <?php esc_html_e('Pricing', 'wordiva-blog-theme'); ?>
                </a>
                <a href="<?php echo esc_url(wordiva_get_blog_url()); ?>" class="wordiva-nav-mobile-link <?php echo $is_blog_context ? 'active' : ''; ?>">
                    <?php esc_html_e('Blog', 'wordiva-blog-theme'); ?>
                </a>
                <a href="<?php echo esc_url(wordiva_get_sign_in_url()); ?>" class="wordiva-sign-in-button-mobile">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="m10 17 5-5-5-5"></path>
                        <path d="M15 12H3"></path>
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                    </svg>
                    <?php esc_html_e('Sign In', 'wordiva-blog-theme'); ?>
                </a>
                <a href="<?php echo esc_url(wordiva_get_cta_url()); ?>" class="wordiva-cta-button-mobile">
                    <?php esc_html_e('Get Started', 'wordiva-blog-theme'); ?>
                </a>
            </div>
        </div>
    </nav>
    
    <?php if (is_single() && get_post_type() === 'post') : ?>
        <div class="header-breadcrumb-wrapper">
            <div class="wordiva-nav-inner">
                <?php get_template_part('template-parts/breadcrumbs'); ?>
            </div>
        </div>
    <?php elseif ((is_category() || is_tag() || is_date() || is_page_template('page-sitemap.php')) && !is_author()) : ?>
        <div class="header-breadcrumb-wrapper">
            <div class="wordiva-nav-inner container">
                <?php get_template_part('template-parts/breadcrumbs'); ?>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="site-content"><?php // Content starts here, closed in footer.php ?>
