<?php


// Memuat autoloader Composer agar class MongoDB\Client dikenali
require_once __DIR__ . '/vendor/autoload.php';

use MongoDB\Client;
use MongoDB\Driver\ServerApi;

try {
    $uri = "mongodb://127.0.0.1:27017";

    $apiVersion = new ServerApi(ServerApi::V1);
    $client = new Client($uri, [], ['serverApi' => $apiVersion]);

    $database = $client->selectDatabase('nexus_ai_db');

    $auditCollection  = $database->selectCollection('audit_logs');
    $modelsCollection = $database->selectCollection('model_registry');

} catch (Exception $e) {
    // Tangani jika terjadi kegagalan koneksi
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'status'  => 'error',
        'message' => 'Gagal terhubung ke MongoDB Server: ' . $e->getMessage()
    ]);
    exit;
}