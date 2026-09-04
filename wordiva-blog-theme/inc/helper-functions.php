<?php
/**
 * Helper functions and utilities
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get post card data with all necessary information
 */
function wordiva_get_card_data($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post = get_post($post_id);
    if (!$post) {
        return false;
    }
    
    // Get custom meta values
    $featured_level = get_post_meta($post_id, '_wordiva_featured_level', true) ?: 'none';
    $card_color = get_post_meta($post_id, '_wordiva_card_color', true) ?: '';
    $custom_excerpt = get_post_meta($post_id, '_wordiva_custom_excerpt', true);
    $excerpt_length = get_post_meta($post_id, '_wordiva_excerpt_length', true) ?: 25;
    $card_layout = get_post_meta($post_id, '_wordiva_card_layout', true) ?: 'default';
    $hide_category = get_post_meta($post_id, '_wordiva_hide_category', true);
    $hide_date = get_post_meta($post_id, '_wordiva_hide_date', true);
    $reading_time = get_post_meta($post_id, '_wordiva_reading_time', true);
    
    // Get post data
    $title = get_the_title($post_id);
    $permalink = get_permalink($post_id);
    $date = get_the_date('', $post_id);
    $author = get_the_author_meta('display_name', $post->post_author);
    $author_url = get_author_posts_url($post->post_author);
    
    // Get excerpt
    $excerpt = '';
    if (!empty($custom_excerpt)) {
        $excerpt = $custom_excerpt;
    } else {
        $excerpt = wordiva_get_post_excerpt($post_id, $excerpt_length);
    }
    
    // Get categories
    $categories = get_the_category($post_id);
    $primary_category = !empty($categories) ? $categories[0] : null;
    
    // Get featured image
    $image_data = array(
        'has_image' => has_post_thumbnail($post_id),
        'url' => '',
        'alt' => '',
        'sizes' => array()
    );
    
    if ($image_data['has_image']) {
        $image_data['url'] = get_the_post_thumbnail_url($post_id, 'wordiva-blog-card');
        $image_data['alt'] = get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true) ?: $title;
        
        // Get different sizes for responsive images
        $image_data['sizes'] = array(
            'card' => get_the_post_thumbnail_url($post_id, 'wordiva-blog-card'),
            'medium' => get_the_post_thumbnail_url($post_id, 'wordiva-card-medium'),
            'large' => get_the_post_thumbnail_url($post_id, 'wordiva-card-large'),
            'hero' => get_the_post_thumbnail_url($post_id, 'wordiva-hero-card')
        );
    } else {
        $image_data['url'] = wordiva_get_fallback_featured_image('wordiva-blog-card');
        $image_data['alt'] = sprintf(__('Placeholder image for: %s', 'wordiva-blog-theme'), $title);
    }
    
    return array(
        'id' => $post_id,
        'title' => $title,
        'permalink' => $permalink,
        'excerpt' => $excerpt,
        'date' => $date,
        'author' => $author,
        'author_url' => $author_url,
        'reading_time' => $reading_time,
        'primary_category' => $primary_category,
        'categories' => $categories,
        'image' => $image_data,
        'featured_level' => $featured_level,
        'card_color' => $card_color,
        'card_layout' => $card_layout,
        'hide_category' => $hide_category,
        'hide_date' => $hide_date,
        'post_type' => $post->post_type,
        'post_status' => $post->post_status
    );
}

/**
 * Get featured posts for homepage display
 */
function wordiva_get_featured_posts($limit = 3) {
    $featured_posts = new WP_Query(array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => '_wordiva_featured_post',
                'value' => '1',
                'compare' => '='
            )
        ),
        'orderby' => array(
            'meta_value' => 'ASC', // Primary featured first
            'date' => 'DESC'
        ),
        'meta_key' => '_wordiva_featured_level',
        'no_found_rows' => true
    ));
    
    return $featured_posts;
}

/**
 * Related posts: shared tags first, then same category, then most recent.
 */
function wordiva_get_related_posts_query($post_id, $limit = 3) {
    $exclude = array($post_id);
    $ids = array();
    $base = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'fields' => 'ids',
        'orderby' => 'date',
        'order' => 'DESC',
        'no_found_rows' => true,
    );

    $tags = wp_get_post_tags($post_id, array('fields' => 'ids'));
    if (!empty($tags)) {
        $ids = get_posts(array_merge($base, array(
            'posts_per_page' => $limit,
            'post__not_in' => $exclude,
            'tag__in' => $tags,
        )));
    }

    if (count($ids) < $limit) {
        $categories = wp_get_post_categories($post_id);
        if (!empty($categories)) {
            $ids = array_merge($ids, get_posts(array_merge($base, array(
                'posts_per_page' => $limit - count($ids),
                'post__not_in' => array_merge($exclude, $ids),
                'category__in' => $categories,
            ))));
        }
    }

    if (count($ids) < $limit) {
        $ids = array_merge($ids, get_posts(array_merge($base, array(
            'posts_per_page' => $limit - count($ids),
            'post__not_in' => array_merge($exclude, $ids),
        ))));
    }

    if (empty($ids)) {
        return new WP_Query(array('post__in' => array(0)));
    }

    return new WP_Query(array(
        'post_type' => 'post',
        'post__in' => $ids,
        'orderby' => 'post__in',
        'posts_per_page' => count($ids),
    ));
}

