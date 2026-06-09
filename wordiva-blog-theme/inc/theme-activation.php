<?php
/**
 * Theme activation and deactivation hooks
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme activation hook with enhanced setup
 */
function wordiva_theme_activation() {
    // Set default options
    if (!get_option('posts_per_page')) {
        update_option('posts_per_page', 9);
    }
    
    // Create default featured posts if none exist
    wordiva_setup_default_featured_posts();
    
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Regenerate image sizes for existing posts
    wordiva_regenerate_card_images();
}
add_action('after_switch_theme', 'wordiva_theme_activation');

/**
 * Setup default featured posts for demo purposes
 */
function wordiva_setup_default_featured_posts() {
    $recent_posts = get_posts(array(
        'numberposts' => 3,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    if (!empty($recent_posts)) {
        // Set the most recent post as primary featured
        update_post_meta($recent_posts[0]->ID, '_wordiva_featured_post', 1);
        update_post_meta($recent_posts[0]->ID, '_wordiva_featured_level', 'primary');
        
        // Set the next two as secondary featured
        if (isset($recent_posts[1])) {
            update_post_meta($recent_posts[1]->ID, '_wordiva_featured_post', 1);
            update_post_meta($recent_posts[1]->ID, '_wordiva_featured_level', 'secondary');
        }
        
        if (isset($recent_posts[2])) {
            update_post_meta($recent_posts[2]->ID, '_wordiva_featured_post', 1);
            update_post_meta($recent_posts[2]->ID, '_wordiva_featured_level', 'secondary');
        }
    }
}

/**
 * Regenerate card images for existing posts
 */
function wordiva_regenerate_card_images() {
    // This would typically trigger image regeneration
    // For now, we'll just set a flag that images need regeneration
    update_option('wordiva_needs_image_regeneration', true);
}

/**
 * Theme deactivation hook
 */
function wordiva_theme_deactivation() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('switch_theme', 'wordiva_theme_deactivation');