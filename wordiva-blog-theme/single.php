<?php
/**
 * The template for displaying single blog posts
 * Slack.dev-inspired design with Wordiva brand colors
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main single-post-main" role="main">
    <div class="container single-article-container">

        <?php while (have_posts()) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('single-post slack-single-post'); ?> itemscope itemtype="https://schema.org/Article">

                <!-- Post Header -->
                <header class="single-post-header slack-post-header" itemprop="headline">

                    <!-- Category Link -->
                    <?php if (has_category()) : ?>
                        <?php
                        $categories = get_the_category();
                        if (!empty($categories)) {
                            $primary_category = $categories[0];
                            ?>
                            <a href="<?php echo esc_url(get_category_link($primary_category->term_id)); ?>"
                                class="post-category-link slack-category-link"
                                aria-label="<?php printf(esc_attr__('View all posts in %s', 'wordiva-blog-theme'), esc_attr($primary_category->name)); ?>"
                                itemprop="articleSection">
                                <?php echo esc_html(strtoupper($primary_category->name)); ?>
                            </a>
                            <?php
                        }
                        ?>
                    <?php endif; ?>

                    <!-- Post Title -->
                    <h1 class="single-post-title slack-post-title entry-title" itemprop="headline"><?php the_title(); ?></h1>

                    <!-- Post Meta Information -->
                    <div class="single-post-meta slack-post-meta entry-meta">

                        <!-- Author Information -->
                        <div class="author-info slack-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                            <div class="author-avatar">
                                <?php echo get_avatar(get_the_author_meta('ID'), 40, '', esc_attr(get_the_author() . ' - Author'), array('class' => 'avatar-img', 'itemprop' => 'image')); ?>
                            </div>
                            <div class="author-details">
                                <span class="author-name">
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author-link" itemprop="url">
                                        <span itemprop="name"><?php echo esc_html(wordiva_get_author_display_name(get_the_author_meta('ID'))); ?></span>
                                    </a>
                                </span>
                            </div>
                        </div>

                        <!-- Publication Metadata -->
                        <div class="publication-meta slack-meta">
                            <div class="meta-item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                                    <path d="M8 2V6M16 2V6M3 10H21M5 4H19C20.1046 4 21 4.89543 21 6V20C21 21.1046 20.1046 22 19 22H5C3.89543 22 3 21.1046 3 20V6C3 4.89543 3.89543 4 5 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                    <?php echo esc_html(get_the_date('M j, Y')); ?>
                                </time>
                            </div>

                            <div class="meta-item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                    <path d="M12 6V12L16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="reading-time">
                                    <span itemprop="timeRequired" content="PT<?php echo esc_attr(ceil(str_word_count(get_the_content()) / 200)); ?>M">
                                        <?php echo esc_html(ceil(str_word_count(get_the_content()) / 200)); ?> <?php esc_html_e('min read', 'wordiva-blog-theme'); ?>
                                    </span>
                                </span>
                            </div>
                        </div>

                    </div>

                    <!-- Featured Image -->
                    <?php if (has_post_thumbnail()) : ?>
                        <figure class="single-post-featured-image slack-featured-image" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                            <?php
                            $featured_image_id = get_post_thumbnail_id();
                            $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                            $featured_image_alt = get_post_meta($featured_image_id, '_wp_attachment_image_alt', true);
                            $featured_image_caption = get_the_post_thumbnail_caption();

                            the_post_thumbnail('large', array(
                                'class' => 'featured-image',
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

                <!-- Post Content -->
                <div class="single-post-content slack-post-content entry-content wordiva-speakable" itemprop="articleBody">
                    <?php
                    the_content();

                    wp_link_pages(array(
                        'before' => '<div class="page-links slack-page-links">' . esc_html__('Pages:', 'wordiva-blog-theme'),
                        'after'  => '</div>',
                        'link_before' => '<span class="page-number">',
                        'link_after'  => '</span>',
                    ));
                    ?>
                </div>

                <?php if (has_category()) :
                    $categories = get_the_category();
                    $primary_category = $categories[0];
                    ?>
                    <section class="wordiva-topic-block" aria-labelledby="wordiva-topic-heading">
                        <h3 id="wordiva-topic-heading"><?php esc_html_e('Explore this topic', 'wordiva-blog-theme'); ?></h3>
                        <p>
                            <?php esc_html_e('Read more articles in', 'wordiva-blog-theme'); ?>
                            <a href="<?php echo esc_url(get_category_link($primary_category->term_id)); ?>">
                                <?php echo esc_html($primary_category->name); ?>
                            </a>
                        </p>
                    </section>
                <?php endif; ?>

                <?php get_template_part('template-parts/product-links'); ?>

                <section class="wordiva-product-cta" aria-labelledby="wordiva-product-cta-heading">
                    <h3 id="wordiva-product-cta-heading"><?php esc_html_e('Try Wordiva free', 'wordiva-blog-theme'); ?></h3>
                    <p><?php esc_html_e('Automate your WordPress blog with agentic AI content marketing.', 'wordiva-blog-theme'); ?></p>
                    <a class="wordiva-cta-button" href="<?php echo esc_url(wordiva_get_blog_cta_url()); ?>">
                        <?php esc_html_e('Get started', 'wordiva-blog-theme'); ?>
                    </a>
                </section>

                <!-- Hidden structured data -->
                <div class="structured-data-hidden" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                    <span itemprop="name"><?php echo esc_html(get_bloginfo('name')); ?></span>
                    <span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                        <meta itemprop="url" content="<?php echo esc_url(get_template_directory_uri() . '/assets/images/icon.png'); ?>">
                        <meta itemprop="width" content="200">
                        <meta itemprop="height" content="200">
                    </span>
                </div>
                <meta itemprop="mainEntityOfPage" content="<?php echo esc_url(get_permalink()); ?>">
                <meta itemprop="wordCount" content="<?php echo esc_attr(str_word_count(wp_strip_all_tags(get_the_content()))); ?>">
                <meta itemprop="inLanguage" content="<?php echo esc_attr(get_locale()); ?>">

                <!-- Post Footer -->
                <footer class="single-post-footer slack-post-footer entry-footer">

                    <!-- Tags -->
                    <?php if (has_tag()) : ?>
                        <div class="post-tags slack-tags">
                            <h4 class="tags-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20.59 13.41L13.42 20.58C13.2343 20.766 13.0137 20.9135 12.7709 21.0141C12.5281 21.1148 12.2678 21.1666 12.005 21.1666C11.7422 21.1666 11.4819 21.1148 11.2391 21.0141C10.9963 20.9135 10.7757 20.766 10.59 20.58L2 12V2H12L20.59 10.59C20.9625 10.9647 21.1716 11.4716 21.1716 12C21.1716 12.5284 20.9625 13.0353 20.59 13.41V13.41Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7 7H7.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <?php esc_html_e('Tags', 'wordiva-blog-theme'); ?>
                            </h4>
                            <div class="tag-list slack-tag-list" itemprop="keywords">
                                <?php the_tags('', '', ''); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Social Sharing Buttons -->
                    <div class="social-sharing slack-sharing">
                        <h4 class="sharing-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 8C19.6569 8 21 6.65685 21 5C21 3.34315 19.6569 2 18 2C16.3431 2 15 3.34315 15 5C15 5.18875 15.0129 5.37498 15.0378 5.55671L8.56739 9.26956C8.01725 8.51697 7.08259 8 6 8C4.34315 8 3 9.34315 3 11C3 12.6569 4.34315 14 6 14C7.08259 14 8.01725 13.483 8.56739 12.7304L15.0378 16.4433C15.0129 16.625 15 16.8113 15 17C15 18.6569 16.3431 20 18 20C19.6569 20 21 18.6569 21 17C21 15.3431 19.6569 14 18 14C16.9174 14 15.9827 14.517 15.4326 15.2696L8.96219 11.5567C8.98709 11.375 9 11.1887 9 11C9 10.8113 8.98709 10.625 8.96219 10.4433L15.4326 6.73044C15.9827 7.48303 16.9174 8 18 8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <?php esc_html_e('Share this article', 'wordiva-blog-theme'); ?>
                        </h4>
                        <div class="sharing-buttons">

                            <!-- Twitter/X Share -->
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>&via=wordivaai"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="share-button share-twitter"
                               aria-label="<?php esc_attr_e('Share on Twitter', 'wordiva-blog-theme'); ?>">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M23 3C22.0424 3.67548 20.9821 4.19211 19.86 4.53C19.2577 3.83751 18.4573 3.34669 17.567 3.12393C16.6767 2.90116 15.7395 2.95718 14.8821 3.28445C14.0247 3.61173 13.2884 4.19445 12.773 4.95371C12.2575 5.71297 11.9877 6.61435 12 7.53V8.53C10.2426 8.57557 8.50127 8.18581 6.93101 7.39624C5.36074 6.60667 4.01032 5.43666 3 4C3 4 -1 13 8 17C5.94053 18.398 3.48716 19.099 1 19C10 24 21 19 21 7.5C20.9991 7.22145 20.9723 6.94359 20.92 6.67C21.9406 5.66349 22.6608 4.39271 23 3V3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span><?php esc_html_e('Twitter', 'wordiva-blog-theme'); ?></span>
                            </a>

                            <!-- LinkedIn Share -->
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="share-button share-linkedin"
                               aria-label="<?php esc_attr_e('Share on LinkedIn', 'wordiva-blog-theme'); ?>">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 8C17.5913 8 19.1174 8.63214 20.2426 9.75736C21.3679 10.8826 22 12.4087 22 14V21H18V14C18 13.4696 17.7893 12.9609 17.4142 12.5858C17.0391 12.2107 16.5304 12 16 12C15.4696 12 14.9609 12.2107 14.5858 12.5858C14.2107 12.9609 14 13.4696 14 14V21H10V14C10 12.4087 10.6321 10.8826 11.7574 9.75736C12.8826 8.63214 14.4087 8 16 8V8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M6 9H2V21H6V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M4 6C5.10457 6 6 5.10457 6 4C6 2.89543 5.10457 2 4 2C2.89543 2 2 2.89543 2 4C2 5.10457 2.89543 6 4 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span><?php esc_html_e('LinkedIn', 'wordiva-blog-theme'); ?></span>
                            </a>

                            <!-- Copy Link -->
                            <button class="share-button share-copy"
                                    data-url="<?php echo esc_url(get_permalink()); ?>"
                                    aria-label="<?php esc_attr_e('Copy link to clipboard', 'wordiva-blog-theme'); ?>">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 13C10.4295 13.5741 10.9774 14.0491 11.6066 14.3929C12.2357 14.7367 12.9315 14.9411 13.6467 14.9923C14.3618 15.0435 15.0796 14.9403 15.7513 14.6897C16.4231 14.4392 17.0331 14.047 17.54 13.54L20.54 10.54C21.4508 9.59695 21.9548 8.33394 21.9434 7.02296C21.932 5.71198 21.4061 4.45791 20.4791 3.53087C19.5521 2.60383 18.298 2.07799 16.987 2.0666C15.676 2.0552 14.413 2.55918 13.47 3.47L11.75 5.18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14 11C13.5705 10.4259 13.0226 9.95085 12.3934 9.60706C11.7643 9.26327 11.0685 9.05885 10.3533 9.00763C9.63819 8.95641 8.92037 9.05963 8.24860 9.31018C7.57682 9.56073 6.96687 9.95295 6.46 10.46L3.46 13.46C2.54918 14.403 2.04520 15.6661 2.05660 16.977C2.068 18.288 2.59384 19.5421 3.52088 20.4691C4.44792 21.3962 5.70199 21.922 7.01297 21.9334C8.32395 21.9448 9.58701 21.4408 10.53 20.53L12.24 18.82" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span><?php esc_html_e('Copy Link', 'wordiva-blog-theme'); ?></span>
                            </button>

                        </div>
                    </div>

                </footer>

            </article>

    </div>

    <div class="container single-secondary-container">

        <?php // Secondary sections below the article ?>
            <?php if (get_the_author_meta('description')) : ?>
                <section class="author-bio slack-author-bio">
                    <div class="author-bio-content">
                        <div class="author-bio-avatar">
                            <?php echo get_avatar(get_the_author_meta('ID'), 64, '', '', array('class' => 'author-avatar-large')); ?>
                        </div>
                        <div class="author-bio-info">
                            <h3 class="author-bio-name">
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                    <?php echo esc_html(wordiva_get_author_display_name(get_the_author_meta('ID'))); ?>
                                </a>
                            </h3>
                            <p class="author-bio-description">
                                <?php echo wp_kses_post(get_the_author_meta('description')); ?>
                            </p>
                            <div class="author-bio-links">
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author-posts-link">
                                    <?php esc_html_e('View all posts', 'wordiva-blog-theme'); ?>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Post Navigation -->
            <?php
            $prev_post = get_previous_post();
            $next_post = get_next_post();
            ?>
            <?php if ($prev_post || $next_post): ?>
                <nav class="post-navigation slack-post-nav" aria-label="<?php esc_attr_e('Post Navigation', 'wordiva-blog-theme'); ?>">
                    <div class="nav-links">

                        <?php if ($prev_post) : ?>
                            <div class="nav-previous">
                                <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" class="nav-link nav-prev">
                                    <div class="nav-direction">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span class="nav-label"><?php esc_html_e('Previous', 'wordiva-blog-theme'); ?></span>
                                    </div>
                                    <h4 class="nav-title"><?php echo esc_html(wp_trim_words(get_the_title($prev_post), 8, '...')); ?></h4>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ($next_post) : ?>
                            <div class="nav-next">
                                <a href="<?php echo esc_url(get_permalink($next_post)); ?>" class="nav-link nav-next">
                                    <div class="nav-direction">
                                        <span class="nav-label"><?php esc_html_e('Next', 'wordiva-blog-theme'); ?></span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <h4 class="nav-title"><?php echo esc_html(wp_trim_words(get_the_title($next_post), 8, '...')); ?></h4>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </nav>
            <?php endif; ?>

            <!-- Comments Section -->
            <?php
            /* // If comments are open or we have at least one comment, load up the comment template. */
            /* if (comments_open() || get_comments_number()) : */
            /*     ?> */
            /*     <section class="comments-section slack-comments"> */
            /*         <?php comments_template(); ?> */
            /*     </section> */
            /*     <?php */
            /* endif; */
            ?>

            <!-- Related Posts using Card Layout -->
            <?php
            $related_posts = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => 3,
                'post__not_in' => array(get_the_ID()),
                'category__in' => wp_get_post_categories(get_the_ID()),
                'orderby' => 'date',
                'order' => 'DESC',
            ));

            // Fallback: If no related posts found by category, get recent posts
            if (!$related_posts->have_posts()) {
                $related_posts = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                    'post__not_in' => array(get_the_ID()),
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
            }

            if ($related_posts->have_posts()) : ?>
                <section class="related-posts slack-related-posts" itemscope itemtype="https://schema.org/ItemList">
                    <meta itemprop="name" content="Related Articles">
                    <meta itemprop="numberOfItems" content="<?php echo esc_attr($related_posts->found_posts); ?>">
                    <div class="related-posts-header">
                        <h3 class="related-posts-title"><?php esc_html_e('Related Articles', 'wordiva-blog-theme'); ?></h3>
                        <p class="related-posts-subtitle"><?php esc_html_e('Continue exploring these topics', 'wordiva-blog-theme'); ?></p>
                    </div>

                    <div class="post-grid related-post-grid" role="list" aria-label="Related articles">
                        <?php 
                        $item_position = 1;
                        while ($related_posts->have_posts()) : $related_posts->the_post(); 
                        ?>
                            <div class="post-grid-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" role="listitem">
                                <meta itemprop="position" content="<?php echo esc_attr($item_position); ?>">
                                <?php get_template_part('template-parts/content', 'card'); ?>
                            </div>
                        <?php 
                        $item_position++;
                        endwhile; 
                        ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                </section>
            <?php endif; ?>

    </div>

        <?php endwhile; ?>

</main>

<?php
get_template_part('template-parts/sticky-cta');
get_footer();
