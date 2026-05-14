<section class="page-hero">
    <div class="page-hero__bg"></div>
    <div class="container">
        <p class="section__label">Jesteśmy do dyspozycji</p>
        <h1>Kontakt</h1>
        <p>Masz pytania? Napisz do nas lub skorzystaj z formularza zgłoszenia naprawy.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-info">
                <div class="contact-card">
                    <div class="contact-card__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <div>
                        <h4>Email</h4>
                        <a href="mailto:serwis@novafix.pl">serwis@novafix.pl</a>
                    </div>
                </div>
                <div class="contact-card">
                    <div class="contact-card__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </div>
                    <div>
                        <h4>Telefon</h4>
                        <p>Kontakt przez email lub formularz</p>
                    </div>
                </div>
                <div class="contact-card">
                    <div class="contact-card__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div>
                        <h4>Czas odpowiedzi</h4>
                        <p>Do 24h w dni robocze</p>
                    </div>
                </div>
                <div class="contact-card">
                    <div class="contact-card__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <h4>Serwis</h4>
                        <p>NovaFix — Eliasz Proć<br>Polska</p>
                    </div>
                </div>

                <div class="contact-cta">
                    <h3>Chcesz zgłosić naprawę?</h3>
                    <p>Skorzystaj z naszego systemu zgłoszeń — szybko, wygodnie, online.</p>
                    <a href="/panel/nowe-zgloszenie" class="btn btn--primary btn--lg">Zgłoś urządzenie</a>
                </div>
            </div>

            <div class="contact-form-wrap">
                <div class="panel-card">
                    <h3>Wyślij wiadomość</h3>
                    <?php if ($_GET['sent'] ?? false): ?>
                        <div style="padding:24px;text-align:center;color:#22c55e">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 12px"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <p>Wiadomość wysłana! Odpiszemy wkrótce.</p>
                        </div>
                    <?php else: ?>
                    <form class="auth-form" method="POST" action="/kontakt">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Imię i nazwisko</label>
                                <input type="text" name="name" placeholder="Jan Kowalski" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" placeholder="jan@email.com" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Temat</label>
                            <input type="text" name="subject" placeholder="Pytanie o naprawę...">
                        </div>
                        <div class="form-group">
                            <label>Wiadomość</label>
                            <textarea name="message" rows="6" placeholder="Opisz swoje pytanie..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn--primary btn--full">Wyślij wiadomość</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
