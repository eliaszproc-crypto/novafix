<section class="panel-section">
<div class="container">
<?php $sc = $repair['status_code']; ?>

    <div class="panel-header">
        <div>
            <h1><?= sanitize($repair['rma_number']) ?></h1>
            <div class="repair-status-badge" style="background:<?= $repair['status_color'] ?>22;color:<?= $repair['status_color'] ?>;border:1px solid <?= $repair['status_color'] ?>44">
                <span class="status-dot" style="background:<?= $repair['status_color'] ?>"></span>
                <?= sanitize($repair['status_label']) ?>
            </div>
        </div>
        <div style="display:flex;gap:10px;align-items:center">
            <a href="/panel" class="btn btn--ghost">← Wróć</a>
            <?php
            $can_delete = !in_array($sc, ['paid','awaiting_payment','shipped_to_client','completed']);
            if ($can_delete): ?>
            <form method="POST" action="/panel/naprawa/<?= $repair['id'] ?>/usun"
                  onsubmit="return confirm('Czy na pewno chcesz usunąć to zlecenie? Tej operacji nie można cofnąć.')">
                <button type="submit" class="btn btn--ghost" style="color:#f87171;border-color:rgba(239,68,68,0.3)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                    Usuń zlecenie
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($success): ?><div class="alert alert--success"><?= sanitize($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert--error"><?= sanitize($error) ?></div><?php endif; ?>

    <?php
    $sa = $config['service_address'];
    $has_return_address = $repair['return_first_name'] && $repair['return_city'];
    ?>

    <!-- INSTRUKCJA WYSYŁKI — po akceptacji wstępnej wyceny -->
    <?php if (in_array($sc, ['initial_quote_accepted','parcel_received','diagnosis','final_quote_sent','final_quote_accepted','final_quote_rejected','final_quote_renegotiation','in_repair','awaiting_payment','paid','shipped_to_client','completed'])): ?>
    <div class="info-box info-box--shipping">
        <div class="info-box__icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        </div>
        <div class="info-box__content">
            <?php if ($sc === 'initial_quote_accepted'): ?>
                <h4>Zapakuj starannie sprzęt i wyślij go na nasz adres:</h4>
            <?php else: ?>
                <h4>Adres serwisu:</h4>
            <?php endif; ?>
            <div class="service-address">
                <strong><?= sanitize($sa['name']) ?></strong><br>
                <?= sanitize($sa['street']) ?><br>
                <?= sanitize($sa['postal']) ?> <?= sanitize($sa['city']) ?><br>
                Tel: <?= sanitize($sa['phone']) ?> | <?= sanitize($sa['email']) ?>
            </div>
            <?php if ($sc === 'initial_quote_accepted'): ?>
                <p class="info-box__tip">⚠ Pamiętaj o dołączeniu numeru zgłoszenia <strong><?= sanitize($repair['rma_number']) ?></strong> do paczki.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="detail-grid">
    <div class="detail-main">

        <!-- Szczegóły urządzenia -->
        <div class="panel-card">
            <h3>Szczegóły urządzenia</h3>
            <div class="detail-info">
                <div><span>Typ:</span><strong><?= sanitize($repair['device_type']) ?></strong></div>
                <?php if ($repair['device_brand']): ?><div><span>Marka:</span><strong><?= sanitize($repair['device_brand']) ?></strong></div><?php endif; ?>
                <?php if ($repair['device_model']): ?><div><span>Model:</span><strong><?= sanitize($repair['device_model']) ?></strong></div><?php endif; ?>
                <div><span>Data zgłoszenia:</span><strong><?= date('d.m.Y H:i', strtotime($repair['created_at'])) ?></strong></div>
            </div>
        </div>

        <!-- Opis problemu -->
        <div class="panel-card">
            <h3>Opis problemu</h3>
            <p class="detail-text"><?= nl2br(sanitize($repair['problem_description'])) ?></p>
        </div>

        <?php if (!empty($photos)): ?>
        <div class="panel-card">
            <h3>Zdjęcia (<?= count($photos) ?>/5)</h3>
            <div class="photos-grid-editable">
                <?php foreach ($photos as $p): ?>
                <div class="photo-thumb">
                    <a href="/uploads/<?= $p['filename'] ?>" target="_blank">
                        <img src="/uploads/<?= $p['filename'] ?>" alt="">
                    </a>
                    <?php if ($repair['status_code'] === 'new'): ?>
                    <form method="POST" action="/panel/naprawa/<?= $repair['id'] ?>/usun-zdjecie/<?= $p['id'] ?>"
                          onsubmit="return confirm('Usunąć to zdjęcie?')">
                        <button type="submit" class="photo-thumb__del" title="Usuń zdjęcie">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- NADANIE PACZKI - po akceptacji wstępnej wyceny -->
        <?php if (in_array($sc, ['initial_quote_accepted','parcel_sent'])): ?>
        <div class="panel-card" style="border-color:rgba(6,182,212,0.2)">
            <h3><?= $sc === 'parcel_sent' ? '📦 Paczka w drodze' : '📦 Nadaj paczkę' ?></h3>
            <?php if ($sc === 'parcel_sent' && $repair['tracking_number']): ?>
                <div class="address-display" style="margin-bottom:16px">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    <div>Numer przesyłki: <strong style="color:var(--c)"><?= sanitize($repair['tracking_number']) ?></strong></div>
                </div>
                <p class="detail-text">Paczka nadana — czekam na jej odbiór. Możesz zaktualizować numer jeśli podałeś błędny.</p>
            <?php else: ?>
                <p class="detail-text" style="margin-bottom:16px">Zapakuj starannie sprzęt i nadaj na paczkomat <strong style="color:var(--c)">SCZ04M</strong> w Szczecinku. Podaj numer przesyłki żeby automatycznie zaktualizować status.</p>
            <?php endif; ?>
            <form method="POST" action="/panel/naprawa/<?= $repair['id'] ?>/nadanie-paczki">
                <div class="form-row">
                    <div class="form-group">
                        <label>Numer przesyłki / paczkomatu</label>
                        <input type="text" name="tracking_number" value="<?= sanitize($repair['tracking_number'] ?? '') ?>" placeholder="np. 123456789012345678" required>
                    </div>
                    <div class="form-group">
                        <label>Przewoźnik</label>
                        <select name="carrier">
                            <option value="InPost">InPost / Paczkomat</option>
                            <option value="DPD">DPD</option>
                            <option value="DHL">DHL</option>
                            <option value="UPS">UPS</option>
                            <option value="Poczta Polska">Poczta Polska</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn--primary" style="margin-top:4px">
                    <?= $sc === 'parcel_sent' ? 'Zaktualizuj numer' : '📦 Potwierdzam nadanie paczki' ?>
                </button>
            </form>
        </div>
        <?php endif; ?>

        <!-- WSTĘPNA WYCENA -->
        <?php if ($repair['initial_quote_amount']): ?>
        <div class="panel-card quote-card">
            <h3>Wstępna wycena</h3>
            <div class="quote-amount"><?= formatMoney((float)$repair['initial_quote_amount']) ?></div>
            <?php if ($repair['initial_quote_note']): ?>
                <p class="detail-text" style="margin-bottom:20px"><?= nl2br(sanitize($repair['initial_quote_note'])) ?></p>
            <?php endif; ?>

            <?php if ($sc === 'initial_quote_sent'): ?>
                <div class="quote-actions">
                    <form method="POST" action="/panel/naprawa/<?= $repair['id'] ?>/akceptuj-wycene">
                        <button type="submit" class="btn btn--primary">✓ Akceptuję wycenę</button>
                    </form>
                    <button type="button" class="btn btn--ghost" onclick="toggleEl('reject-initial')">✗ Odrzucam wycenę</button>
                </div>
                <div id="reject-initial" class="reject-form" style="display:none">
                    <form method="POST" action="/panel/naprawa/<?= $repair['id'] ?>/odrzuc-wycene">
                        <div class="form-group">
                            <label>Dlaczego odrzucasz wycenę? (opcjonalnie)</label>
                            <textarea name="rejection_note" rows="3" placeholder="Np. cena jest za wysoka, chciałbym negocjować..."></textarea>
                        </div>
                        <button type="submit" class="btn btn--ghost">Wyślij odrzucenie</button>
                    </form>
                </div>
            <?php elseif (in_array($sc, ['initial_quote_accepted','parcel_received','diagnosis','final_quote_sent','final_quote_accepted','final_quote_rejected','final_quote_renegotiation','in_repair','awaiting_payment','paid','shipped_to_client','completed'])): ?>
                <div class="quote-decision accepted">✓ Zaakceptowano <?= $repair['initial_quote_decided_at'] ? date('d.m.Y', strtotime($repair['initial_quote_decided_at'])) : '' ?></div>
            <?php elseif (in_array($sc, ['initial_quote_rejected','initial_quote_renegotiation'])): ?>
                <div class="quote-decision rejected">✗ Odrzucono</div>
                <?php if ($repair['initial_quote_rejection_note']): ?>
                    <p class="detail-text" style="margin-top:8px"><em>Twój komentarz: <?= sanitize($repair['initial_quote_rejection_note']) ?></em></p>
                <?php endif; ?>
                <?php if ($sc === 'initial_quote_renegotiation'): ?>
                    <p class="detail-text" style="margin-top:8px;color:var(--c)">Serwis pracuje nad nową propozycją...</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- KOSZT NAPRAWY -->
        <?php if ($repair['final_quote_amount']): ?>
        <div class="panel-card quote-card" style="border-color:rgba(0,229,255,0.2)">
            <h3>Koszt naprawy</h3>
            <div class="quote-amount"><?= formatMoney((float)$repair['final_quote_amount']) ?></div>
            <?php if ($repair['final_quote_note']): ?>
                <p class="detail-text" style="margin-bottom:20px"><?= nl2br(sanitize($repair['final_quote_note'])) ?></p>
            <?php endif; ?>

            <?php if ($sc === 'final_quote_sent'): ?>
                <div class="quote-actions">
                    <form method="POST" action="/panel/naprawa/<?= $repair['id'] ?>/akceptuj-koszt">
                        <button type="submit" class="btn btn--primary">✓ Akceptuję koszt naprawy</button>
                    </form>
                    <button type="button" class="btn btn--ghost" onclick="toggleEl('reject-final')">✗ Odrzucam</button>
                </div>
                <div id="reject-final" class="reject-form" style="display:none">
                    <form method="POST" action="/panel/naprawa/<?= $repair['id'] ?>/odrzuc-koszt">
                        <div class="form-group">
                            <label>Dlaczego odrzucasz koszt naprawy?</label>
                            <textarea name="rejection_note" rows="3" placeholder="Np. koszt jest za wysoki, proszę o negocjację..."></textarea>
                        </div>
                        <button type="submit" class="btn btn--ghost">Wyślij odrzucenie</button>
                    </form>
                </div>
            <?php elseif (in_array($sc, ['final_quote_accepted','in_repair','awaiting_payment','paid','shipped_to_client','completed'])): ?>
                <div class="quote-decision accepted">✓ Zaakceptowano — naprawa w toku</div>
            <?php elseif (in_array($sc, ['final_quote_rejected','final_quote_renegotiation'])): ?>
                <div class="quote-decision rejected">✗ Odrzucono</div>
                <?php if ($repair['final_quote_rejection_note']): ?>
                    <p class="detail-text" style="margin-top:8px"><em>Twój komentarz: <?= sanitize($repair['final_quote_rejection_note']) ?></em></p>
                <?php endif; ?>
                <?php if ($sc === 'final_quote_renegotiation'): ?>
                    <p class="detail-text" style="margin-top:8px;color:var(--c)">Serwis pracuje nad nową propozycją kosztu...</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- ADRES ZWROTNY -->
        <div class="panel-card">
            <h3>Adres zwrotny</h3>
            <p class="detail-text" style="margin-bottom:16px">Na ten adres wyślemy naprawiony sprzęt lub zwrócimy go w razie rezygnacji.</p>

            <?php if ($has_return_address): ?>
            <div class="address-display">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <div>
                    <strong><?= sanitize($repair['return_first_name'].' '.$repair['return_last_name']) ?></strong><br>
                    <?= sanitize($repair['return_street']) ?><br>
                    <?= sanitize($repair['return_postal']) ?> <?= sanitize($repair['return_city']) ?>
                    <?php if ($repair['return_phone']): ?><br>Tel: <?= sanitize($repair['return_phone']) ?><?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <button type="button" class="btn btn--ghost" style="margin-top:12px" onclick="toggleEl('edit-address')">
                <?= $has_return_address ? 'Zmień adres' : 'Podaj adres zwrotny' ?>
            </button>

            <div id="edit-address" style="display:<?= $has_return_address ? 'none' : 'block' ?>;margin-top:16px">
                <form method="POST" action="/panel/naprawa/<?= $repair['id'] ?>/adres-zwrotny">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Imię <span class="required">*</span></label>
                            <input type="text" name="return_first_name" value="<?= sanitize($repair['return_first_name']??'') ?>" placeholder="Jan" required>
                        </div>
                        <div class="form-group">
                            <label>Nazwisko <span class="required">*</span></label>
                            <input type="text" name="return_last_name" value="<?= sanitize($repair['return_last_name']??'') ?>" placeholder="Kowalski" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Telefon</label>
                        <input type="tel" name="return_phone" value="<?= sanitize($repair['return_phone']??'') ?>" placeholder="+48 123 456 789">
                    </div>
                    <div class="form-group">
                        <label>Ulica i numer <span class="required">*</span></label>
                        <input type="text" name="return_street" value="<?= sanitize($repair['return_street']??'') ?>" placeholder="ul. Przykładowa 1" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Kod pocztowy <span class="required">*</span></label>
                            <input type="text" name="return_postal" value="<?= sanitize($repair['return_postal']??'') ?>" placeholder="00-000" required>
                        </div>
                        <div class="form-group">
                            <label>Miasto <span class="required">*</span></label>
                            <input type="text" name="return_city" value="<?= sanitize($repair['return_city']??'') ?>" placeholder="Warszawa" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn--primary" style="margin-top:4px">Zapisz adres</button>
                </form>
            </div>
        </div>

    </div>

        <!-- OCZEKUJE NA PŁATNOŚĆ -->
        <?php if ($sc === 'awaiting_payment'): ?>
        <?php
            $payment_info = $pdo->query("SELECT method FROM payments WHERE repair_id={$repair['id']} ORDER BY id DESC LIMIT 1")->fetch();
            $method = $payment_info['method'] ?? 'transfer';
        ?>
        <div class="panel-card" style="border-color:rgba(234,179,8,0.25)">
            <h3 style="color:#eab308">💳 Oczekuje na płatność</h3>
            <div class="quote-amount" style="margin-bottom:12px"><?= formatMoney((float)$repair['final_quote_amount']) ?></div>
            <?php if ($method === 'transfer'): ?>
                <div class="payment-info">
                    <p><strong>Forma płatności:</strong> Przelew bankowy</p>
                    <div class="payment-details">
                        <div><span>Numer konta:</span><strong>PL 00 0000 0000 0000 0000 0000 0000</strong></div>
                        <div><span>Odbiorca:</span><strong>Eliasz Proć — NovaFix</strong></div>
                        <div><span>Tytuł przelewu:</span><strong><?= sanitize($repair['rma_number']) ?></strong></div>
                        <div><span>Kwota:</span><strong style="color:#eab308"><?= formatMoney((float)$repair['final_quote_amount']) ?></strong></div>
                    </div>
                    <p class="detail-text" style="margin-top:12px">Po zaksięgowaniu płatności zmienię status i wyślę sprzęt.</p>
                </div>
            <?php elseif ($method === 'cash'): ?>
                <p class="detail-text"><strong>Forma płatności:</strong> Gotówka przy odbiorze</p>
            <?php elseif ($method === 'card'): ?>
                <p class="detail-text"><strong>Forma płatności:</strong> Karta płatnicza</p>
            <?php else: ?>
                <p class="detail-text"><strong>Forma płatności:</strong> Skontaktuję się w sprawie płatności.</p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    <!-- Historia statusów -->
    <div class="detail-side">
        <div class="panel-card">
            <h3>Historia statusów</h3>
            <div class="history-list">
                <?php foreach (array_reverse($history) as $h): ?>
                <div class="history-item">
                    <div class="history-dot" style="background:<?= $h['color'] ?>"></div>
                    <div class="history-content">
                        <strong style="color:<?= $h['color'] ?>"><?= sanitize($h['label']) ?></strong>
                        <span><?= date('d.m.Y H:i', strtotime($h['changed_at'])) ?></span>
                        <?php if ($h['note']): ?><p><?= sanitize($h['note']) ?></p><?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    </div>
</div>
</section>
<script>
function toggleEl(id) {
    const el = document.getElementById(id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>
