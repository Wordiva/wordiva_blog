<?php
/**
 * SEO Meta Tags and Structured Data
 * Implements comprehensive SEO markup for search engine optimization
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add SEO meta tags to head
 */
function wordiva_seo_meta_tags() {
    global $post;
    
    // Get site information
    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');
    $site_url = home_url('/');
    
    // Default values
    $title = '';
    $description = '';
    $canonical_url = '';
    $image_url = '';
    $article_type = 'website';
    
    if (is_singular()) {
        // Single post/page
        $title = get_the_title() . ' | ' . $site_name;
        $description = get_the_excerpt() ? wp_trim_words(get_the_excerpt(), 25, '...') : wp_trim_words(get_the_content(), 25, '...');
        $canonical_url = get_permalink();
        
        if (has_post_thumbnail()) {
            $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
        }
        
        if (is_single()) {
            $article_type = 'article';
        }
    } elseif (is_home() || is_front_page()) {
        // Homepage
        $title = $site_name . ' | ' . $site_description;
        $description = $site_description;
        $canonical_url = $site_url;
    } elseif (is_category()) {
        // Category archive
        $category = get_queried_object();
        $title = 'Category: ' . $category->name . ' | ' . $site_name;
        $description = $category->description ? $category->description : 'Browse articles in ' . $category->name . ' category.';
        $canonical_url = get_category_link($category->term_id);
    } elseif (is_tag()) {
        // Tag archive
        $tag = get_queried_object();
        $title = 'Tag: ' . $tag->name . ' | ' . $site_name;
        $description = $tag->description ? $tag->description : 'Browse articles tagged with ' . $tag->name . '.';
        $canonical_url = get_tag_link($tag->term_id);
    } elseif (is_search()) {
        // Search results
        $search_query = get_search_query();
        $title = 'Search Results for "' . $search_query . '" | ' . $site_name;
        $description = 'Search results for "' . $search_query . '" on ' . $site_name . '.';
        $canonical_url = get_search_link($search_query);
    } elseif (is_404()) {
        // 404 page
        $title = 'Page Not Found | ' . $site_name;
        $description = 'The page you are looking for could not be found.';
        $canonical_url = $site_url;
    }
    
    // Fallback image
    if (empty($image_url)) {
        $image_url = get_template_directory_uri() . '/assets/images/wordiva-og-default.jpg';
    }
    
    // Clean description
    $description = wp_strip_all_tags($description);
    $description = str_replace(array("\r", "\n", "\t"), ' ', $description);
    $description = preg_replace('/\s+/', ' ', trim($description));
    
    ?>
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo esc_attr($description); ?>">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="<?php echo esc_url($canonical_url); ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:locale" content="<?php echo esc_attr(get_locale()); ?>">
    <meta property="og:type" content="<?php echo esc_attr($article_type); ?>">
    <meta property="og:title" content="<?php echo esc_attr($title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($description); ?>">
    <meta property="og:url" content="<?php echo esc_url($canonical_url); ?>">
    <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>">
    <meta property="og:image" content="<?php echo esc_url($image_url); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="image/jpeg">
    
    <?php if (is_single() && get_post_type() === 'post') : ?>
        <!-- Article-specific Open Graph tags -->
        <meta property="article:published_time" content="<?php echo esc_attr(get_the_date('c')); ?>">
        <meta property="article:modified_time" content="<?php echo esc_attr(get_the_modified_date('c')); ?>">
        <meta property="article:author" content="<?php echo esc_attr(get_the_author()); ?>">
        <meta property="article:section" content="<?php echo esc_attr(wp_strip_all_tags(get_the_category_list(', '))); ?>">
        <?php
        $tags = get_the_tags();
        if ($tags) {
            foreach ($tags as $tag) {
                echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
            }
        }
        ?>
    <?php endif; ?>
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@wordiva">
    <meta name="twitter:creator" content="@wordiva">
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
    <meta name="twitter:image" content="<?php echo esc_url($image_url); ?>">
    
    <!-- Additional SEO Meta Tags -->
    <meta name="author" content="<?php echo is_single() ? esc_attr(get_the_author()) : esc_attr($site_name); ?>">
    <meta name="generator" content="WordPress <?php echo esc_attr(get_bloginfo('version')); ?>">
    
    <?php if (is_single()) : ?>
        <!-- Article-specific meta tags -->
        <meta name="article:published_time" content="<?php echo esc_attr(get_the_date('c')); ?>">
        <meta name="article:modified_time" content="<?php echo esc_attr(get_the_modified_date('c')); ?>">
    <?php endif; ?>
    
    <?php
}
add_action('wp_head', 'wordiva_seo_meta_tags', 1);

