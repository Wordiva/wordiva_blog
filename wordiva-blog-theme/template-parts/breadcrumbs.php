<?php
/**
 * Breadcrumb navigation partial
 *
 * @package Wordiva_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$items = wordiva_get_breadcrumb_items();
if (empty($items)) {
    return;
}
?>
<nav class="header-breadcrumb-nav wordiva-breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'wordiva-blog-theme'); ?>" itemscope itemtype="https://schema.org/BreadcrumbList">
    <ol class="header-breadcrumb" role="list">
        <?php foreach ($items as $item) :
            $is_last = ($item === end($items));
            ?>
            <li class="breadcrumb-item<?php echo $is_last ? ' active' : ''; ?>" role="listitem" <?php echo $is_last ? 'aria-current="page"' : ''; ?> itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <?php if (!$is_last && !empty($item['item'])) : ?>
                    <a href="<?php echo esc_url($item['item']); ?>" itemprop="item">
                        <span itemprop="name"><?php echo esc_html($item['name']); ?></span>
                    </a>
                <?php else : ?>
                    <span itemprop="name"><?php echo esc_html($item['name']); ?></span>
                <?php endif; ?>
                <meta itemprop="position" content="<?php echo esc_attr($item['position']); ?>">
            </li>
        <?php endforeach; ?>
    </ol>
</nav>
