<?php
require_once __DIR__ . '/../private/github_stats.php';
$cacheFile = __DIR__ . '/../files/data/github_stats.json';


if (file_exists($cacheFile)) {
    $lastModified = filemtime($cacheFile);
    if (time() - $lastModified < 86400) {
        // less than a day old — no need to fetch
        exit("Up to date.");
    }
}

if (!file_exists($cacheFile)) {
    http_response_code(503);
    echo json_encode(['error' => 'No data available']);
    exit;
}

header('Content-Type: application/json');
echo file_get_contents($cacheFile);
