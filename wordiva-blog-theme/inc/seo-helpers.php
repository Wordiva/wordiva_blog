<?php
/**
 * SEO helper functions — schema, meta, breadcrumbs, llms.txt, FAQ
 *
 * @package Wordiva_Theme
 * @since 1.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

define('WORDIVA_ORG_ID', 'https://wordiva.ai/#organization');

/**
 * Default blog SEO description (blog-updates.md).
 */
function wordiva_get_default_blog_description() {
    $custom = get_theme_mod('wordiva_blog_seo_description', '');
    if (!empty($custom)) {
        return $custom;
    }
    $tagline = get_bloginfo('description');
    if (!empty($tagline)) {
        return $tagline;
    }
    return 'Insights on agentic AI content marketing, WordPress blog automation, SEO, and GEO from the Wordiva team.';
}

/**
 * Organization description for schema.
 */
function wordiva_get_organization_description() {
    return 'Agentic AI content marketing engine for automated blogging and WordPress publishing.';
}

/**
 * Canonical sameAs social URLs.
 */
function wordiva_get_organization_same_as() {
    return array(
        get_theme_mod('wordiva_linkedin_url', 'https://www.linkedin.com/company/wordiva-ai/'),
        get_theme_mod('wordiva_facebook_url', 'https://www.facebook.com/wordivaai/'),
        get_theme_mod('wordiva_instagram_url', 'https://www.instagram.com/wordivaai/'),
        get_theme_mod('wordiva_youtube_url', 'https://www.youtube.com/@wordivaai'),
        get_theme_mod('wordiva_twitter_url', 'https://twitter.com/wordivaai'),
    );
}

/**
 * Blog index URL without duplicate path segments.
 */
function wordiva_get_blog_index_url() {
    $blog_page_id = get_option('page_for_posts');
    if ($blog_page_id) {
        return trailingslashit(get_permalink($blog_page_id));
    }
    return trailingslashit(wordiva_get_blog_url());
}

/**
 * Robots meta directive for current request.
 */
function wordiva_get_robots_directive() {
    if (is_404()) {
        return 'noindex, nofollow';
    }
    if (is_search()) {
        return 'noindex, follow';
    }
    if (is_date()) {
        return 'noindex, follow';
    }
    if (is_author()) {
        $author = get_queried_object();
        if ($author && empty($author->description)) {
            return 'noindex, follow';
        }
    }
    return 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
}

/**
 * Whether canonical tag should render.
 */
function wordiva_should_output_canonical() {
    return !is_404();
}

/**
 * OG image MIME type from URL.
 */
function wordiva_get_og_image_type($url) {
    $type = wp_check_filetype($url);
    if (!empty($type['type'])) {
        return $type['type'];
    }
    return 'image/jpeg';
}

/**
 * Default OG image URL.
 */
function wordiva_get_default_og_image_url() {
    $theme_image = get_template_directory_uri() . '/assets/images/wordiva-og-default.jpg';
    if (file_exists(get_template_directory() . '/assets/images/wordiva-og-default.jpg')) {
        return $theme_image;
    }
    return rtrim(wordiva_get_main_site_url(), '/') . '/wordiva_ai.png';
}

/**
 * Organization JSON-LD.
 */
function wordiva_get_organization_schema() {
    $main_url = rtrim(wordiva_get_main_site_url(), '/');
    return array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => WORDIVA_ORG_ID,
        'name' => 'Wordiva.ai',
        'url' => $main_url,
        'logo' => array(
            '@type' => 'ImageObject',
            'url' => $main_url . '/wordiva_ai.png',
            'width' => 632,
            'height' => 545,
        ),
        'description' => wordiva_get_organization_description(),
        'sameAs' => wordiva_get_organization_same_as(),
        'knowsAbout' => array(
            'AI content marketing',
            'content automation',
            'SEO',
            'generative engine optimization',
        ),
    );
}

/**
 * WebSite JSON-LD.
 */
function wordiva_get_website_schema() {
    $blog_url = wordiva_get_blog_index_url();
    return array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        '@id' => $blog_url . '#website',
        'name' => get_bloginfo('name'),
        'url' => $blog_url,
        'description' => wordiva_get_default_blog_description(),
        'publisher' => array('@id' => WORDIVA_ORG_ID),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => array(
                '@type' => 'EntryPoint',
                'urlTemplate' => $blog_url . '?s={search_term_string}',
            ),
            'query-input' => 'required name=search_term_string',
        ),
    );
}

/**
 * Author display name with optional fallback.
 */
function wordiva_get_author_display_name($user_id = null) {
    if (!$user_id) {
        $user_id = get_the_author_meta('ID');
    }
    $name = get_the_author_meta('display_name', $user_id);
    if (!empty($name)) {
        return $name;
    }
    if (get_user_meta($user_id, 'wordiva_allow_team_fallback', true)) {
        return 'Wordiva Team';
    }
    return '';
}

/**
 * Person schema for an author.
 */
