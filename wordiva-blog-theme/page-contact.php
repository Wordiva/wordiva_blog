<?php
/**
 * Template Name: Contact Page
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main page-contact" role="main">

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title"><?php esc_html_e('Contact', 'wordiva-blog-theme'); ?> <span>Wordiva</span></h1>
            <p class="hero-subtitle">
                <?php esc_html_e('Have questions about our AI content system? We\'re here to help.', 'wordiva-blog-theme'); ?>
            </p>
        </div>
    </section>

    <div class="container">
        <div class="main-content">
            <div class="contact-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: start;">
                
                <!-- Contact Info -->
                <div class="contact-info">
                    <h2 class="section-title"><?php esc_html_e('Get in Touch', 'wordiva-blog-theme'); ?></h2>
                    <p style="font-size: 1.125rem; margin-bottom: 30px;">
                        <?php esc_html_e('Whether you want a demo, support, or just want to see how we can help your brand grow, reach out to us.', 'wordiva-blog-theme'); ?>
                    </p>
                    
                    <div class="contact-methods" style="display: flex; flex-direction: column; gap: 20px;">
                        <div class="contact-item">
                            <h3 style="font-size: 1.25rem; color: var(--wordiva-royal-purple); margin-bottom: 5px;"><?php esc_html_e('Email Us', 'wordiva-blog-theme'); ?></h3>
                            <a href="mailto:hello@wordiva.com" style="font-size: 1.1rem;">hello@wordiva.com</a>
                        </div>
                        
                        <div class="contact-item">
                            <h3 style="font-size: 1.25rem; color: var(--wordiva-royal-purple); margin-bottom: 5px;"><?php esc_html_e('Office', 'wordiva-blog-theme'); ?></h3>
                            <p><?php esc_html_e('123 AI Boulevard, Tech District', 'wordiva-blog-theme'); ?><br><?php esc_html_e('San Francisco, CA 94105', 'wordiva-blog-theme'); ?></p>
                        </div>
                        
                        <div class="contact-item">
                            <h3 style="font-size: 1.25rem; color: var(--wordiva-royal-purple); margin-bottom: 5px;"><?php esc_html_e('Social', 'wordiva-blog-theme'); ?></h3>
                            <div class="social-links" style="justify-content: flex-start; margin-top: 10px;">
                                <!-- Reusing social links styles from footer -->
                                <a href="https://twitter.com/wordiva" class="social-link" style="background: var(--wordiva-electric-blue); color: white;"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
                                <a href="https://linkedin.com/company/wordiva" class="social-link" style="background: var(--wordiva-electric-blue); color: white;"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452z"/></svg></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form Placeholder -->
                <div class="contact-form-wrapper" style="background: var(--wordiva-gray-light); padding: 40px; border-radius: 12px; border: 1px solid var(--wordiva-gray-medium);">
                    <h3 style="margin-bottom: 20px;"><?php esc_html_e('Send us a message', 'wordiva-blog-theme'); ?></h3>
                    
                    <?php if (isset($_GET['contact-success'])) : ?>
                        <div class="alert alert-success" style="padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #155724; background-color: #d4edda; border-color: #c3e6cb;">
                            <?php esc_html_e('Thank you! Your message has been sent successfully.', 'wordiva-blog-theme'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['contact-error'])) : ?>
                        <div class="alert alert-danger" style="padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #721c24; background-color: #f8d7da; border-color: #f5c6cb;">
                            <?php esc_html_e('Sorry, there was an error sending your message. Please verify your details and try again.', 'wordiva-blog-theme'); ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post" class="wordiva-contact-form">
                        <?php wp_nonce_field('wordiva_contact_action', 'wordiva_contact_nonce'); ?>
                        <div style="margin-bottom: 20px;">
                            <label for="contact-name" style="display: block; margin-bottom: 8px; font-weight: 500;"><?php esc_html_e('Name', 'wordiva-blog-theme'); ?></label>
                            <input type="text" id="contact-name" name="contact-name" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ccc;">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label for="contact-email" style="display: block; margin-bottom: 8px; font-weight: 500;"><?php esc_html_e('Email', 'wordiva-blog-theme'); ?></label>
                            <input type="email" id="contact-email" name="contact-email" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ccc;">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label for="contact-message" style="display: block; margin-bottom: 8px; font-weight: 500;"><?php esc_html_e('Message', 'wordiva-blog-theme'); ?></label>
                            <textarea id="contact-message" name="contact-message" rows="5" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ccc;"></textarea>
                        </div>
                        <button type="submit" name="wordiva_contact_submit" class="btn btn-primary" style="width: 100%; justify-content: center;"><?php esc_html_e('Send Message', 'wordiva-blog-theme'); ?></button>
                    </form>
                </div>

            </div>
        </div>
    </div>

</main>

<style>
    /* Responsive adjustment for contact grid using inline style block scoped to this page */
    @media (max-width: 768px) {
        .contact-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<?php get_footer(); ?>
