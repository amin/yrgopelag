<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/bootstrap.php';

header('Content-Type: Application/json');

try {
    echo json_encode([
        'features' => getFeaturePricing(),
        'rooms' => getRoomPricing(),
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'something went wrong']);
    exit;
}
