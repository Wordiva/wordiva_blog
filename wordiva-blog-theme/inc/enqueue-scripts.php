<?php
/**
 * Enqueue scripts and styles
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue scripts and styles with performance optimizations
 */
function wordiva_theme_scripts() {
    $theme_version = wp_get_theme()->get('Version');
    $is_production = !WP_DEBUG;
    
    // Enqueue main theme stylesheet (consolidated - contains all base styles)
    wp_enqueue_style(
        'wordiva-blog-theme-style',
        get_stylesheet_uri(), // Uses the main style.css file
        array(),
        $theme_version . ($is_production ? '' : '-' . time())
    );
    
    // Enqueue critical CSS inline for above-the-fold content
    $critical_css = file_get_contents(get_template_directory() . '/assets/css/critical.css');
    if ($critical_css) {
        wp_add_inline_style('wordiva-blog-theme-style', $critical_css);
    }
    
    // Enqueue additional component styles (Slack-inspired grid, etc.)
    wp_enqueue_style(
        'wordiva-components',
        get_template_directory_uri() . '/assets/css/style.css',
        array('wordiva-blog-theme-style'),
        $theme_version,
        'all'
    );
    
    // Enqueue accessibility enhancements
    wp_enqueue_style(
        'wordiva-accessibility',
        get_template_directory_uri() . '/assets/css/accessibility.css',
        array('wordiva-blog-theme-style'),
        $theme_version,
        'all'
    );
    
    // Enqueue landing page navigation styles
    wp_enqueue_style(
        'wordiva-navigation',
        get_template_directory_uri() . '/assets/css/navigation.css',
        array('wordiva-blog-theme-style'),
        $theme_version,
        'all'
    );
    
    // Enqueue mobile navigation fix (high priority)
    wp_enqueue_style(
        'wordiva-navigation-mobile-fix',
        get_template_directory_uri() . '/assets/css/navigation-mobile-fix.css',
        array('wordiva-navigation'),
        $theme_version,
        'all'
    );
    
    // Enqueue main JavaScript
    wp_enqueue_script(
        'wordiva-blog-theme-script',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        $theme_version . ($is_production ? '' : '-' . time()),
        true
    );
    
    // Enqueue social sharing script
    wp_enqueue_script(
        'wordiva-social-sharing',
        get_template_directory_uri() . '/assets/js/social-sharing.js',
        array(),
        $theme_version . ($is_production ? '' : '-' . time()),
        true
    );
    
    // Enqueue navigation script
    wp_enqueue_script(
        'wordiva-navigation',
        get_template_directory_uri() . '/assets/js/navigation.js',
        array(),
        $theme_version . ($is_production ? '' : '-' . time()),
        true
    );
    
    // Enqueue simple navigation script (backup/override)
    wp_enqueue_script(
        'wordiva-navigation-simple',
        get_template_directory_uri() . '/assets/js/navigation-simple.js',
        array(),
        $theme_version . ($is_production ? '' : '-' . time()),
        true
    );
    
    // Enqueue absolute mobile menu fix (highest priority)
    wp_enqueue_script(
        'wordiva-mobile-menu-fix',
        get_template_directory_uri() . '/assets/js/mobile-menu-fix.js',
        array(),
        $theme_version . ($is_production ? '' : '-' . time()),
        true
    );
    
    // Enqueue navigation scroll script
    wp_enqueue_script(
        'wordiva-navigation-scroll',
        get_template_directory_uri() . '/assets/js/navigation-scroll.js',
        array(),
        $theme_version . ($is_production ? '' : '-' . time()),
        true
    );
    
    // Add resource hints for performance
    add_action('wp_head', function() use ($theme_version, $is_production) {
        // Preload critical fonts
        echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        
        // DNS prefetch for external resources
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
        echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
        
        // Preload critical images
        $fallback_images = [
            'fallback-card.svg',
            'fallback-featured.svg',
            'fallback-large.svg'
        ];
        
        foreach ($fallback_images as $image) {
            echo '<link rel="preload" href="' . esc_url(get_template_directory_uri() . '/assets/images/' . $image) . '" as="image">' . "\n";
        }
    }, 1);
    
    // Add inline styles for dynamic colors and ensure white background
    $custom_css = "
        :root {
            --wordiva-electric-blue: #2F80FF;
            --wordiva-royal-purple: #7B4DFF;
            --wordiva-neon-pink: #FF4FA3;
            --wordiva-sunrise-orange: #FF9F1C;
            --wordiva-golden-yellow: #FFD166;
            --wordiva-charcoal-dark: #2B2B2B;
            --wordiva-white: #FFFFFF;
        }
        
        /* Ensure white background - override any conflicts */
        body, html {
            background-color: #FFFFFF !important;
            background: #FFFFFF !important;
        }
        
        .site-wrapper {
            background-color: #FFFFFF !important;
        }
        
        /* Performance optimizations */
        img {
            loading: lazy;
        }
        
        .blog-card img,
        .featured-card img {
            loading: eager;
        }
    ";
    wp_add_inline_style('wordiva-blog-theme-style', $custom_css);
}
add_action('wp_enqueue_scripts', 'wordiva_theme_scripts');

/**
 * Fix any background color conflicts and ensure white background
 * Note: Styles moved to main stylesheet for better performance
 */
function wordiva_fix_background_color() {
    // This function now only adds necessary body classes
    // All styles have been moved to the main stylesheet
}
add_action('wp_head', 'wordiva_fix_background_color', 999);

/**
 * Register additional JavaScript files
 */
function wordiva_additional_scripts() {
    // Social sharing functionality
    wp_enqueue_script(
        'wordiva-social-sharing',
        get_template_directory_uri() . '/assets/js/social-sharing.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );
    
    // Enqueue comment reply script if needed
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // Localize script for AJAX
    wp_localize_script('wordiva-blog-theme-script', 'wordiva_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('wordiva_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'wordiva_additional_scripts');

/**
 * Admin enqueue scripts with enhanced meta box styling
 */
function wordiva_admin_scripts($hook) {
    if ('post.php' === $hook || 'post-new.php' === $hook) {
        $theme_version = wp_get_theme()->get('Version');
        $is_production = !WP_DEBUG;
        
        wp_enqueue_style(
            'wordiva-admin-style',
            get_template_directory_uri() . '/assets/css/admin.css',
            array(),
            $theme_version
        );
        
        // All admin styles are now in the admin.css file for better performance
        
        // Add JavaScript for meta box interactions (minified in production)
        if ($is_production && file_exists(get_template_directory() . '/assets/js/admin.min.js')) {
            wp_enqueue_script(
                'wordiva-admin-script',
                get_template_directory_uri() . '/assets/js/admin.min.js',
                array('jquery'),
                $theme_version,
                true
            );
        } else {
            wp_enqueue_script(
                'wordiva-admin-script',
                get_template_directory_uri() . '/assets/js/admin.js',
                array('jquery'),
                $theme_version,
                true
            );
        }
    }
}
add_action('admin_enqueue_scripts', 'wordiva_admin_scripts');