<?php
/**
 * The header for our theme - Landing Page Navigation
 *
 * @package Wordiva_Theme
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?> itemscope itemtype="https://schema.org/WebPage">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
    
    <!-- Inline Mobile Menu Fix -->
    <script>
    (function(){
        window.addEventListener('DOMContentLoaded', function() {
            console.log('🔥 INLINE FIX: Starting...');
            
            var btn = document.getElementById('wordiva-mobile-btn');
            var menu = document.getElementById('wordiva-mobile-menu');
            
            if (!btn || !menu) {
                console.error('🔥 INLINE FIX: Elements not found');
                return;
            }
            
            console.log('🔥 INLINE FIX: Elements found, attaching handler');
            
            // Remove any existing handlers by cloning
            var newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            btn = newBtn;
            
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                console.log('🔥 INLINE FIX: CLICKED!');
                
                var isOpen = menu.style.maxHeight === '500px';
                
                if (isOpen) {
                    console.log('🔥 CLOSING');
                    menu.style.maxHeight = '0';
                    menu.style.opacity = '0';
                    menu.style.overflow = 'hidden';
                    btn.classList.remove('active');
                    menu.classList.remove('active');
                    btn.setAttribute('aria-expanded', 'false');
                } else {
                    console.log('🔥 OPENING');
                    menu.style.maxHeight = '500px';
                    menu.style.opacity = '1';
                    menu.style.overflow = 'visible';
                    menu.style.display = 'block';
                    btn.classList.add('active');
                    menu.classList.add('active');
                    btn.setAttribute('aria-expanded', 'true');
                }
            }, true);
            
            console.log('🔥 INLINE FIX: Handler attached!');
        });
    })();
    </script>
</head>

<body <?php body_class(); ?> itemscope itemtype="https://schema.org/WebPage">
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to content', 'wordiva-blog-theme'); ?></a>

<div class="site-wrapper" itemscope itemtype="https://schema.org/WebSite">
    
    <!-- Navigation matching landing page -->
    <nav class="wordiva-nav-fixed" id="wordiva-nav" role="navigation" aria-label="<?php esc_attr_e('Main Navigation', 'wordiva-blog-theme'); ?>">
        <div class="wordiva-nav-inner">
            <div class="wordiva-nav-content">
                
                <!-- Logo -->
                <div class="wordiva-nav-logo">
                    <a href="<?php echo esc_url(wordiva_get_main_site_url()); ?>" class="wordiva-logo-link">
                        <img src="<?php echo esc_url(wordiva_get_logo_url()); ?>" 
                             alt="<?php echo esc_attr(get_bloginfo('name')); ?> Logo" 
                             class="wordiva-logo-img">
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="wordiva-nav-desktop">
                    <a href="<?php echo esc_url(wordiva_get_main_site_url()); ?>" class="wordiva-nav-link">
                        <?php esc_html_e('Home', 'wordiva-blog-theme'); ?>
                    </a>
                    <a href="<?php echo esc_url(wordiva_get_blog_url()); ?>" class="wordiva-nav-link <?php echo (is_home() || is_singular('post') || is_archive()) ? 'active' : ''; ?>">
                        <?php esc_html_e('Blog', 'wordiva-blog-theme'); ?>
                    </a>
                    <button class="wordiva-cta-button" 
                            onclick="window.location.href='<?php echo esc_url(wordiva_get_cta_url()); ?>'"
                            aria-label="<?php esc_attr_e('Join Waitlist', 'wordiva-blog-theme'); ?>">
                        <?php esc_html_e('Join Waitlist', 'wordiva-blog-theme'); ?>
                    </button>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="wordiva-nav-mobile-toggle">
                    <button id="wordiva-mobile-btn" 
                            class="wordiva-mobile-button" 
                            aria-label="<?php esc_attr_e('Toggle menu', 'wordiva-blog-theme'); ?>"
                            aria-expanded="false">
                        <svg class="wordiva-hamburger-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path class="wordiva-hamburger-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path class="wordiva-hamburger-close" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
            </div>
        </div>
        
        <!-- Mobile Navigation Dropdown -->
        <div class="wordiva-nav-mobile-menu" id="wordiva-mobile-menu">
            <div class="wordiva-nav-mobile-inner">
                <a href="<?php echo esc_url(wordiva_get_main_site_url()); ?>" class="wordiva-nav-mobile-link">
                    <?php esc_html_e('Home', 'wordiva-blog-theme'); ?>
                </a>
                <a href="<?php echo esc_url(wordiva_get_blog_url()); ?>" class="wordiva-nav-mobile-link <?php echo (is_home() || is_singular('post') || is_archive()) ? 'active' : ''; ?>">
                    <?php esc_html_e('Blog', 'wordiva-blog-theme'); ?>
                </a>
                <button class="wordiva-cta-button-mobile" 
                        onclick="window.location.href='<?php echo esc_url(wordiva_get_cta_url()); ?>'"
                        aria-label="<?php esc_attr_e('Join Waitlist', 'wordiva-blog-theme'); ?>">
                    <?php esc_html_e('Join Waitlist', 'wordiva-blog-theme'); ?>
                </button>
            </div>
        </div>
    </nav>
    
    <!-- Breadcrumb Navigation (Single Posts Only) -->
    <?php if (is_single() && get_post_type() === 'post') : ?>
        <div class="header-breadcrumb-wrapper">
            <div class="wordiva-nav-inner">
                <nav class="header-breadcrumb-nav" aria-label="<?php esc_attr_e('Breadcrumb', 'wordiva-blog-theme'); ?>" itemscope itemtype="https://schema.org/BreadcrumbList">
                    <ol class="header-breadcrumb" role="list">
                        <li class="breadcrumb-item" role="listitem" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a href="<?php echo esc_url(home_url('/')); ?>" itemprop="item" aria-label="<?php esc_attr_e('Go to homepage', 'wordiva-blog-theme'); ?>">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                                    <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span itemprop="name"><?php esc_html_e('Home', 'wordiva-blog-theme'); ?></span>
                            </a>
                            <meta itemprop="position" content="1">
                        </li>
                        <?php if (has_category()) : ?>
                            <li class="breadcrumb-item" role="listitem" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                <?php
                                $categories = get_the_category();
                                if (!empty($categories)) {
                                    $category = $categories[0];
                                    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" itemprop="item" aria-label="' . esc_attr(sprintf(__('View all posts in %s', 'wordiva-blog-theme'), $category->name)) . '">';
                                    echo '<span itemprop="name">' . esc_html($category->name) . '</span>';
                                    echo '</a>';
                                }
                                ?>
                                <meta itemprop="position" content="2">
                            </li>
                        <?php endif; ?>
                        <li class="breadcrumb-item active" role="listitem" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <span itemprop="name"><?php echo esc_html(wp_trim_words(get_the_title(), 10, '...')); ?></span>
                            <meta itemprop="position" content="<?php echo has_category() ? '3' : '2'; ?>">
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="site-content"><?php // Content starts here, closed in footer.php ?>