function wordiva_get_person_schema($user_id) {
    $author_url = get_author_posts_url($user_id);
    $name = wordiva_get_author_display_name($user_id);
    if (empty($name)) {
        return null;
    }
    $schema = array(
        '@type' => 'Person',
        '@id' => trailingslashit($author_url) . '#person',
        'name' => $name,
        'url' => $author_url,
        'worksFor' => array('@id' => WORDIVA_ORG_ID),
    );
    $job = get_user_meta($user_id, 'wordiva_job_title', true);
    if (!empty($job)) {
        $schema['jobTitle'] = $job;
    }
    $same_as = array();
    $linkedin = get_user_meta($user_id, 'wordiva_linkedin_url', true);
    $twitter = get_user_meta($user_id, 'wordiva_twitter_url', true);
    if (!empty($linkedin)) {
        $same_as[] = $linkedin;
    }
    if (!empty($twitter)) {
        $same_as[] = $twitter;
    }
    if (!empty($same_as)) {
        $schema['sameAs'] = $same_as;
    }
    return $schema;
}

/**
 * BlogPosting schema for a post.
 */
function wordiva_get_blog_posting_schema($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $post = get_post($post_id);
    if (!$post) {
        return null;
    }
    $permalink = get_permalink($post_id);
    $excerpt = has_excerpt($post_id)
        ? wp_trim_words(get_the_excerpt($post_id), 25, '...')
        : wp_trim_words(wp_strip_all_tags($post->post_content), 25, '...');
    $image_url = has_post_thumbnail($post_id)
        ? get_the_post_thumbnail_url($post_id, 'large')
        : wordiva_get_default_og_image_url();
    $author_id = (int) $post->post_author;
    $person = wordiva_get_person_schema($author_id);
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        '@id' => $permalink . '#blogposting',
        'headline' => get_the_title($post_id),
        'description' => $excerpt,
        'image' => array(
            '@type' => 'ImageObject',
            'url' => $image_url,
            'width' => 1200,
            'height' => 630,
        ),
        'publisher' => array('@id' => WORDIVA_ORG_ID),
        'datePublished' => get_the_date('c', $post_id),
        'dateModified' => get_the_modified_date('c', $post_id),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => $permalink,
        ),
        'url' => $permalink,
        'wordCount' => str_word_count(wp_strip_all_tags($post->post_content)),
        'articleSection' => wp_strip_all_tags(get_the_category_list(', ', '', $post_id)),
        'inLanguage' => get_locale(),
    );
    if ($person) {
        $schema['author'] = $person;
    }
    $schema['speakable'] = array(
        '@type' => 'SpeakableSpecification',
        'cssSelector' => array('.wordiva-speakable'),
    );
    $tags = get_the_tags($post_id);
    if ($tags) {
        $keywords = array();
        foreach ($tags as $tag) {
            $keywords[] = $tag->name;
        }
        $schema['keywords'] = implode(', ', $keywords);
    }
    return $schema;
}

/**
 * Blog schema for index/archives.
 */
function wordiva_get_blog_schema($extra = array()) {
    $blog_url = is_home() ? wordiva_get_blog_index_url() : get_pagenum_link();
    if (is_category()) {
        $blog_url = get_category_link(get_queried_object_id());
    } elseif (is_tag()) {
        $blog_url = get_tag_link(get_queried_object_id());
    }
    $name = get_bloginfo('name') . ' Blog';
    if (is_category()) {
        $name = single_cat_title('', false);
    } elseif (is_tag()) {
        $name = single_tag_title('', false);
    }
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Blog',
        '@id' => wordiva_get_blog_index_url() . '#blog',
        'name' => $name,
        'description' => wordiva_get_default_blog_description(),
        'url' => $blog_url,
        'isPartOf' => array('@id' => WORDIVA_ORG_ID),
        'publisher' => array('@id' => WORDIVA_ORG_ID),
        'inLanguage' => get_locale(),
    );
    return array_merge($schema, $extra);
}

/**
 * Latest posts for blogPost array / ItemList / llms.txt.
 */
function wordiva_get_latest_posts_for_schema($count = 10) {
    return get_posts(array(
        'numberposts' => $count,
        'post_status' => 'publish',
        'post_type' => 'post',
        'orderby' => 'date',
        'order' => 'DESC',
    ));
}

/**
 * Breadcrumb trail items.
 */
function wordiva_get_breadcrumb_items() {
    $items = array();
    $position = 1;
    $blog_url = wordiva_get_blog_index_url();

    $items[] = array(
        '@type' => 'ListItem',
        'position' => $position++,
        'name' => 'Home',
        'item' => rtrim(wordiva_get_main_site_url(), '/'),
    );

    $items[] = array(
        '@type' => 'ListItem',
        'position' => $position++,
        'name' => 'Blog',
        'item' => $blog_url,
    );

    if (is_single() && get_post_type() === 'post') {
        $categories = get_the_category();
        if (!empty($categories)) {
            $category = $categories[0];
            $items[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $category->name,
                'item' => get_category_link($category->term_id),
            );
        }
        $items[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title(),
            'item' => get_permalink(),
        );
    } elseif (is_category()) {
        $items[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => single_cat_title('', false),
            'item' => get_category_link(get_queried_object_id()),
        );
    } elseif (is_author()) {
        $items[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => wordiva_get_author_display_name(get_queried_object_id()),
            'item' => get_author_posts_url(get_queried_object_id()),
        );
    } elseif (is_page()) {
        $items[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title(),
            'item' => get_permalink(),
        );
    }

    return $items;
}

