<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$method = $_SERVER['REQUEST_METHOD'];

// Ambil statistik agregasi jika ada query ?action=stats
if ($method === 'GET' && isset($_GET['action']) && $_GET['action'] === 'stats') {
    try {
        $pipeline = [
            [
                '$group' => [
                    '_id'          => null,
                    'total_runs'   => ['$sum' => 1],
                    'avg_latency'  => ['$avg' => '$latency_ms'],
                    'total_tokens' => ['$sum' => '$generated_tokens']
                ]
            ]
        ];

        $statsCursor = $auditCollection->aggregate($pipeline)->toArray();
        $stats = !empty($statsCursor) ? $statsCursor[0] : [
            'total_runs'   => 0,
            'avg_latency'  => 0,
            'total_tokens' => 0
        ];

        echo json_encode([
            'status' => 'success',
            'data'   => [
                'total_runs'   => $stats['total_runs'],
                'avg_latency'  => round($stats['avg_latency'] ?? 0, 1),
                'total_tokens' => $stats['total_tokens'] ?? 0
            ]
        ]);
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// Ambil 10 audit log terbaru dari MongoDB
if ($method === 'GET') {
    try {
        $cursor = $auditCollection->find([], [
            'sort' => ['_id' => -1],
            'limit' => 10
        ]);

        $logs = [];
        foreach ($cursor as $doc) {
            $logs[] = [
                'id'               => (string)$doc['_id'],
                'model_name'       => $doc['model_name'] ?? 'Nexus-Model',
                'latency_ms'       => $doc['latency_ms'] ?? 0,
                'generated_tokens' => $doc['generated_tokens'] ?? 0,
                'status'           => $doc['status'] ?? 'SUCCESS'
            ];
        }

        echo json_encode(['status' => 'success', 'data' => $logs]);
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// Simpan data inferensi baru ke MongoDB
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $modelName   = $input['model_name'] ?? 'Nexus-Llama-3-8B';
    $temperature = floatval($input['temperature'] ?? 0.7);
    $prompt      = trim($input['prompt'] ?? '');
    $precision   = $input['precision'] ?? 'FP16';

    if (empty($prompt)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Prompt tidak boleh kosong']);
        exit;
    }

    // Kalkulasi metrik simulasi
    $latencyMs      = rand(45, 160);
    $totalTokens    = ceil(strlen($prompt) / 4) + rand(30, 90);
    $responseSample = "Precision: {$precision} | Latency: {$latencyMs}ms | Temperature: {$temperature}.";

    // Struktur dokumen BSON MongoDB
    $doc = [
        'timestamp'        => new MongoDB\BSON\UTCDateTime(),
        'model_name'       => $modelName,
        'temperature'      => $temperature,
        'precision'        => $precision,
        'prompt'           => $prompt,
        'response_sample'  => $responseSample,
        'generated_tokens' => $totalTokens,
        'latency_ms'       => $latencyMs,
        'status'           => 'SUCCESS'
    ];

    try {
        $result = $auditCollection->insertOne($doc);

        echo json_encode([
            'status' => 'success',
            'data'   => [
                'id'              => (string)$result->getInsertedId(),
                'model_name'      => $modelName,
                'precision'       => $precision,
                'latency_ms'      => $latencyMs,
                'tokens'          => $totalTokens,
                'response_sample' => $responseSample
            ]
        ]);
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// Hapus dokumen berdasarkan _id (ObjectId)
if ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'ID wajib disertakan']);
        exit;
    }

    try {
        // Konversi string heksadesimal ke BSON ObjectId
        $deleteResult = $auditCollection->deleteOne([
            '_id' => new MongoDB\BSON\ObjectId($id)
        ]);

        if ($deleteResult->getDeletedCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Log berhasil dihapus']);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Dokumen tidak ditemukan']);
        }
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Format ID tidak valid atau error: ' . $e->getMessage()]);
        exit;
    }
}

http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);