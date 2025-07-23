<?php

$cacheFile = __DIR__ . '/../files/data/github_stats.json';

if (!file_exists($cacheFile)) {
    http_response_code(503);
    echo json_encode(['error' => 'No data available']);
    exit;
}

header('Content-Type: application/json');
echo file_get_contents($cacheFile);
