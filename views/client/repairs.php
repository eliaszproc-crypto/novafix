<section class="panel-section">
    <div class="container">
        <div class="panel-header">
            <div>
                <h1>Moje zgłoszenia</h1>
                <p>Historia wszystkich Twoich napraw.</p>
            </div>
            <a href="/panel/nowe-zgloszenie" class="btn btn--primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                Nowe zgłoszenie
            </a>
        </div>
        <div class="panel-card">
            <?php if (empty($repairs)): ?>
                <div class="panel-empty">
                    <p>Nie masz jeszcze żadnych zgłoszeń.</p>
                    <a href="/panel/nowe-zgloszenie" class="btn btn--primary">Zgłoś pierwsze urządzenie</a>
                </div>
            <?php else: ?>
                <div class="repairs-list">
                    <?php foreach ($repairs as $r): ?>
                    <a href="/panel/naprawa/<?= $r['id'] ?>" class="repair-item">
                        <div class="repair-item__info">
                            <strong><?= sanitize($r['rma_number']) ?></strong>
                            <span><?= sanitize($r['device_type']) ?><?= $r['device_brand'] ? ' — ' . sanitize($r['device_brand']) : '' ?></span>
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
