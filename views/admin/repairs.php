<div class="admin-filters">
    <form method="GET" class="filters-form">
        <input type="text" name="q" value="<?= sanitize($_GET['q'] ?? '') ?>" placeholder="Szukaj po RMA, nazwisku, emailu..." class="filter-input">
        <select name="status" class="filter-select">
            <option value="">Wszystkie statusy</option>
            <?php foreach ($statuses as $s): ?>
                <option value="<?= $s['code'] ?>" <?= ($_GET['status'] ?? '') === $s['code'] ? 'selected' : '' ?>><?= sanitize($s['label']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn--primary btn--sm">Filtruj</button>
        <?php if (!empty($_GET['q']) || !empty($_GET['status'])): ?>
            <a href="/admin/zgloszenia" class="btn btn--ghost btn--sm">Wyczyść</a>
        <?php endif; ?>
    </form>
</div>

<div class="admin-card">
    <?php if (empty($repairs)): ?>
        <p style="color:var(--tm);text-align:center;padding:32px">Brak zgłoszeń spełniających kryteria.</p>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>RMA</th><th>Klient</th><th>Urządzenie</th><th>Marka/Model</th><th>Status</th><th>Data</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($repairs as $r): ?>
            <tr>
                <td><strong><?= sanitize($r['rma_number']) ?></strong></td>
                <td>
                    <strong><?= sanitize($r['first_name'] . ' ' . $r['last_name']) ?></strong>
                    <span style="display:block;font-size:12px;color:var(--tm)"><?= sanitize($r['email']) ?></span>
                </td>
                <td><?= sanitize($r['device_type']) ?></td>
                <td><?= sanitize(($r['device_brand'] ?? '') . ' ' . ($r['device_model'] ?? '')) ?></td>
                <td><span class="status-pill" style="background:<?= $r['status_color'] ?>22;color:<?= $r['status_color'] ?>"><?= sanitize($r['status_label']) ?></span></td>
                <td><?= date('d.m.Y', strtotime($r['created_at'])) ?></td>
                <td><a href="/admin/naprawa/<?= $r['id'] ?>" class="table-link">Otwórz →</a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
