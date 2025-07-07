<?php
require_once __DIR__ . '/../private/db.php'; // Adjust path if needed

$slug = $_GET['slug'] ?? null;

if (!$slug) {
    http_response_code(400);
    exit('Post slug is required.');
}

// Prepare and execute the query using slug
$stmt = $pdo->prepare('SELECT * FROM posts WHERE slug = ?');
$stmt->execute([$slug]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    http_response_code(404);
    exit('Post not found.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link href="devlog_style.css"  rel="stylesheet">
    <?php if (!empty($post['custom_css'])): ?>
        <style><?= $post['custom_css'] ?></style>
    <?php endif; ?>
</head>
<body>

    <article>
        <a href="/../index.php">Back to home</a>
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <h2><?= htmlspecialchars($post['subtitle']) ?></h2>
        <hr>
        <div>
            <?= $post['content'] ?> <!-- Trusting stored HTML here -->
        </div>
    </article>

    <?php if (!empty($post['custom_js'])): ?>
        <script><?= $post['custom_js'] ?></script>
    <?php endif; ?>

</body>
</html>