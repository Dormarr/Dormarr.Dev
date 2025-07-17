<?php
$projectDir = __DIR__ . '/demo';
$projectFiles = glob($projectDir . '/*.php');

function extractMeta($filePath) {
    $contents = file_get_contents($filePath);
    preg_match('/<!--(.*?)-->/s', $contents, $matches);
    
    $meta = [
        'title' => 'Untitled',
        'description' => '',
        'image' => '',
        'link' => basename($filePath)
    ];
    
    if (isset($matches[1])) {
        foreach (explode("\n", trim($matches[1])) as $line) {
            if (strpos($line, ':') !== false) {
                [$key, $value] = explode(':', $line, 2);
                $meta[trim(strtolower($key))] = trim($value);
            }
        }
    }

    return $meta;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Projects</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <?php include '../shared/header.php';?>
    <div style="display: flex; flex-direction: column; width: 100%; justify-content: center; align-items: center;">
      <div class="projects-grid">
        <?php foreach ($projectFiles as $file): 
          $meta = extractMeta($file); ?>
          <a href="demo/<?= htmlspecialchars($meta['link']) ?>" class="project-card">
            <!-- <img src="<?= htmlspecialchars($meta['image']) ?>" alt="<?= htmlspecialchars($meta['title']) ?>"> -->
            <h2><?= htmlspecialchars($meta['title']) ?></h2>
            <p><?= htmlspecialchars($meta['description']) ?></p>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
</body>
</html>
