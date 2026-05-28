<div class="repair-header">
    <div>
        <h2><?= sanitize($repair['rma_number']) ?></h2>
        <span class="status-pill" style="background:<?= $repair['status_color'] ?>22;color:<?= $repair['status_color'] ?>;padding:5px 12px;font-size:12px">
            <?= sanitize($repair['status_label']) ?>
        </span>
        <?php if (($repair['negotiation_round'] ?? 0) > 1): ?>
            <span style="font-size:11px;color:var(--tm);margin-left:8px">Runda <?= $repair['negotiation_round'] ?> negocjacji</span>
        <?php endif; ?>
    </div>
    <a href="/admin/zgloszenia" class="a-btn a-btn-secondary">← Wróć</a>
</div>

<?php if ($success): ?><div class="a-alert a-alert--success"><?= sanitize($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="a-alert a-alert--error"><?= sanitize($error) ?></div><?php endif; ?>

<?php $sc = $repair['status_code']; ?>

<div class="detail-grid">
<div>

    <div class="admin-card">
        <h3>Dane zgłoszenia</h3>
        <div class="detail-rows">
            <div class="detail-row"><span>Klient</span><strong><?= sanitize($repair['first_name'].' '.$repair['last_name']) ?></strong></div>
            <div class="detail-row"><span>Email</span><strong><?= sanitize($repair['email']) ?></strong></div>
            <?php if ($repair['phone']): ?><div class="detail-row"><span>Telefon</span><strong><?= sanitize($repair['phone']) ?></strong></div><?php endif; ?>
            <div class="detail-row"><span>Urządzenie</span><strong><?= sanitize($repair['device_type']) ?><?= $repair['device_brand'] ? ' — '.sanitize($repair['device_brand']) : '' ?></strong></div>
            <?php if ($repair['device_model']): ?><div class="detail-row"><span>Model</span><strong><?= sanitize($repair['device_model']) ?></strong></div><?php endif; ?>
            <div class="detail-row"><span>Zgłoszono</span><strong><?= date('d.m.Y H:i', strtotime($repair['created_at'])) ?></strong></div>
        </div>
    </div>

    <!-- Adres zwrotny klienta -->
    <?php if ($repair['return_first_name']): ?>
    <div class="admin-card" style="border-color:rgba(0,229,255,0.12)">
        <h3>Adres zwrotny klienta</h3>
        <div style="font-size:14px;color:var(--t);line-height:1.8">
            <strong><?= sanitize($repair['return_first_name'].' '.$repair['return_last_name']) ?></strong><br>
            <?= sanitize($repair['return_street']) ?><br>
            <?= sanitize($repair['return_postal']) ?> <?= sanitize($repair['return_city']) ?>
            <?php if ($repair['return_phone']): ?><br>Tel: <?= sanitize($repair['return_phone']) ?><?php endif; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="admin-card" style="border-color:rgba(239,68,68,0.15)">
        <h3 style="color:#f87171">⚠ Brak adresu zwrotnego</h3>
        <p class="detail-text">Klient nie podał jeszcze adresu zwrotnego.</p>
    </div>
    <?php endif; ?>

    <div class="admin-card">
        <h3>Opis problemu</h3>
        <p class="detail-text"><?= nl2br(sanitize($repair['problem_description'])) ?></p>
    </div>

    <?php if (!empty($photos)): ?>
    <div class="admin-card">
        <h3>Zdjęcia od klienta</h3>
        <div class="photos-grid">
            <?php foreach ($photos as $p): ?>
                <a href="/uploads/<?= $p['filename'] ?>"><img src="/uploads/<?= $p['filename'] ?>" alt=""></a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Zdjęcia admina dla klienta -->
    <div class="admin-card">
        <h3>📷 Zdjęcia dla klienta</h3>
        <p style="font-size:13px;color:var(--tm);margin-bottom:16px">Zdjęcia widoczne dla klienta — np. po diagnostyce lub naprawie.</p>
        <?php if (!empty($admin_photos)): ?>
        <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:16px">
            <?php foreach ($admin_photos as $ap): ?>
            <div style="position:relative">
                <a href="/uploads/<?= $ap['filename'] ?>">
                    <img src="/uploads/<?= $ap['filename'] ?>" style="width:90px;height:90px;object-fit:cover;border-radius:8px;border:1px solid var(--bd)" alt="">
                </a>
                <?php if ($ap['caption']): ?>
                    <p style="font-size:10px;color:var(--tm);margin:3px 0 0;max-width:90px"><?= sanitize($ap['caption']) ?></p>
                <?php endif; ?>
                <form method="POST" action="/admin/naprawa/<?= $repair['id'] ?>/usun-zdjecie/<?= $ap['id'] ?>" style="position:absolute;top:-6px;right:-6px" onsubmit="return confirm('Usunąć?')">
                    <button type="submit" style="width:20px;height:20px;border-radius:50%;background:#ef4444;border:2px solid #0f1929;color:#fff;cursor:pointer;font-size:10px;padding:0">✕</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <form method="POST" action="/admin/naprawa/<?= $repair['id'] ?>/dodaj-zdjecie" enctype="multipart/form-data" class="admin-form">
            <div class="f-row">
                <div class="f-group">
                    <label>Zdjęcie</label>
                    <input type="file" name="photo" accept="image/*" required>
                </div>
                <div class="f-group">
                    <label>Opis (opcjonalnie)</label>
                    <input type="text" name="caption" placeholder="np. Stan po naprawie...">
                </div>
            </div>
            <button type="submit" class="a-btn a-btn-secondary">Dodaj zdjęcie dla klienta</button>
        </form>
    </div>

    <!-- Komentarze klienta przy odrzuceniu -->
    <?php if ($repair['initial_quote_rejection_note']): ?>
    <div class="admin-card" style="border-color:rgba(239,68,68,0.2)">
        <h3 style="color:#f87171">💬 Komentarz klienta — wstępna wycena</h3>
        <p class="detail-text"><?= nl2br(sanitize($repair['initial_quote_rejection_note'])) ?></p>
    </div>
    <?php endif; ?>

    <?php if ($repair['final_quote_rejection_note']): ?>
    <div class="admin-card" style="border-color:rgba(239,68,68,0.2)">
        <h3 style="color:#f87171">💬 Komentarz klienta — koszt naprawy</h3>
        <p class="detail-text"><?= nl2br(sanitize($repair['final_quote_rejection_note'])) ?></p>
    </div>
    <?php endif; ?>

    <!-- Wstępna wycena info -->
    <?php if ($repair['initial_quote_amount']): ?>
    <div class="admin-card" style="border-color:rgba(0,229,255,0.1)">
        <h3>Wstępna wycena</h3>
        <div class="quote-amount"><?= formatMoney((float)$repair['initial_quote_amount']) ?></div>
        <?php if ($repair['initial_quote_note']): ?><p class="detail-text"><?= nl2br(sanitize($repair['initial_quote_note'])) ?></p><?php endif; ?>
        <?php if ($repair['initial_quote_decided_at']): ?>
            <p style="font-size:12px;color:var(--tm);margin-top:8px">
                <?= in_array($sc, ['initial_quote_accepted','parcel_received','diagnosis','final_quote_sent','final_quote_accepted','final_quote_rejected','in_repair','awaiting_payment','paid','shipped_to_client','completed','return_in_progress','final_quote_renegotiation']) ? '✓ Zaakceptowana' : '✗ Odrzucona' ?>
                — <?= date('d.m.Y', strtotime($repair['initial_quote_decided_at'])) ?>
            </p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Koszt naprawy info -->
    <?php if ($repair['final_quote_amount']): ?>
    <div class="admin-card" style="border-color:rgba(0,229,255,0.18)">
        <h3>Koszt naprawy</h3>
        <div class="quote-amount"><?= formatMoney((float)$repair['final_quote_amount']) ?></div>
        <?php if ($repair['final_quote_note']): ?><p class="detail-text"><?= nl2br(sanitize($repair['final_quote_note'])) ?></p><?php endif; ?>
        <?php if ($repair['final_quote_decided_at']): ?>
            <p style="font-size:12px;color:var(--tm);margin-top:8px">
                <?= in_array($sc, ['final_quote_accepted','in_repair','awaiting_payment','paid','shipped_to_client','completed']) ? '✓ Zaakceptowany' : '✗ Odrzucony' ?>
                — <?= date('d.m.Y', strtotime($repair['final_quote_decided_at'])) ?>
            </p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- FORMULARZE AKCJI -->

    <!-- Wyślij wycenę — zawsze dostępne -->
    <div class="admin-card">
        <h3><?= in_array($sc, ['initial_quote_rejected','initial_quote_renegotiation']) ? '🔄 Wyślij nową propozycję wyceny' : (in_array($sc, ['final_quote_rejected','final_quote_renegotiation']) ? '🔄 Wyślij nowy koszt naprawy' : 'Wyślij wycenę / koszt naprawy') ?></h3>
        <form method="POST" action="/admin/naprawa/<?= $repair['id'] ?>/wycena" class="admin-form">
            <div class="f-row">
                <div class="f-group">
                    <label>Typ</label>
                    <select name="quote_type" required>
                        <option value="initial" <?= in_array($sc, ['new','initial_quote_sent','initial_quote_rejected','initial_quote_renegotiation']) ? 'selected' : '' ?>>Wstępna wycena</option>
                        <option value="final" <?= in_array($sc, ['parcel_received','diagnosis','final_quote_sent','final_quote_rejected','final_quote_renegotiation']) ? 'selected' : '' ?>>Koszt naprawy</option>
                    </select>
                </div>
                <div class="f-group">
                    <label>Kwota (zł)</label>
                    <input type="number" name="amount" step="0.01" min="0.01"
                           value="<?= in_array($sc, ['final_quote_sent','final_quote_rejected','final_quote_renegotiation']) ? ($repair['final_quote_amount']??'') : ($repair['initial_quote_amount']??'') ?>"
                           placeholder="0.00" required>
                </div>
            </div>
            <div class="f-group">
                <label>Opis</label>
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

    <!-- Forma płatności + potwierdzenie -->
    <?php if (in_array($sc, ['awaiting_payment','in_repair','final_quote_accepted'])): ?>
    <div class="admin-card" style="border-color:rgba(234,179,8,0.2)">
        <h3 style="color:#eab308">💳 Forma płatności dla klienta</h3>
        <p class="detail-text" style="margin-bottom:16px">Wybierz formę — klient zobaczy odpowiednie instrukcje w swoim panelu.</p>
        <?php if (!$repair['final_quote_amount']): ?>
        <p style="color:#f87171;font-size:13px;margin-bottom:12px">⚠ Najpierw wyślij koszt naprawy do klienta i poczekaj na akceptację.</p>
        <?php else: ?>
        <?php
            $repair_cost = (float)($repair['final_quote_amount'] ?? 0);
            $shipping    = (float)($repair['shipping_cost'] ?? 25);
            $total       = $repair_cost + $shipping;
        ?>
        <div style="background:var(--bg4);border-radius:10px;padding:14px 16px;margin-bottom:16px;font-size:14px">
            <div style="display:flex;justify-content:space-between;margin-bottom:6px"><span style="color:var(--tm)">Koszt naprawy:</span><strong><?= formatMoney($repair_cost) ?></strong></div>
            <div style="display:flex;justify-content:space-between;margin-bottom:6px"><span style="color:var(--tm)">Wysyłka zwrotna:</span><strong><?= formatMoney($shipping) ?></strong></div>
            <div style="display:flex;justify-content:space-between;border-top:1px solid var(--bd);padding-top:8px;margin-top:4px"><span style="color:#fff;font-weight:600">Do zapłaty:</span><strong style="color:#22c55e;font-size:18px"><?= formatMoney($total) ?></strong></div>
        </div>
        <form method="POST" action="/admin/naprawa/<?= $repair['id'] ?>/ustaw-platnosc" class="admin-form">
            <input type="hidden" name="amount" value="<?= $total ?>">
            <div class="f-group">
                <label>Forma płatności</label>
                <select name="method" required>
                    <option value="transfer" <?= ($repair['payment_method'] ?? '') === 'transfer' ? 'selected' : '' ?>>💳 Przelew bankowy</option>
                    <option value="blik"     <?= ($repair['payment_method'] ?? '') === 'blik'     ? 'selected' : '' ?>>📱 BLIK</option>
                    <option value="cash"     <?= ($repair['payment_method'] ?? '') === 'cash'     ? 'selected' : '' ?>>💵 Gotówka przy odbiorze</option>
                </select>
            </div>
            <button type="submit" class="a-btn a-btn-primary">Zapisz i ustaw oczekiwanie na płatność</button>
        </form>
        <?php endif; ?>
        <?php if ($repair['payment_method']): ?>
        <div style="margin-top:16px;padding:12px 16px;background:rgba(0,0,0,0.2);border-radius:10px;font-size:13px;color:var(--tm)">
            Aktualnie ustawiona forma:
            <?php $pm = ['transfer'=>'💳 Przelew bankowy','blik'=>'📱 BLIK','cash'=>'💵 Gotówka']; ?>
            <strong style="color:var(--t)"><?= $pm[$repair['payment_method']] ?? $repair['payment_method'] ?></strong>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ($sc === 'awaiting_payment'): ?>
    <div class="admin-card" style="border-color:rgba(34,197,94,0.2)">
        <h3 style="color:#22c55e">✓ Potwierdź otrzymanie płatności</h3>
        <p class="detail-text" style="margin-bottom:16px">
            Kwota: <strong style="color:#22c55e;font-size:18px"><?= formatMoney((float)($repair['final_quote_amount'] ?? 0)) ?></strong>
        </p>
        <form method="POST" action="/admin/naprawa/<?= $repair['id'] ?>/oplacone" class="admin-form">
            <input type="hidden" name="amount" value="<?= $repair['final_quote_amount'] ?? 0 ?>">
            <input type="hidden" name="method" value="<?= $repair['payment_method'] ?? 'transfer' ?>">
            <p class="detail-text" style="margin-bottom:12px">
                <?php $pm = ['transfer'=>'💳 Przelew','blik'=>'📱 BLIK','cash'=>'💵 Gotówka']; ?>
                Forma: <strong><?= $pm[$repair['payment_method'] ?? 'transfer'] ?? '—' ?></strong>
            </p>
            <button type="submit" class="a-btn a-btn-primary">✓ Płatność otrzymana — oznacz jako opłacone</button>
        </form>
    </div>
    <?php endif; ?>

    <!-- Zwrot sprzętu — przy odrzuceniu wyceny lub kosztu -->
    <?php if (in_array($sc, ['initial_quote_rejected','initial_quote_renegotiation','final_quote_rejected','final_quote_renegotiation'])): ?>
    <div class="admin-card" style="border-color:rgba(239,68,68,0.2)">
        <h3 style="color:#f87171">Zwrot sprzętu</h3>
        <?php if (!$repair['return_first_name']): ?>
            <p style="color:#f87171;font-size:13px;margin-bottom:12px">⚠ Klient nie podał jeszcze adresu zwrotnego!</p>
        <?php else: ?>
            <p class="detail-text" style="margin-bottom:12px">
                Adres zwrotny: <strong><?= sanitize($repair['return_first_name'].' '.$repair['return_last_name'].', '.$repair['return_street'].', '.$repair['return_postal'].' '.$repair['return_city']) ?></strong>
            </p>
        <?php endif; ?>
        <form method="POST" action="/admin/naprawa/<?= $repair['id'] ?>/zwrot">
            <input type="hidden" name="note" value="Zwrot sprzętu do klienta">
            <button type="submit" class="a-btn" style="background:rgba(239,68,68,0.12);color:#f87171;border:1px solid rgba(239,68,68,0.3)">
                Oznacz jako zwrot sprzętu
            </button>
        </form>
    </div>
    <?php endif; ?>

</div>

<!-- Historia statusów -->
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

<?php
$repair_id = $repair['id'];
$current_user_id = $_SESSION['user_id'];
$is_admin = true;
include VIEW_PATH.'/partials/chat.php';
?>
