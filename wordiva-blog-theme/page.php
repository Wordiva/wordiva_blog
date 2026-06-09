<?php
/**
 * The template for displaying pages
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main page-main" role="main" itemscope itemtype="https://schema.org/WebPage">
    <div class="container single-article-container">
        
        <?php while (have_posts()) : the_post(); ?>
            
            <article id="page-<?php the_ID(); ?>" <?php post_class('page-content'); ?> itemscope itemtype="https://schema.org/WebPage">
                
                <!-- Page Header -->
                <header class="page-header">
                    
                    <!-- Page Title -->
                    <h1 class="page-title entry-title" itemprop="name"><?php the_title(); ?></h1>
                    
                    <!-- Page Meta Information -->
                    <?php if (get_the_modified_date() !== get_the_date()) : ?>
                        <div class="page-meta">
                            <div class="meta-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                                    <path d="M8 2V6M16 2V6M3 10H21M5 4H19C20.1046 4 21 4.89543 21 6V20C21 21.1046 20.1046 22 19 22H5C3.89543 22 3 21.1046 3 20V6C3 4.89543 3.89543 4 5 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="screen-reader-text"><?php esc_html_e('Last updated:', 'wordiva-blog-theme'); ?></span>
                                <time class="updated" datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>" itemprop="dateModified">
                                    <?php esc_html_e('Updated:', 'wordiva-blog-theme'); ?> <?php echo esc_html(get_the_modified_date('F j, Y')); ?>
                                </time>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Featured Image -->
                    <?php if (has_post_thumbnail()) : ?>
                        <figure class="page-featured-image" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                            <?php 
                            $featured_image_id = get_post_thumbnail_id();
                            $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                            $featured_image_alt = get_post_meta($featured_image_id, '_wp_attachment_image_alt', true);
                            $featured_image_caption = get_the_post_thumbnail_caption();
                            
                            the_post_thumbnail('large', array(
                                'class' => 'page-featured-img',
                                'alt' => $featured_image_alt ? $featured_image_alt : the_title_attribute(array('echo' => false)) . ' - ' . __('Featured image', 'wordiva-blog-theme'),
                                'itemprop' => 'url'
                            )); 
                            ?>
                            <meta itemprop="width" content="1200">
                            <meta itemprop="height" content="630">
                            <?php if ($featured_image_caption) : ?>
                                <figcaption class="image-caption" itemprop="caption">
                                    <?php echo wp_kses_post($featured_image_caption); ?>
                                </figcaption>
                            <?php endif; ?>
                        </figure>
                    <?php endif; ?>
                    
                </header>
                
                <!-- Page Content -->
                <div class="page-content entry-content" itemprop="text">
                    <?php
                    the_content();
                    
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'wordiva-blog-theme'),
                        'after'  => '</div>',
                        'link_before' => '<span class="page-number">',
                        'link_after'  => '</span>',
                    ));
                    ?>
                </div>
                
                <!-- Hidden structured data -->
                <div class="structured-data-hidden">
                    <meta itemprop="url" content="<?php echo esc_url(get_permalink()); ?>">
                    <meta itemprop="datePublished" content="<?php echo esc_attr(get_the_date('c')); ?>">
                    <?php if (get_the_modified_date() !== get_the_date()) : ?>
                        <meta itemprop="dateModified" content="<?php echo esc_attr(get_the_modified_date('c')); ?>">
                    <?php endif; ?>
                    <span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                        <span itemprop="name"><?php echo esc_html(get_bloginfo('name')); ?></span>
                        <span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                            <meta itemprop="url" content="<?php echo esc_url(get_template_directory_uri() . '/assets/images/icon.png'); ?>">
                        </span>
                    </span>
                </div>
                
            </article>
            
        <?php endwhile; ?>
        
    </div>
</main>

<?php get_footer(); ?>