/**
 * Customizer Preview JavaScript for Wordiva Theme
 * 
 * @package Wordiva_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    // Header message
    wp.customize('wordiva_header_message', function(value) {
        value.bind(function(newval) {
            $('.header-title').text(newval);
        });
    });
    
    // Header subtitle
    wp.customize('wordiva_header_subtitle', function(value) {
        value.bind(function(newval) {
            $('.header-subtitle').text(newval);
        });
    });
    
    // Brand colors
    wp.customize('wordiva_electric_blue', function(value) {
        value.bind(function(newval) {
            updateCSSVariable('--wordiva-electric-blue', newval);
        });
    });
    
    wp.customize('wordiva_royal_purple', function(value) {
        value.bind(function(newval) {
            updateCSSVariable('--wordiva-royal-purple', newval);
        });
    });
    
    wp.customize('wordiva_neon_pink', function(value) {
        value.bind(function(newval) {
            updateCSSVariable('--wordiva-neon-pink', newval);
        });
    });
    
    // Footer options
    wp.customize('wordiva_footer_copyright', function(value) {
        value.bind(function(newval) {
            $('.copyright').html('&copy; ' + new Date().getFullYear() + ' <a href="' + window.location.origin + '" rel="home">' + $('title').text().split(' | ')[1] + '</a>. ' + newval);
        });
    });
    
    wp.customize('wordiva_footer_description', function(value) {
        value.bind(function(newval) {
            $('.footer-description').text(newval);
        });
    });
    
    /**
     * Update CSS custom property
     */
    function updateCSSVariable(property, value) {
        document.documentElement.style.setProperty(property, value);
    }
    
})(jQuery);