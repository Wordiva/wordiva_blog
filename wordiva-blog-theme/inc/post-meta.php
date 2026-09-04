<?php
/**
 * Post meta boxes and custom fields
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enhanced post meta for slack.dev-inspired blog posts
 */
function wordiva_add_post_meta_boxes() {
    add_meta_box(
        'wordiva_post_options',
        __('Wordiva Post Options', 'wordiva-blog-theme'),
        'wordiva_post_options_callback',
        'post',
        'side',
        'default'
    );
    
    add_meta_box(
        'wordiva_card_options',
        __('Card Display Options', 'wordiva-blog-theme'),
        'wordiva_card_options_callback',
        'post',
        'normal',
        'default'
    );

    add_meta_box(
        'wordiva_seo_checklist',
        __('SEO Checklist', 'wordiva-blog-theme'),
        'wordiva_seo_checklist_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'wordiva_add_post_meta_boxes');

function wordiva_seo_checklist_callback($post) {
    $faq = get_post_meta($post->ID, '_wordiva_enable_faq_schema', true);
    $schema_type = get_post_meta($post->ID, '_wordiva_schema_type', true);
    $seo_title = get_post_meta($post->ID, '_wordiva_seo_title', true);
    $seo_description = get_post_meta($post->ID, '_wordiva_seo_description', true);
    $suffix_len = strlen(' ' . WORDIVA_TITLE_SEPARATOR . ' ' . WORDIVA_TITLE_SUFFIX);
    ?>
    <p>
        <label for="wordiva_seo_title"><strong><?php esc_html_e('SEO Title', 'wordiva-blog-theme'); ?></strong></label><br>
        <input type="text" id="wordiva_seo_title" name="wordiva_seo_title" class="widefat wordiva-char-count" value="<?php echo esc_attr($seo_title); ?>" data-max="<?php echo esc_attr(60 - $suffix_len); ?>" placeholder="<?php echo esc_attr(get_the_title($post)); ?>">
        <span class="description wordiva-char-count-label"></span>
        <span class="description"><?php printf(esc_html__('Rendered as “%1$s %2$s %3$s”. Leave blank to use the post title.', 'wordiva-blog-theme'), '…', WORDIVA_TITLE_SEPARATOR, WORDIVA_TITLE_SUFFIX); ?></span>
    </p>
    <p>
        <label for="wordiva_seo_description"><strong><?php esc_html_e('Meta Description', 'wordiva-blog-theme'); ?></strong></label><br>
        <textarea id="wordiva_seo_description" name="wordiva_seo_description" class="widefat wordiva-char-count" rows="3" data-max="155"><?php echo esc_textarea($seo_description); ?></textarea>
        <span class="description wordiva-char-count-label"></span>
        <span class="description"><?php esc_html_e('Leave blank to use the excerpt.', 'wordiva-blog-theme'); ?></span>
    </p>
    <ul style="margin-left:1em;list-style:disc;">
        <li><?php esc_html_e('Keyword in slug and H1', 'wordiva-blog-theme'); ?></li>
        <li><?php esc_html_e('1200×630 featured image', 'wordiva-blog-theme'); ?></li>
        <li><?php esc_html_e('3–5 internal links (blog + product)', 'wordiva-blog-theme'); ?></li>
        <li><?php esc_html_e('Question-format H2s with answer capsules', 'wordiva-blog-theme'); ?></li>
    </ul>
    <p>
        <label>
            <input type="checkbox" name="wordiva_enable_faq_schema" value="1" <?php checked($faq, '1'); ?>>
            <?php esc_html_e('Enable FAQPage schema', 'wordiva-blog-theme'); ?>
        </label>
    </p>
    <p>
        <label for="wordiva_schema_type"><?php esc_html_e('Schema type', 'wordiva-blog-theme'); ?></label><br>
        <select name="wordiva_schema_type" id="wordiva_schema_type">
            <option value="" <?php selected($schema_type, ''); ?>><?php esc_html_e('Default (BlogPosting)', 'wordiva-blog-theme'); ?></option>
            <option value="howto" <?php selected($schema_type, 'howto'); ?>><?php esc_html_e('HowTo', 'wordiva-blog-theme'); ?></option>
        </select>
    </p>
    <?php
}

/**
 * Enhanced post options meta box callback
 */
function wordiva_post_options_callback($post) {
    wp_nonce_field('wordiva_post_options_nonce', 'wordiva_post_options_nonce');
    
    $featured_post = get_post_meta($post->ID, '_wordiva_featured_post', true);
    $featured_level = get_post_meta($post->ID, '_wordiva_featured_level', true);
    $reading_time = get_post_meta($post->ID, '_wordiva_reading_time', true);
    $card_color = get_post_meta($post->ID, '_wordiva_card_color', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="wordiva_featured_post"><?php _e('Featured Post', 'wordiva-blog-theme'); ?></label>
            </th>
            <td>
                <input type="checkbox" id="wordiva_featured_post" name="wordiva_featured_post" value="1" <?php checked($featured_post, 1); ?> />
                <label for="wordiva_featured_post"><?php _e('Feature this post on homepage', 'wordiva-blog-theme'); ?></label>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="wordiva_featured_level"><?php _e('Featured Level', 'wordiva-blog-theme'); ?></label>
            </th>
            <td>
                <select id="wordiva_featured_level" name="wordiva_featured_level">
                    <option value="none" <?php selected($featured_level, 'none'); ?>><?php _e('Not Featured', 'wordiva-blog-theme'); ?></option>
                    <option value="secondary" <?php selected($featured_level, 'secondary'); ?>><?php _e('Secondary Featured', 'wordiva-blog-theme'); ?></option>
                    <option value="primary" <?php selected($featured_level, 'primary'); ?>><?php _e('Primary Featured (Hero)', 'wordiva-blog-theme'); ?></option>
                </select>
                <p class="description"><?php _e('Primary featured posts appear as large hero cards, secondary as medium cards.', 'wordiva-blog-theme'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="wordiva_reading_time"><?php _e('Reading Time', 'wordiva-blog-theme'); ?></label>
            </th>
            <td>
                <input type="number" id="wordiva_reading_time" name="wordiva_reading_time" value="<?php echo esc_attr($reading_time); ?>" min="1" max="60" />
                <label for="wordiva_reading_time"><?php _e('minutes', 'wordiva-blog-theme'); ?></label>
                <p class="description"><?php _e('Estimated reading time for this post.', 'wordiva-blog-theme'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="wordiva_card_color"><?php _e('Card Accent Color', 'wordiva-blog-theme'); ?></label>
            </th>
            <td>
                <select id="wordiva_card_color" name="wordiva_card_color">
                    <option value="" <?php selected($card_color, ''); ?>><?php _e('Default (Electric Blue)', 'wordiva-blog-theme'); ?></option>
                    <option value="royal-purple" <?php selected($card_color, 'royal-purple'); ?>><?php _e('Royal Purple', 'wordiva-blog-theme'); ?></option>
                    <option value="neon-pink" <?php selected($card_color, 'neon-pink'); ?>><?php _e('Neon Pink', 'wordiva-blog-theme'); ?></option>
                    <option value="sunrise-orange" <?php selected($card_color, 'sunrise-orange'); ?>><?php _e('Sunrise Orange', 'wordiva-blog-theme'); ?></option>
                    <option value="golden-yellow" <?php selected($card_color, 'golden-yellow'); ?>><?php _e('Golden Yellow', 'wordiva-blog-theme'); ?></option>
                </select>
                <p class="description"><?php _e('Override the default card accent color for this post.', 'wordiva-blog-theme'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Card display options meta box callback
 */
function wordiva_card_options_callback($post) {
    wp_nonce_field('wordiva_card_options_nonce', 'wordiva_card_options_nonce');
    
    $custom_excerpt = get_post_meta($post->ID, '_wordiva_custom_excerpt', true);
    $excerpt_length = get_post_meta($post->ID, '_wordiva_excerpt_length', true);
    $card_layout = get_post_meta($post->ID, '_wordiva_card_layout', true);
    $hide_category = get_post_meta($post->ID, '_wordiva_hide_category', true);
    $hide_date = get_post_meta($post->ID, '_wordiva_hide_date', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="wordiva_custom_excerpt"><?php _e('Custom Card Excerpt', 'wordiva-blog-theme'); ?></label>
            </th>
            <td>
                <textarea id="wordiva_custom_excerpt" name="wordiva_custom_excerpt" rows="3" cols="50" class="large-text"><?php echo esc_textarea($custom_excerpt); ?></textarea>
                <p class="description"><?php _e('Custom excerpt text for card display. Leave empty to use auto-generated excerpt.', 'wordiva-blog-theme'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="wordiva_excerpt_length"><?php _e('Excerpt Length', 'wordiva-blog-theme'); ?></label>
            </th>
            <td>
                <input type="number" id="wordiva_excerpt_length" name="wordiva_excerpt_length" value="<?php echo esc_attr($excerpt_length ?: 25); ?>" min="10" max="100" />
                <label for="wordiva_excerpt_length"><?php _e('words', 'wordiva-blog-theme'); ?></label>
                <p class="description"><?php _e('Number of words to show in card excerpt (default: 25).', 'wordiva-blog-theme'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="wordiva_card_layout"><?php _e('Card Layout Style', 'wordiva-blog-theme'); ?></label>
            </th>
            <td>
                <select id="wordiva_card_layout" name="wordiva_card_layout">
                    <option value="default" <?php selected($card_layout, 'default'); ?>><?php _e('Default Card', 'wordiva-blog-theme'); ?></option>
                    <option value="horizontal" <?php selected($card_layout, 'horizontal'); ?>><?php _e('Horizontal Card', 'wordiva-blog-theme'); ?></option>
                    <option value="minimal" <?php selected($card_layout, 'minimal'); ?>><?php _e('Minimal Card', 'wordiva-blog-theme'); ?></option>
                    <option value="image-focus" <?php selected($card_layout, 'image-focus'); ?>><?php _e('Image Focus Card', 'wordiva-blog-theme'); ?></option>
                </select>
                <p class="description"><?php _e('Choose the card layout style for this post.', 'wordiva-blog-theme'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Card Display Options', 'wordiva-blog-theme'); ?></th>
            <td>
                <fieldset>
                    <label for="wordiva_hide_category">
                        <input type="checkbox" id="wordiva_hide_category" name="wordiva_hide_category" value="1" <?php checked($hide_category, 1); ?> />
                        <?php _e('Hide category badge on card', 'wordiva-blog-theme'); ?>
                    </label><br>
                    <label for="wordiva_hide_date">
                        <input type="checkbox" id="wordiva_hide_date" name="wordiva_hide_date" value="1" <?php checked($hide_date, 1); ?> />
                        <?php _e('Hide publication date on card', 'wordiva-blog-theme'); ?>
                    </label>
                </fieldset>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save enhanced post meta
 */
function wordiva_save_post_meta($post_id) {
    // Verify nonces
    if (!isset($_POST['wordiva_post_options_nonce']) || !wp_verify_nonce($_POST['wordiva_post_options_nonce'], 'wordiva_post_options_nonce')) {
        return;
    }
    
    if (!isset($_POST['wordiva_card_options_nonce']) || !wp_verify_nonce($_POST['wordiva_card_options_nonce'], 'wordiva_card_options_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save post options
    $featured_post = isset($_POST['wordiva_featured_post']) ? 1 : 0;
    update_post_meta($post_id, '_wordiva_featured_post', $featured_post);
    
    if (isset($_POST['wordiva_featured_level'])) {
        update_post_meta($post_id, '_wordiva_featured_level', sanitize_text_field($_POST['wordiva_featured_level']));
    }
    
    if (isset($_POST['wordiva_reading_time'])) {
        update_post_meta($post_id, '_wordiva_reading_time', absint($_POST['wordiva_reading_time']));
    }
    
    if (isset($_POST['wordiva_card_color'])) {
        update_post_meta($post_id, '_wordiva_card_color', sanitize_text_field($_POST['wordiva_card_color']));
    }
    
    // Save card options
    if (isset($_POST['wordiva_custom_excerpt'])) {
        update_post_meta($post_id, '_wordiva_custom_excerpt', sanitize_textarea_field($_POST['wordiva_custom_excerpt']));
    }
    
    if (isset($_POST['wordiva_excerpt_length'])) {
        update_post_meta($post_id, '_wordiva_excerpt_length', absint($_POST['wordiva_excerpt_length']));
    }
    
    if (isset($_POST['wordiva_card_layout'])) {
        update_post_meta($post_id, '_wordiva_card_layout', sanitize_text_field($_POST['wordiva_card_layout']));
    }
    
    $hide_category = isset($_POST['wordiva_hide_category']) ? 1 : 0;
    update_post_meta($post_id, '_wordiva_hide_category', $hide_category);
    
    $hide_date = isset($_POST['wordiva_hide_date']) ? 1 : 0;
    update_post_meta($post_id, '_wordiva_hide_date', $hide_date);

    update_post_meta($post_id, '_wordiva_enable_faq_schema', isset($_POST['wordiva_enable_faq_schema']) ? '1' : '');
    if (isset($_POST['wordiva_schema_type'])) {
        update_post_meta($post_id, '_wordiva_schema_type', sanitize_text_field($_POST['wordiva_schema_type']));
    }
    if (isset($_POST['wordiva_seo_title'])) {
        update_post_meta($post_id, '_wordiva_seo_title', sanitize_text_field($_POST['wordiva_seo_title']));
    }
    if (isset($_POST['wordiva_seo_description'])) {
        update_post_meta($post_id, '_wordiva_seo_description', sanitize_textarea_field($_POST['wordiva_seo_description']));
    }
}
add_action('save_post', 'wordiva_save_post_meta');

/**
 * Expose SEO meta to REST / WP-CLI.
 */
function wordiva_register_seo_post_meta() {
    $auth = function () {
        return current_user_can('edit_posts');
    };
    foreach (array('_wordiva_seo_title', '_wordiva_seo_description', '_wordiva_enable_faq_schema') as $key) {
        register_post_meta('post', $key, array(
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => $key === '_wordiva_seo_description' ? 'sanitize_textarea_field' : 'sanitize_text_field',
            'auth_callback' => $auth,
        ));
    }
}
add_action('init', 'wordiva_register_seo_post_meta');