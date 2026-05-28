<header class="navbar" id="navbar">
    <div class="container navbar__inner">
        <a href="/" class="navbar__logo">Nova<span>Fix</span></a>
        <nav class="navbar__nav" id="navMenu">
            <a href="/">Start</a>
            <a href="/uslugi">Usługi</a>
            <a href="/cennik">Cennik</a>
            <a href="/panel/nowe-zgloszenie">Zgłoś urządzenie</a>
            <a href="/status">Status naprawy</a>
            <a href="/kontakt">Kontakt</a>
        </nav>
        <div class="navbar__actions">
            <?php if (isLoggedIn()): ?>
                <div class="navbar__user">
                    <div class="navbar__user-avatar"><?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?></div>
                    <span class="navbar__user-name"><?= sanitize(explode(' ', $_SESSION['user_name'] ?? '')[0]) ?></span>
                </div>
                <?php if (isAdmin()): ?>
                    <a href="/admin" class="btn btn--outline">Panel admina</a>
                <?php else: ?>
                    <a href="/panel" class="btn btn--outline">Moje zgłoszenia</a>
                <?php endif; ?>
                <a href="/logout" class="btn btn--ghost">Wyloguj</a>
            <?php else: ?>
                <a href="/login" class="btn btn--outline">Zaloguj się</a>
                <a href="/rejestracja" class="btn btn--primary">Rejestracja</a>
            <?php endif; ?>
        </div>
        <div class="navbar__mobile-actions">
            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                    <a href="/admin" class="btn btn--outline btn--sm">Admin</a>
                <?php else: ?>
                    <a href="/panel" class="btn btn--outline btn--sm">Panel</a>
                <?php endif; ?>
                <a href="/logout" class="btn btn--ghost btn--sm">Wyloguj</a>
            <?php else: ?>
                <a href="/login" class="btn btn--outline btn--sm">Logowanie</a>
                <a href="/rejestracja" class="btn btn--primary btn--sm">Rejestracja</a>
            <?php endif; ?>
        </div>
        <button class="navbar__burger" id="burgerBtn" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