/**
 * Category fallback intro copy.
 */
function wordiva_get_category_fallback_description($slug) {
    $copy = array(
        'agentic-ai' => 'Explore how agentic AI transforms content marketing — autonomous workflows, intelligent publishing, and always-on growth engines for modern brands.',
        'ai-content-marketing' => 'Learn strategies for AI-powered content marketing: planning, creation, SEO optimization, and scaling output without sacrificing quality or brand voice.',
        'content-marketing' => 'Practical guides on content marketing operations — capacity, automation, ROI, and building systems that publish consistently across channels.',
        'wordiva-story' => 'The Wordiva story: product updates, founder insights, and how we are building the 24/7 agentic content marketing engine for creators and teams.',
    );
    return isset($copy[$slug]) ? $copy[$slug] : '';
}

/**
 * Parse FAQ pairs from post content.
 */
function wordiva_faq_schema_from_content($content) {
    $pairs = array();
    if (preg_match_all('/<!-- wp:heading.*?-->.*?<h[2-4][^>]*>(.*?)<\/h[2-4]>.*?<!-- \/wp:heading -->.*?<!-- wp:paragraph.*?-->.*?<p>(.*?)<\/p>/s', $content, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $question = wp_strip_all_tags($match[1]);
            $answer = wp_strip_all_tags($match[2]);
            if (strlen($question) > 5 && strlen($answer) > 10) {
                if (stripos($question, '?') !== false || stripos($question, 'faq') !== false) {
                    $pairs[] = array('question' => $question, 'answer' => $answer);
                }
            }
        }
    }
    if (count($pairs) < 2) {
        return null;
    }
    $entities = array();
    foreach ($pairs as $pair) {
        $entities[] = array(
            '@type' => 'Question',
            'name' => $pair['question'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $pair['answer'],
            ),
        );
    }
    return array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $entities,
    );
}

/**
 * HowTo schema from post content.
 */
function wordiva_get_howto_schema($post_id) {
    if (get_post_meta($post_id, '_wordiva_schema_type', true) !== 'howto') {
        return null;
    }
    $content = get_post_field('post_content', $post_id);
    if (strpos($content, 'ordered":true') === false) {
        return null;
    }
    return array(
        '@context' => 'https://schema.org',
        '@type' => 'HowTo',
        'name' => get_the_title($post_id),
        'description' => wp_trim_words(wp_strip_all_tags($content), 30, '...'),
    );
}

/**
 * llms.txt markdown body.
 */
function wordiva_get_llms_txt_content() {
    $main = rtrim(wordiva_get_main_site_url(), '/');
    $blog = rtrim(wordiva_get_blog_url(), '/');
    $lines = array(
        '# Wordiva.ai',
        '',
        '> Agentic AI content marketing engine for automated blogging, SEO, and WordPress publishing.',
        '',
        '## Product',
        '',
        '- [Home](' . $main . '/): Agentic AI content marketing platform',
        '- [Register](' . $main . '/register): Create a Wordiva account',
        '- [Login](' . $main . '/login): Sign in to your workspace',
        '- [Compare](' . $main . '/compare): Compare AI writing tools',
        '- [WordPress Integration](' . $main . '/integrations/wordpress): Publish to WordPress',
        '- [GEO Guide](' . $main . '/learn/generative-engine-optimization): Generative engine optimization',
        '',
        '## Blog',
        '',
        '- [Wordiva Blog](' . $blog . '/): ' . wordiva_get_default_blog_description(),
        '',
    );
    foreach (wordiva_get_latest_posts_for_schema(10) as $post) {
        $excerpt = has_excerpt($post->ID)
            ? wp_strip_all_tags(get_the_excerpt($post->ID))
            : wp_trim_words(wp_strip_all_tags($post->post_content), 20, '...');
        $lines[] = '- [' . get_the_title($post->ID) . '](' . get_permalink($post->ID) . '): ' . $excerpt;
    }
    return implode("\n", $lines) . "\n";
}

/**
 * Output JSON-LD script tag.
 */
function wordiva_output_json_ld($data) {
    if (empty($data)) {
        return;
    }
    echo '<script type="application/ld+json">' . wp_json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}

/**
 * Blog CTA URL with UTM params for organic blog traffic.
 */
function wordiva_get_blog_cta_url() {
    $base = wordiva_get_cta_url();
    return add_query_arg(
        array(
            'utm_source' => 'blog',
            'utm_medium' => 'organic',
        ),
        $base
    );
}
