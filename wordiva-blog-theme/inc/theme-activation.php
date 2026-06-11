<?php
/**
 * Theme activation and deactivation hooks
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

function wordiva_theme_activation() {
    if (!get_option('posts_per_page')) {
        update_option('posts_per_page', 9);
    }

    if (empty(get_option('blogdescription'))) {
        update_option('blogdescription', wordiva_get_default_blog_description());
    }

    wordiva_setup_default_categories();
    wordiva_setup_default_featured_posts();
    wordiva_setup_sitemap_page();

    delete_option('wordiva_llms_rewrite_flushed');
    flush_rewrite_rules();
    wordiva_regenerate_card_images();
}
add_action('after_switch_theme', 'wordiva_theme_activation');

function wordiva_setup_default_categories() {
    $categories = array(
        'agentic-ai' => array(
            'name' => 'Agentic AI',
            'description' => wordiva_get_category_fallback_description('agentic-ai'),
        ),
        'ai-content-marketing' => array(
            'name' => 'AI Content Marketing',
            'description' => wordiva_get_category_fallback_description('ai-content-marketing'),
        ),
        'content-marketing' => array(
            'name' => 'Content Marketing',
            'description' => wordiva_get_category_fallback_description('content-marketing'),
        ),
        'wordiva-story' => array(
            'name' => 'Wordiva Story',
            'description' => wordiva_get_category_fallback_description('wordiva-story'),
        ),
    );

    foreach ($categories as $slug => $data) {
        if (!term_exists($slug, 'category')) {
            wp_insert_term($data['name'], 'category', array(
                'slug' => $slug,
                'description' => $data['description'],
            ));
        }
    }
}

function wordiva_setup_sitemap_page() {
    $existing = get_page_by_path('sitemap');
    if ($existing) {
        return;
    }
    wp_insert_post(array(
        'post_title' => 'Sitemap',
        'post_name' => 'sitemap',
        'post_status' => 'publish',
        'post_type' => 'page',
        'page_template' => 'page-sitemap.php',
    ));
}

function wordiva_setup_default_featured_posts() {
    $recent_posts = get_posts(array(
        'numberposts' => 3,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    if (!empty($recent_posts)) {
        update_post_meta($recent_posts[0]->ID, '_wordiva_featured_post', 1);
        update_post_meta($recent_posts[0]->ID, '_wordiva_featured_level', 'primary');
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

function wordiva_regenerate_card_images() {
    update_option('wordiva_needs_image_regeneration', true);
}

function wordiva_theme_deactivation() {
    flush_rewrite_rules();
}
add_action('switch_theme', 'wordiva_theme_deactivation');

function wordiva_seo_activation_notice() {
    if (!get_option('wordiva_seo_notice_dismissed')) {
        echo '<div class="notice notice-info is-dismissible"><p>';
        esc_html_e('Wordiva SEO: For WebP uploads, install ShortPixel or Smush. Verify CloudFront cache headers on wp-content/uploads after deploy.', 'wordiva-blog-theme');
        echo '</p></div>';
    }
}
add_action('admin_notices', 'wordiva_seo_activation_notice');
