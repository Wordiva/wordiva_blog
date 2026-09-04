<?php
/**
 * SEO Meta Tags and Structured Data
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/seo-helpers.php';

remove_action('wp_head', 'rel_canonical');

/**
 * Get the GA4 measurement ID (Customizer override, theme default fallback).
 */
function wordiva_get_ga4_measurement_id() {
    return sanitize_text_field(get_theme_mod('wordiva_ga4_measurement_id', 'G-QNTZ96XNJE'));
}

/**
 * Output Google Analytics (gtag.js) site-wide.
 */
function wordiva_output_google_analytics() {
    if (is_admin()) {
        return;
    }

    $ga4_id = wordiva_get_ga4_measurement_id();
    if ($ga4_id === '') {
        return;
    }
    ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga4_id); ?>"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?php echo esc_js($ga4_id); ?>');
    </script>
    <?php
}
add_action('wp_head', 'wordiva_output_google_analytics', 0);

/**
 * Register llms.txt rewrite rule.
 */
function wordiva_register_llms_rewrite() {
    add_rewrite_rule('^llms\.txt$', 'index.php?wordiva_llms_txt=1', 'top');
}
add_action('init', 'wordiva_register_llms_rewrite');

function wordiva_llms_query_vars($vars) {
    $vars[] = 'wordiva_llms_txt';
    return $vars;
}
add_filter('query_vars', 'wordiva_llms_query_vars');

function wordiva_serve_llms_txt() {
    if (!get_query_var('wordiva_llms_txt')) {
        return;
    }
    header('Content-Type: text/plain; charset=utf-8');
    echo wordiva_get_llms_txt_content();
    exit;
}
add_action('template_redirect', 'wordiva_serve_llms_txt');

/**
 * Keep thin tag archives (noindex) out of the sitemap.
 */
function wordiva_filter_tag_sitemap_args($args, $taxonomy) {
    if ($taxonomy !== 'post_tag') {
        return $args;
    }
    $tags = get_terms(array('taxonomy' => 'post_tag', 'hide_empty' => false));
    if (is_wp_error($tags)) {
        return $args;
    }
    $exclude = array();
    foreach ($tags as $term) {
        if (wordiva_is_thin_tag($term)) {
            $exclude[] = $term->term_id;
        }
    }
    if (!empty($exclude)) {
        $args['exclude'] = array_merge(isset($args['exclude']) ? (array) $args['exclude'] : array(), $exclude);
    }
    return $args;
}
add_filter('wp_sitemaps_taxonomies_query_args', 'wordiva_filter_tag_sitemap_args', 10, 2);

/**
 * Document title: "<SEO title> | Wordiva".
 */
function wordiva_document_title_separator() {
    return WORDIVA_TITLE_SEPARATOR;
}
add_filter('document_title_separator', 'wordiva_document_title_separator');

function wordiva_document_title_parts($parts) {
    if (is_singular()) {
        $parts['title'] = wordiva_get_seo_title();
    } elseif (is_home() || is_front_page()) {
        $parts['title'] = get_bloginfo('name');
    } elseif (is_tag()) {
        $parts['title'] = sprintf('Posts tagged “%s”', single_tag_title('', false));
    } elseif (is_category()) {
        $parts['title'] = single_cat_title('', false);
    }
    unset($parts['tagline']);
    $parts['site'] = WORDIVA_TITLE_SUFFIX;
    return $parts;
}
add_filter('document_title_parts', 'wordiva_document_title_parts', 20);

/**
 * Same title string as <title>, for og:title / twitter:title.
 */
function wordiva_build_social_title($title) {
    $paged = max(1, (int) get_query_var('paged'));
    if ($paged > 1) {
        $title .= ' ' . WORDIVA_TITLE_SEPARATOR . ' Page ' . $paged;
    }
    return $title . ' ' . WORDIVA_TITLE_SEPARATOR . ' ' . WORDIVA_TITLE_SUFFIX;
}

/**
 * SEO meta tags.
 */
