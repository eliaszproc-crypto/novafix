<div class="repair-header">
    <div>
        <h2><?= sanitize($repair['rma_number']) ?></h2>
        <span class="status-pill" style="background:<?= $repair['status_color'] ?>22;color:<?= $repair['status_color'] ?>;padding:5px 12px;font-size:12px">
            <?= sanitize($repair['status_label']) ?>
        </span>
    </div>
    <a href="/admin/zgloszenia" class="a-btn a-btn-secondary">← Wróć</a>
</div>

<div class="detail-grid">
    <div>
        <!-- Dane -->
        <div class="admin-card">
            <h3>Dane zgłoszenia</h3>
            <div class="detail-rows">
                <div class="detail-row"><span>Klient</span><strong><?= sanitize($repair['first_name'] . ' ' . $repair['last_name']) ?></strong></div>
                <div class="detail-row"><span>Email</span><strong><?= sanitize($repair['email']) ?></strong></div>
                <?php if ($repair['phone']): ?><div class="detail-row"><span>Telefon</span><strong><?= sanitize($repair['phone']) ?></strong></div><?php endif; ?>
                <div class="detail-row"><span>Typ urządzenia</span><strong><?= sanitize($repair['device_type']) ?></strong></div>
                <?php if ($repair['device_brand']): ?><div class="detail-row"><span>Marka</span><strong><?= sanitize($repair['device_brand']) ?></strong></div><?php endif; ?>
                <?php if ($repair['device_model']): ?><div class="detail-row"><span>Model</span><strong><?= sanitize($repair['device_model']) ?></strong></div><?php endif; ?>
                <div class="detail-row"><span>Data zgłoszenia</span><strong><?= date('d.m.Y H:i', strtotime($repair['created_at'])) ?></strong></div>
            </div>
        </div>

        <!-- Opis -->
        <div class="admin-card">
            <h3>Opis problemu</h3>
            <p class="detail-text"><?= nl2br(sanitize($repair['problem_description'])) ?></p>
        </div>

        <?php if (!empty($photos)): ?>
        <div class="admin-card">
            <h3>Zdjęcia</h3>
            <div class="photos-grid">
                <?php foreach ($photos as $p): ?>
                    <a href="/uploads/<?= $p['filename'] ?>" target="_blank">
                        <img src="/uploads/<?= $p['filename'] ?>" alt="">
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Zmień status -->
        <div class="admin-card">
            <h3>Zmień status</h3>
            <form method="POST" action="/admin/naprawa/<?= $repair['id'] ?>/status" class="admin-form">
                <div class="f-group">
                    <label>Nowy status</label>
                    <select name="status_id" required>
                        <?php foreach ($statuses as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= $repair['status_id'] == $s['id'] ? 'selected' : '' ?>>
                                <?= sanitize($s['label']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="f-group">
                    <label>Notatka (opcjonalnie)</label>
                    <textarea name="note" rows="3" placeholder="Dodaj notatkę..."></textarea>
                </div>
                <button type="submit" class="a-btn a-btn-primary">Zapisz status</button>
            </form>
        </div>

        <!-- Wycena -->
        <div class="admin-card">
            <h3>Wyślij wycenę</h3>
            <form method="POST" action="/admin/naprawa/<?= $repair['id'] ?>/wycena" class="admin-form">
                <div class="f-row">
                    <div class="f-group">
                        <label>Typ wyceny</label>
                        <select name="quote_type" required>
                            <option value="initial">Wstępna</option>
                            <option value="final">Finalna</option>
                        </select>
                    </div>
                    <div class="f-group">
                        <label>Kwota (zł)</label>
                        <input type="number" name="amount" step="0.01" min="0" placeholder="0.00" required>
                    </div>
                </div>
                <div class="f-group">
                    <label>Opis wyceny</label>
                    <textarea name="note" rows="3" placeholder="Co zostanie naprawione..."></textarea>
                </div>
                <button type="submit" class="a-btn a-btn-primary">Wyślij wycenę</button>
            </form>
        </div>

        <?php if ($repair['initial_quote_amount']): ?>
        <div class="admin-card" style="border-color:rgba(0,229,255,0.15)">
            <h3>Wstępna wycena</h3>
            <div class="quote-amount"><?= formatMoney((float)$repair['initial_quote_amount']) ?></div>
            <?php if ($repair['initial_quote_note']): ?><p class="detail-text"><?= nl2br(sanitize($repair['initial_quote_note'])) ?></p><?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if ($repair['final_quote_amount']): ?>
        <div class="admin-card" style="border-color:rgba(0,229,255,0.15)">
            <h3>Finalna wycena</h3>
            <div class="quote-amount"><?= formatMoney((float)$repair['final_quote_amount']) ?></div>
            <?php if ($repair['final_quote_note']): ?><p class="detail-text"><?= nl2br(sanitize($repair['final_quote_note'])) ?></p><?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Historia -->
    <div>
        <div class="admin-card">
            <h3>Historia statusów</h3>
            <?php if (empty($history)): ?>
                <p style="color:var(--tm);font-size:13px">Brak historii.</p>
            <?php else: ?>
            <div class="history-list">
                <?php foreach ($history as $h): ?>
                <div class="history-item">
                    <div class="history-dot" style="background:<?= $h['color'] ?>"></div>
                    <div class="history-content">
                        <strong style="color:<?= $h['color'] ?>"><?= sanitize($h['label']) ?></strong>
                        <time><?= date('d.m.Y H:i', strtotime($h['changed_at'])) ?></time>
                        <?php if ($h['note']): ?><p><?= sanitize($h['note']) ?></p><?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
