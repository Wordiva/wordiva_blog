/**
 * Navigation JavaScript for Wordiva Theme
 * 
 * @package Wordiva_Theme
 * @since 1.0.0
 */

(function() {
    'use strict';
    
    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initSearchEnhancements();
        initFeaturedNavigation();
        initNavigationAccessibility();
        initMobileMenu();
        initKeyboardNavigation();
    });
    
    /**
     * Initialize search functionality enhancements
     */
    function initSearchEnhancements() {
        const searchForms = document.querySelectorAll('.search-form');
        
        searchForms.forEach(function(form) {
            const searchInput = form.querySelector('.search-field');
            const searchButton = form.querySelector('.search-submit');
            
            if (!searchInput || !searchButton) {
                return;
            }
            
            // Add loading state on form submission
            form.addEventListener('submit', function(e) {
                // Basic validation
                if (searchInput.value.trim() === '') {
                    e.preventDefault();
                    searchInput.focus();
                    
                    // Add error styling
                    searchInput.classList.add('error');
                    searchInput.setAttribute('aria-invalid', 'true');
                    
                    // Remove error styling after user starts typing
                    searchInput.addEventListener('input', function() {
                        this.classList.remove('error');
                        this.setAttribute('aria-invalid', 'false');
                    }, { once: true });
                    
                    return;
                }
                
                // Add loading state
                searchButton.classList.add('searching');
                searchButton.disabled = true;
                searchButton.setAttribute('aria-busy', 'true');
                
                // Update button content
                const originalContent = searchButton.innerHTML;
                searchButton.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M12 2V6M12 18V22M4.93 4.93L7.76 7.76M16.24 16.24L19.07 19.07M2 12H6M18 12H22M4.93 19.07L7.76 16.24M16.24 7.76L19.07 4.93" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><animateTransform attributeName="transform" attributeType="XML" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite"/></path></svg><span class="screen-reader-text">Searching...</span>';
                
                // Reset after timeout (in case navigation is slow)
                setTimeout(function() {
                    searchButton.classList.remove('searching');
                    searchButton.disabled = false;
                    searchButton.setAttribute('aria-busy', 'false');
                    searchButton.innerHTML = originalContent;
                }, 3000);
            });
            
            // Clear search on escape key
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    this.blur();
                }
            });
            
            // Search expansion logic removed - handled by CSS now
        });
    }
    
    /**
     * Initialize featured blog navigation
     */
    function initFeaturedNavigation() {
        const featuredSection = document.querySelector('.featured-blog');
        
        if (featuredSection) {
            // Add loaded class for styling
            featuredSection.classList.add('loaded');
            
            // Announce featured content to screen readers
            const featuredTitle = featuredSection.querySelector('.featured-title');
            if (featuredTitle) {
                featuredTitle.setAttribute('aria-live', 'polite');
            }
        }
    }
    
    /**
     * Initialize navigation accessibility enhancements
     */
    function initNavigationAccessibility() {
        // Add skip link functionality
        const skipLinks = document.querySelectorAll('.skip-link');
        skipLinks.forEach(function(skipLink) {
            skipLink.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.focus();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
        
        // Improve focus management for card links
        const cardLinks = document.querySelectorAll('.blog-card .card-title a, .featured-post .featured-title a');
        cardLinks.forEach(function(link) {
            link.addEventListener('focus', function() {
                this.closest('article').classList.add('focused');
            });
            
            link.addEventListener('blur', function() {
                this.closest('article').classList.remove('focused');
            });
        });
        
        // Add keyboard navigation for read more buttons
        const readMoreButtons = document.querySelectorAll('.read-more, .read-more-featured');
        readMoreButtons.forEach(function(button) {
            button.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });
        
        // Add focus indicators for better accessibility
        const focusableElements = document.querySelectorAll('a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])');
        
        focusableElements.forEach(function(element) {
            element.addEventListener('focus', function() {
                this.classList.add('focused');
            });
            
            element.addEventListener('blur', function() {
                this.classList.remove('focused');
            });
        });
    }
    
    /**
     * Initialize mobile menu functionality
     */
    function initMobileMenu() {
        const mobileToggle = document.querySelector('#wordiva-mobile-btn');
        const mobileMenu = document.querySelector('#wordiva-mobile-menu');
        
        if (!mobileToggle || !mobileMenu) {
            console.warn('Wordiva Navigation: Mobile menu elements not found');
            return;
        }
        
        // Ensure initial state is correct
        if (!mobileToggle.hasAttribute('aria-expanded')) {
            mobileToggle.setAttribute('aria-expanded', 'false');
        }
        
        // Add click event listener
        mobileToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            console.log('Menu toggle clicked. Current state:', isExpanded ? 'open' : 'closed');
            
            // Toggle states
            this.setAttribute('aria-expanded', !isExpanded);
            this.classList.toggle('active');
            mobileMenu.classList.toggle('active');
            
            console.log('Menu new state:', !isExpanded ? 'open' : 'closed');
            
            // Focus management
            if (!isExpanded) {
                const firstMenuItem = mobileMenu.querySelector('a');
                if (firstMenuItem) {
                    setTimeout(() => firstMenuItem.focus(), 100);
                }
            }
        });
        
        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                mobileMenu.classList.remove('active');
                mobileToggle.classList.remove('active');
                mobileToggle.setAttribute('aria-expanded', 'false');
                mobileToggle.focus();
            }
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
                if (mobileMenu.classList.contains('active')) {
                    mobileMenu.classList.remove('active');
                    mobileToggle.classList.remove('active');
                    mobileToggle.setAttribute('aria-expanded', 'false');
                }
            }
        });
        
        console.log('Wordiva Navigation: Mobile menu initialized successfully');
    }

    }
    
    /**
     * Initialize keyboard navigation
     */
    function initKeyboardNavigation() {
        // Add keyboard navigation for desktop menu items
        const desktopMenuItems = document.querySelectorAll('.wordiva-nav-desktop a');
        
        desktopMenuItems.forEach(function(item, index) {
            item.addEventListener('keydown', function(e) {
                // Handle arrow key navigation
                if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                    e.preventDefault();
                    const nextItem = getNextMenuItem(desktopMenuItems, index);
                    if (nextItem) {
                        nextItem.focus();
                    }
                } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    const prevItem = getPrevMenuItem(desktopMenuItems, index);
                    if (prevItem) {
                        prevItem.focus();
                    }
                } else if (e.key === 'Home') {
                    e.preventDefault();
                    desktopMenuItems[0].focus();
                } else if (e.key === 'End') {
                    e.preventDefault();
                    desktopMenuItems[desktopMenuItems.length - 1].focus();
                }
            });
        });
        
        // Add keyboard navigation for mobile menu items
        const mobileMenuItems = document.querySelectorAll('.wordiva-nav-mobile-link');
        
        mobileMenuItems.forEach(function(item, index) {
            item.addEventListener('keydown', function(e) {
                // Handle arrow key navigation
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    const nextItem = getNextMenuItem(mobileMenuItems, index);
                    if (nextItem) {
                        nextItem.focus();
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    const prevItem = getPrevMenuItem(mobileMenuItems, index);
                    if (prevItem) {
                        prevItem.focus();
                    }
                } else if (e.key === 'Home') {
                    e.preventDefault();
                    mobileMenuItems[0].focus();
                } else if (e.key === 'End') {
                    e.preventDefault();
                    mobileMenuItems[mobileMenuItems.length - 1].focus();
                }
            });
        });
        
        // Add keyboard navigation for featured navigation buttons
        const featuredNavButtons = document.querySelectorAll('.featured-navigation button');
        featuredNavButtons.forEach(function(button) {
            button.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });
        
        // Add keyboard navigation for pagination
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(function(link) {
            link.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    // Let the default behavior handle the navigation
                    return;
                }
            });
        });
    }
    
    /**
     * Get next menu item for keyboard navigation
     */
    function getNextMenuItem(menuItems, currentIndex) {
        if (currentIndex < menuItems.length - 1) {
            return menuItems[currentIndex + 1];
        }
        return menuItems[0]; // Loop to first item
    }
    
    /**
     * Get previous menu item for keyboard navigation
     */
    function getPrevMenuItem(menuItems, currentIndex) {
        if (currentIndex > 0) {
            return menuItems[currentIndex - 1];
        }
        return menuItems[menuItems.length - 1]; // Loop to last item
    }
    
})();