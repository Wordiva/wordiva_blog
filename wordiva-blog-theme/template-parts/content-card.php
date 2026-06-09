<?php
/**
 * Template part for displaying posts in card format
 * Slack.dev-inspired card design with Wordiva brand colors
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

// Get post data
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

// Fallback image if no featured image exists
if (!$featured_image_url) {
    $featured_image_url = get_template_directory_uri() . '/assets/images/fallback-featured.svg';
}

// Determine if this is a featured post and we're on the homepage
$is_featured = get_post_meta($post_id, '_wordiva_featured_post', true);
$is_homepage = is_home() || is_front_page();
$should_show_featured = $is_featured && $is_homepage;
$card_class = $should_show_featured ? 'blog-card featured-card' : 'blog-card';

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
    <div class="card-image" role="img" aria-label="<?php echo esc_attr($post_title); ?> featured image">
        <a href="<?php echo esc_url($post_permalink); ?>" 
           aria-hidden="true" 
           tabindex="-1"
           class="card-image-link">
            <?php 
            if (has_post_thumbnail()) {
                the_post_thumbnail('medium', array(
                    'class' => 'card-image-img',
                    'alt' => get_the_title(),
                    'loading' => 'lazy',
                    'itemprop' => 'image'
                )); 
            } else {
                // Use fallback image
                echo '<img src="' . esc_url($featured_image_url) . '" alt="' . esc_attr(get_the_title()) . '" class="card-image-img fallback-image" loading="lazy" itemprop="image">';
            }
            ?>
        </a>
        
        <!-- Featured Badge -->
        <?php if ($should_show_featured) : ?>
            <div class="featured-badge" aria-label="<?php esc_attr_e('Featured Post', 'wordiva-blog-theme'); ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="currentColor"/>
                </svg>
                <span class="screen-reader-text"><?php esc_html_e('Featured Post', 'wordiva-blog-theme'); ?></span>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Card Content -->
    <div class="card-content">
        
        <!-- Category Badge -->
        <?php if (!empty($categories)) : ?>
            <div class="card-category-wrapper">
                <?php 
                $primary_category = $categories[0];
                $category_class = $should_show_featured ? 'card-category featured-category' : 'card-category';
                ?>
                <a href="<?php echo esc_url(get_category_link($primary_category->term_id)); ?>" 
                   class="card-category"
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
                echo wp_kses_post(wp_trim_words(get_the_content(), 25, '...'));
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