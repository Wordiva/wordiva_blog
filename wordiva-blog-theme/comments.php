<?php
/**
 * The template for displaying comments
 *
 * @package Wordiva_Blog_Theme
 * @since 1.0.0
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $comments_number = get_comments_number();
            if ('1' === $comments_number) {
                printf(
                    /* translators: %s: post title */
                    esc_html__('One thought on &ldquo;%s&rdquo;', 'wordiva-blog-theme'),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            } else {
                printf(
                    /* translators: 1: comment count number, 2: title. */
                    esc_html(_nx(
                        '%1$s thought on &ldquo;%2$s&rdquo;',
                        '%1$s thoughts on &ldquo;%2$s&rdquo;',
                        $comments_number,
                        'comments title',
                        'wordiva-blog-theme'
                    )),
                    number_format_i18n($comments_number), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            }
            ?>
        </h2>

        <?php wordiva_comment_nav(); ?>

        <ol class="comment-list">
            <?php
            wp_list_comments(
                array(
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'avatar_size' => 42,
                )
            );
            ?>
        </ol>

        <?php
        wordiva_comment_nav();

        // If comments are closed and there are comments, let's leave a little note, shall we?
        if (!comments_open()) :
            ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'wordiva-blog-theme'); ?></p>
            <?php
        endif;

    endif; // Check for have_comments().

    comment_form();
    ?>
</div><!-- #comments -->