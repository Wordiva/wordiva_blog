/*!
 * Wordiva Theme Main JavaScript
 * Version: 1.0.0
 * Description: Main JavaScript functionality for the Wordiva WordPress theme
 * Author: Wordiva Team
 * License: GPL v2 or later
 */

(function() {
    'use strict';

    /**
     * Initialize smooth scrolling for anchor links
     */
    function initSmoothScrolling() {
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                const target = document.querySelector(href);
                
                if (target) {
                    e.preventDefault();
                    const navHeight = document.querySelector('.main-navigation').offsetHeight;
                    
                    window.scrollTo({
                        top: target.offsetTop - navHeight - 20,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    /**
     * Enhanced search functionality
     */
    function initSearchEnhancements() {
        document.querySelectorAll('.search-form').forEach(function(form) {
            const searchField = form.querySelector('.search-field');
            const searchSubmit = form.querySelector('.search-submit');
            
            if (searchField && searchSubmit) {
                // Add loading state to search
                form.addEventListener('submit', function() {
                    searchSubmit.classList.add('searching');
                    searchSubmit.disabled = true;
                    
                    // Reset after 2 seconds (fallback)
                    setTimeout(function() {
                        searchSubmit.classList.remove('searching');
                        searchSubmit.disabled = false;
                    }, 2000);
                });
                
                // ESC key to clear search
                searchField.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        this.value = '';
                        this.blur();
                    }
                });
            }
        });
    }

    /**
     * Featured post navigation
     */
    function initFeaturedNavigation() {
        const featuredSection = document.querySelector('.featured-blog');
        const prevButton = document.querySelector('#featured-prev');
        const nextButton = document.querySelector('#featured-next');
        
        if (featuredSection && prevButton && nextButton) {
            featuredSection.classList.add('loaded');
            
            prevButton.addEventListener('click', function(e) {
                e.preventDefault();
                navigateFeatured('prev');
            });
            
            nextButton.addEventListener('click', function(e) {
                e.preventDefault();
                navigateFeatured('next');
            });
            
            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft' && e.ctrlKey) {
                    e.preventDefault();
                    navigateFeatured('prev');
                } else if (e.key === 'ArrowRight' && e.ctrlKey) {
                    e.preventDefault();
                    navigateFeatured('next');
                }
            });
        }
    }

    /**
     * Navigate featured posts
     * @param {string} direction - 'prev' or 'next'
     */
    function navigateFeatured(direction) {
        const container = document.querySelector('.featured-container');
        
        if (container) {
            container.classList.add('loading');
            
            // Simulate navigation (in real implementation, this would make an AJAX call)
            setTimeout(function() {
                container.classList.remove('loading');
                container.classList.add('navigated');
                
                setTimeout(function() {
                    container.classList.remove('navigated');
                }, 300);
                
                // Focus management
                const titleLink = container.querySelector('.featured-title a');
                if (titleLink) {
                    titleLink.focus();
                }
            }, 500);
        }
    }

    /**
     * Accessibility enhancements
     */
    function initAccessibilityEnhancements() {
        // Skip link functionality
        const skipLink = document.querySelector('.skip-link');
        if (skipLink) {
            skipLink.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.focus();
                }
            });
        }
        
        // Enhanced focus management for cards
        document.querySelectorAll('.blog-card .card-title a, .featured-post .featured-title a').forEach(function(link) {
            link.addEventListener('focus', function() {
                this.closest('article').classList.add('focused');
            });
            
            link.addEventListener('blur', function() {
                this.closest('article').classList.remove('focused');
            });
        });
        
        // Keyboard navigation for read-more buttons
        document.querySelectorAll('.read-more, .read-more-featured').forEach(function(button) {
            button.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });
    }

    /**
     * Dynamic styles injection for enhanced interactions
     */
    function injectDynamicStyles() {
        const styleId = 'wordiva-card-interactions';
        
        // Prevent duplicate injection
        if (document.getElementById(styleId)) {
            return;
        }
        
        const style = document.createElement('style');
        style.id = styleId;
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }
            
            .card-hover-active {
                transform: translateY(-4px);
                box-shadow: 0 12px 24px rgba(47, 128, 255, 0.15);
                border-color: var(--wordiva-electric-blue, #2F80FF);
                transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            }
            
            @media (prefers-reduced-motion: reduce) {
                .card-hover-active {
                    transform: none;
                    transition: box-shadow 0.3s ease, border-color 0.3s ease;
                }
                
                .article-card img,
                .blog-card img,
                .post-card img,
                .featured-card img {
                    transform: none !important;
                    transition: none !important;
                }
                
                .card-content {
                    transform: none !important;
                    transition: none !important;
                }
            }
            
            .card-focused {
                outline: 3px solid var(--wordiva-electric-blue, #2F80FF);
                outline-offset: 2px;
                border-radius: 12px;
            }
            
            .animate-in {
                opacity: 1 !important;
                transform: translateY(0) !important;
            }
            
            .skeleton-shimmer::after {
                animation: shimmer 1.5s infinite;
            }
            
            @keyframes shimmer {
                0% { transform: translateX(-100%); }
                100% { transform: translateX(100%); }
            }
            
            .article-card,
            .blog-card,
            .post-card,
            .featured-card {
                transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
                will-change: transform, box-shadow;
            }
            
            .article-card:focus-visible,
            .blog-card:focus-visible,
            .post-card:focus-visible,
            .featured-card:focus-visible {
                outline: 3px solid var(--wordiva-electric-blue, #2F80FF);
                outline-offset: 2px;
            }
            
            .featured-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 16px 32px rgba(123, 77, 255, 0.2);
            }
            
            @media (prefers-reduced-motion: reduce) {
                .featured-card:hover {
                    transform: none;
                }
            }
            
            .sr-only {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border: 0;
            }
        `;
        
        document.head.appendChild(style);
    }

    /**
     * Performance optimizations
     */
    function initPerformanceOptimizations() {
        let ticking = false;
        
        function updateScrollState() {
            ticking = false;
            // Scroll-based optimizations can be added here
        }
        
        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateScrollState);
                ticking = true;
            }
        }
        
        // Throttled scroll handler
        window.addEventListener('scroll', function() {
            clearTimeout(undefined);
            setTimeout(function() {
                requestTick();
            }, 10);
        }, { passive: true });
        
        // Optimize critical images
        document.querySelectorAll('.featured-card img, .article-card img:first-of-type').forEach(function(img) {
            if (img.loading !== 'eager') {
                img.loading = 'eager';
            }
        });
        
        // Optimize card animations
        document.querySelectorAll('.article-card, .blog-card, .post-card, .featured-card').forEach(function(card) {
            card.style.willChange = 'transform, box-shadow';
            
            card.addEventListener('transitionend', function() {
                this.style.willChange = 'auto';
            });
        });
    }

    /**
     * Enhanced card accessibility
     */
    function initCardAccessibility() {
        document.querySelectorAll('.article-card, .blog-card, .post-card, .featured-card').forEach(function(card) {
            // Make cards focusable and accessible
            card.setAttribute('role', 'article');
            card.setAttribute('tabindex', '0');
            
            // Generate accessible labels
            const title = card.querySelector('.card-title, .featured-title');
            if (title) {
                const titleText = title.textContent.trim();
                let ariaLabel = `Article: ${titleText}`;
                
                const excerpt = card.querySelector('.card-excerpt');
                if (excerpt) {
                    ariaLabel += `. ${excerpt.textContent.trim().substring(0, 100)}`;
                }
                
                const meta = card.querySelector('.card-meta');
                if (meta) {
                    ariaLabel += `. ${meta.textContent.trim()}`;
                }
                
                card.setAttribute('aria-label', ariaLabel);
            }
            
            // Add screen reader instructions on focus
            card.addEventListener('focus', function() {
                const instruction = document.createElement('div');
                instruction.setAttribute('aria-live', 'polite');
                instruction.setAttribute('aria-atomic', 'true');
                instruction.className = 'sr-only';
                instruction.textContent = 'Press Enter or Space to read this article';
                
                this.appendChild(instruction);
                
                // Remove after 3 seconds
                setTimeout(() => {
                    if (instruction.parentNode) {
                        instruction.parentNode.removeChild(instruction);
                    }
                }, 3000);
            });
            
            // Handle keyboard activation
            card.addEventListener('keydown', function(e) {
                // Allow tab navigation
                if (e.key === 'Tab') {
                    return;
                }
                
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    
                    // Announce navigation
                    const announcement = document.createElement('div');
                    announcement.setAttribute('aria-live', 'assertive');
                    announcement.className = 'sr-only';
                    announcement.textContent = 'Navigating to article...';
                    document.body.appendChild(announcement);
                    
                    setTimeout(() => {
                        const link = this.querySelector('.card-title a, .card-image-link');
                        if (link) {
                            link.click();
                        }
                        
                        if (announcement.parentNode) {
                            announcement.parentNode.removeChild(announcement);
                        }
                    }, 100);
                }
            });
        });
    }

    /**
     * Enhanced card interactions
     */
    function initCardInteractions() {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        document.querySelectorAll('.article-card, .blog-card, .post-card, .featured-card').forEach(function(card) {
            setupCardHover(card, prefersReducedMotion);
            setupCardClick(card);
            setupCardKeyboard(card);
            setupCardFocus(card);
            
            if (!prefersReducedMotion) {
                setupCard3DEffect(card);
            }
        });
        
        if (!prefersReducedMotion) {
            initScrollAnimations();
        }
    }

    /**
     * Setup card hover effects
     * @param {Element} card - The card element
     * @param {boolean} reducedMotion - Whether reduced motion is preferred
     */
    function setupCardHover(card, reducedMotion) {
        const image = card.querySelector('.card-image img');
        const content = card.querySelector('.card-content');
        const titleLink = card.querySelector('.card-title a');
        const category = card.querySelector('.card-category, .category');
        const readMore = card.querySelector('.card-read-more, .read-more');
        
        card.addEventListener('mouseenter', function() {
            if (reducedMotion) {
                this.classList.add('card-hover-active');
                if (titleLink) {
                    titleLink.style.color = 'var(--wordiva-royal-purple)';
                }
            } else {
                this.classList.add('card-hover-active');
                
                if (image) {
                    image.style.transform = 'scale(1.08)';
                    image.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                }
                
                if (content) {
                    content.style.transform = 'translateY(-2px)';
                    content.style.transition = 'transform 0.3s ease-out';
                }
                
                if (titleLink) {
                    titleLink.style.color = 'var(--wordiva-royal-purple)';
                    titleLink.style.transition = 'color 0.3s ease-out';
                }
                
                if (category) {
                    category.style.boxShadow = '0 0 20px rgba(123, 77, 255, 0.3)';
                    category.style.transition = 'box-shadow 0.3s ease-out';
                }
                
                if (readMore) {
                    readMore.style.transform = 'translateX(4px)';
                    readMore.style.transition = 'all 0.3s ease-out';
                }
            }
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('card-hover-active');
            
            if (!reducedMotion) {
                if (image) {
                    image.style.transform = 'scale(1)';
                }
                
                if (content) {
                    content.style.transform = 'translateY(0)';
                }
                
                if (category) {
                    category.style.boxShadow = 'none';
                }
                
                if (readMore) {
                    readMore.style.transform = 'translateX(0)';
                    readMore.style.color = 'var(--wordiva-electric-blue)';
                }
            }
            
            if (titleLink) {
                titleLink.style.color = 'var(--wordiva-charcoal-dark)';
            }
        });
    }

    /**
     * Setup card click functionality
     * @param {Element} card - The card element
     */
    function setupCardClick(card) {
        card.addEventListener('click', function(e) {
            // Don't interfere with actual links or buttons
            if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || 
                e.target.closest('a') || e.target.closest('button')) {
                return;
            }
            
            const link = this.querySelector('.card-title a, .card-image-link');
            if (link) {
                // Visual feedback
                this.style.transform = 'scale(0.98)';
                this.style.transition = 'transform 0.1s ease-out';
                
                setTimeout(() => {
                    this.style.transform = '';
                    window.location.href = link.href;
                }, 100);
            }
        });
        
        // Add cursor pointer to indicate clickability
        card.style.cursor = 'pointer';
    }

    /**
     * Setup card keyboard navigation
     * @param {Element} card - The card element
     */
    function setupCardKeyboard(card) {
        card.setAttribute('tabindex', '0');
        card.setAttribute('role', 'article');
        
        const title = card.querySelector('.card-title, .featured-title');
        const titleText = title ? title.textContent.trim() : 'Article';
        card.setAttribute('aria-label', `Read article: ${titleText}`);
        
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                
                // Visual feedback
                this.style.transform = 'scale(0.98)';
                this.style.transition = 'transform 0.1s ease-out';
                
                const link = this.querySelector('.card-title a, .card-image-link');
                if (link) {
                    setTimeout(() => {
                        this.style.transform = '';
                        link.click();
                    }, 100);
                }
            }
        });
    }

    /**
     * Setup card focus states
     * @param {Element} card - The card element
     */
    function setupCardFocus(card) {
        card.addEventListener('focus', function() {
            this.classList.add('card-focused');
            this.style.outline = '3px solid var(--wordiva-electric-blue)';
            this.style.outlineOffset = '2px';
            this.style.borderRadius = '12px';
            
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (!prefersReducedMotion) {
                this.style.transform = 'scale(1.02)';
                this.style.transition = 'transform 0.2s ease-out';
            }
        });
        
        card.addEventListener('blur', function() {
            this.classList.remove('card-focused');
            this.style.outline = '';
            this.style.outlineOffset = '';
            this.style.transform = '';
        });
    }

    /**
     * Setup 3D card effects
     * @param {Element} card - The card element
     */
    function setupCard3DEffect(card) {
        const image = card.querySelector('.card-image img');
        
        if (!image) return;
        
        card.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / centerY * -5;
            const rotateY = (x - centerX) / centerX * 5;
            
            image.style.transform = `scale(1.08) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            image.style.transition = 'transform 0.1s ease-out';
        });
        
        card.addEventListener('mouseleave', function() {
            if (image) {
                image.style.transform = 'scale(1)';
                image.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            }
        });
    }

    /**
     * Initialize scroll-based animations
     */
    function initScrollAnimations() {
        if (!('IntersectionObserver' in window)) {
            return;
        }
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const card = entry.target;
                    const cards = Array.from(document.querySelectorAll('.article-card, .blog-card, .post-card'));
                    const index = cards.indexOf(card);
                    const delay = (index % 3) * 100; // Stagger animation
                    
                    setTimeout(() => {
                        card.classList.add('animate-in');
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                        card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                    }, delay);
                    
                    observer.unobserve(card);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        // Observe all cards and set initial state
        document.querySelectorAll('.article-card, .blog-card, .post-card').forEach(function(card) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            observer.observe(card);
        });
    }

    // Initialize core functionality on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        initSmoothScrolling();
        initSearchEnhancements();
        initFeaturedNavigation();
        initAccessibilityEnhancements();
    });

    // Initialize enhanced functionality on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        injectDynamicStyles();
        initPerformanceOptimizations();
        initCardAccessibility();
        initCardInteractions();
    });

})();