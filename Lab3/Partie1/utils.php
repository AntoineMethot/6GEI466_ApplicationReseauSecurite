<?php

// Base 404 return function
function send_404(string $path = ""): void
{
    http_response_code(404);
    header("Content-Type: text/html; charset=utf-8");

    $tpl = file_get_contents(__DIR__ . "/../templates/erreur404.html");

    $safePath = htmlspecialchars($path, ENT_QUOTES, "UTF-8");
    $tpl = str_replace("{{ path }}", $safePath, $tpl);

    echo $tpl;
    exit;
}

// Return static file
function serve_static_file(string $urlPath): void
{
    $relative = substr($urlPath, strlen("/static/"));

    $staticDir = __DIR__ . DIRECTORY_SEPARATOR . "static";

    // If the folder doesn't exist, return your 404 template
    if (!is_dir($staticDir)) {
        send_404($urlPath);
    }

    // Build the candidate path
    $candidate = $staticDir . DIRECTORY_SEPARATOR
        . str_replace("/", DIRECTORY_SEPARATOR, $relative);

    // Resolve to real path (prevents ../ traversal)
    $fullPath = realpath($candidate);

    // Resolve staticDir too (now it should work because it exists)
    $staticBase = realpath($staticDir);

    if (
        $fullPath === false ||
        $staticBase === false ||
        strncmp($fullPath, $staticBase, strlen($staticBase)) !== 0 ||
        !is_file($fullPath)
    ) {
        send_404($urlPath);
    }

    $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
    $mime = match ($ext) {
        "css"  => "text/css; charset=utf-8",
        "js"   => "application/javascript; charset=utf-8",
        "html" => "text/html; charset=utf-8",
        "png"  => "image/png",
        "jpg", "jpeg" => "image/jpeg",
        "gif"  => "image/gif",
        "svg"  => "image/svg+xml",
        "ico"  => "image/x-icon",
        default => "application/octet-stream",
    };

    http_response_code(200);
    header("Content-Type: $mime");
    readfile($fullPath);
    exit;
}


// Zodiac Sign calc
function zodiac_sign_from_date(DateTime $d): string
{
    $m = (int)$d->format("m");
    $day = (int)$d->format("d");

    $map = [
        1  => ["Capricorn", 19, "Aquarius"],
        2  => ["Aquarius", 18, "Pisces"],
        3  => ["Pisces", 20, "Aries"],
        4  => ["Aries", 19, "Taurus"],
        5  => ["Taurus", 20, "Gemini"],
        6  => ["Gemini", 20, "Cancer"],
        7  => ["Cancer", 22, "Leo"],
        8  => ["Leo", 22, "Virgo"],
        9  => ["Virgo", 22, "Libra"],
        10 => ["Libra", 22, "Scorpio"],   // <-- spelling fix vs Scorpius
        11 => ["Scorpio", 21, "Sagittarius"],
        12 => ["Sagittarius", 21, "Capricorn"],
    ];

    [$s1, $cut, $s2] = $map[$m];
    return ($day <= $cut) ? $s1 : $s2;
}
