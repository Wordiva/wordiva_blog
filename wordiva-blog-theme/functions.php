<?php
/**
 * Wordiva Theme functions and definitions
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define theme constants
 */
define('WORDIVA_THEME_VERSION', wp_get_theme()->get('Version'));
define('WORDIVA_THEME_DIR', get_template_directory());
define('WORDIVA_THEME_URI', get_template_directory_uri());

/**
 * Include theme files in organized structure
 */
$wordiva_includes = array(
    'inc/theme-setup.php',          // Theme setup and configuration
    'inc/enqueue-scripts.php',      // Scripts and styles enqueuing
    'inc/customizer.php',           // WordPress Customizer settings
    'inc/accessibility.php',        // Accessibility enhancements
    'inc/seo.php',                  // SEO and structured data
    'inc/author-profile.php',       // Author E-E-A-T fields
    'inc/post-meta.php',           // Custom post meta boxes
    'inc/helper-functions.php',     // Helper functions and utilities
    'inc/nav-links.php',            // Hardcoded mega menu + footer link data
    'inc/theme-activation.php',     // Theme activation/deactivation
    'inc/comments.php',            // Comments functionality
);

// Include all theme files
foreach ($wordiva_includes as $file) {
    $file_path = WORDIVA_THEME_DIR . '/' . $file;
    if (file_exists($file_path)) {
        require_once $file_path;
    }
}

/**
 * Register block patterns
 */
function wordiva_register_block_patterns() {
    if (function_exists('register_block_pattern')) {
        // Call to Action Pattern
        register_block_pattern(
            'wordiva/cta-section',
            array(
                'title'       => __('Wordiva CTA Section', 'wordiva-blog-theme'),
                'description' => __('A call-to-action section with Wordiva branding', 'wordiva-blog-theme'),
                'content'     => '<!-- wp:group {"backgroundColor":"electric-blue","textColor":"white","className":"wordiva-cta-section"} -->
<div class="wp-block-group wordiva-cta-section has-white-color has-electric-blue-background-color has-text-color has-background">
    <!-- wp:heading {"textAlign":"center","level":2} -->
    <h2 class="has-text-align-center">Transform Your Content Strategy</h2>
    <!-- /wp:heading -->
    
    <!-- wp:paragraph {"align":"center"} -->
    <p class="has-text-align-center">Discover how AI-powered content creation can revolutionize your marketing approach.</p>
    <!-- /wp:paragraph -->
    
    <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
    <div class="wp-block-buttons">
        <!-- wp:button {"backgroundColor":"white","textColor":"electric-blue"} -->
        <div class="wp-block-button"><a class="wp-block-button__link has-electric-blue-color has-white-background-color has-text-color has-background">Get Started</a></div>
        <!-- /wp:button -->
    </div>
    <!-- /wp:buttons -->
</div>
<!-- /wp:group -->',
                'categories'  => array('wordiva'),
            )
        );
        
        // Feature Highlight Pattern
        register_block_pattern(
            'wordiva/feature-highlight',
            array(
                'title'       => __('Wordiva Feature Highlight', 'wordiva-blog-theme'),
                'description' => __('A feature highlight section with icon and description', 'wordiva-blog-theme'),
                'content'     => '<!-- wp:columns -->
<div class="wp-block-columns">
    <!-- wp:column -->
    <div class="wp-block-column">
        <!-- wp:heading {"level":3,"textColor":"royal-purple"} -->
        <h3 class="has-royal-purple-color has-text-color">AI-Powered Writing</h3>
        <!-- /wp:heading -->
        
        <!-- wp:paragraph -->
        <p>Generate high-quality content with advanced artificial intelligence that understands your brand voice and audience.</p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->
    
    <!-- wp:column -->
    <div class="wp-block-column">
        <!-- wp:heading {"level":3,"textColor":"royal-purple"} -->
        <h3 class="has-royal-purple-color has-text-color">Smart Optimization</h3>
        <!-- /wp:heading -->
        
        <!-- wp:paragraph -->
        <p>Automatically optimize your content for search engines and social media platforms to maximize reach and engagement.</p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->
</div>
<!-- /wp:columns -->',
                'categories'  => array('wordiva'),
            )
        );
    }
}
add_action('init', 'wordiva_register_block_patterns');

/**
 * Register block pattern category
 */
function wordiva_register_block_pattern_category() {
    if (function_exists('register_block_pattern_category')) {
        register_block_pattern_category(
            'wordiva',
            array('label' => __('Wordiva', 'wordiva-blog-theme'))
        );
    }
}
add_action('init', 'wordiva_register_block_pattern_category');

/**
 * Register block styles
 */
