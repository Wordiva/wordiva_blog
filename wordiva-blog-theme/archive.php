<?php
/**
 * The template for displaying archive pages
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main archive-main" role="main" itemscope itemtype="https://schema.org/CollectionPage">
    <div class="container">
        
        <?php if (have_posts()) : ?>
            
            <!-- Archive Header -->
            <header class="archive-header" itemscope itemtype="https://schema.org/WebPageElement">
                <?php
                $archive_title = '';
                $archive_description = '';
                
                if (is_category()) {
                    $category = get_queried_object();
                    $archive_title = 'Category: ' . $category->name;
                    $archive_description = $category->description ? $category->description : 'Browse articles in the ' . $category->name . ' category.';
                } elseif (is_tag()) {
                    $tag = get_queried_object();
                    $archive_title = 'Tag: ' . $tag->name;
                    $archive_description = $tag->description ? $tag->description : 'Browse articles tagged with ' . $tag->name . '.';
                } elseif (is_author()) {
                    $author = get_queried_object();
                    $archive_title = 'Author: ' . $author->display_name;
                    $archive_description = $author->description ? $author->description : 'Articles by ' . $author->display_name . '.';
                } elseif (is_date()) {
                    if (is_year()) {
                        $archive_title = 'Year: ' . get_the_date('Y');
                        $archive_description = 'Articles from ' . get_the_date('Y') . '.';
                    } elseif (is_month()) {
                        $archive_title = 'Month: ' . get_the_date('F Y');
                        $archive_description = 'Articles from ' . get_the_date('F Y') . '.';
                    } elseif (is_day()) {
                        $archive_title = 'Day: ' . get_the_date('F j, Y');
                        $archive_description = 'Articles from ' . get_the_date('F j, Y') . '.';
                    }
                } else {
                    $archive_title = 'Archives';
                    $archive_description = 'Browse our article archives.';
                }
                ?>
                
                <h1 class="archive-title" itemprop="name"><?php echo esc_html($archive_title); ?></h1>
                
                <?php if ($archive_description) : ?>
                    <div class="archive-description" itemprop="description">
                        <p><?php echo wp_kses_post($archive_description); ?></p>
                    </div>
                <?php endif; ?>
                
            </header>
            
            <!-- Archive Content -->
            <section class="archive-content" itemscope itemtype="https://schema.org/ItemList">
                <meta itemprop="name" content="<?php echo esc_attr($archive_title); ?>">
                <meta itemprop="numberOfItems" content="<?php echo esc_attr($wp_query->found_posts); ?>">
                
                <!-- Card Grid Layout inspired by slack.dev -->
                <div class="post-grid archive-post-grid" role="list" aria-label="<?php esc_attr_e('Archive posts', 'wordiva-blog-theme'); ?>">
                    
                    <?php
                    $item_position = 1;
                    while (have_posts()) : the_post();
                        echo '<div class="post-grid-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" role="listitem">';
                        echo '<meta itemprop="position" content="' . esc_attr($item_position) . '">';
                        
                        // Use the card template for consistent slack.dev-inspired design
                        get_template_part('template-parts/content', 'card');
                        
                        echo '</div>';
                        $item_position++;
                    endwhile;
                    ?>
                    
                </div>
                
                <!-- Pagination -->
                <?php
                $pagination_args = array(
                    'prev_text' => '<span aria-hidden="true">&laquo;</span> ' . __('Previous', 'wordiva-blog-theme'),
                    'next_text' => __('Next', 'wordiva-blog-theme') . ' <span aria-hidden="true">&raquo;</span>',
                    'before_page_number' => '<span class="screen-reader-text">' . __('Page', 'wordiva-blog-theme') . ' </span>',
                );
                
                $pagination_links = paginate_links($pagination_args);
                if ($pagination_links) :
                ?>
                    <nav class="pagination archive-pagination" role="navigation" aria-label="<?php esc_attr_e('Archive pagination', 'wordiva-blog-theme'); ?>" itemscope itemtype="https://schema.org/SiteNavigationElement">
                        <?php echo $pagination_links; ?>
                    </nav>
                <?php endif; ?>
                
            </section>
            
        <?php else : ?>
            
            <!-- Enhanced No Posts Found State -->
            <section class="no-posts-found" role="alert" itemscope itemtype="https://schema.org/WebPageElement">
                <div class="no-posts-container">
                    
                    <!-- Empty State Icon -->
                    <div class="no-posts-icon" aria-hidden="true">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8.5 8.5L15.5 15.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15.5 8.5L8.5 15.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    
                    <!-- Dynamic Title Based on Archive Type -->
                    <header class="no-posts-header">
                        <?php
                        $empty_title = '';
                        $empty_message = '';
                        $suggestions = array();
                        
                        if (is_category()) {
                            $category = get_queried_object();
                            $empty_title = sprintf(__('No posts in %s yet', 'wordiva-blog-theme'), $category->name);
                            $empty_message = sprintf(__('We haven\'t published any articles in the %s category yet, but we\'re working on it!', 'wordiva-blog-theme'), $category->name);
                            
                            // Get related categories for suggestions
                            $related_categories = get_categories(array(
                                'exclude' => $category->term_id,
                                'number' => 3,
                                'orderby' => 'count',
                                'order' => 'DESC'
                            ));
                            
                            if (!empty($related_categories)) {
                                $suggestions[] = array(
                                    'title' => __('Explore Related Categories', 'wordiva-blog-theme'),
                                    'items' => $related_categories,
                                    'type' => 'category'
                                );
                            }
                            
                        } elseif (is_tag()) {
                            $tag = get_queried_object();
                            $empty_title = sprintf(__('No posts tagged with %s', 'wordiva-blog-theme'), $tag->name);
                            $empty_message = sprintf(__('We haven\'t published any articles with the %s tag yet.', 'wordiva-blog-theme'), $tag->name);
                            
                            // Get related tags for suggestions
                            $related_tags = get_tags(array(
                                'exclude' => $tag->term_id,
                                'number' => 5,
                                'orderby' => 'count',
                                'order' => 'DESC'
                            ));
                            
                            if (!empty($related_tags)) {
                                $suggestions[] = array(
                                    'title' => __('Try These Tags Instead', 'wordiva-blog-theme'),
                                    'items' => $related_tags,
                                    'type' => 'tag'
                                );
                            }
                            
                        } elseif (is_author()) {
                            $author = get_queried_object();
                            $empty_title = sprintf(__('No posts by %s yet', 'wordiva-blog-theme'), $author->display_name);
                            $empty_message = sprintf(__('%s hasn\'t published any articles yet.', 'wordiva-blog-theme'), $author->display_name);
                            
                        } elseif (is_date()) {
                            if (is_year()) {
                                $empty_title = sprintf(__('No posts from %s', 'wordiva-blog-theme'), get_the_date('Y'));
                                $empty_message = sprintf(__('We didn\'t publish any articles in %s.', 'wordiva-blog-theme'), get_the_date('Y'));
                            } elseif (is_month()) {
                                $empty_title = sprintf(__('No posts from %s', 'wordiva-blog-theme'), get_the_date('F Y'));
                                $empty_message = sprintf(__('We didn\'t publish any articles in %s.', 'wordiva-blog-theme'), get_the_date('F Y'));
                            } elseif (is_day()) {
                                $empty_title = sprintf(__('No posts from %s', 'wordiva-blog-theme'), get_the_date('F j, Y'));
                                $empty_message = sprintf(__('We didn\'t publish any articles on %s.', 'wordiva-blog-theme'), get_the_date('F j, Y'));
                            }
                        } else {
                            $empty_title = __('No posts found', 'wordiva-blog-theme');
                            $empty_message = __('This archive doesn\'t contain any posts yet.', 'wordiva-blog-theme');
                        }
                        ?>
                        
                        <h1 class="no-posts-title" itemprop="name"><?php echo esc_html($empty_title); ?></h1>
                        <p class="no-posts-message" itemprop="description"><?php echo esc_html($empty_message); ?></p>
                    </header>
                    
                    <!-- Helpful Suggestions -->
                    <?php if (!empty($suggestions)) : ?>
                        <div class="no-posts-suggestions">
                            <?php foreach ($suggestions as $suggestion) : ?>
                                <div class="suggestion-group">
                                    <h3 class="suggestion-title"><?php echo esc_html($suggestion['title']); ?></h3>
                                    <div class="suggestion-items">
                                        <?php foreach ($suggestion['items'] as $item) : ?>
                                            <a href="<?php echo esc_url(get_term_link($item)); ?>" 
                                               class="suggestion-item"
                                               aria-label="<?php echo esc_attr(sprintf(__('Browse %s', 'wordiva-blog-theme'), $item->name)); ?>">
                                                <span class="suggestion-name"><?php echo esc_html($item->name); ?></span>
                                                <span class="suggestion-count"><?php echo esc_html($item->count); ?> <?php echo _n('post', 'posts', $item->count, 'wordiva-blog-theme'); ?></span>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Alternative Actions -->
                    <div class="no-posts-actions">
                        
                        <!-- Search Form -->
                        <div class="action-group">
                            <h3 class="action-title"><?php esc_html_e('Search for Content', 'wordiva-blog-theme'); ?></h3>
                            <p class="action-description"><?php esc_html_e('Try searching for specific topics or keywords.', 'wordiva-blog-theme'); ?></p>
                            <div class="search-form-wrapper">
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                        
                        <!-- Browse All Categories -->
                        <div class="action-group">
                            <h3 class="action-title"><?php esc_html_e('Browse All Categories', 'wordiva-blog-theme'); ?></h3>
                            <p class="action-description"><?php esc_html_e('Explore our full range of topics and categories.', 'wordiva-blog-theme'); ?></p>
                            <div class="category-links">
                                <?php
                                $popular_categories = get_categories(array(
                                    'number' => 6,
                                    'orderby' => 'count',
                                    'order' => 'DESC'
                                ));
                                
                                if (!empty($popular_categories)) :
                                    foreach ($popular_categories as $cat) :
                                ?>
                                    <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" 
                                       class="category-link"
                                       aria-label="<?php echo esc_attr(sprintf(__('Browse %s category', 'wordiva-blog-theme'), $cat->name)); ?>">
                                        <?php echo esc_html($cat->name); ?>
                                        <span class="category-count"><?php echo esc_html($cat->count); ?></span>
                                    </a>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </div>
                        
                        <!-- Back to Homepage -->
                        <div class="action-group">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-home">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <?php esc_html_e('Back to Homepage', 'wordiva-blog-theme'); ?>
                            </a>
                        </div>
                        
                    </div>
                    
                </div>
            </section>
            
        <?php endif; ?>
        
    </div>
</main>

<?php get_footer(); ?>