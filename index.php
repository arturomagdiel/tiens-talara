<!-- filepath: c:\Users\artur\Documents\GitHub\tiens-talara\index.php -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiens Talara v1.4.5 - Sistema de Gestión</title>  <!-- PWA Meta Tags -->
  <meta name="description" content="Sistema de gestión de compras, productos y afiliados para Tiens Talara">
  <meta name="theme-color" content="#0d6efd">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Tiens Talara">
  
  <!-- PWA Links -->
  <link rel="manifest" href="manifest.json">
  <link rel="apple-touch-icon" href="images/apple-touch-icon.svg">
  <link rel="icon" type="image/x-icon" href="images/tiens.ico">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="pwa-styles.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    .main-container {
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      padding: 2rem;
      margin-top: 2rem;
      margin-bottom: 2rem;
    }
    
    .header-section {
      text-align: center;
      margin-bottom: 2rem;
      color: white;
    }
    
    .logo-icon {
      font-size: 4rem;
      color: #28a745;
      margin-bottom: 1rem;
      text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
    
    .main-title {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .subtitle {
      font-size: 1.1rem;
      opacity: 0.9;
      margin-bottom: 1rem;
    }
    
    .version-badge {
      background: linear-gradient(45deg, #28a745, #20c997);
      border: none;
      border-radius: 25px;
      padding: 8px 16px;
      font-weight: 600;
      box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }
    
    .option-card {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 20px;
      padding: 2rem 1rem;
      text-align: center;
      transition: all 0.3s ease;
      color: white;
      text-decoration: none !important;
      display: block;
      height: 100%;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .option-card:hover {
      transform: translateY(-5px) scale(1.02);
      background: rgba(255, 255, 255, 0.25);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
      border-color: rgba(255, 255, 255, 0.5);
      color: white;
    }
    
    .option-icon {
      font-size: 3rem;
      margin-bottom: 1rem;
      display: block;
      color: #ffffff;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .option-title {
      font-size: 1.1rem;
      font-weight: 600;
      margin: 0;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }
    
    .install-btn {
      background: linear-gradient(45deg, #ff6b6b, #ee5a52);
      border: none;
      border-radius: 50px;
      color: white;
      padding: 12px 20px;
      font-weight: 600;
      box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
      transition: all 0.3s ease;
    }
    
    .install-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(255, 107, 107, 0.6);
      color: white;
    }
    
    .version-indicator {
      position: fixed;
      bottom: 10px;
      left: 10px;
      background: rgba(255, 255, 255, 0.2);
      color: white;
      padding: 5px 10px;
      border-radius: 15px;
      font-size: 0.8rem;
      backdrop-filter: blur(5px);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    /* Animaciones */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .option-card {
      animation: fadeInUp 0.6s ease forwards;
    }
    
    .option-card:nth-child(1) { animation-delay: 0.1s; }
    .option-card:nth-child(2) { animation-delay: 0.2s; }
    .option-card:nth-child(3) { animation-delay: 0.3s; }
    .option-card:nth-child(4) { animation-delay: 0.4s; }
    
    /* Responsive */
    @media (max-width: 768px) {
      .main-container {
        margin: 1rem;
        padding: 1.5rem;
      }
      
      .main-title {
        font-size: 2rem;
      }
      
      .option-icon {
        font-size: 2.5rem;
      }
      
      .option-title {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="main-container">
      <!-- Header Section -->
      <div class="header-section">
        <i class="bi bi-leaf logo-icon"></i>
        <h1 class="main-title">Tiens Talara</h1>
        <p class="subtitle">Sistema de Gestión Integral</p>
        <span class="badge version-badge">v1.4.5</span>
      </div>
      
      <!-- Options Grid -->
      <div class="row g-4">
        <!-- Pedidos -->
        <div class="col-6 col-md-3">
          <a href="pedido/" class="option-card">
            <i class="bi bi-cart3 option-icon"></i>
            <div class="option-title">Pedidos</div>
          </a>
        </div>
        
        <!-- Compras -->
        <div class="col-6 col-md-3">
          <a href="compras/" class="option-card">
            <i class="bi bi-box-seam option-icon"></i>
            <div class="option-title">Compras</div>
          </a>
        </div>
        
        <!-- Mantenimiento de Productos -->
        <div class="col-6 col-md-3">
          <a href="productos/" class="option-card">
            <i class="bi bi-tags option-icon"></i>
            <div class="option-title">Productos</div>
          </a>
        </div>
        
        <!-- Mantenimiento de Afiliados -->
        <div class="col-6 col-md-3">
          <a href="afiliados/" class="option-card">
            <i class="bi bi-people option-icon"></i>
            <div class="option-title">Afiliados</div>
          </a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- PWA Service Worker Registration -->
  <script>
    // Registrar Service Worker para PWA
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('sw.js')
          .then(function(registration) {
            console.log('✅ Tiens Talara v1.4.5 - Service Worker registrado:', registration.scope);
          })
          .catch(function(error) {
            console.log('❌ Error al registrar Service Worker:', error);
          });
      });
    }
    
    // Mostrar versión en consola
    console.log('%c🌿 Tiens Talara v1.4.5', 'color: #28a745; font-size: 16px; font-weight: bold;');
    console.log('Sistema de Gestión - PWA v1.4.5 con UX Limpia y Iconos Simples');

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
      // Crear botón de instalación moderno
      const installBtn = document.createElement('button');
      installBtn.className = 'install-btn position-fixed';
      installBtn.style.cssText = 'bottom: 20px; right: 20px; z-index: 1000;';
      installBtn.innerHTML = '<i class="bi bi-download"></i> Instalar App';
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
        const installBtn = document.querySelector('.install-btn');
        if (installBtn) {
          installBtn.style.transform = 'scale(0)';
          setTimeout(() => installBtn.remove(), 300);
        }
      }
    }
    
    // Animación de entrada suave
    document.addEventListener('DOMContentLoaded', function() {
      const mainContainer = document.querySelector('.main-container');
      mainContainer.style.opacity = '0';
      mainContainer.style.transform = 'translateY(20px)';
      
      setTimeout(() => {
        mainContainer.style.transition = 'all 0.6s ease';
        mainContainer.style.opacity = '1';
        mainContainer.style.transform = 'translateY(0)';
      }, 100);
    });
  </script>
  
  <!-- Indicador de versión para PWA -->
  <div class="version-indicator">v1.4.5</div>
</body>
</html>