<?php
/**
 * Theme setup and configuration
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup
 */
function wordiva_theme_setup() {
    // Add theme support for various features
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
    
    // Add support for responsive embeds
    add_theme_support('responsive-embeds');
    
    // Add support for editor styles
    add_theme_support('editor-styles');
    add_editor_style();
    
    // Add support for block styles
    add_theme_support('wp-block-styles');
    
    // Add support for wide and full alignment
    add_theme_support('align-wide');
    
    // Set content width
    if (!isset($content_width)) {
        $content_width = 1200;
    }
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'wordiva-blog-theme'),
        'footer' => __('Footer Menu', 'wordiva-blog-theme')
    ));
    
    // Add image sizes for slack.dev-inspired card layouts
    add_image_size('wordiva-featured', 800, 400, true);
    add_image_size('wordiva-blog-card', 400, 250, true);
    add_image_size('wordiva-card-large', 600, 375, true);
    add_image_size('wordiva-card-medium', 350, 220, true);
    add_image_size('wordiva-card-small', 280, 175, true);
    add_image_size('wordiva-hero-card', 1200, 600, true);
}
add_action('after_setup_theme', 'wordiva_theme_setup');

/**
 * Add enhanced theme support for slack.dev-inspired features
 */
function wordiva_add_theme_support() {
    // Add support for custom header
    add_theme_support('custom-header', array(
        'default-image'      => get_template_directory_uri() . '/assets/images/header-bg.jpg',
        'width'              => 1200,
        'height'             => 400,
        'flex-height'        => true,
        'flex-width'         => true,
        'uploads'            => true,
        'random-default'     => false,
        'header-text'        => false,
    ));
    
    // Add support for custom background
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ));
    
    // Add support for selective refresh
    add_theme_support('customize-selective-refresh-widgets');
    
    // Add support for wide and full alignment
    add_theme_support('align-wide');
    
    // Add support for responsive embeds
    add_theme_support('responsive-embeds');
    
    // Add support for custom line height
    add_theme_support('custom-line-height');
    
    // Add support for custom units
    add_theme_support('custom-units');
    
    // Add support for custom spacing
    add_theme_support('custom-spacing');
    
    // Add support for link color
    add_theme_support('link-color');
    
    // Add support for border
    add_theme_support('border');
    
    // Add support for block editor color palette
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => __('Electric Blue', 'wordiva-blog-theme'),
            'slug'  => 'electric-blue',
            'color' => '#2F80FF',
        ),
        array(
            'name'  => __('Royal Purple', 'wordiva-blog-theme'),
            'slug'  => 'royal-purple',
            'color' => '#7B4DFF',
        ),
        array(
            'name'  => __('Neon Pink', 'wordiva-blog-theme'),
            'slug'  => 'neon-pink',
            'color' => '#FF4FA3',
        ),
        array(
            'name'  => __('Sunrise Orange', 'wordiva-blog-theme'),
            'slug'  => 'sunrise-orange',
            'color' => '#FF9F1C',
        ),
        array(
            'name'  => __('Golden Yellow', 'wordiva-blog-theme'),
            'slug'  => 'golden-yellow',
            'color' => '#FFD166',
        ),
        array(
            'name'  => __('Charcoal Dark', 'wordiva-blog-theme'),
            'slug'  => 'charcoal-dark',
            'color' => '#2B2B2B',
        ),
    ));
    
    // Add support for block editor font sizes
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => __('Small', 'wordiva-blog-theme'),
            'size' => 14,
            'slug' => 'small'
        ),
        array(
            'name' => __('Normal', 'wordiva-blog-theme'),
            'size' => 16,
            'slug' => 'normal'
        ),
        array(
            'name' => __('Medium', 'wordiva-blog-theme'),
            'size' => 20,
            'slug' => 'medium'
        ),
        array(
            'name' => __('Large', 'wordiva-blog-theme'),
            'size' => 36,
            'slug' => 'large'
        ),
        array(
            'name' => __('Huge', 'wordiva-blog-theme'),
            'size' => 48,
            'slug' => 'huge'
        ),
    ));
    
    // Add support for appearance tools
    add_theme_support('appearance-tools');
    
    // Add support for block templates
    add_theme_support('block-templates');
    
    // Add support for block template parts
    add_theme_support('block-template-parts');
}
add_action('after_setup_theme', 'wordiva_add_theme_support');

/**
 * Register widget areas
 */
function wordiva_theme_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Widget Area 1', 'wordiva-blog-theme'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in the first footer column.', 'wordiva-blog-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Widget Area 2', 'wordiva-blog-theme'),
        'id'            => 'footer-2',
        'description'   => __('Add widgets here to appear in the second footer column.', 'wordiva-blog-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Widget Area 3', 'wordiva-blog-theme'),
        'id'            => 'footer-3',
        'description'   => __('Add widgets here to appear in the third footer column.', 'wordiva-blog-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'wordiva_theme_widgets_init');

/**
 * Custom excerpt length
 */
function wordiva_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'wordiva_excerpt_length');

/**
 * Custom excerpt more text
 */
function wordiva_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'wordiva_excerpt_more');

/**
 * Add custom body classes
 */
function wordiva_body_classes($classes) {
    // Add class for the theme
    $classes[] = 'wordiva-blog-theme';
    
    // Add class if no sidebar
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }
    
    return $classes;
}
add_filter('body_class', 'wordiva_body_classes');