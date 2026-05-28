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
    <meta name="description" content="<?= htmlspecialchars($metaDesc ?? 'NovaFix — profesjonalny serwis elektroniki akwarystycznej. Naprawa lamp LED, sterowników, falowników i dozowników. Eliasz Proć, inżynier elektronik, Szczecinek.') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($metaKeys ?? 'serwis akwarystyczny, naprawa lampy LED akwarium, naprawa sterownika akwarystycznego, serwis elektroniki akwarystycznej, naprawa Hydra, naprawa AI Prime, Szczecinek') ?>">
    <meta name="author" content="Eliasz Proć — NovaFix">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://novafix.pl<?= $_SERVER['REQUEST_URI'] ?? '' ?>">
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="NovaFix">
    <meta property="og:title" content="<?= htmlspecialchars($metaTitle ?? ($pageTitle ?? 'NovaFix').' — Serwis Elektroniki Akwarystycznej') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDesc ?? 'Profesjonalny serwis elektroniki akwarystycznej. Naprawa lamp LED, sterowników, falowników. Eliasz Proć, inżynier elektronik.') ?>">
    <meta property="og:url" content="https://novafix.pl<?= $_SERVER['REQUEST_URI'] ?? '' ?>">
    <meta property="og:locale" content="pl_PL">
    <!-- Schema.org LocalBusiness -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ElectronicsRepair",
        "name": "NovaFix",
        "description": "Profesjonalny serwis elektroniki akwarystycznej — naprawa lamp LED, sterowników, falowników, dozowników.",
        "url": "https://novafix.pl",
        "telephone": "+48691113754",
        "email": "eliasz.proc@gmail.com",
        "founder": {"@type":"Person","name":"Eliasz Proć","jobTitle":"Inżynier elektronik","description":"Zapalony akwarysta morski i inżynier elektronik"},
        "foundingDate": "2022",
        "address": {"@type":"PostalAddress","addressLocality":"Szczecinek","postalCode":"78-400","addressCountry":"PL"},
        "areaServed": "PL",
        "priceRange": "od 50 zł",
        "openingHours": "Mo-Fr 08:00-18:00",
        "serviceType": ["Naprawa lamp LED akwarystycznych","Naprawa sterowników akwarystycznych","Naprawa dozowników Balling","Naprawa falowników i cyrkulatorów","Naprawa sprzętu po zalaniu"],
        "taxID": "6731864422",
        "vatID": "PL6731864422",
        "legalName": "NovaFix Eliasz Proć"
    }
    </script>
    <title><?= $pageTitle ?? $appName ?> | Serwis Sprzętu Akwarystycznego</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/main.css">
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
