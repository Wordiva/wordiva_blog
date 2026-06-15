<?php
/**
 * Author profile fields and publish validation
 *
 * @package Wordiva_Theme
 * @since 1.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

function wordiva_author_profile_fields($user) {
    ?>
    <h2><?php esc_html_e('Wordiva Author Profile', 'wordiva-blog-theme'); ?></h2>
    <table class="form-table">
        <tr>
            <th><label for="wordiva_job_title"><?php esc_html_e('Job Title', 'wordiva-blog-theme'); ?></label></th>
            <td><input type="text" name="wordiva_job_title" id="wordiva_job_title" value="<?php echo esc_attr(get_user_meta($user->ID, 'wordiva_job_title', true)); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="wordiva_linkedin_url"><?php esc_html_e('LinkedIn URL', 'wordiva-blog-theme'); ?></label></th>
            <td><input type="url" name="wordiva_linkedin_url" id="wordiva_linkedin_url" value="<?php echo esc_url(get_user_meta($user->ID, 'wordiva_linkedin_url', true)); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="wordiva_twitter_url"><?php esc_html_e('Twitter/X URL', 'wordiva-blog-theme'); ?></label></th>
            <td><input type="url" name="wordiva_twitter_url" id="wordiva_twitter_url" value="<?php echo esc_url(get_user_meta($user->ID, 'wordiva_twitter_url', true)); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="wordiva_allow_team_fallback"><?php esc_html_e('Team Fallback Name', 'wordiva-blog-theme'); ?></label></th>
            <td>
                <label>
                    <input type="checkbox" name="wordiva_allow_team_fallback" id="wordiva_allow_team_fallback" value="1" <?php checked(get_user_meta($user->ID, 'wordiva_allow_team_fallback', true), '1'); ?>>
                    <?php esc_html_e('Use "Wordiva Team" when display name is empty', 'wordiva-blog-theme'); ?>
                </label>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'wordiva_author_profile_fields');
add_action('edit_user_profile', 'wordiva_author_profile_fields');

function wordiva_save_author_profile_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return;
    }
    update_user_meta($user_id, 'wordiva_job_title', sanitize_text_field($_POST['wordiva_job_title'] ?? ''));
    update_user_meta($user_id, 'wordiva_linkedin_url', esc_url_raw($_POST['wordiva_linkedin_url'] ?? ''));
    update_user_meta($user_id, 'wordiva_twitter_url', esc_url_raw($_POST['wordiva_twitter_url'] ?? ''));
    update_user_meta($user_id, 'wordiva_allow_team_fallback', isset($_POST['wordiva_allow_team_fallback']) ? '1' : '');
}
add_action('personal_options_update', 'wordiva_save_author_profile_fields');
add_action('edit_user_profile_update', 'wordiva_save_author_profile_fields');

function wordiva_validate_author_on_publish($post_id, $post) {
    if (wp_is_post_revision($post_id) || $post->post_type !== 'post' || $post->post_status !== 'publish') {
        return;
    }
    $author_name = get_the_author_meta('display_name', $post->post_author);
    if (empty($author_name) && !get_user_meta($post->post_author, 'wordiva_allow_team_fallback', true)) {
        wp_update_post(array(
            'ID' => $post_id,
            'post_status' => 'draft',
        ));
        add_filter('redirect_post_location', function ($location) {
            return add_query_arg('wordiva_author_error', '1', $location);
        });
    }
}
add_action('save_post', 'wordiva_validate_author_on_publish', 20, 2);

function wordiva_author_publish_notice() {
    if (isset($_GET['wordiva_author_error'])) {
        echo '<div class="notice notice-error"><p>' . esc_html__('Post saved as draft: author display name is required for SEO.', 'wordiva-blog-theme') . '</p></div>';
    }
}
add_action('admin_notices', 'wordiva_author_publish_notice');
