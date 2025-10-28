// Service Worker para Tiens Talara PWA v1.4.2
const CACHE_NAME = 'tiens-talara-v1.4.2';
const APP_VERSION = '1.4.2';
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
  '/talara/shared/header_compras.php',
  '/talara/shared/header_afiliados.php',
  '/talara/shared/auth.php',
  '/talara/shared/conexion.php',
  '/talara/pwa-styles.css',
  // Bootstrap y dependencias externas
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
  'https://code.jquery.com/jquery-3.6.0.min.js',
  'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css'
];

// Instalación del Service Worker
self.addEventListener('install', function(event) {
  console.log(`🌿 Tiens Talara v${APP_VERSION} - Service Worker: Instalando...`);
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        console.log(`✅ Service Worker v${APP_VERSION}: Archivos en cache`);
        return cache.addAll(urlsToCache);
      })
      .catch(function(error) {
        console.log('❌ Service Worker: Error al cachear:', error);
      })
  );
  // Forzar activación inmediata
  self.skipWaiting();
});

// Activación del Service Worker
self.addEventListener('activate', function(event) {
  console.log(`🔄 Service Worker v${APP_VERSION}: Activando...`);
  event.waitUntil(
    caches.keys().then(function(cacheNames) {
      return Promise.all(
        cacheNames.map(function(cacheName) {
          if (cacheName !== CACHE_NAME) {
            console.log('🗑️ Service Worker: Eliminando cache antiguo:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    }).then(function() {
      console.log(`✅ Service Worker v${APP_VERSION}: Activado y tomando control`);
      return self.clients.claim(); // Tomar control inmediatamente
    })
  );
});

// Interceptar peticiones de red
self.addEventListener('fetch', function(event) {
  // Solo cachear peticiones GET
  if (event.request.method !== 'GET') {
    return;
  }

  event.respondWith(
    caches.match(event.request)
      .then(function(response) {
        // Si está en cache, devolver desde cache
        if (response) {
          return response;
        }

        // Si no está en cache, hacer petición de red
        return fetch(event.request).then(function(response) {
          // Verificar si la respuesta es válida
          if (!response || response.status !== 200 || response.type !== 'basic') {
            return response;
          }

          // Clonar la respuesta para cache
          var responseToCache = response.clone();

          caches.open(CACHE_NAME)
            .then(function(cache) {
              cache.put(event.request, responseToCache);
            });

          return response;
        }).catch(function() {
          // Si no hay red, mostrar página offline básica
          if (event.request.mode === 'navigate') {
            return caches.match('/talara/');
          }
        });
      })
  );
});