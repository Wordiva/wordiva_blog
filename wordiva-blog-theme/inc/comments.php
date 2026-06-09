<?php
/**
 * Comments functionality
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom comment pagination
 */
function wordiva_comment_nav() {
    if (get_comment_pages_count() > 1 && get_option('page_comments')) {
        ?>
        <nav class="comment-navigation" role="navigation">
            <h3 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'wordiva-blog-theme'); ?></h3>
            <div class="nav-links">
                <?php
                if ($prev_link = get_previous_comments_link(__('Older Comments', 'wordiva-blog-theme'))) {
                    printf('<div class="nav-previous">%s</div>', $prev_link);
                }
                if ($next_link = get_next_comments_link(__('Newer Comments', 'wordiva-blog-theme'))) {
                    printf('<div class="nav-next">%s</div>', $next_link);
                }
                ?>
            </div>
        </nav>
        <?php
    }
}

/**
 * Improve comment form
 */
function wordiva_comment_form_defaults($defaults) {
    $defaults['comment_notes_before'] = '<p class="comment-notes">' . 
        __('Your email address will not be published.', 'wordiva-blog-theme') . 
        '</p>';
    
    $defaults['comment_notes_after'] = '';
    
    $defaults['title_reply'] = __('Leave a Comment', 'wordiva-blog-theme');
    $defaults['title_reply_to'] = __('Leave a Reply to %s', 'wordiva-blog-theme');
    $defaults['cancel_reply_link'] = __('Cancel Reply', 'wordiva-blog-theme');
    $defaults['label_submit'] = __('Post Comment', 'wordiva-blog-theme');
    
    $defaults['submit_button'] = '<input name="%1$s" type="submit" id="%2$s" class="%3$s btn btn-primary" value="%4$s" />';
    
    return $defaults;
}
add_filter('comment_form_defaults', 'wordiva_comment_form_defaults');