<?php
/**
 * Hardcoded navigation data mirroring the wordiva.ai marketing site.
 *
 * Two-layer mega menu (Product / Solutions / Resources / Compare / Pricing)
 * and the 6-column footer link map. All URLs are built from the Customizer
 * base URL so a staging site can repoint everything at once.
 *
 * @package Wordiva_Theme
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Build an absolute URL on the main marketing site.
 */
function wordiva_main_url($path = '') {
    $base = rtrim(wordiva_get_main_site_url(), '/');
    if ($path === '' || $path === '/') {
        return $base;
    }
    return $base . '/' . ltrim($path, '/');
}

/**
 * Two-layer navigation model. Each top-level entry either has `href`
 * (plain link) or `groups` (mega panel of grouped links).
 */
function wordiva_get_nav_menu() {
    return array(
        array(
            'label'  => __('Product', 'wordiva-blog-theme'),
            'groups' => array(
                array(
                    'label' => __('Features', 'wordiva-blog-theme'),
                    'href'  => wordiva_main_url('features'),
                    'items' => array(
                        array('href' => wordiva_main_url('features/autopilot'), 'label' => __('Autopilot', 'wordiva-blog-theme'), 'description' => __('Hands-off publishing on your cadence', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('features/geo-seo'), 'label' => __('SEO & GEO', 'wordiva-blog-theme'), 'description' => __('Rank on Google, get cited by AI', 'wordiva-blog-theme')),
                    ),
                ),
                array(
                    'label' => __('Integrations', 'wordiva-blog-theme'),
                    'href'  => wordiva_main_url('integrations'),
                    'items' => array(
                        array('href' => wordiva_main_url('integrations/wordpress'), 'label' => __('WordPress', 'wordiva-blog-theme'), 'description' => __('Official plugin or REST connection', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('integrations/mdxpress'), 'label' => __('MDXpress', 'wordiva-blog-theme'), 'description' => __('Markdown blogs via device code', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('integrations/vercel'), 'label' => __('Vercel', 'wordiva-blog-theme'), 'description' => __('Git-commit publishing on Vercel', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('wordpress-plugin'), 'label' => __('WordPress plugin', 'wordiva-blog-theme'), 'description' => __('Free on WordPress.org — device-code connect', 'wordiva-blog-theme')),
                    ),
                ),
            ),
        ),
        array(
            'label'  => __('Solutions', 'wordiva-blog-theme'),
            'groups' => array(
                array(
                    'label' => __('Use cases', 'wordiva-blog-theme'),
                    'href'  => wordiva_main_url('for'),
                    'items' => array(
                        array('href' => wordiva_main_url('for/saas-founders'), 'label' => __('SaaS founders', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('for/agencies'), 'label' => __('Agencies', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('for/indie-hackers'), 'label' => __('Indie hackers', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('for/ecommerce'), 'label' => __('E-commerce', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('for/local-businesses'), 'label' => __('Local businesses', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('for/content-marketers'), 'label' => __('Content marketers', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('for/bloggers'), 'label' => __('Bloggers', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('for/startups'), 'label' => __('Startups', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('for/consultants'), 'label' => __('Consultants', 'wordiva-blog-theme')),
                    ),
                ),
                array(
                    'label' => __('Benefits', 'wordiva-blog-theme'),
                    'href'  => wordiva_main_url('benefits'),
                    'items' => array(
                        array('href' => wordiva_main_url('benefits/save-time'), 'label' => __('Save time', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('benefits/scale-content'), 'label' => __('Scale content', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('benefits/grow-organic-traffic'), 'label' => __('Grow organic traffic', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('benefits/reduce-content-costs'), 'label' => __('Reduce content costs', 'wordiva-blog-theme')),
                    ),
                ),
            ),
        ),
        array(
            'label'  => __('Resources', 'wordiva-blog-theme'),
            'groups' => array(
                array(
                    'label' => __('Guides', 'wordiva-blog-theme'),
                    'href'  => wordiva_main_url('learn'),
                    'items' => array(
                        array('href' => wordiva_main_url('learn/generative-engine-optimization'), 'label' => __('GEO guide', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('learn/ai-content-marketing-strategy'), 'label' => __('AI content strategy', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('learn/answer-engine-optimization'), 'label' => __('AEO guide', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('learn/autoblogging-guide'), 'label' => __('Autoblogging guide', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('learn/wordpress-seo-automation'), 'label' => __('WordPress SEO automation', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('learn/content-calendar-automation'), 'label' => __('Calendar automation', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('learn/scale-content-without-hiring'), 'label' => __('Scale without hiring', 'wordiva-blog-theme')),
                    ),
                ),
                // No "More" group: Blog is a top-level nav entry (rendered by
                // header.php with its own active state) and WordPress plugin
                // already lives under Product > Integrations.
            ),
        ),
        array(
            'label'  => __('Compare', 'wordiva-blog-theme'),
            'groups' => array(
                array(
                    'label' => __('Comparisons', 'wordiva-blog-theme'),
                    'href'  => wordiva_main_url('compare'),
                    'items' => array(
                        array('href' => wordiva_main_url('compare/wordiva-vs-blogseo'), 'label' => __('Wordiva vs BlogSEO', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('compare/wordiva-vs-rightblogger'), 'label' => __('Wordiva vs RightBlogger', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('compare/wordiva-vs-frase'), 'label' => __('Wordiva vs Frase', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('compare/wordiva-vs-jasper'), 'label' => __('Wordiva vs Jasper', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('compare/wordiva-vs-copy-ai'), 'label' => __('Wordiva vs Copy.ai', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('compare/wordiva-vs-surfer-seo'), 'label' => __('Wordiva vs Surfer SEO', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('compare/wordiva-vs-writesonic'), 'label' => __('Wordiva vs Writesonic', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('compare/wordiva-vs-koala-ai'), 'label' => __('Wordiva vs Koala AI', 'wordiva-blog-theme')),
                        array('href' => wordiva_main_url('compare/wordiva-vs-autoblogging-ai'), 'label' => __('Wordiva vs Autoblogging.ai', 'wordiva-blog-theme')),
                    ),
                ),
            ),
        ),
        array(
            'label' => __('Pricing', 'wordiva-blog-theme'),
            'href'  => wordiva_main_url('#pricing'),
        ),
    );
}

/**
 * Footer link columns mirroring the marketing site's MarketingFooter.
 */
function wordiva_get_footer_links() {
    return array(
        __('Product', 'wordiva-blog-theme') => array(
            array('href' => wordiva_main_url('features'), 'label' => __('All features', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('features/autopilot'), 'label' => __('Autopilot', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('features/geo-seo'), 'label' => __('SEO & GEO', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('integrations'), 'label' => __('Integrations', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('integrations/wordpress'), 'label' => __('WordPress', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('integrations/mdxpress'), 'label' => __('MDXpress', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('integrations/vercel'), 'label' => __('Vercel', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('wordpress-plugin'), 'label' => __('WordPress plugin', 'wordiva-blog-theme')),
        ),
        __('Compare', 'wordiva-blog-theme') => array(
            array('href' => wordiva_main_url('compare'), 'label' => __('All comparisons', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('compare/wordiva-vs-blogseo'), 'label' => __('vs BlogSEO', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('compare/wordiva-vs-rightblogger'), 'label' => __('vs RightBlogger', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('compare/wordiva-vs-frase'), 'label' => __('vs Frase', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('compare/wordiva-vs-jasper'), 'label' => __('vs Jasper', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('compare/wordiva-vs-copy-ai'), 'label' => __('vs Copy.ai', 'wordiva-blog-theme')),
        ),
        __('Use cases', 'wordiva-blog-theme') => array(
            array('href' => wordiva_main_url('for'), 'label' => __('All use cases', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('for/saas-founders'), 'label' => __('SaaS founders', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('for/agencies'), 'label' => __('Agencies', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('for/indie-hackers'), 'label' => __('Indie hackers', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('for/ecommerce'), 'label' => __('E-commerce', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('for/startups'), 'label' => __('Startups', 'wordiva-blog-theme')),
        ),
        __('Benefits', 'wordiva-blog-theme') => array(
            array('href' => wordiva_main_url('benefits'), 'label' => __('All benefits', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('benefits/save-time'), 'label' => __('Save time', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('benefits/scale-content'), 'label' => __('Scale content', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('benefits/grow-organic-traffic'), 'label' => __('Grow organic traffic', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('benefits/reduce-content-costs'), 'label' => __('Reduce content costs', 'wordiva-blog-theme')),
        ),
        __('Resources', 'wordiva-blog-theme') => array(
            array('href' => wordiva_get_blog_url(), 'label' => __('Blog', 'wordiva-blog-theme'), 'active_blog' => true),
            array('href' => trailingslashit(wordiva_get_blog_url()) . 'feed/', 'label' => __('RSS', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('learn'), 'label' => __('All guides', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('learn/generative-engine-optimization'), 'label' => __('GEO guide', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('learn/autoblogging-guide'), 'label' => __('Autoblogging guide', 'wordiva-blog-theme')),
            array('href' => wordiva_main_url('learn/wordpress-seo-automation'), 'label' => __('WordPress SEO automation', 'wordiva-blog-theme')),
        ),
    );
}

/**
 * Legal / bottom bar links.
 */
function wordiva_get_footer_legal_links() {
    return array(
        array('href' => wordiva_main_url('#features'), 'label' => __('Features', 'wordiva-blog-theme')),
        array('href' => wordiva_main_url('#pricing'), 'label' => __('Pricing', 'wordiva-blog-theme')),
        array('href' => wordiva_get_cta_url(), 'label' => __('Get Started', 'wordiva-blog-theme')),
        array('href' => wordiva_main_url('privacy'), 'label' => __('Privacy', 'wordiva-blog-theme')),
        array('href' => wordiva_main_url('terms'), 'label' => __('Terms', 'wordiva-blog-theme')),
        array('href' => wordiva_main_url('cookies'), 'label' => __('Cookies', 'wordiva-blog-theme')),
        array('href' => wordiva_main_url('acceptable-use'), 'label' => __('Acceptable Use', 'wordiva-blog-theme')),
    );
}
