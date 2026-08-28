<?php
/**
 * The header for our theme — two-layer mega menu matching wordiva.ai
 *
 * @package Wordiva_Theme
 * @since 2.0.0
 */

$is_blog_context = is_home() || is_singular('post') || is_archive() || is_search();
$wordiva_nav_menu = function_exists('wordiva_get_nav_menu') ? wordiva_get_nav_menu() : array();
?>
<!doctype html>
<html <?php language_attributes(); ?> data-theme="dark" itemscope itemtype="https://schema.org/WebPage">
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
                    <?php foreach ($wordiva_nav_menu as $entry) : ?>
                        <?php if (!empty($entry['groups'])) : ?>
                            <div class="wordiva-nav-item">
                                <a href="<?php echo esc_url($entry['groups'][0]['href']); ?>" class="wordiva-nav-link" aria-haspopup="true">
                                    <?php echo esc_html($entry['label']); ?>
                                    <svg class="wordiva-nav-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                                </a>
                                <div class="wordiva-mega-panel <?php echo count($entry['groups']) > 1 ? 'is-wide' : ''; ?>">
                                    <div class="wordiva-mega-panel-card">
                                        <?php foreach ($entry['groups'] as $group) : ?>
                                            <div class="wordiva-mega-group">
                                                <a href="<?php echo esc_url($group['href']); ?>" class="wordiva-mega-group-label">
                                                    <?php echo esc_html($group['label']); ?>
                                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
                                                </a>
                                                <ul class="wordiva-mega-list">
                                                    <?php foreach ($group['items'] as $item) : ?>
                                                        <li>
                                                            <a href="<?php echo esc_url($item['href']); ?>" class="wordiva-mega-link <?php echo (!empty($item['active_blog']) && $is_blog_context) ? 'active' : ''; ?>">
                                                                <span class="wordiva-mega-link-label"><?php echo esc_html($item['label']); ?></span>
                                                                <?php if (!empty($item['description'])) : ?>
                                                                    <span class="wordiva-mega-link-desc"><?php echo esc_html($item['description']); ?></span>
                                                                <?php endif; ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <a href="<?php echo esc_url($entry['href']); ?>" class="wordiva-nav-link">
                                <?php echo esc_html($entry['label']); ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <a href="<?php echo esc_url(wordiva_get_blog_url()); ?>" class="wordiva-nav-link <?php echo $is_blog_context ? 'active' : ''; ?>">
                        <?php esc_html_e('Blog', 'wordiva-blog-theme'); ?>
                    </a>
                </div>

                <div class="wordiva-nav-actions wordiva-nav-desktop">
                    <button type="button" class="wordiva-theme-toggle" data-wordiva-theme-toggle aria-label="<?php esc_attr_e('Toggle color theme', 'wordiva-blog-theme'); ?>">
                        <svg class="wordiva-icon-sun" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                        <svg class="wordiva-icon-moon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                    </button>
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
                    <button type="button" class="wordiva-theme-toggle wordiva-theme-toggle-mobile" data-wordiva-theme-toggle aria-label="<?php esc_attr_e('Toggle color theme', 'wordiva-blog-theme'); ?>">
                        <svg class="wordiva-icon-sun" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                        <svg class="wordiva-icon-moon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                    </button>
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
                <?php foreach ($wordiva_nav_menu as $mi => $entry) : ?>
                    <?php if (!empty($entry['groups'])) : ?>
                        <div class="wordiva-mobile-group">
                            <button type="button" class="wordiva-mobile-group-btn" aria-expanded="false" aria-controls="wordiva-mobile-group-<?php echo (int) $mi; ?>">
                                <?php echo esc_html($entry['label']); ?>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="wordiva-mobile-group-panel" id="wordiva-mobile-group-<?php echo (int) $mi; ?>">
                                <?php foreach ($entry['groups'] as $group) : ?>
                                    <a href="<?php echo esc_url($group['href']); ?>" class="wordiva-mobile-group-label"><?php echo esc_html($group['label']); ?></a>
                                    <?php foreach ($group['items'] as $item) : ?>
                                        <a href="<?php echo esc_url($item['href']); ?>" class="wordiva-nav-mobile-link"><?php echo esc_html($item['label']); ?></a>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo esc_url($entry['href']); ?>" class="wordiva-nav-mobile-link"><?php echo esc_html($entry['label']); ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
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
