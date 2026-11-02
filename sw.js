const CACHE_NAME = 'bulls-fitness-v3';
const ASSETS_TO_CACHE = [
  './',
  './index.php',
  './style.css',
  './mobile.css',
  './manifest.json',
  './img/logo.png',
  './img/icon-192.png',
  './img/icon-512.png'
];

// Install Event
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(ASSETS_TO_CACHE);
    }).then(() => self.skipWaiting())
  );
});

// Activate Event: bersihkan cache lama
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cache) => {
          if (cache !== CACHE_NAME) {
            return caches.delete(cache);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch Event
self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);

  // PENTING: JANGAN PERNAH CACHE halaman otentikasi, login, otp, form submit, atau request non-GET
  if (
    event.request.method !== 'GET' ||
    url.pathname.endsWith('login.php') ||
    url.pathname.endsWith('verify.php') ||
    url.pathname.endsWith('logout.php') ||
    url.pathname.endsWith('register.php') ||
    url.pathname.endsWith('process_payment.php') ||
    url.pathname.endsWith('admin.php')
  ) {
    return; // Biarkan langsung ke server / PHP backend tanpa diintersepsi Service Worker
  }

  // Network First untuk file statis / GET
  event.respondWith(
    fetch(event.request)
      .then((response) => {
        if (response && response.status === 200 && response.type === 'basic') {
          const responseToCache = response.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(event.request, responseToCache);
          });
        }
        return response;
      })
      .catch(() => caches.match(event.request))
  );
});
