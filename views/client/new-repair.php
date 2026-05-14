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
            <div class="auth-error" style="margin-bottom:24px">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?= sanitize($error) ?>
            </div>
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