/**
 * Get card CSS classes based on post meta
 */
function wordiva_get_card_classes($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $classes = array('post-card');
    
    // Add featured level class
    $featured_level = get_post_meta($post_id, '_wordiva_featured_level', true);
    if ($featured_level && $featured_level !== 'none') {
        $classes[] = 'post-card--' . $featured_level;
    }
    
    // Add card color class
    $card_color = get_post_meta($post_id, '_wordiva_card_color', true);
    if ($card_color) {
        $classes[] = 'post-card--' . $card_color;
    }
    
    // Add layout class
    $card_layout = get_post_meta($post_id, '_wordiva_card_layout', true);
    if ($card_layout && $card_layout !== 'default') {
        $classes[] = 'post-card--' . $card_layout;
    }
    
    // Add image status class
    if (has_post_thumbnail($post_id)) {
        $classes[] = 'post-card--has-image';
    } else {
        $classes[] = 'post-card--no-image';
    }
    
    // Add category class
    $categories = get_the_category($post_id);
    if (!empty($categories)) {
        $classes[] = 'post-card--category-' . $categories[0]->slug;
    }
    
    return implode(' ', $classes);
}

/**
 * Generate card color CSS custom properties
 */
function wordiva_get_card_color_vars($card_color = '') {
    $color_map = array(
        'royal-purple' => '#d946ef',
        'neon-pink' => '#FF4FA3',
        'sunrise-orange' => '#FF9F1C',
        'golden-yellow' => '#FFD166',
        '' => '#6366f1' // Default brand indigo
    );
    
    $color = isset($color_map[$card_color]) ? $color_map[$card_color] : $color_map[''];
    
    return sprintf('data-card-accent-color="%s"', esc_attr($color));
}

/**
 * Get reading time for a post
 */
function wordiva_calculate_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    // Check if reading time is manually set
    $manual_time = get_post_meta($post_id, '_wordiva_reading_time', true);
    if ($manual_time) {
        return absint($manual_time);
    }
    
    // Calculate based on content
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(wp_strip_all_tags($content));
    
    // Average reading speed is 200-250 words per minute
    $reading_time = ceil($word_count / 225);
    
    return max(1, $reading_time); // Minimum 1 minute
}

/**
 * Get card excerpt with proper fallbacks
 */
function wordiva_get_card_excerpt($post_id = null, $length = 25) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    // Check for custom excerpt first
    $custom_excerpt = get_post_meta($post_id, '_wordiva_custom_excerpt', true);
    if (!empty($custom_excerpt)) {
        return wp_trim_words($custom_excerpt, $length, '...');
    }
    
    // Use the enhanced excerpt function
    return wordiva_get_post_excerpt($post_id, $length);
}

/**
 * Check if post should be displayed as featured
 */
function wordiva_is_featured_post($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $featured = get_post_meta($post_id, '_wordiva_featured_post', true);
    return !empty($featured);
}

/**
 * Get featured level for a post
 */
function wordiva_get_featured_level($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $level = get_post_meta($post_id, '_wordiva_featured_level', true);
    return $level ?: 'none';
}

/**
 * Generate responsive image markup for cards
 */
function wordiva_get_responsive_card_image($post_id = null, $featured_level = 'none') {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $title = get_the_title($post_id);
    
    if (has_post_thumbnail($post_id)) {
        $sizes = array();
        $srcset = array();
        
        // Define sizes based on featured level
        switch ($featured_level) {
            case 'primary':
                $main_size = 'wordiva-hero-card';
                $sizes = array(
                    '(max-width: 768px)' => 'wordiva-card-medium',
                    '(max-width: 1024px)' => 'wordiva-card-large',
                    'default' => 'wordiva-hero-card'
                );
                break;
            case 'secondary':
                $main_size = 'wordiva-card-large';
                $sizes = array(
                    '(max-width: 768px)' => 'wordiva-card-small',
                    'default' => 'wordiva-card-large'
                );
                break;
            default:
                $main_size = 'wordiva-blog-card';
                $sizes = array(
                    '(max-width: 768px)' => 'wordiva-card-small',
                    'default' => 'wordiva-blog-card'
                );
        }
        
        // Build srcset
        foreach ($sizes as $condition => $size) {
            $url = get_the_post_thumbnail_url($post_id, $size);
            if ($url) {
                if ($condition === 'default') {
                    $srcset[] = $url;
                } else {
                    $srcset[] = $url . ' ' . $condition;
                }
            }
        }
        
        $main_url = get_the_post_thumbnail_url($post_id, $main_size);
        $alt_text = get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true) ?: $title;
        
        return sprintf(
            '<img src="%s" srcset="%s" sizes="%s" alt="%s" loading="lazy" decoding="async" class="card-image">',
            esc_url($main_url),
            esc_attr(implode(', ', $srcset)),
            esc_attr('(max-width: 768px) 280px, (max-width: 1024px) 350px, 400px'),
            esc_attr($alt_text)
        );
    } else {
        // Return fallback image
        $fallback_url = wordiva_get_fallback_featured_image($main_size ?? 'wordiva-blog-card');
        return sprintf(
            '<img src="%s" alt="%s" loading="lazy" decoding="async" class="card-image card-image--fallback">',
            esc_url($fallback_url),
            esc_attr(sprintf(__('Placeholder image for: %s', 'wordiva-blog-theme'), $title))
        );
    }
}

