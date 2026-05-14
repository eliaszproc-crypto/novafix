<?php
$config = require ROOT_PATH . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin' ?> — NovaFix Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body class="admin-body">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar__logo">
            <a href="/admin">Nova<span>Fix</span></a>
            <span class="sidebar__badge">Admin</span>
        </div>
        <nav class="sidebar__nav">
            <a href="/admin" class="sidebar__link <?= ($_SERVER['REQUEST_URI'] === '/admin') ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="/admin/zgloszenia" class="sidebar__link <?= (strpos($_SERVER['REQUEST_URI'], '/admin/zg') !== false) ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Zgłoszenia
            </a>
            <a href="/admin/kalendarz" class="sidebar__link <?= (strpos($_SERVER['REQUEST_URI'], '/admin/kal') !== false) ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Kalendarz
            </a>
            <a href="/admin/platnosci" class="sidebar__link <?= (strpos($_SERVER['REQUEST_URI'], '/admin/pl') !== false) ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Płatności
            </a>
        </nav>
        <div class="sidebar__footer">
            <div class="sidebar__user">
                <div class="sidebar__avatar"><?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?></div>
                <div>
                    <strong><?= sanitize($_SESSION['user_name'] ?? 'Admin') ?></strong>
                    <span>Administrator</span>
                </div>
            </div>
            <a href="/logout" class="sidebar__logout" title="Wyloguj">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </a>
        </div>
    </aside>
    <main class="admin-main">
        <div class="admin-topbar">
            <h1 class="admin-topbar__title"><?= $pageTitle ?? 'Dashboard' ?></h1>
            <div class="admin-topbar__actions">
                <a href="/" target="_blank" class="btn btn--ghost btn--sm">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    Strona główna
                </a>
            </div>
        </div>
        <div class="admin-content">
            <?= $content ?? '' ?>
        </div>
    </main>
    <script src="/js/admin.js"></script>
</body>
</html>
