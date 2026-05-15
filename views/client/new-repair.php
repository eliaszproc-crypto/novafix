<section class="panel-section">
    <div class="container">
        <div class="panel-header">
            <div>
                <h1>Nowe zgłoszenie</h1>
                <p>Opisz problem, a my zajmiemy się resztą.</p>
            </div>
            <a href="/panel" class="btn btn--ghost">← Wróć</a>
        </div>
        <?php if ($error): ?>
            <div class="alert alert--error"><?= sanitize($error) ?></div>
        <?php endif; ?>
        <div class="panel-card">
            <form method="POST" action="/panel/nowe-zgloszenie" enctype="multipart/form-data" class="repair-form">

                <div class="form-section">
                    <h3>Urządzenie</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Typ urządzenia <span class="required">*</span></label>
                            <select name="device_type_id" required>
                                <option value="">Wybierz typ...</option>
                                <?php foreach ($device_types as $dt): ?>
                                    <option value="<?= $dt['id'] ?>"><?= sanitize($dt['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Marka</label>
                            <select name="device_brand_id">
                                <option value="">Wybierz markę...</option>
                                <?php foreach ($device_brands as $db): ?>
                                    <option value="<?= $db['id'] ?>"><?= sanitize($db['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Model urządzenia</label>
                        <input type="text" name="device_model" placeholder="np. Hydra 32HD, Apex EL...">
                    </div>
                </div>

                <div class="form-section">
                    <h3>Opis problemu</h3>
                    <div class="form-group">
                        <label>Opisz usterkę <span class="required">*</span></label>
                        <textarea name="problem_description" rows="5" placeholder="Opisz dokładnie co się dzieje z urządzeniem. Im więcej szczegółów, tym szybsza diagnoza." required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Zdjęcia urządzenia</label>
                        <div class="file-upload">
                            <input type="file" name="photos[]" id="photos" multiple accept="image/*">
                            <label for="photos" class="file-upload__label">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                <span>Kliknij lub przeciągnij zdjęcia (max 5MB każde)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Adres zwrotny</h3>
                    <p style="font-size:14px;color:var(--tm);margin-bottom:20px">Na ten adres wyślemy naprawiony sprzęt lub go zwrócimy w razie rezygnacji z naprawy.</p>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Imię <span class="required">*</span></label>
                            <input type="text" name="return_first_name" placeholder="Jan" required>
                        </div>
                        <div class="form-group">
                            <label>Nazwisko <span class="required">*</span></label>
                            <input type="text" name="return_last_name" placeholder="Kowalski" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Telefon kontaktowy</label>
                            <input type="tel" name="return_phone" placeholder="+48 123 456 789">
                        </div>
                        <div class="form-group">
                            <label>Ulica i numer <span class="required">*</span></label>
                            <input type="text" name="return_street" placeholder="ul. Przykładowa 1/2" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Kod pocztowy <span class="required">*</span></label>
                            <input type="text" name="return_postal" placeholder="00-000" required pattern="\d{2}-\d{3}">
                        </div>
                        <div class="form-group">
                            <label>Miasto <span class="required">*</span></label>
                            <input type="text" name="return_city" placeholder="Warszawa" required>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn--primary btn--lg">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                        Wyślij zgłoszenie
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
