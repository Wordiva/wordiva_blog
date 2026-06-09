/**
 * Simple Mobile Navigation Toggle - Wordiva Theme
 * Minimal implementation to ensure menu works
 * 
 * @package Wordiva_Theme
 * @since 1.0.0
 */

(function() {
    'use strict';
    
    console.log('🚀 Simple Navigation: Script loaded');
    
    // Wait for DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    function init() {
        console.log('🚀 Simple Navigation: Initializing...');
        
        const btn = document.getElementById('wordiva-mobile-btn');
        const menu = document.getElementById('wordiva-mobile-menu');
        
        if (!btn) {
            console.error('❌ Button not found: #wordiva-mobile-btn');
            return;
        }
        
        if (!menu) {
            console.error('❌ Menu not found: #wordiva-mobile-menu');
            return;
        }
        
        console.log('✅ Elements found:', { btn, menu });
        
        // Simple click handler
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('🎯 Button clicked!');
            
            const isActive = menu.classList.contains('active');
            console.log('Current state:', isActive ? 'OPEN' : 'CLOSED');
            
            if (isActive) {
                // Close menu
                menu.classList.remove('active');
                btn.classList.remove('active');
                btn.setAttribute('aria-expanded', 'false');
                console.log('➡️ Closing menu');
            } else {
                // Open menu
                menu.classList.add('active');
                btn.classList.add('active');
                btn.setAttribute('aria-expanded', 'true');
                console.log('➡️ Opening menu');
            }
            
            // Log computed styles
            const menuStyles = window.getComputedStyle(menu);
            console.log('Menu styles after toggle:', {
                maxHeight: menuStyles.maxHeight,
                opacity: menuStyles.opacity,
                display: menuStyles.display,
                overflow: menuStyles.overflow
            });
        });
        
        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && menu.classList.contains('active')) {
                menu.classList.remove('active');
                btn.classList.remove('active');
                btn.setAttribute('aria-expanded', 'false');
                btn.focus();
                console.log('🔒 Menu closed with ESC');
            }
        });
        
        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                if (menu.classList.contains('active')) {
                    menu.classList.remove('active');
                    btn.classList.remove('active');
                    btn.setAttribute('aria-expanded', 'false');
                    console.log('🔒 Menu closed (clicked outside)');
                }
            }
        });
        
        console.log('✅ Simple Navigation: Initialized successfully!');
    }
    
})();