/**
 * Add JSON-LD Structured Data
 */
function wordiva_structured_data() {
    global $post;
    
    $site_name = get_bloginfo('name');
    $site_url = home_url('/');
    $logo_url = get_template_directory_uri() . '/assets/images/icon.png';
    
    // Organization Schema (always present)
    $organization_schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $site_name,
        'url' => $site_url,
        'logo' => array(
            '@type' => 'ImageObject',
            'url' => $logo_url,
            'width' => 200,
            'height' => 200
        ),
        'description' => get_bloginfo('description'),
        'sameAs' => array(
            get_theme_mod('wordiva_twitter_url', 'https://twitter.com/wordiva'),
            get_theme_mod('wordiva_linkedin_url', 'https://linkedin.com/company/wordiva')
        )
    );
    
    // Website Schema (always present)
    $website_schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $site_name,
        'url' => $site_url,
        'description' => get_bloginfo('description'),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => $site_name,
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => $logo_url
            )
        ),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => array(
                '@type' => 'EntryPoint',
                'urlTemplate' => $site_url . '?s={search_term_string}'
            ),
            'query-input' => 'required name=search_term_string'
        )
    );
    
    // Output Organization and Website schemas
    echo '<script type="application/ld+json">' . wp_json_encode($organization_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    echo '<script type="application/ld+json">' . wp_json_encode($website_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    
    // Article Schema for single posts
    if (is_single() && get_post_type() === 'post') {
        $author_name = get_the_author();
        $author_url = get_author_posts_url(get_the_author_meta('ID'));
        $featured_image = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : $logo_url;
        
        $article_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'description' => get_the_excerpt() ? wp_trim_words(get_the_excerpt(), 25, '...') : wp_trim_words(get_the_content(), 25, '...'),
            'image' => array(
                '@type' => 'ImageObject',
                'url' => $featured_image,
                'width' => 1200,
                'height' => 630
            ),
            'author' => array(
                '@type' => 'Person',
                'name' => $author_name,
                'url' => $author_url
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => $site_name,
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => $logo_url,
                    'width' => 200,
                    'height' => 200
                )
            ),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id' => get_permalink()
            ),
            'url' => get_permalink(),
            'wordCount' => str_word_count(wp_strip_all_tags(get_the_content())),
            'articleSection' => wp_strip_all_tags(get_the_category_list(', ')),
            'inLanguage' => get_locale()
        );
        
        // Add keywords if tags exist
        $tags = get_the_tags();
        if ($tags) {
            $keywords = array();
            foreach ($tags as $tag) {
                $keywords[] = $tag->name;
            }
            $article_schema['keywords'] = implode(', ', $keywords);
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($article_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
    
    // Breadcrumb Schema for single posts and pages
    if (is_singular() && !is_front_page()) {
        $breadcrumb_items = array();
        
        // Home
        $breadcrumb_items[] = array(
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => $site_url
        );
        
        $position = 2;
        
        // Blog page (if not homepage)
        if (is_single() && get_post_type() === 'post') {
            $blog_page_id = get_option('page_for_posts');
            if ($blog_page_id) {
                $breadcrumb_items[] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => get_the_title($blog_page_id),
                    'item' => get_permalink($blog_page_id)
                );
                $position++;
            } else {
                $breadcrumb_items[] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => 'Blog',
                    'item' => $site_url . 'blog/'
                );
                $position++;
            }
            
            // Category (if exists)
            $categories = get_the_category();
            if (!empty($categories)) {
                $category = $categories[0];
                $breadcrumb_items[] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => $category->name,
                    'item' => get_category_link($category->term_id)
                );
                $position++;
            }
        }
        
        // Current page
        $breadcrumb_items[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title(),
            'item' => get_permalink()
        );
        
        $breadcrumb_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumb_items
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode($breadcrumb_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
    
    // Blog Schema for blog listing pages
    if (is_home() || is_category() || is_tag()) {
        $blog_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Blog',
            'name' => is_home() ? $site_name . ' Blog' : (is_category() ? 'Category: ' . single_cat_title('', false) : 'Tag: ' . single_tag_title('', false)),
            'description' => is_home() ? get_bloginfo('description') : (is_category() ? category_description() : tag_description()),
            'url' => is_home() ? $site_url : (is_category() ? get_category_link(get_queried_object_id()) : get_tag_link(get_queried_object_id())),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => $site_name,
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => $logo_url
                )
            ),
            'inLanguage' => get_locale()
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode($blog_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}
add_action('wp_head', 'wordiva_structured_data', 2);

