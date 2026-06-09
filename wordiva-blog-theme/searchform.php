<?php
/**
 * Search form template
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

$unique_id = wp_unique_id('search-form-');
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="<?php echo esc_attr($unique_id); ?>">
        <span class="screen-reader-text"><?php echo _x('Search for:', 'label', 'wordiva-blog-theme'); ?></span>
        <input type="search" id="<?php echo esc_attr($unique_id); ?>" class="search-field" placeholder="<?php echo esc_attr_x('Search blog...', 'placeholder', 'wordiva-blog-theme'); ?>" value="<?php echo get_search_query(); ?>" name="s" required aria-describedby="<?php echo esc_attr($unique_id); ?>-description" />
    </label>
    <button type="submit" class="search-submit" aria-label="<?php echo esc_attr_x('Submit search', 'submit button', 'wordiva-blog-theme'); ?>">
        <span class="screen-reader-text"><?php echo _x('Search', 'submit button', 'wordiva-blog-theme'); ?></span>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
            <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
    <span id="<?php echo esc_attr($unique_id); ?>-description" class="screen-reader-text"><?php echo esc_html__('Search through blog posts and articles', 'wordiva-blog-theme'); ?></span>
</form>