function wordiva_seo_meta_tags() {
    global $post;

    $site_name = get_bloginfo('name');
    $site_description = wordiva_get_default_blog_description();
    $title = '';
    $description = '';
    $canonical_url = wordiva_get_canonical_url();
    $image_url = '';
    $image_alt = '';
    $article_type = 'website';
    $robots = wordiva_get_robots_directive();

    if (is_singular()) {
        $title = wordiva_get_seo_title();
        $description = wordiva_get_seo_description();
        if (has_post_thumbnail()) {
            $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
            $image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
        }
        if (is_single()) {
            $article_type = 'article';
        }
    } elseif (is_home() || is_front_page()) {
        $title = $site_name;
        $description = $site_description;
    } elseif (is_category()) {
        $category = get_queried_object();
        $title = $category->name;
        $description = $category->description
            ? $category->description
            : wordiva_get_category_fallback_description($category->slug);
        if (empty($description)) {
            $description = 'Browse articles in ' . $category->name . '.';
        }
    } elseif (is_tag()) {
        $tag = get_queried_object();
        $title = sprintf('Posts tagged “%s”', $tag->name);
        $description = $tag->description ? $tag->description : 'Articles about ' . $tag->name . ' from the Wordiva blog.';
    } elseif (is_author()) {
        $author = get_queried_object();
        $title = wordiva_get_author_display_name($author->ID);
        $description = $author->description ? $author->description : 'Articles by ' . wordiva_get_author_display_name($author->ID) . '.';
    } elseif (is_search()) {
        $search_query = get_search_query();
        $title = 'Search Results for "' . $search_query . '"';
        $description = 'Search results for "' . $search_query . '" on ' . $site_name . '.';
    } elseif (is_404()) {
        $title = 'Page Not Found';
        $description = 'The page you are looking for could not be found.';
    }
    $title = wordiva_build_social_title($title);
    $rel_links = wordiva_get_pagination_rel_links();

    if (empty($image_url)) {
        $image_url = wordiva_get_default_og_image_url();
    }
    if (empty($image_alt)) {
        $image_alt = is_singular() ? get_the_title() : $site_name;
    }

    $description = wp_strip_all_tags($description);
    $description = preg_replace('/\s+/', ' ', trim(str_replace(array("\r", "\n", "\t"), ' ', $description)));
    $og_image_type = wordiva_get_og_image_type($image_url);
    ?>
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo esc_attr($description); ?>">
    <meta name="robots" content="<?php echo esc_attr($robots); ?>">
    <?php if (wordiva_should_output_canonical() && !empty($canonical_url)) : ?>
    <link rel="canonical" href="<?php echo esc_url($canonical_url); ?>">
    <?php endif; ?>
    <?php foreach ($rel_links as $rel => $href) : ?>
    <link rel="<?php echo esc_attr($rel); ?>" href="<?php echo esc_url($href); ?>">
    <?php endforeach; ?>

    <meta property="og:locale" content="<?php echo esc_attr(get_locale()); ?>">
    <meta property="og:type" content="<?php echo esc_attr($article_type); ?>">
    <meta property="og:title" content="<?php echo esc_attr($title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($description); ?>">
    <?php if (!empty($canonical_url)) : ?>
    <meta property="og:url" content="<?php echo esc_url($canonical_url); ?>">
    <?php endif; ?>
    <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>">
    <meta property="og:image" content="<?php echo esc_url($image_url); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="<?php echo esc_attr($og_image_type); ?>">
    <meta property="og:image:alt" content="<?php echo esc_attr($image_alt); ?>">

    <?php if (is_single() && get_post_type() === 'post') :
        $author_name = wordiva_get_author_display_name();
        ?>
        <meta property="article:published_time" content="<?php echo esc_attr(get_the_date('c')); ?>">
        <meta property="article:modified_time" content="<?php echo esc_attr(get_the_modified_date('c')); ?>">
        <?php if (!empty($author_name)) : ?>
        <meta property="article:author" content="<?php echo esc_attr($author_name); ?>">
        <?php endif; ?>
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

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@wordivaai">
    <meta name="twitter:creator" content="@wordivaai">
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
    <meta name="twitter:image" content="<?php echo esc_url($image_url); ?>">
    <meta name="twitter:image:alt" content="<?php echo esc_attr($image_alt); ?>">

    <meta name="author" content="<?php echo is_single() ? esc_attr(wordiva_get_author_display_name()) : esc_attr($site_name); ?>">
    <?php
}
add_action('wp_head', 'wordiva_seo_meta_tags', 1);

/**
 * JSON-LD structured data.
 */