/**
 * Add additional SEO-related head tags
 */
function wordiva_additional_seo_tags() {
    ?>
    <!-- DNS Prefetch for performance -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    
    <!-- Preconnect for critical resources -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Theme color for mobile browsers -->
    <meta name="theme-color" content="#2F80FF">
    <meta name="msapplication-TileColor" content="#2F80FF">
    
    <!-- Apple touch icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/apple-touch-icon.png'); ?>">
    
    <!-- Note: Favicons are handled by WordPress Site Icon setting since version 4.3 -->
    
    <!-- Manifest for PWA -->
    <link rel="manifest" href="<?php echo esc_url(get_template_directory_uri() . '/manifest.json'); ?>">
    
    <?php if (is_singular()) : ?>
        <!-- Prevent duplicate content -->
        <link rel="shortlink" href="<?php echo esc_url(wp_get_shortlink()); ?>">
        
        <!-- RSS feed for comments -->
        <link rel="alternate" type="application/rss+xml" title="<?php echo esc_attr(get_the_title()); ?> Comments Feed" href="<?php echo esc_url(get_post_comments_feed_link()); ?>">
    <?php endif; ?>
    
    <!-- RSS feeds -->
    <link rel="alternate" type="application/rss+xml" title="<?php echo esc_attr(get_bloginfo('name')); ?> Feed" href="<?php echo esc_url(get_feed_link()); ?>">
    <link rel="alternate" type="application/atom+xml" title="<?php echo esc_attr(get_bloginfo('name')); ?> Atom Feed" href="<?php echo esc_url(get_feed_link('atom')); ?>">
    
    <?php
}
add_action('wp_head', 'wordiva_additional_seo_tags', 3);

/**
 * Generate XML sitemap (basic implementation)
 */
function wordiva_generate_sitemap() {
    if (isset($_GET['sitemap']) && $_GET['sitemap'] === 'xml') {
        header('Content-Type: application/xml; charset=utf-8');
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Homepage
        echo '<url>' . "\n";
        echo '<loc>' . esc_url(home_url('/')) . '</loc>' . "\n";
        echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
        echo '<changefreq>daily</changefreq>' . "\n";
        echo '<priority>1.0</priority>' . "\n";
        echo '</url>' . "\n";
        
        // Posts
        $posts = get_posts(array(
            'numberposts' => -1,
            'post_status' => 'publish',
            'post_type' => 'post'
        ));
        
        foreach ($posts as $post) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_permalink($post->ID)) . '</loc>' . "\n";
            echo '<lastmod>' . date('c', strtotime($post->post_modified)) . '</lastmod>' . "\n";
            echo '<changefreq>weekly</changefreq>' . "\n";
            echo '<priority>0.8</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        // Pages
        $pages = get_pages(array(
            'post_status' => 'publish'
        ));
        
        foreach ($pages as $page) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_permalink($page->ID)) . '</loc>' . "\n";
            echo '<lastmod>' . date('c', strtotime($page->post_modified)) . '</lastmod>' . "\n";
            echo '<changefreq>monthly</changefreq>' . "\n";
            echo '<priority>0.6</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        // Categories
        $categories = get_categories();
        foreach ($categories as $category) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_category_link($category->term_id)) . '</loc>' . "\n";
            echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
            echo '<changefreq>weekly</changefreq>' . "\n";
            echo '<priority>0.5</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        echo '</urlset>' . "\n";
        exit;
    }
}
add_action('init', 'wordiva_generate_sitemap');

/**
 * Add robots.txt improvements
 */
function wordiva_robots_txt($output, $public) {
    if ($public) {
        $output .= "Sitemap: " . home_url('/') . "?sitemap=xml\n";
        $output .= "User-agent: *\n";
        $output .= "Disallow: /wp-admin/\n";
        $output .= "Disallow: /wp-includes/\n";
        $output .= "Disallow: /wp-content/plugins/\n";
        $output .= "Disallow: /wp-content/themes/\n";
        $output .= "Disallow: /trackback/\n";
        $output .= "Disallow: /feed/\n";
        $output .= "Disallow: /comments/\n";
        $output .= "Disallow: /category/*/*\n";
        $output .= "Disallow: */trackback/\n";
        $output .= "Disallow: */feed/\n";
        $output .= "Disallow: */comments/\n";
        $output .= "Disallow: /*?*\n";
        $output .= "Disallow: /*?\n";
        $output .= "Allow: /wp-content/uploads/\n";
        $output .= "Allow: /wp-content/themes/*/assets/\n";
    }
    
    return $output;
}
add_filter('robots_txt', 'wordiva_robots_txt', 10, 2);