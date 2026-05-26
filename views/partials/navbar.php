<header class="navbar" id="navbar">
    <div class="container navbar__inner">
        <a href="/" class="navbar__logo">Nova<span>Fix</span></a>
        <nav class="navbar__nav" id="navMenu">
            <a href="/"><?= t('nav.home') ?></a>
            <a href="/uslugi"><?= t('nav.services') ?></a>
            <a href="/cennik"><?= t('nav.pricing') ?></a>
            <a href="/panel/nowe-zgloszenie"><?= t('nav.report') ?></a>
            <a href="/status"><?= t('nav.status') ?></a>
            <a href="/kontakt"><?= t('nav.contact') ?></a>
        </nav>
        <div class="lang-switch lang-switch--desktop">
            <a href="/lang/pl" class="lang-btn <?= getLang()==='pl' ? 'active' : '' ?>">PL</a>
            <a href="/lang/en" class="lang-btn <?= getLang()==='en' ? 'active' : '' ?>">EN</a>
        </div>
        <div class="navbar__actions">
            <?php if (isLoggedIn()): ?>
                <div class="navbar__user">
                    <div class="navbar__user-avatar"><?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?></div>
                    <span class="navbar__user-name"><?= sanitize(explode(' ', $_SESSION['user_name'] ?? '')[0]) ?></span>
                </div>
                <?php if (isAdmin()): ?>
                    <a href="/admin" class="btn btn--outline"><?= t('nav.admin') ?></a>
                <?php else: ?>
                    <a href="/panel" class="btn btn--outline"><?= t('nav.panel') ?></a>
                <?php endif; ?>
                <a href="/logout" class="btn btn--ghost"><?= t('nav.logout') ?></a>
            <?php else: ?>
                <a href="/login" class="btn btn--outline"><?= t('nav.login') ?></a>
                <a href="/rejestracja" class="btn btn--primary"><?= t('nav.register') ?></a>
            <?php endif; ?>
        </div>
        <div class="navbar__mobile-actions">
            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                    <a href="/admin" class="btn btn--outline btn--sm">Admin</a>
                <?php else: ?>
                    <a href="/panel" class="btn btn--outline btn--sm">Panel</a>
                <?php endif; ?>
                <a href="/logout" class="btn btn--ghost btn--sm"><?= t('nav.logout') ?></a>
            <?php else: ?>
                <a href="/login" class="btn btn--outline btn--sm">Logowanie</a>
                <a href="/rejestracja" class="btn btn--primary btn--sm"><?= t('nav.register') ?></a>
            <?php endif; ?>
        </div>
        <div class="lang-switch">
            <a href="/lang/pl" class="lang-btn <?= getLang()==='pl' ? 'active' : '' ?>">PL</a>
            <a href="/lang/en" class="lang-btn <?= getLang()==='en' ? 'active' : '' ?>">EN</a>
        </div>
        <div class="lang-switch">
            <a href="/lang/pl" class="lang-btn <?= lang()==='pl'?'active':'' ?>">PL</a>
            <a href="/lang/en" class="lang-btn <?= lang()==='en'?'active':'' ?>">EN</a>
        </div>
        <button class="navbar__burger" id="burgerBtn" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
