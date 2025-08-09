importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.5.4/workbox-sw.js');

if (workbox) {
console.log(`Workbox is loaded`);

workbox.precaching.cleanupOutdatedCaches();


workbox.precaching.precacheAndRoute([
{ url: '{{ url("/offline.html") }}', revision: 'v1.3' },
{ url: '{{ asset("assets/css/app.min.css") }}', revision: 'v1.3' },
{ url: '{{ asset("assets/js/app.min.js") }}', revision: 'v1.3' },
{ url: '{{ asset("assets/images/logo-192.png") }}', revision: 'v1.3' },

{ url: 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', revision: null },
{ url: 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', revision: null },
{ url: 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js', revision: null },
{ url: 'https://storage.googleapis.com/workbox-cdn/releases/6.5.4/workbox-sw.js', revision: null },
]);


workbox.routing.registerRoute(
({ request }) => request.mode === 'navigate',
new workbox.strategies.NetworkFirst({
cacheName: 'pages-cache',
plugins: [
new workbox.expiration.ExpirationPlugin({ maxEntries: 50 }),
],
})
);

workbox.routing.registerRoute(
({ request }) => request.destination === 'style' || request.destination === 'script',
new workbox.strategies.StaleWhileRevalidate({
cacheName: 'assets-cache',
})
);


workbox.routing.registerRoute(
({ request }) => request.destination === 'image',
new workbox.strategies.CacheFirst({
cacheName: 'images-cache',
plugins: [
new workbox.expiration.ExpirationPlugin({ maxEntries: 60, maxAgeSeconds: 30 * 24 * 60 * 60 }),
],
})
);


workbox.loadModule('workbox-background-sync');
const bgSyncPlugin = new workbox.backgroundSync.BackgroundSyncPlugin('api-requests-queue', {
maxRetentionTime: 24 * 60
});

workbox.routing.registerRoute(
({ request }) => request.method !== 'GET',
new workbox.strategies.NetworkOnly({
plugins: [bgSyncPlugin]
})
);

} else {
console.log(`Workbox didn't load`);
}
