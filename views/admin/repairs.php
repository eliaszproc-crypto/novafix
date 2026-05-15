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

<?php if ($success): ?><div class="a-alert a-alert--success"><?= sanitize($success) ?></div><?php endif; ?>

<div class="admin-card">
    <h2>Zgłoszenia (<?= count($repairs) ?>)</h2>
    <?php if (empty($repairs)): ?>
        <p style="color:var(--tm);text-align:center;padding:24px">Brak zgłoszeń.</p>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr><th>RMA</th><th>Klient</th><th>Email</th><th>Urządzenie</th><th>Status</th><th>Data</th><th></th></tr>
            </thead>
            <tbody>
            <?php foreach ($repairs as $r): ?>
            <tr>
                <td><strong style="color:#fff"><?= sanitize($r['rma_number']) ?></strong></td>
                <td><?= sanitize($r['first_name'].' '.$r['last_name']) ?></td>
                <td style="color:var(--tm);font-size:12px"><?= sanitize($r['email']) ?></td>
                <td><?= sanitize($r['device_type']) ?><?= $r['device_brand'] ? ' — <span style="color:var(--tm)">'.sanitize($r['device_brand']).'</span>' : '' ?></td>
                <td><span class="status-pill" style="background:<?= $r['status_color'] ?>22;color:<?= $r['status_color'] ?>"><?= sanitize($r['status_label']) ?></span></td>
                <td style="color:var(--tm);font-size:13px"><?= date('d.m.Y', strtotime($r['created_at'])) ?></td>
                <td style="display:flex;gap:8px;align-items:center">
                    <a href="/admin/naprawa/<?= $r['id'] ?>" class="table-link">Otwórz →</a>
                    <form method="POST" action="/admin/naprawa/<?= $r['id'] ?>/usun"
                          onsubmit="return confirm('Usunąć zgłoszenie <?= sanitize($r['rma_number']) ?>? Tej operacji nie można cofnąć.')">
                        <button type="submit" class="del-btn" title="Usuń"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
