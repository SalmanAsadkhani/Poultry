const CACHE_NAME = 'poultry-app-v1';

const urlsToCache = [
    '/',
    'login',
    'offline.html',
    'panel/breeding/show/1',
    'panel/breeding',
    'assets/css/app.min.css',
    'assets/js/app.min.js',
    'assets/css/style.css',
    'assets/js/admin.js',
    'assets/images/logo.png'
];


self.addEventListener('install', event => {

    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
            .then(() => {
                return self.skipWaiting();
            })
    );
});
self.addEventListener('fetch', event => {

    if (event.request.method !== 'GET') {
        return;
    }

    event.respondWith(
        caches.open(CACHE_NAME).then(cache => {
            return cache.match(event.request).then(response => {

                const fetchPromise = fetch(event.request).then(networkResponse => {

                    if (networkResponse && networkResponse.status === 200) {
                        cache.put(event.request, networkResponse.clone());
                    }
                    return networkResponse;
                }).catch(() => {
                    return caches.match('/offline.html');
                });

                return response || fetchPromise;
            });
        })
    );
});
