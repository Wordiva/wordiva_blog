<?php
/**
 * Template Name: HTML Sitemap
 *
 * @package Wordiva_Theme
 */

get_header();
?>

<main id="main" class="site-main page-sitemap-main" role="main">
    <div class="container">
        <?php get_template_part('template-parts/breadcrumbs'); ?>
        <h1 class="page-title"><?php esc_html_e('Sitemap', 'wordiva-blog-theme'); ?></h1>

        <section class="sitemap-section">
            <h2><?php esc_html_e('Posts', 'wordiva-blog-theme'); ?></h2>
            <ul>
                <?php
                $posts = get_posts(array('numberposts' => -1, 'post_type' => 'post', 'post_status' => 'publish'));
                foreach ($posts as $p) {
                    echo '<li><a href="' . esc_url(get_permalink($p->ID)) . '">' . esc_html(get_the_title($p->ID)) . '</a></li>';
                }
                ?>
            </ul>
        </section>

        <section class="sitemap-section">
            <h2><?php esc_html_e('Categories', 'wordiva-blog-theme'); ?></h2>
            <ul>
                <?php
                foreach (get_categories(array('hide_empty' => false)) as $cat) {
                    echo '<li><a href="' . esc_url(get_category_link($cat->term_id)) . '">' . esc_html($cat->name) . '</a></li>';
                }
                ?>
            </ul>
        </section>

        <section class="sitemap-section">
            <h2><?php esc_html_e('Authors', 'wordiva-blog-theme'); ?></h2>
            <ul>
                <?php
                foreach (get_users(array('who' => 'authors', 'has_published_posts' => array('post'))) as $user) {
                    echo '<li><a href="' . esc_url(get_author_posts_url($user->ID)) . '">' . esc_html(wordiva_get_author_display_name($user->ID)) . '</a></li>';
                }
                ?>
            </ul>
        </section>
    </div>
</main>

<?php get_footer(); ?>
