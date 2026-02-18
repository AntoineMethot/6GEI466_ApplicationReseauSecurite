<?php
require __DIR__ . "/utils.php";
$HOROSCOPES = require __DIR__ . "/horoscopes.php";

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri    = $_SERVER['REQUEST_URI'] ?? '/';
$path   = parse_url($uri, PHP_URL_PATH);


// 1) /static/*
if (strncmp($path, "/static/", 8) === 0) {
    serve_static_file($path);
}

// 2) GET /
if ($method === "GET" && $path === "/") {
    $html = file_get_contents(__DIR__ . "/templates/accueil.html");
    $html = str_replace("{{ titre }}", "HOROSCOPE", $html);

    header("Content-Type: text/html; charset=utf-8");
    echo $html;
    exit;
}

// 3) POST /horoscope
if ($method === "POST" && $path === "/horoscope") {
    $prenom = trim($_POST["prenom"] ?? "");
    $nom    = trim($_POST["nom"] ?? "");
    $dateS  = trim($_POST["date"] ?? "");

    if ($prenom === "" || $nom === "" || $dateS === "") {
        http_response_code(400);
        header("Content-Type: text/plain; charset=utf-8");
        echo "parametre manquant";
        exit;
    }

    $d = DateTime::createFromFormat("Y-m-d", $dateS);
    $errors = DateTime::getLastErrors();
    if (
        $d === false ||
        ($errors["warning_count"] ?? 0) > 0 ||
        ($errors["error_count"] ?? 0) > 0
    ) {
        http_response_code(400);
        header("Content-Type: text/plain; charset=utf-8");
        echo "date invalide";
        exit;
    }

    $sign = zodiac_sign_from_date($d);

    if (!isset($HOROSCOPES[$sign])) {
        http_response_code(500);
        header("Content-Type: text/plain; charset=utf-8");
        echo "Horoscope not found";
        exit;
    }

    http_response_code(200);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode([
        "prenom" => $prenom,
        "nom"    => $nom,
        "sign"   => $sign,
        "image"  => $HOROSCOPES[$sign]["image"],
        "text"   => $HOROSCOPES[$sign]["text"],
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 4) everything else
send_404($path);
