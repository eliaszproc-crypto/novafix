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

        <div class="detail-grid">
            <div class="detail-main">
                <div class="panel-card">
                    <h3>Szczegóły urządzenia</h3>
                    <div class="detail-info">
                        <div><span>Typ:</span><strong><?= sanitize($repair['device_type']) ?></strong></div>
                        <?php if ($repair['device_brand']): ?>
                        <div><span>Marka:</span><strong><?= sanitize($repair['device_brand']) ?></strong></div>
                        <?php endif; ?>
                        <?php if ($repair['device_model']): ?>
                        <div><span>Model:</span><strong><?= sanitize($repair['device_model']) ?></strong></div>
                        <?php endif; ?>
                        <div><span>Data zgłoszenia:</span><strong><?= date('d.m.Y H:i', strtotime($repair['created_at'])) ?></strong></div>
                    </div>
                </div>

                <div class="panel-card">
                    <h3>Opis problemu</h3>
                    <p class="detail-text"><?= nl2br(sanitize($repair['problem_description'])) ?></p>
                </div>

                <?php if (!empty($photos)): ?>
                <div class="panel-card">
                    <h3>Zdjęcia</h3>
                    <div class="photos-grid">
                        <?php foreach ($photos as $photo): ?>
                            <a href="/uploads/<?= $photo['filename'] ?>" target="_blank">
                                <img src="/uploads/<?= $photo['filename'] ?>" alt="Zdjęcie">
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($repair['initial_quote_amount']): ?>
                <div class="panel-card quote-card">
                    <h3>Wstępna wycena</h3>
                    <div class="quote-amount"><?= formatMoney($repair['initial_quote_amount']) ?></div>
                    <?php if ($repair['initial_quote_note']): ?>
                        <p class="detail-text"><?= nl2br(sanitize($repair['initial_quote_note'])) ?></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if ($repair['final_quote_amount']): ?>
                <div class="panel-card quote-card">
                    <h3>Finalna wycena</h3>
                    <div class="quote-amount"><?= formatMoney($repair['final_quote_amount']) ?></div>
                    <?php if ($repair['final_quote_note']): ?>
                        <p class="detail-text"><?= nl2br(sanitize($repair['final_quote_note'])) ?></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if ($repair['repair_report']): ?>
                <div class="panel-card">
                    <h3>Raport z naprawy</h3>
                    <p class="detail-text"><?= nl2br(sanitize($repair['repair_report'])) ?></p>
                </div>
                <?php endif; ?>
            </div>

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
                                <?php if ($h['note']): ?>
                                    <p><?= sanitize($h['note']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
