<div class="repair-header">
    <div>
        <h2><?= sanitize($repair['rma_number']) ?></h2>
        <span class="status-pill" style="background:<?= $repair['status_color'] ?>22;color:<?= $repair['status_color'] ?>;padding:5px 12px;font-size:12px">
            <?= sanitize($repair['status_label']) ?>
        </span>
        <?php if ($repair['negotiation_round'] > 1): ?>
            <span style="font-size:11px;color:var(--tm);margin-left:8px">Runda <?= $repair['negotiation_round'] ?> negocjacji</span>
        <?php endif; ?>
    </div>
    <a href="/admin/zgloszenia" class="a-btn a-btn-secondary">← Wróć</a>
</div>

<?php if ($success): ?><div class="a-alert a-alert--success"><?= sanitize($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="a-alert a-alert--error"><?= sanitize($error) ?></div><?php endif; ?>

<div class="detail-grid">
<div>

    <!-- Dane klienta i urządzenia -->
    <div class="admin-card">
        <h3>Dane zgłoszenia</h3>
        <div class="detail-rows">
            <div class="detail-row"><span>Klient</span><strong><?= sanitize($repair['first_name'] . ' ' . $repair['last_name']) ?></strong></div>
            <div class="detail-row"><span>Email</span><strong><?= sanitize($repair['email']) ?></strong></div>
            <?php if ($repair['phone']): ?><div class="detail-row"><span>Telefon</span><strong><?= sanitize($repair['phone']) ?></strong></div><?php endif; ?>
            <div class="detail-row"><span>Urządzenie</span><strong><?= sanitize($repair['device_type']) ?><?= $repair['device_brand'] ? ' — ' . sanitize($repair['device_brand']) : '' ?></strong></div>
            <?php if ($repair['device_model']): ?><div class="detail-row"><span>Model</span><strong><?= sanitize($repair['device_model']) ?></strong></div><?php endif; ?>
            <div class="detail-row"><span>Zgłoszono</span><strong><?= date('d.m.Y H:i', strtotime($repair['created_at'])) ?></strong></div>
        </div>
    </div>

    <!-- Opis problemu -->
    <div class="admin-card">
        <h3>Opis problemu</h3>
        <p class="detail-text"><?= nl2br(sanitize($repair['problem_description'])) ?></p>
    </div>

    <?php if (!empty($photos)): ?>
    <div class="admin-card">
        <h3>Zdjęcia</h3>
        <div class="photos-grid">
            <?php foreach ($photos as $p): ?>
                <a href="/uploads/<?= $p['filename'] ?>" target="_blank"><img src="/uploads/<?= $p['filename'] ?>" alt=""></a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Adres zwrotny klienta -->
    <?php if ($repair['return_address']): ?>
    <div class="admin-card">
        <h3>Adres zwrotny klienta</h3>
        <p class="detail-text"><?= nl2br(sanitize($repair['return_address'])) ?></p>
    </div>
    <?php endif; ?>

    <!-- Odrzucenia / komentarze klienta -->
    <?php if ($repair['initial_quote_rejection_note']): ?>
    <div class="admin-card" style="border-color:rgba(239,68,68,0.2)">
        <h3 style="color:#f87171">Komentarz klienta — wstępna wycena</h3>
        <p class="detail-text"><?= nl2br(sanitize($repair['initial_quote_rejection_note'])) ?></p>
    </div>
    <?php endif; ?>

    <?php if ($repair['final_quote_rejection_note']): ?>
    <div class="admin-card" style="border-color:rgba(239,68,68,0.2)">
        <h3 style="color:#f87171">Komentarz klienta — koszt naprawy</h3>
        <p class="detail-text"><?= nl2br(sanitize($repair['final_quote_rejection_note'])) ?></p>
    </div>
    <?php endif; ?>

    <!-- Wyceny -->
    <?php if ($repair['initial_quote_amount']): ?>
    <div class="admin-card" style="border-color:rgba(0,229,255,0.12)">
        <h3>Wstępna wycena</h3>
        <div class="quote-amount"><?= formatMoney((float)$repair['initial_quote_amount']) ?></div>
        <?php if ($repair['initial_quote_note']): ?><p class="detail-text"><?= nl2br(sanitize($repair['initial_quote_note'])) ?></p><?php endif; ?>
        <?php if ($repair['initial_quote_decided_at']): ?>
            <p style="font-size:12px;color:var(--tm);margin-top:8px">
                <?= in_array($repair['status_code'], ['initial_quote_accepted','shipping_instructions','parcel_received','diagnosis','final_quote_sent','final_quote_accepted','final_quote_rejected','in_repair','awaiting_payment','paid','shipped_to_client','completed']) ? '✓ Zaakceptowana' : '✗ Odrzucona' ?>
                <?= date('d.m.Y', strtotime($repair['initial_quote_decided_at'])) ?>
            </p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ($repair['final_quote_amount']): ?>
    <div class="admin-card" style="border-color:rgba(0,229,255,0.2)">
        <h3>Koszt naprawy</h3>
        <div class="quote-amount"><?= formatMoney((float)$repair['final_quote_amount']) ?></div>
        <?php if ($repair['final_quote_note']): ?><p class="detail-text"><?= nl2br(sanitize($repair['final_quote_note'])) ?></p><?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- FORMULARZE AKCJI -->

    <!-- Wyślij wycenę / koszt -->
    <div class="admin-card">
        <h3>Wyślij wycenę / koszt naprawy</h3>
        <form method="POST" action="/admin/naprawa/<?= $repair['id'] ?>/wycena" class="admin-form">
            <div class="f-row">
                <div class="f-group">
                    <label>Typ</label>
                    <select name="quote_type" required>
                        <option value="initial">Wstępna wycena</option>
                        <option value="final">Koszt naprawy</option>
                    </select>
                </div>
                <div class="f-group">
                    <label>Kwota (zł)</label>
                    <input type="number" name="amount" step="0.01" min="0.01" placeholder="0.00" required>
                </div>
            </div>
            <div class="f-group">
                <label>Opis / zakres prac</label>
                <textarea name="note" rows="3" placeholder="Co zostanie naprawione, jakie części..."></textarea>
            </div>
            <button type="submit" class="a-btn a-btn-primary">Wyślij do klienta</button>
        </form>
    </div>

    <!-- Zmień status ręcznie -->
    <div class="admin-card">
        <h3>Zmień status ręcznie</h3>
        <form method="POST" action="/admin/naprawa/<?= $repair['id'] ?>/status" class="admin-form">
            <div class="f-group">
                <label>Nowy status</label>
                <select name="status_id" required>
                    <?php foreach ($statuses as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $repair['status_id']==$s['id'] ? 'selected' : '' ?>>
                            <?= sanitize($s['label']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="f-group">
                <label>Notatka</label>
                <textarea name="note" rows="2" placeholder="Opcjonalna notatka..."></textarea>
            </div>
            <button type="submit" class="a-btn a-btn-secondary">Zapisz status</button>
        </form>
    </div>

    <!-- Oznacz jako opłacone -->
    <?php if ($repair['status_code'] === 'awaiting_payment'): ?>
    <div class="admin-card" style="border-color:rgba(34,197,94,0.2)">
        <h3 style="color:#22c55e">Potwierdź płatność</h3>
        <p class="detail-text" style="margin-bottom:16px">Kwota: <strong style="color:#22c55e"><?= formatMoney((float)$repair['final_quote_amount']) ?></strong></p>
        <form method="POST" action="/admin/naprawa/<?= $repair['id'] ?>/oplacone" class="admin-form">
            <div class="f-group">
                <label>Metoda płatności</label>
                <select name="method">
                    <option value="transfer">Przelew bankowy</option>
                    <option value="card">Karta płatnicza</option>
                    <option value="cash">Gotówka</option>
                    <option value="other">Inna</option>
                </select>
            </div>
            <button type="submit" class="a-btn a-btn-primary">✓ Oznacz jako opłacone</button>
        </form>
    </div>
    <?php endif; ?>

    <!-- Zwrot sprzętu -->
    <?php if (in_array($repair['status_code'], ['initial_quote_rejected','final_quote_rejected','initial_quote_renegotiation','final_quote_renegotiation'])): ?>
    <div class="admin-card" style="border-color:rgba(239,68,68,0.2)">
        <h3 style="color:#f87171">Zwrot sprzętu</h3>
        <p class="detail-text" style="margin-bottom:16px">Klient zrezygnował — możesz oznaczyć zgłoszenie jako zwrot sprzętu.</p>
        <?php if ($repair['return_address']): ?>
            <p class="detail-text" style="margin-bottom:16px">Adres zwrotny: <strong><?= nl2br(sanitize($repair['return_address'])) ?></strong></p>
        <?php else: ?>
            <p style="color:#f87171;font-size:13px;margin-bottom:16px">⚠ Klient nie podał jeszcze adresu zwrotnego.</p>
        <?php endif; ?>
        <form method="POST" action="/admin/naprawa/<?= $repair['id'] ?>/zwrot">
            <input type="hidden" name="note" value="Zwrot sprzętu do klienta">
            <button type="submit" class="a-btn" style="background:rgba(239,68,68,0.15);color:#f87171;border:1px solid rgba(239,68,68,0.3)">Oznacz jako zwrot sprzętu</button>
        </form>
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
            <?php foreach (array_reverse($history) as $h): ?>
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
