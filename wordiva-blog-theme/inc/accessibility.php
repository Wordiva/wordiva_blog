<?php
/**
 * Accessibility enhancements
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Accessible Navigation Walker Class
 * Extends Walker_Nav_Menu to add accessibility features
 */
class Wordiva_Accessible_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    /**
     * Start the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Add accessibility classes
        if (in_array('current-menu-item', $classes)) {
            $classes[] = 'current';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names . ' role="menuitem">';
        
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        
        // Add aria-current for current page
        if (in_array('current-menu-item', $classes)) {
            $attributes .= ' aria-current="page"';
        }
        
        // Add tabindex for keyboard navigation
        $attributes .= ' tabindex="0"';
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * Add accessibility enhancements to widget areas
 */
function wordiva_widget_accessibility($params) {
    // Add ARIA labels to widget areas
    if (isset($params[0]['before_widget'])) {
        $params[0]['before_widget'] = str_replace(
            'class="widget',
            'class="widget" role="complementary" aria-label="' . esc_attr__('Widget', 'wordiva-blog-theme') . '"',
            $params[0]['before_widget']
        );
    }
    
    return $params;
}
add_filter('dynamic_sidebar_params', 'wordiva_widget_accessibility');

/**
 * Add skip links and accessibility improvements to body
 */
function wordiva_accessibility_enhancements() {
    // Add skip links (already in header.php, but ensure they're always present)
    if (!has_action('wp_body_open', 'wordiva_skip_links')) {
        add_action('wp_body_open', 'wordiva_skip_links');
    }
}
add_action('wp_head', 'wordiva_accessibility_enhancements');

/**
 * Output skip links
 */
function wordiva_skip_links() {
    ?>
    <div class="skip-links">
        <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to main content', 'wordiva-blog-theme'); ?></a>
        <a class="skip-link screen-reader-text" href="#site-navigation"><?php esc_html_e('Skip to navigation', 'wordiva-blog-theme'); ?></a>
        <a class="skip-link screen-reader-text" href="#site-footer"><?php esc_html_e('Skip to footer', 'wordiva-blog-theme'); ?></a>
    </div>
    <?php
}

/**
 * Improve image accessibility by ensuring alt text
 */
function wordiva_improve_image_accessibility($attr, $attachment, $size) {
    // If no alt text is set, use the image title or filename
    if (empty($attr['alt'])) {
        $alt_text = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
        
        if (empty($alt_text)) {
            $alt_text = $attachment->post_title;
        }
        
        if (empty($alt_text)) {
            $alt_text = pathinfo($attachment->guid, PATHINFO_FILENAME);
        }
        
        $attr['alt'] = esc_attr($alt_text);
    }
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'wordiva_improve_image_accessibility', 10, 3);

/**
 * Add color contrast validation for customizer
 */
function wordiva_validate_color_contrast($value, $setting) {
    // Basic color contrast validation
    // In a full implementation, you'd want more sophisticated contrast checking
    
    if (strpos($setting->id, 'color') !== false) {
        // Ensure the color is a valid hex color
        if (!preg_match('/^#[a-f0-9]{6}$/i', $value)) {
            return $setting->default;
        }
    }
    
    return $value;
}
add_filter('customize_validate_color', 'wordiva_validate_color_contrast', 10, 2);

/**
 * Handle broken images with JavaScript fallback
 * Adds error handling for images that fail to load
 */
function wordiva_image_error_handling() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle broken images
        var images = document.querySelectorAll('img');
        images.forEach(function(img) {
            img.addEventListener('error', function() {
                if (!this.classList.contains('fallback-processed')) {
                    this.classList.add('fallback-processed');
                    
                    // Determine appropriate fallback based on context
                    var fallbackUrl = '<?php echo esc_js(get_template_directory_uri()); ?>/assets/images/';
                    
                    if (this.closest('.featured-image, .featured-post')) {
                        fallbackUrl += 'fallback-featured.svg';
                    } else if (this.closest('.card-image, .blog-card, .search-card-image, .suggestion-image, .recent-post-image')) {
                        fallbackUrl += 'fallback-card.svg';
                    } else {
                        fallbackUrl += 'fallback-large.svg';
                    }
                    
                    this.src = fallbackUrl;
                    this.alt = '<?php echo esc_js(__('Image not available', 'wordiva-blog-theme')); ?>';
                    this.removeAttribute('srcset');
                    this.removeAttribute('sizes');
                    this.classList.add('broken-image-fallback');
                } else if (this.classList.contains('fallback-processed') && !this.classList.contains('no-svg-fallback')) {
                    // If the SVG fallback also fails, show text fallback
                    this.classList.add('no-svg-fallback');
                    this.alt = '<?php echo esc_js(__('Image not available', 'wordiva-blog-theme')); ?>';
                }
            });
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'wordiva_image_error_handling');

/**
 * Add microdata to post elements
 */
function wordiva_add_microdata_to_posts() {
    if (is_singular() && get_post_type() === 'post') {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add microdata attributes to post elements
            var article = document.querySelector('article.single-post');
            if (article) {
                article.setAttribute('itemscope', '');
                article.setAttribute('itemtype', 'https://schema.org/Article');
            }
            
            var title = document.querySelector('.single-post-title');
            if (title) {
                title.setAttribute('itemprop', 'headline');
            }
            
            var content = document.querySelector('.single-post-content');
            if (content) {
                content.setAttribute('itemprop', 'articleBody');
            }
            
            var publishedDate = document.querySelector('.entry-date.published');
            if (publishedDate) {
                publishedDate.setAttribute('itemprop', 'datePublished');
            }
            
            var modifiedDate = document.querySelector('.updated');
            if (modifiedDate) {
                modifiedDate.setAttribute('itemprop', 'dateModified');
            }
            
            var authorLink = document.querySelector('.author-link');
            if (authorLink) {
                authorLink.setAttribute('itemprop', 'author');
                authorLink.setAttribute('itemscope', '');
                authorLink.setAttribute('itemtype', 'https://schema.org/Person');
            }
            
            var featuredImage = document.querySelector('.single-post-featured-image img');
            if (featuredImage) {
                featuredImage.setAttribute('itemprop', 'image');
            }
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'wordiva_add_microdata_to_posts');