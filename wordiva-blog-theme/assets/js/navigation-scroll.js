/**
 * Navigation scroll effects and mobile menu functionality
 * 
 * @package Wordiva_Theme
 * @since 1.0.0
 */

(function() {
    'use strict';
    
    // Header scroll effect - matching React component
    const nav = document.getElementById('wordiva-nav');
    let lastScrollY = window.scrollY;
    
    function updateNavOnScroll() {
        const scrollY = window.scrollY;
        
        if (scrollY > 10) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
        
        lastScrollY = scrollY;
    }
    
    // Throttle scroll events for performance
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                updateNavOnScroll();
                ticking = false;
            });
            ticking = true;
        }
    });
    
    // Mobile menu toggle - matching React component
    const mobileBtn = document.getElementById('wordiva-mobile-btn');
    const mobileMenu = document.getElementById('wordiva-mobile-menu');
    
    if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener('click', function() {
            const isExpanded = mobileBtn.getAttribute('aria-expanded') === 'true';
            
            mobileBtn.setAttribute('aria-expanded', !isExpanded);
            mobileBtn.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });
        
        // Close mobile menu when clicking a link
        const mobileLinks = mobileMenu.querySelectorAll('.wordiva-nav-mobile-link');
        mobileLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                mobileBtn.setAttribute('aria-expanded', 'false');
                mobileBtn.classList.remove('active');
                mobileMenu.classList.remove('active');
            });
        });
    }
    
    // Initialize on page load
    updateNavOnScroll();
})();