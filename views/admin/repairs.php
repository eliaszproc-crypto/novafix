<div class="admin-filters">
    <form method="GET" class="filters-form">
        <input type="text" name="q" value="<?= sanitize($_GET['q'] ?? '') ?>"
               placeholder="Szukaj po RMA, nazwisku, emailu..." class="filter-input">
        <select name="status" class="filter-select">
            <option value="">Wszystkie statusy</option>
            <?php foreach ($statuses as $s): ?>
                <option value="<?= $s['code'] ?>" <?= ($_GET['status'] ?? '') === $s['code'] ? 'selected' : '' ?>>
                    <?= sanitize($s['label']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="a-btn a-btn-primary">Filtruj</button>
        <?php if (!empty($_GET['q']) || !empty($_GET['status'])): ?>
            <a href="/admin/zgloszenia" class="a-btn a-btn-secondary">Wyczyść</a>
        <?php endif; ?>
    </form>
</div>

<div class="admin-card">
    <h2>Zgłoszenia (<?= count($repairs) ?>)</h2>
    <?php if (empty($repairs)): ?>
        <p style="color:var(--tm);text-align:center;padding:24px">Brak zgłoszeń spełniających kryteria.</p>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>RMA</th>
                    <th>Klient</th>
                    <th>Email</th>
                    <th>Urządzenie</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($repairs as $r): ?>
            <tr>
                <td><strong style="color:#fff"><?= sanitize($r['rma_number']) ?></strong></td>
                <td><?= sanitize($r['first_name'] . ' ' . $r['last_name']) ?></td>
                <td style="color:var(--tm);font-size:13px"><?= sanitize($r['email']) ?></td>
                <td>
                    <?= sanitize($r['device_type']) ?>
                    <?php if ($r['device_brand']): ?>
                        <span style="color:var(--tm)"> — <?= sanitize($r['device_brand']) ?></span>
                    <?php endif; ?>
                </td>
                <td><span class="status-pill" style="background:<?= $r['status_color'] ?>22;color:<?= $r['status_color'] ?>"><?= sanitize($r['status_label']) ?></span></td>
                <td style="color:var(--tm);font-size:13px"><?= date('d.m.Y', strtotime($r['created_at'])) ?></td>
                <td><a href="/admin/naprawa/<?= $r['id'] ?>" class="table-link">Otwórz →</a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
