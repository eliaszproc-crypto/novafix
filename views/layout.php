<?php
$config = require ROOT_PATH . '/config/config.php';
$appName = $config['app']['name'];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#070d1a">
    <meta name="description" content="NovaFix — profesjonalny serwis elektroniki akwarystycznej. Naprawa lamp LED, sterowników, dozowników. Eliasz Proć, inżynier elektronik.">
    <title><?= $pageTitle ?? $appName ?> | Serwis Sprzętu Akwarystycznego</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/main.css">
    <script src="/js/transitions.js"></script>
</head>
<body>
    <?php include VIEW_PATH . '/partials/navbar.php'; ?>
    <main><?= $content ?? '' ?></main>
    <?php include VIEW_PATH . '/partials/footer.php'; ?>
    <script src="/js/main.js"></script>
    <script src="/js/upload.js"></script>
    <script src="/js/hero-stats.js"></script>
    <script src="/js/upload.js"></script>
</body>
</html>
