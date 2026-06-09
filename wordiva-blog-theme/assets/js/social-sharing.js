/**
 * Social Sharing JavaScript for Wordiva Theme
 * 
 * @package Wordiva_Theme
 * @since 1.0.0
 */

(function() {
    'use strict';
    
    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initSocialSharing();
    });
    
    /**
     * Initialize social sharing functionality
     */
    function initSocialSharing() {
        const shareButtons = document.querySelectorAll('.social-share-button');
        
        shareButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const platform = this.dataset.platform;
                const url = encodeURIComponent(this.dataset.url || window.location.href);
                const title = encodeURIComponent(this.dataset.title || document.title);
                const text = encodeURIComponent(this.dataset.text || '');
                
                let shareUrl = '';
                
                switch (platform) {
                    case 'twitter':
                        shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                        break;
                    case 'facebook':
                        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                        break;
                    case 'linkedin':
                        shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`;
                        break;
                    case 'email':
                        shareUrl = `mailto:?subject=${title}&body=${text}%20${url}`;
                        break;
                    default:
                        return;
                }
                
                if (platform === 'email') {
                    window.location.href = shareUrl;
                } else {
                    openShareWindow(shareUrl, platform);
                }
                
                // Track sharing event (if analytics is available)
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'share', {
                        method: platform,
                        content_type: 'article',
                        item_id: this.dataset.postId || ''
                    });
                }
            });
        });
        
        // Add native Web Share API support if available
        if (navigator.share) {
            const nativeShareButtons = document.querySelectorAll('.native-share-button');
            
            nativeShareButtons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const shareData = {
                        title: this.dataset.title || document.title,
                        text: this.dataset.text || '',
                        url: this.dataset.url || window.location.href
                    };
                    
                    navigator.share(shareData).catch(function(error) {
                        console.log('Error sharing:', error);
                    });
                });
            });
        } else {
            // Hide native share buttons if not supported
            const nativeShareButtons = document.querySelectorAll('.native-share-button');
            nativeShareButtons.forEach(function(button) {
                button.style.display = 'none';
            });
        }
    }
    
    /**
     * Open share window with proper dimensions
     */
    function openShareWindow(url, platform) {
        const windowFeatures = getWindowFeatures(platform);
        const shareWindow = window.open(url, 'share', windowFeatures);
        
        if (shareWindow) {
            shareWindow.focus();
        }
    }
    
    /**
     * Get appropriate window features for each platform
     */
    function getWindowFeatures(platform) {
        const baseFeatures = 'scrollbars=yes,resizable=yes,toolbar=no,location=yes';
        
        switch (platform) {
            case 'twitter':
                return `${baseFeatures},width=550,height=420`;
            case 'facebook':
                return `${baseFeatures},width=626,height=436`;
            case 'linkedin':
                return `${baseFeatures},width=550,height=550`;
            default:
                return `${baseFeatures},width=600,height=500`;
        }
    }
    
})();