/**
 * Wordiva Theme Service Worker
 * Provides basic caching for improved performance
 */

const CACHE_NAME = 'wordiva-v1.0.0';
const STATIC_CACHE = 'wordiva-static-v1';
const DYNAMIC_CACHE = 'wordiva-dynamic-v1';

// Assets to cache on install
const STATIC_ASSETS = [
    '/',
    '/wp-content/themes/wordiva-theme/assets/css/style.min.css',
    '/wp-content/themes/wordiva-theme/assets/css/accessibility.css',
    '/wp-content/themes/wordiva-theme/assets/js/main.min.js',
    '/wp-content/themes/wordiva-theme/assets/js/navigation.min.js',
    '/wp-content/themes/wordiva-theme/assets/images/fallback-featured.svg',
    '/wp-content/themes/wordiva-theme/assets/images/fallback-card.svg'
];

// Install event - cache static assets
self.addEventListener('install', function(event) {
    console.log('Service Worker: Installing...');
    event.waitUntil(
        caches.open(STATIC_CACHE)
        .then(function(cache) {
            console.log('Service Worker: Caching static assets');
            return cache.addAll(STATIC_ASSETS);
        })
        .then(() => self.skipWaiting())
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', function(event) {
    console.log('Service Worker: Activating...');
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
                        console.log('Service Worker: Deleting old cache', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', function(event) {
    const requestUrl = new URL(event.request.url);
    
    // Skip non-GET requests
    if (event.request.method !== 'GET') {
        return;
    }
    
    // Skip admin and login pages
    if (requestUrl.pathname.includes('/wp-admin/') || 
        requestUrl.pathname.includes('/wp-login.php')) {
        return;
    }
    
    event.respondWith(
        caches.match(event.request)
        .then(function(cachedResponse) {
            // Return cached version if available
            if (cachedResponse) {
                return cachedResponse;
            }
            
            // Otherwise fetch from network
            return fetch(event.request)
            .then(function(networkResponse) {
                // Don't cache if not a valid response
                if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') {
                    return networkResponse;
                }
                
                // Clone the response
                const responseToCache = networkResponse.clone();
                
                // Cache dynamic content
                if (shouldCache(event.request)) {
                    caches.open(DYNAMIC_CACHE)
                    .then(function(cache) {
                        cache.put(event.request, responseToCache);
                    });
                }
                
                return networkResponse;
            })
            .catch(function() {
                // Fallback for offline scenarios
                if (event.request.destination === 'document') {
                    return caches.match('/offline.html');
                }
                
                // Fallback for images
                if (event.request.destination === 'image') {
                    return caches.match('/wp-content/themes/wordiva-theme/assets/images/fallback-card.svg');
                }
            });
        })
    );
});

// Helper function to determine if request should be cached
function shouldCache(request) {
    const url = new URL(request.url);
    
    // Cache CSS, JS, images, and fonts
    if (request.destination === 'style' || 
        request.destination === 'script' || 
        request.destination === 'image' || 
        request.destination === 'font') {
        return true;
    }
    
    // Cache HTML pages (but not admin pages)
    if (request.destination === 'document' && 
        !url.pathname.includes('/wp-admin/') && 
        !url.pathname.includes('/wp-login.php')) {
        return true;
    }
    
    return false;
}

// Background sync for form submissions (if supported)
self.addEventListener('sync', function(event) {
    if (event.tag === 'background-sync') {
        event.waitUntil(doBackgroundSync());
    }
});

function doBackgroundSync() {
    // Handle background sync tasks
    return Promise.resolve();
}

// Push notification handling (if needed in future)
self.addEventListener('push', function(event) {
    if (event.data) {
        const data = event.data.json();
        const options = {
            body: data.body,
            icon: '/wp-content/themes/wordiva-theme/assets/images/icon-192x192.png',
            badge: '/wp-content/themes/wordiva-theme/assets/images/badge-72x72.png',
            vibrate: [100, 50, 100],
            data: {
                dateOfArrival: Date.now(),
                primaryKey: data.primaryKey
            }
        };
        
        event.waitUntil(
            self.registration.showNotification(data.title, options)
        );
    }
});

// Handle notification clicks
self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    
    event.waitUntil(
        clients.openWindow('/')
    );
});