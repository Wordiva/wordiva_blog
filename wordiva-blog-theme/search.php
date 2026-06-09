<?php
/**
 * The template for displaying search results
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main search-main" role="main" itemscope itemtype="https://schema.org/SearchResultsPage">
    
    <!-- Search Header -->
    <section class="search-page-header" itemscope itemtype="https://schema.org/WebPageElement">
        <div class="container">
            <div class="search-header-content">
                <h1 class="search-page-title" itemprop="name">
                    Search <span class="search-results-text">Results</span>
                </h1>
                
                <?php if (have_posts()) : ?>
                    <p class="search-subtitle" itemprop="description">
                        Search Results for: <strong><?php echo esc_html(get_search_query()); ?></strong>
                    </p>
                    <meta itemprop="numberOfItems" content="<?php echo esc_attr($wp_query->found_posts); ?>">
                <?php else : ?>
                    <p class="search-subtitle" itemprop="description">
                        Search Results for: <strong><?php echo esc_html(get_search_query()); ?></strong>
                    </p>
                    <meta itemprop="numberOfItems" content="0">
                <?php endif; ?>
                
                <p class="search-description">
                    Down here you'll find the results of your search. If you do not find what you are looking for try with a different term or <a href="<?php echo esc_url(home_url('/contact')); ?>" class="contact-link">contact us</a>.
                </p>
                
                <!-- Search Form -->
                <div class="search-form-container" itemscope itemtype="https://schema.org/WebSite">
                    <meta itemprop="url" content="<?php echo esc_url(home_url('/')); ?>">
                    <form role="search" method="get" class="search-page-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="search-input-wrapper">
                            <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <input type="search" class="search-page-field" placeholder="Search..." value="<?php echo esc_attr(get_search_query()); ?>" name="s" required />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <div class="main-content">
        <div class="container">
        
        <?php if (have_posts()) : ?>
            
            <!-- Search Results -->
            <section class="blog-posts-section search-results-section" aria-labelledby="search-results-heading" itemscope itemtype="https://schema.org/ItemList">
                <h2 id="search-results-heading" class="section-title screen-reader-text">Search Results</h2>
                <meta itemprop="name" content="<?php echo esc_attr('Search Results for: ' . get_search_query()); ?>">
                
                <!-- Search Results Grid using same structure as archive.php -->
                <div class="post-grid archive-post-grid search-post-grid" role="list" aria-label="Search results">
                    <?php 
                    $item_position = 1;
                    while (have_posts()) : the_post(); ?>
                        
                        <div class="post-grid-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" role="listitem">
                            <meta itemprop="position" content="<?php echo esc_attr($item_position); ?>">
                            
                            <!-- Use the existing card component for consistency -->
                            <?php 
                            // Ensure search results are not marked as featured
                            delete_post_meta(get_the_ID(), '_wordiva_featured_post');
                            get_template_part('template-parts/content', 'card'); 
                            ?>
                            
                        </div>
                        
                    <?php 
                    $item_position++;
                    endwhile; ?>
                    
                </div>
                
                <!-- Pagination using same structure as archive.php -->
                <?php
                global $wp_query;
                $pagination_args = array(
                    'prev_text' => '<span aria-hidden="true">&laquo;</span> ' . __('Previous', 'wordiva-blog-theme'),
                    'next_text' => __('Next', 'wordiva-blog-theme') . ' <span aria-hidden="true">&raquo;</span>',
                    'before_page_number' => '<span class="screen-reader-text">' . __('Page', 'wordiva-blog-theme') . ' </span>',
                );
                
                $pagination_links = paginate_links($pagination_args);
                if ($pagination_links) :
                ?>
                    <nav class="pagination archive-pagination search-pagination" role="navigation" aria-labelledby="search-pagination-heading" itemscope itemtype="https://schema.org/SiteNavigationElement">
                        <h3 id="search-pagination-heading" class="screen-reader-text"><?php esc_html_e('Search results navigation', 'wordiva-blog-theme'); ?></h3>
                        <?php echo $pagination_links; ?>
                    </nav>
                <?php endif; ?>
                
            </section>
            
        <?php else : ?>
            
            <!-- No Results -->
            <div class="no-posts no-search-results" role="alert">
                <div class="no-results-content">
                    
                    <div class="no-results-icon">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    
                    <div class="no-results-text">
                        <h2><?php esc_html_e('No results found', 'wordiva-blog-theme'); ?></h2>
                        <p><?php esc_html_e('It looks like nothing was found for your search. Maybe try a different term?', 'wordiva-blog-theme'); ?></p>
                    </div>
                    
                </div>
            </div>
            
        <?php endif; ?>
        
        </div>
    </div>
</main>

<?php get_footer(); ?>