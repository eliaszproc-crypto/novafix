<section class="page-hero page-hero--img" style="background-image:url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=1600&q=75')">
    <div class="page-hero__overlay"></div>
    <div class="page-deco">
        <div class="page-deco__ring page-deco__ring--1"></div>
        <div class="page-deco__ring page-deco__ring--2"></div>
    </div>
    <div class="container page-hero__inner">
        <p class="section__label">Napisz do mnie</p>
        <h1>Kontakt</h1>
        <p>Masz pytanie przed zgłoszeniem? Napisz — odpiszę szczerze czy naprawa ma sens.</p>
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
                        <a href="mailto:eliasz.proc@gmail.com">eliasz.proc@gmail.com</a>
                    </div>
                </div>
                <div class="contact-card">
                    <div class="contact-card__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <h4>Adres firmy</h4>
                        <p>ul. Wyszyńskiego 14a/1<br>78-400 Szczecinek</p>
                        <p style="font-size:12px;margin-top:4px;color:var(--tm)">Wysyłka: paczkomat <strong>SCZ04M</strong> lub kurier</p>
                    </div>
                </div>
                <div class="contact-card">
                    <div class="contact-card__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </div>
                    <div>
                        <h4>Telefon</h4>
                        <a href="tel:+48691113754">691 113 754</a>
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
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div>
                        <h4>Eliasz Proć</h4>
                        <p>Inżynier elektronik<br>NovaFix — Szczecinek</p>
                    </div>
                </div>
                <div class="contact-cta">
                    <h3>Wolisz od razu zgłosić sprzęt?</h3>
                    <p>Złóż zlecenie online — opisz problem, wyślij zdjęcia.</p>
                    <a href="/panel/nowe-zgloszenie" class="btn btn--primary btn--lg">Zgłoś urządzenie</a>
                </div>
                <div class="contact-cta" style="background:rgba(0,229,255,0.04);border-color:rgba(0,229,255,0.15)">
                    <h3>📦 Jak wysłać sprzęt?</h3>
                    <p>Zawiń w folię bąbelkową i zabezpiecz w kartonie. Dołącz kartkę z numerem zgłoszenia RMA.</p>
                    <p style="margin-top:10px"><strong style="color:var(--c)">Małe urządzenia:</strong> paczkomat <strong>SCZ04M</strong>, 78-400 Szczecinek</p>
                    <p style="margin-top:6px"><strong style="color:#8b5cf6">Duże urządzenia</strong> (nie mieszczą się w paczkomacie):<br>kurierem na ul. Wyszyńskiego 14a/1, 78-400 Szczecinek</p>
                </div>
            </div>
            <div class="contact-form-wrap">
                <div class="panel-card">
                    <h3>Wyślij wiadomość</h3>
                    <?php if ($_GET['sent'] ?? false): ?>
                        <div style="padding:32px;text-align:center;color:#22c55e">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 12px"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <p style="font-size:16px;font-weight:600">Wiadomość wysłana!</p>
                            <p style="color:var(--tm);margin-top:8px">Odezwę się w ciągu 24h.</p>
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
                            <input type="text" name="subject" placeholder="Np. pytanie o naprawę lampy AI Hydra...">
                        </div>
                        <div class="form-group">
                            <label>Wiadomość</label>
                            <textarea name="message" rows="6" placeholder="Opisz pytanie lub problem..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn--primary btn--full">Wyślij wiadomość</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
