<?php
/**
 * Product deep-link block for singles
 *
 * @package Wordiva_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$main = rtrim(wordiva_get_main_site_url(), '/');
$links = array(
    array(
        'url' => get_theme_mod('wordiva_product_compare_url', $main . '/compare'),
        'label' => get_theme_mod('wordiva_product_compare_label', __('Compare AI writing tools', 'wordiva-blog-theme')),
    ),
    array(
        'url' => get_theme_mod('wordiva_product_integrations_url', $main . '/integrations/wordpress'),
        'label' => get_theme_mod('wordiva_product_integrations_label', __('WordPress integration', 'wordiva-blog-theme')),
    ),
    array(
        'url' => get_theme_mod('wordiva_product_geo_url', $main . '/learn/generative-engine-optimization'),
        'label' => get_theme_mod('wordiva_product_geo_label', __('GEO guide', 'wordiva-blog-theme')),
    ),
);
?>
<section class="wordiva-product-links" aria-labelledby="wordiva-product-links-heading">
    <h3 id="wordiva-product-links-heading" class="wordiva-product-links-title"><?php esc_html_e('Explore on Wordiva', 'wordiva-blog-theme'); ?></h3>
    <ul class="wordiva-product-links-list">
        <?php foreach ($links as $link) : ?>
            <li><a href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['label']); ?></a></li>
        <?php endforeach; ?>
    </ul>
</section>
