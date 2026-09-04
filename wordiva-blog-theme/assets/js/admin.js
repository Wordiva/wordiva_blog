/*!
 * Wordiva Theme Admin JavaScript
 * Version: 1.0.0
 * Description: Admin panel functionality for the Wordiva WordPress theme
 * Author: Wordiva Team
 * License: GPL v2 or later
 */

(function($) {
    'use strict';

    /**
     * Initialize meta box styling and functionality
     */
    function initMetaBoxes() {
        // Add custom classes to meta boxes
        $('#wordiva_post_options').addClass('wordiva-meta-box');
        $('#wordiva_card_options').addClass('wordiva-meta-box');
        
        // Handle save notifications
        $(document).on('click', '#publish, #save-post', function() {
            showSaveNotification();
        });
    }

    /**
     * Show save notification
     */
    function showSaveNotification() {
        const notification = $('<div class="wordiva-meta-saved">Post options saved successfully!</div>');
        $('.wordiva-meta-box').first().prepend(notification);
        
        setTimeout(function() {
            notification.addClass('show');
        }, 100);
        
        setTimeout(function() {
            notification.fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    }

    /**
     * Update color preview
     * @param {string} color - The selected color
     */
    function updateColorPreview(color) {
        const preview = $('#color-preview');
        const colorClasses = [
            'electric-blue',
            'royal-purple', 
            'neon-pink',
            'sunrise-orange',
            'golden-yellow'
        ];
        
        // Remove all color classes
        preview.removeClass(colorClasses.join(' '));
        
        // Add selected color class
        if (color) {
            preview.addClass(color);
        } else {
            preview.addClass('electric-blue'); // Default
        }
    }

    /**
     * Show temporary notification
     * @param {string} message - The notification message
     * @param {string} type - The notification type ('success' or 'error')
     */
    function showTemporaryNotification(message, type = 'success') {
        const noticeClass = type === 'error' ? 'notice-error' : 'notice-success';
        const notice = $(`<div class="notice ${noticeClass} is-dismissible"><p>${message}</p></div>`);
        
        $('.wrap h1').after(notice);
        
        setTimeout(function() {
            notice.fadeOut(function() {
                $(this).remove();
            });
        }, 4000);
    }

    /**
     * Update featured level indicator
     * @param {string} level - The featured level
     */
    function updateFeaturedLevelIndicator(level) {
        const select = $('#wordiva_featured_level');
        let indicator = select.siblings('.wordiva-featured-level-indicator');
        
        // Remove existing indicator
        indicator.remove();
        
        if (level && level !== 'none') {
            const className = `wordiva-featured-level-${level}`;
            const label = level === 'primary' ? 'Hero Post' : 'Featured';
            
            select.after(`<span class="wordiva-featured-level-indicator ${className}">${label}</span>`);
        }
    }

    /**
     * Calculate reading time based on content
     */
    function calculateReadingTime() {
        let content = '';
        
        // Try to get content from different editors
        if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
            content = tinymce.get('content').getContent();
        } else if ($('#content').length) {
            content = $('#content').val();
        } else if ($('.editor-post-text-editor').length) {
            content = $('.editor-post-text-editor').val();
        }
        
        if (content) {
            // Strip HTML and count words
            const wordCount = $('<div>').html(content).text().trim().split(/\s+/).length;
            const readingTime = Math.max(1, Math.ceil(wordCount / 225)); // 225 words per minute
            
            $('#wordiva_reading_time').val(readingTime);
            showCalculationNotification(`Reading time calculated: ${readingTime} minute${readingTime !== 1 ? 's' : ''} (${wordCount} words)`);
        } else {
            showCalculationNotification('No content found to calculate reading time', 'error');
        }
    }

    /**
     * Show calculation notification
     * @param {string} message - The notification message
     * @param {string} type - The notification type
     */
    function showCalculationNotification(message, type = 'success') {
        const noticeClass = type === 'error' ? 'notice-error' : 'notice-success';
        const notice = `<div class="notice ${noticeClass} is-dismissible"><p>${message}</p></div>`;
        
        $('.wrap h1').after(notice);
        
        setTimeout(function() {
            $(notice).fadeOut(function() {
                $(this).remove();
            });
        }, 4000);
    }

    /**
     * Live character counters for SEO title / meta description fields
     */
    function initSeoCharCounters() {
        $('.wordiva-char-count').each(function() {
            const field = $(this);
            const max = parseInt(field.data('max'), 10) || 0;
            const label = field.nextAll('.wordiva-char-count-label').first();

            function update() {
                const len = field.val().length;
                label.text(len + ' / ' + max + ' characters');
                label.toggleClass('wordiva-char-count-over', max > 0 && len > max);
            }

            field.on('input', update);
            update();
        });
    }

    // Initialize all functionality when document is ready
    $(document).ready(function() {
        initMetaBoxes();
        initSeoCharCounters();
        
        /**
         * Initialize card color preview
         */
        (function initCardColorPreview() {
            const colorSelect = $('#wordiva_card_color');
            
            if (colorSelect.length) {
                // Add preview element
                colorSelect.after('<span class="wordiva-card-color-preview" id="color-preview"></span>');
                
                // Handle color changes
                colorSelect.on('change', function() {
                    updateColorPreview($(this).val());
                });
                
                // Set initial preview
                updateColorPreview(colorSelect.val());
            }
        })();

        /**
         * Initialize excerpt length slider
         */
        (function initExcerptLengthSlider() {
            const excerptField = $('#wordiva_excerpt_length');
            
            if (excerptField.length) {
                const currentValue = excerptField.val() || 25;
                const sliderHtml = `
                    <div class="wordiva-excerpt-length-wrapper">
                        <input type="range" id="excerpt-length-slider" min="10" max="100" value="${currentValue}" step="5">
                        <span class="wordiva-excerpt-length-display">${currentValue} words</span>
                    </div>
                `;
                
                excerptField.after(sliderHtml).hide();
                
                $('#excerpt-length-slider').on('input', function() {
                    const value = $(this).val();
                    excerptField.val(value);
                    $('.wordiva-excerpt-length-display').text(value + ' words');
                });
            }
        })();

        /**
         * Initialize card layout preview
         */
        (function initCardLayoutPreview() {
            const layoutSelect = $('#wordiva_card_layout');
            
            if (layoutSelect.length) {
                const layouts = [
                    { value: 'default', label: 'Default' },
                    { value: 'horizontal', label: 'Horizontal' },
                    { value: 'minimal', label: 'Minimal' },
                    { value: 'image-focus', label: 'Image Focus' }
                ];
                
                let previewHtml = '<div class="wordiva-card-layout-preview">';
                
                layouts.forEach(function(layout) {
                    const checked = layoutSelect.val() === layout.value ? 'checked' : '';
                    previewHtml += `
                        <label class="wordiva-layout-option">
                            <input type="radio" name="wordiva_card_layout_visual" value="${layout.value}" ${checked}>
                            <div class="wordiva-layout-preview ${layout.value}"></div>
                            <span class="wordiva-layout-label">${layout.label}</span>
                        </label>
                    `;
                });
                
                previewHtml += '</div>';
                
                layoutSelect.after(previewHtml).hide();
                
                $('input[name="wordiva_card_layout_visual"]').on('change', function() {
                    layoutSelect.val($(this).val());
                });
            }
        })();

        /**
         * Initialize featured post controls
         */
        (function initFeaturedPostControls() {
            const featuredCheckbox = $('#wordiva_featured_post');
            const featuredLevel = $('#wordiva_featured_level');
            
            if (featuredCheckbox.length && featuredLevel.length) {
                function toggleFeaturedLevel() {
                    const levelRow = featuredLevel.closest('tr');
                    
                    if (featuredCheckbox.is(':checked')) {
                        levelRow.show();
                        if (featuredLevel.val() === 'none') {
                            featuredLevel.val('secondary');
                        }
                    } else {
                        levelRow.hide();
                        featuredLevel.val('none');
                    }
                }
                
                // Initialize state
                toggleFeaturedLevel();
                
                // Handle checkbox changes
                featuredCheckbox.on('change', toggleFeaturedLevel);
                
                // Handle level changes
                featuredLevel.on('change', function() {
                    updateFeaturedLevelIndicator($(this).val());
                });
                
                // Set initial indicator
                updateFeaturedLevelIndicator(featuredLevel.val());
            }
        })();

        /**
         * Initialize reading time calculator
         */
        (function initReadingTimeCalculator() {
            const readingTimeField = $('#wordiva_reading_time');
            
            if (readingTimeField.length) {
                // Wrap field and add controls
                readingTimeField.wrap('<div class="wordiva-reading-time-wrapper"></div>');
                readingTimeField.after('<span class="unit-label">minutes</span>');
                readingTimeField.after('<button type="button" class="button button-small" id="auto-calculate-reading-time">Auto Calculate</button>');
                
                // Handle auto-calculate button
                $('#auto-calculate-reading-time').on('click', function() {
                    calculateReadingTime();
                });
            }
        })();

        /**
         * Initialize field validation
         */
        (function initFieldValidation() {
            // Excerpt length validation
            $('#wordiva_excerpt_length').on('input', function() {
                const value = parseInt($(this).val());
                const field = $(this);
                
                if (value < 10 || value > 100) {
                    field.css('border-color', '#dc3232');
                    showCalculationNotification('Excerpt length should be between 10 and 100 words', 'error');
                } else {
                    field.css('border-color', '#2F80FF');
                }
            });
            
            // Reading time validation
            $('#wordiva_reading_time').on('input', function() {
                const value = parseInt($(this).val());
                const field = $(this);
                
                if (value < 1 || value > 60) {
                    field.css('border-color', '#dc3232');
                    showCalculationNotification('Reading time should be between 1 and 60 minutes', 'error');
                } else {
                    field.css('border-color', '#2F80FF');
                }
            });
        })();

        /**
         * Initialize accessibility enhancements
         */
        (function initAccessibilityEnhancements() {
            // Add ARIA descriptions
            $('#wordiva_featured_post').attr('aria-describedby', 'featured-post-description');
            $('#wordiva_card_color').attr('aria-describedby', 'card-color-description');
            $('#wordiva_excerpt_length').attr('aria-describedby', 'excerpt-length-description');
            
            // Keyboard navigation for layout options
            $('.wordiva-layout-option').on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $(this).find('input[type="radio"]').prop('checked', true).trigger('change');
                }
            });
            
            // Focus indicators for layout options
            $('.wordiva-layout-option').on('focus', function() {
                $(this).find('.wordiva-layout-preview').css('box-shadow', '0 0 0 3px rgba(47, 128, 255, 0.3)');
            }).on('blur', function() {
                $(this).find('.wordiva-layout-preview').css('box-shadow', '');
            });
        })();
    });

    // Initialize additional admin functionality
    $(document).ready(function() {
        /**
         * Initialize meta box state persistence
         */
        (function initMetaBoxStatePersistence() {
            // Save meta box state
            $('.postbox .handlediv').on('click', function() {
                const metaBox = $(this).parent();
                const isOpen = !metaBox.hasClass('closed');
                localStorage.setItem('wordiva_metabox_' + metaBox.attr('id'), isOpen ? 'open' : 'closed');
            });
            
            // Restore meta box state
            $('.postbox').each(function() {
                const metaBox = $(this);
                const state = localStorage.getItem('wordiva_metabox_' + metaBox.attr('id'));
                
                if (state === 'closed') {
                    metaBox.addClass('closed');
                }
            });
        })();

        /**
         * Initialize WordPress postbox functionality
         */
        (function initWordPressPostboxes() {
            if (typeof postboxes !== 'undefined') {
                postboxes.add_postbox_toggles('post');
            }
        })();

        /**
         * Initialize custom excerpt preview
         */
        (function initCustomExcerptPreview() {
            const excerptField = $('#wordiva_custom_excerpt');
            
            if (excerptField.length) {
                function updatePreview() {
                    const content = excerptField.val();
                    const preview = content ? 
                        content.substring(0, 150) + (content.length > 150 ? '...' : '') : 
                        'No custom excerpt set';
                    
                    $('.preview-text').text(preview);
                }
                
                // Add preview element
                excerptField.after('<div class="wordiva-excerpt-preview"><strong>Preview:</strong> <span class="preview-text"></span></div>');
                
                // Update preview on input
                excerptField.on('input', updatePreview);
                
                // Initialize preview
                updatePreview();
            }
        })();

        /**
         * Initialize character counter for excerpt
         */
        (function initExcerptCharacterCounter() {
            const excerptField = $('#wordiva_custom_excerpt');
            
            if (excerptField.length) {
                // Add character counter
                excerptField.after('<div class="character-counter"><span class="current">0</span> / <span class="max">300</span> characters</div>');
                
                // Update counter on input
                excerptField.on('input', function() {
                    const length = $(this).val().length;
                    $('.character-counter .current').text(length);
                    
                    // Color coding
                    if (length > 300) {
                        $('.character-counter').css('color', '#dc3232'); // Red
                    } else if (length > 250) {
                        $('.character-counter').css('color', '#ff9f1c'); // Orange
                    } else {
                        $('.character-counter').css('color', '#666'); // Gray
                    }
                });
                
                // Initialize counter
                excerptField.trigger('input');
            }
        })();
    });

    // Expose public methods
    window.WordivaAdmin = {
        updateColorPreview: updateColorPreview,
        calculateReadingTime: calculateReadingTime,
        showTemporaryNotification: showCalculationNotification
    };

})(jQuery);

// Fallback for when jQuery is not available
if (typeof jQuery === 'undefined') {
    document.addEventListener('DOMContentLoaded', function() {
        console.warn('Wordiva Admin: jQuery not found. Some features may not work properly.');
        
        // Basic functionality without jQuery
        const colorSelect = document.getElementById('wordiva_card_color');
        if (colorSelect) {
            colorSelect.addEventListener('change', function() {
                console.log('Card color changed to:', this.value);
            });
        }
    });
}