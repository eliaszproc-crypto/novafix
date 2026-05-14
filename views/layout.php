<?php
$config = require ROOT_PATH . '/config/config.php';
$appName = $config['app']['name'];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? $appName ?> | Serwis Sprzętu Akwarystycznego</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <?php include VIEW_PATH . '/partials/navbar.php'; ?>
    <main><?= $content ?? '' ?></main>
    <?php include VIEW_PATH . '/partials/footer.php'; ?>
    <script src="/js/main.js"></script>
</body>
</html>