function wordiva_register_block_styles() {
    if (function_exists('register_block_style')) {
        // Button styles
        register_block_style(
            'core/button',
            array(
                'name'  => 'wordiva-gradient',
                'label' => __('Wordiva Gradient', 'wordiva-blog-theme'),
            )
        );
        
        // Quote styles
        register_block_style(
            'core/quote',
            array(
                'name'  => 'wordiva-highlight',
                'label' => __('Wordiva Highlight', 'wordiva-blog-theme'),
            )
        );
        
        // Group styles
        register_block_style(
            'core/group',
            array(
                'name'  => 'wordiva-card',
                'label' => __('Wordiva Card', 'wordiva-blog-theme'),
            )
        );
    }
}
add_action('init', 'wordiva_register_block_styles');

/**
 * Enhanced search results handling
 * Provides better messaging and suggestions for empty search results
 */
function wordiva_enhance_search_results($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        // Store search query for later use
        set_transient('wordiva_last_search_' . get_current_user_id(), get_search_query(), HOUR_IN_SECONDS);
        
        // If no results, we'll handle this in the template
        if ($query->found_posts == 0) {
            // Log failed searches for analysis (optional)
            $failed_searches = get_option('wordiva_failed_searches', array());
            $search_term = get_search_query();
            
            if (!empty($search_term)) {
                if (isset($failed_searches[$search_term])) {
                    $failed_searches[$search_term]++;
                } else {
                    $failed_searches[$search_term] = 1;
                }
                
                // Keep only the last 100 failed searches
                if (count($failed_searches) > 100) {
                    $failed_searches = array_slice($failed_searches, -100, null, true);
                }
                
                update_option('wordiva_failed_searches', $failed_searches);
            }
        }
    }
}
add_action('pre_get_posts', 'wordiva_enhance_search_results');

/**
 * Improve HTML5 semantic markup
 */
function wordiva_improve_semantic_markup($content) {
    // Add semantic markup to content
    if (is_singular()) {
        // Wrap content in article tag if not already wrapped
        if (strpos($content, '<article') === false) {
            $content = '<div class="entry-content" itemprop="articleBody">' . $content . '</div>';
        }
    }
    
    return $content;
}
add_filter('the_content', 'wordiva_improve_semantic_markup');

/**
 * Internal links must be crawlable: drop nofollow/target from wordiva.ai anchors.
 */
function wordiva_make_internal_links_followable($content) {
    if (strpos($content, 'wordiva.ai') === false) {
        return $content;
    }
    return preg_replace_callback('/<a\s[^>]*href="https?:\/\/(?:www\.)?wordiva\.ai[^"]*"[^>]*>/i', function ($m) {
        $tag = preg_replace('/\s+target="[^"]*"/i', '', $m[0]);
        return preg_replace_callback('/\srel="([^"]*)"/i', function ($rel) {
            $values = array_diff(preg_split('/\s+/', trim($rel[1])), array('nofollow', 'noopener', 'noreferrer', ''));
            return $values ? ' rel="' . esc_attr(implode(' ', $values)) . '"' : '';
        }, $tag);
    }, $content);
}
add_filter('the_content', 'wordiva_make_internal_links_followable', 9);

/**
 * Get configurable URLs from customizer with fallbacks
 */
function wordiva_get_main_site_url() {
    return get_theme_mod('wordiva_main_site_url', 'https://wordiva.ai');
}

function wordiva_get_blog_url() {
    return get_theme_mod('wordiva_blog_url', 'https://wordiva.ai/blog');
}

function wordiva_get_logo_url() {
    $default = get_template_directory_uri() . '/assets/images/icon.png';
    $logo_url = get_theme_mod('wordiva_logo_url', $default);

    if ($logo_url === 'https://wordiva.ai/wordiva-logo-light.png') {
        return $default;
    }

    return $logo_url;
}

function wordiva_get_sign_in_url() {
    $url = get_theme_mod('wordiva_sign_in_url', 'https://wordiva.ai/login');

    if (strpos($url, 'app.wordiva.ai') !== false) {
        $url = str_replace('app.wordiva.ai', 'wordiva.ai', $url);
    }

    return $url;
}

function wordiva_get_cta_url() {
    $url = get_theme_mod('wordiva_cta_url', 'https://wordiva.ai/register');

    if (strpos($url, 'app.wordiva.ai') !== false) {
        $url = str_replace('app.wordiva.ai', 'wordiva.ai', $url);
    }

    return $url;
}

function wordiva_get_main_site_anchor($anchor) {
    return rtrim(wordiva_get_main_site_url(), '/') . '#' . ltrim($anchor, '#');
}

/**
 * Render the Wordiva logo mark with icon and wordmark text.
 */
