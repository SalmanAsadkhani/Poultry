
importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.5.4/workbox-sw.js');

if (workbox) {

workbox.precaching.precacheAndRoute([
{ url: '{{ url("/") }}', revision: 'v1.2' },
{ url: '{{ url("/offline.html") }}', revision: 'v1.2' },
{ url: '{{ asset("assets/css/app.min.css") }}', revision: 'v1.2' },
{ url: '{{ asset("assets/js/app.min.js") }}', revision: 'v1.2' },
{ url: '{{ asset("assets/images/logo-192.png") }}', revision: 'v1.2' },
]);




workbox.routing.registerRoute(
({ request }) => request.mode === 'navigate',
new workbox.strategies.NetworkFirst({ cacheName: 'pages-cache' })
);


workbox.routing.registerRoute(
({ request }) => request.destination === 'style' || request.destination === 'script',
new workbox.strategies.StaleWhileRevalidate({ cacheName: 'assets-cache' })
);


workbox.routing.registerRoute(
({ request }) => request.destination === 'image',
new workbox.strategies.CacheFirst({ cacheName: 'images-cache' })
);

}
