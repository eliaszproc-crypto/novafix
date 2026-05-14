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
                <a href="/panel" class="btn btn--primary">Panel klienta</a>
            <?php else: ?>
                <a href="/login" class="btn btn--outline">Zaloguj się</a>
                <a href="/rejestracja" class="btn btn--primary">Rejestracja</a>
            <?php endif; ?>
        </div>
        <button class="navbar__burger" id="burgerBtn" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
