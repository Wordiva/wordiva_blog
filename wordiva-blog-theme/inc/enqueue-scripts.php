<?php
/**
 * Enqueue scripts and styles
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue scripts and styles with performance optimizations
 */
function wordiva_theme_scripts() {
    $theme_version = wp_get_theme()->get('Version');
    $is_production = !WP_DEBUG;

    wp_enqueue_style(
        'wordiva-blog-theme-style',
        get_stylesheet_uri(),
        array(),
        $theme_version . ($is_production ? '' : '-' . time())
    );

    $critical_css = file_get_contents(get_template_directory() . '/assets/css/critical.css');
    if ($critical_css) {
        wp_add_inline_style('wordiva-blog-theme-style', $critical_css);
    }

    wp_enqueue_style(
        'wordiva-components',
        get_template_directory_uri() . '/assets/css/style.css',
        array('wordiva-blog-theme-style'),
        $theme_version,
        'all'
    );

    wp_enqueue_style(
        'wordiva-accessibility',
        get_template_directory_uri() . '/assets/css/accessibility.css',
        array('wordiva-blog-theme-style'),
        $theme_version,
        'all'
    );

    wp_enqueue_style(
        'wordiva-navigation',
        get_template_directory_uri() . '/assets/css/navigation.css',
        array('wordiva-blog-theme-style'),
        $theme_version,
        'all'
    );

    wp_enqueue_script(
        'wordiva-blog-theme-script',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        $theme_version . ($is_production ? '' : '-' . time()),
        true
    );

    if (is_singular('post')) {
        wp_enqueue_script(
            'wordiva-social-sharing',
            get_template_directory_uri() . '/assets/js/social-sharing.js',
            array(),
            $theme_version . ($is_production ? '' : '-' . time()),
            true
        );
    }

    wp_enqueue_script(
        'wordiva-navigation',
        get_template_directory_uri() . '/assets/js/navigation.js',
        array(),
        $theme_version . ($is_production ? '' : '-' . time()),
        true
    );

    wp_script_add_data('wordiva-navigation', 'defer', true);
    wp_script_add_data('wordiva-blog-theme-script', 'defer', true);
    if (is_singular('post')) {
        wp_script_add_data('wordiva-social-sharing', 'defer', true);
    }

    add_action('wp_head', function() {
        echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
        echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
    }, 1);

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
        body, html, .site-wrapper { background-color: #FFFFFF !important; }
        .wordiva-sticky-cta {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 999;
        }
        .wordiva-sticky-cta-link {
            display: inline-block;
            padding: 0.75rem 1.25rem;
            background: var(--wordiva-electric-blue);
            color: #fff;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 8px 24px rgba(47, 128, 255, 0.35);
        }
        .wordiva-category-chips { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem; }
        .wordiva-category-chip {
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            border: 1px solid #e2e8f0;
            text-decoration: none;
            font-size: 0.875rem;
        }
        .wordiva-newsletter-cta { margin: 2rem 0; padding: 1.5rem; border-radius: 0.75rem; background: #f8fafc; }
        .wordiva-product-links { margin: 2rem 0; padding: 1.25rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; }
        .wordiva-product-links-list { list-style: none; margin: 0; padding: 0; }
        .wordiva-product-links-list li + li { margin-top: 0.5rem; }
        .wordiva-topic-block { margin: 2rem 0; padding: 1.25rem; background: #f8fafc; border-radius: 0.75rem; }
        .wordiva-product-cta {
            margin: 0;
            padding: 0 2rem 2rem;
        }
        .wordiva-product-cta h3 {
            margin: 0 0 0.5rem;
        }
        .wordiva-product-cta p {
            margin: 0 0 1rem;
        }
        @media (max-width: 768px) {
            .wordiva-product-cta {
                padding: 0 1.5rem 1.5rem;
            }
        }
        @media (max-width: 480px) {
            .wordiva-product-cta {
                padding: 0 1rem 1.5rem;
            }
        }
    ";
    wp_add_inline_style('wordiva-blog-theme-style', $custom_css);
}
add_action('wp_enqueue_scripts', 'wordiva_theme_scripts');

/**
 * Localize main script and comment reply when needed.
 */
function wordiva_additional_scripts() {
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

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

        wp_enqueue_style(
            'wordiva-admin-style',
            get_template_directory_uri() . '/assets/css/admin.css',
            array(),
            $theme_version
        );

        $admin_js = get_template_directory() . '/assets/js/admin.min.js';
        wp_enqueue_script(
            'wordiva-admin-script',
            get_template_directory_uri() . '/assets/js/' . (WP_DEBUG || !file_exists($admin_js) ? 'admin.js' : 'admin.min.js'),
            array('jquery'),
            $theme_version,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'wordiva_admin_scripts');