function wordiva_structured_data() {
    wordiva_output_json_ld(wordiva_get_organization_schema());
    wordiva_output_json_ld(wordiva_get_website_schema());

    if (is_single() && get_post_type() === 'post') {
        $post_id = get_the_ID();
        wordiva_output_json_ld(wordiva_get_blog_posting_schema($post_id));
        wordiva_output_json_ld(array(
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            '@id' => get_permalink($post_id),
            'url' => get_permalink($post_id),
            'name' => get_the_title($post_id),
            'mainEntity' => array('@id' => get_permalink($post_id) . '#blogposting'),
        ));

        if (get_post_meta($post_id, '_wordiva_enable_faq_schema', true)) {
            wordiva_output_json_ld(wordiva_faq_schema_from_content(get_post_field('post_content', $post_id)));
        }
        wordiva_output_json_ld(wordiva_get_howto_schema($post_id));
    }

    if (is_singular() && !is_front_page()) {
        $items = wordiva_get_breadcrumb_items();
        if (!empty($items)) {
            wordiva_output_json_ld(array(
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => $items,
            ));
        }
    }

    if (is_home()) {
        $posts = wordiva_get_latest_posts_for_schema(10);
        $blog_posts = array();
        foreach ($posts as $p) {
            $entry = array(
                '@type' => 'BlogPosting',
                'headline' => get_the_title($p->ID),
                'url' => get_permalink($p->ID),
                'datePublished' => get_the_date('c', $p->ID),
            );
            if (has_post_thumbnail($p->ID)) {
                $entry['image'] = get_the_post_thumbnail_url($p->ID, 'large');
            }
            $author = wordiva_get_author_display_name($p->post_author);
            if (!empty($author)) {
                $entry['author'] = array('@type' => 'Person', 'name' => $author);
            }
            $blog_posts[] = $entry;
        }
        wordiva_output_json_ld(wordiva_get_blog_schema(array('blogPost' => $blog_posts)));

        $list_items = array();
        $pos = 1;
        foreach ($posts as $p) {
            $list_items[] = array(
                '@type' => 'ListItem',
                'position' => $pos++,
                'url' => get_permalink($p->ID),
                'name' => get_the_title($p->ID),
            );
        }
        wordiva_output_json_ld(array(
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'itemListOrder' => 'https://schema.org/ItemListOrderDescending',
            'numberOfItems' => count($list_items),
            'itemListElement' => $list_items,
        ));
    }

    if (is_category()) {
        wordiva_output_json_ld(array(
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => single_cat_title('', false),
            'description' => category_description() ?: wordiva_get_category_fallback_description(get_queried_object()->slug),
            'url' => get_category_link(get_queried_object_id()),
            'isPartOf' => array('@id' => wordiva_get_blog_index_url() . '#blog'),
        ));
    }

    if (is_author()) {
        $author_id = get_queried_object_id();
        wordiva_output_json_ld(array(
            '@context' => 'https://schema.org',
            '@type' => 'ProfilePage',
            'name' => wordiva_get_author_display_name($author_id),
            'url' => get_author_posts_url($author_id),
            'mainEntity' => wordiva_get_person_schema($author_id),
        ));
    }
}
add_action('wp_head', 'wordiva_structured_data', 2);

/**
 * Favicon matching https://wordiva.ai (falls back when no Customizer site icon is set).
 */
function wordiva_output_favicon() {
    if (function_exists('has_site_icon') && has_site_icon()) {
        return;
    }

    $icon_url = get_template_directory_uri() . '/assets/images/icon.png';
    ?>
    <link rel="icon" href="<?php echo esc_url($icon_url); ?>" sizes="632x545" type="image/png">
    <link rel="apple-touch-icon" href="<?php echo esc_url($icon_url); ?>">
    <?php
}
add_action('wp_head', 'wordiva_output_favicon', 1);

/**
 * Additional SEO head tags.
 */
function wordiva_additional_seo_tags() {
    ?>
    <link rel="dns-prefetch" href="//www.googletagmanager.com">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <meta name="theme-color" content="#020617">
    <link rel="manifest" href="<?php echo esc_url(get_template_directory_uri() . '/manifest.json'); ?>">
    <link rel="alternate" type="application/rss+xml" title="<?php echo esc_attr(get_bloginfo('name')); ?> Feed" href="<?php echo esc_url(get_feed_link()); ?>">
    <?php
}
add_action('wp_head', 'wordiva_additional_seo_tags', 3);

/**
 * robots.txt.
 */
function wordiva_robots_txt($output, $public) {
    if (!$public) {
        return $output;
    }

    $sitemap = home_url('/wp-sitemap.xml');
    $lines = array(
        'Sitemap: ' . $sitemap,
        '',
        'User-agent: *',
        'Disallow: /wp-admin/',
        'Allow: /wp-admin/admin-ajax.php',
        'Disallow: /wp-includes/',
        'Disallow: /wp-content/plugins/',
        'Disallow: /wp-content/themes/',
        'Disallow: /wp-json/',
        'Disallow: /xmlrpc.php',
        'Allow: /wp-content/uploads/',
        'Allow: /wp-content/themes/*/assets/',
        '',
    );

    $ai_bots = array('GPTBot', 'ChatGPT-User', 'ClaudeBot', 'PerplexityBot', 'OAI-SearchBot', 'Google-Extended', 'CCBot');
    foreach ($ai_bots as $bot) {
        $lines[] = 'User-agent: ' . $bot;
        $lines[] = 'Allow: /';
        $lines[] = '';
    }

    return implode("\n", $lines);
}
add_filter('robots_txt', 'wordiva_robots_txt', 10, 2);

/**
 * Flush rewrite rules when theme updates llms.txt route.
 */
function wordiva_maybe_flush_rewrites() {
    if (get_option('wordiva_llms_rewrite_flushed') !== WORDIVA_THEME_VERSION) {
        wordiva_register_llms_rewrite();
        flush_rewrite_rules(false);
        update_option('wordiva_llms_rewrite_flushed', WORDIVA_THEME_VERSION);
    }
}
add_action('init', 'wordiva_maybe_flush_rewrites', 20);
