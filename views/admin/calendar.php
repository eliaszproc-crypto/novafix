<div class="admin-card">
    <h2>Aktywne naprawy</h2>
    <?php if (empty($repairs)): ?>
        <p style="color:var(--tm);text-align:center;padding:24px">Brak aktywnych napraw.</p>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>RMA</th><th>Klient</th><th>Status</th><th>Ostatnia aktualizacja</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($repairs as $r): ?>
            <tr>
                <td><strong style="color:#fff"><?= sanitize($r['rma_number']) ?></strong></td>
                <td><?= sanitize($r['first_name'] . ' ' . $r['last_name']) ?></td>
                <td><span class="status-pill" style="background:<?= $r['status_color'] ?>22;color:<?= $r['status_color'] ?>"><?= sanitize($r['status_label']) ?></span></td>
                <td style="color:var(--tm)"><?= date('d.m.Y H:i', strtotime($r['updated_at'])) ?></td>
                <td><a href="/admin/naprawa/<?= $r['id'] ?>" class="table-link">Otwórz →</a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