/**
 * Get fallback featured image URL
 * Returns a default placeholder image when no featured image is available
 */
function wordiva_get_fallback_featured_image($size = 'large') {
    $fallback_images = array(
        'wordiva-featured' => get_template_directory_uri() . '/assets/images/fallback-featured.svg',
        'wordiva-blog-card' => get_template_directory_uri() . '/assets/images/fallback-card.svg',
        'large' => get_template_directory_uri() . '/assets/images/fallback-large.svg',
        'medium' => get_template_directory_uri() . '/assets/images/fallback-card.svg',
        'thumbnail' => get_template_directory_uri() . '/assets/images/fallback-card.svg'
    );
    
    // Return specific size if available, otherwise return large as default
    return isset($fallback_images[$size]) ? $fallback_images[$size] : $fallback_images['large'];
}

/**
 * Get post thumbnail with fallback
 * Returns post thumbnail or fallback image with proper alt text
 */
function wordiva_get_post_thumbnail($post_id = null, $size = 'large', $attr = array()) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail($post_id, $size, $attr);
    }
    
    // Generate fallback image
    $fallback_url = wordiva_get_fallback_featured_image($size);
    $post_title = get_the_title($post_id);
    
    // Set default alt text if not provided
    if (empty($attr['alt'])) {
        $attr['alt'] = sprintf(__('Placeholder image for: %s', 'wordiva-blog-theme'), $post_title);
    }
    
    // Set default class if not provided
    if (empty($attr['class'])) {
        $attr['class'] = 'attachment-' . $size . ' size-' . $size . ' wp-post-image fallback-image';
    } else {
        $attr['class'] .= ' fallback-image';
    }
    
    // Build image attributes
    $attributes = '';
    foreach ($attr as $key => $value) {
        $attributes .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
    }
    
    return sprintf(
        '<img src="%s" %s loading="lazy" />',
        esc_url($fallback_url),
        $attributes
    );
}

/**
 * Get post excerpt with auto-generation fallback
 * Returns post excerpt or auto-generates from content
 */
function wordiva_get_post_excerpt($post_id = null, $length = 25) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post = get_post($post_id);
    if (!$post) {
        return __('Content not available.', 'wordiva-blog-theme');
    }
    
    // Check if post has manual excerpt
    if (!empty($post->post_excerpt)) {
        return wp_trim_words($post->post_excerpt, $length, '...');
    }
    
    // Auto-generate excerpt from content
    if (!empty($post->post_content)) {
        $content = wp_strip_all_tags($post->post_content);
        $content = preg_replace('/\s+/', ' ', $content); // Normalize whitespace
        return wp_trim_words($content, $length, '...');
    }
    
    // Final fallback
    return __('No excerpt available for this post.', 'wordiva-blog-theme');
}

/**
 * Get author information with fallback
 * Returns author info or default Wordiva Team information
 */
function wordiva_get_author_info($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $author_id = get_post_field('post_author', $post_id);
    
    if ($author_id) {
        $author_name = get_the_author_meta('display_name', $author_id);
        $author_url = get_author_posts_url($author_id);
        
        // Check if author has a valid name
        if (!empty($author_name) && $author_name !== 'admin') {
            return array(
                'name' => $author_name,
                'url' => $author_url,
                'is_fallback' => false
            );
        }
    }
    
    // Return fallback author information
    return array(
        'name' => __('Wordiva Team', 'wordiva-blog-theme'),
        'url' => home_url('/about/'),
        'is_fallback' => true
    );
}

/**
 * Get post title with fallback
 * Returns post title or generates one from content/date
 */
function wordiva_get_post_title($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $title = get_the_title($post_id);
    
    if (!empty($title) && $title !== __('Auto Draft', 'wordiva-blog-theme')) {
        return $title;
    }
    
    // Generate fallback title
    $post_date = get_the_date('F j, Y', $post_id);
    return sprintf(__('Untitled Post - %s', 'wordiva-blog-theme'), $post_date);
}

/**
 * Get post categories with fallback
 * Returns categories or default "Uncategorized"
 */
function wordiva_get_post_categories($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = get_the_category($post_id);
    
    if (!empty($categories)) {
        return $categories;
    }
    
    // Return default category
    $default_category = get_category_by_slug('uncategorized');
    if ($default_category) {
        return array($default_category);
    }
    
    // Create a virtual category object as final fallback
    $fallback_category = new stdClass();
    $fallback_category->name = __('General', 'wordiva-blog-theme');
    $fallback_category->slug = 'general';
    $fallback_category->term_id = 0;
    
    return array($fallback_category);
}