function wordiva_render_logo($extra_classes = '') {
    $classes = trim('wordiva-logo-link ' . $extra_classes);
    ?>
    <a href="<?php echo esc_url(wordiva_get_main_site_url()); ?>" class="<?php echo esc_attr($classes); ?>" rel="home">
        <span class="wordiva-logo-icon-wrap" aria-hidden="true">
            <img src="<?php echo esc_url(wordiva_get_logo_url()); ?>"
                 alt=""
                 class="wordiva-logo-img">
        </span>
        <span class="wordiva-logo-text">
            wordiva<span class="wordiva-logo-domain">.ai</span>
        </span>
    </a>
    <?php
}

/**
 * Add theme support for additional features
 */
function wordiva_additional_theme_support() {
    // Add support for post formats
    add_theme_support('post-formats', array(
        'aside',
        'gallery',
        'link',
        'image',
        'quote',
        'status',
        'video',
        'audio',
        'chat'
    ));
    
    // Add theme support for starter content
    add_theme_support('starter-content', array(
        'widgets' => array(
            'footer-1' => array(
                'text_business_info',
                'meta',
            ),
            'footer-2' => array(
                'text_about',
            ),
            'footer-3' => array(
                'text_business_info',
            ),
        ),
        'posts' => array(
            'home',
            'about' => array(
                'thumbnail' => '{{image-sandwich}}',
            ),
            'contact' => array(
                'thumbnail' => '{{image-espresso}}',
            ),
            'blog' => array(
                'thumbnail' => '{{image-coffee}}',
            ),
        ),
        'theme_mods' => array(
            'wordiva_header_message' => 'Transform Your Content Strategy with AI',
            'wordiva_header_subtitle' => 'Discover insights, strategies, and innovations in autonomous content creation.',
        ),
        'nav_menus' => array(
            'primary' => array(
                'name' => __('Primary Menu', 'wordiva-blog-theme'),
                'items' => array(
                    'link_home',
                    'page_about',
                    'page_blog',
                    'page_contact',
                ),
            ),
        ),
    ));
}
add_action('after_setup_theme', 'wordiva_additional_theme_support');

/**
 * Highlight search terms in text
 * Wraps search terms with a span for visual highlighting
 * 
 * @param string $text The text to search within
 * @param string $search_term The search term(s) to highlight
 * @return string The text with highlighted search terms
 */
function wordiva_highlight_search_terms($text, $search_term = '') {
    // Get search term if not provided
    if (empty($search_term)) {
        $search_term = get_search_query();
    }
    
    // Return original text if no search term
    if (empty($search_term) || empty($text)) {
        return $text;
    }
    
    // Split search term into individual words
    $words = explode(' ', $search_term);
    
    foreach ($words as $word) {
        $word = trim($word);
        if (strlen($word) > 2) { // Only highlight words longer than 2 characters
            $text = preg_replace(
                '/(' . preg_quote($word, '/') . ')/i',
                '<mark class="search-highlight">$1</mark>',
                $text
            );
        }
    }
    
    return $text;
}

/**
 * Add custom CSS for search highlighting
 */
function wordiva_search_highlight_css() {
    if (is_search()) {
        ?>
        <style>
        .search-highlight {
            background-color: var(--wordiva-golden-yellow);
            color: var(--wordiva-charcoal-dark);
            padding: 0 2px;
            border-radius: 2px;
            font-weight: var(--wordiva-font-weight-medium);
        }
        </style>
        <?php
    }
}
add_action('wp_head', 'wordiva_search_highlight_css');

/**
 * Add theme version to body class for cache busting
 */
function wordiva_add_version_body_class($classes) {
    $classes[] = 'wordiva-v' . str_replace('.', '-', WORDIVA_THEME_VERSION);
    return $classes;
}
add_filter('body_class', 'wordiva_add_version_body_class');

/**
 * Add security headers
 */
function wordiva_add_security_headers() {
    if (!is_admin()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
}
add_action('send_headers', 'wordiva_add_security_headers');

/**
 * Custom login page styling
 */
function wordiva_custom_login_styles() {
    ?>
    <style type="text/css">
        body.login {
            background-color: #ffffff;
        }
        .login h1 a {
            background-image: url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/icon.png'); ?>');
            background-size: contain;
            width: 200px;
            height: 80px;
        }
        .login form {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(43, 43, 43, 0.1);
        }
        .wp-core-ui .button-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
            text-shadow: none;
            box-shadow: none;
        }
        .wp-core-ui .button-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }
    </style>
    <?php
}
add_action('login_head', 'wordiva_custom_login_styles');

/**
 * Change login logo URL
 */
function wordiva_login_logo_url() {
    return home_url();
}
add_filter('login_headerurl', 'wordiva_login_logo_url');

/**
 * Change login logo title
 */
function wordiva_login_logo_title() {
    return get_bloginfo('name');
}
add_filter('login_headertitle', 'wordiva_login_logo_title');