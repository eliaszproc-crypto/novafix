<section class="panel-section">
    <div class="container">
        <div class="panel-header">
            <div>
                <h1>Witaj, <?= sanitize($_SESSION['user_name']) ?>!</h1>
                <p>Zarządzaj swoimi zgłoszeniami napraw.</p>
            </div>
            <a href="/panel/diagnostyka" class="btn btn--ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/><circle cx="12" cy="12" r="10"/></svg>
                Diagnoza AI
            </a>
            <a href="/panel/diagnostyka" class="btn btn--ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Diagnoza wstępna
            </a>
            <a href="/panel/nowe-zgloszenie" class="btn btn--primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                Nowe zgłoszenie
            </a>
        </div>

        <div class="panel-stats">
            <div class="panel-stat">
                <strong><?= $total ?></strong>
                <span>Wszystkich zgłoszeń</span>
            </div>
            <div class="panel-stat">
                <strong><?= count(array_filter($repairs, fn($r) => !in_array($r['status_label'], ['Zakończone', 'Wstępna wycena odrzucona', 'Finalna wycena odrzucona']))) ?></strong>
                <span>Aktywnych napraw</span>
            </div>
        </div>

        <div class="panel-card">
            <div class="panel-card__header">
                <h2>Ostatnie zgłoszenia</h2>
                <a href="/panel/zgloszenia">Zobacz wszystkie →</a>
            </div>
            <?php if (empty($repairs)): ?>
                <div class="panel-empty">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                    <p>Nie masz jeszcze żadnych zgłoszeń.</p>
                    <a href="/panel/diagnostyka" class="btn btn--ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/><circle cx="12" cy="12" r="10"/></svg>
                Diagnoza AI
            </a>
            <a href="/panel/diagnostyka" class="btn btn--ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Diagnoza wstępna
            </a>
            <a href="/panel/nowe-zgloszenie" class="btn btn--primary">Zgłoś pierwsze urządzenie</a>
                </div>
            <?php else: ?>
                <div class="repairs-list">
                    <?php foreach ($repairs as $r): ?>
                    <a href="/panel/naprawa/<?= $r['id'] ?>" class="repair-item">
                        <div class="repair-item__info">
                            <strong><?= sanitize($r['rma_number']) ?></strong>
                            <span><?= sanitize($r['device_type']) ?><?= $r['device_brand'] ? ' — ' . sanitize($r['device_brand']) : '' ?><?= $r['device_model'] ? ' ' . sanitize($r['device_model']) : '' ?></span>
                        </div>
                        <div class="repair-item__status" style="color:<?= $r['status_color'] ?>">
                            <span class="status-dot" style="background:<?= $r['status_color'] ?>"></span>
                            <?= sanitize($r['status_label']) ?>
                        </div>
                        <div class="repair-item__date"><?= date('d.m.Y', strtotime($r['created_at'])) ?></div>
                        <div class="repair-item__arrow">→</div>
                    </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
