<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

require_once __DIR__ . '/config/version.php';

$manifest = [
  "name" => getFullVersion(),
  "short_name" => "Tiens v" . getVersion(),
  "description" => APP_DESCRIPTION . " v" . getVersion(),
  "start_url" => "/talara/",
  "display" => "standalone",
  "background_color" => "#ffffff",
  "theme_color" => "#0d6efd",
  "orientation" => "portrait-primary",
  "scope" => "/talara/",
  "icons" => [
    [
      "src" => "images/apple-touch-icon.svg",
      "sizes" => "192x192",
      "type" => "image/svg+xml",
      "purpose" => "any maskable"
    ],
    [
      "src" => "images/tiens.ico",
      "sizes" => "48x48 72x72 96x96 128x128 256x256",
      "type" => "image/x-icon"
    ]
  ],
  "screenshots" => [
    [
      "src" => "images/screenshot1.png",
      "sizes" => "540x720",
      "type" => "image/png",
      "form_factor" => "narrow"
    ],
    [
      "src" => "images/screenshot2.png", 
      "sizes" => "1024x593",
      "type" => "image/png",
      "form_factor" => "wide"
    ]
  ],
  "categories" => ["business", "productivity", "utilities"],
  "lang" => "es",
  "dir" => "ltr"
];

echo json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>