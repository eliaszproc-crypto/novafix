<section class="panel-section">
<div class="container">

    <div class="panel-header">
        <div>
            <h1><?= sanitize($repair['rma_number']) ?></h1>
            <div class="repair-status-badge" style="background:<?= $repair['status_color'] ?>22;color:<?= $repair['status_color'] ?>;border:1px solid <?= $repair['status_color'] ?>44">
                <span class="status-dot" style="background:<?= $repair['status_color'] ?>"></span>
                <?= sanitize($repair['status_label']) ?>
            </div>
        </div>
        <a href="/panel" class="btn btn--ghost">← Wróć</a>
    </div>

    <?php if ($success): ?>
        <div class="alert alert--success"><?= sanitize($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert--error"><?= sanitize($error) ?></div>
    <?php endif; ?>

    <div class="detail-grid">
    <div class="detail-main">

        <!-- Szczegóły -->
        <div class="panel-card">
            <h3>Szczegóły urządzenia</h3>
            <div class="detail-info">
                <div><span>Typ:</span><strong><?= sanitize($repair['device_type']) ?></strong></div>
                <?php if ($repair['device_brand']): ?><div><span>Marka:</span><strong><?= sanitize($repair['device_brand']) ?></strong></div><?php endif; ?>
                <?php if ($repair['device_model']): ?><div><span>Model:</span><strong><?= sanitize($repair['device_model']) ?></strong></div><?php endif; ?>
                <div><span>Data zgłoszenia:</span><strong><?= date('d.m.Y H:i', strtotime($repair['created_at'])) ?></strong></div>
            </div>
        </div>

        <!-- Opis -->
        <div class="panel-card">
            <h3>Opis problemu</h3>
            <p class="detail-text"><?= nl2br(sanitize($repair['problem_description'])) ?></p>
        </div>

        <?php if (!empty($photos)): ?>
        <div class="panel-card">
            <h3>Zdjęcia</h3>
            <div class="photos-grid">
                <?php foreach ($photos as $p): ?>
                    <a href="/uploads/<?= $p['filename'] ?>" target="_blank"><img src="/uploads/<?= $p['filename'] ?>" alt=""></a>
                <?php endforeach; ?>
            </div>
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

            <?php if ($repair['status_code'] === 'initial_quote_sent'): ?>
                <!-- Przyciski akceptacji -->
                <div class="quote-actions">
                    <form method="POST" action="/panel/naprawa/<?= $repair['id'] ?>/akceptuj-wycene">
                        <button type="submit" class="btn btn--primary">✓ Akceptuję wycenę</button>
                    </form>
                    <button type="button" class="btn btn--ghost" onclick="toggleReject('reject-initial')">✗ Odrzucam wycenę</button>
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
            <?php elseif ($repair['status_code'] === 'initial_quote_accepted'): ?>
                <div class="quote-decision accepted">✓ Zaakceptowano <?= $repair['initial_quote_decided_at'] ? date('d.m.Y', strtotime($repair['initial_quote_decided_at'])) : '' ?></div>
            <?php elseif (in_array($repair['status_code'], ['initial_quote_rejected','initial_quote_renegotiation'])): ?>
                <div class="quote-decision rejected">✗ Odrzucono <?= $repair['initial_quote_decided_at'] ? date('d.m.Y', strtotime($repair['initial_quote_decided_at'])) : '' ?></div>
                <?php if ($repair['initial_quote_rejection_note']): ?>
                    <p class="detail-text" style="margin-top:8px">Twój komentarz: <em><?= sanitize($repair['initial_quote_rejection_note']) ?></em></p>
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

            <?php if ($repair['status_code'] === 'final_quote_sent'): ?>
                <div class="quote-actions">
                    <form method="POST" action="/panel/naprawa/<?= $repair['id'] ?>/akceptuj-koszt">
                        <button type="submit" class="btn btn--primary">✓ Akceptuję koszt naprawy</button>
                    </form>
                    <button type="button" class="btn btn--ghost" onclick="toggleReject('reject-final')">✗ Odrzucam</button>
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
            <?php elseif ($repair['status_code'] === 'final_quote_accepted'): ?>
                <div class="quote-decision accepted">✓ Zaakceptowano — naprawa w toku</div>
            <?php elseif (in_array($repair['status_code'], ['final_quote_rejected','final_quote_renegotiation'])): ?>
                <div class="quote-decision rejected">✗ Odrzucono</div>
                <?php if ($repair['final_quote_rejection_note']): ?>
                    <p class="detail-text" style="margin-top:8px">Twój komentarz: <em><?= sanitize($repair['final_quote_rejection_note']) ?></em></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- ADRES ZWROTNY -->
        <?php
        $show_address = in_array($repair['status_code'], [
            'initial_quote_accepted','shipping_instructions','parcel_received',
            'diagnosis','final_quote_sent','final_quote_accepted','final_quote_rejected',
            'final_quote_renegotiation','in_repair','awaiting_payment','paid',
            'shipped_to_client','return_in_progress'
        ]);
        ?>
        <?php if ($show_address): ?>
        <div class="panel-card">
            <h3>Adres zwrotny</h3>
            <p class="detail-text" style="margin-bottom:16px">Adres na który wyślemy naprawiony sprzęt lub go zwrócimy.</p>
            <?php if ($repair['return_address']): ?>
                <div class="address-display">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <?= nl2br(sanitize($repair['return_address'])) ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="/panel/naprawa/<?= $repair['id'] ?>/adres-zwrotny" style="margin-top:14px">
                <div class="form-group">
                    <label><?= $repair['return_address'] ? 'Zmień adres zwrotny' : 'Podaj adres zwrotny' ?></label>
                    <textarea name="return_address" rows="3" placeholder="Imię Nazwisko&#10;ul. Przykładowa 1&#10;00-000 Miasto"><?= sanitize($repair['return_address'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn btn--primary">Zapisz adres</button>
            </form>
        </div>
        <?php endif; ?>

    </div>

    <!-- Historia statusów -->
    <div class="detail-side">
        <div class="panel-card">
            <h3>Historia statusów</h3>
            <div class="history-list">
                <?php foreach ($history as $h): ?>
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
function toggleReject(id) {
    const el = document.getElementById(id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>
