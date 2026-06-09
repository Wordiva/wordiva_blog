<?php
/**
 * Theme customizer configuration
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme customizer
 */
function wordiva_customize_register($wp_customize) {
    // Add Wordiva section
    $wp_customize->add_section('wordiva_options', array(
        'title'    => __('Wordiva Options', 'wordiva-blog-theme'),
        'priority' => 30,
    ));
    
    // Header message setting
    $wp_customize->add_setting('wordiva_header_message', array(
        'default'           => 'Transform Your Content Strategy with AI',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('wordiva_header_message', array(
        'label'   => __('Header Message', 'wordiva-blog-theme'),
        'section' => 'wordiva_options',
        'type'    => 'text',
    ));
    
    // Header subtitle setting
    $wp_customize->add_setting('wordiva_header_subtitle', array(
        'default'           => 'Discover insights, strategies, and innovations in autonomous content creation. Wordiva empowers brands to create, optimize, and scale content marketing with artificial intelligence.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('wordiva_header_subtitle', array(
        'label'   => __('Header Subtitle', 'wordiva-blog-theme'),
        'section' => 'wordiva_options',
        'type'    => 'textarea',
    ));
}
add_action('customize_register', 'wordiva_customize_register');

/**
 * Enhanced WordPress Customizer
 */
function wordiva_enhanced_customize_register($wp_customize) {
    // Navigation URLs Section
    $wp_customize->add_section('wordiva_navigation', array(
        'title'    => __('Navigation URLs', 'wordiva-blog-theme'),
        'priority' => 20,
    ));
    
    // Main Website URL
    $wp_customize->add_setting('wordiva_main_site_url', array(
        'default'           => 'https://wordiva.ai',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('wordiva_main_site_url', array(
        'label'       => __('Main Website URL', 'wordiva-blog-theme'),
        'description' => __('URL for the main Wordiva website (used in navigation)', 'wordiva-blog-theme'),
        'section'     => 'wordiva_navigation',
        'type'        => 'url',
    ));
    
    // Blog URL
    $wp_customize->add_setting('wordiva_blog_url', array(
        'default'           => 'https://wordiva.ai/blog',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('wordiva_blog_url', array(
        'label'       => __('Blog URL', 'wordiva-blog-theme'),
        'description' => __('URL for the blog section (used in navigation)', 'wordiva-blog-theme'),
        'section'     => 'wordiva_navigation',
        'type'        => 'url',
    ));
    
    // Logo URL
    $wp_customize->add_setting('wordiva_logo_url', array(
        'default'           => get_template_directory_uri() . '/assets/images/icon.png',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('wordiva_logo_url', array(
        'label'       => __('Logo Image URL', 'wordiva-blog-theme'),
        'description' => __('URL for the logo image in navigation', 'wordiva-blog-theme'),
        'section'     => 'wordiva_navigation',
        'type'        => 'url',
    ));
    
    // CTA Button URL
    $wp_customize->add_setting('wordiva_cta_url', array(
        'default'           => 'https://wordiva.ai/register',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('wordiva_cta_url', array(
        'label'       => __('Get Started Button URL', 'wordiva-blog-theme'),
        'description' => __('URL for the "Get Started" button in navigation', 'wordiva-blog-theme'),
        'section'     => 'wordiva_navigation',
        'type'        => 'url',
    ));

    // Sign In URL
    $wp_customize->add_setting('wordiva_sign_in_url', array(
        'default'           => 'https://wordiva.ai/login',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('wordiva_sign_in_url', array(
        'label'       => __('Sign In Button URL', 'wordiva-blog-theme'),
        'description' => __('URL for the "Sign In" button in navigation', 'wordiva-blog-theme'),
        'section'     => 'wordiva_navigation',
        'type'        => 'url',
    ));
    
    // Brand Colors Section
    $wp_customize->add_section('wordiva_brand_colors', array(
        'title'    => __('Wordiva Brand Colors', 'wordiva-blog-theme'),
        'priority' => 25,
    ));
    
    // Electric Blue Color
    $wp_customize->add_setting('wordiva_electric_blue', array(
        'default'           => '#2F80FF',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'wordiva_electric_blue', array(
        'label'   => __('Electric Blue (Primary)', 'wordiva-blog-theme'),
        'section' => 'wordiva_brand_colors',
    )));
    
    // Royal Purple Color
    $wp_customize->add_setting('wordiva_royal_purple', array(
        'default'           => '#7B4DFF',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'wordiva_royal_purple', array(
        'label'   => __('Royal Purple (Secondary)', 'wordiva-blog-theme'),
        'section' => 'wordiva_brand_colors',
    )));
    
    // Neon Pink Color
    $wp_customize->add_setting('wordiva_neon_pink', array(
        'default'           => '#FF4FA3',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'wordiva_neon_pink', array(
        'label'   => __('Neon Pink (Accent)', 'wordiva-blog-theme'),
        'section' => 'wordiva_brand_colors',
    )));
    
    // Layout Options Section
    $wp_customize->add_section('wordiva_layout', array(
        'title'    => __('Layout Options', 'wordiva-blog-theme'),
        'priority' => 35,
    ));
    
    // Blog posts per page
    $wp_customize->add_setting('wordiva_posts_per_page', array(
        'default'           => 9,
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('wordiva_posts_per_page', array(
        'label'   => __('Blog Posts Per Page', 'wordiva-blog-theme'),
        'section' => 'wordiva_layout',
        'type'    => 'number',
        'input_attrs' => array(
            'min' => 3,
            'max' => 12,
            'step' => 3,
        ),
    ));
    
    // Show author info
    $wp_customize->add_setting('wordiva_show_author_info', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('wordiva_show_author_info', array(
        'label'   => __('Show Author Information', 'wordiva-blog-theme'),
        'section' => 'wordiva_layout',
        'type'    => 'checkbox',
    ));
    
    // Show reading time
    $wp_customize->add_setting('wordiva_show_reading_time', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('wordiva_show_reading_time', array(
        'label'   => __('Show Reading Time', 'wordiva-blog-theme'),
        'section' => 'wordiva_layout',
        'type'    => 'checkbox',
    ));
    
    // Footer Options Section
    $wp_customize->add_section('wordiva_footer', array(
        'title'    => __('Footer Options', 'wordiva-blog-theme'),
        'priority' => 40,
    ));
    
    // Footer copyright text
    $wp_customize->add_setting('wordiva_footer_copyright', array(
        'default'           => 'Wordiva. All rights reserved.',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('wordiva_footer_copyright', array(
        'label'   => __('Footer Copyright Text', 'wordiva-blog-theme'),
        'section' => 'wordiva_footer',
        'type'    => 'text',
    ));
    
    // Footer description
    $wp_customize->add_setting('wordiva_footer_description', array(
        'default'           => 'AI-Powered Content Marketing System that transforms how brands create, optimize, and scale their content strategy.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('wordiva_footer_description', array(
        'label'   => __('Footer Description', 'wordiva-blog-theme'),
        'section' => 'wordiva_footer',
        'type'    => 'textarea',
    ));
    
    // Footer credits
    $wp_customize->add_setting('wordiva_footer_credits', array(
        'default'           => 'Built with innovation and AI intelligence',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('wordiva_footer_credits', array(
        'label'   => __('Footer Credits Text', 'wordiva-blog-theme'),
        'section' => 'wordiva_footer',
        'type'    => 'text',
    ));
    
    // Social Media Section
    $wp_customize->add_section('wordiva_social_media', array(
        'title'    => __('Social Media Links', 'wordiva-blog-theme'),
        'priority' => 45,
    ));
    
    // Twitter URL
    $wp_customize->add_setting('wordiva_twitter_url', array(
        'default'           => 'https://twitter.com/wordiva',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('wordiva_twitter_url', array(
        'label'   => __('Twitter URL', 'wordiva-blog-theme'),
        'section' => 'wordiva_social_media',
        'type'    => 'url',
    ));
    
    // LinkedIn URL
    $wp_customize->add_setting('wordiva_linkedin_url', array(
        'default'           => 'https://www.linkedin.com/company/wordiva-ai/',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('wordiva_linkedin_url', array(
        'label'   => __('LinkedIn URL', 'wordiva-blog-theme'),
        'section' => 'wordiva_social_media',
        'type'    => 'url',
    ));
    
    // Facebook URL
    $wp_customize->add_setting('wordiva_facebook_url', array(
        'default'           => 'https://www.facebook.com/wordivaai/',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('wordiva_facebook_url', array(
        'label'   => __('Facebook URL', 'wordiva-blog-theme'),
        'section' => 'wordiva_social_media',
        'type'    => 'url',
    ));
    
    // Instagram URL
    $wp_customize->add_setting('wordiva_instagram_url', array(
        'default'           => 'https://www.instagram.com/wordivaai/',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('wordiva_instagram_url', array(
        'label'   => __('Instagram URL', 'wordiva-blog-theme'),
        'section' => 'wordiva_social_media',
        'type'    => 'url',
    ));
}
add_action('customize_register', 'wordiva_enhanced_customize_register');

/**
 * Output customizer CSS
 * Note: Styles moved to main stylesheet, this function now only handles dynamic color values
 */
function wordiva_customizer_css() {
    $electric_blue = get_theme_mod('wordiva_electric_blue', '#2F80FF');
    $royal_purple = get_theme_mod('wordiva_royal_purple', '#7B4DFF');
    $neon_pink = get_theme_mod('wordiva_neon_pink', '#FF4FA3');
    
    // Only output dynamic CSS custom properties
    $dynamic_css = ":root {
        --wordiva-electric-blue: {$electric_blue};
        --wordiva-royal-purple: {$royal_purple};
        --wordiva-neon-pink: {$neon_pink};
    }";
    
    wp_add_inline_style('wordiva-blog-theme-style', $dynamic_css);
}
add_action('wp_head', 'wordiva_customizer_css');

/**
 * Customizer live preview
 */
function wordiva_customize_preview_js() {
    wp_enqueue_script(
        'wordiva-customizer-preview',
        get_template_directory_uri() . '/assets/js/customizer-preview.js',
        array('customize-preview'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('customize_preview_init', 'wordiva_customize_preview_js');