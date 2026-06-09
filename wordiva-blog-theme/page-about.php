<?php
/**
 * Template Name: About Page
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main page-about" role="main">
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">About <span>Wordiva</span></h1>
            <p class="hero-subtitle">
                <?php esc_html_e('We are revolutionizing content marketing with autonomous AI agents. Our mission is to help brands scale their voice without losing their soul.', 'wordiva-blog-theme'); ?>
            </p>
        </div>
    </section>

    <div class="container">
        <!-- Company Story -->
        <section class="main-content">
            <div class="about-content-wrapper" style="max-width: 800px; margin: 0 auto;">
                <h2 class="section-title text-center"><?php esc_html_e('Our Story', 'wordiva-blog-theme'); ?></h2>
                <div class="entry-content">
                    <p>
                        <?php esc_html_e('Founded in 2024, Wordiva started with a simple question: "What if content creation could be both scalable and deeply personal?" We observed that brands were struggling to keep up with the demand for high-quality content while maintaining consistency and voice.', 'wordiva-blog-theme'); ?>
                    </p>
                    <p>
                        <?php esc_html_e('Wordiva is not just another AI writing tool. It is a comprehensive system of intelligent agents that understand your brand strategy, analyze market trends, and generate content that resonates with your audience.', 'wordiva-blog-theme'); ?>
                    </p>
                    <blockquote>
                        <?php esc_html_e('Start small, think big, and let AI handle the heavy lifting of content scale.', 'wordiva-blog-theme'); ?>
                    </blockquote>
                    <p>
                        <?php esc_html_e('Today, we help hundreds of forward-thinking companies transform their marketing operations, allowing human creativity to focus on strategy while our agents handle the execution.', 'wordiva-blog-theme'); ?>
                    </p>
                </div>
            </div>
        </section>

        <!-- Values Section -->
        <section class="values-section" style="padding: 60px 0;">
            <h2 class="section-title" style="text-align: center; margin-bottom: 60px;"><?php esc_html_e('Our Values', 'wordiva-blog-theme'); ?></h2>
            <div class="slack-card-grid">
                <div class="blog-card" style="padding: 30px;">
                    <h3 class="card-title"><?php esc_html_e('Innovation', 'wordiva-blog-theme'); ?></h3>
                    <p class="card-excerpt"><?php esc_html_e('We constantly push the boundaries of what is possible with Generative AI.', 'wordiva-blog-theme'); ?></p>
                </div>
                <div class="blog-card" style="padding: 30px;">
                    <h3 class="card-title"><?php esc_html_e('Integrity', 'wordiva-blog-theme'); ?></h3>
                    <p class="card-excerpt"><?php esc_html_e('We believe in transparent, ethical AI that augments human capability.', 'wordiva-blog-theme'); ?></p>
                </div>
                <div class="blog-card" style="padding: 30px;">
                    <h3 class="card-title"><?php esc_html_e('Quality', 'wordiva-blog-theme'); ?></h3>
                    <p class="card-excerpt"><?php esc_html_e('Good enough is not enough. We strive for excellence in every word generated.', 'wordiva-blog-theme'); ?></p>
                </div>
            </div>
        </section>
    </div>

</main>

<?php get_footer(); ?>
