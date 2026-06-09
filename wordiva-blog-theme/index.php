<?php
/**
 * The main template file - Wordiva Product Blog Homepage
 * Slack.dev-inspired card-based layout with Wordiva brand colors
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main" itemscope itemtype="https://schema.org/Blog">
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Welcome to Wordiva <span>Blog</span></h1>
                <p class="hero-subtitle">Discover the latest updates, insights, and innovations in AI-powered content marketing.</p>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <div class="main-content">
        <div class="container">
            
            <?php if (have_posts()) : ?>
                
                <?php
                // Initialize variables first for schema markup
                $featured_post_id = null;
                $main_query = null;
                
                // Get featured post first
                $featured_query = new WP_Query(array(
                    'posts_per_page' => 1,
                    'post_status' => 'publish',
                    'meta_query' => array(
                        array(
                            'key' => '_wordiva_featured_post',
                            'value' => '1',
                            'compare' => '='
                        )
                    )
                ));
                
                if ($featured_query->have_posts()) {
                    $featured_query->the_post();
                    $featured_post_id = get_the_ID();
                    wp_reset_postdata();
                }
                
                // Get regular posts (excluding featured post)
                $posts_per_page = get_option('posts_per_page', 10);
                $paged = get_query_var('paged') ? get_query_var('paged') : 1;
                
                $main_query_args = array(
                    'posts_per_page' => $posts_per_page,
                    'post_status' => 'publish',
                    'paged' => $paged
                );
                
                // Exclude featured post from main query if it exists
                if ($featured_post_id) {
                    $main_query_args['post__not_in'] = array($featured_post_id);
                }
                
                $main_query = new WP_Query($main_query_args);
                ?>
                
                <!-- Featured Post Section - Centered Above Grid -->
                <?php if ($featured_post_id) : ?>
                    <section class="featured-post-section" aria-label="Featured post">
                        <div class="featured-post-wrapper">
                            <?php
                            // Re-query the featured post for display
                            $featured_display_query = new WP_Query(array(
                                'p' => $featured_post_id,
                                'post_type' => 'post'
                            ));
                            
                            if ($featured_display_query->have_posts()) :
                                while ($featured_display_query->have_posts()) : $featured_display_query->the_post();
                                    // Set featured post meta for the card component
                                    update_post_meta($featured_post_id, '_wordiva_featured_post', '1');
                                    get_template_part('template-parts/content', 'card');
                                endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Slack.dev-Inspired Card Grid -->
                <section class="blog-posts-section" aria-labelledby="posts-heading" itemscope itemtype="https://schema.org/ItemList">
                    <h2 id="posts-heading" class="section-title screen-reader-text">Latest Blog Posts</h2>
                    <meta itemprop="name" content="Latest Blog Posts">
                    <meta itemprop="numberOfItems" content="<?php echo esc_attr($main_query->found_posts + ($featured_post_id ? 1 : 0)); ?>">
                    
                    <!-- Card Grid Layout inspired by slack.dev -->
                    <div class="post-grid index-post-grid" role="list" aria-label="Blog posts">
                        <?php
                        $item_position = 1;
                        
                        // Increment position if featured post was shown
                        if ($featured_post_id) {
                            $item_position++;
                        }
                        
                        if ($main_query->have_posts()) :
                            while ($main_query->have_posts()) : $main_query->the_post();
                                // Ensure this is not marked as featured
                                delete_post_meta(get_the_ID(), '_wordiva_featured_post');
                                
                                echo '<div class="post-grid-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" role="listitem">';
                                echo '<meta itemprop="position" content="' . esc_attr($item_position) . '">';
                                get_template_part('template-parts/content', 'card');
                                echo '</div>';
                                $item_position++;
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                    
                    <?php
                    // Pagination - using the already defined $main_query
                    if ($main_query && $main_query->max_num_pages > 1) :
                        ?>
                        <nav class="pagination-wrapper" role="navigation" aria-labelledby="pagination-heading">
                            <h3 id="pagination-heading" class="screen-reader-text"><?php esc_html_e('Posts navigation', 'wordiva-blog-theme'); ?></h3>
                            <?php
                            echo paginate_links(array(
                                'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                                'format' => '?paged=%#%',
                                'current' => max(1, $paged),
                                'total' => $main_query->max_num_pages,
                                'prev_text' => '<span aria-hidden="true">&larr;</span> ' . __('Previous', 'wordiva-blog-theme'),
                                'next_text' => __('Next', 'wordiva-blog-theme') . ' <span aria-hidden="true">&rarr;</span>',
                                'mid_size' => 2,
                                'end_size' => 1,
                                'type' => 'list',
                                'add_args' => false,
                                'add_fragment' => '',
                                'before_page_number' => '<span class="screen-reader-text">' . __('Page', 'wordiva-blog-theme') . ' </span>',
                                'after_page_number' => '',
                            ));
                            ?>
                        </nav>
                        <?php
                    endif;
                    ?>
                </section>
                
            <?php else : ?>
                
                <div class="no-posts" role="alert">
                    <h2><?php esc_html_e('No posts found', 'wordiva-blog-theme'); ?></h2>
                    <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'wordiva-blog-theme'); ?></p>
                    <?php get_search_form(); ?>
                </div>
                
            <?php endif; ?>
            
        </div>
    </div>
</main>

<?php get_footer(); ?>