<div class="admin-stats">
    <div class="admin-stat">
        <div class="admin-stat__icon" style="background:rgba(0,229,255,0.1);border-color:rgba(0,229,255,0.2);color:#00e5ff">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div><strong><?= $stats['total'] ?></strong><span>Wszystkich zgłoszeń</span></div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__icon" style="background:rgba(59,130,246,0.1);border-color:rgba(59,130,246,0.2);color:#3b82f6">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div><strong><?= $stats['new'] ?></strong><span>Nowych zgłoszeń</span></div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__icon" style="background:rgba(249,115,22,0.1);border-color:rgba(249,115,22,0.2);color:#f97316">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
        </div>
        <div><strong><?= $stats['active'] ?></strong><span>Aktywnych napraw</span></div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__icon" style="background:rgba(34,197,94,0.1);border-color:rgba(34,197,94,0.2);color:#22c55e">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div><strong><?= $stats['clients'] ?></strong><span>Klientów</span></div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card__header">
        <h2>Ostatnie zgłoszenia</h2>
        <a href="/admin/zgloszenia" class="a-btn a-btn-secondary">Zobacz wszystkie</a>
    </div>
    <?php if (empty($recent)): ?>
        <p style="color:var(--tm);text-align:center;padding:24px">Brak zgłoszeń.</p>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>RMA</th>
                    <th>Klient</th>
                    <th>Urządzenie</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($recent as $r): ?>
            <tr>
                <td><strong style="color:#fff"><?= sanitize($r['rma_number']) ?></strong></td>
                <td><?= sanitize($r['first_name'] . ' ' . $r['last_name']) ?></td>
                <td><?= sanitize($r['device_type']) ?></td>
                <td><span class="status-pill" style="background:<?= $r['status_color'] ?>22;color:<?= $r['status_color'] ?>"><?= sanitize($r['status_label']) ?></span></td>
                <td style="color:var(--tm);font-size:12px" title="<?= date('d.m.Y H:i', strtotime($r['created_at'])) ?>"><?= formatDate($r['created_at']) ?></td>
                <td><a href="/admin/naprawa/<?= $r['id'] ?>" class="table-link">Otwórz →</a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
