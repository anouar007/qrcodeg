const CACHE_NAME = 'qrstudio-pos-v1';
const STATIC_ASSETS = [
    '/',
    '/client/loyalty/terminal',
    '/manifest.json',
    '/images/icon-192.png',
    '/images/icon-512.png',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
    'https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js'
];

// Install: pre-cache essential assets
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(STATIC_ASSETS).catch(err => {
                console.warn('Pre-cache error (ignored):', err);
            });
        }).then(() => self.skipWaiting())
    );
});

// Activate: clean up old cache versions
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys => {
            return Promise.all(
                keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key))
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch: Network first with Cache fallback for seamless POS continuity
self.addEventListener('fetch', event => {
    // Only handle GET requests and skip API POSTs
    if (event.request.method !== 'GET') {
        return;
    }

    const url = new URL(event.request.url);

    // Bypass browser-sync or dev routes
    if (url.pathname.startsWith('/_boost') || url.pathname.includes('/storage/')) {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then(networkResponse => {
                // If valid response, clone to cache for future offline access
                if (networkResponse && networkResponse.status === 200) {
                    const responseClone = networkResponse.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseClone);
                    });
                }
                return networkResponse;
            })
            .catch(() => {
                // Return cached version if offline
                return caches.match(event.request);
            })
    );
});

