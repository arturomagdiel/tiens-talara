<!-- filepath: c:\Users\artur\Documents\GitHub\tiens-talara\index.php -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio - Sistema Tiens Talara</title>
  
  <!-- PWA Meta Tags -->
  <meta name="description" content="Sistema de gestión de compras, productos y afiliados para Tiens Talara">
  <meta name="theme-color" content="#0d6efd">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Tiens Talara">
  
  <!-- PWA Links -->
  <link rel="manifest" href="manifest.json">
  <link rel="apple-touch-icon" href="images/icon-192.png">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="pwa-styles.css" rel="stylesheet">
  <style>
    .option-card {
      text-align: center;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 10px;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .option-card:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .option-icon {
      font-size: 4rem;
      color: #007bff;
    }
    .option-title {
      margin-top: 10px;
      font-size: 1.5rem;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <h1 class="text-center mb-4">Bienvenido al Sistema</h1>
    <div class="row g-4">
      <!-- Pedidos -->
      <div class="col-md-3">
        <a href="pedido/" class="text-decoration-none">
          <div class="option-card">
            <i class="fas fa-shopping-cart option-icon"></i>
            <div class="option-title">Pedidos</div>
          </div>
        </a>
      </div>
      <!-- Compras -->
      <div class="col-md-3">
        <a href="compras/" class="text-decoration-none">
          <div class="option-card">
            <i class="fas fa-box option-icon"></i>
            <div class="option-title">Compras</div>
          </div>
        </a>
      </div>
      <!-- Mantenimiento de Productos -->
      <div class="col-md-3">
        <a href="productos/" class="text-decoration-none">
          <div class="option-card">
            <i class="fas fa-tags option-icon"></i>
            <div class="option-title">Mant. Productos</div>
          </div>
        </a>
      </div>
      <!-- Mantenimiento de Afiliados -->
      <div class="col-md-3">
        <a href="afiliados/" class="text-decoration-none">
          <div class="option-card">
            <i class="fas fa-users option-icon"></i>
            <div class="option-title">Mant. Afiliados</div>
          </div>
        </a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
  
  <!-- PWA Service Worker Registration -->
  <script>
    // Registrar Service Worker para PWA
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('sw.js')
          .then(function(registration) {
            console.log('Service Worker registrado correctamente:', registration.scope);
          })
          .catch(function(error) {
            console.log('Error al registrar Service Worker:', error);
          });
      });
    }

    // PWA Install Prompt
    let deferredPrompt;
    window.addEventListener('beforeinstallprompt', (e) => {
      e.preventDefault();
      deferredPrompt = e;
      
      // Mostrar botón de instalación si no está ya instalado
      if (!window.matchMedia('(display-mode: standalone)').matches) {
        showInstallButton();
      }
    });

    function showInstallButton() {
      // Crear botón de instalación discreto
      const installBtn = document.createElement('button');
      installBtn.className = 'btn btn-primary btn-sm position-fixed';
      installBtn.style.cssText = 'bottom: 20px; right: 20px; z-index: 1000; border-radius: 50px; padding: 10px 15px;';
      installBtn.innerHTML = '<i class="fas fa-download"></i> Instalar App';
      installBtn.onclick = installApp;
      document.body.appendChild(installBtn);
    }

    async function installApp() {
      if (deferredPrompt) {
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        console.log('Resultado de instalación:', outcome);
        deferredPrompt = null;
        
        // Ocultar botón después de la instalación
        const installBtn = document.querySelector('button[onclick="installApp()"]');
        if (installBtn) installBtn.remove();
      }
    }
  </script>
</body>
</html>