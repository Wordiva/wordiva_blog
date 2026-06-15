<?php
/**
 * Sticky CTA partial
 *
 * @package Wordiva_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$label = get_theme_mod('wordiva_sticky_cta_label', __('Try Wordiva free', 'wordiva-blog-theme'));
$url = get_theme_mod('wordiva_sticky_cta_url', wordiva_get_blog_cta_url());
?>
<aside class="wordiva-sticky-cta" aria-label="<?php esc_attr_e('Call to action', 'wordiva-blog-theme'); ?>">
    <a href="<?php echo esc_url($url); ?>" class="wordiva-sticky-cta-link">
        <?php echo esc_html($label); ?>
    </a>
</aside>
