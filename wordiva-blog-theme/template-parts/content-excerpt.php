<?php
/**
 * Template part for displaying blog post excerpts in the blog list
 * Updated for slack.dev-inspired card consistency
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

// Get post data for consistency with content-card.php
$post_id = get_the_ID();
$post_title = get_the_title();
$post_excerpt = get_the_excerpt();
$post_permalink = get_the_permalink();
$post_date = get_the_date();
$post_author = get_the_author();
$post_author_url = get_author_posts_url(get_the_author_meta('ID'));
$categories = get_the_category();
$featured_image_url = get_the_post_thumbnail_url($post_id, 'medium');
$reading_time = get_post_meta($post_id, '_wordiva_reading_time', true);

// Determine if this is a featured post
$is_featured = get_post_meta($post_id, '_wordiva_featured_post', true) || is_sticky();
$card_class = $is_featured ? 'blog-card featured-card' : 'blog-card';

// Generate structured data
$structured_data = array(
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post_title,
    'description' => $post_excerpt,
    'url' => $post_permalink,
    'datePublished' => get_the_date('c'),
    'dateModified' => get_the_modified_date('c'),
    'author' => array(
        '@type' => 'Person',
        'name' => $post_author,
        'url' => $post_author_url
    ),
    'publisher' => array(
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url()
    )
);

if ($featured_image_url) {
    $structured_data['image'] = $featured_image_url;
}
?>

<article id="post-<?php echo esc_attr($post_id); ?>" 
         <?php post_class($card_class); ?>
         itemscope 
         itemtype="https://schema.org/BlogPosting"
         role="article"
         aria-labelledby="card-title-<?php echo esc_attr($post_id); ?>"
         aria-describedby="card-excerpt-<?php echo esc_attr($post_id); ?>">
    
    <!-- Structured Data -->
    <script type="application/ld+json">
        <?php echo wp_json_encode($structured_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
    </script>
    
    <!-- Card Image -->
    <?php if ($featured_image_url) : ?>
        <div class="card-image" role="img" aria-label="<?php echo esc_attr($post_title); ?> featured image">
            <a href="<?php echo esc_url($post_permalink); ?>" 
               aria-hidden="true" 
               tabindex="-1"
               class="card-image-link">
                <?php 
                the_post_thumbnail('medium', array(
                    'class' => 'card-image-img',
                    'alt' => get_the_title(),
                    'loading' => 'lazy',
                    'itemprop' => 'image'
                )); 
                ?>
            </a>
            
            <!-- Featured/Sticky Badge -->
            <?php if ($is_featured || is_sticky()) : ?>
                <div class="featured-badge" aria-label="<?php echo is_sticky() ? esc_attr__('Pinned Post', 'wordiva-blog-theme') : esc_attr__('Featured Post', 'wordiva-blog-theme'); ?>">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <?php if (is_sticky()) : ?>
                            <path d="M16 4V2C16 1.45 15.55 1 15 1H9C8.45 1 8 1.45 8 2V4H7C6.45 4 6 4.45 6 5S6.45 6 7 6H8V7L10 9V22H14V9L16 7V6H17C17.55 6 18 5.55 18 5S17.55 4 17 4H16Z" fill="currentColor"/>
                        <?php else : ?>
                            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="currentColor"/>
                        <?php endif; ?>
                    </svg>
                    <span class="screen-reader-text"><?php echo is_sticky() ? esc_html__('Pinned Post', 'wordiva-blog-theme') : esc_html__('Featured Post', 'wordiva-blog-theme'); ?></span>
                </div>
            <?php endif; ?>
        </div>
    <?php else : ?>
        <!-- Placeholder for posts without featured images -->
        <div class="card-image card-image-placeholder" role="img" aria-label="<?php esc_attr_e('No image available', 'wordiva-blog-theme'); ?>">
            <div class="placeholder-icon" aria-hidden="true">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 16L8.586 11.414C9.367 10.633 10.633 10.633 11.414 11.414L16 16M14 14L15.586 12.414C16.367 11.633 17.633 11.633 18.414 12.414L20 14M14 8H14.01M6 20H18C19.105 20 20 19.105 20 18V6C20 4.895 19.105 4 18 4H6C4.895 4 4 4.895 4 6V18C4 19.105 4.895 20 6 20Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Card Content -->
    <div class="card-content">
        
        <!-- Category Badge -->
        <?php if (!empty($categories)) : ?>
            <div class="card-category-wrapper">
                <?php 
                $primary_category = $categories[0];
                $category_color = get_term_meta($primary_category->term_id, '_wordiva_category_color', true);
                $category_class = $category_color ? 'card-category category-custom-color' : 'card-category';
                ?>
                <a href="<?php echo esc_url(get_category_link($primary_category->term_id)); ?>" 
                   class="<?php echo esc_attr($category_class); ?>" 
                   data-category-color="<?php echo esc_attr($category_color); ?>"
                   aria-label="<?php printf(esc_attr__('View all posts in %s', 'wordiva-blog-theme'), esc_attr($primary_category->name)); ?>"
                   itemprop="articleSection">
                    <?php echo esc_html($primary_category->name); ?>
                </a>
            </div>
        <?php endif; ?>
        
        <!-- Card Title -->
        <h2 class="card-title" id="card-title-<?php echo esc_attr($post_id); ?>" itemprop="headline">
            <a href="<?php echo esc_url($post_permalink); ?>" 
               class="card-title-link"
               aria-describedby="card-meta-<?php echo esc_attr($post_id); ?>"
               itemprop="url">
                <?php echo esc_html($post_title); ?>
            </a>
        </h2>
        
        <!-- Card Excerpt -->
        <div class="card-excerpt" 
             id="card-excerpt-<?php echo esc_attr($post_id); ?>" 
             itemprop="description">
            <?php 
            if (!empty($post_excerpt)) {
                echo wp_kses_post($post_excerpt);
            } else {
                echo wp_kses_post(wp_trim_words(get_the_content(), 22, '...'));
            }
            ?>
        </div>
        
        <!-- Card Meta -->
        <div class="card-meta" id="card-meta-<?php echo esc_attr($post_id); ?>" role="group" aria-label="<?php esc_attr_e('Post metadata', 'wordiva-blog-theme'); ?>">
            
            <!-- Date -->
            <div class="card-date" itemprop="datePublished" content="<?php echo esc_attr(get_the_date('c')); ?>">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M8 2V6M16 2V6M3 10H21M5 4H19C20.1046 4 21 4.89543 21 6V20C21 21.1046 20.1046 22 19 22H5C3.89543 22 3 21.1046 3 20V6C3 4.89543 3.89543 4 5 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" 
                      aria-label="<?php printf(esc_attr__('Published on %s', 'wordiva-blog-theme'), esc_attr($post_date)); ?>">
                    <?php echo esc_html($post_date); ?>
                </time>
            </div>
            
            <!-- Author (if enabled in theme options) -->
            <?php if (get_theme_mod('wordiva_show_author_info', true)) : ?>
                <div class="card-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <a href="<?php echo esc_url($post_author_url); ?>" 
                       class="card-author-link"
                       aria-label="<?php printf(esc_attr__('View all posts by %s', 'wordiva-blog-theme'), esc_attr($post_author)); ?>"
                       itemprop="url">
                        <span itemprop="name"><?php echo esc_html($post_author); ?></span>
                    </a>
                </div>
            <?php endif; ?>
            
            <!-- Reading Time (if available) -->
            <?php if ($reading_time && get_theme_mod('wordiva_show_reading_time', true)) : ?>
                <div class="card-reading-time">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 6V12L16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span aria-label="<?php printf(esc_attr__('Estimated reading time: %s minutes', 'wordiva-blog-theme'), esc_attr($reading_time)); ?>">
                        <?php printf(esc_html__('%s min read', 'wordiva-blog-theme'), esc_html($reading_time)); ?>
                    </span>
                </div>
            <?php elseif (get_theme_mod('wordiva_show_reading_time', true)) : ?>
                <div class="card-reading-time">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 6V12L16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span aria-label="<?php printf(esc_attr__('Estimated reading time: %s minutes', 'wordiva-blog-theme'), esc_attr(ceil(str_word_count(get_the_content()) / 200))); ?>">
                        <?php printf(esc_html__('%s min read', 'wordiva-blog-theme'), esc_html(ceil(str_word_count(get_the_content()) / 200))); ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <!-- Read More Link -->
            <a href="<?php echo esc_url($post_permalink); ?>" 
               class="card-read-more"
               aria-label="<?php printf(esc_attr__('Read more about %s', 'wordiva-blog-theme'), esc_attr($post_title)); ?>">
                <span><?php esc_html_e('Read More', 'wordiva-blog-theme'); ?></span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            
        </div>
        
    </div>
    
    <!-- Hidden meta for screen readers -->
    <div class="screen-reader-text">
        <p>
            <?php 
            printf(
                esc_html__('Article titled "%1$s" published on %2$s by %3$s', 'wordiva-blog-theme'),
                esc_html($post_title),
                esc_html($post_date),
                esc_html($post_author)
            );
            ?>
        </p>
        <?php if (!empty($categories)) : ?>
            <p>
                <?php 
                printf(
                    esc_html__('Filed under: %s', 'wordiva-blog-theme'),
                    esc_html(implode(', ', wp_list_pluck($categories, 'name')))
                );
                ?>
            </p>
        <?php endif; ?>
    </div>
    
</article>