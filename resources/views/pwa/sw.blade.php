
importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.5.4/workbox-sw.js');

if (workbox) {
console.log(`Workbox is loaded and running.`);
workbox.precaching.cleanupOutdatedCaches();

workbox.precaching.precacheAndRoute([
{ url: '{{ url("/offline.html") }}', revision: 'v2.2' },
{ url: '{{ asset("assets/css/app.min.css") }}', revision: 'v2.2' },
{ url: '{{ asset("assets/js/app.min.js") }}', revision: 'v2.2' },
{ url: '{{ asset("assets/images/logo-192.png") }}', revision: 'v2.2' },
{ url: 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', revision: null },
]);


const pageStrategy = new workbox.strategies.NetworkFirst({
cacheName: 'pages-cache',
plugins: [ new workbox.expiration.ExpirationPlugin({ maxEntries: 50 }) ]
});
workbox.routing.registerRoute(
({ request }) => request.mode === 'navigate',
async (args) => {
try {

return await pageStrategy.handle(args);
} catch (error) {

return caches.match('{{ url("/offline.html") }}');
}
}
);


workbox.routing.registerRoute(
({ request }) => request.destination === 'style' || request.destination === 'script' || request.destination === 'image',
new workbox.strategies.StaleWhileRevalidate({ cacheName: 'assets-cache' })
);



workbox.loadModule('workbox-background-sync');

const bgSyncPlugin = new workbox.backgroundSync.BackgroundSyncPlugin('offline-form-submissions', {
maxRetentionTime: 24 * 60
});

workbox.routing.registerRoute(

({ request }) => request.method === 'POST',
new workbox.strategies.NetworkOnly({
plugins: [bgSyncPlugin]
})
);

} else {
console.log(`Workbox failed to load.`);
}
