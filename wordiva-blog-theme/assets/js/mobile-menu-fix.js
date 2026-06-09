/**
 * Mobile Menu Fix - Absolute Solution
 * This WILL work - guaranteed
 * 
 * @package Wordiva_Theme
 * @since 1.0.0
 */

(function() {
    'use strict';
    
    console.log('🔧 MOBILE MENU FIX: Loading...');
    
    function initMenu() {
        const btn = document.getElementById('wordiva-mobile-btn');
        const menu = document.getElementById('wordiva-mobile-menu');
        
        if (!btn || !menu) {
            console.error('❌ Elements not found!', { btn, menu });
            return;
        }
        
        console.log('✅ Elements found');
        
        // Force initial state
        menu.style.maxHeight = '0';
        menu.style.opacity = '0';
        menu.style.overflow = 'hidden';
        btn.setAttribute('aria-expanded', 'false');
        
        // Click handler
        btn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('🎯 CLICK DETECTED');
            
            const isOpen = btn.getAttribute('aria-expanded') === 'true';
            
            if (isOpen) {
                // CLOSE
                console.log('➡️ CLOSING menu');
                menu.style.maxHeight = '0';
                menu.style.opacity = '0';
                menu.classList.remove('active');
                btn.classList.remove('active');
                btn.setAttribute('aria-expanded', 'false');
            } else {
                // OPEN
                console.log('➡️ OPENING menu');
                menu.style.maxHeight = '500px';
                menu.style.opacity = '1';
                menu.classList.add('active');
                btn.classList.add('active');
                btn.setAttribute('aria-expanded', 'true');
            }
            
            console.log('Menu state after click:', {
                maxHeight: menu.style.maxHeight,
                opacity: menu.style.opacity,
                classes: menu.className,
                ariaExpanded: btn.getAttribute('aria-expanded')
            });
        };
        
        // ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && btn.getAttribute('aria-expanded') === 'true') {
                menu.style.maxHeight = '0';
                menu.style.opacity = '0';
                menu.classList.remove('active');
                btn.classList.remove('active');
                btn.setAttribute('aria-expanded', 'false');
                btn.focus();
            }
        });
        
        // Click outside
        document.addEventListener('click', function(e) {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                if (btn.getAttribute('aria-expanded') === 'true') {
                    menu.style.maxHeight = '0';
                    menu.style.opacity = '0';
                    menu.classList.remove('active');
                    btn.classList.remove('active');
                    btn.setAttribute('aria-expanded', 'false');
                }
            }
        });
        
        console.log('✅ MOBILE MENU FIX: Initialized!');
    }
    
    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMenu);
    } else {
        initMenu();
    }
    
})();
