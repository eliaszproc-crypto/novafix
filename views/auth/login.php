<section class="auth-section">
    <div class="auth-bg"></div>
    <div class="container auth-inner">
        <div class="auth-card">
            <div class="auth-card__header">
                <a href="/" class="auth-logo">Nova<span>Fix</span></a>
                <h1>Zaloguj się</h1>
                <p>Witaj z powrotem! Wpisz swoje dane.</p>
            </div>
            <?php if ($error): ?>
                <div class="auth-error">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <?= sanitize($error) ?>
                </div>
            <?php endif; ?>
            <form class="auth-form" method="POST" action="/login">
                <div class="form-group">
                    <label>Adres email</label>
                    <input type="email" name="email" placeholder="twoj@email.com" required autocomplete="email">
                </div>
                <div class="form-group">
                    <label>Hasło</label>
                    <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn btn--primary btn--full">Zaloguj się</button>
            </form>
            <p class="auth-footer">Nie masz konta? <a href="/rejestracja">Zarejestruj się</a></p>
        </div>
    </div>
</section>
