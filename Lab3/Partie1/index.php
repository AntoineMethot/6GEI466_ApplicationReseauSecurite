<?php
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri    = $_SERVER['REQUEST_URI'] ?? '/';
$path   = parse_url($uri, PHP_URL_PATH);

 // Send 404 template
function send_404(string $path = ""): void
{
    http_response_code(404);
    header("Content-Type: text/html; charset=utf-8");

    $tpl = file_get_contents(__DIR__ . "/templates/erreur404.html");

    // Escape path for safety
    $safePath = htmlspecialchars($path, ENT_QUOTES, "UTF-8");

    // Inject into template
    $tpl = str_replace("{{ path }}", $safePath, $tpl);

    echo $tpl;
    exit;
}

// Serve static files from /static/*
function serve_static_file(string $urlPath): void
{
    // "/static/js/app.js" → "js/app.js"
    $relative = substr($urlPath, strlen("/static/"));

    $staticBase = realpath(__DIR__ . DIRECTORY_SEPARATOR . "static");
    if ($staticBase === false) {
        http_response_code(500);
        echo "Static directory not found";
        exit;
    }

    // Build candidate path
    $candidate = __DIR__ . DIRECTORY_SEPARATOR . "static" . DIRECTORY_SEPARATOR
        . str_replace("/", DIRECTORY_SEPARATOR, $relative);

    $fullPath = realpath($candidate);

    // File must exist AND be inside /static
    if (
        $fullPath === false ||
        strncmp($fullPath, $staticBase, strlen($staticBase)) !== 0 ||
        !is_file($fullPath)
    ) {
        send_404($urlPath);
    }

    // Basic MIME types
    $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
    $mime = match ($ext) {
        "css"  => "text/css; charset=utf-8",
        "js"   => "application/javascript; charset=utf-8",
        "html" => "text/html; charset=utf-8",
        "png"  => "image/png",
        "jpg", "jpeg" => "image/jpeg",
        default => "application/octet-stream",
    };

    http_response_code(200);
    header("Content-Type: $mime");
    readfile($fullPath);
    exit;
}

// ROUTING

// 1️⃣ Static files
if (strncmp($path, "/static/", 8) === 0) {
    serve_static_file($path);
}

// 2️⃣ Root → accueil.html
if ($method === "GET" && $path === "/") {
    $html = file_get_contents(__DIR__ . "/templates/accueil.html");

    $titre = "HOROSCOPE";
    $html = str_replace("{{ titre }}", $titre, $html);

    header("Content-Type: text/html; charset=utf-8");
    echo $html;
    exit;
}

// 404
send_404($path);
