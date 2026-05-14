<section class="auth-section">
    <div class="auth-bg"></div>
    <div class="container auth-inner">
        <div class="auth-card auth-card--wide">
            <div class="auth-card__header">
                <a href="/" class="auth-logo">Nova<span>Fix</span></a>
                <h1>Utwórz konto</h1>
                <p>Zarejestruj się i zgłoś swoje urządzenie do naprawy.</p>
            </div>
            <?php if ($error): ?>
                <div class="auth-error">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <?= sanitize($error) ?>
                </div>
            <?php endif; ?>
            <form class="auth-form" method="POST" action="/rejestracja">
                <div class="form-row">
                    <div class="form-group">
                        <label>Imię <span class="required">*</span></label>
                        <input type="text" name="first_name" placeholder="Jan" required>
                    </div>
                    <div class="form-group">
                        <label>Nazwisko <span class="required">*</span></label>
                        <input type="text" name="last_name" placeholder="Kowalski" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Adres email <span class="required">*</span></label>
                    <input type="email" name="email" placeholder="twoj@email.com" required autocomplete="email">
                </div>
                <div class="form-group">
                    <label>Telefon</label>
                    <input type="tel" name="phone" placeholder="+48 123 456 789">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Hasło <span class="required">*</span></label>
                        <input type="password" name="password" placeholder="Min. 8 znaków" required autocomplete="new-password">
                    </div>
                    <div class="form-group">
                        <label>Powtórz hasło <span class="required">*</span></label>
                        <input type="password" name="password2" placeholder="••••••••" required autocomplete="new-password">
                    </div>
                </div>
                <button type="submit" class="btn btn--primary btn--full">Utwórz konto</button>
            </form>
            <p class="auth-footer">Masz już konto? <a href="/login">Zaloguj się</a></p>
        </div>
    </div>
</section>
