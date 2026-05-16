<?php $config = require ROOT_PATH.'/config/config.php'; ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin' ?> — NovaFix Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
<div class="admin-wrap">
    <aside class="sidebar">
        <div class="sidebar__logo">
            <a href="/admin">Nova<span>Fix</span></a>
            <span class="sidebar__badge">Admin</span>
        </div>
        <nav class="sidebar__nav">
            <a href="/admin" class="sidebar__link <?= $_SERVER['REQUEST_URI']==='/admin' ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="/admin/zgloszenia" class="sidebar__link <?= strpos($_SERVER['REQUEST_URI'],'/admin/zg')!==false||strpos($_SERVER['REQUEST_URI'],'/admin/na')!==false ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Zgłoszenia
            </a>
            <a href="/admin/opinie" class="sidebar__link <?= strpos($_SERVER['REQUEST_URI'],'/admin/opinie')!==false ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                Opinie
            </a>
            <a href="/admin/uzytkownicy" class="sidebar__link <?= strpos($_SERVER['REQUEST_URI'],'/admin/uz')!==false ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Użytkownicy
            </a>
            <a href="/admin/statystyki" class="sidebar__link <?= strpos($_SERVER['REQUEST_URI'],'/admin/stat')!==false ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                Statystyki
            </a>
            <a href="/admin/kalendarz" class="sidebar__link <?= strpos($_SERVER['REQUEST_URI'],'/admin/kal')!==false ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Kalendarz
            </a>
            <a href="/admin/diagnostyka" class="sidebar__link <?= strpos($_SERVER['REQUEST_URI'],'/admin/dia')!==false ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Diagnostyka
            </a>
            <a href="/admin/cennik" class="sidebar__link <?= strpos($_SERVER['REQUEST_URI'],'/admin/cennik')!==false ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Cennik
            </a>
            <a href="/admin/platnosci" class="sidebar__link <?= strpos($_SERVER['REQUEST_URI'],'/admin/pl')!==false ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Płatności
            </a>
        </nav>
        <div class="sidebar__footer">
            <div class="sidebar__user">
                <div class="sidebar__avatar"><?= strtoupper(substr($_SESSION['user_name']??'A',0,1)) ?></div>
                <div>
                    <strong><?= sanitize($_SESSION['user_name']??'Admin') ?></strong>
                    <span>Administrator</span>
                </div>
            </div>
            <a href="/logout" class="sidebar__logout" title="Wyloguj">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </a>
        </div>
    </aside>
    <div class="admin-main">
        <div class="admin-topbar">
            <h1><?= $pageTitle ?? 'Dashboard' ?></h1>
            <a href="/" target="_blank" class="a-btn-ghost">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                Strona główna
            </a>
        </div>
        <div class="admin-content"><?= $content ?? '' ?></div>
    </div>
</div>
<script src="/js/admin.js"></script>
</body>
</html>
