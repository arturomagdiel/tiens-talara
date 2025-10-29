<?php
header('Content-Type: application/javascript');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

require_once __DIR__ . '/config/version.php';
?>
// Service Worker para <?php echo getFullVersion(); ?> - Generado dinámicamente
const CACHE_NAME = '<?php echo getCacheName(); ?>';
const APP_VERSION = '<?php echo getVersion(); ?>';

console.log(`🌿 ${CACHE_NAME} - Service Worker: Instalando...`);

const urlsToCache = [
  '/talara/',
  '/talara/index.php',
  '/talara/login.php',
  '/talara/logout.php',
  '/talara/compras/index.php',
  '/talara/compras/compras.php',
  '/talara/compras/registrar_compra.php',
  '/talara/compras/registro_diario.php',
  '/talara/compras/compras.js',
  '/talara/compras/compras.css',
  '/talara/pedido/index.php',
  '/talara/pedido/styles.css',
  '/talara/pedido/scripts.js',
  '/talara/productos/index.php',
  '/talara/afiliados/index.php',
  '/talara/afiliados/index-modern.js',
  '/talara/shared/header.php',
  '/talara/shared/auth.php',
  '/talara/pwa-styles.css',
  '/talara/manifest.php',
  '/talara/images/apple-touch-icon.svg',
  '/talara/images/tiens.ico',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
  'https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css'
];

self.addEventListener('install', function(event) {
  console.log(`✅ Service Worker v${APP_VERSION}: Archivos en cache`);
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('activate', function(event) {
  console.log(`🔄 Service Worker v${APP_VERSION}: Activando...`);
  event.waitUntil(
    caches.keys().then(function(cacheNames) {
      return Promise.all(
        cacheNames.map(function(cacheName) {
          if (cacheName !== CACHE_NAME) {
            console.log(`🗑️ Service Worker: Eliminando cache antiguo: ${cacheName}`);
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => {
      console.log(`✅ Service Worker v${APP_VERSION}: Activado y tomando control`);
      return self.clients.claim();
    })
  );
});

self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request)
      .then(function(response) {
        if (response) {
          return response;
        }
        return fetch(event.request);
      }
    )
  );
});

// Manejo de actualizaciones
self.addEventListener('message', function(event) {
  if (event.data.action === 'skipWaiting') {
    self.skipWaiting();
  }
});