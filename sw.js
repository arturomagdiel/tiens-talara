// Service Worker para Tiens Talara PWA
const CACHE_NAME = 'tiens-talara-v1.1.0'; // ⬅️ Actualizada la versión
const urlsToCache = [
  '/talara/',
  '/talara/index.php',
  '/talara/compras/compras.php',
  '/talara/compras/compras.js',
  '/talara/compras/compras.css',
  '/talara/pedido/index.php',
  '/talara/pedido/styles.css',
  '/talara/pedido/scripts.js',
  '/talara/productos/index.php',
  '/talara/afiliados/index.php',
  '/talara/shared/header.php',
  '/talara/shared/conexion.php',
  // Bootstrap y dependencias externas
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
  'https://code.jquery.com/jquery-3.6.0.min.js',
  'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css'
];

// Instalación del Service Worker
self.addEventListener('install', function(event) {
  console.log('Service Worker: Instalando...');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        console.log('Service Worker: Archivos en cache');
        return cache.addAll(urlsToCache);
      })
      .catch(function(error) {
        console.log('Service Worker: Error al cachear:', error);
      })
  );
});

// Activación del Service Worker
self.addEventListener('activate', function(event) {
  console.log('Service Worker: Activando...');
  event.waitUntil(
    caches.keys().then(function(cacheNames) {
      return Promise.all(
        cacheNames.map(function(cacheName) {
          if (cacheName !== CACHE_NAME) {
            console.log('Service Worker: Eliminando cache antiguo:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
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