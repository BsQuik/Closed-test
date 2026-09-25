<?php
ini_set('display_errors', '0');
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/auth.php';

// Se l'utente non è autenticato, blocca le chiamate API
if (!isAuthenticated()) {
    http_response_code(401);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => false, 'error' => 'Accesso non autorizzato. Effettua il login.']);
    exit;
}

set_exception_handler(function ($e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => false, 'error' => 'Errore interno del server']);
    error_log('Brawl API exception: ' . $e->getMessage());
    exit;
});

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
        }
        echo json_encode(['ok' => false, 'error' => 'Errore fatale del server (controlla i log PHP)']);
    }
});

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/BrawlApi.php';

$action = $_GET['action'] ?? '';
$tag = $_GET['tag'] ?? '';

$api = new BrawlApi();

if ($action === 'brawlers') {
    $result = $api->getBrawlers();
    http_response_code($result['status'] > 0 ? $result['status'] : 500);
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($tag === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Parametro "tag" mancante']);
    exit;
}

$result = match ($action) {
    'player' => $api->getPlayer($tag),
    'battlelog' => $api->getPlayerBattlelog($tag),
    'club' => $api->getClub($tag),
    default => ['ok' => false, 'status' => 400, 'data' => null, 'error' => 'Azione non valida. Usa: player, battlelog, club, brawlers'],
};

http_response_code($result['status'] > 0 ? $result['status'] : 500);
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
